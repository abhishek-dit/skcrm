<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class Run_rate extends Base_controller {

    public function __construct() 
    {
        parent::__construct();
    }

    public function run_rate_projection()
    {
    	# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Run Rate Projection Dashboard";
		$data['nestedView']['cur_page'] = 'runrateDashboard';
		$data['nestedView']['parent_page'] = 'runrateDashboard';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Run Rate Projection Dashboard';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Run Rate Projection Dashboard','class'=>'active','url'=>'');

		$this->load->view('dashboard/runrateDashboard', $data);
    }

   /* public function margin_analysis()
    {
    	# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Margin Analysis Dashboard";
		$data['nestedView']['cur_page'] = 'marginDashboard';
		$data['nestedView']['parent_page'] = 'marginDashboard';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		//$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/serial-label.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Margin Analysis Dashboard';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Margin Analysis Dashboard','class'=>'active','url'=>'');

		$this->load->view('dashboard/marginDash', $data);
    }*/
}