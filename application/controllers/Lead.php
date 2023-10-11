<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Lead extends Base_controller {

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
  
	public function newLead()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Create a New Lead";
		$data['nestedView']['cur_page'] = 'newLead';
		$data['nestedView']['parent_page'] = 'newLead';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Create a new Lead';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Create a new Lead','class'=>'active','url'=>'');

		$data['SourceOfLead'] = array(''=>'Select Source of Lead') + $this->Common_model->get_dropdown('source_of_lead', 'source_id', 'name', []);
		$data['site_readiness'] = array(''=>'Select Site Readiness Status') + $this->Common_model->get_dropdown('site_readiness', 'site_readiness_id', 'name', []);
		$data['rapport'] = array(''=>'Select an Option from Below') + $this->Common_model->get_dropdown('relationship', 'relationship_id', 'name', []);
		$checkRole = 0;
		if($this->session->userdata('role_id') == 5)
			$checkRole = 1;
		$data['checkRole'] = $checkRole;
		$this->load->view('lead/newLeadView', $data);
	}

	public function assignLeads()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Assign Lead";
		$data['nestedView']['cur_page'] = 'assignLeads';
		$data['nestedView']['parent_page'] = 'assignLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Assign Lead';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Assign Lead','class'=>'active','url'=>'');

		$checkRole = 0;
		if($this->session->userdata('role_id') == 5)
			$checkRole = 1;
		$data['checkRole'] = $checkRole;
		$data['site_readiness'] = array(''=>'Select Site Readiness Status') + $this->Common_model->get_dropdown('site_readiness', 'site_readiness_id', 'name', []);
		$data['rapport'] = array(''=>'Select an Option From Below') + $this->Common_model->get_dropdown('relationship', 'relationship_id', 'name', []);
		$this->load->view('lead/assignLeadsView', $data);
	}

	public function assignLeadAdd()
	{
		if($this->input->post('submitAssignLead') != "")
		{
			$this->db->trans_begin();
			//print_r($_POST);
			$customer_id = $this->input->post('customer');
			$location_id = getCustomerLocation($customer_id);
			$campaign_id = $this->input->post('campaign');
			$contact_id2 = $this->input->post('contact2');
			$purchase_potential = $this->input->post('purchase_potential');
			//$dataArr = $_POST[];
			$dataArr = array(
				'source_id' => 2,
				'campaign_id' => $campaign_id,
				'customer_id' => $this->input->post('customer'),
				'location_id' => $location_id,
				'contact_id' => $this->input->post('contact1'),
				'user_id' => $this->input->post('rbh'),
				'visit_requirement' => $this->input->post('visit_requirement'),
				'resource_requirement' => $this->input->post('resource_requirement'),
				//'site_readiness_id' => $this->input->post('site'),
				//'relationship_id' => $this->input->post('relationship'),
				'user2' => $this->input->post('second_user'),
				'remarks2' => $this->input->post('remarks2'),
				'remarks3' => $this->input->post('remarks3'),
				'remarks4' => $this->input->post('resource_required_details'),
				'type' => 2,
				'status' => 2,
				'created_by' => $this->session->userdata('user_id'),
				'created_time' => date('Y-m-d H:i:s'));
			if($purchase_potential != '')
			{
				$dataArr['purchase_potential'] = $purchase_potential;
			}
			if($contact_id2 != '')
			{
				$dataArr['contact_id2'] = $contact_id2;
			}
			//print_r($dataArr); die();
			//Insert
			$lead_id = $this->Common_model->insert_data('lead',$dataArr);
			addLeadStatusHistory($lead_id, 1);
			addLeadStatusHistory($lead_id, 2);
			addLeadUserHistory($lead_id, $this->input->post('rbh'));
			//print $this->db->last_query(); die();
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> There was a problem while creating a Lead!
									 </div>');
				redirect(SITE_URL.'assignLeads');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> New Lead has been created and Assigned successfully!
									 </div>');
				redirect(SITE_URL.'trackLeads');
			}	
		}	
	}

	/*
	Adding New Lead
	Lead Status 
	status =1 (CIC Approval)
	status =2 (Lead Status Approved)
	status =3 (lead is approved , oppportunity is created , but quote is not yet done)
	status=4 (opportunities created and all are dropped)
	status=5 (opportunities created and all are dropped && lost)
	status=7 (quotes are created and cnote is not created)
	status =9 (quotes are created and out of them for some quotes , cnote is created  and remaining quotes are in open)
    status =10 (for all the quotes , cnotes are created)
	status =20 (Lead Rejected)
	status = 21 (Drop Lead)
	status =22 (closed lead)
	Inactive User Lead (lead status=19 or user status=2)
	
	In this, we can assign the leads to thier reportees.
	*/
	public function newLeadAdd()
	{
		if($this->input->post('submitLead') != "")
		{   goto start;
			start:
			$this->db->trans_begin();
			$lead_str_arr = get_current_unique_numbers("lead","lead_counter","lead_id");
			$lead_counter=$lead_str_arr[0];
			$lead_number=$lead_str_arr[1];
			$company_id  = $this->session->userdata('company');
			$customer_id = $this->input->post('customer');
			$location_id = getCustomerLocation($customer_id);
			$campaign_id = $this->input->post('campaign');
			$user2 = $this->input->post('second_user');
			$user3 = $this->input->post('user3');
			$contact_id2 = $this->input->post('contact2');
			$purchase_potential = $this->input->post('purchase_potential');
			$checkSelf = $this->input->post('checkSelf');
			$user_id = ($checkSelf == 1)?$this->session->userdata('user_id'):$this->input->post('assign');
			$role_type = $this->input->post('role_type');
			//$status = ($role_type == 1)?2:1;
			$status = 2;
			//$dataArr = $_POST[];
			$dataArr = array(
				'source_id' => $this->input->post('source'),
				'remarks1' => $this->input->post('referral'),
				'customer_id' => $this->input->post('customer'),
				'location_id' => $location_id,
				'contact_id' => $this->input->post('contact1'),
				'user_id' => $user_id,
				'site_readiness_id' => $this->input->post('site'),
				'visit_requirement' => $this->input->post('visit_requirement'),
				'resource_requirement' => $this->input->post('resource_requirement'),
				'relationship_id' => $this->input->post('relationship'),
				'remarks2' => $this->input->post('remarks2'),
				'remarks3' => $this->input->post('remarks3'),
				'remarks4' => $this->input->post('resource_required_details'),
				'status' => $status,
				'created_by' => $this->session->userdata('user_id'),
				'created_time' => date('Y-m-d H:i:s'));
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
				$dataArr['re_routed_by'] = $this->session->userdata('user_id');
				$dataArr['re_routed_time'] = date('Y-m-d H:i:s');
			}
			try
			{
				check_unique_numbers_constraint('lead','lead_counter',$lead_counter);
			}
			catch(Exception $e)
			{
				//echo "hi";exit;
				goto start;
			}

			//Insert
			//$check_number = check_unique_numbers_constraint('lead','lead_number',$lead_number);
			/*if($check_number==0)
			{   
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> There was a problem while creating a Lead!
									 </div>');
				redirect(SITE_URL.'newLead');exit;

			}*/
			//echo $lead_number;exit;
			$dataArr['lead_counter']=$lead_counter;
			$dataArr['lead_number']=$lead_number;
			$dataArr['company_id']=$company_id;
			$lead_id = $this->Common_model->insert_data('lead',$dataArr);
			addLeadStatusHistory($lead_id, 1);
			if($status == 2) addLeadStatusHistory($lead_id, 2);
			addLeadUserHistory($lead_id, $user_id);
			//print $this->db->last_query(); die();
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> There was a problem while creating a Lead!
									 </div>');
				redirect(SITE_URL.'newLead');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> New Lead has been created successfully!
									 </div>');
				redirect(SITE_URL.'openLeads');
			}	
		}	
	}

	public function approveLeads()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Approve Leads";
		$data['nestedView']['cur_page'] = 'approveLeads';
		$data['nestedView']['parent_page'] = 'approveLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Approve Leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Approve Leads','class'=>'active','url'=>'');

		# Search Functionality
		$psearch=$this->input->post('searchAppLead', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'lead_id'=>$this->input->post('lead_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'lead_id'=>$this->session->userdata('lead_id'),
					  'customer'=>$this->session->userdata('customer'),
					  'created_user'=>$this->session->userdata('created_user')
							  );
			}
			else {
				$searchParams=array(
					  'lead_id'=>'',
					  'customer'=>'',
					  'created_user'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'approveLeads/'; 
		# Total Records
	    $config['total_rows'] = $this->Lead_model->leadAppTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		
		# Search Results
	   	$data['searchResults'] = $this->Lead_model->leadAppRsults($searchParams,$config['per_page'], $current_offset);
		$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);
		//$data['usersInfo'] = $this->Lead_model->getSEAndDistributorUsers();
		//$this->load->view('product/groupView', $data);

		$this->load->view('lead/approveLeadsView', $data);
	}

	public function approveLead($encoded_id)
	{
		$this->db->trans_begin();
		$lead_id=@icrm_decode($encoded_id);
		$where = array('lead_id' => $lead_id);
		$dataArr = array('status' => 2, 'approved_by' => $this->session->userdata('user_id'), 'approved_time' => date('Y-m-d H:i:s'));
		$this->Common_model->update_data('lead',$dataArr, $where);
		addLeadStatusHistory($lead_id, 2);
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There was a problem while approving a Lead!
								 </div>');
			redirect(SITE_URL.'approveLeads');
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Lead had been Approved successfully!
								 </div>');
			redirect(SITE_URL.'approveLeads');
		}	
	}

	public function rejectLead($encoded_id)
	{
		$this->db->trans_begin();
		$lead_id=@icrm_decode($encoded_id);
		$where = array('lead_id' => $lead_id);
		$dataArr = array('status' => 20, 'approved_by' => $this->session->userdata('user_id'), 'approved_time' => date('Y-m-d H:i:s'));
		$this->Common_model->update_data('lead',$dataArr, $where);
		addLeadStatusHistory($lead_id, 20);
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There was a problem while approving a Lead!
								 </div>');
			redirect(SITE_URL.'approveLeads');
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Lead had been Rejected successfully!
								 </div>');
			redirect(SITE_URL.'approveLeads');
		}	
	}

	public function editAppLead($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Edit Lead";
		$data['nestedView']['cur_page'] = 'approveLeads';
		$data['nestedView']['parent_page'] = 'approveLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit Lead';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Approve Lead','class'=>'','url'=>SITE_URL.'approveLeads');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit','class'=>'active','url'=>'');

		if(@icrm_decode($encoded_id)!='')
		{
			
			$lead_id = @icrm_decode($encoded_id);
			$data['leadDetails'] = $this->Lead_model->getApprovalLeaddata($lead_id);

	        $where = array('customer_id' => $data['leadDetails']['customer_id']);
	        $data['customer_data'] = $this->Common_model->get_data('customer', $where);
	        $data['categories'] = $this->customer_model->get_category_drop_down();
	        $data['sub_categories'] = $this->customer_model->get_sub_category_dropdown($data['customer_data'][0]['category_id']);
	        $data['city'] = $this->customer_model->getLocation($data['leadDetails']['customer_id']);

			
		}
		$data['SourceOfLead'] = array(''=>'Select Source of Lead') + $this->Common_model->get_dropdown('source_of_lead', 'source_id', 'name', []);
		$data['site_readiness'] = array(''=>'Select Site Readiness Status') + $this->Common_model->get_dropdown('site_readiness', 'site_readiness_id', 'name', []);
		$data['rapport'] = array(''=>'Select an Option from Below') + $this->Common_model->get_dropdown('relationship', 'relationship_id', 'name', []);
		
		$this->load->view('lead/editAppView', $data);
	}

	public function editApproveLead()
	{
		//print_r($_POST); die();
		if($this->input->post('approveLead') != '')
		{
			$this->db->trans_begin();
			$lead_id = $this->input->post('lead');
			$where = array('lead_id' => $lead_id);
			$user2 = $this->input->post('second_user');
			$purchase_potential = $this->input->post('purchase_potential');
			$user2 = ($user2 != '')?$user2:NULL;
			$purchase_potential = ($purchase_potential != '')?$purchase_potential:NULL;
			$dataArr = array('status' => 2, 
							'purchase_potential' => $purchase_potential,
							'visit_requirement' => $this->input->post('visit_requirement'),
							'resource_requirement' => $this->input->post('resource_requirement'),
							'site_readiness_id' => $this->input->post('site'),
							'relationship_id' => $this->input->post('relationship'),
							'remarks2' => $this->input->post('remarks2'),
							'remarks3' => $this->input->post('remarks3'),
							'user2' => $this->input->post('second_user'),
							'approved_by' => $this->session->userdata('user_id'), 
							'approved_time' => date('Y-m-d H:i:s')
							);
			$this->Common_model->update_data('lead',$dataArr, $where);
			addLeadStatusHistory($lead_id, 2);

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> There was a problem while approving a Lead!
									 </div>');
				redirect(SITE_URL.'approveLeads');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Lead had been Approved successfully!
									 </div>');
				redirect(SITE_URL.'approveLeads');
			}	
		}
		/*
		if($this->input->post('rejectLead') != '')
		{
			$lead_id = $this->input->post('lead');
			$where = array('lead_id' => $lead_id);
			$dataArr = array('status' => 10, 'approved_by' => $this->session->userdata('user_id'), 'approved_time' => date('Y-m-d H:i:s'));
			$this->Common_model->update_data('lead',$dataArr, $where);
			
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Lead had been Rejected successfully!
								 </div>');
			redirect(SITE_URL.'approveLeads');
		}
		*/
	}


	public function trackLeads()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Track Leads";
		$data['nestedView']['cur_page'] = 'trackLeads';
		$data['nestedView']['parent_page'] = 'trackLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Track Leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Track Leads','class'=>'active','url'=>'');
                
                # Search Functionality
		$psearch=$this->input->post('searchOpenLead', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'lead_id'=>$this->input->post('lead_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  'open_status' => $this->input->post('open_status', TRUE),
					  'campaign' => $this->input->post('campaign', TRUE),
					  'start_date' => $this->input->post('start_date', TRUE),
                      'end_date' => $this->input->post('end_date', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'lead_id'=>$this->session->userdata('lead_id'),
					  'customer'=>$this->session->userdata('customer'),
					  'created_user'=>$this->session->userdata('created_user'),
					  'open_status'=>$this->session->userdata('open_status'),
					  'campaign'=>$this->session->userdata('campaign'),
					  'start_date'=>$this->session->userdata('start_date'),
					  'end_date'=>$this->session->userdata('end_date')
							  );
			}
			else {
				$searchParams=array(
					  'lead_id'=>'',
					  'customer'=>'',
					  'created_user'=>'',
					  'open_status' => '',
					  'campaign' => '',
					  'start_date'=>'',
					  'end_date' => ''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'trackLeads/'; 
		# Total Records
	    $config['total_rows'] = $this->Lead_model->openLeadTotalRows($searchParams,2);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		
		# Search Results
	   	$data['searchResults'] = $this->Lead_model->openLeadResults($searchParams,$config['per_page'], $current_offset,2);
		$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		$data['s_cam'] = $this->Lead_model->getSearchCampaign(@$searchParams['campaign']);
		$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);


		$this->load->view('lead/trackLeadsView', $data);
	}


	public function openLeads()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Open Leads";
		$data['nestedView']['cur_page'] = 'openLeads';
		$data['nestedView']['parent_page'] = 'openLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Open Leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Open Leads','class'=>'active','url'=>'');

		# Search Functionality
		$psearch=$this->input->post('searchOpenLead', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'lead_id'=>$this->input->post('lead_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  'open_status' => $this->input->post('open_status', TRUE),
                                          'start_date' => $this->input->post('start_date', TRUE),
                                          'end_date' => $this->input->post('end_date', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'lead_id'=>$this->session->userdata('lead_id'),
					  'customer'=>$this->session->userdata('customer'),
					  'created_user'=>$this->session->userdata('created_user'),
					  'open_status'=>$this->session->userdata('open_status'),
                                          'start_date' => $this->session->userdata('start_date', TRUE),
                                          'end_date' => $this->session->userdata('end_date', TRUE)
							  );
			}
			else {
				$searchParams=array(
					  'lead_id'=>'',
					  'customer'=>'',
					  'created_user'=>'',
					  'open_status' => '',
                                          'start_date'=>'',
                                          'end_date'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'openLeads/'; 
		# Total Records
	    $config['total_rows'] = $this->Lead_model->openLeadTotalRows($searchParams);
	    $config['per_page'] =$this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		
		# Search Results
		   $data['searchResults'] = $this->Lead_model->openLeadResults($searchParams,$config['per_page'], $current_offset);
		  // echo $this->db->last_query();exit;
		$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);


		$this->load->view('lead/openLeadsView', $data);
	}

	public function closedLeads()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Closed Leads";
		$data['nestedView']['cur_page'] = 'closedLeads';
		$data['nestedView']['parent_page'] = 'closedLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Closed Leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Closed Leads','class'=>'active','url'=>'');

		# Search Functionality
		$psearch=$this->input->post('searchClosedLead', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'lead_id'=>$this->input->post('lead_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  'closed_status' => $this->input->post('closed_status', TRUE),
					  'start_date' => $this->input->post('start_date', TRUE),
                      'end_date' => $this->input->post('end_date', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'lead_id'=>$this->session->userdata('lead_id'),
					  'customer'=>$this->session->userdata('customer'),
					  'created_user'=>$this->session->userdata('created_user'),
					  'closed_status'=>$this->session->userdata('closed_status'),
					  'start_date'=>$this->session->userdata('start_date'),
					  'end_date'=>$this->session->userdata('end_date')
							  );
			}
			else {
				$searchParams=array(
					  'lead_id'=>'',
					  'customer'=>'',
					  'created_user'=>'',
					  'closed_status' => '',
					  'start_date'=>'',
					  'end_date' => ''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'closedLeads/'; 
		# Total Records
	    $config['total_rows'] = $this->Lead_model->closedLeadTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		
		# Search Results
	   	$data['searchResults'] = $this->Lead_model->closedLeadResults($searchParams,$config['per_page'], $current_offset);
		$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);


		$this->load->view('lead/closedLeadsView', $data);
	}


	public function closedLeadDetails($encoded_id)
	{
		$lead_id = @icrm_decode($encoded_id);
		if(checkClosedLead($lead_id) == 0)
		{
			redirect(SITE_URL.'closedLeads');
		}
		$leadStatus = getLeadStatusID($lead_id);
		$lead_number= $this->Common_model->get_value('lead',array('lead_id'=>$lead_id),'lead_number');

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Closed Leads";
		$data['nestedView']['cur_page'] = 'closedLeads';
		$data['nestedView']['parent_page'] = 'closedLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Closed Leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Closed Leads','class'=>'','url'=>SITE_URL.'closedLeads');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead ID - '.$lead_number,'class'=>'active','url'=>'');

		$data['leadStatus'] = $leadStatus;
		$data['pageDetails'] = 'Lead';
		$data['lead_id'] = $lead_id;

		$data['leadDetails'] = $this->Lead_model->getLeadData($lead_id, 2);
		$data['SourceOfLead'] = array(''=>'Select Source of Lead') + $this->Common_model->get_dropdown('source_of_lead', 'source_id', 'name', []);
		$data['site_readiness'] = array(''=>'Select Site Readiness Status') + $this->Common_model->get_dropdown('site_readiness', 'site_readiness_id', 'name', []);
		$data['rapport'] = array(''=>'Select an Option from Below') + $this->Common_model->get_dropdown('relationship', 'relationship_id', 'name', []);


        $where = array('customer_id' => $data['leadDetails']['customer_id']);
        $data['customer_data'] = $this->Common_model->get_data('customer', $where);
        $data['categories'] = $this->customer_model->get_category_drop_down();
        $data['sub_categories'] = $this->customer_model->get_sub_category_dropdown($data['customer_data'][0]['category_id']);
        $data['city'] = $this->customer_model->getLocation($data['leadDetails']['customer_id']);

        $data['checkPage'] = 0;//1 for Open Pages. 0 for Closed Pages

		$this->load->view('lead/openLeadDetailsView', $data);
		//redirect(SITE_URL.'openLeads');
	}
	//mahesh updated 28th july 12:05 pm
	public function openLeadDetails($encoded_id)
	{
		$lead_id = @icrm_decode($encoded_id);
		if(checkLead($lead_id) == 0)
		{
			redirect(SITE_URL.'openLeads');
		}

		// GET LEAD ROW BY ID
		$lead_res = $this->Common_model->get_data('lead',array('lead_id'=>$lead_id));
        $data['lead_row'] = $lead_res[0];
		$leadStatus = $lead_res[0]['status'];
		$lead_number = $this->Common_model->get_value('lead',array('lead_id'=>$lead_id),'lead_number');
		//$leadStatus = getLeadStatusID($lead_id);

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Open Leads";
		$data['nestedView']['cur_page'] = 'openLeads';
		$data['nestedView']['parent_page'] = 'openLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Open Leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Open Leads','class'=>'','url'=>SITE_URL.'openLeads');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead ID - '.$lead_number,'class'=>'active','url'=>'');

		$data['leadStatus'] = $leadStatus;
		$data['pageDetails'] = 'Lead';
		$data['lead_id'] = $lead_id;

		$data['leadDetails'] = $this->Lead_model->getLeadData($lead_id);
		$data['SourceOfLead'] = array(''=>'Select Source of Lead') + $this->Common_model->get_dropdown('source_of_lead', 'source_id', 'name', []);
		$data['site_readiness'] = array(''=>'Select Site Readiness Status') + $this->Common_model->get_dropdown('site_readiness', 'site_readiness_id', 'name', []);
		$data['rapport'] = array(''=>'Select an Option from Below') + $this->Common_model->get_dropdown('relationship', 'relationship_id', 'name', []);


        $where = array('customer_id' => $data['leadDetails']['customer_id']);
        $data['customer_data'] = $this->Common_model->get_data('customer', $where);
        $data['categories'] = $this->customer_model->get_category_drop_down();
        $data['sub_categories'] = $this->customer_model->get_sub_category_dropdown($data['customer_data'][0]['category_id']);
        $data['city'] = $this->customer_model->getLocation($data['leadDetails']['customer_id']);

        $data['checkPage'] = 1;//1 for Open Pages. 0 for Closed Pages

		$this->load->view('lead/openLeadDetailsView', $data);
		//redirect(SITE_URL.'openLeads');
	}

	public function opportunity()
	{
		$data['nestedView']['heading'] = "opportunities";
		$data['nestedView']['cur_page'] = 'opportunity';
		$data['nestedView']['parent_page'] = 'opportunity';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/manage-opportunity.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Opportunities';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Opportunities','class'=>'active','url'=>'');

		# Search Functionality
		$psearch=$this->input->post('searchOpenLead', TRUE);
		if($psearch!='') {

		$searchParams=array(
					  'opportunity_id'=>$this->input->post('opportunity_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'product_id' =>$this->input->post('product_id', TRUE),
					  'source_of_lead' =>$this->input->post('source_of_lead', TRUE),
					  'region_id' =>$this->input->post('region_id', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  'opp_status' => $this->input->post('opp_status', TRUE),
                      'opp_category' => $this->input->post('opp_category', TRUE),
                      'start_date' => $this->input->post('start_date', TRUE),
                      'end_date' => $this->input->post('end_date', TRUE),
                      'order_start_date' => $this->input->post('order_start_date', TRUE),
                      'order_end_date' => $this->input->post('order_end_date', TRUE),
                      'text_search'=>$this->input->post('text_search',TRUE),
                      'search_option'=>$this->input->post('search_option')
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'opportunity_id'=>$this->session->userdata('opportunity_id'),
					  'customer'=>$this->session->userdata('customer'),
					  'product_id'=>$this->session->userdata('product_id'),
					  'source_of_lead'=>$this->session->userdata('source_of_lead'),
					  'region_id'=>$this->session->userdata('region_id'),
					  'created_user'=>$this->session->userdata('created_user'),
					  'opp_status'=>$this->session->userdata('opp_status'),
					  'opp_category'=>$this->session->userdata('opp_category'),
                      'start_date'=>$this->session->userdata('start_date'),
                      'end_date'=>$this->session->userdata('end_date'),
                      'order_start_date'=>$this->session->userdata('order_start_date'),
                      'order_end_date'=>$this->session->userdata('order_end_date'),
                      'text_search'=>$this->session->userdata('text_search'),
                      'search_option'=>$this->session->userdata('search_option')
							  );
			}
			else {
				$searchParams=array(
					  'opportunity_id'=>'',
					  'customer'=>'',
					  'product_id' => '',
					  'source_of_lead' => '',
					  'region_id' => '',
					  'created_user'=>'',
					  'opp_status' => '',
					  'opp_category' => '',
                      'start_date'=>'',
					  'end_date' => '',
					  'order_start_date'=>'',
					  'order_end_date' => '',
					  'text_search'=>'',
					  'search_option'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$data['form_action'] = $config['base_url'] = SITE_URL.'opportunity/'; 
		# Total Records
	    $config['total_rows'] = $this->Lead_model->opportunityRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		$data['categories'] =  array(''=>'Select Category') + $this->Opportunity_model->getLoggedInUserProductCategoriesDropdown();
		
		$data['category_id'] = $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
		$data['groups'] = array(''=>'Select Group');
		$data['products'] = array(''=>'Select Product');
		$data['source_of_funds'] = $this->Common_model->get_data('source_of_funds',array());
		$data['relationship'] = $this->Common_model->get_data('relationship',array());

		//GETTING OPPORTUNITY STATUS OPTIONS
		$qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 5';
		$data['opportunity_status'] = $this->Common_model->get_query_result($qry);
		//GETTING EDIT OPPORTUNITY STATUS OPTIONS

		// changed on 26-07-2021 for distributor
		if($_SESSION['role_id'] == '5')
		{
			$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8)'; //changed on 14-06-2021 for Distributor
		}
		else
		{
			$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8,10,11,12)';
		}
		
		$data['edit_opportunity_status1'] = $this->Common_model->get_query_result($qry);

		if($_SESSION['role_id'] == '5')
		{
			$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,6,7,8)'; //changed on 14-06-2021 for Distributor
		}
		else
		{
			$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8,10,11,12)';
		}
		$data['edit_opportunity_status3'] = $this->Common_model->get_query_result($qry);
		$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,7,11,12)';
		$data['edit_opportunity_status2'] = $this->Common_model->get_query_result($qry);
		// end 

		$qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 8';
		$data['edit_opportunity_status'] = $this->Common_model->get_query_result($qry);
		
		# Search Results
		$data['searchResults'] = $this->Lead_model->opportunityResults($searchParams,$config['per_page'], $current_offset);
		   //print_r($data['searchResults']);die;
		$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);
		$data['opp_status'] = array(''=>'Select Stage') + $this->Common_model->get_dropdown('opportunity_status', 'status', 'name', 'status IN (1,2,3,4,5)');
		$data['product_id'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', [], 'concat(name, "( ", description, ")") name');
 		$leads = $this->Calendar_model->getLeadDetails(3);
 		$data['leads'] = array(''=>'Select Lead');
 		foreach ($leads as $lead) 
 		{
 			$data['leads'][$lead['lead_id']] = "Lead ID - ".$lead['lead_number']." (".$lead['CustomerName'].")";
 		}
 		$data['encoded_id'] = 0;
		$data['pageInfo'] = 1;
		$data['check'] = 1;

		// get source of lead options
		$data['source_of_leads'] = $this->Common_model->get_data('source_of_lead',array('status'=>1));
		// get regions
		$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));

		// added on 01-07-2021 for distributor role
		foreach ($data['searchResults'] as $key => $u_role_id) {
			$qry = 'SELECT role_id FROM user WHERE user_id="'.$u_role_id['created_by'].'"';
			$data['u_role_id'] = $this->Common_model->get_query_result($qry);
		}
		// added on 01-07-2021 for distributor role end
		
		//print_r($regions); exit;
		$this->load->view('lead/opportunityView', $data);
	}

	public function opportunityClosed()
	{
		$data['nestedView']['heading'] = "Closed Opportunities";
		$data['nestedView']['cur_page'] = 'opportunityClosed';
		$data['nestedView']['parent_page'] = 'opportunityClosed';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/manage-opportunity.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Closed Opportunities';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Closed Opportunities','class'=>'active','url'=>'');

		# Search Functionality
		$psearch=$this->input->post('searchOpenLead', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'opportunity_id'=>$this->input->post('opportunity_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'product_id' =>$this->input->post('product_id', TRUE),
					  'source_of_lead' =>$this->input->post('source_of_lead', TRUE),
					  'region_id' =>$this->input->post('region_id', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  'opp_status' => $this->input->post('opp_status', TRUE),
					  'opp_category' => $this->input->post('opp_category', TRUE),
                      'start_date' => $this->input->post('start_date', TRUE),
                      'end_date' => $this->input->post('end_date', TRUE),
                      'order_start_date' => $this->input->post('order_start_date', TRUE),
                      'order_end_date' => $this->input->post('order_end_date', TRUE),
                      'text_search'=>$this->input->post('text_search',TRUE),
                      'search_option'=>$this->input->post('search_option')
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'opportunity_id'=>$this->session->userdata('opportunity_id'),
					  'customer'=>$this->session->userdata('customer'),
					  'product_id'=>$this->session->userdata('product_id'),
					  'source_of_lead'=>$this->session->userdata('source_of_lead'),
					  'region_id'=>$this->session->userdata('region_id'),
					  'created_user'=>$this->session->userdata('created_user'),
					  'opp_status'=>$this->session->userdata('opp_status'),
					  'opp_category'=>$this->session->userdata('opp_category'),
                      'start_date' => $this->session->userdata('start_date', TRUE),
                      'end_date' => $this->session->userdata('end_date', TRUE),
                      'order_start_date' => $this->session->userdata('order_start_date', TRUE),
                      'order_end_date' => $this->session->userdata('order_end_date', TRUE),
                       'text_search'=>$this->session->userdata('text_search'),
                      'search_option'=>$this->session->userdata('search_option')
							  );
			}
			else {
				$searchParams=array(
					  'opportunity_id'=>'',
					  'customer'=>'',
					  'product_id' => '',
					  'source_of_lead' => '',
					  'region_id' => '',
					  'created_user'=>'',
					  'opp_status' => '',
					  'opp_category' => '',
					  'start_date' => '',
					  'end_date' => '',
					  'order_start_date' => '',
					  'order_end_date' => '',
					  'text_search'=>'',
					  'search_option'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		$data['category_id'] = $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));

		/* pagination start */
		$config = get_paginationConfig();
		$data['form_action'] = $config['base_url'] = SITE_URL.'opportunityClosed/'; 
		# Total Records
	    $config['total_rows'] = $this->Lead_model->opportunityRows($searchParams, 2);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		
		$data['pageInfo'] = 0;
		$data['check'] = 2;
		# Search Results
	   	$data['searchResults'] = $this->Lead_model->opportunityResults($searchParams,$config['per_page'], $current_offset, 2);
	   	
		$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);
		$data['opp_status'] = array(''=>'Select Stage') + $this->Common_model->get_dropdown('opportunity_status', 'status', 'name','status IN (6,7,8)');
		$data['product_id'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', [], 'concat(name, "( ", description, ")") name');

		// get source of lead options
		$data['source_of_leads'] = $this->Common_model->get_data('source_of_lead',array('status'=>1));
		// get regions
		$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));

		// added on 01-07-2021 for distributor role
		foreach ($data['searchResults'] as $key => $u_role_id) {
			$qry = 'SELECT role_id FROM user WHERE user_id="'.$u_role_id['created_by'].'"';
			$data['u_role_id'] = $this->Common_model->get_query_result($qry);
		}
		// added on 01-07-2021 for distributor role end

		$this->load->view('lead/opportunityView', $data);
	}


	public function updateLead()
	{
		if($this->input->post('updateLead') != '')
		{
			$lead_id = $this->input->post('lead');
			$where = array('lead_id' => $lead_id);
			$user2 = $this->input->post('second_user');
			$user2 = ($user2 != '')?$user2:NULL;
			$purchase_potential = $this->input->post('purchase_potential');
			$purchase_potential = ($purchase_potential != '')?$purchase_potential:NULL;
			$dataArr = array('visit_requirement' => $this->input->post('visit_requirement'),
							'resource_requirement' => $this->input->post('resource_requirement'),
							'user2' => $user2,
							'purchase_potential' => $purchase_potential,
							'site_readiness_id' => $this->input->post('site'),
							'relationship_id' => $this->input->post('relationship'),
							'remarks2' => $this->input->post('remarks2'),
							'remarks3' => $this->input->post('remarks3'),
							'remarks4' => $this->input->post('resource_required_details'),
							'modified_by' => $this->session->userdata('user_id'), 
							'modified_time' => date('Y-m-d H:i:s')
							);
			$this->Common_model->update_data('lead',$dataArr, $where);

			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Lead had been Updated successfully!
								 </div>');
			redirect(SITE_URL.'openLeadDetails/'.@icrm_encode($lead_id));
		}	
	}

	public function dropLead()
	{
		//print_r($_POST); die();
		if($this->input->post('dropLead') != '')
		{
			$this->db->trans_begin();
			$lead_id = $this->input->post('lead_id');
			$where = array('lead_id' => $lead_id);
			$user2 = $this->input->post('second_user');
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
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> There was a problem while Dropping the Lead!
									 </div>');
				redirect($this->agent->referrer());
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Lead had been Dropped successfully!
									 </div>');
				redirect(SITE_URL.'closedLeads');
			}
		}	
	}

	public function closeLead()
	{
		//print_r($_POST); die();
		if($this->input->post('closeLead') != '')
		{
			$this->db->trans_begin();
			$lead_id = $this->input->post('lead_id');
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

			if($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> There was a problem while Dropping the Lead!
									 </div>');
				redirect($this->agent->referrer());
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Lead had been Closed successfully!
									 </div>');
				redirect(SITE_URL.'closedLeads');
			}
		}	
	}	

	// mahesh 7th july 10:50 am
	public function assignInactiveUserLeads()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Assign Inactive User leads";
		$data['nestedView']['cur_page'] = 'assignInactiveUserLeads';
		$data['nestedView']['parent_page'] = 'assignInactiveUserLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/assignInactiveUserLeads.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Assign Inactive User leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Assign Inactive User leads','class'=>'active','url'=>'');

		# Search Functionality
		$psearch=$this->input->post('searchInactiveUserLeads', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'lead_id'=>$this->input->post('lead_id', TRUE),
					  'lead_number'=>$this->input->post('lead_number', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  's_region'=>$this->input->post('s_region', TRUE),
					  's_state'=>$this->input->post('s_state', TRUE),
					  's_district'=>$this->input->post('s_district', TRUE),
					  's_city'=>$this->input->post('s_city', TRUE),
					  'open_status' => $this->input->post('open_status', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'lead_id'=>$this->session->userdata('lead_id'),
					  'lead_number'=>$this->session->userdata('lead_number'),
					  'customer'=>$this->session->userdata('customer'),
					  'created_user'=>$this->session->userdata('created_user'),
					  's_region'=>$this->session->userdata('s_region'),
					  's_state'=>$this->session->userdata('s_state'),
					  's_district'=>$this->session->userdata('s_district'),
					  's_city'=>$this->session->userdata('s_city'),
					  'open_status'=>$this->session->userdata('open_status')
							  );
			}
			else {
				$searchParams=array(
					  'lead_id'=>'',
					  'lead_number'=>'',
					  'customer'=>'',
					  'created_user'=>'',
					  's_region'=>'',
					  's_state'=>'',
					  's_district'=>'',
					  's_city'=>'',
					  'open_status' => ''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'assignInactiveUserLeads/'; 
		# Total Records
	    $config['total_rows'] = $this->Lead_model->inActiveUserLeadsTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		
		# Search Results
	   	$data['searchResults'] = $this->Lead_model->inActiveUserLeadsResults($searchParams,$config['per_page'], $current_offset);
		$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);

		$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
		$data['states'] = $data['districts'] = $data['cities'] = array();
		if($searchParams['s_region']!='')
		{
			$data['states'] = $this->Common_model->get_data('location',array('parent_id'=>$searchParams['s_region'],'status'=>1));
		}
		if($searchParams['s_state']!='')
		{
			$data['districts'] = $this->Common_model->get_data('location',array('parent_id'=>$searchParams['s_state'],'status'=>1));
		}
		if($searchParams['s_district']!='')
		{
			$data['cities'] = $this->Common_model->get_data('location',array('parent_id'=>$searchParams['s_district'],'status'=>1));
		}
		$this->load->view('lead/assignInActiveUserLeadsView', $data);
	}

	//mahesh 7th july 2016 5:19 pm, updated on 17th july 2016 08:32 PM
	public function submit_assignInactiveUserLeads(){

		//exit;
		$assign_user = $this->input->post('assign_user');
		$leads = $this->input->post('lead');
		$lead_user = $this->input->post('lead_user');
		$lead_st = $this->input->post('st');
		if(count($leads)>0){
			$this->db->trans_begin();
			foreach ($leads as $lead_id) {
				$lead_user_id = $lead_user[$lead_id];
				$lead_old_status = getLeadOldStatus($lead_id);
				$lead_where = array('lead_id'=>$lead_id);
				$lead_data = array('user_id'=>$assign_user,
								   're_routed_by'=>$this->session->userdata('user_id'),
								   're_routed_time'=>date('Y-m-d H:i:s')
								   );
				if($lead_st[$lead_id]==19){
					$lead_data['status']=$lead_old_status;
					//Inserting lead status history
					$lh_data = array('lead_id'=>$lead_id,
							 'status'=>$lead_old_status,
							 'created_by'=>$this->session->userdata('user_id'),
							 'created_time'=>date('Y-m-d H:i:s')
							 );
					$this->Common_model->insert_data('lead_status_history',$lh_data);
				}

				
				// updating lead user
				$this->Common_model->update_data('lead',$lead_data,$lead_where);
				// updating lead user history
				addLeadUserHistory($lead_id, $assign_user, $lead_user_id);
				/*
				$lead_history_where = array('lead_id'=>$lead_id,'user_id'=>$lead_user_id);
				$lead_history_data = array('end_time'=>date('Y-m-d H:i:s'),'changed_by'=>$this->session->userdata('user_id'));
				$this->Common_model->update_data('lead_user_history',$lead_history_data,$lead_history_where);
				// Insertig lead user history
				$lead_user_history_data = array('lead_id'=>$lead_id,'user_id'=>$assign_user,'start_time'=>date('Y-m-d H:i:s'));
				$this->Common_model->insert_data('lead_user_history',$lead_user_history_data);
				*/
			}

			if ($this->db->trans_status() === FALSE)
			{
					$this->db->trans_rollback();
					$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> There\'s a problem occured while assigning leads to user!
									 </div>');
				redirect(SITE_URL.'assignInactiveUserLeads');
					
			}
			else
			{
				$this->db->trans_commit();
				//GETTING USER DETAILS
				$this->db->Select("concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name, ')') as cName");
				$this->db->from('user u');
				$this->db->join('role r','u.role_id=r.role_id','inner');
				$this->db->where('u.user_id',$assign_user);
				$res = $this->db->get();
				$user = $res->row_array();
				//sorting lead IDS
				sort($leads);
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Leads <strong>'.implode(',', $leads).'</strong> has been assigned to user <strong>'.@$user['cName'].'</strong> successfully!
									 </div>');
				redirect(SITE_URL.'assignInactiveUserLeads');
			}
		}
		else{
			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> No leads has been assigned!
									 </div>');
				redirect(SITE_URL.'assignInactiveUserLeads');
		}
	}


	 //mahesh 15th july 2016 00:56 PM
    // Phase2 update: Prasad 27th july 2017 start
    public function download_opportunities()
    {
        if($this->input->post('downloadOpportunity')!='') {
            
            $searchParams=array(
					  'opportunity_id'=>$this->input->post('opportunity_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'product_id' =>$this->input->post('product_id', TRUE),
					  'source_of_lead' =>$this->input->post('source_of_lead', TRUE),
					  'region_id' =>$this->input->post('region_id', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  'opp_status' => $this->input->post('opp_status', TRUE),
					  'opp_category' => $this->input->post('opp_category', TRUE),
                      'start_date' => $this->input->post('start_date', TRUE),
                      'end_date' => $this->input->post('end_date', TRUE),
                      'order_start_date' => $this->input->post('order_start_date', TRUE),
                      'order_end_date' => $this->input->post('order_end_date', TRUE),
                      'text_search'=>$this->input->post('text_search',TRUE),
                      'search_option'=>$this->input->post('search_option')
					 		  );
            $status = $this->input->post('check');
            $results = $this->Lead_model->download_opportunityResults($searchParams,$status);
            
            $header = '';
            $data ='';
            /*phase2 changes modified by prasad */
            if($status==1) {
                $titles = array('Opporunity ID','Lead Id','Customer','Customer Location','Source of Lead','Site Readiness','Source of Fund','Lead Owner','Owner Emp Id','Owner Designation','Product Category','Segment','Product Code','Product Description', 'Quantity', 'Value (in Lakhs)', 'Region','Stage','Category','Expected Order Conclusion Date','Outdated','Probability Of Winning','Decision Maker1','Decision Maker1 - Speciality','Created On','Lead Life Time','Opp Life Time');
            }
            else {
               $titles = array('Opporunity ID','Lead Id','Customer','Customer Location','Source of Lead','Site Readiness','Source of Fund','Lead Owner','Owner Emp Id','Owner Designation','Product Category','Segment','Product Code','Product Description', 'Quantity', 'Value (in Lakhs)', 'Region','Stage','Reason','Decision Maker1','Decision Maker1 - Speciality','Created On','Closed On','Lead Life Time','Opp Life Time','Order Status');
            }
            $data = '<table border="1">';
            $data.='<thead>';
            $data.='<tr>';
            foreach ( $titles as $title)
            {
                $data.= '<th>'.$title.'</th>';
            }
            $data.='</tr>';
            $data.='</thead>';
            $data.='<tbody>';
             $j=1;
            if(count($results)>0)
            {
                
                foreach($results as $row)
                {    $contact = get_contract_user_name($row['decision_maker1']);
                    $data.='<tr>';
                    $data.='<td>'.$row['opp_number'].'</td>';
                    $data.='<td>'.$row['lead_number'].'</td>';
                    $data.='<td>'.$row['lead_name'].'</td>';
                    $data.='<td>'.$row['lead_location'].'</td>';
                    $data.='<td>'.$row['source'].'</td>';
                    $data.='<td>'.$row['readiness'].'</td>';
                    $data.='<td>'.$row['source_of_fund'].'</td>';
                    $data.='<td>'.$row['user_name'].'</td>';
                    $data.='<td>'.$row['emp_id'].'</td>';
                    $data.='<td>'.$row['role_name'].'</td>';
                    $data.='<td>'.$row['product_category'].'</td>';
                     $data.='<td>'.$row['segment'].'</td>';
                    $data.='<td>'.$row['product_name'].'</td>';
                    $data.='<td>'.$row['pro_des'].'</td>';
                    $data.='<td>'.$row['required_quantity'].'</td>';
                    $data.='<td>'.valueInLakhs(($row['required_quantity']*$row['dp']),2).'</td>';
                    $data.='<td>'.@getRegionNameFromCity($row['location_id']).'</td>';
                    $data.='<td>'.$row['stage'].'</td>';
			if($status != 1)
                    $data.='<td>'.$row['remarks2'].'</td>';
                    if($status==1){
                    	$probability = getProbabilityForOpportunity($row['opportunity_id']);
                    	$data.='<td>'.@getOpportunityCategory(@$row['status'], @$row['oDate']).'</td>';
                    	$data.='<td>'.@DateFormat($row['oDate']).'</td>';
                    	$expected_date=strtotime($row['oDate']);
                    	$current_date=strtotime(date('Y-m-d'));
                    	if($current_date>$expected_date)
                    	{
                    		$outdated='Yes';
                    	}
                    	else
                    	{
                    		$outdated='No';
                    	}
                    	 $data.='<td>'.@$outdated.'</td>';
                        $data.='<td>'.@$probability.' % </td>';
                    }
                    $data.='<td>'.$contact['name'].'</td>';
                    $data.='<td>'.$contact['speciality'].'</td>';
                    $data.='<td>'.@$row['created_time'].'</td>';
                    if($status != 1)
                    {
                    	$cdata = get_opportunity_closed_data($row['opportunity_id']);
                    	$data.='<td>'.@$cdata['created_time'].'</td>';
                	}
                	$data.='<td>'.lead_opp_status($row['lCTime'],$row['lead_mtime'],$row['lead_status']).'</td>';
                	$data.='<td>'.get_opp_life_time($row['oCTime'],$row['opp_mtime'],$row['status']).'</td>';
                	if($status != 1)
                	{
                		$order_status = '';
                		if($row['status']==6) // Closed won
                		{
                			$order_status = ($row['so_number']!='')?'Invoiced':'Open Order';
                		}
                		$data.='<td>'.@$order_status.'</td>';
                	}
                    $data.='</tr>';
                    $j++;
                }
            }
            else
            {
                $data.='<tr><td colspan="'.(count($titles)).'" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile='opportunities_'.$time.'.xls'; 
            header("Content-type: application/x-msdownload"); 
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=".$xlFile."");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
            
        }
    }

    //mahesh 15th july 2016 08:26 PM
    public function download_openLeads()
	{
		if($this->input->post('downloadLeads')!='') {
			
			$searchParams=array(
					  'lead_id'=>$this->input->post('lead_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'campaign'=>$this->input->post('campaign', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  'open_status' => $this->input->post('open_status', TRUE),
                                          'start_date' => $this->input->post('start_date', TRUE),
                                          'end_date' => $this->input->post('end_date', TRUE)
					 		  );
                        
			$source_id = $this->input->post('source_id', TRUE);
			$results = $this->Lead_model->all_openLeadResults($searchParams,$source_id);
			$header = '';
			$data ='';
			if($source_id == 1)
				$titles = array('Lead ID','Customer','Contact Person','Owner','Associated User','Lead Status','Life Time(Days)','Created Date','Created By');
			else
				$titles = array('Lead ID','Customer','Contact Person','Owner','campaign Name','Associated User','Lead Status','Life Time(Days)','Created Date','Created By');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($results)>0)
			{
				
				foreach($results as $row)
				{
					$data.='<tr>';
					$data.='<td valign="top">'.@$row['lead_number'].'</td>';
					$data.='<td valign="top">'.@$row['customer'].'</td>';
					$data.='<td valign="top">'.@$row['contact'].'</td>';
					$data.='<td valign="top">'.getUserName(@$row['user_id']).'</td>';
					if($source_id == 2)
						$data.='<td valign="top">'.getCampaignName(@$row['campaign_id']).'</td>';
					$data.='<td valign="top">'.getUserName(@$row['user2']).'</td>';
					$data.='<td valign="top">'.getLeadStatus(@$row['status']).'</td>';
					$data.='<td valign="top">'.date_difference_two_days($row['created_time'],date('Y-m-d')).'</td>';
                                        $data.='<td valign="top">'.@$row['created_time'].'</td>';
                                        $data.='<td valign="top">'.getUserName(@$row['created_by']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$file_name = ($source_id==2)?'trackLeads':'openLeads';
			$xlFile=$file_name.'_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	//mahesh 15th july 2016 09:40 PM
    public function download_closedLeads()
	{
       
		if($this->input->post('downloadLeads')!='') {
			
			$searchParams=array(
					  'lead_id'=>$this->input->post('lead_id', TRUE),
					  'customer'=>$this->input->post('customer', TRUE),
					  'created_user'=>$this->input->post('created_user', TRUE),
					  'closed_status' => $this->input->post('closed_status', TRUE),
					  'start_date' => $this->input->post('start_date', TRUE),
                      'end_date' => $this->input->post('end_date', TRUE)
					 		  );
			$source_id = $this->input->post('source_id', TRUE);
			$results = $this->Lead_model->all_closedLeadResults($searchParams,$source_id);
			
			$header = '';
			$data ='';
			$titles = array('Lead ID','Source Of Lead','Customer','Contact Person','Owner','Associated User','Created By','Life Time(Days)','Created Time','Lead Status');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($results)>0)
			{
				
				foreach($results as $row)
				{
					$life_time=date_difference_two_days($row['created_time'],$row['modified_time']);
					$data.='<tr>';
					$data.='<td valign="top">'.@$row['lead_number'].'</td>';
					$data.='<td valign="top">'.@$row['source'].'</td>';
					$data.='<td valign="top">'.@$row['customer'].'</td>';
					$data.='<td valign="top">'.@$row['contact'].'</td>';					
					$data.='<td valign="top">'.getUserName(@$row['user_id']).'</td>';
					$data.='<td valign="top">'.getUserName(@$row['user2']).'</td>';
					$data.='<td valign="top">'.@$row['user'].'</td>';
					$data.='<td valign="top">'.$life_time.'</td>';
					$data.='<td valign="top">'.DateFormatAM(@$row['created_time']).'</td>';
					$data.='<td valign="top">'.getLeadStatus(@$row['status']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='closedLeads_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}
	
	//mahesh 16th july 2016 3:59 PM
	public function edit_orderConclusionDate()
	{
		$data['nestedView']['heading'] = "Edit Order Conclusion Date";
		$data['nestedView']['cur_page'] = 'editorderconclusiondate';
		$data['nestedView']['parent_page'] = 'editorderconclusiondate';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/manage-opportunity.js"></script>';

		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit Order Conclusion Date';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Order Conclusion Date','class'=>'active','url'=>'');
		
		# Search Results
	   	$data['searchResults'] = $this->Lead_model->edit_orderConclusionResults();

		$data['encoded_id'] = 0;
		$data['pageInfo'] = 1;
		$data['check'] = 1;
		$this->load->view('lead/edit_orderConclusionDate', $data);
	}

	// mahesh 16th july 2016 04:57 PM
	public function update_orderConclusionDate(){

		if($this->input->post('update_details')!=''){
			$opids = $this->input->post('opids');
			if($opids){
				foreach ($opids as $op_id) {
					$conclusion_date = $this->input->post('conclusion_date_'.$op_id);
					$this->Common_model->update_data('opportunity',array('expected_order_conclusion'=>$conclusion_date),array('opportunity_id'=>$op_id));
				}

				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Expected order conclusion dates has been updated successfully!
									 </div>');
				
			}


		}
		//exit;
		redirect(SITE_URL.'edit_orderConclusionDate');
	}

	public function re_route_user()
	{
		if($this->input->post('submitReRoute',TRUE) != '')
		{
			if($this->input->post('re_route_to',TRUE) > 0)
			{
				$this->db->trans_begin();
				$lead_id = $this->input->post('reroute_lead',TRUE);
				$lead_user_id = $this->input->post('lead_user_id',TRUE);
				$re_route_to = $this->input->post('re_route_to',TRUE);
				$where = array('lead_id' => $lead_id);
				$dataArr = array('user_id' => $re_route_to,
								   're_routed_by'=>$this->session->userdata('user_id'),
								   're_routed_time'=>date('Y-m-d H:i:s'));
				// updating lead user
				$this->Common_model->update_data('lead',$dataArr,$where);
				addLeadUserHistory($lead_id, $re_route_to, $lead_user_id);
				if($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											<div class="icon"><i class="fa fa-check"></i></div>
											<strong>Error!</strong> There was a problem while re-routing the Lead!
										 </div>');
					redirect($this->agent->referrer());
				}
				else
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											<div class="icon"><i class="fa fa-check"></i></div>
											<strong>Success!</strong> Lead had been Re-routed successfully!
										 </div>');
					redirect($this->agent->referrer());
				}


			}
		}
	}

	// mahesh 4th Mar 2017 
	public function editRejectedLead($encoded_id)
	{
		$lead_id = @icrm_decode($encoded_id);
		if(checkClosedLead($lead_id) == 0)
		{
			redirect(SITE_URL.'closedLeads');
		}
		$leadStatus = getLeadStatusID($lead_id);

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Edit Lead";
		$data['nestedView']['cur_page'] = 'editRejectedLeadLead';
		$data['nestedView']['parent_page'] = 'closedLeads';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit Rejected Lead';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Rejected Lead','class'=>'','url'=>SITE_URL.'closedLeads');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead ID - '.$lead_id,'class'=>'active','url'=>'');

		$data['leadStatus'] = $leadStatus;
		$data['pageDetails'] = 'Lead';
		$data['lead_id'] = $lead_id;

		$data['leadDetails'] = $this->Lead_model->getLeadData($lead_id, 2);
		$data['contact_data'] = $this->Common_model->get_data('contact',array('contact_id'=>$data['leadDetails']['contact_id']));
		$data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
		//echo '<pre>'; print_r($data['leadDetails']); print_r($contact_data); exit;
        $data['checkPage'] = 1;//1 for Open Pages. 0 for Closed Pages
        $data['encoded_id'] = $encoded_id;
		$this->load->view('lead/editLeadDetailsView', $data);
		//redirect(SITE_URL.'openLeads');
	}

	// mahesh 4th mar 2017
	public function updateRejectedLead()
	{
		if($this->input->post('updateLead') != "")
		{
			$this->db->trans_begin();
			$encoded_id = $this->input->post('encoded_id');
			$lead_id = @icrm_decode($encoded_id);
			if(checkClosedLead($lead_id) == 0)
			{
				redirect(SITE_URL.'closedLeads');
			}
			//print_r($_POST);die();
			$contact_id = $this->input->post('contact_id');

			$isd1 = $this->input->post('isd1');
            $isd2 = $this->input->post('isd2');
            $telephone_no = $isd1 . "-" . $this->input->post('telephone');
            $mobile_no = $isd2 . "-" . $this->input->post('mobile_no');
            $c_dataArr = array(
                'telephone' => $telephone_no,
                'mobile_no' => $mobile_no,
                'email' => $this->input->post('email'),
                'modified_by' => $this->session->userdata('user_id'),
				'modified_time' => date('Y-m-d H:i:s')
            );
            $c_where = array('contact_id'=>$contact_id);
            $this->Common_model->update_data('contact',$c_dataArr,$c_where);
			$dataArr = array(
				'status' => 1,
				'modified_by' => $this->session->userdata('user_id'),
				'modified_time' => date('Y-m-d H:i:s'));
			//Insert
			$where = array('lead_id'=>$lead_id);
			//echo '<pre>'; print_r($dataArr); exit;
			$this->Common_model->update_data('lead',$dataArr,$where);
			addLeadStatusHistory($lead_id, 1);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> There was a problem while updating Lead!
									 </div>');
				redirect(SITE_URL.'editRejectedLead/'.$this->input->post('encoded_id'));
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Lead : '.$lead_id.' Customer contact details has been updated successfully and lead has been sent for approval!
									 </div>');
				redirect(SITE_URL.'openLeads');
			}	
		}	
	}

	//// Phase2 update: opportunity Status added by suresh on 4th May 2017
	public function opportunityStatus()
	{
		$data['nestedView']['heading'] = "opportunities";
		$data['nestedView']['cur_page'] = 'opportunityStatus';
		$data['nestedView']['parent_page'] = 'opportunityStatus';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/manage-opportunity.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Opportunity Status';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Opportunity Status','class'=>'active','url'=>'');

		# Search Functionality
		$psearch=$this->input->post('searchOpenLead', TRUE);
		if($psearch!='') {

		$searchParams=array(
					  'opportunity_id'=>$this->input->post('opportunity_id', TRUE),
					  
                      'start_date' => $this->input->post('start_date', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'opportunity_id'=>$this->session->userdata('opportunity_id'),
					 
                      'start_date'=>$this->session->userdata('start_date')
							  );
			}
			else {
				$searchParams=array(
					  'opportunity_id'=>'',
					  
                      'start_date'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'opportunityStatus/'; 
		# Total Records
	    $config['total_rows'] = $this->Lead_model->opportunityStatusRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		
		
		# Search Results
	   	$data['searchResults'] = $this->Lead_model->opportunityStatusResults($searchParams,$config['per_page'], $current_offset);
		
		$data['pageInfo'] = 1;
		
		$this->load->view('lead/opportunityStatusView', $data);
	}
	///// end opportunity Status

	// Phase2 update: download all Opportunities created by suresh on 4th May 2017
	public function download_allOpportunities()
    {
        if($this->input->post('downloadOpportunity')!='') {
            
            $searchParams=array(
					  'opportunity_id'=>$this->input->post('opportunity_id', TRUE),
                                          'start_date' => $this->input->post('start_date', TRUE)
					 		  );
            $status = 1;
            $results = $this->Lead_model->download_allOpportunityResults($searchParams);
            
            $header = '';
            $data ='';
            if($status==1) {
                $titles = array('Opporunity ID','Lead Id','Customer','Customer Location','Source of Lead','Site Readiness','Source Of Fund','Lead Owner','Owner EmpId','Owner Designation','Product Category','Segment','Product Code','Product Description', 'Quantity', 'Value (in Lakhs)', 'Region','Stage','Current Stage','Category','Expected Order Conclusion Date','Outdated','Probability Of Winning','Decision Maker1','Decision Maker1 - Speciality','Created On','Lead Life Time(Days)','Opp Life Time(Days)');
            }
            $data = '<table border="1">';
            $data.='<thead>';
            $data.='<tr>';
            foreach ( $titles as $title)
            {
                $data.= '<th>'.$title.'</th>';
            }
            $data.='</tr>';
            $data.='</thead>';
            $data.='<tbody>';
             $j=1;
            if(count($results)>0)
            {
                
                foreach($results as $row)
                {
                	$contact = get_contract_user_name($row['decision_maker1']);
                    $data.='<tr>';
                    $data.='<td>'.$row['opportunity_id'].'</td>';
                    $data.='<td>'.$row['lead_id'].'</td>';
                    $data.='<td>'.$row['lead_name'].'</td>';
                    $data.='<td>'.$row['lead_location'].'</td>';
                    $data.='<td>'.$row['source'].'</td>';
                    $data.='<td>'.$row['readiness'].'</td>';
                    $data.='<td>'.$row['source_of_fund'].'</td>';
                    $data.='<td>'.$row['user_name'].'</td>';
                    $data.='<td>'.$row['emp_id'].'</td>';
                    $data.='<td>'.$row['role_name'].'</td>';
                    $data.='<td>'.$row['product_category'].'</td>';
                     $data.='<td>'.$row['segment'].'</td>';
                    $data.='<td>'.$row['product_code'].'</td>';
                    $data.='<td>'.$row['product_description'].'</td>';
                    $data.='<td>'.$row['required_quantity'].'</td>';
                    $data.='<td>'.valueInLakhs($row['required_quantity']*$row['dp'],2).'</td>';
                    $data.='<td>'.@getRegionNameFromCity($row['location_id']).'</td>';
					$data.='<td>'.@$row['previous_status'].'</td>';
                    $data.='<td>'.$row['stage'].'</td>';
                    if($status==1){
                    	$probability = getProbabilityForOpportunity($row['opportunity_id']);
                    	if($searchParams['start_date']!='')
                    	{
                    		$year = format_date($searchParams['start_date'],'Y');
                    		$month = format_date($searchParams['start_date'],'m');
                    	}
                    	else
                    	{
                    		$year = date('Y');
                    		$month = date('m');
                    	}
                    	$data.='<td>'.@getOpportunityCategory(@$row['op_prev_status'], @$row['oDate'],$year,$month).'</td>';
                    	$data.='<td>'.@DateFormat($row['oDate']).'</td>';
                    	$expected_date=strtotime($row['oDate']);
                    	$cur_date = ($searchParams['start_date']!='')?$searchParams['start_date']:date('Y-m-d');
                    	$current_date=strtotime($cur_date);
                    	if($current_date>$expected_date)
                    	{
                    		$outdated='Yes';
                    	}
                    	else
                    	{
                    		$outdated='No';
                    	}
                    	 $data.='<td>'.@$outdated.'</td>';
                        $data.='<td>'.@$probability.' % </td>';
                    }
                    $data.='<td>'.$contact['name'].'</td>';
                    $data.='<td>'.$contact['speciality'].'</td>';
                    $data.='<td>'.@$row['created_time'].'</td>';
                    $data.='<td>'.date_difference_two_days($row['lCTime'],$cur_date.' 23:59:59').'</td>';
                	$data.='<td>'.date_difference_two_days($row['oCTime'],$cur_date.' 23:59:59').'</td>';
                    $data.='</tr>';
                    $j++;
                }
            }
            else
            {
                $data.='<tr><td colspan="'.(count($titles)).'" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile='opportunity_history_'.$time.'.xls'; 
            header("Content-type: application/x-msdownload"); 
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=".$xlFile."");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
            
        }
    }
	// end download all opportunities

	public function assignInactiveUserLeadsDownload(){
		// echo "IN Download";
		$searchParams=array(
			'lead_id'=>$this->input->post('lead_id', TRUE),
			'customer'=>$this->input->post('customer', TRUE),
			'created_user'=>$this->input->post('created_user', TRUE),
			's_region'=>$this->input->post('s_region', TRUE),
			's_state'=>$this->input->post('s_state', TRUE),
			's_district'=>$this->input->post('s_district', TRUE),
			's_city'=>$this->input->post('s_city', TRUE),
			'open_status' => $this->input->post('open_status', TRUE)
					 );

	    $inactiveLead = $this->Lead_model->inActiveUserLeadsDownload($searchParams);
		// print_r($inactiveLead);die;		
		
		$header = '';
			$data ='';
			$titles = array('S.NO','Lead Id','Lead User','Customer','Contact Person','Status');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th align="center">'.$title.'</th>';
			}
			
			// $data.='</tr>';
			// $data.='<tr>';
			// $data.='<th></th>';
			// $data.='<th>Planned</th><th>Completed</th>';
			// $data.='<th>Planned</th><th>Completed</th>';
			// $data.='<th>Planned</th><th>Completed</th>';
			// $data.='<th>Planned</th><th>Completed</th>';
			// $data.='<th>Planned</th><th>Completed</th>';
			// $data.='<th>Planned</th><th>Completed</th>';
			// $data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($inactiveLead)>0)
			{
				
				foreach($inactiveLead as $row)
				{					
					$status = "";
					if($row['status'] == 1)
					  $status = "Waiting for Approval";
					if($row['status'] == 3)
					  $status = "Opportunity Created"; 
					if($row['status'] == 5)
					  $status = "All Opportunities Lost or Dropped";
					if($row['status'] == 6)
					  $status = "Partial Quote";
					if($row['status'] == 8)
					  $status = "Partial Contract Note - Partial Quote";
					if($row['status'] == 9)
					  $status = "Partial Contract Note - Full Quote"; 
					if($row['status'] == 19)
					  $status = "Lead Owner Role Changed";
					if($row['status'] == 2) 
					 $status = "Lead Approved";
					if($row['status'] == 4) 
					 $status = "All Opportunities Dropped";
					if($row['status'] == 7) 
					 $status = "Full Quote";
					if($row['status'] == 10) 
					 $status = "Full Contract Note - Full Quote";
					// $leadPlaned=0;
					// if($row['leadPlaned'] != null)
					// $leadPlaned = $row['leadPlaned'];

					// $leadCompleted = 0;
					// if($row['leadCompleted'] != null)
					// $leadCompleted = $row['leadCompleted'];

					// $coldCallPlaned = 0;
					// if($row['coldCallPlaned'] != null)
					// $coldCallPlaned = $row['coldCallPlaned'];

					// $coldCallCompleted = 0;
					// if($row['coldCallCompleted'] != null)
					// $coldCallCompleted = $row['coldCallCompleted'];

					// $dealerPlaned = 0;
					// if($row['dealerPlaned'] != null)
					// $dealerPlaned = $row['dealerPlaned'];

					// $dealereCompleted = 0;
					// if($row['dealereCompleted'] != null)
					// $dealereCompleted = $row['dealereCompleted'];

					// $curtecyPlaned = 0;
					// if($row['curtecyPlaned'] != null)
					// $curtecyPlaned = $row['curtecyPlaned'];

					// $curtecyCallCompleted = 0;
					// if($row['curtecyCallCompleted'] != null)
					// $curtecyCallCompleted = $row['curtecyCallCompleted'];

					// $miscPlaned = 0;
					// if($row['miscPlaned'] != null)
					// $miscPlaned = $row['miscPlaned'];

					// $miscCompleted = 0;
					// if($row['miscCompleted'] != null)
					// $miscCompleted = $row['miscCompleted'];

					// $totalPlan = 0;
					// if($row['totalPlan'] != null)
					// $totalPlan = $row['totalPlan'];

					// $totalComplete = 0;
					// if($row['totalComplete'] != null)
					// $totalComplete = $row['totalComplete'];

					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td align="center">'.$row['lead_id'].'</td>';
					$data.='<td align="center">'.$row['user'].'</td>';
					$data.='<td align="center">'.$row['customer'].'</td>';
					$data.='<td align="center">'.$row['contact'].'</td>';
					$data.='<td align="center">'.$status.'</td>';
					// $data.='<td align="center">'.$leadCompleted.'</td>';
					// $data.='<td align="center">'.$coldCallPlaned.'</td>';
					// $data.='<td align="center">'.$coldCallCompleted.'</td>';
					// $data.='<td align="center">'.$dealerPlaned.'</td>';
					// $data.='<td align="center">'.$dealereCompleted.'</td>';
					// $data.='<td align="center">'.$curtecyPlaned.'</td>';
					// $data.='<td align="center">'.$curtecyCallCompleted.'</td>';
					// $data.='<td align="center">'.$miscPlaned.'</td>';
					// $data.='<td align="center">'.$miscCompleted.'</td>';
					// $data.='<td align="center">'.$totalPlan.'</td>';
					// $data.='<td align="center">'.$totalComplete.'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='inactiveUserLead_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
	}

	public function addConditionForApprovalMail(){
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Condition For Approval Mail";
		$data['nestedView']['cur_page'] = 'addConditionForApprovalMail';
		$data['nestedView']['parent_page'] = 'addConditionForApprovalMail';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Condition For Approval Mail';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Condition For Approval Mail','class'=>'active','url'=>'');
		$data['displayList'] = 1;

		$data['conditionApproval'] = $this->Common_model->get_data('condition_approval_mail', array('condition_approval_mail_id'=>1));		
		// $this->Common_model->update_data('lead',$dataArr, $where);

		$this->load->view('lead/addConditionForApprovalMail', $data);
	} 

	public function submitConditionForApprovigMail(){
		$condition=$this->input->post('condition', TRUE);
		// echo $condition;die;
		$dataArr = array('condition' => $condition);
		$this->Common_model->update_data('condition_approval_mail',$dataArr, 'condition_approval_mail_id = 1');

		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> condition has been Updated successfully!
							 </div>');
		redirect(SITE_URL.'addConditionForApprovalMail');

	}
}