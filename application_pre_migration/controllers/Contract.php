<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Contract extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Contract_model");
		$this->load->model("quote_model");
        $this->load->library('user_agent');
	}

	public function openCNoteDetails($encoded_id)
	{
		$lead_id = @icrm_decode($encoded_id);
		if(checkCNote($lead_id) == 0)
		{
			redirect(SITE_URL.'openLeads');
		}
		$leadStatus = getLeadStatusID($lead_id);

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
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead ID - '.$lead_id,'class'=>'active','url'=>'');

		$data['leadStatus'] = $leadStatus;
		$data['pageDetails'] = 'CNote';
		$data['lead_id'] = $lead_id;
        $data['product_id'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', [], 'concat(name, "( ", description, ")") name');    
        
        $data['products'] = $this->Common_model->get_data('product',array('status'=>1));
        //echo '<pre>'; print_r($data['products'] ); exit;
        $search_fields = 0;
        $cNoteDetails = array();
        $cNoteDetails = $this->Contract_model->getCNoteDetails($lead_id);

        # Loading the data array to send to View
        $data['cNoteDetails'] = @$cNoteDetails['resArray'];
        
        $data['count'] = @$cNoteDetails['count'];

        #For add quote popup

        $lead_user_id = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user_id");
        $lead_user_role_id = getUserRole($lead_user_id);

        $data['quoteDetails'] = $this->Contract_model->getAllLeadQuotes($lead_id);

        //die();
        $data['lead_user_id'] = $lead_user_id;

        $data['checkPage'] = 1;//1 for Open Pages. 0 for Closed Pages		

		$this->load->view('lead/openCNoteDetailsView', $data);
		//redirect(SITE_URL.'openLeads');
	}

    public function closedCNoteDetails($encoded_id)
    {
        $lead_id = @icrm_decode($encoded_id);
        if(checkClosedLead($lead_id) == 0)
        {
            redirect(SITE_URL.'closedLeads');
        }
        $leadStatus = getLeadStatusID($lead_id);

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
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead ID - '.$lead_id,'class'=>'active','url'=>'');

        $data['leadStatus'] = $leadStatus;
        $data['pageDetails'] = 'CNote';
        $data['lead_id'] = $lead_id;
        $data['product_id'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', [], 'concat(name, "( ", description, ")") name');
        $search_fields = 0;
        $cNoteDetails = array();
        $cNoteDetails = $this->Contract_model->getCNoteDetails($lead_id);

        # Loading the data array to send to View
        $data['cNoteDetails'] = @$cNoteDetails['resArray'];
        
        $data['count'] = @$cNoteDetails['count'];

        #For add quote popup

        $lead_user_id = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user_id");
        $lead_user_role_id = getUserRole($lead_user_id);

        $data['quoteDetails'] = $this->Contract_model->getAllLeadQuotes($lead_id);

        //die();
        $data['lead_user_id'] = $lead_user_id;  

        $data['checkPage'] = 0;//1 for Open Pages. 0 for Closed Pages    

        $this->load->view('lead/openCNoteDetailsView', $data);
        //redirect(SITE_URL.'openLeads');
    }


	// updated on 11-10-2018 (channel partner)
    public function cNoteAdd()
    {
        if ($this->input->post('submitCNote') != "") 
        {   $quote_id = $this->input->post('quote_id');
            $quotes=implode(",", $quote_id);
            $count_type = $this->Contract_model->check_for_multiple_quotes($quotes);
            if($count_type==0)
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-times"></i></div>
                                        <strong>Error!</strong> Please make sure that selected quotes should have same Billing through.
                                     </div>');
                    redirect($this->agent->referrer());  
                    exit; 
            }
            if(count($quote_id)>1)
            {
                // Get quote revisions info
                $quote_revisions = $this->Contract_model->getQuoteRevisionsInfo($quote_id);
                //echo '<pre>';print_r($quote_revisions); echo '</pre>';//exit;
                $hasError = FALSE; $i=1;
                foreach($quote_revisions as $qr_row)
                {
                    if($i==1)
                    {
                        $first_quote = $qr_row;
                    }
                    else
                    {
                        if($first_quote['warranty']!=$qr_row['warranty']||$first_quote['advance_type']!=$qr_row['advance_type']||$first_quote['advance']!=$qr_row['advance']||$first_quote['balance_payment_days']!=$qr_row['balance_payment_days']||$first_quote['dealer_commission']!=$qr_row['dealer_commission']||$first_quote['dealer_id']!=$qr_row['dealer_id'])
                        {
                            $hasError = TRUE; break;
                        }
                    }
                    $i++;
                }

                if($hasError)
                {
                    //exit('error');
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Message!</strong> Please make sure payment terms (Warranty, Advance, Balance Payment Days, Dealer Commision, Dealer) to be same for all quotes in a contract note. <br> If you have different terms generate them as seperate contract notes.
                                     </div>');
                    redirect($this->agent->referrer());  
                    exit; 
                }
            }
            
            $this->db->trans_begin();
            
            /*phase 2 changes 
              updated by prasad 
              updating status to 3 for contract note approval  by rbh
            */
            //print_r($quote_id); exit();
            $lead_id=$this->input->post('lead_id');
            $customer_id=$this->Common_model->get_value('lead',array('lead_id'=>$lead_id),'customer_id');
            $customer_cnotes=$this->Contract_model->get_customer_previous_data($customer_id);
            //echo $customer_id; exit();
            //echo "<pre>"; echo count($customer_cnotes); exit();
            if(count(@$customer_cnotes)>0)
            {
                $business_type=2;
            }
            else
            {
                $business_type=1;
            }
            $dataArr2 = array(
                "purchase_order_no" => $this->input->post('po_number'),
                "date_of_purchase_order" => $this->input->post('po_date'),
                "institution_code" => $this->input->post('institution_code'),
                "billing_to_party" => $this->input->post('billing_to_party'),
                "created_by" => $_SESSION['user_id'],
                'status'    => 3,
                'business_type'=>$business_type,
                'delivery_period'           => $this->input->post('delivery_period'),
                'ld_applicable_date'        => $this->input->post('ld_applicable'),
                'warranty'                  => $this->input->post('warranty'),
                'amendment'                 => $this->input->post('amendment'),
                'reason_for_amendment'      => $this->input->post('reason_for_amendment'),
                'other_conditions'          => $this->input->post('other_conditions')
            );

            $contract_note_id = $this->Common_model->insert_data('contract_note', $dataArr2);
            // C-Note Status history
            addCnoteStatusHistory($contract_note_id,3);
            
            $quote_ids = $this->input->post('quote_id',TRUE);
            //print_r($quote_ids);die();
            $quotes = implode(",", $quote_ids);
            if($quotes == '') $quotes = 0;
            $quote_id_arr = array();
            if($quote_ids)
            {
                //updating quote status to 5 && quote_history to 5 for RBH approval modified by prasad  previously status in both tables was 3.
                $cn_quote_data = array(); 
                foreach ($quote_ids as $quote) 
                {
                    $cn_quote_data[] = array( 
                        'contract_note_id'      =>  $contract_note_id,
                        'quote_revision_id'         =>  $quote);
                    $quote_id = $this->Common_model->get_value('quote_revision', array('quote_revision_id' => $quote), 'quote_id');
                    $where = array('quote_id' => $quote_id);
                    $updateData = array('status' => 3,
                                        'modified_by' => $this->session->userdata('user_id'),
                                        'modified_time' => date('Y-m-d H:i:s'));
                    $this->Common_model->update_data('quote', $updateData, $where);
                    addQuoteStatusHistory($quote_id, 3);
                    //Rejecting quotes revisions which are in waiting for approval
                    updateOtherQuoteRevisionStatus($quote_id, 3, 2); // Updating revision 3 to 2
                    $quote_id_arr[] = $quote_id;
                }
                if(count($cn_quote_data)>0)
                $this->Common_model->insert_batch_data('contract_note_quote_revision',$cn_quote_data);
            }

            // Get Quote free supply items
            if(count($quote_ids)>0)
            {
                $free_products = $this->Contract_model->getQuoteFreeSupplyItems($quote_ids);
                if($free_products)
                {
                    $free_items_array = array();
                    $l = 0;
                    foreach($free_products as $prow)
                    {
                        if($prow['product_id'] != '' && $prow['quantity'] != '')
                        {
                            $free_items_array[]=array(
                                    "contract_note_id" => $contract_note_id,
                                    "product_id"=>$prow['product_id'],
                                    "quantity"=>$prow['quantity'],
                                    "unit_price"=>$prow['unit_price']);  
                            $l++;
                        }
                    }
                    if($l > 0)
                    $this->Common_model->insert_batch_data('free_products',$free_items_array);
                    }
            }
            


            $q = "SELECT qd.opportunity_id, group_concat(qd.quote_id) as quote, max(qr.discount) discount from quote_details qd
                 INNER JOIN quote q ON q.quote_id = qd.quote_id
                 INNER JOIN quote_revision qr ON qr.quote_id = q.quote_id
                 WHERE qr.quote_revision_id IN (".$quotes.") group by opportunity_id";
            $r = $this->db->query($q);
            //echo $q; die();
            foreach ($r->result_array() as $op_id) 
            {
                $op_product_update = getProductDetailsForOpprotunity($op_id['opportunity_id']);
                $op_product_update['discount'] = $op_id['discount'];
                $op_pro_where = array('opportunity_id' => $op_id['opportunity_id']);
                $this->Common_model->update_data('opportunity_product',$op_product_update,$op_pro_where);

               addOpportunityStatusByQuote($op_id['opportunity_id'], 3);
               updateOtherQuotes($op_id['opportunity_id'], $op_id['quote']);
               update_closed_time_opportunity_status($op_id['opportunity_id']);
            }
             /*phase 2 changes prasad updating status after rbh approval */  
           leadStatusUpdate($this->input->post('lead_id'));

            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while adding Contact Note!
                                     </div>');
                redirect($this->agent->referrer());
                    
            }
            else
            {
                $this->db->trans_commit();
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> Contract Note has been Added successfully!
                                     </div>');
                redirect($this->agent->referrer());
            }
        }
    }

public function deleteContractNote($encoded_id)
    {
        //echo 'hi';
            $contract_note_id=@icrm_decode($encoded_id);
            $where = array('contract_note_id' => $contract_note_id);
            $c_lead_id=$this->Contract_model->get_lead_id($contract_note_id);
            //print_r($c_lead_id); exit;
            

            $this->db->trans_begin();
            

            // Update Quote Status
            $qry1 = ' UPDATE quote q 
                      JOIN quote_revision qr ON qr.quote_id = q.quote_id
                      JOIN contract_note_quote_revision cnqr ON cnqr.quote_revision_id = qr.quote_revision_id
                      SET q.status = 2
                      WHERE cnqr.contract_note_id = '.$contract_note_id;

            $this->db->query($qry1);
            //echo $this->db->last_query().'<br>';
            // Update Opportunity Status
            $qry2 = ' UPDATE opportunity o 
                      JOIN quote_details qd ON qd.opportunity_id = o.opportunity_id
                      JOIN quote q ON q.quote_id = qd.quote_id 
                      JOIN quote_revision qr ON qr.quote_id = q.quote_id
                      JOIN contract_note_quote_revision cnqr ON cnqr.quote_revision_id = qr.quote_revision_id
                      SET o.status = 5
                      WHERE cnqr.contract_note_id = '.$contract_note_id;
            $this->db->query($qry2);
            //echo $this->db->last_query().'<br>';
            $cnote_details = get_details_by_cnoteId($contract_note_id);
            //echo $this->db->last_query().'<br>';
            //echo '<pre>';print_r($cnote_details); exit;
            $lead_arr = $opp_arr = $quote_arr = array();
            //looping cnote details
            foreach ($cnote_details as $row) {
                $lead_arr[$row['lead_id']] = array('lead_id'=>$row['lead_id'],'status'=>$row['lead_status']);
                $opp_arr[$row['opportunity_id']] = $row['opportunity_id'];
                $quote_arr[$row['quote_id']] = $row['quote_id'];
            }
            $new_quote_status = 2;
            $new_opportunity_status = 5; $new_lead_status = 6;
            // looping quote array and insert quote status history
            foreach ($quote_arr as $quote_id => $qid) {
                addQuoteStatusHistory($quote_id,$new_quote_status);
            }
            // looping opportunity array and insert new opportunity status history
            foreach ($opp_arr as $opportunity_id => $opid) {
                // Removing closed won status from opportunity history
               $this->db->delete('opportunity_status_history',array('status'=>6,'opportunity_id'=>$opportunity_id));
            }

            
            // looping lead array and insert new lead status history
            foreach ($lead_arr as $lead_id => $lrow) 
            {
                $no_of_cnotes = get_cnoteCountByLeadId($lead_id);
                if($no_of_cnotes>1)
                {
                    if($lrow['status']==10||$lrow['status']==9)
                        $new_lead_status = 9;
                    else 
                        $new_lead_status = 8;
                }
                else{
                    if($lrow['status']==10||$lrow['status']==9)
                        $new_lead_status = 7;
                    else 
                        $new_lead_status = 6;
                }
                // Add lead status history
                addLeadStatusHistory($lead_id,$new_lead_status);
                // UPDATE lead status
                $data_arr = array('status'=>$new_lead_status,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d H:i:s'));
                $where_arr = array('lead_id'=>$lead_id);
                $this->Common_model->update_data('lead',$data_arr,$where_arr);

                // Deleting Free Products
                $this->db->delete('free_products', $where);
                // Deleting contract note Quote revision 
                $this->db->delete('contract_note_quote_revision', $where);
                // Deleting contract note Status history
                $this->db->delete('contract_note_status_history', $where);
                // Deleting Contract Note
                $res = $this->db->delete('contract_note', $where);
            }
            $c_customer_id=$this->Common_model->get_value('lead',array('lead_id'=>$c_lead_id['lead_id']),'customer_id');
            $first_cnote=$this->Contract_model->get_first_cnote($c_customer_id);
            if($first_cnote['first_cnote']!='')
            {
                $this->Common_model->update_data('contract_note',array('business_type'=>1),array('contract_note_id'=>$first_cnote['first_cnote']));
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while adding Contact Note!
                                     </div>');
            }
            else
            {
                $this->db->trans_commit();
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Contract Note '.$contract_note_id.' has been Deleted successfully!
                                 </div>');
            }
            redirect(SITE_URL.'manageContractNotes');

    }

    public function soEntryOpen()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "SO Entry Open";
        $data['nestedView']['cur_page'] = 'soEntryOpen';
        $data['nestedView']['parent_page'] = 'soEntryOpen';
        
        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/manage_soentry.js"></script>';

        $data['nestedView']['css_includes'] = array();
        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'So Entry Open';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'SO Entry Open','class'=>'','url'=>SITE_URL.'soEntryOpen');

        # Search Functionality
        $psearch=$this->input->post('search', TRUE);
        if($psearch!='') {
        $searchParams=array(
                      'contract_note_id'=>$this->input->post('contract_note_id'),
                      'cnote_type'=>$this->input->post('cnote_type'),
                      'billing_party'=>$this->input->post('billing_party')
                      );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'contract_note_id'=>$this->session->userdata('contract_note_id'),
                      'cnote_type'=>$this->session->userdata('cnote_type'),
                      'billing_party'=>$this->session->userdata('billing_party')
                      );
            }
            else {
                $searchParams=array(
                      'contract_note_id'=>'',
                      'cnote_type'=>'',
                      'billing_party'=>'',
                       );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;


        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $data['form_action'] = $config['base_url'] = SITE_URL . 'soEntryOpen/';
        # Total Records
        $config['total_rows'] = $this->Contract_model->soEntryTotalRows(1,$searchParams);

        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        $current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        if ($data['pagination_links'] != '') {
            $data['last'] = $this->pagination->cur_page * $config['per_page'];
            if ($data['last'] > $data['total_rows']) {
                $data['last'] = $data['total_rows'];
            }
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $config['per_page']) + 1) . ' to ' . ($data['last']) . ' of ' . $data['total_rows'];
        }
        $data['sn'] = $current_offset + 1;
        /* pagination end */

        # Loading the data array to send to View
        $data['cNoteDetails'] = $this->Contract_model->soEntryResults(1,$searchParams,$current_offset,$config['per_page']);
        //print_r($data['cNoteDetails']); exit;
        $data['status'] = 1;
        //print_r($data['cNoteDetails'] );exit();
        $this->load->view('lead/soEntryView', $data);
    }

    public function soEntryClose()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "SO Entry Close";
        $data['nestedView']['cur_page'] = 'soEntryClose';
        $data['nestedView']['parent_page'] = 'soEntryClose';
        
        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';

        $data['nestedView']['css_includes'] = array();
        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'So Entry Close';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'SO Entry Close','class'=>'','url'=>SITE_URL.'soEntryClose');

        # Search Functionality
        $psearch=$this->input->post('search', TRUE);
        if($psearch!='') {
        $searchParams=array(
                      'contract_note_id'=>$this->input->post('contract_note_id'),
                      'cnote_type'=>$this->input->post('cnote_type'),
                      'billing_party'=>$this->input->post('billing_party')
                      );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'contract_note_id'=>$this->session->userdata('contract_note_id'),
                      'cnote_type'=>$this->session->userdata('cnote_type'),
                      'billing_party'=>$this->session->userdata('billing_party')
                      );
            }
            else {
                $searchParams=array(
                      'contract_note_id'=>'',
                      'cnote_type'=>'',
                      'billing_party'=>'',
                       );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;


        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $data['form_action'] = $config['base_url'] = SITE_URL . 'soEntryClose/';
        # Total Records
        $config['total_rows'] = $this->Contract_model->soEntryTotalRows(2,$searchParams);

        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        $current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        if ($data['pagination_links'] != '') {
            $data['last'] = $this->pagination->cur_page * $config['per_page'];
            if ($data['last'] > $data['total_rows']) {
                $data['last'] = $data['total_rows'];
            }
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $config['per_page']) + 1) . ' to ' . ($data['last']) . ' of ' . $data['total_rows'];
        }
        $data['sn'] = $current_offset + 1;
        /* pagination end */

        # Loading the data array to send to View
        $data['cNoteDetails'] = $this->Contract_model->soEntryResults(2,$searchParams,$current_offset,$config['per_page']);
        //$data['cNoteDetails'] = $this->Contract_model->soEntry(2);
        $data['status'] = 2;
        
        //print_r($data['cNoteDetails'] );exit();
        $this->load->view('lead/soEntryView', $data);
    }

    //mahesh 14th july 2016 8:20 PM
    public function download_soEntry()
    {
        if($this->input->post('download_so')!='') {
            
            $searchParams=array(
              'contract_note_id'=>$this->input->post('contract_note_id'),
              'cnote_type'=>$this->input->post('cnote_type'),
              'billing_party'=>$this->input->post('billing_party')
              );
            $status = $this->input->post('status');
            $results = $this->Contract_model->soEntryDetails($status,$searchParams);
            
            $header = '';
            $data ='';
            if($status==1) {
                $titles = array('Contract Note ID','PO Number','SO Number');
            }
            else{
                $titles = array('Contract Note ID','C-Note Type','Customer Name','Sales Engineer','PO Number','PO Date','SO Number','Created On','Created By','Modified On','Modified By');
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
            
            if(count($results)>0)
            {
                
                foreach($results as $row)
                {
                    if($status==1){
                        $data.='<tr>';
                        $data.='<td>'.$row['contract_note_id'].'</td>';
                        $data.='<td>'.$row['po_number'].'</td>';
                        $data.='<td></td>';
                        $data.='</tr>';
                    }
                    else{

                        $data.='<tr>';
                            $data.='<td valign="top" class="text-center">'.@$row['contract_note_id'].'</td>';
                            $data.='<td valign="top" align="cneter">'.getCNoteTypeLable($row['cnote_type']).'</td>';
                            $data.='<td valign="top" align="cneter">'.@$row['customer_name'].'</td>';
                            $data.='<td valign="top" align="cneter">'.@$row['lead_owner_name'].'</td>';
                            $data.='<td valign="top" align="cneter">'.@$row['po_number'].'</td>';
                            $data.='<td valign="top" align="cneter">'.@$row['po_date'].'</td>';
                            $data.='<td valign="top" align="cneter">'.@$row['so_number'].'</td>';
                            $data.='<td valign="top" align="cneter">'.@$row['created_time'].'</td>';
                            $data.='<td valign="top" align="cneter">'.getUserName(@$row['created_by']).'</td>';
                            $data.='<td valign="top" align="cneter">'.@$row['modified_time'].'</td>';
                            $data.='<td valign="top" align="cneter">'.getUserName(@$row['modified_by']).'</td>';
                        $data.='</tr>';
                    }
                    
                }
            }
            else
            {
                $data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile='soEntry_'.$time.'.xls'; 
            header("Content-type: application/x-msdownload"); 
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=".$xlFile."");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
            
        }
    }

    // mahesh 14th july 2016 9:12 pm
    public function bulkUpload_soEntry(){


        $filename= $_FILES["uploadCsv"]["tmp_name"];
        $allQrys = '';
         if($_FILES["uploadCsv"]["size"] > 0)
         {
             
            $file = fopen($filename, "r");
            $i=0;
            $j=0;
            $k=0;
            $this->db->trans_begin();
            // GET USER OPEN SO ENTRIES
            $this->db->where('status',1);
            $this->db->where('created_by',$this->session->userdata('user_id'));
            $res = $this->db->get('contract_note');
            $user_cids = array();
            if($res){
                foreach ($res->result_array() as $cnote_row) {
                    $user_cids[] = $cnote_row['contract_note_id'];
                }
            }
            //print_r($user_cids); exit;
             while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
             {
                
                 if($j==0) {
                     $j++;
                     continue;
                 }
                
                $contract_note_id = $emapData[0];
                $so_entry_num = $emapData[2];
                if($so_entry_num != '')
                {
                    // Execute only if logged in user contract note ids SO Entries OR user role is Admin or CIC
                    if(in_array($contract_note_id, $user_cids)||$this->session->userdata('role_id') == 2||$this->session->userdata('role_id') == 14){
                        $data = array('SO_number'=>$so_entry_num,'status'=>2,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d H:i:s'));
                        $where = array('contract_note_id'=>$contract_note_id, 'status' => 1);
                        //update so number
                        $var = $this->Common_model->update_data('contract_note',$data,$where);
                        if($var > 0) $k++;
                        //echo $this->db->last_query();
                    }
                }
              
             }
             fclose($file);
            
            
         }

         //exit;
        if($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Error!</strong> There\'s a problem occured while uploading so entries!
                                 </div>');
            redirect(SITE_URL.'soEntryOpen');
            //echo 'transaction failed';
                
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> '.$k.' SO Entries uploaded successfully!
                                 </div>');
            redirect(SITE_URL.'soEntryOpen');
            //echo 'transaction success';
        }
    }

    //mahesh 14th july 2016 10:14 pm 
    public function insert_soEntry(){

        if($this->input->post('insert_so')!=''){

            $contract_note_ids = $this->input->post('contratct_note_id');
            if(count($contract_note_ids)>0){
                $this->db->trans_begin(); $i=0;
                foreach ($contract_note_ids as $contract_note_id) {
                    
                    $so_entry_num = $this->input->post('so_number'.$contract_note_id);
                    if($so_entry_num!=''&&$contract_note_id!=''){
                        $data = array('SO_number'=>$so_entry_num,'status'=>2,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d H:i:s'));
                        $where = array('contract_note_id'=>$contract_note_id);
                        //update so number
                        $this->Common_model->update_data('contract_note',$data,$where);
                        // C-Note Status history
                        addCnoteStatusHistory($contract_note_id,2);
                        $i++;
                        //echo $this->db->last_query();
                    }
                }

                if($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> There\'s a problem occured while inserting so entries!
                                         </div>');
                        
                }
                else
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Success!</strong> '.$i.' So entries added successfully!
                                         </div>');
                }
            }

            
            redirect(SITE_URL.'soEntryOpen');
        }
    }

    /** new enhancement: Suresh 20th april 2017  START **/

    public function manageContractNotes()
    {
        

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Contract Notes";
        $data['nestedView']['cur_page'] = 'manageContractNotes';
        $data['nestedView']['parent_page'] = 'manageContractNotes';
        
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
        $data['nestedView']['breadCrumbTite'] = 'Contract Notes';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'Contract Notes','class'=>'','url'=>SITE_URL.'Contract Notes');
        //$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Lead ID - '.$lead_id,'class'=>'active','url'=>'');

        
        $data['pageDetails'] = 'CNote';
        
        //$data['product_id'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', [], 'concat(name, "( ", description, ")") name');    
        //$searchParams = $this->input->post('searchCNote', TRUE);
        if($this->input->post('searchCNote') != ''){
            $searchParams = array(
              'contract_note_id'=>$this->input->post('contract_note_id')
            );
        }
        else 
        {
            if($this->uri->segment(2)!='')
            {
                $searchParams=array(
                      'contract_note_id'=>$this->session->userdata('contract_note_id')
                              );
            }
            else {
                $searchParams = array(
                  'contract_note_id'=>''
                );
            }
        }
        //print_r($searchParams); exit;
        $this->session->set_userdata($searchParams);
        $data['searchParams'] = $searchParams;
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'manageContractNotes/'; 
        # Total Records
        $config['total_rows'] = $this->Contract_model->manageCNoteDetailsTotalRows($searchParams);
         
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
         //$data['sn'] = $current_offset + 1;
        /* pagination end */
        
        //$search_fields = 0;
        $cNoteDetails = array();
        $cNoteDetails = $this->Contract_model->manageCNoteDetails($searchParams,$config['per_page'], $current_offset);
        //echo '<pre>'; print_r($cNoteDetails); exit;
        # Loading the data array to send to View
        $data['cNoteDetails'] = @$cNoteDetails['resArray'];
        
        $data['count'] = @$cNoteDetails['count'];

        #For add quote popup

        //$lead_user_id = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user_id");
        //$lead_user_role_id = getUserRole($lead_user_id);

        ///////$data['quoteDetails'] = $this->Contract_model->manageAllLeadQuotes();
        
        //die();
        //$data['lead_user_id'] = $lead_user_id;

        $data['checkPage'] = 1;//1 for Open Pages. 0 for Closed Pages       

        $this->load->view('lead/cNoteDetailsView', $data);
        //redirect(SITE_URL.'openLeads');
    }
    
    

    /** new enhancement: Suresh 20th april 2017  END **/
    // Channel partner: 11-10-2018
    public function check_quotes()
    {
        $quotes = $this->input->post('quotes');
        $count_type = $this->Contract_model->check_for_multiple_quotes($quotes);
        echo $count_type;
    }
}