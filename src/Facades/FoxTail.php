<?php namespace Sukohi\FoxTail\Facades;

use Illuminate\Support\Facades\Facade;

class FoxTail extends Facade {

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'fox-tail'; }

}