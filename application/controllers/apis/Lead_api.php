<?php 
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Headers: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lead_api extends CI_Controller {
	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Common_model");
		$this->load->model("Lead_model");
		$this->load->model("ajax_model");
	}

	public function newLead()
	{
		$json = file_get_contents('php://input');
		$post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
		//$post_data = json_decode($json,TRUE);
		//print_r($post_data);die;
		if($post_data != 0)
		{
			//print_r($post_data);die;
			$SourceOfLead = $this->Common_model->get_dropdown('source_of_lead', 'source_id', 'name', []);
			foreach($SourceOfLead as $key=>$value)
			{
				$s_lead[] = array('id'=>$key,'name'=>$value);
			}
			$site_readiness = $this->Common_model->get_dropdown('site_readiness', 'site_readiness_id', 'name', []);
			foreach($site_readiness as $key=>$value)
			{
				$site[] = array('id'=>$key,'name'=>$value);
			}
			
			$rapport = $this->Common_model->get_dropdown('relationship', 'relationship_id', 'name', []);
			foreach($rapport as $key=>$value)
			{
				$c_rapport[] = array('id'=>$key,'name'=>$value);
			}
			$checkRole = 0;
			if($post_data['role_id'] == 5)
				$checkRole = 1;
			$data['checkRole'] = $checkRole;
			$data=array('source_of_lead'=>$s_lead,'site_readiness'=>$site,'rapport'=>$c_rapport,'checkRole'=>$checkRole);
		}else{
			$data['response'] =0;
			$data['error']='Please enter Request Body';
		}
		echo json_encode($data);
	}

	public function getCustomer()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $_SESSION['locationString'] = $post_data['locationString'];
        $_SESSION['company'] = $post_data['company_id'];
		$val = @trim($post_data['customer_name']);
		$data['customer_list'] = getCustomerInfo($val);
		$this->session->sess_destroy();
		echo json_encode($data);
	}

	public function getCampaign()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $_SESSION['locationString'] = $post_data['locationString'];
        $_SESSION['company'] = $post_data['company_id'];
		$val = @trim($post_data['campaign']);
		$data['campaign_list'] = getCampaignInfo($val);
		$this->session->sess_destroy();
		echo json_encode($data);		
	}

	public function getColleagues()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['company'] = $post_data['company_id'];
		$val = @trim($post_data['colleagues']);
		$data['colleagues_list'] = $this->ajax_model->getColleagues($val);
		$this->session->sess_destroy();
		echo json_encode($data);		
	}

	public function getContact()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
		$customer_id = $post_data['customer_id'];
		$q = "SELECT c.contact_id, concat(c.first_name,' ',c.last_name,' - ',s.name,' (', c.mobile_no, ')' ) as cName from contact c
			INNER JOIN speciality s on s.speciality_id = c.speciality_id
			INNER JOIN customer_location_contact clc ON clc.contact_id = c.contact_id
			INNER JOIN customer cu ON cu.customer_id = clc.customer_id
			WHERE c.status = 1 AND cu.status = 1 AND clc.customer_id = '".$customer_id."'";
		$res = $this->db->query($q);
		$data['contact_list'] = $res->result_array();
		echo json_encode($data);
	}

	public function getSecondUser()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
		$customer_id = $post_data['customer_id'];
		$checkRole = $post_data['checkRole'];
		$company_id = $post_data['company_id'];
		$_SESSION['company'] = $company_id;
		$role_id = ($checkRole == 1)?4:5;

		$location_id = getCustomerLocation($customer_id);
		$locations = getAllParents($location_id);
		if($locations == '') $locations = 0;
		$q = "SELECT u.user_id, case when (r.role_id = 5) then concat(distributor_name, ' - ', u.employee_id,' (',r.name, ')') else
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name,')') end as uName from user u
			INNER JOIN user_location ul on ul.user_id = u.user_id
			INNER JOIN role r ON r.role_id = u.role_id
			LEFT JOIN distributor_details d ON d.user_id = u.user_id
			WHERE u.status = 1 and ul.status = 1 and u.company_id='".$this->session->userdata('company')."' and ul.location_id IN (".$locations.") and u.role_id IN (".$role_id.")";
		$res = $this->db->query($q);
		$data['second_user_list'] = $res->result_array();
		$this->session->sess_destroy();
		echo json_encode($data);
	}

	public function newLeadAdd()
	{
	   $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
       $_SESSION['company'] = $post_data['company_id'];
	   $_SESSION['user_id'] = $post_data['user_id'];

       $this->db->trans_begin();
	   goto start;
		start:

		$lead_str_arr = get_current_unique_numbers("lead","lead_counter","lead_id");
		$lead_counter=$lead_str_arr[0];
		$lead_number=$lead_str_arr[1];
		$company_id  = $this->session->userdata('company');
		$customer_id = $post_data['customer'];
		$location_id = getCustomerLocation($customer_id);
		$campaign_id = $post_data['campaign'];
		$user2 = $post_data['second_user'];
		$user3 = $post_data['user3'];
		$contact_id2 = $post_data['contact2'];
		$purchase_potential = $post_data['purchase_potential'];
		//$checkSelf = 1;
		$checkSelf = $post_data['checkSelf'];
		$user_id = ($checkSelf == 1)?$this->session->userdata('user_id'):$post_data['assign'];

		//$user_id =$this->session->userdata('user_id');
		$status = 2;
		
		$dataArr = array(
			'source_id'            => $post_data['source'],
			'remarks1'             => $post_data['referral'],
			'customer_id'          => $customer_id,
			'location_id'          => $location_id,
			'contact_id'           => $post_data['contact1'],
			'user_id'              => $user_id,
			'site_readiness_id'    => $post_data['site'],
			'visit_requirement'    => $post_data['visit_requirement'],
			'resource_requirement' => $post_data['resource_requirement'],
			'relationship_id'      => $post_data['relationship'],
			'remarks2'             => $post_data['remarks2'],
			'remarks3'             => $post_data['remarks3'],
			'remarks4'             => $post_data['resource_required_details'],
			'status'               => $status,
			'created_by'           => $post_data['user_id'],
			'created_time'         => date('Y-m-d H:i:s'));
		if($campaign_id != '')
		{
			$dataArr['campaign_id'] = $campaign_id;
		}
		if($user2 != '')
		{
			$dataArr['user2'] = $user2;
		}
		if($user3 != '')
		{
			$dataArr['user3'] = $user3;
		}			
		if($purchase_potential != '')
		{
			$dataArr['purchase_potential'] = $purchase_potential;
		}
		if($contact_id2 != '')
		{
			$dataArr['contact_id2'] = $contact_id2;
		}
		if($checkSelf == 0)
		{
			$dataArr['re_routed_by'] = $post_data['user_id'];
			$dataArr['re_routed_time'] = date('Y-m-d H:i:s');
		}
		try
		{
			check_unique_numbers_constraint('lead','lead_counter',$lead_counter);
		}
		catch(Exception $e)
		{
			goto start;
		}
		$dataArr['lead_counter']=$lead_counter;
		$dataArr['lead_number']=$lead_number;
		$dataArr['company_id']=$company_id;
		$lead_id = $this->Common_model->insert_data('lead',$dataArr);
		addLeadStatusHistory($lead_id, 1);
		if($status == 2) addLeadStatusHistory($lead_id, 2);
		addLeadUserHistory($lead_id, $user_id);
		$this->session->sess_destroy();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = 0;
			echo json_encode($data);
			header("Status: 404 Not Found",true,404);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = $lead_number;
			echo json_encode($data);
			header("HTTP/1.1 201 Created");
		}
	}

	public function openLeads()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $searchParams['lead_id'] = $post_data['lead_id'];
        $searchParams['customer'] = $post_data['customer'];
        $searchParams['created_user'] = $post_data['created_user'];
        $searchParams['start_date'] = $post_data['start_date'];
        $searchParams['end_date'] = $post_data['end_date'];
        $searchParams['open_status'] = $post_data['open_status'];
        $searchParams['campaign'] = $post_data['campaign_id'];
        $_SESSION['role_id'] = $post_data['role_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['reportees'] = $post_data['reportees'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords(); 
		
		$searchResults = $this->Lead_model->openLeadResults($searchParams,$config['per_page'], $current_offset);
		foreach($searchResults as $key=>$row)
		{
			$searchResults[$key]['lead_status'] = getLeadStatusID($row['lead_id']);
			if($row['campaign_id']!='')
			{
				$searchResults[$key]['campaign_name'] = $this->Common_model->get_value('campaign',array('campaign_id'=>$row['campaign_id']),'name');
			}
			if($row['contact_id2']!='')
			{
				$q = 'SELECT concat(cn.first_name," ",cn.last_name," - ",sp.name," (", cn.mobile_no, ")" ) as contact FROM contact as cn JOIN speciality as sp on sp.speciality_id = cn.speciality_id WHERE contact_id='.$row['contact_id2'];
				$res = $this->db->query($q);
				$result = $res->row_array();
				$searchResults[$key]['contact2'] = $result['contact'];
			}
		}
		$lead_status = getLeadStatusArray();
		foreach($lead_status as $key=>$value)
		{
			$status[] = array('id'=>$key,'name'=>$value);
		}
		$data['lead_status'] = $status;
		/*if(count($searchResults)===0)
		{
			$data['searchResults'] = array('id'=>"No Results Found");
			//$data['Response'] = "No Results Found";
		}
		else
		{
			$data['searchResults'] = $searchResults;
			//$data['Response'] = "OK";
		}*/
		$data['searchResults'] = $searchResults;
		$data['selected_search_results'] = $searchParams;
		
		$this->session->sess_destroy();
		echo json_encode($data);
	}

	public function updateLead()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
		$lead_id = $post_data['lead'];
		$where = array('lead_id' => $lead_id);
		$user2 = $post_data['second_user'];
		$_SESSION['user_id'] = $post_data['user_id'];
		$user2 = ($user2 != '')?$user2:NULL;
		$lead_number = $this->Common_model->get_value('lead',array('lead_id'=>$lead_id),'lead_number');
		$purchase_potential = $post_data['purchase_potential'];
		$purchase_potential = ($purchase_potential != '')?$purchase_potential:NULL;
		$this->db->trans_begin();
		$dataArr = array('visit_requirement' => $post_data['visit_requirement'],
						'resource_requirement' => $post_data['resource_requirement'],
						'user2' => $user2,
						'purchase_potential' => $purchase_potential,
						'site_readiness_id' => $post_data['site'],
						'relationship_id' => $post_data['relationship'],
						'remarks2' => $post_data['remarks2'],
						'remarks3' => $post_data['remarks3'],
						'remarks4' => $post_data['resource_required_details'],
						'modified_by' => $this->session->userdata('user_id'), 
						'modified_time' => date('Y-m-d H:i:s')
						);
		$this->Common_model->update_data('lead',$dataArr, $where);
		$this->session->sess_destroy();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = "Something Went Wrong";
			echo json_encode($data);
			header("Status: 404 Not Found",true,404);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = "Lead " .$lead_number. " Is Updated Successfully";
			echo json_encode($data);
			header("HTTP/1.1 201 OK");
		}
	}

	public function closedLeads()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $searchParams['lead_id'] = $post_data['lead_id'];
        $searchParams['customer'] = $post_data['customer'];
        $searchParams['created_user'] = $post_data['created_user'];
        $searchParams['start_date'] = $post_data['start_date'];
        $searchParams['end_date'] = $post_data['end_date'];
        $searchParams['closed_status'] = $post_data['closed_status'];
        $_SESSION['role_id'] = $post_data['role_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['reportees'] = $post_data['reportees'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords(); 
		
		
		# Search Results
	   	$searchResults = $this->Lead_model->closedLeadResults($searchParams,$config['per_page'], $current_offset);

	   	foreach($searchResults as $key=>$row)
	   	{
	   		if($row['campaign_id']!='')
			{
				$searchResults[$key]['campaign_name'] = $this->Common_model->get_value('campaign',array('campaign_id'=>$row['campaign_id']),'name');
			}
			$searchResults[$key]['status_name'] = getLeadStatus($row['status']);
			$searchResults[$key]['life_time'] = date_difference_two_days($row['created_time'],$row['modified_time']);
	   	}
	   	$data['searchResults'] = $searchResults;
	   	$data['selected_search_results'] = $searchParams;
	   	$closed_lead_status = array(22=>'Lead Closed',21=>'Lead Dropped',20=>'Lead Rejected');
	   	foreach($closed_lead_status as $key=>$value)
	   	{
	   		$closed_status[] = array('id'=>$key,'name'=>$value);
	   	}
	   	$data['closed_lead_status'] = $closed_status;
		$this->session->sess_destroy();
		echo json_encode($data);
	}

	public function leadStatusBar()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $lead_id = $post_data['lead_id'];
        $status = $post_data['status'];
		$leadS = 'success';
		$appS = 'grey';
		$opS = 'grey';
		$q1S = 'grey';
		$q2S = 'grey';
		$c1S = 'grey';
		$c2S = 'grey';
		$statusBar = '';
		$status1 = 0;
		if($status == 19) 
		{
			$status1 = 1;
			$status = getLeadOldStatus($lead_id);
		}
		if($status > 1) $appS = 'success';
		if($status > 2) $opS = 'success';
		if($status > 5) $q1S = 'success';
		if($status == 7) $q2S = 'success';		
		if($status == 8) $c1S = 'success';		
		if($status > 8)
		{
			$q2S = 'success';
			$c1S = 'success';		
		}
		if($status == 10) $c2S = 'success';	

		$statusbar['statusbar'] = array('Leaad'=>$leadS,'Approved'=>$appS,'Opportunity'=>$opS,'Quote1'=>$q1S,'Quote2'=>$q2S,'Cnote1'=>$c1S,'Cnote2'=>$c2S);
		echo json_encode($statusbar);
	}

	public function re_route_user()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $_SESSION['user_id'] = $post_data['user_id'];
		
		$this->db->trans_begin();
		$lead_id = $post_data['lead_id'];
		$lead_user_id = $post_data['re_route_from'];
		$re_route_to = $post_data['re_route_to'];
		$where = array('lead_id' => $lead_id);
		$dataArr = array('user_id' => $re_route_to,
						   're_routed_by'=>$post_data['user_id'],
						   're_routed_time'=>date('Y-m-d H:i:s'));
		// updating lead user
		$this->Common_model->update_data('lead',$dataArr,$where);
		addLeadUserHistory($lead_id, $re_route_to, $lead_user_id);
		$this->session->sess_destroy();
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = "Something Went Wrong";
			echo json_encode($data);
			header("Status: 404 Not Found",true,404);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = "Lead had been Re-routed successfully!";
			echo json_encode($data);
			header("HTTP/1.1 201 OK");
		}
	}

	public function dropLead()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
		$_SESSION['user_id'] = $post_data['user_id'];
		$this->db->trans_begin();
		$lead_id = $post_data['lead_id'];
		$where = array('lead_id' => $lead_id);
		//$user2 = $this->input->post('second_user');
		$dataArr = array('status' => 21,
						'modified_by' => $this->session->userdata('user_id'), 
						'modified_time' => date('Y-m-d H:i:s')
						);
		$this->Common_model->update_data('lead',$dataArr, $where);

		$opp = getLeadOpportunities($lead_id);
		foreach($opp as $opportunity)
		{
			$opportunity_id = $opportunity['opportunity_id'];
			$whe = array('opportunity_id' => $opportunity_id);
			$dataArr2 = array('status' => 8,
							'modified_by' => $this->session->userdata('user_id'), 
							'modified_time' => date('Y-m-d H:i:s')
							);
			$this->Common_model->update_data('opportunity',$dataArr2, $where);
			addOpportunityStatusHistory($opportunity_id, 8);
		}
		addLeadStatusHistory($lead_id, 21);
		$this->session->sess_destroy();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = "Something Went Wrong";
			echo json_encode($data);
			header("Status: 404 Not Found",true,404);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = "Lead had been Dropped successfully!";
			echo json_encode($data);
			header("HTTP/1.1 201 OK");
		}
	}

	public function closeLead()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $_SESSION['user_id'] = $post_data['user_id'];
		
		$this->db->trans_begin();
		$lead_id = $post_data['lead_id'];
		$where = array('lead_id' => $lead_id);
		$dataArr = array('status' => 22,
						'modified_by' => $this->session->userdata('user_id'), 
						'modified_time' => date('Y-m-d H:i:s')
						);
		$this->Common_model->update_data('lead',$dataArr, $where);
		addLeadStatusHistory($lead_id, 22);
		// Get open opportunities
		$opportunities = $this->Common_model->get_data('opportunity',array('lead_id'=>$lead_id,'status <='=>5));
		$drop_status = 8;
		$remarks = 'Auto dropped due to closing lead';
		foreach ($opportunities as $op_row) {
			// Drop open opportunity
			$where2 = array('opportunity_id' => $op_row['opportunity_id']);
			$data2 = array('status' => $drop_status,
						'remarks2'	=> $remarks,
						'modified_by' => $this->session->userdata('user_id'), 
						'modified_time' => date('Y-m-d H:i:s'),
						'closed_by'		=>	$this->session->userdata('user_id'), 
						'closed_time' => date('Y-m-d H:i:s')
						);
			$this->Common_model->update_data('opportunity',$data2, $where2);
			addOpportunityStatusHistory($op_row['opportunity_id'],$drop_status);
		}
		$this->session->sess_destroy();
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = "Something Went Wrong";
			echo json_encode($data);
			header("Status: 404 Not Found",true,404);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = "Lead had been Closed successfully!";
			echo json_encode($data);
			header("HTTP/1.1 201 OK");
		}
	}

}