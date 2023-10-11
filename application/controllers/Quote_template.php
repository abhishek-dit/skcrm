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
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

        $data['leadStatus'] = $leadStatus;
        $data['pageDetails'] = 'Quote';
        $data['lead_id'] = $lead_id;


        $search_fields = 0;
        $quoteSearch = array();
        $quoteResults = $this->quote_model->getQuoteDetailsByLead($lead_id);

        if($quoteResults)
        {

            foreach ($quoteResults as $row) {
                $quoteSearch[$row['quote_revision_id']][] = $row;
            }
        }
        //echo $this->db->last_query(); exit;
        //echo '<pre>';print_r($quoteSearch);echo '</pre>'; exit;

        # Loading the data array to send to View
        $data['quoteSearch'] = @$quoteSearch;

        #For add quote popup
        $data['opportunities'] = $this->quote_model->get_opportunities($lead_id);
        //print_r($data['opportunities']); exit;
        $data['productCategories'] = $this->quote_model->getProductCategories($lead_id);

        $lead_user_id = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user_id");
        $lead_user_role_id = getUserRole($lead_user_id);

        $lead_user2 = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user2");
        $where_con = array();
        if($lead_user_role_id=='4')
        {
           $where_con = array('billing_info_id!=' => 3); // Not including stockist
        }
        else if ($lead_user_role_id != '4' && $lead_user2 == NULL) {
            $where_con = array('billing_info_id!=' => 2); // Not including distributor
        } 
        //die();
        $data['lead_user_id'] = $lead_user_id;
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name", $where_con);

        $data['checkPage'] = 1;//1 for Open Pages. 0 for Closed Pages
        /* Mahesh Phase2 Capture additional terms in quote start */
        $data['products'] = $this->Common_model->get_data('product',array('status'=>1));
        $data['discount_types'] = get_advance_types();
        $data['dealers']    =   $this->quote_model->getDistributors();
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

    public function addQuoteRevision()
    {
        //exit('testingg');
        if($this->input->post('submitAddRevision') != '')
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

            $this->db->trans_begin();
            $quote_id = $this->input->post('quote_id');
            $billing_id = $this->input->post('billing_name');
            $advance = $this->input->post('advance');
            if($advance=='') $advance = 0;
            $balance_payment_days = $this->input->post('balance_payment_days');
            $dealer_commission = $this->input->post('dealer_commission');
            $dealer = $this->input->post('dealer');
            $warranty = $this->input->post('warranty');
            $advance_type = $this->input->post('advance_type');


            $prev_quote_revision_id = $this->input->post('prev_quote_revision_id');
            $rev_op_id = @$this->input->post('rev_op_id');
            if($rev_op_id=='')
                $rev_op_id = array();
            $pqr_row = $this->Common_model->get_data_row('quote_revision',array('quote_revision_id'=>$prev_quote_revision_id));
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
                "warranty" => $warranty,
                "advance_type" => $advance_type,
                "advance" => $advance,
                "created_by" => $_SESSION['user_id']
            );
            if ($billing_id == 3) $dataArr2['stockist_id'] = $this->input->post('stokist_id');
            if($balance_payment_days !='') $dataArr2['balance_payment_days'] = $balance_payment_days;
            if($dealer_commission !='') $dataArr2['dealer_commission'] = $dealer_commission;
            if($dealer !='') $dataArr2['dealer_id'] = $dealer;

            $dataArr2['status'] = 3;
            $quotePresentStatus = getCurrentQuoteStatus($quote_id);
            updateOtherQuoteRevisionStatus($quote_id,3);


            $quote_revision_id = $this->Common_model->insert_data('quote_revision', $dataArr2);
            $quoteStatus = 6;
            
            $discount_type = $this->input->post('discount_type');
            $discount = $this->input->post('discount');

            $product_id_arr = $this->input->post('product_id',TRUE);
            $qty_arr = $this->input->post('qty',TRUE);
            
            if($op_id)
            {
                $op_count = 0; $approved_op_count = 0; $rejected_op_count = 0; $approval_req_ops = array();
                // looping opportunities
                foreach ($op_id as $opportunity_id) {
                    $op_count++;
                    $disc_type = @$discount_type[@$opportunity_id];
                    $disc_val = @$discount[@$opportunity_id];
                    $ma_data = array('quote_revision_id'    =>  $quote_revision_id,
                                     'opportunity_id'       =>  $opportunity_id,
                                     'discount_type'        =>  @$disc_type,
                                     'discount'             =>  @$disc_val
                                     );

                    // Free supply items
                    if(in_array($opportunity_id, $rev_op_id))
                    {
                        $free_products_arr = $product_id_arr[$opportunity_id];
                        $free_qty_arr = $qty_arr[$opportunity_id]; $cost_of_free_supply = 0;
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
                            $data['total_warranty_in_years'] = ($warranty>0)?round(($warranty/12),2):0;
                            
                            if($advance!='')
                            {
                                if ($advance_type==2) 
                                    $advance = round(($advance/$row['mrp'])*100,2);
                            }
                            else $advance = 0;
                            $data['advance'] = $advance;
                            $data['balance_payment_days'] = ($balance_payment_days!='')?$balance_payment_days:0;
                            $data['dealer_commission'] = ($dealer_commission>0)?$dealer_commission:0;
                            
                            
                            $data['cost_of_free_supply'] = $cost_of_free_supply;
                            $data['net_selling_price'] = $nsp;
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
                            // Get Previous Revision Margin Info
                            $ma_row = $this->Common_model->get_data_row('quote_op_margin_approval',array('quote_revision_id'=>$prev_quote_revision_id,'opportunity_id'=>$opportunity_id));
                            $previous_margin_approval_id = $ma_row['margin_approval_id'];
                            $ma_data = array('quote_revision_id'    =>  $quote_revision_id,
                                     'opportunity_id'       =>  $opportunity_id,
                                     'discount_type'        =>  $ma_row['discount_type'],
                                     'discount'             =>  $ma_row['discount'],
                                     'approval_at'          =>  $ma_row['approval_at'],
                                     'close_at'             =>  $ma_row['close_at'],
                                     'status'               =>  $ma_row['status']
                                     );
                            if($ma_row['status']==2) $approved_op_count++;
                            if($ma_row['status']==3) $rejected_op_count++;
                        }
                    }                   
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
                            $quote_approvers = array();
                    foreach($approval_req_ops as $orow) {
                        if(!array_key_exists($orow['approval_at'], $quote_approvers))
                            $quote_approvers[$orow['approval_at']] = getOppApproverEmailsByRole($orow['approval_at'],$opportunity_id);
                        //echo $this->db->last_query().'<br>';
                        if(count($quote_approvers[$orow['approval_at']])>0)
                        {
                            foreach ($quote_approvers[$orow['approval_at']] as $key => $urow) {
                                $to = mail_to($urow['email_id']);
                                echo $urow['email_id'].'<br>';
                                $encoded_id = icrm_encode($orow['margin_approval_id'].'_'.$urow['user_id']);
                                $email_data = getQuoteApprovalEmailData($quote_revision_id,$quote_id,$orow['approval_at'],$key);
                                $subject = $email_data['subject'];
                                //$message = $email_data['message'];
                                $message = str_replace('{ENCODED_ID}', $encoded_id, $email_data['message']);
                                send_email($to,$subject,$message);
                                //echo $to.'<br>'.$subject.'<br>'.$message.'<br>'; 
                            }
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

    public function quoteAdd() 
    {
        if ($this->input->post('submitQuote') != "") 
        {
            if (count($_POST['op_id']) > 0) 
            {
                $opportunities = implode(",", $_POST['op_id']);
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

                if($quoteExist == 1)
                {
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> A quote with quote ID: '.$quote_id.' already exists for the selected Opportunities. Please add a revision to that instead!
                                         </div>');
                    redirect($this->agent->referrer());

                }
                else
                {
                    //print_r($_POST['op_id']); die();
                    $this->db->trans_begin();
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
                        "warranty" => $this->input->post('warranty'),
                        "advance_type"  => $this->input->post('advance_type'),
                        "advance"  => $this->input->post('advance')
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
                        $dataArr2 = array(
                            "quote_id" => $quote_id,
                            "opportunity_id" => $op_id,
                            "sub_category_id" => $this->input->post('sub_category_id')[$op_id],
                            "mrp" => $productDetails['mrp'],
                            "ed" => $productDetails['ed'],
                            "vat" => $productDetails['vat'],
                            "gst" => $productDetails['gst'],
                            "freight_insurance" => $productDetails['freight_insurance']
                            );
                        $quote_details_id = $this->Common_model->insert_data('quote_details', $dataArr2);
                        addOpportunityStatusByQuote($op_id, $quote_status);

                        //Margin Analysis auto approval
                        $ma_data = array('quote_revision_id'   =>  $quote_revision_id,
                                     'opportunity_id'       =>  $op_id,
                                     'discount_type'        =>  1,
                                     'discount'             =>  0,
                                     'status'               =>  2
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
        //print_r($data['quotation']); exit;
        $data['quote_date'] =  format_date($data['quotation']['created_time']);
        $data['tax_type']   =   tax_type($data['quote_date']);
        
        $quote_id = $data['quotation']['quotation_id'];
        $data['quote_revision_number'] = getQuoteRevisionNumber($quote_id,$quote_revision_id);
        $data['free_supply_items'] = $this->quote_model->getQuoteRevisionFreeSupplyItems($quote_revision_id);
        $data['quote_info'] = $this->Common_model->get_data_row('quote',array('quote_id'=>$quote_id));
        $data['quote_format_type'] = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id); //mahesh: 5th Jan 2018
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
        }
        //echo $quote_view_file;
        $this->load->view('quote/'.$quote_view_file, $data);
        //$this->load->view('quote/quotation_view', $data);
    }


    function quotation_pdf($qid) {
        $quote_revision_id = icrm_decode($qid);
        $data['quotation'] = $this->quote_model->get_quote_details1($quote_revision_id);
        $quotation_id = $data['quotation']['quotation_id'];
        $data['quote_revision_number'] = getQuoteRevisionNumber($quotation_id,$quote_revision_id);
        $data['free_supply_items'] = $this->quote_model->getQuoteRevisionFreeSupplyItems($quote_revision_id);
        $data['quote_info'] = $this->Common_model->get_data_row('quote',array('quote_id'=>$quotation_id));
        //echo $this->db->last_query();
        $data['quote_date'] =  format_date($data['quotation']['created_time']);
        $data['tax_type']   =   tax_type($data['quote_date']);
        $data['quote_format_type'] = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id);
        $data['quote_revision_id'] = $quote_revision_id;
        //echo $data['quote_format_type']; exit;
        //echo '<pre>';print_r($data['quote_info']); echo '</pre>';die();
        $data['table_width']="1550";
        switch ($data['quote_format_type']) {
            case 1: // Old Format
                $quote_view_file = 'quotation_view1';
            break;
            
            case 2:
                $quote_view_file = 'quotation_pdf_template';
            break;
        }
        $quote_content = $this->load->view('quote/'.$quote_view_file, $data, true);
        //print_r($quote_content); exit;
        $lead_id = getLeadFromQuote($quotation_id);
        $ref = getQuoteReferenceID($lead_id, $quotation_id);
        $ref = '[QUOTE_REF_ID]';
        $pdf = new Pdf('P', 'px', 'A4', true, 'UTF-8', false);
            $pdf->setRef($ref);
            $pdf->setRoleCheck($data['quotation']['roleCheck']);
            // Phase2 Update: Replacing quote created time with current timestamp when user takes print
            $pdf->setDate('[DATE_TIME]'); //date('Y-m-d H:i:s')
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
        switch ($cnote['cnote_type']) {
            case 1:
                $data = $this->quote_model->getContractPDFDetails($contract_note_id);

                $data['cnote_date'] =  format_date($data['contract_note']['created_time']);
                $data['tax_type']   =   tax_type($data['cnote_date']);
                // Get lead user details
                $data['lead_user'] = $this->quote_model->getLeadOwnerDetails($data['lead_id_val']);
                // Get Quote Details
                $data['quotes'] = $this->quote_model->getQuotesByCNoteID($contract_note_id);
                //print_r($data['quotes']); exit;

                $data['dist_row'] = array();
                if(@$data['quotes'][0]['dealer_id']>0&&@$data['quotes'][0]['dealer_commission']>0)
                {
                    $data['dist_row'] = getDistributorDetails(@$data['quotes'][0]['dealer_id']);
                }
                //print_r($data['quotes']); exit;
                $data['quote_format_type']   =   quote_format_type($data['cnote_date']);
                /*echo $data['cnote_date'].'-->'.$data['tax_type'];
                echo print_r($data); exit;*/
                $lead_id = $data['lead_id_val'];
                $customerSAPCode = getCustomerSAPCode($lead_id);
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
                    /*echo $this->db->last_query();
                    echo '<pre>';print_r($data['quote_op_approvals']); echo '</pre>'; 
                    foreach ($quote_op_approvals as $quote_id => $row) {
                        echo '<pre>';print_r($row['opportunities']);echo '</pre>';
                    }
                    exit;*/
                }
                $view_file = ($data['quote_format_type']==1)?'contractPDF':'contractPDF_new_template';
            break;
            case 2:
                $data = array();
                $data['contract_note'] = $this->quote_model->getDistributorCNoteDetails($contract_note_id);
                //echo '<pre>'; print_r($data['contract_note']); echo '</pre>'; exit;
                $data['product_details'] = $this->quote_model->getDistributorCNoteProductDetails($contract_note_id);
                $quote_approval_details = $this->quote_model->getCNotePoApprovalDetails($contract_note_id);
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
        
        $quote_content = $this->load->view('quote/'.$view_file, $data, true);
        //echo $quote_content; exit;

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
                . " WHERE u.role_id=12 and u.status=1 ";
        $query = $this->db->query($sql);
        $res = $query->result_array();
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
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Quote ID - ' . $quote_id, 'class' => 'active', 'url' => '');

        
        $data['leadStatus'] = $lead['status'];
        //$data['row']  = $this->Common_model->get_data_row('quote',array('quote_id'=>$quote_id));
        $data['row']  = $this->quote_model->getLatestRevisionDetails($quote_id);
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
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name", $where_con);

        /* Mahesh Phase2 Capture additional terms in quote start */
        $data['products'] = $this->Common_model->get_data('product',array('status'=>1));
        $data['discount_types'] = get_advance_types();
        $data['dealers']    =   $this->quote_model->getDistributors();
        /* Mahesh Phase2 Capture additional terms in quote END */        
        $this->load->view('quote/quote_revision', $data);
        
    }

}