<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class MarginAnalysis extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdminModel");
        $this->load->model("MarginAnalysis_model");
        $this->load->model("common_model");
        $this->load->library('Pdf');
        $this->load->library('numbertowords');
        $this->load->library('user_agent');
    }

    function margin_analysis_approval_list(){

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Quote Approval";
        $data['nestedView']['cur_page'] = 'marginAnalysisList';
        $data['nestedView']['parent_page'] = 'marginAnalysisList';

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
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Quote Approval', 'class' => '', 'url' => '');
        //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

       
        $data['pageDetails'] = 'marginAnalysisList';
        //$data['lead_id'] = $lead_id;

        # Search Functionality
        $psearch = $this->input->post('searchApprveQuote', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'opportunity_details' => $this->input->post('opportunity_details', TRUE),
                'quote_id' => $this->input->post('quote_id', TRUE),
                'ma_region' => $this->input->post('ma_region', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'opportunity_details' => $this->session->userdata('opportunity_details'),
                     'quote_id' => $this->session->userdata('quote_id'),
                     'ma_region' => $this->session->userdata('ma_region')
                );
            } else {
                $searchParams = array(
                    'opportunity_details' => '',
                    'quote_id'=>'',
                    'ma_region'=>''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;


        $config = get_paginationConfig();

        $current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();

        $quoteSearch = array();
        $quoteSearch = $this->MarginAnalysis_model->getOpportunitiesForApproval($current_offset, $config['per_page'], @$searchParams);
        /*print_r($quoteSearch);
        exit;*/
        # Loading the data array to send to View
        $data['quoteSearch'] = @$quoteSearch['resArray'];
        $data['regions']    = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
        
        $data['count'] = @$quoteSearch['count'];


        # Default Records Per Page - always 10
        /* pagination start */
        $config['base_url'] = SITE_URL . 'margin_analysis_list/';
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
        $data['login_user_role_id'] = $this->session->userdata('role_id');
        $this->load->view('margin_analysis/margin_analysis_list', $data);
    }

    public function submitMarginAnalysisApproval()
    {
        /*print_r($_POST);
        exit;*/
        $action = $this->input->post('action');
        if($action!='')
        {
            $remarks = $this->input->post('remarks');
            $quote_id = $this->input->post('quote_id');
            $quote_revision_id = $this->input->post('quote_revision_id');
            $margin_approval_id = $this->input->post('margin_approval_id');
            $lead_id = $this->input->post('lead_id');
            $lead_owner = $this->input->post('lead_owner');
            $opportunity_id = $this->input->post('opportunity_id');
            $approval_type = $this->input->post('approval_type');
            if($approval_type==1)
            {
                $role_id = $this->session->userdata('role_id');
                $user_id = $this->session->userdata('user_id');
            }
            else if($approval_type==2)
            {
                $user_id = $this->input->post('user_id');
                $role_id = $this->Common_model->get_value('user',array('user_id'=>$user_id));
                $temp_sess_data = array('user_id'=>$user_id,'role_id'=>$role_id);
                $this->session->set_userdata($temp_sess_data);
            }
            $data1 = array(
                                'margin_approval_id'    =>  $margin_approval_id,
                                'approved_by'           =>  $role_id,
                                'remarks'               =>  $remarks,
                                'created_by'            =>  $user_id,
                                'created_time      '    =>  date('Y-m-d H:i:s'),
                                'status'                =>  $action
                            );
            // Transaction Begins here
            $this->db->trans_begin();
            // Insert approval action history
            $this->Common_model->insert_data('quote_op_margin_approval_history',$data1);
            
            $ma_row = $this->Common_model->get_data_row('quote_op_margin_approval',array('margin_approval_id'=>$margin_approval_id));
            switch ($action) {
                case 1: // If approved
                    // Check if close at, logged in user role is same
                    if($role_id==$ma_row['close_at'])
                    {
                        // Check if all opportunities are approved.
                        // Get opportunities count By quote_revision_id
                        $op_count = getMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id);
                        $approved_op_count = getApprovedMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id);
                        $completed_op_count = getCompletedMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id);
                        // approve margin analysis
                        $ma_data = array('status'=>2,'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
                        $ma_where = array('margin_approval_id'=>$margin_approval_id);
                        $this->Common_model->update_data('quote_op_margin_approval',$ma_data,$ma_where);
                        /*echo $op_count.'-->'.$approved_op_count;
                        exit;*/
                        $approved_op_count++;
                        $completed_op_count++;
                        if($op_count==$completed_op_count)
                        {
                            if($op_count==$approved_op_count)
                            {
                                // Update quote revision status
                                updateOtherQuoteRevisionStatus($quote_id,1,4); // Updating revision 1 to 4
                                updateOtherQuoteRevisionStatus($quote_id, 3, 1); // Updating revision 3 to 1

                                $quoteStatus = 2;
                                $dataArr = array('status' => $quoteStatus,
                                    'modified_by' => $this->session->userdata('user_id'),
                                    'modified_time' => date('Y-m-d H:i:s'));
                                $this->Common_model->update_data('quote', $dataArr, array('quote_id' => $quote_id));
                                addQuoteStatusHistory($quote_id, $quoteStatus);

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
                                if($quote_revision['dealer_id']!='')
                                {
                                    $qdata['dealer_id'] = $quote_revision['dealer_id'];
                                }
                                else
                                {
                                    $qdata['dealer_id'] = NULL;
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

                                // Email Notification to lead owner
                                $lead_user = $this->Common_model->get_data_row('user',array('user_id'=>$lead_owner));
                                $quote_reference_id = getQuoteReferenceID($lead_id,$quote_id);
                                $opportunity_details = getOpportunityDetails($opportunity_id);
                                $cc_users = getMarginAnalysisCcUsersList($role_id,$margin_approval_id);
                                $cc = array();
                                if($cc_users)
                                {
                                    foreach ($cc_users as $cc_row) {
                                        $cc[] = $cc_row['email_id'];
                                    }
                                    $cc = array_unique($cc);
                                }
                                $cc = (count($cc)>0)?implode(',', $cc):'';
                                $to = @$lead_user['email_id'];
                                $subject = 'Notification: Opportunity: '.$opportunity_id.' has been approved with Quote Reference ID: '.$quote_reference_id;
                                $message = '<p>Hi '.$lead_user['first_name'].' '.$lead_user['last_name'].',</p>';
                                $message .= '<p>Opportunity: '.$opportunity_id.' has been successfully pass through Margin Analysis </p>';
                                $message .= '<p><strong>Quote Reference ID:</strong> '.$quote_reference_id.' </p>';
                                $message .= '<p><strong>Opportunity Details:</strong> '.$opportunity_details.' </p>';

                                $message .= '<p>Regards,</p>';
                                $message .= '<p>iCRM,<br>SkanRay</p>';
                                /*echo 'To:'.$to.'<br>'.'CC:'.$cc.'<br>Subject:'.$subject.'<br>Message:'.$message;
                                exit;*/
                                //send_email($to,$subject,$message,$cc);
                            }
                            else
                            {
                                // Update quote revision status as rejected
                                updateOtherQuoteRevisionStatus($quote_id, 3, 2); // Updating revision 3 to 2

                                $quoteStatus = 2;
                                $dataArr = array('status' => $quoteStatus,
                                    'modified_by' => $this->session->userdata('user_id'),
                                    'modified_time' => date('Y-m-d H:i:s'));
                                $this->Common_model->update_data('quote', $dataArr, array('quote_id' => $quote_id));
                                addQuoteStatusHistory($quote_id, $quoteStatus);
                            }
                            

                        }
                        
                        
                       
                    }
                    else
                    {
                        // Forward to next level
                        $next_approval_role = getNextApprovalRole($role_id);
                        $data2 = array(
                                        'approval_at'   => $next_approval_role
                                    );
                        $where2 = array( 'margin_approval_id' => $margin_approval_id);
                        $this->Common_model->update_data('quote_op_margin_approval',$data2,$where2);

                        // Approval Email Alert :  START
                        $quote_approvers = getOppApproverEmailsByRole($next_approval_role,$opportunity_id);
                        if(count($quote_approvers)>0)
                        {
                            foreach ($quote_approvers as $urow) {
                                $to = mail_to($urow['email_id']);
                                $encoded_id = icrm_encode($margin_approval_id.'_'.$urow['user_id']);
                                $email_data = getQuoteApprovalEmailData($quote_revision_id,$quote_id,$next_approval_role);
                                $subject = $email_data['subject'];
                                //$message = $email_data['message'];
                                $message = str_replace('{ENCODED_ID}', $encoded_id, $email_data['message']);
                                send_email($to,$subject,$message);
                                //echo $to.'<br>'.$subject.'<br>'.$message.'<br>'; 
                            }
                        }
                        // Approval Email Alert :  END

                    }
                $success_msg = 'Opportunity : '.$opportunity_id.' quote revision has been approved successfully';
                break;
                
               case 2: // If Rejected
               $success_msg = 'Opportunity : '.$opportunity_id.' quote revision has been rejected successfully';

                    // Check if all opportunities are approved.
                        // Get opportunities count By quote_revision_id
                    $op_count = getMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id);
                    $completed_op_count = getCompletedMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id);
                    $completed_op_count++;
                    if($op_count==$completed_op_count) // If all opportunities approval actions completed: Reject the entire Quote
                    {
                        $quoteCurrentStatus = getCurrentQuoteStatus($quote_id);
                        $where = array('quote_id'=>$quote_id);
                        if($quoteCurrentStatus == 6)
                        {
                            $dataArr = array('status' => 2); 
                            $this->Common_model->update_data('quote',$dataArr, $where);
                            addQuoteStatusHistory($quote_id, 2);
                            updateOtherQuoteRevisionStatus($quote_id, 3, 2); // Updating revision 3 to 2
                        }
                        else
                        {
                            $dataArr = array('status' => 10); 
                            $this->Common_model->update_data('quote',$dataArr, $where);
                            addQuoteStatusHistory($quote_id, 10);
                        }
                    }
                    // reject margin analysis
                    $ma_data = array('status'=>3,'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
                    $ma_where = array('margin_approval_id'=>$margin_approval_id);
                    $this->Common_model->update_data('quote_op_margin_approval',$ma_data,$ma_where);
                    // Email Notification to lead owner
                        $lead_user = $this->Common_model->get_data_row('user',array('user_id'=>$lead_owner));
                        $quote_reference_id = getQuoteReferenceID($lead_id,$quote_id);
                        $opportunity_details = getOpportunityDetails($opportunity_id);
                        $cc_users = getMarginAnalysisCcUsersList($role_id,$margin_approval_id);
                        $cc = array();
                        if($cc_users)
                        {
                            foreach ($cc_users as $cc_row) {
                                $cc[] = $cc_row['email_id'];
                            }
                            $cc = array_unique($cc);
                        }
                        $cc = (count($cc)>0)?implode(',', $cc):'';
                        $to = @$lead_user['email_id'];
                        $subject = 'Notification: Opportunity - '.$opportunity_id.' has been rejected for Quote Reference ID: '.$quote_reference_id;
                        $message = '<p>Hi '.$lead_user['first_name'].' '.$lead_user['last_name'].',</p>';
                        $message .= '<p>Opportunity: '.$opportunity_id.' has been failed to pass through Margin Analysis </p>';
                        $message .= '<p><strong>Quote Reference ID:</strong> '.$quote_reference_id.' </p>';
                        $message .= '<p><strong>Opportunity Details:</strong> '.$opportunity_details.' </p>';
                        $message .= '<p>Regards,</p>';
                        $message .= '<p>iCRM,<br>SkanRay</p>';
                        /*echo 'To:'.$to.'<br>'.'CC:'.$cc.'<br>Subject:'.$subject.'<br>Message:'.$message;
                        exit;*/
                        //send_email($to,$subject,$message,$cc);
                break;
            }

            if($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while adding a revision to Quote!
                                     </div>');
                redirect(SITE_URL.'margin_analysis_list');
                    
            }
            else
            {
                $this->db->trans_commit();
                //exit('testing');
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> '.$success_msg.'.
                                     </div>');
                redirect(SITE_URL.'margin_analysis_list');
            }
        }
        

    }

    public function marginAnalysisConfig()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Edit Margin Bands";
        $data['nestedView']['cur_page'] = 'marginAnalysisConfig';
        $data['nestedView']['parent_page'] = 'marginAnalysisConfig';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/quote.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Edit Margin Approval Bands';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Margin Bands', 'class' => '', 'url' => SITE_URL . 'margin_bands');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Margin Bands', 'class' => '', 'url' => '');

        
        $data['marginApproval'] = $this->Common_model->get_data('quote_approval_config',array('status'=>1));
        
        $this->load->view('margin_analysis/marginAnalysisConfigView', $data);
    }

    public function submitMarginAnalysisConfig()
    {

        if($this->input->post('marginAnalysisApp')!='')
        {
            $quote_approval_ids = $this->input->post('quote_approval_ids');
            //print_r($_POST);
            if($quote_approval_ids)
            {
                $gm_lower_limit = $this->input->post('gm_lower_limit');
                $gm_lower_check = $this->input->post('gm_lower_check');
                $gm_upper_limit = $this->input->post('gm_upper_limit');
                $gm_upper_check = $this->input->post('gm_upper_check');

                $nm_lower_limit = $this->input->post('nm_lower_limit');
                $nm_lower_check = $this->input->post('nm_lower_check');
                $nm_upper_limit = $this->input->post('nm_upper_limit');
                $nm_upper_check = $this->input->post('nm_upper_check');

                $cost_of_maintaining_warranty = $this->input->post('cost_of_maintaining_warranty');
                $cost_of_capital = $this->input->post('cost_of_capital');
                $enable_warranty = ($this->input->post('enable_warranty')==1)?1:2;
                set_preference('cost_of_maintaining_warranty',$cost_of_maintaining_warranty,'margin_settings');
                set_preference('cost_of_capital',$cost_of_capital,'margin_settings');
                set_preference('enable_warranty',$enable_warranty,'dealer_settings');

                foreach ($quote_approval_ids as $ma_id) {
                    $lcheck = (@$gm_lower_check[$ma_id]==1)?1:2;
                    $ucheck = (@$gm_upper_check[$ma_id]==1)?1:2;

                    $nm_lcheck = (@$nm_lower_check[$ma_id]==1)?1:2;
                    $nm_ucheck = (@$nm_upper_check[$ma_id]==1)?1:2;
                    $data = array(
                                    'gm_lower_check' =>  $lcheck,
                                    'gm_upper_check' =>  $ucheck,
                                    'nm_lower_check' =>  $nm_lcheck,
                                    'nm_upper_check' =>  $nm_ucheck
                                );
                    $data['gm_lower_limit'] = ($gm_lower_limit[$ma_id]!='')?$gm_lower_limit[$ma_id]:NULL;
                    $data['gm_upper_limit'] = ($gm_upper_limit[$ma_id]!='')?$gm_upper_limit[$ma_id]:NULL;
                    $data['nm_lower_limit'] = ($nm_lower_limit[$ma_id]!='')?$nm_lower_limit[$ma_id]:NULL;
                    $data['nm_upper_limit'] = ($nm_upper_limit[$ma_id]!='')?$nm_upper_limit[$ma_id]:NULL;
                    $where = array(
                                    'quote_approval_id'    =>  $ma_id
                                );
                    $this->Common_model->update_data('quote_approval_config',$data,$where);
                }

                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> Changes has been updated successfully.
                                     </div>');
                redirect(SITE_URL.'margin_bands');
            }
        }
    }

    public function margin_bands()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Margin Bands";
        $data['nestedView']['cur_page'] = 'margin_bands';
        $data['nestedView']['parent_page'] = 'margin_bands';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Margin Approval Bands';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Margin Bands', 'class' => '', 'url' => '');

        
        $data['marginApproval'] = $this->Common_model->get_data('quote_approval_config',array('status'=>1));
        
        $this->load->view('margin_analysis/margin_bands', $data);
    }

    function quote_tracking(){

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Quote Tracking";
        $data['nestedView']['cur_page'] = 'quoteTracking';
        $data['nestedView']['parent_page'] = 'quoteTracking';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Quote Tracking';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Quote Tracking', 'class' => '', 'url' => '');
        //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

       
        $data['pageDetails'] = 'track_quotes';
        //$data['lead_id'] = $lead_id;

        # Search Functionality
        $psearch = $this->input->post('searchApprveQuote', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'opportunity_details' => $this->input->post('opportunity_details', TRUE),
                'customer_name' => $this->input->post('customer_name', TRUE),
                'quote_id' => $this->input->post('quote_id', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'opportunity_details' => $this->session->userdata('opportunity_details'),
                    'customer_name' => $this->session->userdata('customer_name'),
                    'quote_id' => $this->session->userdata('quote_id')
                );
            } else {
                $searchParams = array(
                    'opportunity_details' => '',
                    'customer_name' => '',
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
        $quoteResults = $this->MarginAnalysis_model->getQuotesList($current_offset, $config['per_page'],$searchParams);
        if($quoteResults)
        {

            foreach ($quoteResults as $row) {
                $quoteSearch[$row['quote_revision_id']][] = $row;
            }
        }
        # Loading the data array to send to View
        $data['quoteSearch'] = @$quoteSearch;


        # Default Records Per Page - always 10
        /* pagination start */
        $config['base_url'] = SITE_URL . 'track_quotes/';
        # Total Records
        $config['total_rows'] = $this->MarginAnalysis_model->getTotalQuoteRows($searchParams);

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
        $data['login_user_role_id'] = $this->session->userdata('role_id');
        $this->load->view('lead/quote_tracking', $data);
    }

    function po_approval_list(){

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Purchase Order Approval";
        $data['nestedView']['cur_page'] = 'po_approval_list';
        $data['nestedView']['parent_page'] = 'po_approval_list';

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
        $data['nestedView']['breadCrumbTite'] = 'Purchase Order Approval';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Purchase Order Approval', 'class' => '', 'url' => '');
        //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

       
        $data['pageDetails'] = 'po_approval_list';
        //$data['lead_id'] = $lead_id;

        # Search Functionality
        $psearch = $this->input->post('searchApprveQuote', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'product_details' => $this->input->post('product_details', TRUE),
                'purchase_order_id' => $this->input->post('purchase_order_id', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'product_details' => $this->session->userdata('product_details'),
                     'purchase_order_id' => $this->session->userdata('purchase_order_id')
                );
            } else {
                $searchParams = array(
                    'product_details' => '',
                    'purchase_order_id'=>''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;


        $config = get_paginationConfig();

        $current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();

        $quoteSearch = array();
        $quoteSearch = $this->MarginAnalysis_model->getPendingPoApprovalList($current_offset, $config['per_page'], @$searchParams);
        /*echo '<pre>';print_r($quoteSearch);echo '</pre>';
        exit;*/
        # Loading the data array to send to View
        $data['quoteSearch'] = $quoteSearch;
        
        $data['count'] = $this->MarginAnalysis_model->getPendingPoApprovalTotalRows(@$searchParams);

        //echo $data['count']; exit;
        # Default Records Per Page - always 10
        /* pagination start */
        $config['base_url'] = SITE_URL . 'po_approval_list/';
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
        $data['login_user_role_id'] = $this->session->userdata('role_id');
        $this->load->view('margin_analysis/po_approval_list', $data);
    }

    public function submitPoApproval()
    {
        /*print_r($_POST);
        exit;*/
        $action = $this->input->post('action');
        if($action!='')
        {
            $remarks = $this->input->post('remarks');
            $purchase_order_id = $this->input->post('purchase_order_id');
            $po_revision_id = $this->input->post('po_revision_id');
            $approval_id = $this->input->post('approval_id');
            $product_id = $this->input->post('product_id');
            $distributor_id = $this->input->post('distributor_id');
            $data1 = array(
                                'approval_id'           =>  $approval_id,
                                'approved_by'           =>  $this->session->userdata('role_id'),
                                'remarks'               =>  $remarks,
                                'created_by'            =>  $this->session->userdata('user_id'),
                                'created_time      '    =>  date('Y-m-d H:i:s'),
                                'status'                =>  $action
                            );
            // Transaction Begins here
            $this->db->trans_begin();
            // Insert approval action history
            $this->Common_model->insert_data('po_product_approval_history',$data1);
            $role_id = $this->session->userdata('role_id');
            $user_id = $this->session->userdata('user_id');
            $ma_row = $this->Common_model->get_data_row('po_product_approval',array('approval_id'=>$approval_id));
            //echo $action; print_r($ma_row); exit;
            switch ($action) {
                case 1: // If approved
                    // Check if close at, logged in user role is same
                    if($role_id==$ma_row['close_at'])
                    {
                        // Check if all products are approved.
                        // Get products count By po_revision_id
                        $product_count = getPoProductCountByPoRevision($po_revision_id);
                        $approved_product_count = getApprovedPoProductCountByPoRevision($po_revision_id);
                        $completed_product_count = getCompletedPoProductCountByPoRevision($po_revision_id);
                        // approve margin analysis
                        $ma_data = array('status'=>2,'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
                        $ma_where = array('approval_id'=>$approval_id);
                        $this->Common_model->update_data('po_product_approval',$ma_data,$ma_where);
                        /*echo $op_count.'-->'.$approved_op_count;
                        exit;*/
                        $completed_product_count++;
                        $approved_product_count++;
                        if($product_count==$completed_product_count)
                        {

                            $poStatus = ($product_count==$approved_product_count)?2:3;
                            $dataArr = array('status' => $poStatus,
                                    'modified_by' => $this->session->userdata('user_id'),
                                    'modified_time' => date('Y-m-d H:i:s'));
                                $this->Common_model->update_data('purchase_order', $dataArr, array('purchase_order_id' => $purchase_order_id));
                                addPoStatusHistory($purchase_order_id, $poStatus);

                                // Update quote additional terms
                               $po_revision = $this->Common_model->get_data_row('po_revision', array('po_revision_id'=>$po_revision_id));
                                 /*$qdata = array( 'warranty'=>$po_revision['warranty'],
                                                'advance_type'  =>  $po_revision['advance_type'],
                                                'advance'  =>  $po_revision['advance'],
                                                'balance_payment_days'  =>  $po_revision['balance_payment_days']
                                                );
                                
                                $this->Common_model->update_data('purchase_order', $dataArr, array('purchase_order_id' => $purchase_order_id));*/
                            // Get distributor info
                            $distributor = $this->MarginAnalysis_model->getPoDistributorDetails($purchase_order_id);
                            /*echo $this->db->last_query();
                            print_r($distributor); exit;*/
                            // Create Auto CNote
                            $cn_data = array(
                                            'cnote_type'    =>  2,
                                            'purchase_order_no' =>  $purchase_order_id,
                                            'date_of_purchase_order' => format_date($distributor['created_time'],'Y-m-d'),
                                            'institution_code'  =>  $distributor['employee_id'],
                                            'billing_to_party'  =>  $distributor['distributor_name'],
                                            'status'            => 3,
                                            'created_by'        => $distributor['user_id'],
                                            'created_time'      => date('Y-m-d H:i:s')
                                        );
                            $contract_note_id = $this->Common_model->insert_data('contract_note',$cn_data);
                            $cnr_data = array('contract_note_id'=>$contract_note_id,'po_revision_id'=>$po_revision_id);
                            // Insert CNote Po Revision
                            $this->Common_model->insert_data('contract_note_po_revision',$cnr_data);

                            
                            
                        }

                        // Email Notification to lead owner
                        $cc_users = getPoApprovalCcUsersList($role_id,$approval_id);
                        $cc = array();
                        if($cc_users)
                        {
                            foreach ($cc_users as $cc_row) {
                                $cc[] = $cc_row['email_id'];
                            }
                            $cc = array_unique($cc);
                        }
                        $cc = (count($cc)>0)?implode(',', $cc):'';
                        $to = @$distributor['email_id'];
                        // Get Product info
                        $product = $this->Common_model->get_data_row('product',array('product_id'=>$product_id));
                        $subject = 'Notification: Product : '.$product['name'].' - '.$product['description'].' has been approved with Purchase Order ID: '.$purchase_order_id;
                        $message = '<p>Hi '.$distributor['first_name'].' '.$distributor['last_name'].',</p>';
                        $message .= '<p>Product: '.$opportunity_id.' has been successfully pass through Margin Analysis </p>';
                        $message .= '<p><strong>Purchase Order ID:</strong> '.$purchase_order.' </p>';

                        $message .= '<p>Regards,</p>';
                        $message .= '<p>iCRM,<br>SkanRay</p>';
                        /*echo 'To:'.$to.'<br>'.'CC:'.$cc.'<br>Subject:'.$subject.'<br>Message:'.$message;
                        exit;*/
                        //send_email($to,$subject,$message,$cc);
                    
                       
                    }
                    else
                    {
                        // Forward to next level
                        $next_approval_role = getNextApprovalRole($role_id);
                        $data2 = array(
                                        'approval_at'   => $next_approval_role
                                    );
                        $where2 = array( 'approval_id' => $approval_id);
                        $this->Common_model->update_data('po_product_approval',$data2,$where2);
                        // Forward to next level
                        $next_approval_role = getNextApprovalRole($role_id);
                        $data2 = array(
                                        'approval_at'   => $next_approval_role
                                    );
                        $where2 = array( 'approval_id' => $approval_id);
                        $this->Common_model->update_data('po_product_approval',$data2,$where2);

                        // Approval Email to Next Level  START
                        $l = getUserLocations($distributor_id);
                        $locationString = getQueryArray($l);
                        $dealer_region = getRegionforUser($locationString);
                        $approval_at = $next_approval_role;
                        $po_approvers[$approval_at] = getPoProductApproverEmailsByRole($approval_at,$dealer_region,$product_id);
                        if(count($po_approvers[$approval_at])>0)
                        {
                            foreach ($po_approvers[$approval_at] as $urow) {
                                $to = mail_to($urow['email_id']);
                                $encoded_id = icrm_encode($approval_id.'_'.$urow['user_id']);
                                $email_data = getPoApprovalEmailData($po_revision_id,$purchase_order_id,$approval_at);
                                $subject = $email_data['subject'];
                                //$message = $email_data['message'];
                                $message = str_replace('{ENCODED_ID}', $encoded_id, $email_data['message']);
                                //$message .= '<p>Approver Email: '.$urow['first_name'].' '.$urow['last_name'].' ('.$urow['email_id'].')</p>';
                                send_email($to,$subject,$message);
                                //echo $to.'<br>'.$subject.'<br>'.$message.'<br>'; 
                            }
                        }
                        // Approval Email to Next Level  END

                    }
                    $success_msg = 'Purchase Order has been approved successfully';
                break;
                
               case 2: // If Rejected
               $success_msg = 'Purchase Order has been rejected successfully';
                    $poStatus = 3;
                    $dataArr = array('status' => $poStatus,
                            'modified_by' => $this->session->userdata('user_id'),
                            'modified_time' => date('Y-m-d H:i:s'));
                    $this->Common_model->update_data('purchase_order', $dataArr, array('purchase_order_id' => $purchase_order_id));
                    addPoStatusHistory($purchase_order_id, $poStatus);
                    // reject margin analysis
                    $ma_data = array('status'=>3,'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
                    $ma_where = array('approval_id'=>$approval_id);
                    $this->Common_model->update_data('po_product_approval',$ma_data,$ma_where);
                    // Cancel remain pending product approvals in this po
                    $ma_data = array('status'=>4,'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
                    $ma_where = array('po_revision_id'=>$po_revision_id,'status'=>1);
                    $this->Common_model->update_data('po_product_approval',$ma_data,$ma_where);
                    // Email Notification to distributor
                    $cc_users = getPoApprovalCcUsersList($role_id,$approval_id);
                    $cc = array();
                    if($cc_users)
                    {
                        foreach ($cc_users as $cc_row) {
                            $cc[] = $cc_row['email_id'];
                        }
                        $cc = array_unique($cc);
                    }
                    $cc = (count($cc)>0)?implode(',', $cc):'';
                    $to = @$distributor['email_id'];
                    // Get Product info
                    $product = $this->Common_model->get_data_row('product',array('product_id'=>$product_id));
                    $subject = 'Notification: Product : '.$product['name'].' - '.$product['description'].' has been rejected for Purchase Order ID: '.$purchase_order_id;
                    $message = '<p>Hi '.$distributor['first_name'].' '.$distributor['last_name'].',</p>';
                    $message .= '<p>Product: '.$opportunity_id.' has been successfully pass through Margin Analysis </p>';
                    $message .= '<p><strong>Purchase Order ID:</strong> '.$purchase_order.' </p>';

                    $message .= '<p>Regards,</p>';
                    $message .= '<p>iCRM,<br>SkanRay</p>';
                    /*echo 'To:'.$to.'<br>'.'CC:'.$cc.'<br>Subject:'.$subject.'<br>Message:'.$message;
                    exit;*/
                    //send_email($to,$subject,$message,$cc);
                break;
            }

            if($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while adding a revision to Quote!
                                     </div>');
                redirect(SITE_URL.'po_approval_list');
                    
            }
            else
            {
                $this->db->trans_commit();
                //exit();
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> '.$success_msg.'
                                     </div>');
                redirect(SITE_URL.'po_approval_list');
            }
        }
        

    }

    // Updated: Srilekha , 3Nov17
    function po_tracking()
    {

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Purchase Order Tracking";
        $data['nestedView']['cur_page'] = 'po_tracking';
        $data['nestedView']['parent_page'] = 'po_tracking';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Purchase Order Tracking';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Purchase Order Tracking', 'class' => '', 'url' => '');
        //$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Lead ID - ' . $lead_id, 'class' => 'active', 'url' => '');

       
        $data['pageDetails'] = 'po_tracking';
        //$data['lead_id'] = $lead_id;

        # Search Functionality
        $psearch = $this->input->post('searchApprveQuote', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'product_details' => $this->input->post('product_details', TRUE),
                'distributor_name' => $this->input->post('distributor_name', TRUE),
                'po_status' => $this->input->post('po_status', TRUE),
                'purchase_order_id' => $this->input->post('purchase_order_id', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'product_details' => $this->session->userdata('product_details'),
                    'distributor_name' => $this->session->userdata('distributor_name'),
                    'po_status' => $this->session->userdata('po_status'),
                    'purchase_order_id' => $this->session->userdata('purchase_order_id')
                );
            } else {
                $searchParams = array(
                    'product_details' => '',
                    'distributor_name' => '',
                    'po_status' => '',
                    'purchase_order_id'=>''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;


        $config = get_paginationConfig();

        $current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();

        $poSearch = array();
        $poResults = $this->MarginAnalysis_model->getPoList($current_offset, $config['per_page'],$searchParams);
        //echo '<pre>';print_r($poResults); echo '</pre>'; exit;
        $docs = array();
        if($poResults)
        {
            foreach ($poResults as $row) {
                $poSearch[$row['purchase_order_id']][] = $row;
                $docs[$row['purchase_order_id']] = $this->Common_model->get_data('po_document',array('status'=>1,'purchase_order_id'=>$row['purchase_order_id']));
            }
        }
        $data['docs'] = $docs;
        # Loading the data array to send to View
        $data['poSearch'] = @$poSearch;


        # Default Records Per Page - always 10
        /* pagination start */
        $config['base_url'] = SITE_URL . 'track_quotes/';
        # Total Records
        $config['total_rows'] = $this->MarginAnalysis_model->getTotalPoRows($searchParams);
        //echo $config['total_rows']; exit;
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
        $data['enable_po_upload']=get_preference('enable_po_upload','dealer_settings');
        $data['flag']=1;
        /* pagination end */
        $data['login_user_role_id'] = $this->session->userdata('role_id');
        $this->load->view('po/po_tracking', $data);
    }



public function insert_po_documents() 
    {
        if ($this->input->post('upload_files')!= "") 
        {
            $po_list = $this->input->post('po_list',TRUE);
            $this->db->trans_begin();
            if(count($po_list)>0)
            {
                foreach ($po_list as $key => $value) 
                {
                    for($i=0; $i<count($_FILES['po_files']['name'][$value]);$i++)
                    {
                        if(isset($_FILES['po_files']['name'][$value][$i]))
                        {
                            if ($_FILES['po_files']['name'][$value][$i] != NULL) 
                            { 
                                // check upload files exist
                                $_FILES['po_files1']['name']     = $_FILES['po_files']['name'][$value][$i];
                                $_FILES['po_files1']['type']     = $_FILES['po_files']['type'][$value][$i];
                                $_FILES['po_files1']['tmp_name'] = $_FILES['po_files']['tmp_name'][$value][$i];
                                $_FILES['po_files1']['error']    = $_FILES['po_files']['error'][$value][$i];
                                $_FILES['po_files1']['size']     = $_FILES['po_files']['size'][$value][$i];

                                $uploaded_file_name = file_upload('po_files1', NULL, 'uploads/dealer_po_documents/', TRUE, 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx', 4096);
                                if (isset($uploaded_file_name['error'])) 
                                {
                                    // if error in attachment uploaded
                                    $this->session->set_flashdata('response', '<div class="alert alert-danger alert-white rounded"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><div class="icon"><i class="fa fa-times-circle"></i></div><strong>Error!</strong> '.$uploaded_file_name['error'].' Try again.!</div>');
                                    redirect(SITE_URL.'po_tracking'); exit();
                                } 
                                else
                                {
                                    $dataArr = array(
                                        'purchase_order_id' => $value,
                                        'document_name'     => $uploaded_file_name,
                                        'name'              => $_FILES['po_files1']['name'],
                                        'status'            => 1,
                                        'created_by'        => $_SESSION['user_id'],
                                        'created_time'      => date('Y-m-d H:i:s')
                                    );
                                    $this->Common_model->insert_data('po_document', $dataArr);
                                }
                            }              
                        }
                    }
                }

                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response', '<div class="alert alert-danger alert-white rounded"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><div class="icon"><i class="fa fa-times-circle"></i></div>
                        <strong>Error!</strong> Something Went Wrong! Try Again.</div>');
                    redirect(SITE_URL.'po_tracking'); exit();
                }
                else
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><div class="icon"><i class="fa fa-check"></i></div><strong>Success!</strong> PO Documents has been Added successfully!</div>');
                    redirect(SITE_URL.'po_tracking'); exit();
                }
            }
        }
        redirect(SITE_URL.'po_tracking'); exit();
    }

}