<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class Sales_by_dealer extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
	}

	public function sales_by_dealer()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Sales by Dealer Report";
		$data['nestedView']['cur_page'] = 'sales_by_dealer';
		$data['nestedView']['parent_page'] = 'sales_by_dealer';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';

		$data['nestedView']['css_includes'] = array();
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Sales by Dealer Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Sales by Dealer Report','class'=>'active','url'=>'');

		
		

		$this->load->view('sales_by_dealer/sales_by_dealer_report', $data);
	}
}