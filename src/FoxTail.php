<?php

namespace Sukohi\FoxTail;

class FoxTail {

    private $session_key,
            $stories,
            $story_names,
            $home_tails,
            $tail_type;

    public function __construct() {

        $this->session_key = config('fox_tail.session_key', 'fox_tails');
        $this->stories = config('fox_tail.stories', []);
        $this->story_names = array_keys($this->stories);
        $this->home_tails = config('fox_tail.home_tails', []);
        $this->tail_type = config('fox_tail.tail_type', 'route');

    }

    public function changeTails($request) {

        $this_tail_name = $this->getThisTailName($request);

        if(!empty($this->home_tails) && in_array($this_tail_name, $this->home_tails)) {

            $this->clear();

        }

        $tails = $this->getTails();
        $tail_names = $this->getTailNames();

        if(empty($this_tail_name)) {

            return;

        } else if($tail_names->contains($this_tail_name)) {

            $index = $tail_names->search($this_tail_name);
            $tails = $tails->take($index);

        }

        $tails->push([
            'name' => $this_tail_name,
            'method' => $request->method(),
            'parameters' => $request->toArray(),
            'url' => $request->url(),
            'full_url' => $request->fullurl()
        ]);

        session()->put($this->session_key, $tails);

    }

    public function getTails() {

        return collect(session($this->session_key, []));

    }

    public function getTail($tail_name, $object_flag = true) {

        $tails = $this->getTails();

        foreach ($tails as $tail) {

            if($tail['name'] == $tail_name) {

                return ($object_flag) ? (object) $tail : (array) $tail;

            }

        }

        return null;

    }

    public function getMethod($tail_name) {

        return $this->getTailValue($tail_name, 'method');

    }

    public function getUrl($tail_name) {

        return $this->getTailValue($tail_name, 'url');

    }

    public function getFullUrl($tail_name) {

        return $this->getTailValue($tail_name, 'full_url');

    }

    public function getParameters($tail_name) {

        return $this->getTailValue($tail_name, 'parameters');

    }

    private function getTailValue($tail_name, $key) {

        $tail = $this->getTail($tail_name, false);

        if(!is_null($tail)) {

            return $tail[$key];

        }

        return '';

    }

    public function getTailByStep($step = 0) {

        $i = 0;
        $tails = $this->getTails();
        $reversed = $tails->reverse();

        foreach ($reversed as $tail) {

            if($i == $step) {

                return $tail;

            }

            $i++;

        }

    }

    public function getTailNames() {

        return $this->getTails()->pluck('name');

    }

    public function getTailNameByStep($step = 0) {

        $tail_names = $this->getTailNames();
        $reversed = $tail_names->reverse()->values();
        return $reversed->get($step, '');

    }

    public function getThisTailName($request) {

        if($this->tail_type == 'uri') {

            return $request->path();

        }

        return \Route::currentRouteName();

    }

    public function isStory($story_name) {

        $tails = collect(array_get($this->stories, $story_name, []));

        if($tails->count() == 0) {

            return false;

        }

        $check_tails = $this->getTailNames()->take($tails->count()*-1);
        return $tails->values() == $check_tails->values();

    }

    public function has($tail_name) {

        $tails = $this->getTails();

        foreach ($tails as $tail) {

            if($tail['name'] == $tail_name) {

                return true;

            }

        }

        return false;

    }

    public function isPrevious($tail_name) {

        $previous_tail_name = $this->getTailNameByStep(1);
        return ($tail_name == $previous_tail_name);

    }

    public function clear() {

        session()->forget($this->session_key);

    }

}