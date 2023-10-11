<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Icrmctrl extends CI_Controller {

	public  function __construct() 
	{
        	parent::__construct();
		//mahesh 3rd august 2016 03:59 pm
		date_default_timezone_set('Asia/Kolkata');
	}
	
	public function home()
	{
		$data['pageTitle'] = 'Home';
		$this->load->view('icrm/homePage');
	}
	public function index()
	{
		if(!isset($_SESSION['user_id']))
		{
			header('Location: '.SITE_URL.'login');exit;		
		}		

		$data['nestedView']['heading']="iCRM Home Page ";
		$data['nestedView']['cur_page'] = 'index';
		$data['nestedView']['parent_page'] = 'home';
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['css_includes'] = array();	
		//include("inc/neo_new.php");
		$data['nestedView']['pageTitle'] = 'Home';

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Home Page';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));

		if($_SESSION['role_id'] == 14)
		{
			header('Location: '.SITE_URL.'approveLeads');exit;		
			//redirect(SITE_URL.'approveLeads');
		}
		else
		{
			$this->load->view('icrm/index', $data);
		}
	}
}