<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contract_api extends CI_Controller {

	public function __construct() 
	{
        parent::__construct();
        $this->load->model("Contract_model");
        $this->load->model("Common_model");
		$this->load->model("quote_model");
        $this->load->library('user_agent');
        $this->load->library('Pdf');
    }
    public function openCNoteDetails()
	{   
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $lead_id = $post_data['lead_id'];
        $_SESSION['company'] = $post_data['company_id'];
		if(checkCNote($lead_id) == 0)
		{
			$this->session->sess_destroy();
            $data['response'] = 'Atleast One Quotation should be there to create Contract Note!';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
		}
		$leadStatus = getLeadStatusID($lead_id);
        $data['leadStatus'] = $leadStatus;
		$data['pageDetails'] = 'CNote';
		$data['lead_id'] = $lead_id;
        $search_fields = 0;
        $cNoteDetails = array();
        $cNoteDetails = $this->Contract_model->getCNoteDetails($lead_id);

        # Loading the data array to send to View
        $data['count'] = @$cNoteDetails['count'];
        $cNoteDetails_arr=array();
        if($data['count']>0)
        {
            foreach($cNoteDetails['resArray'] as $row)
            {   
                $cNoteDetails_arr_row=array();
                $cNoteDetails_arr_row['cnote_number']= $row['cnote_number'];
                $cNoteDetails_arr_row['po_number']= $row['po_number'];
                $cNoteDetails_arr_row['po_date']= $row['po_date'];
                $cNoteDetails_arr_row['so_number']= $row['so_number'];
                $cNoteDetails_arr_row['status']=getCNoteStatus(@$row['status']);
                $cNoteQuotes = getCNoteQuotes(@$row['contract_note_id']);
                $cNoteDetails_arr_row['quotesCount'] = $cNoteQuotes['count'];
                $quotesInfo = $cNoteQuotes['resArr'];
                foreach($quotesInfo as $row1)
                {  
                    $opp= array();
                    $opp['quote_ref_id']=getQuoteReferenceID($lead_id, @$row1['quote_id']);
                    $opp['billing'] = $row1['billing'];
                    $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($row1['quote_revision_id']); // mahesh: 5th Jan 2018
                    switch ($quote_format_type) {
                        case 1: // Old Format
                            $discount = round($row1['discount']);
                            $quote_price = getQuotePrice($row1['quote_id'], $row1['discount']);
                        break;
                        case 2: // New Format
                            
                            $qrow = getQuoteRevisionPrice($row1['quote_revision_id']);
                            $quote_price = round($qrow['quote_price']);
                            $cost = round($qrow['cost']);
                            $discount_amt = ($cost-$quote_price);
                            $discount = ($discount_amt/$cost)*100;
                        break;
                    }
                    $opp['discount'] = round($discount,2).'%';
                    $cNoteDetails_arr_row['quotes_info'][] = $opp;
                }
               $cNoteDetails_arr[]= $cNoteDetails_arr_row;
            }
        }
        $data['cNoteDetails_arr']=$cNoteDetails_arr;
      
        #For add quote popup
        
        $quote_drop_view  = array();
        $lead_user_id = $this->Common_model->get_value("lead", array('lead_id' => $lead_id), "user_id");
        $lead_user_role_id = getUserRole($lead_user_id);

        $quoteDetails = $this->Contract_model->getAllLeadQuotes($lead_id);
        $quoteDropDown = array();
        foreach($quoteDetails as $quote) 
        {   
            $quote_drop_view = array();
            $quote_drop_view['name'] =  getQuoteReferenceID($lead_id, $quote['quote_id'])." : ".$quote['opportunity'];
            $quote_drop_view['quote_id'] = $quote['quote_id'];
            $quote_drop_view['quote_revision_id'] = $quote['quote_revision_id'];
            $quote_drop_view['opportunity'] = $quote['opportunity'];
            $quoteDropDown[] = $quote_drop_view;
        }
        $data['quoteDropDown'] = $quoteDropDown;
        $data['institution_code']=getCustomerSAPCode($lead_id);
        $data['lead_user_id'] = $lead_user_id;

        $data['checkPage'] = 1;//1 for Open Pages. 0 for Closed Pages		
        $this->session->sess_destroy();
        echo json_encode($data);
    }
    public function cNoteAdd()
    {   
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['user_id']= $post_data['user_id'];
        $_SESSION['company'] = $post_data['company_id'];
        if ($post_data['submitCNote'] != "") 
        // {   $quote_id = $this->input->post('quote_id');
           { $quote_id = $post_data['quote_id'];
            // echo "<pre>";print_r($post_data);
            // echo "<pre>POST==";print_r($_POST);die;
            $quotes=implode(",", $quote_id);
            $count_type = $this->Contract_model->check_for_multiple_quotes($quotes);
           if($count_type==0)
            {
                $this->session->sess_destroy(); 
                $data1['response'] =0;
                $data1['error']='Please make sure that selected quotes should have same Billing through.';
                header("Status: 404 Not Found",true,404);
                goto finish;
            }
            $currency_count = $this->Contract_model->check_quote_currency($quotes);
            if($currency_count == 0)
            {
                $this->session->sess_destroy(); 
                $data1['response'] =0;
                $data1['error']='Please make sure that selected quotes should have same Currency.';
                header("Status: 404 Not Found",true,404);
                goto finish;
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
                        if($qr_row['warranty']!=$qr_row['warranty']||$qr_row['advance_type']!=$qr_row['advance_type']||$qr_row['advance']!=$qr_row['advance']||$qr_row['balance_payment_days']!=$qr_row['balance_payment_days']||$qr_row['dealer_commission']!=$qr_row['dealer_commission']||$qr_row['dealer_id']!=$qr_row['dealer_id'])
                        {
                            $hasError = TRUE; break;
                        }
                    }
                    $i++;
                }

                if($hasError)
                {
                    //exit('error');
                    // $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    //                     <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    //                     <div class="icon"><i class="fa fa-check"></i></div>
                    //                     <strong>Message!</strong> Please make sure payment terms (Warranty, Advance, Balance Payment Days, Dealer Commision, Dealer) to be same for all quotes in a contract note. <br> If you have different terms generate them as seperate contract notes.
                    //                  </div>');
                    // redirect($this->agent->referrer());  
                    // exit; 
                    $data1['response'] =0;
                    $data1['error']=' Please make sure payment terms (Warranty, Advance, Balance Payment Days, Dealer Commision, Dealer) to be same for all quotes in a contract note. <br> If you have different terms generate them as seperate contract notes.';
                    header("Status: 404 Not Found",true,404);
                    goto finish;
                }
            }
            
            $this->db->trans_begin();
             goto cnote_start;
            cnote_start:
            
            /*phase 2 changes 
              updated by prasad 
              updating status to 3 for contract note approval  by rbh
            */
           // echo 234;die;
            $lead_str_arr = get_current_unique_numbers("contract_note","cnote_counter","contract_note_id");
            
            $cnote_counter=$lead_str_arr[0];
            $cnote_number=$lead_str_arr[1];
            $lead_id=$this->input->post('lead_id');
            $customer_id=$this->Common_model->get_value('lead',array('lead_id'=>$lead_id),'customer_id');
            $customer_cnotes=$this->Contract_model->get_customer_previous_data($customer_id);
            /*
              For fresh customer , business type=1;
              For Repeat customer, business type=2.
            */
            //print_r(count(@$customer_cnotes));die;
            if(count(@$customer_cnotes)>0)
            {
                $business_type=2;
            }
            else
            {
                $business_type=1;
            }
            //print_r($business_type);
            $dataArr2 = array(
                // "purchase_order_no" => $this->input->post('po_number'),
                // "date_of_purchase_order" => $this->input->post('po_date'),
                // "institution_code" => $this->input->post('institution_code'),
                // "billing_to_party" => $this->input->post('customer'),
                // "created_by" => $_SESSION['user_id'],
                // "company_id" =>  $this->session->userdata('company'),
                // 'status'    => 3,
                // 'business_type'=>$business_type,
				// 'delivery_period'           => $this->input->post('delivery_period'),
                // 'ld_applicable_date'        => $this->input->post('ld_applicable'),
                // 'warranty'                  => $this->input->post('warranty'),
                // 'amendment'                 => $this->input->post('amendment'),
                // 'reason_for_amendment'      => $this->input->post('reason_for_amendment'),
                // 'other_conditions'          => $this->input->post('other_conditions')
                // //'ship_to_party'             => $this->input->post('ship_to_party')

                "purchase_order_no" => $post_data['po_number'],
                "date_of_purchase_order" => $post_data['po_date'],
                "institution_code" => $post_data['institution_code'],
                "billing_to_party" => $post_data['billing_to_party'],
                "created_by" => $_SESSION['user_id'],
                "company_id" =>  $this->session->userdata('company'),
                'status'    => 3,
                'business_type'=>$business_type,
                'delivery_period'           => $post_data['delivery_period'],
                'ld_applicable_date'        => $post_data['ld_applicable'],
                'warranty'                  => $post_data['warranty'],
                'amendment'                 => $post_data['amendment'],
                'reason_for_amendment'      => $post_data['reason_for_amendment'],
                'other_conditions'          => $post_data['other_conditions']
            );
            // print_r($dataArr2);die;
            try
            {
                //echo 123;die;
                //check_unique_numbers_constraint('contract_note','cnote_counter',$cnote_counter);
                //echo 34;die;
            }
            catch(Exception $e)
            {
                //echo 456;die;
                goto cnote_start;
            }
            $dataArr2['cnote_counter']=$cnote_counter;
            $dataArr2['cnote_number']=$cnote_number;
            #By Default cnote status will be 3. It needs to go for RBH Approval
            $contract_note_id = $this->Common_model->insert_data('contract_note', $dataArr2);
            
            // C-Note Status history
            addCnoteStatusHistory($contract_note_id,3);
            
            // $quote_ids = $this->input->post('quote_id',TRUE);
            $quote_ids = $post_data['quote_id'];
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
        //    echo "lead";die;


        //pdf generator
        $data = $this->quote_model->getContractPDFDetails($contract_note_id);
            
        $data['cnote_date'] =  format_date($data['contract_note']['created_time']);
        $data['tax_type']   =   tax_type($data['cnote_date']);
        // Get Quote Details
        $data['quotes'] = $this->quote_model->getQuotesByCNoteID($contract_note_id);
        $data['quote_format_type']   =   quote_format_type($data['cnote_date']);


        // added on 03-02-2021
        $quote_approval_history = $this->quote_model->getCNoteQuoteApprovalHistory($contract_note_id);
        $approval_history = array();
        if($quote_approval_history)
        {
            foreach ($quote_approval_history as $hrow) {
                $approval_history[$hrow['quote_id']][$hrow['opportunity_id']][$hrow['approved_by']] = $hrow['approval_by'];
            }
        }

        $data['approval_history'] = $approval_history;
        // end


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
        $rev = getQuoteRevisionID($data['quotes'][0]['quote_id']);
        $data['rev'] = $rev;
        $cnote = $this->Common_model->get_data_row('contract_note',array('contract_note_id'=>$contract_note_id));
        $data['cnote_data'] = $this->Common_model->get_data_row('contract_note',array('contract_note_id'=>$contract_note_id));


        if(is_numeric($cnote['billing_to_party'])){
            $shipToParty = $this->quote_model->getShipToPartyDetails($cnote['billing_to_party']);
            // echo '<pre>'; print_r($shipToParty);die;
        }else{
            $shipToParty['name'] = $cnote['billing_to_party'];
            // print_r($shipToParty);die;
        }
        $data['product_details'] = $this->quote_model->getCNoteProductDetails($contract_note_id);
        // echo '<pre>'; print_r($data['product_details']);die;
        $data['dist_row'] = array();
        if(@$data['quotes'][0]['dealer_id']>0)
        {
            $data['dist_row'] = getDistributorDetails(@$data['quotes'][0]['dealer_id']);
        }
        $data['shipToParty'] = $shipToParty;


        $lead_id = $data['lead_id_val'];            
        $customerSAPCode = getCustomerSAPCode($lead_id);
        $quote_content = $this->load->view('quote/'.$view_file, $data, true);
        // echo $quote_content; die;
        $pdf = new Pdf('P', 'px', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('dejavusans', '', 8);
        $pdf->AddPage();
        $image1 = assets_url() . "images/skanray-logo.png";
        $pdf->writeHTML($quote_content, true, false, true, false, '0');
        $pdf_name=$customerSAPCode.$contract_note_id.date('MdYhis').".pdf";
        // $path = SITE_URL1.'downloads/';
        // echo $_SERVER['DOCUMENT_ROOT'].'/sabado-icrm/downloads/';
        //$pdf_save_path=FCPATH."downloads/";
        //$pdf_file_path=$pdf_save_path.$pdf_name;
        // echo $_SERVER['DOCUMENT_ROOT'].'/sabado-icrm/downloads/';die;
        
        // ob_end_clean();
        // $pdf->Output($pdf_file_path, 'F');

        // Added on 18-11-2021 for C-Note issue
        //ob_clean();
        //$pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'iCRM_mobile/downloads/'.$pdf_name, 'F');
        $pdf_save_path=FCPATH."application/downloads/";
        $pdf_file_path=$pdf_save_path.$pdf_name;
            // echo $_SERVER['DOCUMENT_ROOT'].'/sabado-icrm/downloads/';die;
        ob_end_clean();
        $pdf->Output($pdf_file_path, 'F');
        // Added on 18-11-2021 for C-Note issue end

        /*Email alert to customer */
        $email_data = $this->Common_model->get_value('user',array('user_id'=>$_SESSION['user_id']),'email_id');
        $user_name = $this->Common_model->get_data_row('user',array('user_id'=>$_SESSION['user_id']),'first_name');

        if($email_data != '')
        {
            $to = $email_data;
            //$cc= "CRM@skanray.com";
            //$encoded_id = icrm_encode($contract_note_id.'+'.$_SESSION['user_id']);
            $subject = 'Contract Note PDF File.';
            //$message = $email_data['message'];
            $message='Hi '.$user_name['first_name'].' '.',';
            $message .= '<p>Please Find Below Attachment for Contract Note PDF File.</p>';
            $message .= '<p>Regards,<br>iCRM,<br>Skanray</p>';
            $attachments=array();
            $attachments['contract_note'.$contract_note_id.'.pdf']=$pdf_file_path;
            //print_r( $pdf_file_path);die;
            //$mail->AddAttachment($docs, '', $encoding = 'base64', $type = 'application/pdf');
            send_email( $to,$subject, $message,$cc=null,$from='noreply@skanray-access.com',$from_name='Skanray ICRM', $bcc=NULL, $replyto=NULL,  $attachments);

            // send_email( $to,$subject, $message);
            // unlink($pdf_file_path);
            //return 1;
        }

        // echo "<pre>";print_r($message);die;
            $this->session->sess_destroy(); 
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $data1['response'] =0;
                $data1['error']='There\'s a problem occured while adding Contact Note!.';
                header("Status: 404 Not Found",true,404);
                goto finish;
                    
            }
            else
            {
                $this->db->trans_commit();
                $data1['response'] =1;
                $data1['error']='Contract Note has been Added successfully!';
                header("HTTP/1.1 200 OK");
                goto finish;
            }
            finish:
            echo json_encode($data1);
        }
    }
}