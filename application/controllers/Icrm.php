<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';
class Icrm extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
        $this->load->library('user_agent');
	}

	public function icrm_report()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Sales Vs Target Report";
		$data['nestedView']['cur_page'] = 'Sales Vs Target Report';
		$data['nestedView']['parent_page'] = 'Sales Vs Target Report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href=" '.assets_url().'js/jquery.icheck/skins/square/blue.css">';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Sales Vs Target Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Calibration Queue','class'=>'active','url'=>'');

		$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));		
		$data['users'] = $this->Common_model->get_data('user',array('status'=>1));		
		//echo '<pre>';print_r($data['users']);exit;
		$this->load->view('icrm/icrm_report', $data);
	}
	public function icrm_product()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Sales Vs Target Product";
		$data['nestedView']['cur_page'] = 'Sales Vs Target Product';
		$data['nestedView']['parent_page'] = 'Sales Vs Target Product';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/data.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href=" '.assets_url().'js/jquery.icheck/skins/square/blue.css">';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Sales Vs Target Product Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Calibration Queue','class'=>'active','url'=>'');
		$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));		
		
		$this->load->view('icrm/icrm_product', $data);
	}


}