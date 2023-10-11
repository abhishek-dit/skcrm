<?php 
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Headers: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opportunity_api extends CI_Controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Common_model");
		$this->load->model("Opportunity_model");
		$this->load->model("Product_model");
		$this->load->model("Calendar_model");
		$this->load->model("Lead_model");
		$this->load->model("Ajax_m");
		$this->load->model("ajax_model");
	}

	public function opportunity()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $_SESSION['role_id'] = $post_data['role_id'];
        $lead_id = $post_data['lead_id'];
        /*if(checkOpportunity($lead_id) == 0)
		{
			$data['response'] = "Invalid User Id";
			header("Status: 400 Bad Request",true,400);
			echo json_encode($data);
		}*/
        $leadStatus = getLeadStatusID($lead_id);
		$categories =  $this->Opportunity_model->getLoggedInUserProductCategoriesDropdown();
		foreach($categories as $key=>$value)
		{
			$cat_Arr[] = array('id'=>$key,'name'=>$value);
		}
		$data['categories'] = $cat_Arr;
		$data['source_of_funds'] = $this->Common_model->get_data('source_of_funds',array());
		$data['relationship'] = $this->Common_model->get_data('relationship',array());
		$lead = $this->Common_model->get_data_row('lead',array('lead_id'=>$lead_id));
		$data['lead']=$lead;

		$qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 5';
		$opportunity_status = $this->Common_model->get_query_result($qry);
		//GETTING EDIT OPPORTUNITY STATUS OPTIONS
		$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,8)';
		$edit_opportunity_status1 = $this->Common_model->get_query_result($qry);
		//GETTING EDIT OPPORTUNITY STATUS OPTIONS
		$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,6,7,8)';
		$edit_opportunity_status3 = $this->Common_model->get_query_result($qry);
		$qry = 'SELECT * FROM opportunity_status WHERE status IN (1,2,3,4,5,7)';
		$edit_opportunity_status2 = $this->Common_model->get_query_result($qry);

		//GETTING OPPORTUNITIES
		$opportunities_results = $this->Opportunity_model->getOpportunityResultsByLead($lead_id);
		$opp_result_arr = array();
		foreach($opportunities_results as $key=>$value)
		{
			$opp_result_arr[$key] = $value;
			$opp_result_arr[$key]['display_category'] = getOpportunityCategory($value['status'],$value['expected_order_conclusion']);
			$op_comp_res =getOpportunityCompetitors($value['opportunity_id']);
			if(count($op_comp_res)>0)
			{
				$op_competitors_arr = array();
				foreach ($op_comp_res as $comp) 
				{
					$op_competitors_arr[] = array('opportunity_id'=>$comp['opportunity_id'],'competitor_id'=>$comp['competitor_id']);
				}
				$opp_result_arr[$key]['edit_selected_competitors'] = $op_competitors_arr;
			}
			else
			{
				$opp_result_arr[$key]['edit_selected_competitors'] = array();
			}
		}
		$data['opportunities_results'] = $opp_result_arr;
		$opportunity_id = $post_data['opportunity_id'];
		$data['opp_lost_reasons'] = $this->Common_model->get_data('opportunity_lost_reasons',array('status'=>1));
		$data['lost_competitors'] = $this->Common_model->get_data('competitor',array('status'=>1,'company_id'=>$this->session->userdata('company')));
		$leads = $this->Calendar_model->getLeadDetails(3);
		$lead_arr = array();
		foreach ($leads as $lead1) 
 		{
 			$lead_arr[] = array('id'=>$lead1['lead_id'],'name'=>"Lead ID - ".$lead1['lead_number']." (".$lead1['CustomerName'].")",'customer_id'=>$lead1['customer_id']);
 		}
 		$data['leads'] = $lead_arr;
 		$data['lead_id'] = $lead_id;
 		$opportunity_status_arr = array();

		if($opportunity_id >0)
		{
			$opportunity_arr = $this->Common_model->get_data_row('opportunity',array('opportunity_id'=>$opportunity_id));
			$quoteCount = getOpenQuoteCountforOpportunity($opportunity_id);
			$opportunity_status_arr = @$edit_opportunity_status1;
			if($quoteCount > 0) 
			{
				
				$opportunity_status_arr = $edit_opportunity_status2;
			}
			if(!(@$lead['user_id'] == $this->session->userdata('user_id')) || @$opportunity_arr['status'] == 6 || @$opportunity_arr['status'] == 7 || @$opportunity_arr['status'] == 8 || $leadStatus == 19)
			{
				$opportunity_status_arr = @$edit_opportunity_status3;
			}
		}
		else
		{
			$opportunity_status_arr = @$opportunity_status;
		}
		
		$data['opportunity_status'] = $opportunity_status_arr;
		$this->session->sess_destroy();
		echo json_encode($data);
	}
	public function get_competitors()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $category_id = $post_data['category_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $competitors =  $this->Ajax_m->getCompetitorsByProductCategory($category_id);
        
        $data['competitors'] = $competitors;
        $this->session->sess_destroy();
        echo json_encode($data);
	}
	public function getProductGroup()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['user_id'] = $post_data['user_id'];
		$category_id = $post_data['category_id'];
        
        $results = $this->Product_model->getLoggedInUserProductGroupsDropdown($category_id); 
        foreach($results as $key=>$value)
        {
        	$result_arr[] = array('id'=>$key,'name'=>$value);
        }
        $data['product_group'] = $result_arr;
        $this->session->sess_destroy();
        echo json_encode($data);
	}

	public function getProduct()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['user_id'] = $post_data['user_id'];
		$group_id = $post_data['group_id'];
        $results = $this->Product_model->getLoggedInUserProductsDropdown($group_id);
        foreach($results as $key=>$value)
        {
        	$result_arr[] = array('id'=>$key,'name'=>$value);
        }
        $data['product'] = $result_arr;
        $this->session->sess_destroy();
        echo json_encode($data);
	}
	public function getDecisionMakers()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
		$val = @trim($post_data['val']);
		$customer = @$post_data['customer_id'];
		$_SESSION['company'] = $post_data['company_id'];
		$data['decision_makers'] = getDecisionMakerInfo_api($val, $customer);
		$this->session->sess_destroy();
		echo json_encode($data);
	}
	public function insertOpportunity()
	{
        $json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        goto start_opportunity;
        start_opportunity:
		$lead_id = $post_data['lead_id'];
		$_SESSION['company'] = $post_data['company_id'];
		$_SESSION['user_id'] = $post_data['user_id'];
		if(checkOpportunity($lead_id) == 0)
		{
			$this->session->sess_destroy();
			$data['response'] = "Invalid Lead ID";
			echo json_encode($data);
			header("Status: 400 Bad Request",true,400);
		}
		
		// transaction begin
		$this->db->trans_begin();
		$lead_str_arr = get_current_unique_numbers("opportunity","opp_counter","opportunity_id");
		$opp_counter=$lead_str_arr[0];
		$opp_number=$lead_str_arr[1];
		$demo_required = ($post_data['status'] ==3)?1:0;
		$expected_invoicing_date = ($post_data['expected_invoicing_date'] != '')?$post_data['expected_invoicing_date']:NULL;
		$decision_maker2 = ($post_data['decision_maker2'] != '')?$post_data['decision_maker2']:NULL;
		
		//INSERTING OPPORTUNITY
		$opportunity_data = array( 
					'lead_id'					=>	$lead_id,
					'company_id'                =>  $this->session->userdata('company'),
					'required_quantity'			=>	$post_data['required_quantity'],
					'fund_source_id'			=>	$post_data['source_of_funds'],
					'expected_order_conclusion'	=>	$post_data['expected_order_conclusion'],
					'expected_invoicing_date'	=>	$expected_invoicing_date,
					'decision_maker1'			=>	$post_data['decision_maker1'],
					'decision_maker2'			=>	$decision_maker2,
					'relationship_id'			=>	$post_data['relationship_with_decision_maker'],
					'demo_requirement'			=>	$demo_required,
					'technically_cleared'		=>	1,
					'status'					=>	$post_data['status'],
					'created_by'				=>	$this->session->userdata('user_id'),
					'created_time'				=>	date('Y-m-d H:i:s')
							);
	    try
		{
			check_unique_numbers_constraint('opportunity','opp_counter',$opp_counter);
		}
		catch(Exception $e)
		{
			goto start_opportunity;
		}
		$opportunity_data['opp_counter']=$opp_counter;
		$opportunity_data['opp_number']=$opp_number;
	   //INSERTING OPPORTUNITY PRODUCT
		$productDetails = getProductCostingDetails($post_data['product'],$lead_id);
		$getFinalValue = getFinalValueAfterConversion($productDetails['mrp'],$productDetails['currency_id']); 
		$total_converted_value= $getFinalValue[0];
		$currency_factor=$getFinalValue[1];
		if($total_converted_value==0 && $currency_factor==0 )
		{
			$this->session->sess_destroy();
			$this->db->trans_rollback();
			$data['response'] = "Please Add Currency Conversion Factor in Currency Convertor Screen.Then Try Again !";
			echo json_encode($data);
			header("Status: 400 Bad Request",true,400); exit;
		}
		$opportunityId = $this->Common_model->insert_data('opportunity',$opportunity_data);
		$op_product_data = array( 
					'opportunity_id'		=>	$opportunityId,
					'product_id'			=>	$post_data['product'],
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
		$competitors = $post_data['opportunity_competitors'];
		if(count($competitors)>0){
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
		addOpportunityStatusHistory($opportunityId, $post_data['status']);

		//Updating Lead status as required
		leadStatusUpdate($lead_id);
		$this->session->sess_destroy();
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$data['response'] = "There\'s a problem occured while adding Opportunity!";
				echo json_encode($data);
				header("Status: 400 Bad Request",true,400);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = "Opportunity ".$opp_number." has been added successfully!";
			echo json_encode($data);
			header("Status: 201 Created");
			
		}
	}


	public function updateOpportunity()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
		goto update_opportunity;
        update_opportunity:
        $_SESSION['company'] = $post_data['company_id'];
		$_SESSION['user_id'] = $post_data['user_id'];
		$lead_id = $post_data['lead_id'];
		$opportunity_id = $post_data['opportunity_id'];
		$opportunity_number = $this->Common_model->get_value('opportunity',array('opportunity_id'=>$opportunity_id),'opp_number');
		if(checkOpportunity($lead_id) == 0||$opportunity_id == 0)
		{
			$this->session->sess_destroy();
			$data['response'] = "Invalid Lead ID";
			echo json_encode($data);
			//header("Status: 400 Bad Request",true,400); exit;
		}
		
		// transaction begin
		$this->db->trans_begin();
		$op_where = array('opportunity_id'=>$opportunity_id);
		$demo_required = ($post_data['status']==3)?1:0;
		//GET OPPORTUNITY DATA
		$op_row = $this->Common_model->get_data('opportunity',$op_where);
		$op_required_quantity = $op_row[0]['required_quantity'];
		$post_required_quantity = $post_data['required_quantity'];
		$cur_op_qty = $op_required_quantity; $split_op_qty = 0;
		if($post_data['status'] != 7 && $post_data['status'] != 8)
		{
			if($op_required_quantity!=$post_required_quantity)
			{

				$differ_qty = $post_required_quantity - $op_required_quantity;

				if($differ_qty<0){
					$cur_op_qty = $post_required_quantity;
					$split_op_qty = -($differ_qty);
				}
				else{$split_op_qty = $differ_qty;}

				//INSERTING OPPORTUNITY
				$lead_str_arr = get_current_unique_numbers("opportunity","opp_counter","opportunity_id");
				$expected_invoicing_date = ($post_data['expected_invoicing_date'] != '')?$post_data['expected_invoicing_date']:NULL;
				$decision_maker2 = ($post_data['decision_maker2'] != '')?$post_data['decision_maker2']:NULL;
		        $opp_counter=$lead_str_arr[0];
		        $opp_number=$lead_str_arr[1];
				$opportunity_data = array( 
							'lead_id'					=>	$lead_id,
							'required_quantity'			=>	$split_op_qty,
							'company_id'                =>  $this->session->userdata('company'),
							'fund_source_id'			=>	$post_data['source_of_funds'],
							'expected_order_conclusion'	=>	$post_data['expected_order_conclusion'],
							'expected_invoicing_date'	=>	$expected_invoicing_date,
							'decision_maker1'			=>	$post_data['decision_maker1'],
							'decision_maker2'			=>	$decision_maker2,
							'relationship_id'			=>	$post_data['relationship_with_decision_maker'],
							'demo_requirement'			=>	$demo_required,
							'technically_cleared'		=>	1,
							'status'					=>	$post_data['status'],
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
				$productDetails = getProductCostingDetails($post_data['product'],$lead_id);
				$getFinalValue = getFinalValueAfterConversion($productDetails['mrp'],$productDetails['currency_id']); 
				$total_converted_value= $getFinalValue[0];
				$currency_factor=$getFinalValue[1];
				if($total_converted_value==0 && $currency_factor==0 )
				{
					$this->session->sess_destroy();
					$this->db->trans_rollback();
					$data['response'] = "Please Add Currency Conversion Factor in Currency Convertor Screen.Then Try Again !";
					echo json_encode($data);
					//header("Status: 400 Bad Request",true,400); exit;
				}
				$opportunityId = $this->Common_model->insert_data('opportunity',$opportunity_data);
				$op_product_data = array( 
					'opportunity_id'		=>	$opportunityId,
					'product_id'			=>	$post_data['product'],
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
				$competitors = $post_data['opportunity_competitors'];
				if(count($competitors)>0){
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

				addOpportunityStatusHistory($opportunityId, $post_data['status']);
			}
		}

		
		//UPDATE OPPORTUNITY
		$op_data = array( 
					'required_quantity'			=>	$cur_op_qty,
					'fund_source_id'			=>	$post_data['source_of_funds'],
					'expected_order_conclusion'	=>	$post_data['expected_order_conclusion'],
					'expected_invoicing_date'	=>	$post_data['expected_invoicing_date'],
					'decision_maker1'			=>	$post_data['decision_maker1'],
					'decision_maker2'			=>	($post_data['decision_maker2'] != '')?$post_data['decision_maker2']:NULL,
					'relationship_id'			=>	$post_data['relationship_with_decision_maker'],
					'demo_requirement'			=>	$demo_required,
					'status'					=>	$post_data['status'],
					'modified_by'               =>  $this->session->userdata('user_id'),
					'modified_time'				=>	date('Y-m-d H:i:s')
							);
		if($post_data['status']==7)
		{
			$op_data['oppr_lost_id']=$post_data['opp_lost_reason'];
			$op_data['remarks2']=$post_data['remarks2'];
			$op_data['lost_competitor_id']=$post_data['opp_lost_competitor'];
			$op_data['model']=  $post_data['model'];
			$op_data['remarks3']=$post_data['comp_remarks2'];
		}
		if($post_data['status']==8)
		{
			$op_data['remarks2']=$post_data['remarks2'];
		}
		if($post_data['status'] == 7 || $post_data['status'] == 8)
		{
			$op_data['required_quantity'] = $post_data['required_quantity'];
			$op_data['closed_time']		  = date('Y-m-d H:i:s');
			$op_data['closed_by']		  = $this->session->userdata('user_id');
		}
		$this->Common_model->update_data('opportunity',$op_data,$op_where);

		// DELETING OPPORTUNITY COMPETITORS
		$this->db->delete('opportunity_competitor',array('opportunity_id'=>$opportunity_id));
		//INSERTING OPPORTUNITY COMPETITORS
		$competitors = $post_data['opportunity_competitors'];
		if(count($competitors)>0){
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
		addOpportunityStatusHistory($opportunity_id, $post_data['status']);

		if($post_data['status'] == 7)
		{
			addQuoteStatusByOpportunity($opportunity_id, $post_data['status']);
			leadStatusUpdate($lead_id);
		}

		if($post_data['status'] == 8)
		{
			addQuoteStatusByOpportunity($opportunity_id, $post_data['status']);
			leadStatusUpdate($lead_id);
		}
		
		$new_opp_number = (@$opp_number != '')?@$opp_number:$opportunity_number;
		$this->session->sess_destroy();
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$data['response'] = "There\'s a problem occured while adding Opportunity!";
				echo json_encode($data);
				//header("Status: 400 Bad Request",true,400);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = "Opportunity ".$new_opp_number." has been Updated successfully!";
			echo json_encode($data);
			//header("Status: 201 Created");
			
		}

	}

	public function get_opportunity_category()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);

		$data['category'] = getOpportunityCategory($post_data['status'], $post_data['expected_order_conclusion']); 
		echo json_encode($data);
	}

	public function get_opportunity_probabilitybar()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);

		$data['statusbar'] = getProbabilityBar_api($post_data['opportunity_id']); 
		echo json_encode($data);
	}

	public function get_opportunity_statusbar()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);

		$data['statusbar'] = getOpStatusBar_api($post_data['status'],$post_data['stage']); 
		echo json_encode($data);
	}

	public function opportunityClosed()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company']        = $post_data['company_id'];
        $_SESSION['products']       = $post_data['products'];
        $_SESSION['role_id']        = $post_data['role_id'];
        $_SESSION['reportees']      = $post_data['reportees'];
        $_SESSION['user_id']        = $post_data['user_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $searchParams=array(
					  'opportunity_id'   => $post_data['opportunity_id'],
					  'customer'         => $post_data['customer'],
					  'product_id'       => $post_data['category_id'],
					  'source_of_lead'   => $post_data['source_of_lead'],
					  'region_id'        => $post_data['region_id'],
					  'created_user'     => $post_data['created_user'],
					  'opp_status'       => $post_data['opp_status'],
					  'opp_category'     => $post_data['opp_category'],
                      'start_date'       => $post_data['start_date'],
                      'end_date'         => $post_data['end_date'],
                      'order_start_date' => $post_data['order_start_date'],
                      'order_end_date'   => $post_data['order_end_date'],
                      'text_search'      => $post_data['text_search'],
                      'search_option'    => $post_data['search_option']
					 		  );
        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords(); 
        # Search Results
	   	$data['searchResults'] = $this->Lead_model->opportunityResults($searchParams,$config['per_page'], $current_offset, 2);
	   	$data['category_list'] = $this->Common_model->get_data('product_category',array('company_id'=>$this->session->userdata('company')),array('category_id','name'));
	   	$stage_list = $this->Common_model->get_dropdown('opportunity_status', 'status', 'name','status IN (6,7,8)');
	   	foreach($stage_list as $key=>$value)
	   	{
	   		$stage_list_arr[] = array('id'=>$key,'name'=>$value);
	   	}
	   	$data['stage_list'] = $stage_list_arr;
	   	$opp_category = array(1 =>'Hot', 2 => 'Warm', 3 => 'Cold'); 
	   	foreach ($opp_category as $key => $value) 
	   	{
	   		$opp_category_arr[] = array('id'=>$key,'name'=>$value); 
	   	}
	   	$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1),array('location_id','location'));
	   	$data['opp_category'] = $opp_category_arr;
	   	$data['source_of_leads'] = $this->Common_model->get_data('source_of_lead',array('status'=>1),array('source_id','name'));
	   	# required for RBH role
	   	//$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);
	   	//$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
	   	foreach(search_types() as $key => $value)
	   	{
            $search_in_list[] = array('id'=>$key,'name'=> 'Search In '.$value);
        }
        $data['search_in_list'] = $search_in_list;
        $this->session->sess_destroy();
		echo json_encode($data);
	}

	public function open_opportunities()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company']        = $post_data['company_id'];
        $_SESSION['products']       = $post_data['products'];
        $_SESSION['role_id']        = $post_data['role_id'];
        $_SESSION['reportees']      = $post_data['reportees'];
        $_SESSION['user_id']        = $post_data['user_id'];
        $_SESSION['locationString'] = $post_data['locationString'];

        $searchParams=array(
					  'opportunity_id'   => $post_data['opportunity_id'],
					  'customer'         => $post_data['customer'],
					  'product_id'       => $post_data['category_id'],
					  'source_of_lead'   => $post_data['source_of_lead'],
					  'region_id'        => $post_data['region_id'],
					  'created_user'     => $post_data['created_user'],
					  'opp_status'       => $post_data['opp_status'],
					  'opp_category'     => $post_data['opp_category'],
                      'start_date'       => $post_data['start_date'],
                      'end_date'         => $post_data['end_date'],
                      'order_start_date' => $post_data['order_start_date'],
                      'order_end_date'   => $post_data['order_end_date'],
                      'text_search'      => $post_data['text_search'],
                      'search_option'    => $post_data['search_option']
					 		  );

        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords(); 

        $searchResults = $this->Lead_model->opportunityResults($searchParams,$config['per_page'], $current_offset);
        $search_arr = array();
        if(count($searchResults)>0)
        {
        	foreach($searchResults as $key=>$value)
	        {
	        	$search_arr[] = $value;
	        	$search_arr[$key]['category'] = getOpportunityCategory($value['status'],$value['oDate']);
	        }
        }
        $data['searchResults'] = $search_arr;

        $data['category_list'] = $this->Common_model->get_data('product_category',array('company_id'=>$this->session->userdata('company')),array('category_id','name'));
	   	$stage_list = $this->Common_model->get_dropdown('opportunity_status', 'status', 'name','status IN (6,7,8)');
	   	foreach($stage_list as $key=>$value)
	   	{
	   		$stage_list_arr[] = array('id'=>$key,'name'=>$value);
	   	}
	   	$data['stage_list'] = $stage_list_arr;
	   	$opp_category = array(1 =>'Hot', 2 => 'Warm', 3 => 'Cold'); 
	   	foreach ($opp_category as $key => $value) 
	   	{
	   		$opp_category_arr[] = array('id'=>$key,'name'=>$value); 
	   	}
	   	$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1),array('location_id','location'));
	   	$data['opp_category'] = $opp_category_arr;
	   	$data['source_of_leads'] = $this->Common_model->get_data('source_of_lead',array('status'=>1),array('source_id','name'));

	   	# Required for RBH role
	   	//$data['s_created_user'] = $this->Lead_model->getSearchUser(@$searchParams['created_user']);
	   	//$data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
	   	foreach(search_types() as $key => $value)
	   	{
            $search_in_list[] = array('id'=>$key,'name'=> 'Search In '.$value);
        }
        $data['search_in_list'] = $search_in_list;


        $this->session->sess_destroy();
		echo json_encode($data);

	}

	public function get_life_time()
	{
		
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);

		$data['life_time'] = get_opp_life_time($post_data['oCTime'],$post_data['opp_mtime'],$post_data['status']); 
		echo json_encode($data);

	}

	public function view_opportunity()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $opportunity_id = $post_data['opportunity_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $opResults = $this->Opportunity_model->getOpportunityResultsByLead($opportunity_id, 2);
      	$row = $opResults[0];
      	$op_comp_res =getOpportunityCompetitors($row['opportunity_id']);
      	$selected_competitors = array();
      	if(count($op_comp_res)>0)
		{
			$op_competitors_arr = array();
			foreach ($op_comp_res as $comp) 
			{
				$op_competitors_arr[] = array('opportunity_id'=>$comp['opportunity_id'],'competitor_id'=>$comp['competitor_id']);
			}
			$selected_competitors = $op_competitors_arr;
		}
		$data['decision_maker1'] = ($row['decision_maker1']>0)?getDecisionMakerDetails($row['decision_maker1']):'';
		$data['decision_maker2'] = ($row['decision_maker2']>0)?getDecisionMakerDetails($row['decision_maker2']):'';
		$data['opportunity_results'] = $row;
		$data['selected_competitors'] = $selected_competitors;
		$this->session->sess_destroy();
		echo json_encode($data);
	}

	public function getReporteesWithUser()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['reportees'] = $post_data['reportees'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
		$val = @trim($post_data['val']);
		$level = 0;
		$data['reportees'] = $this->ajax_model->getReporteesWithUser($val, $level);
		$this->session->sess_destroy();
		echo json_encode($data);
	}

	public function getRBH()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $customer_id = @$post_data['customer_id'];
		$role_id = @$post_data['role_id'];
		$r = getReporteeRoles($role_id);
		$l = getCustomerLocation($customer_id);
		$data['reportees'] = $this->ajax_model->getReportees_api($l, $r);
		echo json_encode($data);
	}
}