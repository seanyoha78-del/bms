<?php
session_start(); // ✅ REQUIRED HERE

//model
include '../model/skModel.php';

//global variable
$page['page'] = 'Sk_kagawad';
$page['subpage'] = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

	//check for an action
	if (isset($_GET['function'])) {
		new ActiveSk_kagawad($page);
	} else {
		new Sk_kagawad($page);
	}


//the default class
class Sk_kagawad
{
	//encapsulation
	private $page = '';
	private $subpage = '';
	protected $skModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		$this->skModel = new skModel(); //instance/object

		//run the method/behaviour
		$this->{$page['subpage']}();
	}

	function dashboard()
	{
		include '../views/sk_kagawad.php';
	}

	function program()
	{
		include '../views/sk_kagawad.php';
	}

	function funds()
	{
		include '../views/sk_kagawad.php';
	}

	function report() 
	{
		include '../views/sk_kagawad.php';
	}
}

class ActiveSk_kagawad
{

	private $page = '';
	private $subpage = '';
	protected $skModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		$this->skModel = new skModel(); //instance/object

		//run the method/behaviour
		$this->{$_GET['function']}();
	}

	function addProgram()
	{
		if ($this->skModel->addProgram($_POST)) {

			$_SESSION['message'] = "Program added successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to add Program!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/sk_kagawad.php?subpage=program");
		exit();
	}

	function addFund()
	{
		if ($this->skModel->addFund($_POST)) {

			$_SESSION['message'] = "Funds added successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to add Funds!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/sk_kagawad.php?subpage=funds");
		exit();
	}

	function updatePrograms()
	{
		$id = $_POST['id'];
		$status = $_POST['program_status'];

		if ($this->skModel->updatePrograms($id, $status)) {

			$_SESSION['message'] = "Status updated successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to update status!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/sk_kagawad.php?subpage=program");
		exit();
	}

	function deleteProgram()
	{
		$id = $_GET['id'];

		if ($this->skModel->deleteProgram($id)) {

			$_SESSION['message'] = "Program deleted successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Delete failed!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/sk_kagawad.php?subpage=program");
		exit();
	}
}
