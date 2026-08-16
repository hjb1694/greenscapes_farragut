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

    $app->get('/gallery', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'gallery.page.twig');
    });

    $app->get('/contact', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'contact.page.twig');
    });

    $app->post('/contact', function (Request $request, Response $response) {


        try{

            $body = $request->getParsedBody();

            $requiredFields = ['first_name','last_name','email','message'];

            foreach($requiredFields as $field){
                foreach($body as $key => $value){
                    if(!in_array($requiredFields, $key)){
                        throw new Exception('Field not set');
                        break 2;
                    }
                }
            }

        }catch(Exception $e){

        }

    });



}

?>