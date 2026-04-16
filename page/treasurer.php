<?php
session_start();
//model
include '../model/kagawadModel.php';


//global variable
$page['page'] = 'Treasurer';
$page['subpage'] = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

//check for an action
if (isset($_GET['function'])) {
	new ActiveTreasurer($page);
} else {
	new Treasurer($page);
}

//the default class
class Treasurer
{
	//encapsulation
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
		$this->{$page['subpage']}();
	}

	function dashboard()
	{
		include '../views/treasurer.php';
	}

	function budget()
	{
		include '../views/budget.php';
	}

	function finance()
	{
		include '../views/treasurer.php';
	}

	function profile()
	{
		include(__DIR__ . '/../admin/profile.php');
	}
}

class ActiveTreasurer
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

	function addBudget()
	{
		if ($this->kagawadModel->addBudget($_POST)) {

			$_SESSION['message'] = "Budget added successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to add Budget!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/treasurer.php?subpage=finance");
		exit();
	}

	function viewBudget()
	{
		$id = $_GET['id'] ?? null;

		if (!$id) {
			die("No ID found");
		}

		$budget = $this->kagawadModel->getBudgetById($id);

		include '../views/budget.php'; // or your page
	}

	function updateStatus()
	{
		$id = $_POST['id'];
		$status = $_POST['budget_status'];

		if ($this->kagawadModel->updateStatus($id, $status)) {

			$_SESSION['message'] = "Status updated successfully!";
			$_SESSION['msg_type'] = "success";
		} else {

			$_SESSION['message'] = "Failed to update status!";
			$_SESSION['msg_type'] = "danger";
		}

		header("Location: ../page/treasurer.php?subpage=finance");
		exit();
	}
}
