<?php
session_start(); // ✅ REQUIRED HERE

//model
include '../model/kagawadModel.php';

//global variable
$page['page'] = 'Kagawad';
$page['subpage'] = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

//check for an action
if (isset($_GET['function'])) {
	new ActiveKagawad($page);
} else {
	new Kagawad($page);
}


//the default class
class Kagawad
{
	//encapsulation
	private $page = '';
	private $subpage = '';
	protected $KagawadModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		$this->KagawadModel = new KagawadModel(); //instance/object

		//run the method/behaviour
		$this->{$page['subpage']}();
	}

	function dashboard()
	{
		include '../views/kagawad.php';
	}

	function concern()
	{
		include '../views/kagawad.php';
	}

	function project()
	{
		include '../views/kagawad.php';
	}

	function reports()
	{
		include '../views/kagawad.php';
	}

	function profile()
	{
		include(__DIR__ . '/../admin/profile.php');
	}
}

class ActiveKagawad
{

	private $page = '';
	private $subpage = '';
	protected $kagawadModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		$this->kagawadModel = new kagawadModel(); //instance/object

		//run the method/behaviour
		$this->{$_GET['function']}();
	}

	function addProject()
	{
		if ($this->kagawadModel->addProject($_POST)) {

			$_SESSION['message'] = "Project added successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to add Project!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/kagawad.php?subpage=project");
		exit();
	}

	function addConcern()
	{
		if ($this->kagawadModel->addConcern($_POST)) {

			$_SESSION['message'] = "Concern added successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to add Concern!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/kagawad.php?subpage=concern");
		exit();
	}

	function updateStatuses()
	{
		session_start(); // make sure session is active

		$id = $_POST['id'];
		$status = $_POST['concern_status'];
		$updated_by = $_SESSION['official_id']; // ✅ FIX HERE

		if ($this->kagawadModel->updateStatuses($id, $status, $updated_by)) {

			$_SESSION['message'] = "Status updated successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to update status!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/kagawad.php?subpage=concern");
		exit();
	}

	function updateProgram()
	{
		$id = $_POST['id'];
		$status = $_POST['status'];

		if ($this->kagawadModel->updateProgram($id, $status)) {

			$_SESSION['message'] = "Status updated successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to update status!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/kagawad.php?subpage=project");
		exit();
	}

	function deleteConcern()
	{
		$id = $_GET['id'];

		if ($this->kagawadModel->deleteConcern($id)) {

			$_SESSION['message'] = "Concern deleted successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Delete failed!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/kagawad.php?subpage=concern");
		exit();
	}

	function deleteProgram()
	{
		$id = $_GET['id'];

		if ($this->kagawadModel->deleteProgram($id)) {

			$_SESSION['message'] = "Program deleted successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Delete failed!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/kagawad.php?subpage=program");
		exit();
	}
}
