<?php
namespace Libraries;
defined ('S_NICK') || exit('no rights!');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class AuthMiddleware /*extends \Slim\Middleware*/ {
        
    public function __construct ($settings = array())
    {
        $defaults = array(
                    'routeName'=>'/admin'
                    );
        $this->config = array_merge($defaults,$settings);
        $this->auth = Authclass::getInstance(new \Model\AModel);
        $this->app = \Controller\AController::getSlimInstance();
    }
    
        public function __invoke($request, $response, $next)
    {
        
        $path = $request->getAttribute('route')->getPattern();
        if ($path == $this->config['routeName']){
            if(!$user = $this->auth->isUserLogin()) {
				$this->app->getContainer()->flash->addMessage('error', 'Доступ запрещен');
                $response = $next($request, $response);
                return $response->withStatus(200)->withHeader('Location', $this->app->getContainer()->router->pathFor('login'));
            }
            $response = $next($request, $response);
            return $response;
        }
        $response = $next($request, $response);

        return $response;
    }
    
}

    
    
    
    
    
	/*public function __construct ($settings = array()) //, \Libraries\Authclass $auth
    {
        $defaults = array(
						'routeName'=>'/admin'
						);
        $this->config = array_merge($defaults,$settings);
        $this->app = \Controller\AController::getSlimInstance();
        $this->auth = $auth;     
        $this->req=$this->app->getContainer()->request;
        $this->res=$this->app->getContainer()->response;
        $this->call($this->req,$this->res);   
        //var_dump($this->app->getContainer()->request);     
    }
    
	public function call($req, $res, callable $next) {
		$path = $request->getAttribute('route')->getPattern();
        if ($path == $this->config['routeName']){
            var_dump($request->getAttribute('route')->getPattern());
        }
        $response = $next($request, $response);
	

        return $response;
        //$this->app->hook('slim.before.dispatch',array($this,'onBeforeDispatch'));
		
		//$this->next->call();
	}
	
	public function onBeforeDispatch() {
		
		$resource = $this->app->router->getCurrentRoute()->getPattern();
		
		if($resource == $this->config['routeName'] ) {
			if(!$user = $this->auth->isUserLogin()) {
				$this->app->flash('error', 'Доступ запрещен');
            	$this->app->redirect($this->app->urlFor('login'));
			}
		}
	}*/
	
