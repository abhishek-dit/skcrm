<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class EmailApprovalAction extends CI_controller {

    public function __construct() {
        parent::__construct();
        //echo 'hi'; 
        $this->load->helper(array('form', 'url'));
        $this->load->model("MarginAnalysis_model");
        $this->load->model("Contract_model");
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
            $opportunity_number = $this->Common_model->get_data_row('opportunity',array('opportunity_id'=>$opportunity_id));
            $approval_type = $this->input->post('approval_type');
            $message1 ='';
            $conditionApproval = $this->Common_model->get_data('condition_approval_mail', array('condition_approval_mail_id'=>1));		

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
            $this->db->trans_begin();
            $data1 = array();
            if($conditionApproval[0]['condition'] == 0){
            $data1 = array(
                                'margin_approval_id'    =>  $margin_approval_id,
                                'approved_by'           =>  $role_id,
                                'remarks'               =>  $remarks,
                                'created_by'            =>  $user_id,
                                'created_time      '    =>  date('Y-m-d H:i:s'),
                                'status'                =>  $action
                            );
                            $this->Common_model->insert_data('quote_op_margin_approval_history',$data1);

                        }else{
                           $quoteMarginApproval = $this->Common_model->get_data('quote_op_margin_approval', array('quote_revision_id'=>$quote_revision_id,'status'=>1));
                           foreach($quoteMarginApproval as $key){
                                $data1 = array(
                                    'margin_approval_id'    =>  $key['margin_approval_id'],
                                    'approved_by'           =>  $role_id,
                                    'remarks'               =>  $remarks,
                                    'created_by'            =>  $user_id,
                                    'created_time      '    =>  date('Y-m-d H:i:s'),
                                    'status'                =>  $action
                            );
                            $this->Common_model->insert_data('quote_op_margin_approval_history',$data1);
            
                           }
                        } 
            // Transaction Begins here
            // $this->db->trans_begin();
            // Insert approval action history
            // $this->Common_model->insert_data('quote_op_margin_approval_history',$data1);
            $lead_user = $this->Common_model->get_data_row('user',array('user_id'=>$lead_owner));
            $ma_row = $this->Common_model->get_data_row('quote_op_margin_approval',array('margin_approval_id'=>$margin_approval_id));
            switch ($action) {
                case 1: 
                    $cond = false;
                    if($conditionApproval[0]['condition'] == 1){
                        $quoteMarginApproval = $this->Common_model->get_data('quote_op_margin_approval', array('quote_revision_id'=>$quote_revision_id, 'status' => 1));
                             $i=0;
                        foreach($quoteMarginApproval as $key){
                        //    echo $key['close_at'];
                                 if($key['approval_at'] == $key['close_at'])
                                 $i++;              
                             }
                             if(sizeof($quoteMarginApproval) == $i){
                                $cond = true;
                             }
                            //  echo sizeof($quoteMarginApproval).' '.$i;die;
                    }
                    else{
                        if($role_id==$ma_row['close_at'])
                        $cond = true;
                    }
                // echo $cond;die;
                if($cond)//finl approval
                    {
                        // Check if all opportunities are approved.
                        // Get opportunities count By quote_revision_id
                        
                        // approve margin analysis
                        if($conditionApproval[0]['condition'] == 1){
                            $quoteMarginApproval = $this->Common_model->get_data('quote_op_margin_approval', array('quote_revision_id'=>$quote_revision_id, 'status'=> 1));
                            foreach($quoteMarginApproval as $key){
                                         $ma_data = array('status'=>2,'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
                                         $ma_where = array('margin_approval_id'=>$key['margin_approval_id']);
                                         $this->Common_model->update_data('quote_op_margin_approval',$ma_data,$ma_where);                                                                                   
                                 }
                        }else{
                        $ma_data = array('status'=>2,'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
                        $ma_where = array('margin_approval_id'=>$margin_approval_id);
                        $this->Common_model->update_data('quote_op_margin_approval',$ma_data,$ma_where);
                        }
                        /*echo $op_count.'-->'.$approved_op_count;
                        exit;*/

                        $op_count = getMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id);
                        $approved_op_count = getApprovedMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id);
                        $completed_op_count = getCompletedMarginAnalysisOpportunityCountByQuoteRevision($quote_revision_id);
                        // echo 'quote_revision_id'.$quote_revision_id;

                        // echo 'op_count'.$op_count;
                        // echo 'approved_op_count'.$approved_op_count ;
                        // echo 'completed_op_count'.$completed_op_count ; die;
                        // if($conditionApproval[0]['condition'] == 0){
                        // $approved_op_count++;
                        // $completed_op_count++;
                        // }
                        // echo 'op_count'.$op_count;
                        // echo 'approved_op_count'.$approved_op_count ;
                        // echo 'completed_op_count'.$completed_op_count ; die;
                        
                        if($op_count==$completed_op_count)
                        // if($completed_op_count>=1)
                        {
                            if($op_count==$approved_op_count)
                            // if($approved_op_count>=1)
                            {
                        //         echo 'quote_revision_id'.$quote_revision_id;

                        // echo 'op_count'.$op_count;
                        // echo 'approved_op_count'.$approved_op_count ;
                        // echo 'completed_op_count'.$completed_op_count ; die;
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
                                $quote_number = $this->Common_model->get_value("quote", array('quote_id' => $quote_id), "quote_number");

                                // $quote_reference_id = getQuoteReferenceID($lead_id,$quote_id);
                                $quote_reference_id = getQuoteRevisionReferenceID($lead_id,$quote_id,$quote_revision_id,$quote_number);
                                
                                $opportunity_details = getOpportunityDetails($opportunity_id);
                                $get_opp_details = getOppDetailsByRevisionID($quote_revision_id);
                                // $cc_users = getMarginAnalysisCcUsersList($role_id,$margin_approval_id);
                                $cc_users = getMarginAnalysisCcUsersListFinalApprove($margin_approval_id);
                                $userId1 = $this->session->userdata('user_id');
                                $chDetails = $this->Common_model->get_data_row('user',array('user_id'=>$userId1));
                                // echo "<pre>";print_r($cc_users);die;
                                $chDetails1 = array_merge($cc_users,array($chDetails));
                                // $cc_users .= $chDetails[0];
                                // echo "<pre>";print_r($chDetails1);die;
                                $cc_users = $chDetails1;
                                $cc = array();
                                // $all_roles = array($this->Common_model->get_data_row('user',array('branch_id'=>$lead_user['branch_id'],'status' => 1)));
                                if($cc_users)
                                {
                                    foreach ($cc_users as $cc_row) {
                                        $cc[] = $cc_row['email_id'];
                                    }
                                    // foreach ($all_roles as $cc_row) {
                                    //     $cc[] = $cc_row['email_id'];
                                    // }
                                    $cc = array_unique($cc);
                                }
                                $cc = (count($cc)>0)?implode(',', $cc):'';
                                // echo "<pre>";print_r($cc);
                                $to = @$lead_user['email_id'];
                                // echo "<pre>";print_r($to);die;


                                // $subject = 'Opportunity: '.$opportunity_number['opp_number'].' has been approved with Quote Reference ID: '.$quote_reference_id;
                                // $message = '<p>Hi '.$lead_user['first_name'].' '.$lead_user['last_name'].',</p>';
                                // $message .= '<p>Opportunity: '.$opportunity_number['opp_number'].' has been successfully pass through Margin Analysis </p>';
                                // $message .= '<p><strong>Quote Reference ID:</strong> '.$quote_reference_id.' </p>';
                                // $message .= '<p><strong>Opportunity Details:</strong> '.$opportunity_details.' </p>';

                                $subject = 'Quote Reference ID: '.$quote_reference_id.' has been approved';
                                $message = '<p>Hi '.$lead_user['first_name'].' '.$lead_user['last_name'].',</p>';
                                

                                $message .= '<p>Opportunity has been successfully pass through Margin Analysis </p>';
                                $message .= '<p><strong>Quote Reference ID:</strong> '.$quote_reference_id.' </p>';
                                
                                foreach($get_opp_details as $opp_val)
                                {
                                    $message .= '<p><strong>Opportunity Details:</strong> '.$opp_val['opportunity'].' </p>';
                                }
                                

                                $message .= '<p>Regards,</p>';
                                $message .= '<p>iCRM,<br>Skanray</p>';
                                /*echo 'To:'.$to.'<br>'.'CC:'.$cc.'<br>Subject:'.$subject.'<br>Message:'.$message;
                                exit;*/
                                send_email1($to,$cc,$subject,$message);
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
                                $quote_number = $this->Common_model->get_value("quote", array('quote_id' => $quote_id), "quote_number");

                                // $quote_reference_id = getQuoteReferenceID($lead_id,$quote_id);
                                $quote_reference_id = getQuoteRevisionReferenceID($lead_id,$quote_id,$quote_revision_id,$quote_number);
                                
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
                                $subject = 'Opportunity: '.$opportunity_number['opp_number'].' has been approved with Quote Reference ID: '.$quote_reference_id;
                                $message = '<p>Hi '.$lead_user['first_name'].' '.$lead_user['last_name'].',</p>';
                                $message .= '<p>Opportunity: '.$opportunity_number['opp_number'].' has been failed to pass through Margin Analysis </p>';
                                $message .= '<p><strong>Quote Reference ID:</strong> '.$quote_reference_id.' </p>';
                                $message .= '<p><strong>Opportunity Details:</strong> '.$opportunity_details.' </p>';

                                $message .= '<p>Regards,</p>';
                                $message .= '<p>iCRM,<br>Skanray</p>';
                                /*echo 'To:'.$to.'<br>'.'CC:'.$cc.'<br>Subject:'.$subject.'<br>Message:'.$message;
                                exit;*/
                                send_email($to,$subject,$message);
                            }
                            

                        }
                        
                        
                       
                    }
                    else
                    {
                        // Forward to next level
                        $next_approval_role = getNextApprovalRole($role_id);
                        if($conditionApproval[0]['condition'] == 1){
                            $quoteMarginApproval = $this->Common_model->get_data('quote_op_margin_approval', array('quote_revision_id'=>$quote_revision_id, 'status'=>1));
                            // echo '<pre>'; print_r($quoteMarginApproval);die;
                            foreach($quoteMarginApproval as $key){
                                     if($role_id == 7){
                                         if($key['approval_at'] == 7 && $key['close_at'] != 7){
                                            $data2 = array(
                                                'approval_at'   => $next_approval_role
                                                );
                                           $where2 = array( 'margin_approval_id' => $key['margin_approval_id']);
                                           $this->Common_model->update_data('quote_op_margin_approval',$data2,$where2);
                                         }else if($key['approval_at'] == 7 && $key['close_at'] == 7){
                                            $data2 = array(
                                                'status'   => 2
                                                );
                                           $where2 = array( 'margin_approval_id' => $key['margin_approval_id']);
                                           $this->Common_model->update_data('quote_op_margin_approval',$data2,$where2);
                                         }
                                                    
                                     }else if($role_id == 8){
                                        if($key['approval_at'] ==8 && $key['close_at'] != 8){
                                            $data2 = array(
                                                'approval_at'   => $next_approval_role
                                                );
                                           $where2 = array( 'margin_approval_id' => $key['margin_approval_id']);
                                           $this->Common_model->update_data('quote_op_margin_approval',$data2,$where2);
                                        }else if($key['approval_at'] == 8 && $key['close_at'] == 8){
                                            $data2 = array(
                                                'status'   => 2
                                                );
                                           $where2 = array( 'margin_approval_id' => $key['margin_approval_id']);
                                           $this->Common_model->update_data('quote_op_margin_approval',$data2,$where2);
                                         }
                                                   
                                     }
                                 }
                                 
                                
                        }else{
                                $data2 = array(
                                        'approval_at'   => $next_approval_role
                                    );
                                  $where2 = array( 'margin_approval_id' => $margin_approval_id);
                                  $this->Common_model->update_data('quote_op_margin_approval',$data2,$where2);
                                }
                        // Approval Email Alert :  START
                        $quote_approvers = getOppApproverEmailsByRole($next_approval_role,$opportunity_id);
                        if($conditionApproval[0]['condition'] == 0){
                                    if($next_approval_role==8)
                                      {
                                        $message1 = '<p>Opportunity: '.$opportunity_number['opp_number'].' has been Approved by RBH and Pending At NSM </p>';
                                     }
                                     else
                                     {
                                          $message1 = '<p>Opportunity: '.$opportunity_number['opp_number'].' has been Approved by NSM and Pending At CH </p>';
                                    }
                        }else{
                            if($next_approval_role==8)
                            {
                              $message1 = '<p>Opportunity: '.$quote_reference_id.' has been Approved by RBH and Pending At NSM </p>';
                           }
                           else
                           {
                                $message1 = '<p>Opportunity: '.$quote_reference_id.' has been Approved by NSM and Pending At CH </p>';
                          }
                        }
                        $lead_user = $this->Common_model->get_data_row('user',array('user_id'=>$lead_owner));
                        $quote_number = $this->Common_model->get_value("quote", array('quote_id' => $quote_id), "quote_number");

                                // $quote_reference_id = getQuoteReferenceID($lead_id,$quote_id);
                                $quote_reference_id = getQuoteRevisionReferenceID($lead_id,$quote_id,$quote_revision_id,$quote_number);
                                
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
                        if($conditionApproval[0]['condition'] == 1){
                            $subject = 'Quotation: '.$quote_reference_id.' has been approved';
                        }
                        else{
                        $subject = 'Opportunity: '.$opportunity_number['opp_number'].' has been approved with Quote Reference ID: '.$quote_reference_id;
                        }
                        $message = '<p>Hi '.$lead_user['first_name'].' '.$lead_user['last_name'].',</p>';
                        //$message .= '<p>Opportunity: '.$opportunity_number.' has been successfully pass through Margin Analysis </p>';
                        $message .= $message1;
                        $message .= '<p><strong>Quote Reference ID:</strong> '.$quote_reference_id.' </p>';
                        if($conditionApproval[0]['condition'] == 1){
                            $get_opportunity_details = getOppDetailsByRevisionID($quote_revision_id);
                            foreach($get_opportunity_details as $opp_val)
                            {
                                $message .= '<p><strong>Opportunity Details:</strong> '.$opp_val['opportunity'].' </p>';
                            }
                         }else{
                                $message .= '<p><strong>Opportunity Details:</strong> '.$opportunity_details.' </p>';
                         }
                        $message .= '<p>Regards,</p>';
                        $message .= '<p>iCRM,<br>Skanray</p>';
                        /*echo 'To:'.$to.'<br>'.'CC:'.$cc.'<br>Subject:'.$subject.'<br>Message:'.$message;
                        exit;*/
                        send_email($to,$subject,$message);

                        if(count($quote_approvers)>0)
                        {
                            foreach ($quote_approvers as $urow) {
                                $to = $urow['email_id'];
                                $quoteMarginApproval11 = $this->Common_model->get_data('quote_op_margin_approval', array('quote_revision_id'=>$quote_revision_id, 'status' => 1));
                                // echo '<pre>'; print_r($quoteMarginApproval11);die;
                                if($conditionApproval[0]['condition'] == 1){
                                    $encoded_id = icrm_encode($quoteMarginApproval11[0]['margin_approval_id'].'_'.$urow['user_id']);
 
                                }else{
                                    $encoded_id = icrm_encode($margin_approval_id.'_'.$urow['user_id']);
                                }
                                $email_data = getQuoteApprovalEmailData($quote_revision_id,$quote_id,$next_approval_role,0,$margin_approval_id);
                                $subject = $email_data['subject'];
                                //$message = $email_data['message'];
                                $message = str_replace('{ENCODED_ID}', $encoded_id, $email_data['message']);
                                send_email($to,$subject,$message);
                                //echo $to.'<br>'.$subject.'<br>'.$message.'<br>'; 
                            }
                        }
                        
                        // Approval Email Alert :  END

                    }
                    if($conditionApproval[0]['condition'] == 1){
                        $success_msg = 'Quotation : '.$quote_reference_id1.' quote revision has been approved successfully';
                    }else{
                        $success_msg = 'Opportunity : '.$opportunity_number['opp_number'].' quote revision has been approved successfully';
                    }
                break;
                
               case 2: // If Rejected
               $success_msg = 'Opportunity : '.$opportunity_number['opp_number'].' quote revision has been rejected successfully';

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
                        $quote_number = $this->Common_model->get_value("quote", array('quote_id' => $quote_id), "quote_number");

                                // $quote_reference_id = getQuoteReferenceID($lead_id,$quote_id);
                                $quote_reference_id = getQuoteRevisionReferenceID($lead_id,$quote_id,$quote_revision_id,$quote_number);
                                
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
                        $subject = 'Opportunity - '.$opportunity_number['opp_number'].' has been rejected for Quote Reference ID: '.$quote_reference_id;
                        $message = '<p>Hi '.$lead_user['first_name'].' '.$lead_user['last_name'].',</p>';
                        $message .= '<p>Opportunity: '.$opportunity_number['opp_number'].' has been failed to pass through Margin Analysis </p>';
                        $message .= '<p><strong>Quote Reference ID:</strong> '.$quote_reference_id.' </p>';
                        $message .= '<p><strong>Opportunity Details:</strong> '.$opportunity_details.' </p>';
                        $message .= '<p>Regards,</p>';
                        $message .= '<p>iCRM,<br>Skanray</p>';
                        /*echo 'To:'.$to.'<br>'.'CC:'.$cc.'<br>Subject:'.$subject.'<br>Message:'.$message;
                        exit;*/
                        send_email($to,$subject,$message);
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
                        $subject = 'Product : '.$product['name'].' - '.$product['description'].' has been approved with Purchase Order ID: '.$purchase_order_id;
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
                    $subject = 'Product : '.$product['name'].' - '.$product['description'].' has been rejected for Purchase Order ID: '.$purchase_order_id;
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
        $conditionApproval = $this->Common_model->get_data('condition_approval_mail', array('condition_approval_mail_id'=>1));		
        $ma_rows = array();
        if($conditionApproval[0]['condition'] == 1){
            $revisionId = $this->Common_model->get_data('quote_op_margin_approval', array('margin_approval_id'=>$margin_approval_id),"quote_revision_id");
            $ma_rows = getOpportunitiesInfoByQuoteRevision($revisionId[0]['quote_revision_id']);
            // print_r($revisionId);die; 
            // $details = $this->Common_model->get_data('quote_op_margin_approval', array('quote_revision_id'=>$revisionId[0]['quote_revision_id']));
            
                // foreach($details as $key){
                //     $ma_rows[] = getOpportunitiesInfoByQuoteRevision($key['quote_revision_id']);
                //     //  $ma_rows[] = $this->MarginAnalysis_model->getMarginApprovalInfo($key['margin_approval_id']);
                // }
        
        }
        // echo '<pre>'; print_r($arr);die;
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
            $data['ma_rows'] = $ma_rows;
            $data['conditionApproval'] = $conditionApproval;
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
    public function cnoteApprovalAction_fromEmail()
    {   
        $type = $this->uri->segment(2);
        $decode_id = icrm_decode($this->uri->segment(3));
        $arr = explode('+',$decode_id);
        $contract_note_id = $arr[0];
        $user_id = $arr[1];
        $cnote_status = $this->Common_model->get_value('contract_note',array('contract_note_id'=>$contract_note_id),'status');
        if($cnote_status==5)
        {
            switch($type)
            {
                case 1:  //Approved
                    $this->Common_model->update_data('contract_note', array('status'=>3), array('contract_note_id'=>$contract_note_id
                    ));
                    // C-Note Status history
                    $statusData = array('contract_note_id' => $contract_note_id, 
                    'status' => 3,
                    'created_by' => $user_id,
                    'created_time' => date('Y-m-d H:i:s'));
                    $this->Common_model->insert_data('contract_note_status_history', $statusData);
                    $type_name = 'Approved';
                    echo "Contract Note has been succcessfully ".$type_name;
                    break;
                case 2 : // Rejected
                        // Update Quote Status
                        $c_lead_id=$this->Contract_model->get_lead_id($contract_note_id);
                        $qry1 = ' UPDATE quote q 
                        JOIN quote_revision qr ON qr.quote_id = q.quote_id
                        JOIN contract_note_quote_revision cnqr ON cnqr.quote_revision_id = qr.quote_revision_id
                        SET q.status = 2
                        WHERE cnqr.contract_note_id = '.$contract_note_id;
                        $this->db->query($qry1);

                          // Update Opportunity Status
                        $qry2 = ' UPDATE opportunity o 
                        JOIN quote_details qd ON qd.opportunity_id = o.opportunity_id
                        JOIN quote q ON q.quote_id = qd.quote_id 
                        JOIN quote_revision qr ON qr.quote_id = q.quote_id
                        JOIN contract_note_quote_revision cnqr ON cnqr.quote_revision_id = qr.quote_revision_id
                        SET o.status = 5
                        WHERE cnqr.contract_note_id = '.$contract_note_id;
                        $this->db->query($qry2);

                        $cnote_details = get_details_by_cnoteId($contract_note_id);
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
                            $statusData = array('quote_id' => $quote_id, 
							'status' => $new_quote_status,
							'created_by' => $user_id,
							'created_time' => date('Y-m-d H:i:s'));
		                    $this->Common_model->insert_data('quote_status_history', $statusData);
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
                            $statusData = array('lead_id' => $lead_id, 
							'status' => $new_lead_status,
							'created_by' => $user_id,
							'created_time' => date('Y-m-d H:i:s'));
		                    $this->Common_model->insert_data('lead_status_history', $statusData);
                            // UPDATE lead status
                            $data_arr = array('status'=>$new_lead_status,'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
                            $where_arr = array('lead_id'=>$lead_id);
                            $this->Common_model->update_data('lead',$data_arr,$where_arr);
                            $where = array('contract_note_id'=>$contract_note_id);
                            // Deleting Free Products
                            $this->db->delete('free_products', $where);
                           
                            $this->Common_model->update_data('contract_note', array('status'=>6), array('contract_note_id'=>$contract_note_id
                            ));
                            // C-Note Status history
                            $statusData = array('contract_note_id' => $contract_note_id, 
                            'status' => 6,
                            'created_by' => $user_id,
                            'created_time' => date('Y-m-d H:i:s'));
                            $this->Common_model->insert_data('contract_note_status_history', $statusData);
                        }
                        $c_customer_id=$this->Common_model->get_value('lead',array('lead_id'=>$c_lead_id['lead_id']),'customer_id');
                        $first_cnote=$this->Contract_model->get_first_cnote($c_customer_id);
                        if($first_cnote['first_cnote']!='')
                        {
                            $this->Common_model->update_data('contract_note',array('business_type'=>1),array('contract_note_id'=>$first_cnote['first_cnote']));
                        }
                        $type_name = 'Rejected';
                        echo "Contract Note has been succcessfully ".$type_name;
                        break;

            }
        }
        else
        {
            echo 'This Link is no longer valid, Contract Note approval action has been completed ';
        }
    }

}