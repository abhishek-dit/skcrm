<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Ajax extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("ajax_model");
        $this->load->model("Product_model");
    }

	public function cityLocation()
	{
		$val = @trim($_GET['q']);
		$data = getLocationInfo($val);
		echo json_encode($data);
	}    

	public function getCustomer()
	{
		$val = @trim($_GET['q']);
		$data = getCustomerInfo($val);
		echo json_encode($data);
	}

	public function getDecisionMakers()
	{
		$val = @trim($_GET['q']);
		$customer = @$_GET['level'];
		$data = getDecisionMakerInfo($val, $customer);
		echo json_encode($data);
	}

	public function getDecisionMakerFromLead()
	{
		$lead_id = @$_REQUEST['lead_id'];
		$customer_id = getLeadCustomerID($lead_id);
		$this->ajax_model->getDecisionMakerInfo($customer_id);
	}

	public function getLocationAndParent()
	{
		$val = @trim($_GET['q']);
		$level = $_GET['level'];
		$data = getLocationAndParent($val, $level);
		echo json_encode($data);
	}

	public function getcityFromRegion()
	{
		$val = @trim($_GET['q']);
		$level = $_GET['level'];
		$data = getcityFromRegion($val, $level);
		echo json_encode($data);
	}

	public function getCampaign()
	{
		$val = @trim($_GET['q']);
		$data = getCampaignInfo($val);
		echo json_encode($data);		
	}

	public function getContact()
	{
		$customer_id = @$_REQUEST['customer_id'];
		$this->ajax_model->getContactInfo($customer_id);
	}

	public function getChilds()
	{
		$location_id = @$_REQUEST['location_id'];
		$territory = @$_REQUEST['territory'];
		$this->ajax_model->getChilds($location_id, $territory);
	}

	public function getSecondUser()
	{
		$customer_id = @$_REQUEST['customer_id'];
		$checkRole = @$_REQUEST['checkRole'];
		$this->ajax_model->getSecondUser($customer_id, $checkRole);
	}

	public function test($a)
	{
		/*
		$product_id = ($a == 1)?177:176;
		$users = $this->Product_model->getUsersByProductCategory($a);
		if($users)
		{
			foreach($users as $user)
			{
				$upDataArr = array('user_id' => $user['user_id'], 'product_id' => $product_id, 'status' => 1);
				$this->Common_model->insert_data('user_product',$upDataArr);
				echo $this->db->last_query().'<br>';
			}
		}
		*/

		echo $this->session->userdata('locationString');
		//echo K_PATH_CACHE;
		//echo phpinfo();
		exit;
		//$locations = '878,879,880,881,882,883,884,885,886,887,888,889,890,891,892,893,894,895,896,897,898,899,900,901,902,903,904,905,906,907,908,909,910,911,912,1087,1088,1089,1090,1091,1092,1093,1094,1095,1096,1097,1098,1099,1100,1101,1102,1103,1104,1105,1106,1107,1108,1109,1110,1111,1112,1113,1114,1115,1116,1117,1118,1119,1120,1121,1418';
		//$product = 130;
		//echo $this->session->userdata('locationString');
		//echo getReporting($a);	
		//print_r(getQuoteIDByContractNote($a));
		//$data = getProbabilityForOpportunity($a);
		//echo $data
		//print_r($data);
		//echo json_encode($data);
	}

	public function getRBH()
	{
		$customer_id = @$_REQUEST['customer_id'];
		$l = getCustomerLocation($customer_id);
		$this->ajax_model->getRBHInfo($l);
	}

	public function getAutocompleteData()
	{
		$table = $_GET["table"];
		$column = $_GET["column"];
		$term = $_GET["term"];
		$data = $this->Common_model->getAutocompleteData($table, $column, $term);
		echo json_encode($data);
	}

	public function getReportees()
	{
		$customer_id = @$_REQUEST['customer_id'];
		$role_id = @$_REQUEST['role_id'];
		$r = getReporteeRoles($role_id);
		$l = getCustomerLocation($customer_id);
		$this->ajax_model->getReportees($l, $r);
	}

	public function getReportingSEAndDistributor()
	{
		$val = @trim($_GET['q']);
		$data = $this->ajax_model->getReportingSEAndDistributorInfo($val);
		echo json_encode($data);		
	}

	public function getColleagues()
	{
		$val = @trim($_GET['q']);
		$data = $this->ajax_model->getColleagues($val);
		echo json_encode($data);		
	}

	public function getReporteesWithUser()
	{
		$val = @trim($_GET['q']);
		$level = @$_REQUEST['level'];
		$data = $this->ajax_model->getReporteesWithUser($val, $level);
		echo json_encode($data);		
	}

	public function getUserProductReporteesWithUser()
	{
		$val = @trim($_GET['q']);
		$level = @$_REQUEST['level'];
		$data = $this->ajax_model->getUserProductReporteesWithUser($val, $level);
		echo json_encode($data);		
	}

	// mahesh 7th july 2:53 pm
	public function getInactiveUsersWithOpenLeads()
	{
		$val = @trim($_GET['q']);
		$data = $this->ajax_model->getInactiveUsersWithOpenLeads($val);
		echo json_encode($data);		
	}

	// mahesh 7th july 03:15 pm
	public function getActiveUsersToAssignLeads()
	{
		$val = @trim($_GET['q']);
		//$customer_id = @$_REQUEST['customer_id'];
		$data = $this->ajax_model->getActiveUsersToAssignLeads($val);
		echo json_encode($data);		
	}


}