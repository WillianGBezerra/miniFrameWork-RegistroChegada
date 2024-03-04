<?php
	namespace App\Controllers;

	//Recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;
	
	class IndexController extends Action {

		public function index() {
			
			$this ->view->login = isset($_GET['login']) ? $_GET['login'] : '';
			$this->render('index', 'layoutLogin'); 

		}

		public function signUp() {

			$this->view->user = array(
				'name' => '',
				'email' => '',
				'password' => '',
			);

			$this->view->registrationError = false;
			$this->render('signup', 'layoutLogin');
		}

		public function register() {

			// receber os dados do formulário
			$user = Container::getModel('User');

			$user->__set('name', $_POST['name']);
			$user->__set('email', $_POST['email']);
			$user->__set('password', md5($_POST['password']));

			//Validar se email já foi utilizado e se os dados atendem ao critérios
			if($user->validateRegistration() && count($user->getUserForEmail()) == 0) {
			
				$user->save();
				$this->render('register', 'layoutLogin');

			} else {
				//Retorna os dados caso não salve o registro
				$this->view->user = array(
					'name' => $_POST['name'],
					'email' => $_POST['email'],
					'password' => $_POST['password'],
				);
				$this->view->registrationError = true;
				$this->render('signup', 'layoutLogin');
			}
		}
	}
