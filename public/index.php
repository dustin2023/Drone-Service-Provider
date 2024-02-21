<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung;

// include composer based autoloading
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Maurerd\Webentwicklung\Controller\HomeController;
use League\Route\Router;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Maurerd\Webentwicklung\Controller\AuthController;
use Maurerd\Webentwicklung\Controller\OrderController;
use Maurerd\Webentwicklung\Controller\Api\OrderController as ApiBlogController;


$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// create a request DTO object from our global variables
$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

// create a new router
$router = new Router();
// homepage route
$router->map('GET', '/', HomeController::class . '::showHomepage');
// our routes to add an order

// routes for category-selection
$router->map('POST', '/Auftrag/{category}/form', OrderController::class . '::selectCategoryForm');
$router->map('GET', '/Auftrag/{category}', HomeController::class . '::showPackages');
//route for package-selection
$router->map('POST', '/Auftrag/{category}/package', OrderController::class . '::selectPackageForm');
// routes for order-form
$router->map('GET', '/Auftrag/Zusammenfassung/{titleUrlKey}', OrderController::class . '::show');
$router->map('POST', '/Auftrag/senden', OrderController::class . '::sendToDatabase');


$router->map('GET', '/Auftrag/{category}/{package}', OrderController::class . '::addForm');
$router->map('POST', '/Auftrag/{category}/add', OrderController::class . '::add');
// login and registration routes
$router->map('GET', '/auth/login/form', AuthController::class . '::loginForm');
$router->map('GET', '/auth/register/form', AuthController::class . '::registerForm');
$router->map('POST', '/auth/login', AuthController::class . '::login');
$router->map('POST', '/auth/register', AuthController::class . '::register');
// logout route
$router->map('GET', '/auth/logout', AuthController::class . '::logout');

$resetVersion = $_ENV['API_VERSION'];
$router->map('GET', '/api/v' .$resetVersion .'/Auftrag/{titleUrlKey}', ApiBlogController::class . '::show');

try {
    // start routing
    $response = $router->dispatch($request);
} catch (AuthenticationRequiredException $exception) {
    // react on content that cannot be found
    setcookie("Show-Login-First-Toast", "true", time()+3, "/auth/login/form");
    $response = new Response(status:302,   headers: ['Location' => '/auth/login/form']);

} catch (\Exception $exception) {
    // react on any exception which we do not catch elsewhere to not expose exception messages
    error_log(var_export($exception, true));
    setcookie("Show-Error-Toast", "true", time()+3, "/");
    $response = new Response(status: 302,headers: ['Location' => '/']);
}

// send the response to the browser
(new SapiEmitter)->emit($response);
