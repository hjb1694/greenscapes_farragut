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

            $firstName = $body['first_name'];
            $lastName = $body['last_name'];
            $email = $body['email'];
            $message = $body['message'];
            $phone = NULL;
            $canText = 0;

            if(grapheme_strlen($firstName) < 2 || grapheme_strlen($firstName) > 50){
                array_push($validationErrors, 'Invalid First Name');
            }

            if(grapheme_strlen($lastName) < 2 || grapheme_strlen($lastName) > 50){
                array_push($validationErrors, 'Invalid Last Name');
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150){
                array_push($validationErrors, 'Invalid Email');
            }

            if(grapheme_strlen($message) < 10 || grapheme_strlen($message) > 1000){
                array_push($validationErrors, 'Invalid Message body');
            }

            if(isset($body['phone'])){

                $phone = $body['phone'];
                $canText = $body['can_text'];

                if(!preg_match('/^\([0-9]{3}\) [0-9]{3}\-[0-9]{4}$/', $phone)){
                    array_push($validationErrors, 'Invalid Phone Number');
                }

                if($canText != 1 || $canText != 0){
                    array_push($validationErrors, 'Invalid Cantext Value');
                }

            }

            if(count($validationErrors)){
                throw new Exception('VALIDATION_ERRORS');
            }

            function sanitize($value) {
                return trim(htmlspecialchars($value));
            }

            $SAFE_firstName = sanitize($firstName);
            $SAFE_lastName = sanitize($lastName);
            $SAFE_email = sanitize($email);
            $SAFE_message = sanitize($message);
            $SAFE_phone = $phone;
            $SAFE_canText = $canText;
            

            $conn = createDBInstance();

            $stmt = $conn->prepare("INSERT INTO contact_inquiries (first_name, last_name, phone, can_text, email, message_body) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param('sssiss', $SAFE_firstName, $SAFE_lastName, $SAFE_phone, $SAFE_canText, $SAFE_email, $SAFE_message);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);


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