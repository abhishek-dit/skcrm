<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Quote extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdminModel");
        $this->load->model("quote_model");
        $this->load->model("common_model");
        $this->load->library('Pdf');
        $this->load->library('numbertowords');
        $this->load->library('user_agent');
    }

    public function openQuoteDetails($encoded_id, $page = "") {
        $lead_id = @icrm_decode($encoded_id);

        if (checkQuote($lead_id) == 0) {
            redirect(SITE_URL . 'openLeads');
        }
        $leadStatus = getLeadStatusID($lead_id);
        $lead_number = $this->Common_model->get_value('lead',array('lead_id'=>$lead_id),'lead_number');

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Open Leads";
        $data['nestedView']['cur_page'] = 'openLeads';
        $data['nestedView']['parent_page'] = 'openLeads';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/lead.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/quote.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Open Leads';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Open Leads', 'class' => '', 'url' => SITE_URL . 'openLeads');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_number, 'class' => 'active', 'url' => '');

        $data['leadStatus'] = $leadStatus;
        $data['pageDetails'] = 'Quote';
        $data['lead_id'] = $lead_id;


        $search_fields = 0;
        $quoteSearch = array();
        $quoteResults = $this->quote_model->getQuoteDetailsByLead($lead_id);
        //print_r($quoteResults);die;
        if($quoteResults)
        {
            foreach ($quoteResults as $row) 
            {
                
                $quoteSearch[$row['quote_revision_id']][] = $row;
            }
        }
        //die;
        //print_r($quote_revision_id);die;
        //echo $this->db->last_query(); exit;
        # Loading the data array to send to View
        $data['quoteSearch'] = @$quoteSearch;
        //print_r($data['quoteSearch']);die;
        #For add quote popup
        $data['opportunities'] = $this->quote_model->get_opportunities($lead_id);
        //print_r($data['opportunities']);die;
        $data['productCategories'] = $this->quote_model->getProductCategories($lead_id);
        $lead_user_id = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user_id");
        $lead_user_role_id = getUserRole($lead_user_id);
        //print_r($lead_user_role_id);die;
        $lead_user2 = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user2");
        $where_con = array();
        if($lead_user_role_id=='4')
        {
           $where_con = array('billing_info_id!=' => 3); // Not including stockist
        }
        else if ($lead_user_role_id != '4' && $lead_user2 == NULL) {
            $where_con = array('billing_info_id!=' => 2); // Not including distributor
        } 

        /*Fetching New Channel Partners */
        $data['channel_partners']=$this->Common_model->get_data('channel_partner',array('company_id'=>$this->session->userdata('company'),'status'=>1));
        $data['lead_user_role_id']=$lead_user_role_id;
        $data['lead_user_id'] = $lead_user_id;
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name", $where_con);

        $data['checkPage'] = 1;//1 for Open Pages. 0 for Closed Pages
        /* Mahesh Phase2 Capture additional terms in quote start */
        $data['products'] = $this->Common_model->get_data('product',array('status'=>1,'company_id'=>$this->session->userdata('company')));
        $data['discount_types'] = get_advance_types();
        $data['dealers']    =   $this->quote_model->getDistributors();
        $data['login_user_role_id'] = $_SESSION['role_id'];
        /* Mahesh Phase2 Capture additional terms in quote END */        
        $this->load->view('lead/openQuoteDetailsView', $data);
        //redirect(SITE_URL.'openLeads');
    }

    public function closedQuoteDetails($encoded_id, $page = "") {
        $lead_id = @icrm_decode($encoded_id);

        if (checkclosedLead($lead_id) == 0) {
            redirect(SITE_URL . 'closedLeads');
        }
        $leadStatus = getLeadStatusID($lead_id);

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Closed Leads";
        $data['nestedView']['cur_page'] = 'closedLeads';
        $data['nestedView']['parent_page'] = 'closedLeads';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/lead.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/quote.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Closed Leads';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Closed Leads', 'class' => '', 'url' => SITE_URL . 'closedLeads');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

        $data['leadStatus'] = $leadStatus;
        $data['pageDetails'] = 'Quote';
        $data['lead_id'] = $lead_id;



        # Default Records Per Page - always 10
        /* pagination start 
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'quote/';
        # Total Records
        $config['total_rows'] = $this->quote_model->quoteTotalRows($lead_id, $searchParams);

        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        $current_offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if ($data['pagination_links'] != '') {
            $data['last'] = $this->pagination->cur_page * $config['per_page'];
            if ($data['last'] > $data['total_rows']) {
                $data['last'] = $data['total_rows'];
            }
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $config['per_page']) + 1) . ' to ' . ($data['last']) . ' of ' . $data['total_rows'];
        }
        $data['sn'] = $current_offset + 1;
         pagination end */

        $search_fields = 0;
        $quoteSearch = array();
        //$quoteSearch = $this->quote_model->getQuoteDetails($lead_id);
        $quoteResults = $this->quote_model->getQuoteDetailsByLead($lead_id);
        if($quoteResults)
        {

            foreach ($quoteResults as $row) {
                $quoteSearch[$row['quote_revision_id']][] = $row;
            }
        }

        # Loading the data array to send to View
        $data['quoteSearch'] = @$quoteSearch;
        //print_r($data['quoteSearch']); exit;
        
        $data['count'] = @$quoteSearch['count'];

        #For add quote popup
        $data['opportunities'] = $this->quote_model->get_opportunities($lead_id);

        $data['productCategories'] = $this->quote_model->getProductCategories($lead_id);

        $lead_user_id = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user_id");
        $lead_user_role_id = getUserRole($lead_user_id);

        $lead_user2 = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user2");
        if ($lead_user_role_id != '4' && $lead_user2 == NULL) {
            $where_con = array('billing_info_id!=' => 2);
        } else {
            $where_con = array();
        }
        //die();
        $data['lead_user_id'] = $lead_user_id;
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name", $where_con);

        $data['checkPage'] = 0;//1 for Open Pages. 0 for Closed Pages
        
        $this->load->view('lead/openQuoteDetailsView', $data);
        //redirect(SITE_URL.'openLeads');
    }

    public function addQuote() {


        $lead_id = $_POST['id'];

        if (checkQuote($lead_id) == 0) {
            echo "Lead Not Found.";
        } else {
            //die();
            $data['opportunities'] = $this->quote_model->get_opportunities($lead_id);

            $lead_user_id = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user_id");
            $lead_user_role_id = getUserRole($lead_user_id);

            $lead_user2 = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user2");
            if ($lead_user_role_id != '4' && $lead_user2 == NULL) {
                $where_con = array('billing_info_id!=' => 2);
            } else {
                $where_con = array();
            }
            //die();

            $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name", $where_con);


            # Load page with all shop details
            $this->load->view('quote/addQuotePopUp', $data);
        }
    }
    
    /*
    Developed by :Mahesh
    Based on the net margin and varaiance , mails will be triggered.
    Auto approval : variance >8% ,net margin>10%
    RBH approval : variance >=0% ,net margin BETWEEN 8-10%
    NSM approval : variance BETWEEN  -15%-5% ,net margin BETWEEN 5-8%
    CH approval : variance <=-15% ,net margin <5
    if RBH is not there for nay regions, approvals will be triggered to NSM.
    */
    public function addQuoteRevision()
    {
        //exit('testingg');
        $quote_id = $this->input->post('quote_id');
        $op_id1 = $this->input->post('op_id');
        $op_id2 = $op_id1[0];
        $dis_val1 = ($_REQUEST['mrp'] * $_REQUEST['discount'][$op_id2])/100;
        $dis_val = $_REQUEST['mrp']-$dis_val1;
        $dp_val = $_REQUEST['dealer_price'];
        if($_SESSION['role_id'] == '5' && $dis_val < $dp_val)
        {
            $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> Price is less than dealer price!
                                        </div>');

            redirect(SITE_URL . 'quoteRevision/'.icrm_encode($quote_id));
        }
        else
        {

        // if($this->input->post('submitAddRevision') != '')
        if($this->input->post('submitAddRevision1') != '')
        {
            $op_id = $this->input->post('op_id');
            if(!$op_id)
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> Please select at least one opportunity to revise the quote
                                     </div>');
                redirect($this->agent->referrer()); exit;
            }
            //print_r(json_encode($_POST));exit;
            $this->db->trans_begin();
            $quote_id = $this->input->post('quote_id');
            $billing_id = $this->input->post('billing_name');
            $advance = $this->input->post('advance');
            if($advance=='') $advance = 0;
            $balance_payment_days = $this->input->post('balance_payment_days');
            $dealer_commission = $this->input->post('op_dealer_commission');
            $dealer = $this->input->post('dealer');
            $warranty = $this->input->post('op_warranty');
            $advance_type = $this->input->post('advance_type');
            $prev_quote_revision_id = $this->input->post('prev_quote_revision_id');
            $rev_op_id = @$this->input->post('rev_op_id');
            if($rev_op_id=='')
                $rev_op_id = array();
            $pqr_row = $this->Common_model->get_data_row('quote_revision',array('quote_revision_id'=>$prev_quote_revision_id));
            //print_r($dealer_commission);die;
            $change_in_terms = false;
            // Check if any changes made in payment terms
            if($pqr_row['warranty']!=$warranty||$pqr_row['advance_type']!=$advance_type||$pqr_row['advance']!=$advance||$pqr_row['balance_payment_days']!=$balance_payment_days||$pqr_row['dealer_commission']!=$dealer_commission)
            {
                $change_in_terms = true;
            }
            if(!$change_in_terms&&count($rev_op_id)==0)
            {
                $this->session->set_flashdata('response','<div class="alert alert-info alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-info"></i></div>
                                        <strong>Info!</strong> No Changes has been made
                                     </div>');
                redirect($this->agent->referrer()); exit;
            }
            /*echo '<pre>'; print_r($_POST); print_r($rev_op_id); echo '</pre>';
            exit('testing--');*/
            $rbhExisted = checkRbhExistByQuote($quote_id);
            $dataArr2 = array(
                "quote_id" => $quote_id,
                "billing_info_id" => $billing_id,
                //"warranty" => $warranty,
                "advance_type" => $advance_type,
                "advance" => $advance,
                "created_by" => $_SESSION['user_id']
            );
            if ($billing_id == 3) $dataArr2['stockist_id'] = $this->input->post('stokist_id');
            if($balance_payment_days !='') $dataArr2['balance_payment_days'] = $balance_payment_days;
            //if($dealer_commission !='') $dataArr2['dealer_commission'] = $dealer_commission;
            if($dealer !='') $dataArr2['dealer_id'] = $dealer;

            $dataArr2['status'] = 3;
            //print_r($dataArr2);die;
            $quotePresentStatus = getCurrentQuoteStatus($quote_id);
            updateOtherQuoteRevisionStatus($quote_id,3);

            //print_r($dataArr2);die;
            $quote_revision_id = $this->Common_model->insert_data('quote_revision', $dataArr2);
            //echo 123;die;
            $quoteStatus = 6;
            
            $discount_type = $this->input->post('discount_type');
            $discount = $this->input->post('discount');

            /*$product_id_arr = $this->input->post('product_id',TRUE);
            $qty_arr = $this->input->post('qty',TRUE);*/
            
            if($op_id)
            {
                //echo 112;die;
                $op_count = 0; $approved_op_count = 0; $rejected_op_count = 0; $approval_req_ops = array();
                // looping opportunities
                foreach ($op_id as $opportunity_id) {
                    $op_count++;
                    $disc_type = @$discount_type[@$opportunity_id];
                    $disc_val = @$discount[@$opportunity_id];
                    $ma_data = array('quote_revision_id'    =>  $quote_revision_id,
                                     'opportunity_id'       =>  $opportunity_id,
                                     'discount_type'        =>  @$disc_type,
                                     'discount'             =>  @$disc_val,
                                     'warranty'             =>  $warranty[@$opportunity_id],
                                     'dealer_id'            =>  $dealer,
                                     'dealer_commission'    =>  $dealer_commission[@$opportunity_id]
                                     );
                                     //print_r($ma_data);die;
                    // Free supply items
                    if(in_array($opportunity_id, $rev_op_id))
                    {
                        $free_products_arr = @$this->input->post('product_id_'.$opportunity_id,TRUE);
            		    $free_qty_arr = @$this->input->post('qty_'.$opportunity_id,TRUE);
			            //print_r($free_products_arr);print_r($free_qty_arr);
                        /*$free_products_arr = $product_id_arr[$opportunity_id];
                        $free_qty_arr = $qty_arr[$opportunity_id]; */
			             $cost_of_free_supply = 0;
                        if(count($free_products_arr)>0&&count($free_qty_arr)>0)
                        {
                            foreach ($free_products_arr as $key => $product_id) {
                                $unit_price = $this->Common_model->get_value('product',array('product_id'=>$product_id),'dp');
                                $qty = $free_qty_arr[$key];
                                if($product_id!=''&&$qty!='')
                                {
                                    $fdata = array(
                                                    'quote_revision_id' =>  $quote_revision_id,
                                                    'opportunity_id'    =>  $opportunity_id,
                                                    'product_id'        =>  $product_id,
                                                    'quantity'          =>  $qty,
                                                    'unit_price'        =>  $unit_price
                                                    );
                                    $cost_of_free_supply += $qty*$unit_price;
                                    $this->Common_model->insert_data('quote_opp_free_supply',$fdata);
                                }
                            }
                        }
                    }
                    else
                    {
                        // Get Previous Revision Free supply Info
                        $fs_results = $this->Common_model->get_data('quote_opp_free_supply',array('quote_revision_id'=>$prev_quote_revision_id,'opportunity_id'=>$opportunity_id));
                        $cost_of_free_supply = 0;
                        if($fs_results)
                        {
                            foreach ($fs_results as $fs_row) {
                                
                                $fs_data = array('quote_revision_id'    =>  $quote_revision_id,
                                                 'opportunity_id'       =>  $opportunity_id,
                                                 'product_id'           =>  $fs_row['product_id'],
                                                 'quantity'             =>  $fs_row['quantity'],
                                                 'unit_price'           =>  $fs_row['unit_price']
                                                 );
                                $cost_of_free_supply += $fs_row['quantity']*$fs_row['unit_price'];
                                $this->Common_model->insert_data('quote_opp_free_supply',$fs_data);
                            }
                        }
                    }

                    if($this->session->userdata('role_id')==5) // If Distributor : Auto approve
                    {
                        $ma_data['status'] = 2;
                        $approved_op_count++;
                    }
                    else
                    {
                        if(in_array($opportunity_id, $rev_op_id)||$change_in_terms) // If opportuntiy is revised or change in terms
                        {
                            if(!in_array($opportunity_id, $rev_op_id))
                            {
                                // Get Previous Revision Margin Info
                                //print_r($ma_data);die;
                                $ma_row = $this->Common_model->get_data_row('quote_op_margin_approval',array('quote_revision_id'=>$prev_quote_revision_id,'opportunity_id'=>$opportunity_id));
                                $ma_data['discount_type'] = $ma_row['discount_type'];
                                $ma_data['discount'] = $ma_row['discount'];
                                $disc_type = $ma_row['discount_type'];
                                $disc_val = $ma_row['discount'];
                            }
                            // Margin Analysis  start
                            $row = getQuoteOppPriceDetails($quote_revision_id,$opportunity_id);
                            $order_value = $row['mrp'];
                            if($disc_type!=''&&$disc_val!='')
                            $order_value = ($disc_type==1)?($order_value*(1-$disc_val/100)):($order_value-$disc_val);
                            $nsp = $order_value/(1+$row['freight_insurance']/100)/(1+$row['gst']/100);
                            $discount_percenrage = round((($row['mrp'] - $order_value )/$row['mrp'])*100,2);
                            $data = array();
                            $data['order_value'] = $order_value;
                            $data['basic_price'] = $row['base_price'];
                            $data['dp'] = $row['dp'];
                            //print_r($warranty[@$opportunity_id]);die;
                            $data['total_warranty_in_years'] = ($warranty[@$opportunity_id]>0)?round(($warranty[@$opportunity_id]/12),2):0;
                            
                            if($advance!='')
                            {
                                if ($advance_type==2) 
                                    $advance = round(($advance/$row['mrp'])*100,2);
                            }
                            else $advance = 0;
                            $data['advance'] = $advance;
                            $data['balance_payment_days'] = ($balance_payment_days!='')?$balance_payment_days:0;
                            $data['dealer_commission'] = ($dealer_commission[@$opportunity_id]>0)?$dealer_commission[@$opportunity_id]:0;
                            $data['cost_of_free_supply'] = $cost_of_free_supply;
                            $data['net_selling_price'] = $nsp;
                            //print_r($data);die;
                            $m_data = marginAnalysis($data);
                            $dp = $row['unit_dp'];
                            $variance_percentage = round(((($order_value/$row['required_quantity'])-$dp)/$dp)*100,2);
                            //echo $variance_percentage.'-->'.$m_data['net_margin_percentage']; exit;
                            // Get margin bands
                            $mbands = $this->Common_model->get_data('quote_approval_config',array('status'=>1));
                            $var = false; $nm = false;
                            foreach ($mbands as $mb_row) {
                                $mb_row['gross_margin_percentage'] = ceil($variance_percentage);
                                $mb_row['net_margin_percentage'] = $m_data['net_margin_percentage'];

                                $gm_data = array(); $nm_data = array();
                                $gm_data['lower_limit'] = $mb_row['gm_lower_limit'];
                                $gm_data['lower_check'] = $mb_row['gm_lower_check'];
                                $gm_data['upper_limit'] = $mb_row['gm_upper_limit'];
                                $gm_data['upper_check'] = $mb_row['gm_upper_check'];

                                $nm_data['lower_limit'] = $mb_row['nm_lower_limit'];
                                $nm_data['lower_check'] = $mb_row['nm_lower_check'];
                                $nm_data['upper_limit'] = $mb_row['nm_upper_limit'];
                                $nm_data['upper_check'] = $mb_row['nm_upper_check'];
                                if(!$var)
                                {
                                    $var = check_range($gm_data,$mb_row['gross_margin_percentage']);
                                }

                                if(!$nm)
                                {
                                    $nm = check_range($nm_data,$mb_row['net_margin_percentage']);
                                }


                                if(($var&&$nm)||$mb_row['role_id']==9)
                                {
                                    switch ($mb_row['role_id']) {
                                        case 7: // RBH
                                            $ma_data['close_at'] = $ma_data['approval_at'] = 7;
                                        break;
                                        case 8: // NSM
                                            $ma_data['close_at'] = 8 ; $ma_data['approval_at'] = 7;
                                        break;
                                        case 9: // CH
                                            $ma_data['close_at'] = 9 ; $ma_data['approval_at'] = 7;
                                        break;
                                        default:
                                            $ma_data['status'] = 2;
                                            $approved_op_count++;
                                        break;
                                    }
                                    break;
                                }
                                
                            }
                            $check_roles = array(7,8,9);
                            if(in_array(@$ma_data['close_at'],$check_roles))
                            {
                                if(!$rbhExisted) // No RBH Exist
                                {
                                    $ma_data['approval_at'] = 8;
                                    if($ma_data['close_at']==7)
                                    {
                                        $ma_data['close_at'] = 8; // move to NSM
                                    }
                                }
                            }

                            
                            /* echo '<pre>';print_r($m_data); echo '</pre>'; 
                            echo $ma_data['approval_at'].'--'.$ma_data['close_at'].'<br>';exit;*/
                        }
                        else
                        {
                            //echo 123;die;
                            // Get Previous Revision Margin Info
                            $ma_row = $this->Common_model->get_data_row('quote_op_margin_approval',array('quote_revision_id'=>$prev_quote_revision_id,'opportunity_id'=>$opportunity_id));
                            $previous_margin_approval_id = $ma_row['margin_approval_id'];
                            //print_r($ma_row);die;
                            $ma_data = array('quote_revision_id'    =>  $quote_revision_id,
                                     'opportunity_id'       =>  $opportunity_id,
                                     'discount_type'        =>  $ma_row['discount_type'],
                                     'discount'             =>  $ma_row['discount'],
                                     'warranty'             =>  $warranty[@$opportunity_id],
                                     'dealer_id'            =>  $dealer,
                                     'dealer_commission'    =>  $dealer_commission[@$opportunity_id],
                                     'approval_at'          =>  $ma_row['approval_at'],
                                     'close_at'             =>  $ma_row['close_at'],
                                     'status'               =>  $ma_row['status']
                                     );
                            if($ma_row['status']==2) $approved_op_count++;
                            if($ma_row['status']==3) $rejected_op_count++;
                            //print_r($ma_data);die;  
                        }
                    }   
                    //print_r($ma_data);die;
    
                    $margin_approval_id = $this->Common_model->insert_data('quote_op_margin_approval',$ma_data);
                    if(@$previous_margin_approval_id!='')
                    {
                        //Get privious approval remarks history
                        $mah_results = $this->Common_model->get_data('quote_op_margin_approval_history',array('margin_approval_id'=>$previous_margin_approval_id));
                        if(@$mah_results)
                        {
                            foreach (@$mah_results as $mah_row) {
                                $mah_data = array('margin_approval_id'    =>  $margin_approval_id,
                                     'approved_by'        =>  $mah_row['approved_by'],
                                     'remarks'            =>  $mah_row['remarks'],
                                     'created_by'         =>  $mah_row['created_by'],
                                     'created_time'       =>  $mah_row['created_time'],
                                     'status'             =>  $mah_row['status']
                                     );
                                $this->Common_model->insert_data('quote_op_margin_approval_history',$mah_data);
                            }
                            
                        }
                        $previous_margin_approval_id = '';
                    }
                    if(@$ma_data['approval_at']!=''&&@$ma_data['status']!=2&&@$ma_data['status']!=3)
                    {
                            $approval_req_ops[$opportunity_id] = array('opportunity_id'=>$opportunity_id,'approval_at'=>$ma_data['approval_at'],'margin_approval_id'=>$margin_approval_id);
                    }
                    
                }

                if($op_count==$approved_op_count)
                {
                    // Update quote revision status
                    updateOtherQuoteRevisionStatus($quote_id,1,4); // Updating revision 1 to 4
                    updateOtherQuoteRevisionStatus($quote_id, 3, 1); // Updating revision 3 to 1

                    $quoteStatus = 2;

                    // Update quote additional terms
                    $quote_revision = $this->Common_model->get_data_row('quote_revision', array('quote_revision_id'=>$quote_revision_id));
                    $qdata = array( 'warranty'=>$quote_revision['warranty'],
                                    'advance_type'  =>  $quote_revision['advance_type'],
                                    'advance'  =>  $quote_revision['advance'],
                                    'balance_payment_days'  =>  $quote_revision['balance_payment_days'],
                                    'billing_info_id'  =>  $quote_revision['billing_info_id']
                                    );
                    if($quote_revision['dealer_commission']!='')
                    {
                        $qdata['dealer_commission'] = $quote_revision['dealer_commission'];
                    }
                    else
                    {
                        $qdata['dealer_commission'] = NULL;
                    }
                    if($quote_revision['stockist_id']!='')
                    {
                        $qdata['stockist_id'] = $quote_revision['stockist_id'];
                    }
                    else
                    {
                        $qdata['stockist_id'] = NULL;
                    }
                    $this->Common_model->update_data('quote',$qdata,array('quote_id' => $quote_id));
                    

                }
                else
                {
                    if($op_count==($approved_op_count+$rejected_op_count))
                    {
                        // Update quote revision status
                        updateOtherQuoteRevisionStatus($quote_id, 3, 2); // Updating revision 3 to 2
                        $quoteStatus = 2;
                    }
                }

            }

            
            if($quoteStatus != $quotePresentStatus)
            {
                $dataArr = array('status' => $quoteStatus,
                                'modified_by' => $this->session->userdata('user_id'),
                                'modified_time' => date('Y-m-d H:i:s'));
                $this->Common_model->update_data('quote', $dataArr, array('quote_id' => $quote_id));
                addQuoteStatusHistory($quote_id, $quoteStatus);
            }
            //exit('exiting');
            $lead_id = $this->input->post('lead_id');
            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while adding a revision to Quote!
                                     </div>');

                redirect(SITE_URL.'openQuoteDetails/'.icrm_encode($lead_id));
                    
            }
            else
            {
                
                $this->db->trans_commit();
                // Email alert for quote approval Start
                if(count($approval_req_ops)>0)
                {
                    //echo 123;die;
                    $conditionApproval = $this->Common_model->get_data('condition_approval_mail', array('condition_approval_mail_id'=>1));		

                            $quote_approvers = array();
                    foreach($approval_req_ops as $orow) {
                        if(!array_key_exists($orow['approval_at'], $quote_approvers))
                            $quote_approvers[$orow['approval_at']] = getOppApproverEmailsByRole($orow['approval_at'],$opportunity_id);
                        //echo $this->db->last_query().'<br>';
                        if(count($quote_approvers[$orow['approval_at']])>0)
                        {
                            foreach ($quote_approvers[$orow['approval_at']] as $key => $urow) {
                                $to = $urow['email_id'];
                                $encoded_id = icrm_encode($orow['margin_approval_id'].'_'.$urow['user_id']);
                                //print_r($urow);die;
                                $email_data = getQuoteApprovalEmailData($quote_revision_id,$quote_id,$orow['approval_at'],$orow['opportunity_id'],'');
                               //print_r($to);die;
                                $subject = $email_data['subject'];
                                //$message = $email_data['message'];
                                $message = str_replace('{ENCODED_ID}', $encoded_id, $email_data['message']);
                                send_email($to,$subject,$message);
                                //echo $to.'<br>'.$subject.'<br>'.$message.'<br>'; 
                            }
                        }
                        // echo '<pre>'; print_r($message);die;
                        if($conditionApproval[0]['condition'] == 1){
                        break;
                        }
                    }

                }
                //exit;
                // Email alert for quote approval End
                //exit;
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> New revision to Quote has been Added successfully!
                                     </div>');
                redirect(SITE_URL.'openQuoteDetails/'.icrm_encode($lead_id));
            }

        }
     }
    }

    public function quoteAdd() 
    {   
        if ($this->input->post('submitQuote') != "") 
        {
            if (count($_POST['op_id']) > 0) 
            {
                $opportunities = implode(",", $_POST['op_id']);
                $opp_ids = $_POST['op_id'];
                $opp_number = array();
                foreach($opp_ids as $key=>$op_id)
                {
                    $opp_number[] = $this->Common_model->get_value('opportunity',array('opportunity_id'=>$op_id),'opp_number');
                }
                $opportunity_number = implode(',', $opp_number);
                $oppCount = count($_POST['op_id']);
                $lead_id = $this->input->post('lead_id');
                $q = 'SELECT q.quote_id from lead l
                    INNER JOIN opportunity o ON o.lead_id = l.lead_id
                    INNER JOIN quote_details qd ON qd.opportunity_id = o.opportunity_id
                    INNER JOIN quote q ON q.quote_id = qd.quote_id
                    WHERE q.status IN (1, 2, 6, 10) AND l.lead_id = "'.$lead_id.'"
                    GROUP BY q.quote_id';
                $r = $this->db->query($q);
                $quoteExist = 0;
                $quote_id = 0;
                foreach($r->result_array() as $row)
                {
                    $quote_id = $row['quote_id'];
                    $quote_number = $row['quote_number'];
                    $q1 = 'SELECT sum(case when opportunity_id IN ('.$opportunities.') then 1 else 0 end) case1, 
                            sum(case when opportunity_id IN ('.$opportunities.') then 0 else 1 end) case2
                            from quote_details qd 
                            where 1 AND quote_id = "'.$quote_id.'"';
                    $r1 = $this->db->query($q1);
                    if($r1->num_rows() > 0)
                    {
                        $rr = $r1->result_array();
                        $checkCase = $rr[0];
                        if($checkCase['case2'] == 0 && $oppCount == $checkCase['case1'])
                        {
                            $quoteExist = 1;
                            break;  
                        }
                    }   
                } 
                $print_quote_number = $this->Common_model->get_value('quote',array('quote_id'=>$quote_id),'quote_number');   
                $currency_count = $this->quote_model->check_opportunity_currency($opportunities);
                if($currency_count == 0)
                {
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-times"></i></div>
                                            <strong>Error!</strong>Selected Opportunities('.$opportunity_number.') must have same Currency!
                                         </div>');
                    redirect($this->agent->referrer());exit;

                }
                $currency_availablity_check = $this->quote_model->currency_availablity_check($opportunities);
                if($currency_availablity_check==0)
                {
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											<div class="icon"><i class="fa fa-check"></i></div>
											<strong>Error!</strong>Please Add Currency Conversion Factor in Currency Convertor Screen.Then Try Again !
                                        </div>');
                    redirect($this->agent->referrer());exit;
                }
                if($quoteExist == 1)
                {
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> A quote with quote ID: '.$print_quote_number.' already exists for the selected Opportunities. Please add a revision to that instead!
                                         </div>');
                    redirect($this->agent->referrer());exit;

                }
                else
                {
                    //print_r($_POST['op_id']); die();
                    $this->db->trans_begin();
                    goto quote_start;
                    quote_start:
                    $lead_str_arr = get_current_unique_numbers("quote","quote_counter","quote_id");
                    $quote_counter=$lead_str_arr[0];
                    $quote_number=$lead_str_arr[1];
                    $channel_partner_id=$this->input->post('channel_partner_id');
                    $billing_id = $this->input->post('billing_name');
                    $dataArr2 = array(
                        "billing_info_id" => $billing_id,
                        "discount" => $this->input->post('discount'),
                        "created_by" => $_SESSION['user_id'],
                        "status" => 1
                    );
                    /* Phase2 update: Inserting additional terms like warranty, advance, dealer commission etc
                    ** Mahesh: 16th August 2017
                    ** START
                    **/
                    $dataArr3 = array(
                        "created_by" => $this->session->userdata('user_id'),
                        "billing_info_id" => $billing_id,
                        "channel_partner_id" => $channel_partner_id,
                        "warranty" => $this->input->post('warranty'),
                        "advance_type"  => $this->input->post('advance_type'),
                        "advance"  => $this->input->post('advance'),
                        'company_id' => $this->session->userdata('company')
                        );
                    $balance_payment_days = @$this->input->post('balance_payment_days');
                    if($balance_payment_days!='')
                    {
                        $dataArr3 ["balance_payment_days"]=  $balance_payment_days;
                    }
                    $dealer_commission = @$this->input->post('dealer_commission');
                    $dealer = @$this->input->post('dealer');
                    if($dealer_commission!='')
                    {
                        $dataArr3 ["dealer_commission"]=  $dealer_commission;
                    }
                    if($dealer!='')
                    {
                        $dataArr3 ["dealer_id"]=  $dealer;   
                    }
                    /* Phase2 update: Inserting additional terms like warranty, advance, dealer commission etc
                    ** Mahesh: 16th August 2017
                    ** END
                    **/
                    if ($billing_id == 3) $dataArr2['stockist_id'] = $this->input->post('stokist_id');
                    $dataArr3['status'] = getQuoteStatusByDiscount($this->input->post('discount'));
                    $quote_status = $dataArr3['status'];
                    try
                    {
                        check_unique_numbers_constraint('quote','quote_counter',$quote_counter);
                    }
                    catch(Exception $e)
                    {
                        goto quote_start;
                    }
                    $dataArr3['quote_counter']=$quote_counter;
                    $dataArr3['quote_number']=$quote_number;
                    $quote_id = $this->Common_model->insert_data('quote', $dataArr3);
                    $dataArr2['quote_id'] = $quote_id;
                    //Phase2 Update: Mahesh 16-08-2017 inserting quote additional terms in quote revsion table
                    $qr_dataArr = $dataArr2;
                    $qr_dataArr ["warranty"]=  $this->input->post('warranty');
                    $qr_dataArr ["advance_type"]=  $this->input->post('advance_type');
                    $qr_dataArr ["advance"]=  $this->input->post('advance');

                    $balance_payment_days = @$this->input->post('balance_payment_days');
                    if($balance_payment_days!='')
                    {
                        $qr_dataArr ["balance_payment_days"]=  $balance_payment_days;
                    }
                    $dealer_commission = @$this->input->post('dealer_commission');
                    if($dealer_commission!='')
                    {
                        $qr_dataArr ["dealer_commission"]=  $dealer_commission;
                    }
                    if($dealer!='')
                    {
                        $qr_dataArr ["dealer_id"]=  $dealer;   
                    }
                    $quote_revision_id = $this->Common_model->insert_data('quote_revision', $qr_dataArr);
                    addQuoteStatusHistory($quote_id, $quote_status);
                    $i = 0;
                    foreach ($_POST['op_id'] as $op_id) 
                    {
                        $productDetails = getProductDetailsForOpprotunity($op_id);
                        $getFinalValue = getFinalValueAfterConversion($productDetails['mrp'],$productDetails['currency_id']); 
				        $total_converted_value= $getFinalValue[0];
				        $currency_factor=$getFinalValue[1];
                        $dataArr2 = array(
                            "quote_id" => $quote_id,
                            "opportunity_id" => $op_id,
                            "sub_category_id" => $this->input->post('sub_category_id')[$op_id],
                            "mrp" => $productDetails['mrp'],
                            "ed" => $productDetails['ed'],
                            "vat" => $productDetails['vat'],
                            "gst" => $productDetails['gst'],
                            "freight_insurance" => $productDetails['freight_insurance'],
                            "currency_id"=>$productDetails['currency_id'],
                            'total_value' =>  $total_converted_value,
					        'currency_factor' =>  $currency_factor
                            );
                        $quote_details_id = $this->Common_model->insert_data('quote_details', $dataArr2);
                        addOpportunityStatusByQuote($op_id, $quote_status);

                        //Margin Analysis auto approval
                        $ma_data = array('quote_revision_id'   =>  $quote_revision_id,
                                     'opportunity_id'       =>  $op_id,
                                     'discount_type'        =>  1,
                                     'discount'             =>  0,
                                     'status'               =>  2,
                                     'warranty'             => 12,
                                     );
                    

                        $this->Common_model->insert_data('quote_op_margin_approval',$ma_data);
                        $i++;
                    }

                    leadStatusUpdate($this->input->post('lead_id'));
                    

                    if ($this->db->trans_status() === FALSE)
                    {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <div class="icon"><i class="fa fa-check"></i></div>
                                                <strong>Error!</strong> There\'s a problem occured while adding Quote!
                                             </div>');
                        redirect($this->agent->referrer());
                            
                    }
                    else
                    {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <div class="icon"><i class="fa fa-check"></i></div>
                                                <strong>Success!</strong> Quote has been Added successfully!
                                             </div>');
                        redirect($this->agent->referrer());
                    }


                }
            } 
            else 
            {

                $this->session->set_flashdata('response', '<div class="alert  alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> Please select Opportunity!
									 </div>');
                redirect($this->agent->referrer());
            }
        }
    }

    function quotation($qid) {

        $quote_revision_id = icrm_decode($qid);
       // echo $quote_revision_id; exit; 
        $data['quotation'] = $this->quote_model->get_quote_details1($quote_revision_id);
        $data['quote_date'] =  format_date($data['quotation']['created_time']);
        $data['tax_type']   =   tax_type($data['quote_date']);
        
        $quote_id = $data['quotation']['quotation_id'];
        $data['quote_id'] = $quote_id;
        $data['company_label'] = $this->quote_model->get_company_lable_details($quote_id);
        $data['quote_revision_number'] = getQuoteRevisionNumber($quote_id,$quote_revision_id);
        $data['free_supply_items'] = $this->quote_model->getQuoteRevisionFreeSupplyItems($quote_revision_id);
        $data['quote_info'] = $this->Common_model->get_data_row('quote',array('quote_id'=>$quote_id));
        $quoteOpMargin = getOpMarginDataOldOrNew($quote_revision_id);
        // echo  $quoteOpMargin;die;
        if($quoteOpMargin == 2){
            $data['quote_format_type'] = 3;
        }else{
            $data['quote_format_type'] = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id);
        }
        // $data['quote_format_type'] = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id); //mahesh: 5th Jan 2018
        //print_r($data['quote_info']); exit;
        $data['quote_revision_id'] = $quote_revision_id;
        $lead_id = getLeadFromQuote($quote_id);
        $data['ref'] = getQuoteReferenceID($lead_id, $quote_id);
        switch ($data['quote_format_type']) {
            case 1: // Old Format
                $quote_view_file = 'quotation_view1';
            break;
            
            case 2:
                $quote_view_file = 'quotation_view';
            break;

            case 3:
                $quote_view_file = 'quotation_pdfNew';
        }
        // print_r($data['quotation']);die;
        $this->load->view('quote/'.$quote_view_file, $data);
        //$this->load->view('quote/quotation_view', $data);
    }


    /*function quotation_pdf($qid) {
        $quote_revision_id = icrm_decode($qid);
        $data['quotation'] = $this->quote_model->get_quote_details1($quote_revision_id);
        $quotation_id = $data['quotation']['quotation_id'];
        $quote_id = $data['quotation']['quotation_id'];
        $data['quote_id'] = $quote_id;
        $data['quote_revision_number'] = getQuoteRevisionNumber($quotation_id,$quote_revision_id);
        $data['free_supply_items'] = $this->quote_model->getQuoteRevisionFreeSupplyItems($quote_revision_id);
        $data['quote_info'] = $this->Common_model->get_data_row('quote',array('quote_id'=>$quotation_id));
        $data['company_label'] = $this->quote_model->get_company_lable_details($quotation_id);
        $data['quote_date'] =  format_date($data['quotation']['created_time']);
        $data['tax_type']   =   tax_type($data['quote_date']);
        $data['quote_format_type'] = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id);
        $data['quote_revision_id'] = $quote_revision_id;
        //echo $data['quote_format_type']; exit;
        //echo '<pre>';print_r($data['quotation']); echo '</pre>';die();
        $data['table_width']="1550";
        switch ($data['quote_format_type']) {
            case 1: // Old Format
                $quote_view_file = 'quotation_view1';
            break;
            
            case 2:
                $quote_view_file = 'quotation_pdf';
            break;
        }
        //print_r($data);die;
        $quote_content = $this->load->view('quote/'.$quote_view_file, $data, true);
        //print_r($quote_content); exit;
        $lead_id = getLeadFromQuote($quotation_id);
        $ref = getQuoteReferenceID($lead_id, $quotation_id);

        $pdf = new Pdf('P', 'px', 'A4', true, 'UTF-8', false);
            $pdf->setRef($ref);
            $pdf->setRoleCheck($data['quotation']['roleCheck']);
            // Phase2 Update: Replacing quote created time with current timestamp when user takes print
            $pdf->setDate(date('Y-m-d H:i:s')); 
            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            
            // set auto page breaks
            //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->SetFont('dejavusans', '', 9);

        // add a page
        $pdf->AddPage();
        $image1 = assets_url() . "images/skanray-logo.png";
        //if(isset($_REQUEST['generate_quotation']))
        //echo $quote_content; exit;
        $pdf->writeHTML($quote_content, true, false, true, false, '0');
        $pdf_name="Skanray Quote-".$ref."_".date('M-d-Y_h:i:s').".pdf";
        $pdf->Output($pdf_name, 'D');
    }*/
    function quotation_pdf($qid) {
        // echo $qid;die;
        
        $quote_revision_id = icrm_decode($qid);
        $data['quotation'] = $this->quote_model->get_quote_details1($quote_revision_id);
        // echo '<pre>'; print_r($data['quotation']['qData']);die;
        $quotation_id = $data['quotation']['quotation_id'];
        $quote=$this->Common_model->get_data_row('quote' , array('quote_id'=>$quotation_id));
        // $quote = $qte->result_array();
        // echo '<pre>'; print_r($quote);die;
        $quote_id = $data['quotation']['quotation_id'];
        $data['quote_id'] = $quote_id;
        $data['quote_revision_number'] = getQuoteRevisionNumber($quotation_id,$quote_revision_id);
        $data['free_supply_items'] = $this->quote_model->getQuoteRevisionFreeSupplyItems($quote_revision_id);
        $data['dealer_info'] = $this->Common_model->get_data_row('user',array('user_id'=>$data['quote_info']['dealer_id']));
        $data['quote_info'] = $this->Common_model->get_data_row('quote',array('quote_id'=>$quotation_id));
        $data['company_label'] = $this->quote_model->get_company_lable_details($quotation_id);
        $data['quote_date'] =  format_date($data['quotation']['created_time']);
        $data['tax_type']   =   tax_type($data['quote_date']);
        $quoteOpMargin = getOpMarginDataOldOrNew($quote_revision_id);
        // echo  $quoteOpMargin;die;
        if($quoteOpMargin == 2){
            $data['quote_format_type'] = 3;
        }else{
            $data['quote_format_type'] = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id);
        }
        // echo $data['quote_format_type'];die;
        $data['quote_revision_id'] = $quote_revision_id;
        // echo $data['quote_format_type']; die();
        // echo '<pre>';print_r($data);die;
        $data['table_width']="1550";
        switch ($data['quote_format_type']) {
            case 1: // Old Format
                $quote_view_file = 'quotation_view1';
            break;
            
            case 2:
                $quote_view_file = 'quotation_pdf';
            break;

            case 3:
                $quote_view_file = 'quotation_pdfNew';
        }
        $quoteDate = '';
        // if($quote['modified_time'] == null){
        //     $quoteDate = $quote['created_time'];
        // }else{
        //     $quoteDate = $quote['modified_time'];
        // }

        if($data['quotation']['qData']['modified_time'] == null){
            $quoteDate = $data['quotation']['qData']['created_time'];
        }else{
            $quoteDate = $data['quotation']['qData']['modified_time'];
        }
        
        // echo '<pre>'; print_r($quoteDate);die;

        // Added on 01-12-2021 for get lead number
        $opportunityDetails = $this->Common_model->get_data_row('opportunity' , array('opportunity_id'=>$data['quotation']['product_details'][0]['opportunity_id']));
        $leadID = $this->Common_model->get_data_row('lead' , array('lead_id'=>$opportunityDetails['lead_id']));
        $data['lead_number'] = $leadID['lead_number'];
        // Added on 01-12-2021 for get lead number end
        // echo "<pre>====";print_r($leadID);die;

        $quote_content = $this->load->view('quote/'.$quote_view_file, $data, true);
        // print_r($quote_content); exit;
        $lead_id = getLeadFromQuote($quotation_id);
        // $ref = getQuoteReferenceID($lead_id, $quotation_id);
        $ref = getQuoteReferenceIDNew($lead_id, $quotation_id,$data['quote_revision_id']);
        // echo "<pre>";print_r($lead_id);
        // echo "<pre>";print_r($quotation_id);
        // echo "<pre>";print_r($ref);die;

        $pdf = new Pdf('P', 'px', 'A4', true, 'UTF-8', false);
            $pdf->setRef($ref);
            $pdf->setRoleCheck($data['quotation']['roleCheck']);
            // Phase2 Update: Replacing quote created time with current timestamp when user takes print
            $pdf->setDate(format_date($quoteDate));
            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            
            // set auto page breaks
            //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetFont('dejavusans', '', 9);

        // add a page
        $pdf->AddPage();
        $image1 = assets_url() . "images/skanray-logo.png";
        //if(isset($_REQUEST['generate_quotation']))
        // print_r($quote_content); exit;
        $pdf->writeHTML($quote_content, true, false, true, false, '0');
        $pdf_name="Skanray Quote-".$ref."_".date('M-d-Y_h:i:s').".pdf";
        $pdf->Output($pdf_name, 'D');
    }

    function contract($cid)
    {
        $contract_note_id = icrm_decode($cid);
        $data = $this->quote_model->getContractPDFDetails($contract_note_id);
        $this->load->view('quote/contractPDF', $data);
    }

    function contract_pdf($qid=0) 
    {
        $contract_note_id = icrm_decode($qid);
        $cnote = $this->Common_model->get_data_row('contract_note',array('contract_note_id'=>$contract_note_id));
        
        // echo '<pre>'; print_r($data['shipToParty']);die;
        switch ($cnote['cnote_type']) {
            case 1:
                $data = $this->quote_model->getContractPDFDetails($contract_note_id);

                $data['cnote_date'] =  format_date($data['contract_note']['created_time']);
                $data['tax_type']   =   tax_type($data['cnote_date']);
                // Get lead user details
                $data['lead_user'] = $this->quote_model->getLeadOwnerDetails($data['lead_id_val']);
                // Get Quote Details
                $quotes = $this->quote_model->getQuotesByCNoteID($contract_note_id);
                // print_r($quotes); exit;
                $data['quotes'] = $quotes;

                $currency_id = $this->Common_model->get_value('quote_details',array('quote_id'=>$quotes[0]['quote_id']),'currency_id');
                $data['currency_name'] = $this->common_model->get_value('currency',array('currency_id'=>$currency_id),'code');
                $data['dist_row'] = array();
                if(@$data['quotes'][0]['dealer_id']>0)
                {
                    $data['dist_row'] = getDistributorDetails(@$data['quotes'][0]['dealer_id']);
                }
                //print_r($data['quotes']); exit;
                $data['quote_format_type']   =   quote_format_type($data['cnote_date']);
                /*echo $data['cnote_date'].'-->'.$data['tax_type'];
                echo print_r($data); exit;*/
                $lead_id = $data['lead_id_val'];
                $customerSAPCode = getCustomerSAPCode($lead_id);
                // echo $customerSAPCode;die;
                /*echo $contract_note_id;
                echo '<pre>';
                print_r($data['quotes']);
                echo '</pre>';
                die();*/
                if($data['quote_format_type']==2)
                {
                    $quote_format = getQuoteFormatTypeByQuoteRevisionID($data['quotes'][0]['quote_revision_id']);
                    if($quote_format==1)
                        $data['product_details'] = $this->quote_model->getCNoteProductDetailsOfOldQuotes($contract_note_id);
                    else
                        $data['product_details'] = $this->quote_model->getCNoteProductDetails($contract_note_id);
                    //print_r($data['product_details']); exit;
                    $quote_approval_details = $this->quote_model->getCNoteQuoteApprovalDetails($contract_note_id);
                    $quote_op_approvals = array();
                    if($quote_approval_details)
                    {
                        foreach ($quote_approval_details as $qrow) {
                            $quote_op_approvals[$qrow['quote_id']]['opportunities'][] = $qrow;
                        }
                    }
                    $currency_id = $this->Common_model->get_value('quote_details',array('quote_id'=>$quote_approval_details[0]['quote_id']),'currency_id');
                    $data['currency_name'] = $this->common_model->get_value('currency',array('currency_id'=>$currency_id),'code');
                    $data['quote_op_approvals'] = $quote_op_approvals;
                    $quote_approval_history = $this->quote_model->getCNoteQuoteApprovalHistory($contract_note_id);
                    $approval_history = array();
                    if($quote_approval_history)
                    {
                        foreach ($quote_approval_history as $hrow) {
                            $approval_history[$hrow['quote_id']][$hrow['opportunity_id']][$hrow['approved_by']] = $hrow['approval_by'];
                        }
                    }

                    $data['approval_history'] = $approval_history;
                    
                    
                }
                // echo $data['quote_format_type'];die;
                if($data['quote_format_type']==3)
                {
                    $quote_format = getQuoteFormatTypeByQuoteRevisionID($data['quotes'][0]['quote_revision_id']);
                    if($quote_format==1)
                        $data['product_details'] = $this->quote_model->getCNoteProductDetailsOfOldQuotes($contract_note_id);
                    else
                        $data['product_details'] = $this->quote_model->getCNoteProductDetails($contract_note_id);
                    //echo $quote_format; exit;
                    //print_r($data['product_details']); exit;
                    $quote_approval_details = $this->quote_model->getCNoteQuoteApprovalDetails($contract_note_id);
                    //$data['cnote_data'] = $this->quote_model->get_cnote_details_for_new_cnote_format($contract_note_id);
                    $data['cnote_data'] = $this->Common_model->get_data_row('contract_note',array('contract_note_id'=>$contract_note_id));
                    //print_r($data['cnote_data']);die;
                    $quote_op_approvals = array();
                    if($quote_approval_details)
                    {
                        foreach ($quote_approval_details as $qrow) {
                            $quote_op_approvals[$qrow['quote_id']]['opportunities'][] = $qrow;
                        }
                    }
                    $data['quote_op_approvals'] = $quote_op_approvals;
                    $quote_approval_history = $this->quote_model->getCNoteQuoteApprovalHistory($contract_note_id);
                    $approval_history = array();
                    if($quote_approval_history)
                    {
                        foreach ($quote_approval_history as $hrow) {
                            $approval_history[$hrow['quote_id']][$hrow['opportunity_id']][$hrow['approved_by']] = $hrow['approval_by'];
                        }
                    }

                    $data['approval_history'] = $approval_history;
                }
                //echo $data['quote_format_type']; exit;
                if($data['quote_format_type']==1)
                {
                    $view_file = 'contractPDF';
                }
                elseif ($data['quote_format_type']==2) 
                {
                    $view_file = 'contractPDF_new';
                }
                else
                {
                    // $view_file = 'contractPDF_latest';
                    $view_file = 'contractPDF_latest_versionNew';
                }
            break;
            case 2:
                $data = array();
                $data['contract_note'] = $this->quote_model->getDistributorCNoteDetails($contract_note_id);

                $data['product_details'] = $this->quote_model->getDistributorCNoteProductDetails($contract_note_id);
                $quote_approval_details = $this->quote_model->getCNotePoApprovalDetails($contract_note_id);
                $po_id = $data['contract_note']['purchase_order_id'];
                $data['po_number'] = $this->Common_model->get_value('purchase_order',array('purchase_order_id'=>$po_id),'po_number');
                $currency_id = $this->Common_model->get_value('po_products',array('purchase_order_id'=>$po_id),'currency_id');
                $data['currency_name'] = $this->Common_model->get_value('currency',array('currency_id'=>$currency_id),'code');

                $quote_op_approvals = array();
                foreach ($quote_approval_details as $qrow) {
                    $quote_op_approvals[$qrow['product_id']]['opportunities'][] = $qrow;
                }
                $data['quote_op_approvals'] = $quote_op_approvals;
                $quote_approval_history = $this->quote_model->getCNotePoApprovalHistory($contract_note_id);
                $approval_history = array();
                foreach ($quote_approval_history as $hrow) {
                    $approval_history[$hrow['product_id']][$hrow['approved_by']] = $hrow['approval_by'];
                }
                $data['approval_history'] = $approval_history;
                $customerSAPCode = $data['contract_note']['employee_id'];
                $view_file = 'distributor_cnotePDF';
            break;
        }
        if(is_numeric($cnote['billing_to_party'])){
            $shipToParty = $this->quote_model->getShipToPartyDetails($cnote['billing_to_party']);
            // echo '<pre>'; print_r($shipToParty);die;
        }else{
            $shipToParty['name'] = $cnote['billing_to_party'];
            // print_r($shipToParty);die;
        }
        $data['shipToParty'] = $shipToParty;
        // echo '<pre> iii'; print_r($data['quotes'][0]['quote_revision_id']);die;
        $quoteRev = $this->Common_model->get_data('quote_revision',array('quote_revision_id' => $data['quotes'][0]['quote_revision_id']));
        // echo '<pre> iii'; print_r($quoteRev);die;
        if($quoteRev[0]['modified_time'] == null){
            $data['quote_date'] = $quoteRev[0]['created_time'];
        }else{
            $data['quote_date'] = $quoteRev[0]['modified_time'];
        }
        // echo '<pre> iii'; print_r($data['quote_date']);die;

        $rev = getQuoteRevisionID($data['quotes'][0]['quote_id']);
        $data['rev'] = $rev;
        // echo $rev;die;
        // echo '<pre>'; print_r($data);die;
        // echo '<pre> data view----'; print_r($data);die;
        $quote_content = $this->load->view('quote/'.$view_file, $data, true);
        // echo $quote_content; exit;

        $pdf = new Pdf('P', 'px', 'A4', true, 'UTF-8', false);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        $pdf->SetFont('dejavusans', '', 8);

        // add a page
        $pdf->AddPage();
        $image1 = assets_url() . "images/skanray-logo.png";
        //if(isset($_REQUEST['generate_quotation']))
        $pdf->writeHTML($quote_content, true, false, true, false, '0');
        $pdf_name=$customerSAPCode."-".$contract_note_id."_".date('M-d-Y_h:i:s').".pdf";
        $pdf->Output($pdf_name, 'D');
        //$pdf->Output($pdf_name, 'I');
    }
    

    function get_stokist_list() {
        $sql = "select u.user_id, d.distributor_name, employee_id from user u "
                . "JOIN distributor_details d ON d.user_id=u.user_id "
                . " WHERE u.role_id=12 and u.status=1 and u.company_id=".$this->session->userdata('company');
        $query = $this->db->query($sql);
        $res = $query->result_array();
        //echo $this->db->last_query(); exit;
        $options = 0;
        $options.="<option value='0'>Select Stockist</option>";
        foreach ($res as $v) {
            $options.="<option value='" . $v['user_id'] . "'>" . $v['distributor_name'] . " ( " . $v['employee_id'] . " ) </option>";
        }
        echo $options;
    }
    function quote_approval_list(){
         //$lead_id = @icrm_decode($encoded_id);
//
//        if (checkQuote($lead_id) == 0) {
//            redirect(SITE_URL . 'openLeads');
//        }
//        $leadStatus = getLeadStatusID($lead_id);

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Quote Approval";
        $data['nestedView']['cur_page'] = 'quoteApprovalList';
        $data['nestedView']['parent_page'] = 'quoteApprovalList';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/lead.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/quote.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Quote Approval';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Quote Approval', 'class' => '', 'url' => SITE_URL . 'quoteApprovalList');
        //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

       
        $data['pageDetails'] = 'quoteApprovalList';
        //$data['lead_id'] = $lead_id;

        # Search Functionality
        $psearch = $this->input->post('searchApprveQuote', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'billing_id' => $this->input->post('billing_id', TRUE),
                'quote_id' => $this->input->post('quote_id', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'billing_id' => $this->session->userdata('billing_id'),
                     'quote_id' => $this->session->userdata('quote_id')
                );
            } else {
                $searchParams = array(
                    'billing_id' => '',
                    'quote_id'=>''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;


        $config = get_paginationConfig();

        $current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();

        $quoteSearch = array();
        $quoteSearch = $this->quote_model->getQuotesForApproval($current_offset, $config['per_page'], @$searchParams);

        # Loading the data array to send to View
        $data['quoteSearch'] = @$quoteSearch['resArray'];
        
        $data['count'] = @$quoteSearch['count'];


        # Default Records Per Page - always 10
        /* pagination start */
        $config['base_url'] = SITE_URL . 'quoteApprovalList/';
        # Total Records
        $config['total_rows'] = $data['count'];

        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        if ($data['pagination_links'] != '') {
            $data['last'] = $this->pagination->cur_page * $config['per_page'];
            if ($data['last'] > $data['total_rows']) {
                $data['last'] = $data['total_rows'];
            }
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $config['per_page']) + 1) . ' to ' . ($data['last']) . ' of ' . $data['total_rows'];
        }
        $data['sn'] = $current_offset + 1;
        /* pagination end */

        # Two query results - Available shop details and count of rows - are returned
        $search_fields = 0;
        $quoteSearch = array();
        $quoteSearch = $this->quote_model->getQuotesForApproval($current_offset, $config['per_page'], @$searchParams);
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name");
        # Loading the data array to send to View
        $data['quoteSearch'] = @$quoteSearch['resArray'];
        
        $data['count'] = @$quoteSearch['count'];
//        echo "<pre>";
//        print_r($data);
//        die();
        $this->load->view('lead/openQuoteApprovalList', $data);
    }
    
    public function quote_approve($quote_id=0)
    {
        $quote_id=@icrm_decode($quote_id);
        // new upate for fix: 24-05-2018  -- start
        if($quote_id=='')
        {
            redirect(SITE_URL . 'quoteApprovalList'); exit;
        }
        // Get Latest Quote Revision ID
        $quote_revision_id = getRecentQuoteRevisionID($quote_id);
        $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id); // mahesh: 5th Jan 2018
        if($quote_format_type==2)
        {
            $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-times"></i></div>
                                    <strong>Error!</strong> Quote '.$quote_id.' is in new format, you cant approve it with old quote approval page.
                                 </div>');
            redirect(SITE_URL . 'quoteApprovalList'); exit;
        } 
        // new upate for fix: 24-05-2018  -- End
        $this->db->trans_begin();
        $where = array('quote_id' => $quote_id);
        $dataArr = array('status' => 2); //, 'approved_by' => $this->session->userdata('user_id'), 'approved_time' => date('Y-m-d H:i:s'));
        $quoteCurrentStatus = getCurrentQuoteStatus($quote_id);
        $this->Common_model->update_data('quote',$dataArr, $where);
        addQuoteStatusHistory($quote_id, 2);
        if($quoteCurrentStatus == 6)
        {
            updateOtherQuoteRevisionStatus($quote_id); // Updating revision 1 to 2
            updateOtherQuoteRevisionStatus($quote_id, 3, 1); // Updating revision 3 to 1
        }
        else
        {
            $q = 'SELECT opportunity_id from quote_details where quote_id = "'.$quote_id.'"';
            $r = $this->db->query($q);
            foreach($r->result_array() as $row)
            {
                addOpportunityStatusByQuote($row['opportunity_id'], 2);
            }
            leadStatusUpdate(getLeadFromQuote($quote_id));
        }

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Error!</strong> There\'s a problem occured while approving Quote!
                                 </div>');
            redirect($this->agent->referrer());
                
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Quote has been Approved successfully!
                                 </div>');
            redirect($this->agent->referrer());
        }
    }
    
    public function quote_reject($quote_id=0)
    {
        $quote_id=@icrm_decode($quote_id);
        // new upate for fix: 24-05-2018  -- start
        if($quote_id=='')
        {
            redirect(SITE_URL . 'quoteApprovalList'); exit;
        }
        // Get Latest Quote Revision ID
        $quote_revision_id = getRecentQuoteRevisionID($quote_id);
        $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id); // mahesh: 5th Jan 2018
        if($quote_format_type==2)
        {
            $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-times"></i></div>
                                    <strong>Error!</strong> Quote '.$quote_id.' is in new format, you cant reject it with old quote approval page.
                                 </div>');
            redirect(SITE_URL . 'quoteApprovalList'); exit;
        } 
        // new upate for fix: 24-05-2018  -- end
        $this->db->trans_begin();
        $where = array('quote_id' => $quote_id);
        $quoteCurrentStatus = getCurrentQuoteStatus($quote_id);
        if($quoteCurrentStatus == 6)
        {
            $dataArr = array('status' => 2); //, 'approved_by' => $this->session->userdata('user_id'), 'approved_time' => date('Y-m-d H:i:s'));
            $this->Common_model->update_data('quote',$dataArr, $where);
            addQuoteStatusHistory($quote_id, 2);
            updateOtherQuoteRevisionStatus($quote_id, 3, 2); // Updating revision 3 to 2
        }
        else
        {
            $dataArr = array('status' => 10); //, 'approved_by' => $this->session->userdata('user_id'), 'approved_time' => date('Y-m-d H:i:s'));
            $this->Common_model->update_data('quote',$dataArr, $where);
            addQuoteStatusHistory($quote_id, 10);
        }
        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Error!</strong> There\'s a problem occured while approving Quote!
                                 </div>');
            redirect($this->agent->referrer());
                
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Quote has been Rejected successfully!
                                 </div>');
            redirect($this->agent->referrer());
        }
    }

    public function quoteDiscount()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Quote Discount For Approval";
        $data['nestedView']['cur_page'] = 'quoteDiscount';
        $data['nestedView']['parent_page'] = 'quoteDiscount';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/quote.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Quote Discount (%) For Approval';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Quote Discount For Approval', 'class' => '', 'url' => SITE_URL . 'quoteDiscount');

        $qry = "SELECT * from quote_approval";
        $data['quoteApproval'][7] = array('min' => 0, 'max' => 30);
        $data['quoteApproval'][8] = array('min' => 30, 'max' => 35);
        $data['quoteApproval'][9] = array('min' => 35, 'max' => 100);
        $quoteApproval = $this->Common_model->get_query_result($qry);
        foreach($quoteApproval as $row)
        {
            $data['quoteApproval'][$row['role_id']] = array('min' => $row['min'], 'max' => $row['max']);
        }
        $this->load->view('quote/quoteDiscountView', $data);
    }

    public function quoteDiscountApp()
    {
        if($this->input->post('quoteDiscountApp') != "")
        {
            $where = array('role_id' => 7);
            $dataArr = array('max' => $this->input->post('rbh'));
            $this->Common_model->update_data('quote_approval',$dataArr, $where);

            $where = array('role_id' => 8);
            $dataArr = array('min' => $this->input->post('rbh'), 'max' => $this->input->post('nsm'));
            $this->Common_model->update_data('quote_approval',$dataArr, $where);

            $where = array('role_id' => 9);
            $dataArr = array('min' => $this->input->post('ch'));
            $this->Common_model->update_data('quote_approval',$dataArr, $where);
            $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Quote Discount (%) For Approval has been successfully!
                                 </div>');
        }
        redirect(SITE_URL.'quoteDiscount');
    }

    public function quoteRevision($encoded_id) {
        
        $quote_id = @icrm_decode($encoded_id);
        if ($quote_id=='') {
            redirect(SITE_URL . 'openLeads');
        }

        $lead = getLeadDetailsByQuote($quote_id);
        $quote_number = $this->Common_model->get_value('quote',array('quote_id'=>$quote_id),'quote_number');
        //$warranty = $this->Common_model->get_value('quote',array('quote_id'=>$quote_id),'warranty');
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Quote Revision";
        $data['nestedView']['cur_page'] = 'openLeads';
        $data['nestedView']['parent_page'] = 'openLeads';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/lead.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/quote.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Quote Revision';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Quote Details', 'class' => '', 'url' => SITE_URL . 'openQuoteDetails/'.icrm_encode($lead['lead_id']));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Quote ID - ' . $quote_number, 'class' => 'active', 'url' => '');

        
        $data['leadStatus'] = $lead['status'];
        $data['channel_partner_id']  = $this->Common_model->get_value('quote',array('quote_id'=>$quote_id),'channel_partner_id');
        $data['row']  = $this->quote_model->getLatestRevisionDetails($quote_id);
        //print_r($data['row']);die;
        $margin_details = $this->Common_model->get_data('quote_op_margin_approval',array('quote_revision_id'=>$data['row']['quote_revision_id']));

        //print_r($margin_details); exit();
        $op_details = array();
        if($margin_details)
        {
            foreach ($margin_details as $mrow) {
                $op_details[$mrow['opportunity_id']] = $mrow;
            }
        }
        $data['op_details'] = $op_details;
        //print_r($data['op_details']);die;
        $data['lead_id'] = $lead['lead_id'];

        $where_con = array();
        if($lead['role_id']=='4')
        {
           $where_con = array('billing_info_id!=' => 3); // Not including stockist
        }
        else
        if ($lead['role_id'] != '4' && $lead['user2'] == NULL) {
            $where_con = array('billing_info_id!=' => 2); // Not including distributor
        } 
        //die();
        $data['lead_user_id'] = $lead['user_id'];
        $data['lead_user_role_id'] = getUserRole($data['lead_user_id']);
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name", $where_con);
        /*Fetching New Channel Partners */
        $data['channel_partners']=$this->Common_model->get_data('channel_partner',array('company_id'=>$this->session->userdata('company'),'status'=>1));
        /* Mahesh Phase2 Capture additional terms in quote start */
        $data['products'] = $this->Common_model->get_data('product',array('status'=>1,'company_id'=>$this->session->userdata('company')));
        $currency_code = get_quote_currency_details($quote_id);
        $data['discount_types'] = get_advance_types($currency_code);
        $data['dealers']    =   $this->quote_model->getDistributors();
        $data['free_supply_item_percentage'] = $this->Common_model->get_data('free_supply_item_percentage',array('item_id = 1'));
        // echo '<pre>'; print_r($data['free_supply_item_percentage']);die;
        //$data['warranty'] = $warranty;
        /* Mahesh Phase2 Capture additional terms in quote END */        
        $this->load->view('quote/quote_revision', $data);
        
    }

}