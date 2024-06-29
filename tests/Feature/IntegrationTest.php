<?php

use Phico\View\Latte\Latte;

test('can instantiate Latte', function () {

    $view = new Latte([
        'use_cache' => false,
        'cache_path' => '/tmp',
        'view_path' => 'tests/views',
    ]);

    expect($view)->toBeInstanceOf(Latte::class);

});
test('can render a Latte template from file', function ($expect, $data) {

    $view = new Latte([
        'use_cache' => false,
        'cache_path' => '/tmp',
        'view_path' => 'tests/views',
    ]);

    $out = $view->render('hello.latte', $data);

    expect(compactHtml($out))->toBe(compactHtml($expect));

})->with([

            ['<h1>Hello World</h1>' . "\n", []],
            ['<h1>Hello Bob</h1>' . "\n", ['name' => 'Bob']],
            ['<h1>Hello Humpty Dumpty</h1>' . "\n", ['name' => 'Humpty Dumpty']],

        ]);

test('can render a Latte template from string', function ($expect, $data) {

    $view = new Latte([
        'use_cache' => false,
        'cache_path' => '/tmp',
        'view_path' => 'tests/views',
    ]);

    $out = $view->string('<h1>Hello {$name ?? \'World\'}</h1>', $data);

    expect(compactHtml($out))->toBe(compactHtml($expect));

})->with([

            ['<h1>Hello World</h1>', []],
            ['<h1>Hello Bob</h1>', ['name' => 'Bob']],
            ['<h1>Hello Humpty Dumpty</h1>', ['name' => 'Humpty Dumpty']],

        ]);

