<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class EmailApprovalAction extends CI_controller {

    public function __construct() {
        parent::__construct();
        //echo 'hi'; 
        $this->load->helper(array('form', 'url'));
        $this->load->model("MarginAnalysis_model");
        $this->load->model("Common_model");
        $this->load->library('encrypt');
        $this->load->library('user_agent');//exit('hello3');
    }

    public function submitQuoteApprovalAction()
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
                $role_id = $this->Common_model->get_value('user',array('user_id'=>$user_id),'role_id');
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
                $success_msg = 'Quote has been approved successfully';
                break;
                
               case 2: // If Rejected
               $success_msg = 'Quote has been rejected successfully';

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
                redirect(SITE_URL.'approval_result');
                    
            }
            else
            {
                $this->db->trans_commit();
                $temp_sess_data = array('user_id'=>'','role_id'=>'');
                $this->session->set_userdata($temp_sess_data);
                //exit('testing');
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> '.$success_msg.'.
                                     </div>');
                redirect(SITE_URL.'approval_result');
            }
        }
        

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
            $approval_type = $this->input->post('approval_type');
            $distributor_id = $this->input->post('distributor_id');
            if($approval_type==1)
            {
                $role_id = $this->session->userdata('role_id');
                $user_id = $this->session->userdata('user_id');
            }
            else if($approval_type==2)
            {
                $user_id = $this->input->post('user_id');
                $role_id = $this->Common_model->get_value('user',array('user_id'=>$user_id),'role_id');
                $temp_sess_data = array('user_id'=>$user_id,'role_id'=>$role_id);
                $this->session->set_userdata($temp_sess_data);
            }
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
                redirect(SITE_URL.'approval_result');
                    
            }
            else
            {
                $this->db->trans_commit();
                //exit;
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> '.$success_msg.'.
                                     </div>');
                redirect(SITE_URL.'approval_result');
            }
        }
        
    }

    
    public function quoteApprovalAction_fromEmail()
    {
        $type = $this->uri->segment(2);
        $decode_id = icrm_decode($this->uri->segment(3));
        $arr = explode('_',$decode_id);
        $margin_approval_id = $arr[0];
        $user_id = $arr[1];
        $ma_row = $this->MarginAnalysis_model->getMarginApprovalInfo($margin_approval_id);
        if($ma_row['status']==1)
        {
            # Data Array to carry the require fields to View and Model
            $data['nestedView']['heading'] = "Quote Approval Action";
            $data['nestedView']['cur_page'] = 'marginAnalysisConfig';
            $data['nestedView']['parent_page'] = 'marginAnalysisConfig';

            # Load JS and CSS Files
            $data['nestedView']['js_includes'] = array();
            $data['nestedView']['css_includes'] = array();

            # Breadcrumbs
            $data['nestedView']['breadCrumbTite'] = 'Quote Approval Action';
            $data['nestedView']['breadCrumbOptions'] = array(array('label' => '', 'class' => '', 'url' => ''));
            $data['row'] = $ma_row;
            $data['type'] = $type;
            $data['user_id'] = $user_id;
            $data['user'] = $this->Common_model->get_data_row('user',array('user_id'=>$user_id));
            $this->load->view('margin_analysis/email_quote_approval', $data);

        }
        else
        {
            echo 'This Link is no longer valid, Quote approval action has been completed ';
        }
    }

    public function approval_result()
    {
        
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Approval Result";
        $data['nestedView']['cur_page'] = 'approval_result';
        $data['nestedView']['parent_page'] = 'approval_result';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Approval Result';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => '', 'class' => '', 'url' => ''));
        $this->load->view('margin_analysis/approval_result', $data);

    }

    public function poApprovalAction_fromEmail()
    {
        $type = $this->uri->segment(2);
        $decode_id = icrm_decode($this->uri->segment(3));
        $arr = explode('_',$decode_id);
        $approval_id = $arr[0];
        $user_id = $arr[1];
        $ma_row = $this->MarginAnalysis_model->getPoApprovalInfo($approval_id);
        /*echo $approval_id.'-->'.$user_id;
        print_r($ma_row); exit;*/
        if($ma_row['status']==1)
        {
            # Data Array to carry the require fields to View and Model
            $data['nestedView']['heading'] = "PO Approval Action";
            $data['nestedView']['cur_page'] = 'poApprovalAction';
            $data['nestedView']['parent_page'] = 'poApprovalAction';

            # Load JS and CSS Files
            $data['nestedView']['js_includes'] = array();
            $data['nestedView']['css_includes'] = array();

            # Breadcrumbs
            $data['nestedView']['breadCrumbTite'] = 'PO Approval Action';
            $data['nestedView']['breadCrumbOptions'] = array(array('label' => '', 'class' => '', 'url' => ''));
            $data['row'] = $ma_row;
            $data['type'] = $type;
            $data['user_id'] = $user_id;
            $data['user'] = $this->Common_model->get_data_row('user',array('user_id'=>$user_id));
            $this->load->view('margin_analysis/email_po_approval', $data);

        }
        else
        {
            echo 'This Link is no longer valid, Purchase Order approval action has been completed ';
        }
    }

}