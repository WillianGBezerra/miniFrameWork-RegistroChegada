<?php
	namespace App\Controllers;

	//Recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

    class AppController extends Action {

        public function timeline() {

			
			$this->validateAuthentication();
				
			//recuperar tweets
			$invoice = Container::getModel('Invoice');
			$prodUnit = Container::getModel('ProductionUnit');
			$DocumentType = Container::getModel('DocumentType');

			$invoice->__set('userId', $_SESSION['id']);

			$invoices = $invoice->getAll();
			$prodUnits = $prodUnit->getAll();
			$DocumentTypes = $DocumentType->getAll();

			$this->view->invoices = $invoices;
			$this->view->prodUnits = $prodUnits;
			$this->view->DocumentTypes = $DocumentTypes;

			$this->getInfoUser();

			$this->render('timeline', 'layout');	
		}

		public function getInfoUser() {
			$user = Container::getModel('User');
			$user->__set('id', $_SESSION['id']);
			$this->view->info_user = $user->getInfoUser();
			$this->view->totalInvoices = $user->getTotalInvoices();
			$this->view->totalNotPedingInvoices = $user->getTotalNotPedingInvoices();
		}

		public function invoice() {

			session_start();
				$invoice = Container::getModel('invoice');

				$invoice->__set('invoice', $_POST['invoice']);
				$invoice->__set('invoiceKey', $_POST['invoiceKey']);
				$invoice->__set('observation', $_POST['observation']);
				$invoice->__set('emissionDate',  $_POST['emissionDate']);
				$invoice->__set('productionUnitId', $_POST['productionUnitId']);
				$invoice->__set('documenttypeId', $_POST['documenttypeId']);
				$invoice->__set('statusId',1);
				$invoice->__set('userId', $_SESSION['id']);

				$invoice->save();

				header('Location: /timeline');
		}

		public function deleteInvoice() {

			session_start();
				$invoice = Container::getModel('invoice');
				$id = isset($_GET['id']) ? $_GET['id'] : '';

				$invoice->__set('id', $_POST['id']);

				$invoice->delete();

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