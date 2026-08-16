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

        $validationErrors = [];

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
            $code;
            $payload;
            if($e->getMessage()){
                switch($e->getMessage()){
                    case "INVALID_REQUEST":
                        $code = 403;
                        $payload = ["error" => "INVALID_REQUEST"];
                        break;
                    case "FIELD_NOT_SET":
                        $code = 422;
                        $payload = ["error" => "MISSING_FIELD"];
                        break;
                    case "VALIDATION_ERRORS":
                        $code = 422;
                        $payload = ["error" => "VALIDATION_ERRORS", "details" => $validationErrors];
                        break;
                    case "FALSE_CAPTCHA":
                        $code = 422;
                        $payload = ["Error" => "FALSE_CAPTCHA", "details" => "Captcha Validation Failed."];
                        break;
                    default:
                        $code = 500;
                        $payload = ["error" => "SERVER_ERROR"];
                }
            }else{
                $code = 500;
                $payload = ["error" => "SERVER_ERROR"];
            }

            $response->getBody()->write(json_encode($payload));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($code);

        }
    
    });

}

?>