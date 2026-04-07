<?php
session_start();
//model
include '../model/secretaryModel.php';

//global variable
$page['page'] = 'Secretary';
$page['subpage'] = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

//check for an action
if (isset($_GET['function'])) {
	new ActiveSecretary($page);
} else {
	new Secretary($page);
}


//the default class
class Secretary
{
	//encapsulation
	private $page = '';
	private $subpage = '';
	protected $secretaryModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		$this->secretaryModel = new secretaryModel(); //instance/object

		//run the method/behaviour
		$this->{$page['subpage']}();
	}

	function dashboard()
	{
		include '../views/secretary.php';
	}

	function blotter()
	{
		include '../views/secretary.php';
	}

	function resident()
	{
		include '../views/secretary.php';
	}

	function certificates()
	{
		include '../views/secretary.php';
	}

	function concern()
	{
		include '../views/kagawad.php';
	}
}

class ActiveSecretary
{

	private $page = '';
	private $subpage = '';
	protected $secretaryModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		$this->secretaryModel = new secretaryModel(); //instance/object

		//run the method/behaviour
		$this->{$_GET['function']}();
	}

	function addResident()
	{
		if ($this->secretaryModel->addResident($_POST)) {

			$_SESSION['message'] = "Resident added successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to add Resident!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/secretary.php?subpage=resident");
		exit();
	}

	function addBlotter()
	{
		if ($this->secretaryModel->addBlotter($_POST)) {

			$_SESSION['message'] = "Blotter added successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to add Blotter!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/secretary.php?subpage=blotter");
		exit();
	}
}
