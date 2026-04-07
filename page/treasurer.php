<?php

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
			echo "<script>alert('Added Successfully'); window.location='../page/treasurer.php?subpage=finance';</script>";
		} else {
			echo "Error adding resident";
		}
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
		$id = $_POST['id'] ?? null;
		$status = $_POST['budget_status'] ?? null;

		if (!$id || !$status) {
			die("Invalid data");
		}

		if ($this->kagawadModel->updateStatus($id, $status)) {
			header("Location: ../page/treasurer.php?subpage=finance");
			exit();
		} else {
			echo "Failed to update status";
		}
	}
}
