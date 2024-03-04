<?php
namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {
		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['singUp'] = array(
			'route' => '/singUp',
			'controller' => 'indexController',
			'action' => 'signUp'
		);

		$routes['register'] = array(
			'route' => '/register',
			'controller' => 'indexController',
			'action' => 'register'
		);

		$routes['authenticate'] = array(
			'route' => '/authenticate',
			'controller' => 'AuthController',
			'action' => 'authenticate'
		);

		$routes['logOut'] = array(
			'route' => '/logOut',
			'controller' => 'AuthController',
			'action' => 'logOut'
		);

		$routes['timeline'] = array(
			'route' => '/timeline',
			'controller' => 'AppController',
			'action' => 'timeline'
		);

		$routes['invoice'] = array(
			'route' => '/invoice',
			'controller' => 'AppController',
			'action' => 'invoice'
		);

		$routes['tweet'] = array(
			'route' => '/tweet',
			'controller' => 'AppController',
			'action' => 'tweet'
		);

		$routes['deletarTweet'] = array(
			'route' => '/deletartweet',
			'controller' => 'AppController',
			'action' => 'deletarTweet'
		);

		$routes['quem_seguir'] = array(
			'route' => '/quem_seguir',
			'controller' => 'AppController',
			'action' => 'quemSeguir'
		);

		$routes['acao'] = array(
			'route' => '/acao',
			'controller' => 'AppController',
			'action' => 'acao'
		);

		$routes['uploadImg'] = array(
			'route' => '/uploadImg',
			'controller' => 'AppController',
			'action' => 'uploadImg'
		);

		$this->setRoutes($routes);
	}	
}
?>