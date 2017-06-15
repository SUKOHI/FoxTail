# FoxTail
A Laravel package to manage routes user accessed.
(L5.4+)

# Installation

Execute the next command.

    composer require sukohi/fox-tail:1.*
    
Set the service provider in `config/app.php`.

    'providers' => [
        ...Others...,
        Sukohi\FoxTail\FoxTailServiceProvider::class,
    ]

Also alias

    'aliases' => [
        ...Others...,
        'FoxTail'   => Sukohi\FoxTail\Facades\FoxTail::class,
    ]

Then execute the next commands.  

    php artisan vendor:publish

Now you have `config/fox_tail.php`.

# Definition

* Tail: A set of data a user will access. It contains `name(route name or uri)`, `method`, `url`, `full_url` and `parameters`.

* Story: A set of `Tails` user could access.

# Preparation

(Middleware)

You need to set FoxTailMiddleware in `\App\Http\Kernel.php` like so.

    protected $routeMiddleware = [
        ...Others...,
        'fox_tail' => \Sukohi\FoxTail\Middleware\FoxTailMiddleware::class
    ];

And also in `routes/web.php`

    Route::middleware(['fox_tail'])->group(function(){
    
        Route::get('/my/page/1', 'HomeController@mypage_1')->name('my-page-1');
        Route::get('/my/page/2', 'HomeController@mypage_2')->name('my-page-2');
        Route::get('/my/page/3', 'HomeController@mypage_3')->name('my-page-3');
    
    });

In this case, FoxTail watches `/my/page/1`, `/my/page/2` and `/my/page/3`.  
Then, it will keep `Tail` in session.

(Config)  
In `config/fox_tail.php`, you can set `Story` which means user's access history.  
See the file for the details.

# Usage

* Check if `Story` you set in the config file matches.  


    $story_name = 'how_much';
    
    if(\FoxTail::isStory($story_name)) {
    
        echo 'Match!';
    
    }

* Get all `Tails`  


    $tails = \FoxTail::getTails();   // Laravel Collection

* Get a `Tail`


    $tail = \FoxTail::getTail($tail_name);
    echo $tail->method;
    echo $tail->url;
    echo $tail->full_url;
    print_r($tail->parameters); // Array

or

    echo \FoxTail::getMethod($tail_name);
    echo \FoxTail::getUrl($tail_name);
    echo \FoxTail::getFullUrl($tail_name);
    print_r(\FoxTail::getParameters($tail_name));   // Array

* Check if FoxTail has a `Tail` or  not


    $tail_name = 'about_us';

    if(\FoxTail::has($tail_name)) {

        echo 'Has it!';
        
        $tail = \FoxTail::getTail($tail_name);

    }

* Get `Tail` and `Tail` name by step


    echo \FoxTail::getTailNameByStep();    // Current tail
    echo \FoxTail::getTailNameByStep(1);    // The previous tail tail

    print_r(\FoxTail::getTailByStep());    // Current tail by step
    print_r(\FoxTail::getTailByStep(1));    // The previous tail by step
    

License
====

This package is licensed under the MIT License.

Copyright 2017 Sukohi Kuhoh