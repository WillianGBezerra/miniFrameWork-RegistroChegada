<?php
	namespace App\Controllers;

	//Recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

    class AuthController extends Action {

        public function authenticate() {
            //echo 'chegamos até aqui';
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';

            $user = Container::getModel('User');

            $user->__set('email', $_POST['email']);
            $user->__set('password', md5($_POST['password']));

            // echo '<pre>';
            // print_r($user);
            // echo '</pre>';

            $user->authenticate();

            if($user->__get('id') !='' && $user->__get('name') != '') {
                
                session_start();

                $_SESSION['id'] = $user->__get('id');
                $_SESSION['name'] = $user->__get('name');

                header('Location: /timeline');

            } else {
                header('Location: /?login=error');
            }
        }

        public function logOut() {
            session_start();
            session_destroy();
            header('Location: /');
        }
    }

?>