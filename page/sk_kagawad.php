<?php

//model
//include '../model/contactModel.php';

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
	// protected $treasurerModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		//$this->servicesModel = new servicesModel(); //instance/object

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

	function profile() 
	{
		include(__DIR__ . '/../admin/profile.php');
	}
}

class ActiveSk_kagawad
{

	private $page = '';
	private $subpage = '';
	// protected $contactModel = '';

	//constructor
	function __construct($page)
	{
		$this->page = $page['page']; //assigned the property value
		$this->subpage = $page['subpage']; //assigned the property value

		//$this->contactModel = new contactModel(); //instance/object

		//run the method/behaviour
		$this->{$_GET['function']}();
	}
}
