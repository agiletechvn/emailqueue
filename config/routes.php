<?php
use Cake\Routing\Router;

Router::plugin(
    'EmailQueue',
    ['path' => '/email-queue'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);
