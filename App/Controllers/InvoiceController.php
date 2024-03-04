<?php
	namespace App\Controllers;

	//Recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

    class AppController extends Action {

        public function timeline() {

			
			$this->validateAuthentication();
				
			//recuperar invoices
			$invoice = Container::getModel('Invoice');

			$invoice->__set('userId', $_SESSION['id']);

			$invoices = $invoice->getAll(); 
			// echo '<pre>';
			// print_r($tweets);
			// echo '</pre>';

			$this->view->invoices = $invoices;

			$this->getInfoUser();

			$this->render('timeline', 'layout');	
		}

		public function getInfoUser() {
			$user = Container::getModel('User');
			$user->__set('id', $_SESSION['id']);
			$this->view->info_user = $user->getInfoUsuario();
			$this->view->total_tweets = $user->getTotalTweets();
			$this->view->total_seguindo = $user->getTotalSeguindo();
			$this->view->total_seguidores = $user->getTotalSeguidores();
		}

		public function invoice() {

			session_start();
				$invoice = Container::getModel('Invoice');

				$invoice->__set('invoiceKey', $_POST['invoiceKey']);
				$invoice->__set('observation', $_POST['observation']);
				$invoice->__set('productionUnitId', $_POST['productionUnitId']);
				$invoice->__set('invoiceKey', $_POST['invoiceKey']);
				$invoice->__set('documenttypeId', $_POST['documenttypeId']);

				$invoice->__set('userId', $_SESSION['id']);

				$invoice->save();

				header('Location: /timeline');
		}

		public function deleteInvoice() {

			session_start();
				$tweet = Container::getModel('tweet');
				$id = isset($_GET['id']) ? $_GET['id'] : '';

				$tweet->__set('id', $_POST['id']);

				$tweet->deletar();

				header('Location: /timeline');
		}

		public function validateAuthentication() {

			session_start();
			
			if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['name']) || $_SESSION['name'] == '') {
				header('Location: /?login=error');
			}
			
		}
    }
?>