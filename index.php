<?php
define ('S_NICK', TRUE);
session_start();
require_once "vendor/autoload.php";
require 'config.php';
spl_autoload_register('my_autoload'); // registering my own autoloader
header('Content-Type: text/html; charset=utf-8');
/**************************
 *  My Class Autoloader   *
 **************************/
function my_autoload ($className){
    
    $fileName = __DIR__ . DIRECTORY_SEPARATOR;
    $namespace = '';
    if ($lastNsPos = strripos($className,'\\')){
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos+1);
        $fileName .= str_replace('\\',DIRECTORY_SEPARATOR,$namespace).DIRECTORY_SEPARATOR;
    }
    $fileName .= strtolower($className) . '.php';
    //echo $fileName;
    if (file_exists($fileName)){
        require $fileName;
    } 
};/*********** END of Autoloader *****************/

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = \Controller\AController::getSlimInstance();//new \Slim\App(["settings" => $configuration]); // Main Instance of Slim/App
$container = $app->getContainer();
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Register component on container
/**************************
 *          TWIG          *
 **************************/
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('templates', [
        'cache' => FALSE,
        'charset'=>'UTF-8'
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    //var_dump ($view);
    return $view;
};
/*********** END of TWIG *****************/

/*$app->add(function (Request $request, Response $response, callable $next){
    $path = $request->getAttribute('route')->getPattern();
	if ($path == '/admin'){
        var_dump($request->getAttribute('route')->getPattern());
    }
	$response = $next($request, $response);
	$response->getBody()->write('AFTER');

	return $response;
});*/
//var_dump ($app->getContainer()->request);
$app->add(new \Libraries\AuthMiddleware(array('routeName'=>'/admin')));
					//\Libraries\Authclass::getInstance(new \Model\AModel)
										//new \Libraries\Aclclass
										//);		

/****************************************************
 *                       Routers                    *
 ****************************************************/
/*************************
*    1. Home Page        *
**************************/ 

$app->get('/[home[/{page:[0-9]+}]]', function (Request $request, Response $response, $args) use ($app){ //необязательный параметр берется в [] '/page[/{par:[0-9]+}]'
    $page = $request->getAttribute('page');
    $obj = \Controller\AController::getInstance('index'); 
    return $obj->execute(array('page' => $page));
})->setName('home');
    
/*************************
*     2. About Page      *
**************************/ 
$app->get('/about', function (Request $request, Response $response, $args){ //необязательный параметр берется в []
    $obj = \Controller\AController::getInstance('about'); //AboutController
    return $obj->execute(array('page' => $id));
})->setName('about');

/*************************
*     Services Page      *
**************************/
 
$app->get('/services[/sub_level_{id:[1-4]}]', function (Request $request, Response $response, $args){ //необязательный параметр берется в []
    $param = $request->getAttribute('id');
    $obj = \Controller\AController::getInstance('services'); //ServicesController
    return $obj->execute(array('page' => $param));
})->setName('services');

/*************************
*       Blog Page        *
**************************/

$app->group('/blog', function (){ //необязательный параметр берется в [] 
    $this->get('[/{id:[0-9]+}]', function (Request $request, Response $response, $args){ //необязательный параметр берется в []
        $id = $request->getAttribute('id');
        $obj = \Controller\AController::getInstance('blog'); //BlogController
        return $obj->execute(array('page' => $id));
    })->setName('blog');
    
    $this->get('/{art_alias}', function (Request $request, Response $response, $args){ //необязательный параметр берется в []
        $alias = $request->getAttribute('art_alias');
        $obj = \Controller\AController::getInstance('blog'); //BlogController
        return $obj->execute(array('page' => $alias));
    })->setName('blog_art_alias');  
}); 

/*************************
*     Contacts Page      *
**************************/
$app->map(['GET', 'POST'],'/contacts', function (Request $request, Response $response, $args){ //необязательный параметр берется в []
    //$myvar2 = $request->getParsedBody();
    $obj = \Controller\AController::getInstance('contacts'); //BlogController
    return $obj->execute();
})->setName('contacts');  

/*************************
*       Login Page       *
**************************/
$app->map(['GET', 'POST'],'/login', function (Request $request, Response $response, $args){ //необязательный параметр берется в []
    //$myvar2 = $request->getParsedBody();
    $obj = \Controller\AController::getInstance('login'); //LoginController
    return $obj->execute();
})->setName('login');  

/*************************
*       Admin Page       *
**************************/
$app->group('/admin', function (){ //необязательный параметр берется в [] 
    $this->get('', function (Request $request, Response $response, $args){ //необязательный параметр берется в []
        $obj = \Controller\AController::getInstance('admin'); //BlogController
        return $obj->execute();
    })->setName('admin');

});

$app->run();
    
   