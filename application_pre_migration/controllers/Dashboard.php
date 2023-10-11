<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';
class Dashboard extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Lead_model");
		$this->load->model("contact_model");
		$this->load->model("customer_model");
		$this->load->model("Opportunity_model");
		$this->load->model("Calendar_model");
		$this->load->model("ajax_model");
        $this->load->library('user_agent');
	}

	public function opportunityDashboard()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Opportunity Dashboard";
		$data['nestedView']['cur_page'] = 'opportunityDashboard';
		$data['nestedView']['parent_page'] = 'opportunityDashboard';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Opportunity Dashboard';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Opportunity Dashboard','class'=>'active','url'=>'');

		if($this->input->post('action')=='submit')
		{
			$user_id = $this->input->post('user_id');
			if($user_id=='')
				$user_id = $this->session->userdata('user_id');
		}
		else
		{
			$user_id = $this->session->userdata('user_id');
		}
		$data['user_id'] = $user_id;
		$data['role_id'] = getUserRole($user_id);

		$data['chartsData'] = getOpportunityDashboardData($data['user_id'], $data['role_id'], 1, 3);
		//echo $data['chartsData']; exit;

		$this->load->view('dashboard/opportunityDashboard', $data);
	}

	public function leadsDashboard()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Lead Dashboard";
		$data['nestedView']['cur_page'] = 'leadsDashboard';
		$data['nestedView']['parent_page'] = 'leadsDashboard';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Lead Dashboard';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead Dashboard','class'=>'active','url'=>'');

		if($this->input->post('action')=='submit')
		{
			$user_id = $this->input->post('user_id');
			if($user_id=='')
				$user_id = $this->session->userdata('user_id');
		}
		else
		{
			$user_id = $this->session->userdata('user_id');
		}
		$data['user_id'] = $user_id;
		$data['role_id'] = getUserRole($user_id);

		$data['chartsData'] = getLeadDashboardData($data['user_id'], $data['role_id'], 2);

		$this->load->view('dashboard/leadsDashboard', $data);
	}

	public function getOpportunityDashboardData()
	{
		$pc_region = @$_REQUEST['pc_region'];
		$timeline = @$_REQUEST['timeline'];
		$user_id = @$_REQUEST['user_id'];
		$role_id = @$_REQUEST['role_id'];
		echo getOpportunityDashboardData($user_id, $role_id, $pc_region, $timeline);
	}

	public function getLeadDashboardData()
	{
		$timeline = @$_REQUEST['timeline'];
		$user_id = @$_REQUEST['user_id'];
		$role_id = @$_REQUEST['role_id'];
		echo getLeadDashboardData($user_id, $role_id, $timeline);
	}

}