<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Opportunity extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Opportunity_model");
	}

	// Phase2 update: Prasad 27th July 2017
	public function openOpportunityDetails($encoded_id)
	{
		$lead_id = @icrm_decode($encoded_id);
		if(checkOpportunity($lead_id) == 0)
		{
			redirect(SITE_URL.'openLeads');
		}
		$leadStatus = getLeadStatusID($lead_id);
		$data['encoded_id'] = $encoded_id;
		$lead_number = $this->Common_model->get_value('lead',array('lead_id'=>$lead_id),'lead_number');
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Open Leads";
		$data['nestedView']['cur_page'] = 'openLeads';
		$data['nestedView']['parent_page'] = 'openLeads';
		
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
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.niftymodals/css/component.css" />';
		

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Open Leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Open Leads','class'=>'','url'=>SITE_URL.'openLeads');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead ID - '.$lead_number,'class'=>'active','url'=>'');

		//$data['prodocuts'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', array('status'=>1));
		$data['categories'] =  array(''=>'Select Category') + $this->Opportunity_model->getLoggedInUserProductCategoriesDropdown();
		$data['groups'] = array(''=>'Select Group');
		$data['products'] = array(''=>'Select Product');
		$data['source_of_funds'] = $this->Common_model->get_data('source_of_funds',array());
		$data['relationship'] = $this->Common_model->get_data('relationship',array());
		$lead = $this->Common_model->get_data('lead',array('lead_id'=>$lead_id));
		$data['lead']=$lead[0];
		$data['leadStatus'] = $leadStatus;
		//GETTING OPPORTUNITY STATUS OPTIONS
		$qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 5';
		$data['opportunity_status'] = $this->Common_model->get_query_result($qry);
		//GETTING EDIT OPPORTUNITY STATUS OPTIONS
		// $qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8)';
		if($_SESSION['role_id'] == '5')
		{
			$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8)'; //changed on 14-06-2021 for Distributor
		}
		else
		{
			$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8,10,11,12)';
		}
		
		$data['edit_opportunity_status1'] = $this->Common_model->get_query_result($qry);
		//GETTING EDIT OPPORTUNITY STATUS OPTIONS
		// $qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,6,7,8)';
		if($_SESSION['role_id'] == '5')
		{
			$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,6,7,8)'; //changed on 14-06-2021 for Distributor
		}
		else
		{
			$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8,10,11,12)';
		}
		$data['edit_opportunity_status3'] = $this->Common_model->get_query_result($qry);
		$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,7)';
		$data['edit_opportunity_status2'] = $this->Common_model->get_query_result($qry);

		$data['pageDetails'] = 'Opportunity';
		$data['lead_id'] = $lead_id;

		$data['checkPage'] = 1;//1 for Open Pages. 0 for Closed Pages
		//GETTING OPPORTUNITIES
		$data['opportunities_results'] = $this->Opportunity_model->getOpportunityResultsByLead($lead_id);

		// added on 01-07-2021 for distributor role
		foreach ($data['opportunities_results'] as $key => $u_role_id) {
			$qry = 'SELECT role_id FROM user WHERE user_id="'.$u_role_id['created_by'].'"';
			$data['u_role_id'] = $this->Common_model->get_query_result($qry);
		}
		// added on 01-07-2021 for distributor role end
		
		//echo "<pre>"; print_r($data['opportunities_results']); exit;
		/* Modified by prasad on 25-07-2017
		   Fetching lost opportunity reasons */
		$data['opp_lost_reasons'] = $this->Common_model->get_data('opportunity_lost_reasons',array('status'=>1));
		$data['lost_competitors'] = $this->Common_model->get_data('competitor',array('status'=>1,'company_id'=>$this->session->userdata('company')));
		/* end prasad */
		// echo "<pre>";print_r($data['u_role_id']);die;
		$this->load->view('lead/openOpportunityDetailsView', $data);
		//redirect(SITE_URL.'openLeads');
	}


	public function closedOpportunityDetails($encoded_id)
	{
		$lead_id = @icrm_decode($encoded_id);
		if(checkClosedLead($lead_id) == 0)
		{
			redirect(SITE_URL.'closedLeads');
		}
		$leadStatus = getLeadStatusID($lead_id);
		$data['encoded_id'] = $encoded_id;
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Closed Leads";
		$data['nestedView']['cur_page'] = 'closedLeads';
		$data['nestedView']['parent_page'] = 'closedLeads';
		
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
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.niftymodals/css/component.css" />';
		

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Closed Leads';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Closed Leads','class'=>'','url'=>SITE_URL.'closedLeads');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead ID - '.$lead_id,'class'=>'active','url'=>'');

		$data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
		
		$data['groups'] = array(''=>'Select Group');
		$data['products'] = array(''=>'Select Product');
		$data['source_of_funds'] = $this->Common_model->get_data('source_of_funds',array());
		$data['relationship'] = $this->Common_model->get_data('relationship',array());
		$lead = $this->Common_model->get_data_row('lead',array('lead_id'=>$lead_id));
		$data['lead']=$lead;
		$data['leadStatus'] = $leadStatus;
		//GETTING OPPORTUNITY STATUS OPTIONS
		$qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 5';
		$data['opportunity_status'] = $this->Common_model->get_query_result($qry);
		//GETTING EDIT OPPORTUNITY STATUS OPTIONS
		$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8,11,12)';
		$data['edit_opportunity_status1'] = $this->Common_model->get_query_result($qry);
		//GETTING EDIT OPPORTUNITY STATUS OPTIONS
		$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,6,7,8,11,12)';
		$data['edit_opportunity_status3'] = $this->Common_model->get_query_result($qry);
		$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,7,11,12)';
		$data['edit_opportunity_status2'] = $this->Common_model->get_query_result($qry);

		$data['pageDetails'] = 'Opportunity';
		$data['lead_id'] = $lead_id;

		$data['checkPage'] = 0;//1 for Open Pages. 0 for Closed Pages

		//GETTING OPPORTUNITIES
		$data['opportunities_results'] = $this->Opportunity_model->getOpportunityResultsByLead($lead_id);
		$this->load->view('lead/openOpportunityDetailsView', $data);
		//redirect(SITE_URL.'openLeads');
	}	

	/*mahesh 5th july 7pm
	description : creating new opportunity for the existing lead.
	inputs:lead_id
	*/
	public function insertOpportunity(){
		goto start_opportunity;
        start_opportunity:
		$checkAdd = $this->input->post('checkAdd', TRUE);
		$encoded_id = $this->input->post('encoded_id',TRUE);
		$lead_id = ($checkAdd == 1)?@icrm_decode($encoded_id):$encoded_id;

		if(checkOpportunity($lead_id) == 0)
		{
			redirect(SITE_URL.'openLeads');
		}
		
		// transaction begin
		$this->db->trans_begin();
		$lead_str_arr = get_current_unique_numbers("opportunity","opp_counter","opportunity_id");
		$opp_counter=$lead_str_arr[0];
		$opp_number=$lead_str_arr[1];
		$demo_required = ($this->input->post('status',TRUE)==3)?1:0;
		//INSERTING OPPORTUNITY
		$opportunity_data = array( 
					'lead_id'					=>	$lead_id,
					'company_id'                =>  $this->session->userdata('company'),
					'required_quantity'			=>	$this->input->post('required_quantity',TRUE),
					'fund_source_id'			=>	$this->input->post('source_of_funds',TRUE),
					'expected_order_conclusion'	=>	$this->input->post('expected_order_conclusion',TRUE),
					'expected_invoicing_date'	=>	($this->input->post('expected_invoicing_date',TRUE) != '')?$this->input->post('expected_invoicing_date',TRUE):NULL,
					'decision_maker1'			=>	$this->input->post('decision_maker1',TRUE),
					'decision_maker2'			=>	($this->input->post('decision_maker2',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'decision_maker3'			=>	($this->input->post('decision_maker3',TRUE) != '')?$this->input->post('decision_maker3',TRUE):NULL,
					'decision_maker4'			=>	($this->input->post('decision_maker4',TRUE) != '')?$this->input->post('decision_maker4',TRUE):NULL,
					'decision_maker5'			=>	($this->input->post('decision_maker5',TRUE) != '')?$this->input->post('decision_maker5',TRUE):NULL,
					'relationship_id'			=>	$this->input->post('relationship_with_decision_maker',TRUE),
					'demo_requirement'			=>	$demo_required,
					'technically_cleared'		=>	1,
					'status'					=>	$this->input->post('status',TRUE),
					'created_by'				=>	$this->session->userdata('user_id'),
					'created_time'				=>	date('Y-m-d H:i:s')
							);
		try
		{
			check_unique_numbers_constraint('opportunity','opp_counter',$opp_counter);
		}
		catch(Exception $e)
		{
			//echo "hi";exit;
			goto start_opportunity;
		}
		$opportunity_data['opp_counter']=$opp_counter;
		$opportunity_data['opp_number']=$opp_number;
	   //INSERTING OPPORTUNITY PRODUCT
		$productDetails = getProductCostingDetails($this->input->post('product',TRUE),$lead_id);
		$getFinalValue = getFinalValueAfterConversion($productDetails['mrp'],$productDetails['currency_id']); 
		$total_converted_value= $getFinalValue[0];
		$currency_factor=$getFinalValue[1];
		if($total_converted_value==0 && $currency_factor==0 )
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong>Please Add Currency Conversion Factor in Currency Convertor Screen.Then Try Again !
								 </div>');
			redirect(SITE_URL.'openOpportunityDetails/'.$encoded_id);exit;
		}
		$opportunityId = $this->Common_model->insert_data('opportunity',$opportunity_data);
		$op_product_data = array( 
					'opportunity_id'		=>	$opportunityId,
					'product_id'			=>	$this->input->post('product',TRUE),
                    'mrp' 					=>	$productDetails['mrp'],
                    'ed' 					=>	$productDetails['ed'],
                    'vat' 					=>	$productDetails['vat'],
                    'freight_insurance' 	=>	$productDetails['freight_insurance'],
                    'gst' 					=>	$productDetails['gst'],
                    'discount'				=>	0,
					'currency_id'           =>  $productDetails['currency_id'],
					'total_value'           =>  $total_converted_value,
					'currency_factor'       =>  $currency_factor
                    );
		$this->Common_model->insert_data('opportunity_product',$op_product_data);

		//INSERTING OPPORTUNITY COMPETITORS
		$competitors = $this->input->post('opportunity_competitors',TRUE);
		if($competitors){
			$op_competitor_data = array();
			foreach ($competitors as $competitorId) {
				
				$op_competitor_data[] = array( 
					'opportunity_id'		=>	$opportunityId,
					'competitor_id'			=>	$competitorId
				);
			}
			if(count($op_competitor_data)>0)
			$this->Common_model->insert_batch_data('opportunity_competitor',$op_competitor_data);
		}

		//INSERTING OPPORTUNITY STATUS HISTORY
		addOpportunityStatusHistory($opportunityId, $this->input->post('status',TRUE));

		//Updating Lead status as required
		leadStatusUpdate($lead_id);

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while adding Opportunity!
								 </div>');
			
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Opportunity has been added successfully!
								 </div>');
			
		}
		if($checkAdd)
			redirect(SITE_URL.'openOpportunityDetails/'.$encoded_id);
		else
			redirect(SITE_URL.'opportunity');
	}

	/*mahesh 6th july 5pm
	Phase2 update: Prasad 27-07-2017
	if we update the quantity for the exisiting opportunity , new opportunity will be created.
	we can update the opportunity till quote is created,once created , opportunity cannot be updated
	inputs :lead_id ,opportunity_id
	*/

	public function updateOpportunity()
	{
		goto update_opportunity;
        update_opportunity:
		$encoded_id = $this->input->post('encoded_id',TRUE);
		$lead_id = @icrm_decode($encoded_id);
		$opportunity_id = @icrm_decode($this->input->post('en_op_id',TRUE));
		if(checkOpportunity($lead_id) == 0||$opportunity_id == 0)
		{
			redirect(SITE_URL.'openLeads');
		}
		
		// transaction begin
		$this->db->trans_begin();
		$op_where = array('opportunity_id'=>$opportunity_id);
		$demo_required = ($this->input->post('status',TRUE)==3)?1:0;
		//GET OPPORTUNITY DATA
		$op_row = $this->Common_model->get_data('opportunity',$op_where);
		$op_required_quantity = $op_row[0]['required_quantity'];
		$post_required_quantity = $this->input->post('required_quantity',TRUE);
		$cur_op_qty = $op_required_quantity; $split_op_qty = 0;
		if($this->input->post('status',TRUE) != 7 && $this->input->post('status',TRUE) != 8)
		{
			if($op_required_quantity!=$post_required_quantity)
			{

				$differ_qty = $post_required_quantity - $op_required_quantity;

				if($differ_qty<0){
					$cur_op_qty = $post_required_quantity;
					$split_op_qty = -($differ_qty);
				}
				else{$split_op_qty = $differ_qty;}

				//echo $cur_op_qty.'--'.$split_op_qty; exit;
				//INSERTING OPPORTUNITY
				$lead_str_arr = get_current_unique_numbers("opportunity","opp_counter","opportunity_id");
		        $opp_counter=$lead_str_arr[0];
		        $opp_number=$lead_str_arr[1];
				$opportunity_data = array( 
							'lead_id'					=>	$lead_id,
							'required_quantity'			=>	$split_op_qty,
							'company_id'                =>  $this->session->userdata('company'),
							'fund_source_id'			=>	$this->input->post('source_of_funds',TRUE),
							'expected_order_conclusion'	=>	$this->input->post('expected_order_conclusion',TRUE),
							'expected_invoicing_date'	=>	($this->input->post('expected_invoicing_date',TRUE) != '')?$this->input->post('expected_invoicing_date',TRUE):NULL,
							'decision_maker1'			=>	$this->input->post('decision_maker1',TRUE),
							'decision_maker2'			=>	($this->input->post('decision_maker2',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
							'decision_maker3'			=>	($this->input->post('decision_maker3',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
							'decision_maker4'			=>	($this->input->post('decision_maker4',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
							'decision_maker5'			=>	($this->input->post('decision_maker5',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
							'relationship_id'			=>	$this->input->post('relationship_with_decision_maker',TRUE),
							'demo_requirement'			=>	$demo_required,
							'technically_cleared'		=>	1,
							'status'					=>	$this->input->post('status',TRUE),
							'created_by'				=>	$this->session->userdata('user_id'),
							'created_time'				=>	date('Y-m-d H:i:s')
						);
					try
					{
						check_unique_numbers_constraint('opportunity','opp_counter',$opp_counter);
					}
					catch(Exception $e)
					{
						//echo "hi";exit;
						goto update_opportunity;
					}
					$opportunity_data['opp_counter']=$opp_counter;
					$opportunity_data['opp_number']=$opp_number;
				//INSERTING OPPORTUNITY PRODUCT
				$productDetails = getProductCostingDetails($this->input->post('product',TRUE),$lead_id);
				$getFinalValue = getFinalValueAfterConversion($productDetails['mrp'],$productDetails['currency_id']); 
				$total_converted_value= $getFinalValue[0];
				$currency_factor=$getFinalValue[1];
				if($total_converted_value==0 && $currency_factor==0 )
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											<div class="icon"><i class="fa fa-check"></i></div>
											<strong>Error!</strong>Please Add Currency Conversion Factor in Currency Convertor Screen.Then Try Again !
										</div>');
					redirect(SITE_URL.'openOpportunityDetails/'.$encoded_id);exit;
				}
				$opportunityId = $this->Common_model->insert_data('opportunity',$opportunity_data);
				$op_product_data = array( 
					'opportunity_id'		=>	$opportunityId,
					'product_id'			=>	$this->input->post('product',TRUE),
                    'mrp' 					=>	$productDetails['mrp'],
                    'ed' 					=>	$productDetails['ed'],
                    'vat' 					=>	$productDetails['vat'],
                    'freight_insurance' 	=>	$productDetails['freight_insurance'],
                    'gst' 					=>	$productDetails['gst'],
                    'discount'				=>	0,
					'currency_id'           =>  $productDetails['currency_id'],
					'total_value'           =>  $total_converted_value,
					'currency_factor'       =>  $currency_factor
                    );

				
				$this->Common_model->insert_data('opportunity_product',$op_product_data);

				//INSERTING OPPORTUNITY COMPETITORS
				$competitors = $this->input->post('opportunity_competitors',TRUE);
				if($competitors){
					$op_competitor_data = array();
					foreach ($competitors as $competitorId) {
						
						$op_competitor_data[] = array( 
							'opportunity_id'		=>	$opportunityId,
							'competitor_id'			=>	$competitorId
									);
					}
					if(count($op_competitor_data)>0)
					$this->Common_model->insert_batch_data('opportunity_competitor',$op_competitor_data);
				}

				//INSERTING OPPORTUNITY STATUS HISTORY

				addOpportunityStatusHistory($opportunityId, $this->input->post('status',TRUE));
			}
		}

		
		//UPDATE OPPORTUNITY
		$op_data = array( 
					'required_quantity'			=>	$cur_op_qty,
					'fund_source_id'			=>	$this->input->post('source_of_funds',TRUE),
					'expected_order_conclusion'	=>	$this->input->post('expected_order_conclusion',TRUE),
					'expected_invoicing_date'	=>	$this->input->post('expected_invoicing_date',TRUE),
					'decision_maker1'			=>	$this->input->post('decision_maker1',TRUE),
					'decision_maker2'			=>	($this->input->post('decision_maker2',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'decision_maker3'			=>	($this->input->post('decision_maker3',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'decision_maker4'			=>	($this->input->post('decision_maker4',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'decision_maker5'			=>	($this->input->post('decision_maker5',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'relationship_id'			=>	$this->input->post('relationship_with_decision_maker',TRUE),
					'demo_requirement'			=>	$demo_required,
					'status'					=>	$this->input->post('status',TRUE),
					'modified_by'				=>  $this->session->userdata('user_id'),
					'modified_time'              =>  date('Y-m-d H:i:s')
							);
		if($this->input->post('status')==7)
		{
			$op_data['oppr_lost_id']=$this->input->post('opp_lost_reason');
			$op_data['remarks2']=$this->input->post('remarks2');
			$op_data['lost_competitor_id']=$this->input->post('opp_lost_competitor');
			$op_data['model']=  $this->input->post('model');
			$op_data['remarks3']=$this->input->post('comp_remarks2');
		}
		if($this->input->post('status')==8)
		{
			$op_data['remarks2']=$this->input->post('remarks2');
		}
		// // added on 16-06-2021 for distributor role
		// if($this->input->post('status')==10 && $_SESSION['role_id'] == '7')
		// {
		// 	$op_data['remarks2']=$this->input->post('remarks2');
		// 	$op_data = array( 
		// 		'status'					=>	$this->input->post('status',TRUE),
		// 		'modified_by'				=>  $this->session->userdata('user_id'),
		// 		'modified_time'              =>  date('Y-m-d H:i:s'),
		// 		'remarks2'					=> $this->input->post('remarks2'),
		// 		'closed_time'				=> date('Y-m-d H:i:s'),
		// 		'closed_by'					=> $this->session->userdata('user_id')
		// }
		// // added on 16-06-2021 for distributor role end
		if($this->input->post('status',TRUE) == 7 || $this->input->post('status',TRUE) == 8)
		{
			$op_data['required_quantity'] = $this->input->post('required_quantity',TRUE);
			$op_data['closed_time']		  = date('Y-m-d H:i:s');
			$op_data['closed_by']		  = $this->session->userdata('user_id');
		}
		// $this->Common_model->update_data('opportunity',$op_data,$op_where);

		// added on 16-06-2021 for distributor role
		if($this->input->post('status')==10 && $_SESSION['role_id'] == '7')
		{
			$op_data['remarks2']=$this->input->post('remarks2');
			$op_data = array( 
				'status'					=>	$this->input->post('status',TRUE),
				'modified_by'				=>  $this->session->userdata('user_id'),
				'modified_time'             =>  date('Y-m-d H:i:s'),
				'remarks2'					=> 	$this->input->post('remarks2'),
				'closed_time'				=> 	date('Y-m-d H:i:s'),
				'closed_by'					=> 	$this->session->userdata('user_id'));
			$this->Common_model->update_data('opportunity',$op_data,$op_where);
		}
		else
		{
			$this->Common_model->update_data('opportunity',$op_data,$op_where);
		}
		// added on 16-06-2021 for distributor role end

		// DELETING OPPORTUNITY COMPETITORS
		$this->db->delete('opportunity_competitor',array('opportunity_id'=>$opportunity_id));
		//INSERTING OPPORTUNITY COMPETITORS
		$competitors = $this->input->post('opportunity_competitors',TRUE);
		if($competitors){
			$op_competitor_data = array();
			foreach ($competitors as $competitorId) {
				
				$op_competitor_data[] = array( 
					'opportunity_id'		=>	$opportunity_id,
					'competitor_id'			=>	$competitorId
							);
			}
			if(count($op_competitor_data)>0)
			$this->Common_model->insert_batch_data('opportunity_competitor',$op_competitor_data);
		}

		//INSERTING OPPORTUNITY STATUS HISTORY
		addOpportunityStatusHistory($opportunity_id, $this->input->post('status',TRUE));

		if($this->input->post('status',TRUE) == 7)
		{
			addQuoteStatusByOpportunity($opportunity_id, $this->input->post('status',TRUE));
			leadStatusUpdate($lead_id);
		}

		if($this->input->post('status',TRUE) == 8)
		{
			addQuoteStatusByOpportunity($opportunity_id, $this->input->post('status',TRUE));
			leadStatusUpdate($lead_id);
		}
		

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while updating Opportunity!
								 </div>');
			
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Opportunity has been updated successfully!
								 </div>');
			
		}

		redirect(SITE_URL.'openOpportunityDetails/'.$encoded_id);

	}

	// added on 27-06-2021 for distributor role
	public function updateOpportunityDistributorRole()
	{
		// goto update_opportunity;
        // update_opportunity:
		$encoded_id = $this->input->post('encoded_id',TRUE);
		$lead_id = @icrm_decode($encoded_id);
		$opportunity_id = @icrm_decode($this->input->post('en_op_id',TRUE));
		// echo "<pre>";print_r($opportunity_id);die;
		// if(checkOpportunity($lead_id) == 0||$opportunity_id == 0)
		// {
		// 	redirect(SITE_URL.'openLeads');
		// }
		
		// transaction begin
		$this->db->trans_begin();
		$op_where = array('opportunity_id'=>$opportunity_id);
		$demo_required = ($this->input->post('status',TRUE)==3)?1:0;
		//GET OPPORTUNITY DATA
		$op_row = $this->Common_model->get_data('opportunity',$op_where);
		$op_required_quantity = $op_row[0]['required_quantity'];
		$post_required_quantity = $this->input->post('required_quantity',TRUE);
		$cur_op_qty = $op_required_quantity; $split_op_qty = 0;
		// if($this->input->post('status',TRUE) != 7 && $this->input->post('status',TRUE) != 8)
		// {
		// 	if($op_required_quantity!=$post_required_quantity)
		// 	{

		// 		$differ_qty = $post_required_quantity - $op_required_quantity;

		// 		if($differ_qty<0){
		// 			$cur_op_qty = $post_required_quantity;
		// 			$split_op_qty = -($differ_qty);
		// 		}
		// 		else{$split_op_qty = $differ_qty;}

		// 		//echo $cur_op_qty.'--'.$split_op_qty; exit;
		// 		//INSERTING OPPORTUNITY
		// 		// $lead_str_arr = get_current_unique_numbers("opportunity","opp_counter","opportunity_id");
		//         // $opp_counter=$lead_str_arr[0];
		//         // $opp_number=$lead_str_arr[1];
		// 		// $opportunity_data = array( 
		// 		// 			'lead_id'					=>	$lead_id,
		// 		// 			'required_quantity'			=>	$split_op_qty,
		// 		// 			'company_id'                =>  $this->session->userdata('company'),
		// 		// 			'fund_source_id'			=>	$this->input->post('source_of_funds',TRUE),
		// 		// 			'expected_order_conclusion'	=>	$this->input->post('expected_order_conclusion',TRUE),
		// 		// 			'expected_invoicing_date'	=>	($this->input->post('expected_invoicing_date',TRUE) != '')?$this->input->post('expected_invoicing_date',TRUE):NULL,
		// 		// 			'decision_maker1'			=>	$this->input->post('decision_maker1',TRUE),
		// 		// 			'decision_maker2'			=>	($this->input->post('decision_maker2',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
		// 		// 			'decision_maker3'			=>	($this->input->post('decision_maker3',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
		// 		// 			'decision_maker4'			=>	($this->input->post('decision_maker4',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
		// 		// 			'decision_maker5'			=>	($this->input->post('decision_maker5',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
		// 		// 			'relationship_id'			=>	$this->input->post('relationship_with_decision_maker',TRUE),
		// 		// 			'demo_requirement'			=>	$demo_required,
		// 		// 			'technically_cleared'		=>	1,
		// 		// 			'status'					=>	$this->input->post('status',TRUE),
		// 		// 			'created_by'				=>	$this->session->userdata('user_id'),
		// 		// 			'created_time'				=>	date('Y-m-d H:i:s')
		// 		// 		);
		// 		// 	try
		// 		// 	{
		// 		// 		check_unique_numbers_constraint('opportunity','opp_counter',$opp_counter);
		// 		// 	}
		// 		// 	catch(Exception $e)
		// 		// 	{
		// 		// 		//echo "hi";exit;
		// 		// 		goto update_opportunity;
		// 		// 	}
		// 		// 	$opportunity_data['opp_counter']=$opp_counter;
		// 		// 	$opportunity_data['opp_number']=$opp_number;
		// 		//INSERTING OPPORTUNITY PRODUCT
		// 		$productDetails = getProductCostingDetails($this->input->post('product',TRUE),$lead_id);
		// 		$getFinalValue = getFinalValueAfterConversion($productDetails['mrp'],$productDetails['currency_id']); 
		// 		$total_converted_value= $getFinalValue[0];
		// 		$currency_factor=$getFinalValue[1];
		// 		if($total_converted_value==0 && $currency_factor==0 )
		// 		{
		// 			$this->db->trans_rollback();
		// 			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
		// 									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		// 									<div class="icon"><i class="fa fa-check"></i></div>
		// 									<strong>Error!</strong>Please Add Currency Conversion Factor in Currency Convertor Screen.Then Try Again !
		// 								</div>');
		// 			redirect(SITE_URL.'openOpportunityDetails/'.$encoded_id);exit;
		// 		}
		// 		$opportunityId = $this->Common_model->insert_data('opportunity',$opportunity_data);
		// 		$op_product_data = array( 
		// 			'opportunity_id'		=>	$opportunityId,
		// 			'product_id'			=>	$this->input->post('product',TRUE),
        //             'mrp' 					=>	$productDetails['mrp'],
        //             'ed' 					=>	$productDetails['ed'],
        //             'vat' 					=>	$productDetails['vat'],
        //             'freight_insurance' 	=>	$productDetails['freight_insurance'],
        //             'gst' 					=>	$productDetails['gst'],
        //             'discount'				=>	0,
		// 			'currency_id'           =>  $productDetails['currency_id'],
		// 			'total_value'           =>  $total_converted_value,
		// 			'currency_factor'       =>  $currency_factor
        //             );

				
		// 		$this->Common_model->insert_data('opportunity_product',$op_product_data);

		// 		//INSERTING OPPORTUNITY COMPETITORS
		// 		$competitors = $this->input->post('opportunity_competitors',TRUE);
		// 		if($competitors){
		// 			$op_competitor_data = array();
		// 			foreach ($competitors as $competitorId) {
						
		// 				$op_competitor_data[] = array( 
		// 					'opportunity_id'		=>	$opportunityId,
		// 					'competitor_id'			=>	$competitorId
		// 							);
		// 			}
		// 			if(count($op_competitor_data)>0)
		// 			$this->Common_model->insert_batch_data('opportunity_competitor',$op_competitor_data);
		// 		}

		// 		//INSERTING OPPORTUNITY STATUS HISTORY

		// 		addOpportunityStatusHistory($opportunityId, $this->input->post('status',TRUE));
		// 	}
		// }

		
		//UPDATE OPPORTUNITY
		$op_data = array( 
					'required_quantity'			=>	$cur_op_qty,
					'fund_source_id'			=>	$this->input->post('source_of_funds',TRUE),
					'expected_order_conclusion'	=>	$this->input->post('expected_order_conclusion',TRUE),
					'expected_invoicing_date'	=>	$this->input->post('expected_invoicing_date',TRUE),
					'decision_maker1'			=>	$this->input->post('decision_maker1',TRUE),
					'decision_maker2'			=>	($this->input->post('decision_maker2',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'decision_maker3'			=>	($this->input->post('decision_maker3',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'decision_maker4'			=>	($this->input->post('decision_maker4',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'decision_maker5'			=>	($this->input->post('decision_maker5',TRUE) != '')?$this->input->post('decision_maker2',TRUE):NULL,
					'relationship_id'			=>	$this->input->post('relationship_with_decision_maker',TRUE),
					'demo_requirement'			=>	$demo_required,
					'status'					=>	$this->input->post('status',TRUE),
					'modified_by'				=>  $this->session->userdata('user_id'),
					'modified_time'              =>  date('Y-m-d H:i:s')
							);
		if($this->input->post('status')==7)
		{
			$op_data['oppr_lost_id']=$this->input->post('opp_lost_reason');
			$op_data['remarks2']=$this->input->post('remarks2');
			$op_data['lost_competitor_id']=$this->input->post('opp_lost_competitor');
			$op_data['model']=  $this->input->post('model');
			$op_data['remarks3']=$this->input->post('comp_remarks2');
		}
		if($this->input->post('status')==8)
		{
			$op_data['remarks2']=$this->input->post('remarks2');
		}
		// // added on 16-06-2021 for distributor role
		// if($this->input->post('status')==10 && $_SESSION['role_id'] == '7')
		// {
		// 	$op_data['remarks2']=$this->input->post('remarks2');
		// 	$op_data = array( 
		// 		'status'					=>	$this->input->post('status',TRUE),
		// 		'modified_by'				=>  $this->session->userdata('user_id'),
		// 		'modified_time'              =>  date('Y-m-d H:i:s'),
		// 		'remarks2'					=> $this->input->post('remarks2'),
		// 		'closed_time'				=> date('Y-m-d H:i:s'),
		// 		'closed_by'					=> $this->session->userdata('user_id')
		// }
		// // added on 16-06-2021 for distributor role end
		if($this->input->post('status',TRUE) == 7 || $this->input->post('status',TRUE) == 8)
		{
			$op_data['required_quantity'] = $this->input->post('required_quantity',TRUE);
			$op_data['closed_time']		  = date('Y-m-d H:i:s');
			$op_data['closed_by']		  = $this->session->userdata('user_id');
		}
		// $this->Common_model->update_data('opportunity',$op_data,$op_where);

		// added on 16-06-2021 for distributor role
		if($this->input->post('status')==10 && $_SESSION['role_id'] == '7')
		{
			$op_data['remarks2']=$this->input->post('remarks2');
			$op_data = array( 
				'status'					=>	$this->input->post('status',TRUE),
				'modified_by'				=>  $this->session->userdata('user_id'),
				'modified_time'             =>  date('Y-m-d H:i:s'),
				'remarks2'					=> 	$this->input->post('remarks2'),
				'closed_time'				=> 	date('Y-m-d H:i:s'),
				'closed_by'					=> 	$this->session->userdata('user_id'));
			$this->Common_model->update_data('opportunity',$op_data,$op_where);
		}
		else
		{
			$this->Common_model->update_data('opportunity',$op_data,$op_where);
		}
		// added on 16-06-2021 for distributor role end

		// // DELETING OPPORTUNITY COMPETITORS
		// $this->db->delete('opportunity_competitor',array('opportunity_id'=>$opportunity_id));
		// //INSERTING OPPORTUNITY COMPETITORS
		// $competitors = $this->input->post('opportunity_competitors',TRUE);
		// if($competitors){
		// 	$op_competitor_data = array();
		// 	foreach ($competitors as $competitorId) {
				
		// 		$op_competitor_data[] = array( 
		// 			'opportunity_id'		=>	$opportunity_id,
		// 			'competitor_id'			=>	$competitorId
		// 					);
		// 	}
		// 	if(count($op_competitor_data)>0)
		// 	$this->Common_model->insert_batch_data('opportunity_competitor',$op_competitor_data);
		// }

		//INSERTING OPPORTUNITY STATUS HISTORY
		// addOpportunityStatusHistory($opportunity_id, $this->input->post('status',TRUE));

		// if($this->input->post('status',TRUE) == 7)
		// {
		// 	addQuoteStatusByOpportunity($opportunity_id, $this->input->post('status',TRUE));
		// 	leadStatusUpdate($lead_id);
		// }

		// if($this->input->post('status',TRUE) == 8)
		// {
		// 	addQuoteStatusByOpportunity($opportunity_id, $this->input->post('status',TRUE));
		// 	leadStatusUpdate($lead_id);
		// }
		

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while updating Opportunity!
								 </div>');
			
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Opportunity has been updated successfully!
								 </div>');
			
		}

		redirect(SITE_URL.'openOpportunityDetails/'.$encoded_id);

	}
	// added on 27-06-2021 for distributor role end

}