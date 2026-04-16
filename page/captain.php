<?php
session_start(); // ✅ REQUIRED HERE

// Handle POST before class instantiation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_official'])) {
	include '../model/captainModel.php';
	$tempModel = new captainModel();
	$tempModel->register();
	exit();
}
//model
include '../model/captainModel.php';

//global variable
$page['page'] = 'Captain';
$page['subpage'] = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

//check for an action
if (isset($_GET['function'])) {
	new ActiveCaptain($page);
} else {
	new Captain($page);
}


//the default class
class Captain
{
	//encapsulation
	private $page = '';
	private $subpage = '';
	protected $captainModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		$this->captainModel = new captainModel(); //instance/object

		//run the method/behaviour
		$this->{$page['subpage']}();
	}

	function dashboard()
	{
		include '../views/captain.php';
	}

	function approval()
	{
		include '../views/captain.php';
	}

	function monitor()
	{
		include '../views/captain.php';
	}

	function resident()
	{
		include '../views/secretary.php';
	}

	function term()
	{
		include '../views/captain.php';
	}

	function reports()
	{
		include '../views/kagawad.php';
	}

	function register()
	{
		$viewData = [
			'currentTerm' => $this->captainModel->getCurrentTerm(),
			'residents' => $this->captainModel->getResidents()
		];

		extract($viewData); // Makes $currentTerm and $residents available in view

		include '../views/captain.php';
	}

	function header()
	{
		include '../views/captain.php';
	}

	function profile()
	{
		include '../views/captain.php';
	}

	function finance()
	{
		include '../views/treasurer.php';
	}

	function report()
	{
		include '../views/sk_kagawad.php';
	}

	function funds()
	{
		include '../views/sk_kagawad.php';
	}
}

class ActiveCaptain
{

	private $page = '';
	private $subpage = '';
	protected $captainModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page'];
		$this->subpage = $page['subpage'];
		$this->captainModel = new captainModel();

		// Handle POST requests
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_official'])) {
			$this->register();
			return;
		}

		//run the method/behaviour
		$this->{$_GET['function']}();
	}

	function addTerm()
	{
		if ($this->captainModel->addTerm($_POST)) {

			$_SESSION['message'] = "Term added successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to add Term!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/captain.php?subpage=term");
		exit();
	}

	function updateTermStatus()
	{
		$id = $_POST['id'];
		$status = $_POST['status'];

		if ($this->captainModel->updateTermStatus($id, $status)) {

			$_SESSION['message'] = "Status updated successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to update status!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/captain.php?subpage=term");
		exit();
	}

	function updatePosition()
	{
		$id = $_POST['official_id'];
		$status = $_POST['position'];

		if ($this->captainModel->updatePosition($id, $status)) {

			$_SESSION['message'] = "Status updated successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to update status!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/captain.php?subpage=monitor");
		exit();
	}

	function deleteOfficial()
	{
		$id = $_GET['id'];

		if ($this->captainModel->deleteOfficial($id)) {

			$_SESSION['message'] = "Official deleted successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Delete failed!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/captain.php?subpage=monitor");
		exit();
	}

	function deleteTerm()
	{
		$id = $_GET['id'];

		if ($this->captainModel->deleteTerm($id)) {

			$_SESSION['message'] = "Term deleted successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Delete failed!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/captain.php?subpage=term");
		exit();
	}

	function register()
	{
		// Fixed: Pass $_POST data and use correct redirect
		if ($this->captainModel->register($_POST)) {

			$_SESSION['message'] = "Barangay Official registered successfully!";
			$_SESSION['msg_type'] = "success";
		} else {
			
			$_SESSION['message'] = "Failed to register official!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/captain.php?subpage=monitor"); // Fixed redirect
		exit();
	}
}
