<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Purchase_order extends Base_controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Po_model');
        $this->load->model('Opportunity_model');
        
    }

    public  function po_list()
    {   
        $defined_roles=array(5,6,7,8);
        $role=$this->session->userdata('role_id');
        if(in_array($role,$defined_roles))
        {
            # Data Array to carry the require fields to View and Model
            $data['nestedView']['heading'] = "Purchase Order List";
            $data['nestedView']['cur_page'] = 'po_list';
            $data['nestedView']['parent_page'] = 'po_list';

            # Load JS and CSS Files
            $data['nestedView']['js_includes'] = array();
            $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
           

            $data['nestedView']['css_includes'] = array();
           
            # Breadcrumbs
            $data['nestedView']['breadCrumbTite'] = 'Purchase Order';
            $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
            $data['nestedView']['breadCrumbOptions'][] = array('label' => 'PO List', 'class' => '', 'url' => SITE_URL . 'po_list');
            //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

            $data['pageDetails'] = 'po_list';

            # Search Functionality
            $psearch = $this->input->post('search', TRUE);
            $start_date = $this->input->post('start_date');
            if($start_date!='')
            {
                $start_date = date('Y-m-d',strtotime($start_date));
            }
            else
            {
                $start_date = '';
            }
            $end_date = $this->input->post('end_date');
            if($end_date!='')
            {
                $end_date = date('Y-m-d',strtotime($end_date));
            }
            else
            {
                $end_date = '';
            }
            if ($psearch != '') {
                $searchParams = array(
                    'purchase_order_id' => $this->input->post('purchase_order_id', TRUE),
                    'approval_status' => $this->input->post('approval_status'),
                    'start_date'=> $start_date,
                    'end_date'=> $end_date,
                    'users_id'=>$this->input->post('users_id')
                );
                $this->session->set_userdata($searchParams);
            } else {

                if ($this->uri->segment(2) != '') {
                    $searchParams = array(
                         'purchase_order_id' => $this->session->userdata('purchase_order_id'),
                         'approval_status' => $this->session->userdata('approval_status'),
                         'start_date'=>$this->session->userdata('start_date'),
                         'end_date'=>$this->session->userdata('end_date'),
                         'users_id'=>$this->session->userdata('users_id')
                    );
                } else {
                    $searchParams = array(
                        'purchase_order_id'=>'',
                        'approval_status'=>'',
                        'start_date'=>'',
                        'end_date'=>'',
                        'users_id'=>''
                    );
                    $this->session->unset_userdata(array_keys($searchParams));
                }
            }
            $data['searchParams'] = $searchParams;
            /* pagination start */
            $config = get_paginationConfig();
            $config['base_url'] = SITE_URL.'po_list/'; 
            # Total Records
            $config['total_rows'] = $this->Po_model->get_total_po_rows($searchParams);
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
            $data['flag']=1;
            # Search Results
            $data['searchResults'] = $this->Po_model->po_list($searchParams,$config['per_page'], $current_offset);
            $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name",array('billing_info_id !='=>2));
            $data['users'] = $this->Common_model->get_dropdown("user", "user_id", "first_name",array('role_id'=>5,'status'=>1,'company_id'=>$this->session->userdata('company')),'concat("(",employee_id,")-",first_name,last_name)first_name');
            $check=$this->Po_model->check_po_documents();
            //echo $check.'-->'.$this->db->last_query(); exit;
            $data['enable_po_upload_check']=get_preference('enable_po_upload_check','dealer_settings');
            // echo 'hi';exit;
            $data['check']=$check;
            $data['btn_class']="btn btn-primary";
            $data['link_class']="fa fa-link";
            $data['title']='Tag Opportunity';
            $data['redirect']=1;
            $this->load->view('po/po_list', $data);
        }
        else
        {
            echo "Sorry !,Your role is not unable to access this page";
        }
    }

    public  function untag_po_list()
    {   
        $defined_roles=array(7);
        $role=$this->session->userdata('role_id');
        if(in_array($role,$defined_roles))
        {
        
            # Data Array to carry the require fields to View and Model
            $data['nestedView']['heading'] = "Purchase Order List";
            $data['nestedView']['cur_page'] = 'untag_po_list';
            $data['nestedView']['parent_page'] = 'untag_po_list';

            # Load JS and CSS Files
            $data['nestedView']['js_includes'] = array();
            $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
           

            $data['nestedView']['css_includes'] = array();
           
            # Breadcrumbs
            $data['nestedView']['breadCrumbTite'] = 'Purchase Order';
            $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
            $data['nestedView']['breadCrumbOptions'][] = array('label' => 'PO List', 'class' => '', 'url' => SITE_URL . 'po_list');
            //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

           
            $data['pageDetails'] = 'untag_po_list';

            # Search Functionality
            $psearch = $this->input->post('search', TRUE);
            $start_date = $this->input->post('start_date');
            if($start_date!='')
            {
                $start_date = date('Y-m-d',strtotime($start_date));
            }
            else
            {
                $start_date = '';
            }
            $end_date = $this->input->post('end_date');
            if($end_date!='')
            {
                $end_date = date('Y-m-d',strtotime($end_date));
            }
            else
            {
                $end_date = '';
            }
            if ($psearch != '') {
                $searchParams = array(
                    'purchase_order_id' => $this->input->post('purchase_order_id', TRUE),
                    'approval_status' => $this->input->post('approval_status'),
                    'start_date'=> $start_date,
                    'end_date'=> $end_date,
                    'users_id'=>$this->input->post('users_id')
                );
                $this->session->set_userdata($searchParams);
            } else {

                if ($this->uri->segment(2) != '') {
                    $searchParams = array(
                         'purchase_order_id' => $this->session->userdata('purchase_order_id'),
                         'approval_status' => $this->session->userdata('approval_status'),
                         'start_date'=>$this->session->userdata('start_date'),
                         'end_date'=>$this->session->userdata('end_date'),
                         'users_id'=>$this->session->userdata('users_id')
                    );
                } else {
                    $searchParams = array(
                        'purchase_order_id'=>'',
                        'approval_status'=>'',
                        'start_date'=>'',
                        'end_date'=>'',
                        'users_id'=>''
                    );
                    $this->session->unset_userdata(array_keys($searchParams));
                }
            }
            $data['searchParams'] = $searchParams;
            //print_r($data['searchParams']);exit;

            /* pagination start */
            $config = get_paginationConfig();
            $config['base_url'] = SITE_URL.'untag_po_list/'; 
            # Total Records
            $config['total_rows'] = $this->Po_model->get_total_po_rows($searchParams);
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
            $data['flag']=1;
            # Search Results
            $data['searchResults'] = $this->Po_model->po_list($searchParams,$config['per_page'], $current_offset);
            $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name",array('billing_info_id !='=>2));
            $data['users'] = $this->Common_model->get_dropdown("user", "user_id", "first_name",array('role_id'=>5,'status'=>1),'concat("(",employee_id,")-",first_name,last_name)first_name');
           // echo 'hi';exit;
            $data['btn_class']="btn btn-danger";
            $data['link_class']="fa fa-unlink";
            $data['title']='UnTag Opportunity';
            $data['redirect']=2;
            $this->load->view('po/po_untag_list', $data);
        }
        else
        {
            echo " Sorry!,your Role is unable to access this page";
        }
    }
    
    public function add_po()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Add New Purchase Order";
        $data['nestedView']['cur_page'] = 'add_po';
        $data['nestedView']['parent_page'] = 'add_po';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/purchase_order.js"></script>';
      /*  $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/quote.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';*/

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Add New Purchase Order';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'PO List', 'class' => '', 'url' => SITE_URL . 'po_list');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add PO', 'class' => 'active', 'url' => '');

        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name",array('billing_info_id !='=>2));
        $data['segments'] = $this->Po_model->get_user_product_segments();
        $check=$this->Po_model->check_po_documents();
        if($check)
        {
            foreach($check as $key=>$value)
            {
                $po_docs[$key]=$value['purchase_order_id'];
                @$po_list.=$po_docs[$key];
            }
        }
        $po_list_arr=@implode(',', @$po_docs);
        $data['check']=$check;
        $data['po_id']=$po_list_arr;
        $locations_det = get_user_dist_country();
        $currency_val = $this->Common_model->get_value('currency',array('currency_id'=>$locations_det[2]),'code');
        $data['currency_val'] = $currency_val;
        $data['discount_types'] = get_advance_types($currency_val);
        $data['pageDetails'] = 'add_po';
        $data['flag']=2;
        if(count($check)==0)
        {
            $this->load->view('po/po_list',$data);
        }
        else
        {
            if(get_preference('enable_po_upload_check','dealer_settings')==1)
            {
                $this->load->view('po/check_po_docs',$data);
            }
            else
            {
                $this->load->view('po/po_list',$data);
            }
        }

    }

    public function insert_po()
    {
        if($this->input->post('submit'))
        {
            goto po_start;
            po_start:
            $unit_currency = $this->input->post('unit_currency');
            $currency_check= $this->Po_model->get_currency_availability($unit_currency);
            if($currency_check==0)
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong>Please Add Currency Conversion Factor in Currency Convertor Screen.Then Try Again !
                                    </div>');
                                    redirect(SITE_URL.'po_list');
            }
            $lead_str_arr = get_current_unique_numbers("purchase_order","po_counter","purchase_order_id");
            $po_counter=$lead_str_arr[0];
            $po_number=$lead_str_arr[1];
            $balance_payment_days = ($this->input->post('balance_payment_days')>0)?$this->input->post('balance_payment_days'):0;
            $advance_type = $this->input->post('advance_type');
            $advance = $this->input->post('advance');
            $warranty = $this->input->post('warranty');
            $dat = array(
                'user_id'          => $this->session->userdata('user_id'),
                'warranty'         => $this->input->post('warranty'),
                'advance_type'     => $this->input->post('advance_type'),
                'advance'          => $this->input->post('advance'),
                'balance_payment_days' => $balance_payment_days,
                'company_id'=>$this->session->userdata('company')
                );
            try
            {
                check_unique_numbers_constraint('purchase_order','po_counter',$po_counter);
            }
            catch(Exception $e)
            {
                goto po_start;
            }
            $dat['po_counter']=$po_counter;
            $dat['po_number']=$po_number;
            $this->db->trans_begin();
            // Insert Purchase Order
            $purchase_order_id = $this->Common_model->insert_data('purchase_order',$dat);
            $rev_data = array(
                'purchase_order_id'=> $purchase_order_id,
                'warranty'         => $this->input->post('warranty'),
                'advance_type'     => $this->input->post('advance_type'),
                'advance'          => $this->input->post('advance'),
                'balance_payment_days' => $this->input->post('balance_payment_days'),
                'status'           => 1,
                'created_by'       => $this->session->userdata('user_id'),
                'created_time'     => date('Y-m-d H:i:s')
                );
            // Insert PO Status History
            addPoStatusHistory($purchase_order_id,1);
            // Insert PO Revision
            $po_revision_id = $this->Common_model->insert_data('po_revision',$rev_data);

            $product_id = $this->input->post('product_id');
            $qty = $this->input->post('qty');
            $discount_type = $this->input->post('discount_type');
            $discount=$this->input->post('discount');
           
             // Get Product prices
            $get_details = get_user_dist_country();
            if($get_details[0]==1)
            {
               $prod_price_results = $this->Po_model->getProductPriceInfo($product_id);
            }
            else
            {
                $prod_price_results = $this->Po_model->getProductPriceInfoCurrency($get_details[2],$product_id);
                //checking the products exists in other segment
                $check_products = $this->Po_model->getUserProductsBySegmentCheckCurrency($get_details[2]);
                if(count($check_products)==0)
                {
                    $prod_price_results = $this->Po_model->getProductPriceInfo($product_id);
                }
            }
           
           $product_unit_price = array(); $product_fi = $product_gst = array();$product_dp_price=array();
            foreach ($prod_price_results as $prow) {
                $product_unit_price[$prow['product_id']] = $prow['dp'];
                $product_fi[$prow['product_id']] = $prow['freight_insurance'];
                $product_gst[$prow['product_id']] = $prow['gst'];
                $product_dp_price[$prow['product_id']] = $prow['dp'];
            }
            $approval_req_products = array();
            foreach($product_id as $key =>$value)
            {  
                $getFinalValue = getFinalValueAfterConversion($product_unit_price[$value],$unit_currency[$key]); 
                $total_converted_value= $getFinalValue[0];
                $currency_factor=$getFinalValue[1];
                $product_array=array(
                    'purchase_order_id' => $purchase_order_id,
                    'product_id'        => $value,
                    'qty'               => $qty[$key],
                    'unit_price'        => $product_unit_price[$value],
                    'dp'                => $product_dp_price[$value],
                    'freight_insurance' => $product_fi[$value],
                    'gst'               => $product_gst[$value],
                    'currency_id'       => $unit_currency[$key],
                    'total_value'       =>  $total_converted_value,
					'currency_factor'   =>  $currency_factor,
                    'created_by'        => $this->session->userdata('user_id')
                    );
                // Insert Po Products
                $this->Common_model->insert_data('po_products',$product_array);

                $app_data=array(
                    'po_revision_id'    => $po_revision_id,
                    'product_id'        => $value,
                    'discount_type'     => $discount_type[$key],
                    'discount'          => $discount[$key],
                    'created_by'        => $this->session->userdata('user_id'),
                    'created_time'      => date('Y-m-d H:i:s'),
                    'status'            => 1
                    );
                // Margin Approval Process start here
                /*$app_data['approval_at'] = 7;
                $app_data['close_at'] = 9;*/
                $disc_type = $discount_type[$key];
                $disc_val = $discount[$key];
                $row = getPoProductPriceDetails($purchase_order_id,$value);
                $order_value = $mrp = $qty[$key]*$product_unit_price[$value];
                if($disc_type!=''&&$disc_val!='')
                $order_value = ($disc_type==1)?($order_value*(1-$disc_val/100)):($order_value-$disc_val);
                $nsp = round($order_value/(1+$row['gst']/100)/(1+$row['freight_insurance']/100));
                $discount_percenrage = round((($mrp - $order_value )/$mrp)*100,2);
                $data = array();
                $data['order_value'] = $order_value;
                $data['net_selling_price'] = $nsp;
                $dp = $product_unit_price[$value];
                $data['basic_price'] = $qty[$key]*$dp;
                //$data['basic_price'] = round($row['dp']*90/100);
                $data['total_warranty_in_years'] = ($warranty>0)?round(($warranty/12),2):0;
                
                if($advance!='')
                {
                    if ($advance_type==2) 
                        $advance = round(($advance/$mrp)*100,2);
                }
                else $advance = 0;
                $data['advance'] = $advance;
                $data['balance_payment_days'] = ($balance_payment_days!='')?$balance_payment_days:0;
                $data['dealer_commission'] = 0;
                
                
                $data['cost_of_free_supply'] = 0;
                $data['exclude_extra_warranty_in_nm'] = 1;
                $m_data = marginAnalysis($data);
                $variance_percentage = round(((($order_value/$qty[$key])-$dp)/$dp)*100,2);
                
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
                            case 7: default:// RBH
                                $app_data['close_at'] = $app_data['approval_at'] = 7;
                            break;
                            case 8: // NSM
                                $app_data['close_at'] = 8 ; $app_data['approval_at'] = 7;
                            break;
                            case 9: // NSM
                                $app_data['close_at'] = 9 ; $app_data['approval_at'] = 7;
                            break;
                        }
                        break;
                    }
                    
                }
                $check_roles = array(7,8,9);
                $dealer_region = getRegionforUser($this->session->userdata('locationString'));
                if(in_array($app_data['close_at'],$check_roles))
                {
                    if(!checkRbhExistForRegion($dealer_region)) // No RBH Exist
                    {
                        $app_data['approval_at'] = 8;
                        if($app_data['close_at']==7)
                        {
                            $app_data['close_at'] = 8; // move to NSM
                        }   
                    }
                }
                // Margin Approval Process ends here
                // Insert Po Product Approval
                $approval_id = $this->Common_model->insert_data('po_product_approval',$app_data);
                if(@$app_data['approval_at']!='')
                {
                        $approval_req_products[$value] = array('product_id'=>$value,'approval_at'=>$app_data['approval_at'],'approval_id'=>$approval_id);
                }
            }
          if($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while adding New PO !
                                     </div>');
                redirect(SITE_URL.'po_list');
                //echo 'transaction failed';
                    
            }
            else
            {
                $this->db->trans_commit();
                // Email alert for quote approval Start
                if(count($approval_req_products)>0)
                {
                            $po_approvers = array();
                    foreach($approval_req_products as $orow) {
                        if(!array_key_exists($orow['approval_at'], $po_approvers))
                            $po_approvers[$orow['approval_at']] = getPoProductApproverEmailsByRole($orow['approval_at'],$dealer_region,$orow['product_id']);
                        if(count($po_approvers[$orow['approval_at']])>0)
                        {
                            foreach ($po_approvers[$orow['approval_at']] as $urow) {
                                $to = mail_to($urow['email_id']);
                                $cc= "CRM@skanray.com";
                                $encoded_id = icrm_encode($orow['approval_id'].'_'.$urow['user_id']);
                                $email_data = getPoApprovalEmailData($po_revision_id,$purchase_order_id,$orow['approval_at']);
                                $subject = $email_data['subject'];
                                //$message = $email_data['message'];
                                $message = str_replace('{ENCODED_ID}', $encoded_id, $email_data['message']);
                                send_email($to,$subject,$message,$cc);
                            }
                        }
                        
                    }

                }
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong>New Purchase Order has been added successfully !
                                     </div>');
                redirect(SITE_URL.'po_list');
               
            }
        }
    }
    public function view_po($encoded_id)
    {   
         # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "View Purchase Order";
        $data['nestedView']['cur_page'] = 'view_po';
        $data['nestedView']['parent_page'] = 'view_po';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/po.js"></script>';
     

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'View Purchase Order';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'PO List', 'class' => '', 'url' => SITE_URL . 'po_list');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'View PO', 'class' => 'active', 'url' => '');
        $po_id = icrm_decode($encoded_id);
        $data['po_results'] = $this->Common_model->get_data_row('purchase_order',array('purchase_order_id'=>$po_id));
        $data['product_results'] = $this->Po_model->get_po_results($po_id);
        $data['stockist_results'] = $this->Po_model->get_stockist_list();
        $data['flag']=2;
        $data['display_results']=1;
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name",array('billing_info_id !='=>2));
        //fecthing opportunity tag
        $data['opportunity_details'] = $this->Po_model->get_opportunity_product_details($po_id);
        $data['view_list_page']=1;
        $this->load->view('po/po_list',$data);
    }
    public function view_po_untag($encoded_id)
    {   
         # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "View Purchase Order";
        $data['nestedView']['cur_page'] = 'view_po';
        $data['nestedView']['parent_page'] = 'view_po';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/po.js"></script>';
     

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'View Purchase Order';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'PO List', 'class' => '', 'url' => SITE_URL . 'po_list');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'View PO', 'class' => 'active', 'url' => '');
        $po_id = icrm_decode($encoded_id);
        $data['po_results'] = $this->Common_model->get_data_row('purchase_order',array('purchase_order_id'=>$po_id));
        $data['product_results'] = $this->Po_model->get_po_results($po_id);
        $data['stockist_results'] = $this->Po_model->get_stockist_list();
        $data['flag']=2;
        $data['display_results']=1;
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name",array('billing_info_id !='=>2));
        //fecthing opportunity tag
        $data['opportunity_details'] = $this->Po_model->get_opportunity_product_details($po_id);
        $data['view_list_page']=1;
        $this->load->view('po/po_untag_list',$data);
    }
        public function view_tagged_po($encoded_id)
    {   
         # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "View Purchase Order";
        $data['nestedView']['cur_page'] = 'view_po';
        $data['nestedView']['parent_page'] = 'view_po';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/po.js"></script>';
     

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'View Purchase Order';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'PO List', 'class' => '', 'url' => SITE_URL . 'po_list');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'View PO', 'class' => 'active', 'url' => '');
        $po_id = icrm_decode($encoded_id);
        $data['po_results'] = $this->Common_model->get_data_row('purchase_order',array('purchase_order_id'=>$po_id));
        $data['product_results'] = $this->Po_model->get_po_results($po_id);
        $data['stockist_results'] = $this->Po_model->get_stockist_list();
        $data['flag']=2;
        $data['display_results']=1;
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name",array('billing_info_id !='=>2));
        //fecthing opportunity tag
        $data['opportunity_details'] = $this->Po_model->get_opportunity_product_details($po_id);
        $data['view_list_page']=2;
        $this->load->view('po/po_list',$data);
    }
     public function download_po()
    {  
        $searchParams=array(
             'billing_id' => $this->input->post('billing_id', TRUE),
             'purchase_order_id' => $this->input->post('purchase_order_id', TRUE),
             'users_id' => $this->input->post('users_id')
            );
        $searchResults = $this->Po_model->po_download_list($searchParams);
        $data ='';
        $data = '<table border="1">';
        $data.='<thead>';
        $data.='<tr>';
        $data.='<th>Sno</th>';
        $data.='<th>Purchase Order Id</th>';
        $data.='<th>Billing To </th>';
        $data.='<th>Warranty</th>';
        $data.='<th>Created By</th>';
        $data.='</tr>';
        $data.='</thead>';
        $data.='<tbody>';
        
        if(count($searchResults)>0)
        {
            $i=1;
            foreach($searchResults as $row)
            {
                $data.='<tr>';
                $data.='<td>'.$i++.'</td>';
                $data.='<td>'.@$row['purchase_order_id'].'</td>';
                $data.='<td>'.@$row['billing_name'].'</td>';
                $data.='<td>'.@$row['warranty'].'</td>';
                $data.='<td>'.getUserName($row['user_id']).'</td>';
                $data.='</tr>';
            }
        }
        else
        {
            $data.='<tr><td colspan="15" align="center">No Files Found </td></tr>';
        }
        $data.='</tbody>';
        $data.='</table>';
        $time = date("Ymdhis");
        $xlFile='PO_'.$time.'.xls'; 
        header("Content-type: application/x-msdownload"); 
        # replace excelfile.xls with whatever you want the filename to default to
        header("Content-Disposition: attachment; filename=".$xlFile."");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $data;
    }
    public function tag_opportunity()
    {
        $po_id = icrm_decode($this->uri->segment(2));
        $data['nestedView']['heading'] = "Tag Opportunity";
        $data['nestedView']['cur_page'] = 'tag_opportunity';
        $data['nestedView']['parent_page'] = 'tag_opportunity';
        
        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/manage-opportunity.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/po.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
        
        # Breadcrumbs
        
        $data['nestedView']['breadCrumbTite'] = 'Tag Opportunities To PO Id- '.$po_id.'';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'po_list','class'=>'active','url'=>SITE_URL.'po_list');
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'Tag Opportunity','class'=>'active','url'=>'');
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
                      'end_date' => $this->input->post('end_date', TRUE)
                              );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(3)!='')
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
                      'end_date'=>$this->session->userdata('end_date')
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
                      'end_date' => ''
                                  );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;

       
        $data['po_id']=$po_id;
        //fetching po products
        $po_products=$this->Common_model->get_data('po_products',array('purchase_order_id'=>$po_id),array('product_id'));

        $product_ids = array();
        foreach ($po_products as $row)
         {
            $product_ids[] = $row['product_id'];
        }
        
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'tag_opportunity/'.icrm_encode($po_id); 
        # Total Records
        $config['total_rows'] = $this->Po_model->distributorOpportunityRows($searchParams,$product_ids,$po_id);
        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links(); 
        $current_offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($data['pagination_links']!= '') {
            $data['last']=$this->pagination->cur_page*$config['per_page'];
            if($data['last']>$data['total_rows']){
                $data['last']=$data['total_rows'];
            }
            $data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
         $data['sn'] = $current_offset + 1;
        /* pagination end */
        $data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
        
        $data['category_id'] = $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
        $data['groups'] = array(''=>'Select Group');
        $data['products'] = array(''=>'Select Product');
        $data['source_of_funds'] = $this->Common_model->get_data('source_of_funds',array());
        $data['relationship'] = $this->Common_model->get_data('relationship',array());

        //GETTING OPPORTUNITY STATUS OPTIONS
        $qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 5';
        $data['opportunity_status'] = $this->Common_model->get_query_result($qry);
        //GETTING EDIT OPPORTUNITY STATUS OPTIONS
        $qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 8';
        $data['edit_opportunity_status'] = $this->Common_model->get_query_result($qry);
        
        # Search Results
        $data['searchResults'] = $this->Po_model->distributorOpportunityResults($searchParams,$config['per_page'], $current_offset,$product_ids,$po_id);
        $data['s_cus'] = $this->Po_model->getSearchCustomer(@$searchParams['customer']);
        $data['s_created_user'] = $this->Po_model->getSearchUser(@$searchParams['created_user']);
        $data['opp_status'] = array(''=>'Select Stage') + $this->Common_model->get_dropdown('opportunity_status', 'status', 'name', 'status IN (1,2,3,4,5)');
        $data['product_id'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', [], 'concat(name, "( ", description, ")") name');
        $leads = $this->Po_model->getLeadDetails(3);
        $data['leads'] = array(''=>'Select Lead');
        foreach ($leads as $lead) 
        {
            $data['leads'][$lead['lead_id']] = "Lead ID - ".$lead['lead_id']." (".$lead['CustomerName'].")";
        }
        $data['encoded_id'] = 0;
        $data['pageInfo'] = 1;
        $data['check'] = 1;

        // get source of lead options
        $data['source_of_leads'] = $this->Common_model->get_data('source_of_lead',array('status'=>1));
        // get regions
        $data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
        $data['opportunties_tagged']=$this->Common_model->get_data('purchase_order_opportunity',array('purchase_order_id'=>$po_id,'status'=>1));
        $opportunities=array();
        foreach($data['opportunties_tagged'] as $row)
        {
            $opportunities[$row['opportunity_id']]['opportunity']=$row['opportunity_id'];
        }
         $data['opportunities']=$opportunities;
        $this->load->view('lead/tag_opportunity', $data);
        
    }
    public function untag_opportunity()
    {
        $po_id = icrm_decode($this->uri->segment(2));
        $data['nestedView']['heading'] = "UnTag Opportunity";
        $data['nestedView']['cur_page'] = 'tag_opportunity';
        $data['nestedView']['parent_page'] = 'tag_opportunity';
        
        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/lead.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/manage-opportunity.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/po.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
        
        # Breadcrumbs
        
       
        $data['nestedView']['breadCrumbTite'] = 'UnTag Opportunities To PO Id- '.$po_id.'';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'po_list','class'=>'active','url'=>SITE_URL.'po_list');
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'UnTag Opportunity','class'=>'active','url'=>'');
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
                      'end_date' => $this->input->post('end_date', TRUE)
                              );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(3)!='')
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
                      'end_date'=>$this->session->userdata('end_date')
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
                      'end_date' => ''
                                  );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;

       
        $data['po_id']=$po_id;
        //fetching po products
        $po_products=$this->Common_model->get_data('po_products',array('purchase_order_id'=>$po_id),array('product_id'));

        $product_ids = array();
        foreach ($po_products as $row)
         {
            $product_ids[] = $row['product_id'];
        }
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'untag_opportunity/'.icrm_encode($po_id); 
        # Total Records
        $config['total_rows'] = $this->Po_model->distributorOpportunityRows($searchParams,$product_ids,$po_id,2);
        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links(); 
        $current_offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($data['pagination_links']!= '') {
            $data['last']=$this->pagination->cur_page*$config['per_page'];
            if($data['last']>$data['total_rows']){
                $data['last']=$data['total_rows'];
            }
            $data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
         $data['sn'] = $current_offset + 1;
        /* pagination end */
        $data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
        
        $data['category_id'] = $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
        $data['groups'] = array(''=>'Select Group');
        $data['products'] = array(''=>'Select Product');
        $data['source_of_funds'] = $this->Common_model->get_data('source_of_funds',array());
        $data['relationship'] = $this->Common_model->get_data('relationship',array());

        //GETTING OPPORTUNITY STATUS OPTIONS
        $qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 5';
        $data['opportunity_status'] = $this->Common_model->get_query_result($qry);
        //GETTING EDIT OPPORTUNITY STATUS OPTIONS
        $qry = 'SELECT * FROM opportunity_status WHERE status BETWEEN 1 AND 8';
        $data['edit_opportunity_status'] = $this->Common_model->get_query_result($qry);
        
        # Search Results
        $data['searchResults'] = $this->Po_model->distributorOpportunityResults($searchParams,$config['per_page'], $current_offset,$product_ids,$po_id);
        $data['s_cus'] = $this->Po_model->getSearchCustomer(@$searchParams['customer']);
        $data['s_created_user'] = $this->Po_model->getSearchUser(@$searchParams['created_user']);
        $data['opp_status'] = array(''=>'Select Stage') + $this->Common_model->get_dropdown('opportunity_status', 'status', 'name', 'status IN (1,2,3,4,5)');
        $data['product_id'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', [], 'concat(name, "( ", description, ")") name');
        $leads = $this->Po_model->getLeadDetails(3);
        $data['leads'] = array(''=>'Select Lead');
        foreach ($leads as $lead) 
        {
            $data['leads'][$lead['lead_id']] = "Lead ID - ".$lead['lead_id']." (".$lead['CustomerName'].")";
        }
        $data['encoded_id'] = 0;
        $data['pageInfo'] = 1;
        $data['check'] = 1;

        // get source of lead options
        $data['source_of_leads'] = $this->Common_model->get_data('source_of_lead',array('status'=>1));
        // get regions
        $data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
        $data['opportunties_tagged']=$this->Common_model->get_data('purchase_order_opportunity',array('purchase_order_id'=>$po_id,'status'=>1));
        $opportunities=array();
        foreach($data['opportunties_tagged'] as $row)
        {
            $opportunities[$row['opportunity_id']]['opportunity']=$row['opportunity_id'];
        }
         $data['opportunities']=$opportunities;
        $this->load->view('lead/untag_opportunity', $data);
        
    }

    public function add_po_opportunity()
    {
        if($this->input->post('tag_submit'))
        {   
            $po_id  = $this->input->post('po_id');
            $opp_id = $this->input->post('opp_id');
            $tag = $this->input->post('tag');
            $this->db->trans_begin();
            $role_id=$this->session->userdata('role_id');
            if($tag ==1)
            {
                foreach ($opp_id as $key => $value) 
                {
                    $dat = array(
                        'opportunity_id' => $value,
                        'purchase_order_id' => $po_id,
                        'status' =>1,
                        'created_by'=> $this->session->userdata('user_id'),
                        'created_time'=>date('Y-m-d h:i:s')
                        );
                    $this->Common_model->insert_data('purchase_order_opportunity',$dat);
                    $this->Common_model->update_data('opportunity',array('status'=>10),array('opportunity_id'=>$value));
                     $dat1 = array(
                        'status' =>10,
                        'created_by'=> $this->session->userdata('user_id'),
                        'created_time'=>date('Y-m-d h:i:s'),
                        'opportunity_id' => $value
                        );
        
                    $this->Common_model->insert_data('opportunity_status_history',$dat1);

                }
                if($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> There\'s a problem occured while Tagging opportunites to PO Id '.$po_id.' !
                                         </div>');
                   
                     redirect(SITE_URL.'po_list');
                        
                }
                else
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Success!</strong>Opportunities has been added successfully Tagged to PO Id '.$po_id.' !
                                         </div>');
                     redirect(SITE_URL.'view_po/'.icrm_encode($po_id));
                   
                }
            }
            //untagging
            elseif($tag==2)
            {
                 foreach ($opp_id as $key => $value) 
                {
                    $update = array(
                        'status' =>2,
                        'modified_by'=> $this->session->userdata('user_id'),
                        'modified_time'=>date('Y-m-d h:i:s')
                        );
                    $where = array(
                        'opportunity_id' => $value,
                        'purchase_order_id' => $po_id
                        );
                    $this->Common_model->update_data('purchase_order_opportunity',$update,$where);
                    $previous_status=$this->Po_model->get_previous_opportunity($value);
                    $this->Common_model->update_data('opportunity',array('status'=>$previous_status),array('opportunity_id'=>$value));
                     $dat1 = array(
                        'status' =>$previous_status,
                        'created_by'=> $this->session->userdata('user_id'),
                        'created_time'=>date('Y-m-d h:i:s'),
                        'opportunity_id' => $value
                        );
        
                    $this->Common_model->insert_data('opportunity_status_history',$dat1);
               }
                 if($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> There\'s a problem occured while UnTagging opportunites to PO Id '.$po_id.' !
                                         </div>');
                   
                     redirect(SITE_URL.'untag_po_list');
                        
                }
                else
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Success!</strong>Opportunities has been Untagged successfully to PO ID '.$po_id.' !
                                         </div>');
                     redirect(SITE_URL.'view_po/'.icrm_encode($po_id));
                 }
            }
        }
    }
    public  function po_opp_tag_list()
    {
        $defined_roles=array(7,8); // RBH, NSM
        $role=$this->session->userdata('role_id');
        if(in_array($role,$defined_roles))
        {
            # Data Array to carry the require fields to View and Model
            $data['nestedView']['heading'] = "PO OPP Tagged List";
            $data['nestedView']['cur_page'] = 'po_opp_tag_list';
            $data['nestedView']['parent_page'] = 'po_opp_tag_list';

            # Load JS and CSS Files
            $data['nestedView']['js_includes'] = array();
            $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
            $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/manage-opportunity.js"></script>';

            $data['nestedView']['css_includes'] = array();
           
            # Breadcrumbs
            $data['nestedView']['breadCrumbTite'] = 'PO OPP Tagged List';
            $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
            $data['nestedView']['breadCrumbOptions'][] = array('label' => 'PO Opp Tagged List', 'class' => '', 'url' => '');
           

           
            $data['pageDetails'] = 'po_opp_tag_list';

            # Search Functionality
            $psearch = $this->input->post('search', TRUE);
            $start_date = $this->input->post('start_date');
            if($start_date!='')
            {
                $start_date = date('Y-m-d',strtotime($start_date));
            }
            else
            {
                $start_date = '';
            }
            $end_date = $this->input->post('end_date');
            if($end_date!='')
            {
                $end_date = date('Y-m-d',strtotime($end_date));
            }
            else
            {
                $end_date = '';
            }
            if ($psearch != '') {
                $searchParams = array(
                    'billing_id' => $this->input->post('billing_id', TRUE),
                    'purchase_order_id' => $this->input->post('purchase_order_id', TRUE),
                    'opp_id' => $this->input->post('opp_id'),
                    'start_date'=> $start_date,
                    'end_date'=> $end_date,
                    'users_id'=>$this->input->post('users_id')
                );
                $this->session->set_userdata($searchParams);
            } else {

                if ($this->uri->segment(2) != '') {
                    $searchParams = array(
                        'billing_id' => $this->session->userdata('billing_id'),
                         'purchase_order_id' => $this->session->userdata('purchase_order_id'),
                         'opp_id' => $this->session->userdata('opp_id'),
                         'start_date'=>$this->session->userdata('start_date'),
                         'end_date'=>$this->session->userdata('end_date'),
                         'users_id'=>$this->session->userdata('users_id')
                    );
                } else {
                    $searchParams = array(
                        'billing_id' => '',
                        'purchase_order_id'=>'',
                        'opp_id'=>'',
                        'start_date'=>'',
                        'end_date'=>'',
                        'users_id'=>''
                    );
                    $this->session->unset_userdata(array_keys($searchParams));
                }
            }
            $data['searchParams'] = $searchParams;
            /* pagination start */
            $config = get_paginationConfig();
            $config['base_url'] = SITE_URL.'po_opp_tag_list/'; 
            # Total Records
            $config['total_rows'] = $this->Po_model->get_total_po_opp_tag_rows($searchParams);
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
            $data['flag']=1;
            # Search Results
            $data['searchResults'] = $this->Po_model->po_opp_tagged_list($searchParams,$config['per_page'], $current_offset);
            $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name",array('billing_info_id !='=>2));
            $data['users'] = $this->Common_model->get_dropdown("user", "user_id", "first_name",array('role_id'=>5,'status'=>1),'concat("(",employee_id,")-",first_name,last_name)first_name');
            $data['opp_lost_reasons'] = $this->Common_model->get_data('opportunity_lost_reasons',array('status'=>1));
            $qry = 'SELECT * FROM opportunity_status WHERE status IN(6,7)';
            $data['opportunity_status'] = $this->Common_model->get_query_result($qry);
            $data['lost_competitors'] = $this->Common_model->get_data('competitor',array('status'=>1));
            $this->load->view('lead/po_opp_tag_list', $data);
        }
        else
        {
           echo "Sorry!,Your role is unable to access this page";
        } 
    }
    public function po_opp_status()
    {
        $defined_roles=array(7);
        $role=$this->session->userdata('role_id');
        if(in_array($role,$defined_roles))
        {
            $opportunity_id = $this->input->post('opportunity_id');
            $status = $this->input->post('status');
            $opp_lost_reason = $this->input->post('opp_lost_reason');
            $remarks2= $this->input->post('remarks2');
            $po_op_id = $this->input->post('po_op_id');
            $opp_lost_competitor = $this->input->post('opp_lost_competitor');
            $comp_remarks2 = $this->input->post('comp_remarks2');
            $model = $this->input->post('model');
            //updating status in opportunity table
             $update = array(
                            'status' =>$status,
                            'modified_by'=> $this->session->userdata('user_id'),
                            'modified_time'=>date('Y-m-d h:i:s')
                            );
             if($status==7) // If opportunity lost
             {
                $update['remarks2'] = $remarks2;
                $update['oppr_lost_id'] = $opp_lost_reason;
                $update['remarks3'] = $comp_remarks2;
                $update['lost_competitor_id'] = $opp_lost_competitor;
                $update['model'] = $model;

             }
            $where = array(
                'opportunity_id' => $opportunity_id
                );
            $this->db->trans_begin();
            $this->Common_model->update_data('opportunity',$update,$where);
            //updating opportunity status history table
             $dat = array(
                            'status' =>$status,
                            'created_by'=> $this->session->userdata('user_id'),
                            'created_time'=>date('Y-m-d h:i:s'),
                            'opportunity_id' => $opportunity_id
                            );
            
            $this->Common_model->insert_data('opportunity_status_history',$dat);
            if($status==6)
            {
                $pop_status=3;
            }
            elseif($status==7)
            {
                $pop_status=4;
            }
            $update1 = array(
                'status' =>$pop_status,
                'modified_by'=> $this->session->userdata('user_id'),
                'modified_time'=>date('Y-m-d h:i:s')
            );
            $where1= array(
                'po_op_id'=> $po_op_id
                );
            $this->Common_model->update_data('purchase_order_opportunity',$update1,$where1);
              if($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> There\'s a problem occured while Capturing status for opportunity Id '.$opportunity_id.' !
                                         </div>');
                   
                     redirect(SITE_URL.'po_opp_tag_list');
                        
                }
                else
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Success!</strong>Status has been captured successfully for Opp Id '.$opportunity_id.' !
                                         </div>');
                     redirect(SITE_URL.'po_opp_tag_list');
                 }
            }
            else
            {
               echo "Sorry!, Your role is unable to access this page.";
            }

    }
    public function distributor_stock_details()
    {
         # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Distributor Stock Details";
        $data['nestedView']['cur_page'] = 'distributor_stock_details';
        $data['nestedView']['parent_page'] = 'distributor_stock_details';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
       

        $data['nestedView']['css_includes'] = array();
       
        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Stock Details';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Distributor Stock Details', 'class' => '', 'url' => '');
      

       
        $data['pageDetails'] = 'distributor_stock_details';

        # Search Functionality
        $psearch = $this->input->post('search', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'product_id' => $this->input->post('product_id', TRUE),
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'product_id' => $this->session->userdata('product_id')
                   
                );
            } else {
                $searchParams = array(
                    'product_id' => '',
                   
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;
        //print_r($data['searchParams']);exit;

        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'distributor_stock_details/'; 
        # Total Records
        $config['total_rows'] = $this->Po_model->get_product_total_no_of_rows($searchParams);
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
        $data['flag']=1;
        # Search Results
        $data['searchResults'] = $this->Po_model->product_results($searchParams);
        //fetching distirbutor po stock
        $po_stock = $this->Po_model->get_po_stock($searchParams);
         $product_qty=array();
        foreach ($po_stock as $row) {
            $product_qty[$row['product_id']]['po_stock']=$row['quantity'];
        }
        //fetching tagged opportunities with closed won
        $tagged_opp =  $this->Po_model->get_distributor_won_opportunities($searchParams);
        foreach ($tagged_opp  as $row) {
             $product_qty[$row['product_id']]['tagged_stock']=$row['req_qty'];
        }
        //fetching opening stock
        $opening_stock = $this->Po_model->get_dist_opening_stock($searchParams);
        foreach ($opening_stock as $row) {
            $product_qty[$row['product_id']]['opening_stock']=$row['opening_stock'];
        }
        $data['product_qty']=$product_qty;
        $data['product_res'] = $this->Common_model->get_dropdown("product", "product_id", "name",array('status'=>1,'company_id'=>$this->session->userdata('company')),'concat("(",name,")-",description)name');
        $this->load->view('po/distributor_stock_details', $data);
    }
    
    public function download_dist_stock()
    {   

        $searchParams=array(
             'product_id' => $this->input->post('product_id', TRUE)
            );
        $searchResults = $this->Po_model->download_product_results($searchParams);
        $po_stock = $this->Po_model->get_po_stock($searchParams);
        $product_qty=array();
        foreach ($po_stock as $row) {
            $product_qty[$row['product_id']]['po_stock']=$row['quantity'];
        }
        //fetching tagged opportunities with closed won
        $tagged_opp =  $this->Po_model->get_distributor_won_opportunities($searchParams);
        foreach ($tagged_opp  as $row) {
             $product_qty[$row['product_id']]['tagged_stock']=$row['req_qty'];
        }

        $opening_stock = $this->Po_model->get_dist_opening_stock($searchParams);
        foreach ($opening_stock as $row) {
            $product_qty[$row['product_id']]['opening_stock']=$row['opening_stock'];
        }
        $data ='';
        $data = '<table border="1">';
        $data.='<thead>';
        $data.='<tr>';
        $data.='<th>Sno</th>';
        $data.='<th>Name</th>';
        $data.='<th>Description </th>';
        $data.='<th>Stock Available</th>';
        $data.='</tr>';
        $data.='</thead>';
        $data.='<tbody>';
        
        if(count($searchResults)>0)
        {
            $i=1;
            $count=0;
            foreach($searchResults as $row)
            {
                 $quantity=@$product_qty[$row['product_id']]['opening_stock']+@$product_qty[$row['product_id']]['po_stock']-@$product_qty[$row['product_id']]['tagged_stock'];
                if($quantity!=''|| $quantity !=0)
                {
                    $data.='<tr>';
                    $data.='<td>'.$i++.'</td>';
                    $data.='<td>'.@$row['name'].'</td>';
                    $data.='<td>'.@$row['description'].'</td>';
                    $data.='<td>'.@$quantity.'</td>';
                    $data.='</tr>';
                    $count++;
                }
            }
            if($count==0)
            {
                $data.='<tr><td colspan="15" align="center">No Files Found </td></tr>';
            }
        }
        else
        {
            $data.='<tr><td colspan="15" align="center">No Files Found </td></tr>';
        }
        $data.='</tbody>';
        $data.='</table>';
        $time = date("Ymdhis");
        $xlFile='Distributor Stock Details_'.$time.'.xls'; 
        header("Content-type: application/x-msdownload"); 
        # replace excelfile.xls with whatever you want the filename to default to
        header("Content-Disposition: attachment; filename=".$xlFile."");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $data;
    }


    public function print_dist_stock()
    {   

        $searchParams=array(
             'product_id' => $this->input->post('product_id', TRUE)
            );
        $data['searchResults'] = $this->Po_model->download_product_results($searchParams);
        $po_stock = $this->Po_model->get_po_stock($searchParams);
        $product_qty=array();
        foreach ($po_stock as $row) {
            $product_qty[$row['product_id']]['po_stock']=$row['quantity'];
        }
        //fetching tagged opportunities with closed won
        $tagged_opp =  $this->Po_model->get_distributor_won_opportunities($searchParams);
        foreach ($tagged_opp  as $row) {
             $product_qty[$row['product_id']]['tagged_stock']=$row['req_qty'];
        }
        $opening_stock = $this->Po_model->get_dist_opening_stock($searchParams);
        foreach ($opening_stock as $row) {
            $product_qty[$row['product_id']]['opening_stock']=$row['opening_stock'];
        }
        $data['product_qty']=$product_qty;
        $this->load->view('po/print_dist_stock_details', $data);
    }

    // Mahesh: 08-10-2017 , Get Logged in user products by Segment
    public function getUserProductsBySegment()
    {
        $segment_id = $this->input->post('segment_id');
        $get_details = get_user_dist_country();
        if($get_details[0]==1)
        {
          $products = $this->Po_model->getUserProductsBySegment($segment_id);
        }
        else
        {
            $products = $this->Po_model->getUserProductsBySegmentCurrency($get_details[2],$segment_id);
            //checking the products exists in other segment
            $check_products = $this->Po_model->getUserProductsBySegmentCheckCurrency($get_details[2]);
            if(count($check_products)==0)
            {
                $products = $this->Po_model->getUserProductsBySegment($segment_id);
            }
        }
        $str = '<option value="">Select Product</option>';
        foreach ($products as $row) {
            $str .= '<option value="'.$row['product_id'].'" data-currency ="'.$get_details[2].'" data-unitPrice="'.$row['unit_price'].'">'.$row['product'].'</option>';
        }
        echo $str;
    }

    public function po_revision($encoded_id) {

        $purchase_order_id = @icrm_decode($encoded_id);

        if ($purchase_order_id=='') {
            redirect(SITE_URL . 'po_list');
        }
         $po_results = $this->Common_model->get_data_row('purchase_order',array('purchase_order_id'=>$purchase_order_id));
        
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "PO Revision";
        $data['nestedView']['cur_page'] = 'po_list';
        $data['nestedView']['parent_page'] = 'po_list';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/purchase_order.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'PO Revision';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'PO List', 'class' => '', 'url' => SITE_URL . 'po_list');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Purchase Order ID - ' . $po_results['po_number'], 'class' => 'active', 'url' => '');


        /* Mahesh Phase2 Capture additional terms in quote start */
        $data['po_results'] = $po_results;
        $product_results = $this->Po_model->get_po_results($purchase_order_id);
        $data['product_results']=$product_results;
        $currency_id = $product_results[0]['currency_id'];
        $cur_val= $this->Common_model->get_value('currency',array('currency_id'=>$currency_id),'code');
        $data['discount_types'] = get_advance_types($cur_val);
        $data['cur_val']=$cur_val;
        /* Mahesh Phase2 Capture additional terms in quote END */        
        $this->load->view('po/po_revision', $data);
        
    }

    public function submitPoRevision()
    {
        if($this->input->post('revisePo')!='')
        {
            $purchase_order_id = icrm_decode($this->input->post('encoded_id'));
            if($purchase_order_id=='')
            {
                redirect(SITE_URL.'po_list'); exit;
            }
            $balance_payment_days = ($this->input->post('balance_payment_days')>0)?$this->input->post('balance_payment_days'):0;
            $dat = array(
                'user_id'          => $this->session->userdata('user_id'),
                'warranty'         => $this->input->post('warranty'),
                'advance_type'     => $this->input->post('advance_type'),
                'advance'          => $this->input->post('advance'),
                'balance_payment_days' => $balance_payment_days,
                'status'           => 1,
                'modified_by'      => $this->session->userdata('user_id'),
                'modified_time'     => date('Y-m-d H:i:s'),
                );
            $po_where = array(
                'purchase_order_id'=> $purchase_order_id
                );
            $this->db->trans_begin();
            // Update Purchase Order
            $this->Common_model->update_data('purchase_order',$dat,$po_where);
            $rev_data = array(
                'purchase_order_id'=> $purchase_order_id,
                'warranty'         => $this->input->post('warranty'),
                'advance_type'     => $this->input->post('advance_type'),
                'advance'          => $this->input->post('advance'),
                'balance_payment_days' => $this->input->post('balance_payment_days'),
                'status'           => 1,
                'created_by'       => $this->session->userdata('user_id'),
                'created_time'     => date('Y-m-d H:i:s')
                );
            // Insert PO Status History
            addPoStatusHistory($purchase_order_id,1);
            // Update other PO revisions status as rejected
            $pr_data = array('status'=>2,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d H:i:s'));
            $pr_where = array('purchase_order_id'=>$purchase_order_id,'status'=>1);
            $this->Common_model->update_data('po_revision',$pr_data,$pr_where);
            // Insert PO Revision
            $po_revision_id = $this->Common_model->insert_data('po_revision',$rev_data);

            $product_id = $this->input->post('product_id');
            $qty = $this->input->post('qty');
            $discount_type = $this->input->post('discount_type');
            $discount=$this->input->post('discount');
            $warranty = $this->input->post('warranty');
            $advance = $this->input->post('advance');
            $advance_type = $this->input->post('advance_type');
            $approval_req_products = array();
            foreach($product_id as $key =>$value)
            {
                $app_data=array(
                    'po_revision_id'    => $po_revision_id,
                    'product_id'        => $value,
                    'discount_type'     => $discount_type[$value],
                    'discount'          => $discount[$value],
                    'created_by'        => $this->session->userdata('user_id'),
                    'created_time'      => date('Y-m-d H:i:s'),
                    'status'            => 1
                    );
                // Margin Approval Process start here
                $app_data['approval_at'] = 7;
                $app_data['close_at'] = 9;
                $disc_type = $discount_type[$value];
                $disc_val = $discount[$value];
                $row = getPoProductPriceDetails($purchase_order_id,$value);
                $order_value = $row['mrp'];
                if($disc_type!=''&&$disc_val!='')
                $order_value = ($disc_type==1)?($order_value*(1-$disc_val/100)):($order_value-$disc_val);
                $nsp = round($order_value/(1+$row['gst']/100)/(1+$row['freight_insurance']/100));
                $discount_percenrage = round((($row['mrp'] - $order_value )/$row['mrp'])*100,2);
                $data = array();
                $data['order_value'] = $order_value;
                $data['basic_price'] = $row['dp'];
                $data['net_selling_price'] = $nsp;
                //$data['basic_price'] = round($row['dp']*90/100);
                $data['total_warranty_in_years'] = ($warranty>0)?round(($warranty/12),2):0;
                
                if($advance!='')
                {
                    if ($advance_type==2) 
                        $advance = round(($advance/$row['mrp'])*100,2);
                }
                else $advance = 0;
                $data['advance'] = $advance;
                $data['balance_payment_days'] = ($balance_payment_days!='')?$balance_payment_days:0;
                $data['dealer_commission'] = 0;
                
                
                $data['cost_of_free_supply'] = 0;
                $data['exclude_extra_warranty_in_nm'] = 1;
                $m_data = marginAnalysis($data);
                $dp = $row['unit_dp'];
                $variance_percentage = round(((($order_value/$row['qty'])-$dp)/$dp)*100,2);
                
                // Get margin bands
                $mbands = $this->Common_model->get_data('quote_approval_config',array('status'=>1,'role_id >='=>7));
                foreach ($mbands as $mb_row) {
                    $mb_row['gross_margin_percentage'] = $variance_percentage;
                    $mb_row['net_margin_percentage'] = $m_data['net_margin_percentage'];
                    if(checkMarginBand($mb_row)||$mb_row['role_id']==9)
                    {
                        switch ($mb_row['role_id']) {
                            case 7: // RBH
                                $app_data['close_at'] = $app_data['approval_at'] = 7;
                            break;
                            case 8: // NSM
                                $app_data['close_at'] = 8 ; $app_data['approval_at'] = 7;
                            break;
                            case 9: // NSM
                                $app_data['close_at'] = 9 ; $app_data['approval_at'] = 7;
                            break;
                        }
                        break;
                    }
                    
                }
                $check_roles = array(7,8,9);
                $dealer_region = getRegionforUser($this->session->userdata('locationString'));
                if(in_array($app_data['close_at'],$check_roles))
                {
                    if(!checkRbhExistForRegion($dealer_region)) // No RBH Exist
                    {
                        $app_data['approval_at'] = 8;
                        if($app_data['close_at']==7)
                        {
                            $app_data['close_at'] = 8; // move to NSM
                        }   
                    }
                }
                
                // Margin Approval Process ends here
                // Insert Po Product Approval
                $approval_id = $this->Common_model->insert_data('po_product_approval',$app_data);
                if(@$app_data['approval_at']!='')
                {
                        $approval_req_products[$value] = array('product_id'=>$value,'approval_at'=>$app_data['approval_at'],'approval_id'=>$approval_id);
                }
            }
          if($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while adding New PO !
                                     </div>');
                redirect(SITE_URL.'po_list');
                
                    
            }
            else
            {
                $this->db->trans_commit();
                // Email alert for quote approval Start
                if(count($approval_req_products)>0)
                {
                            $po_approvers = array();
                    foreach($approval_req_products as $orow) {
                        if(!array_key_exists($orow['approval_at'], $po_approvers))
                            $po_approvers[$orow['approval_at']] = getPoProductApproverEmailsByRole($orow['approval_at'],$dealer_region,$orow['product_id']);
                        if(count($po_approvers[$orow['approval_at']])>0)
                        {
                            foreach ($po_approvers[$orow['approval_at']] as $urow) {
                                $to = mail_to($urow['email_id']);
                                $cc= "CRM@skanray.com";
                                $encoded_id = icrm_encode($orow['approval_id'].'_'.$urow['user_id']);
                                $email_data = getPoApprovalEmailData($po_revision_id,$purchase_order_id,$orow['approval_at']);
                                $subject = $email_data['subject'];
                                $message = str_replace('{ENCODED_ID}', $encoded_id, $email_data['message']);
                                send_email($to,$subject,$message,$cc);
                                
                            }
                        }
                        
                    }

                }
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong>Purchase Order has been revised successfully !
                                     </div>');
                redirect(SITE_URL.'po_list');
            }
        }
    }
}