<?php 
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
require "../vendor/autoload.php";
header('Access-Control-Allow-Origin: *');  
require "settings.php";
date_default_timezone_set('asia/kolkata');
$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();

//DB CONTAINERS
$container['db'] = function ($c) {
    try{
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'].';charset=utf8mb4',$db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	 $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
    }
    catch(\Exception $ex){
       return $ex->getMessage();
   } 
};
$container['baseUrl'] = function ($c) {
    return  "https://stillkonfuzed.com/NewsGrabber/";
};
$container['downloads'] = function ($c) {
    return  "https://stillkonfuzed.com/NewsGrabber/downloads/";
};

//Inject not found handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
      $h = $request->getUri()->getScheme();
      $host = $request->getUri()->getHost();
      $query = $request->getUri()->getQuery();
      exit($query);
       return $response->withStatus(404)->withHeader('Content-Type', 'text/html')
            ->write('Oops! Invalid Url...');
    };
};
//Inject error handler
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong! Probably bad coding!');
    };
};


//Including all custom coded files to run
include('library.php');

$app->run();
