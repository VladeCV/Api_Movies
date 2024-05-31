<?php

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\Movie\MovieAction;

return function (App $app) {
    $app->group('/api/movies', function (Group $group) {
        $group->get('', MovieAction::class . ':getLista');
        $group->post('', MovieAction::class . ':save');
    });
};
