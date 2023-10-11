<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_controller extends CI_Controller {

	public  function __construct() 
	{
        parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('global_functions');
		$this->load->library('curl_operations');
		$this->load->library("pagination");
		$this->load->model("Common_model");
		$this->Common_model->login_check();
	}
	
	# To Validate the Post URL
	function validateEditUrl($dataArray, $redirectUrl=0)
    {
        if(sizeof($dataArray)==0)
        {
        	redirect(SITE_URL.$redirectUrl);
        }
    }

}