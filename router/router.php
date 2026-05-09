<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

// require __DIR__ . "/../config/db_connection.php";
// require __DIR__ . "/../util/helpers.php";

function use_router($app) {

    
    $app->get('/', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'home.page.twig');
    });

    $app->get('/about-us', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'about.page.twig');
    });

    $app->get('/testimonials', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'testimonials.page.twig');
    });

    $app->get('/contact', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'contact.page.twig');
    });



}

?>