<?php 
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Headers: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quote_api extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model("Common_model");
        $this->load->model("quote_model");
        $this->load->model("ajax_model");
        $this->load->model("MarginAnalysis_model");
        $this->load->library('global_functions');
    }

    public function openQuoteDetails()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $lead_id = $post_data['lead_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $leadStatus = getLeadStatusID($lead_id);
        $quoteSearch = array();
        $quoteResults = $this->quote_model->getQuoteDetailsByLead($lead_id);
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

        if($quoteResults)
        {

            foreach ($quoteResults as $row) {
                $quoteSearch[$row['quote_revision_id']][] = $row;
            }
        }
        $i=0;
        $quote_arr = array();
        foreach (@$quoteSearch as $quote_revision_id => $opportunities_arr)
        {
            $j=0;
            $count = count($opportunities_arr);

            foreach ($opportunities_arr as $row)
            {
                $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id);
                $quote_arr[$i]['quote_number'] = getQuoteRevisionReferenceID($lead_id,$row['quote_id'], @$quote_revision_id,$row['quote_number']);
                
                $quote_arr[$i]['quote_revision'] = 0;
                $quote_arr[$i]['quote_id'] = $row['quote_id'];
                $quote_arr[$i]['quote_revision_id'] = $row['quote_revision_id'];
                $quote_arr[$i]['lead_id'] = $lead_id;

                $status_format = ($quote_format_type==1||$row['quote_revision_status']==1)?1:2;
                switch ($status_format) 
                {
                    case 1: // Quote Status
                        

                            $quote_arr[$i]['status'] = ($row['quote_revision_status']==3)?'Waiting for approval':getQuoteStatus(@$row['status']); 
                    break;
                    case 2: // Individual opp status
                            $quote_arr[$i]['opportunity'][$j]['status'] = getQuoteApprovalStatusLabel(@$row['approval_status']);
                    break;
                }
                if($count == 1)
                {
                    $quote_arr[$i]['opportunity'][$j]['final_approver']  = getRoleShortName(@$row['close_at']); 
                }
                else
                {
                    $quote_arr[$i]['opportunity'][$j]['final_approver'] = getRoleShortName(@$row['close_at']); 
                }
                
                if($lead_user_id == $this->session->userdata('user_id') && $leadStatus != 19) 
                {
                    if(@$row['status'] == 1 || @$row['status'] == 2 || @$row['status'] == 10) 
                    {
                        $quote_arr[$i]['quote_revision'] = 1;
                    }
                }

                if($count==1)
                {
                    $quote_arr[$i]['opportunity'][$j]['opportunities'] = $row['opportunity'];

                }
                else
                {
                    $quote_arr[$i]['opportunity'][$j]['opportunities'] = $row['opportunity'];
                }

                switch ($quote_format_type) 
                {
                    case 1: // Old Format
                        if($count == 1)
                        {
                            $quote_arr[$i]['opportunity'][$j]['discount'] = round(@$row['discount'],2);
                        }
                        else
                        {
                            $quote_arr[$i]['opportunity'][$j]['discount'] = round(@$row['discount'],2);
                        }
                    break;
                    case 2: // New Format
                       
                        $cost = round($row['mrp']*$row['required_quantity']);
                        $discount = ($row['opp_discount_type']==1)?$row['opp_discount']:round(($row['opp_discount']/$cost)*100,2);
                        if($count == 1)
                        {
                            $quote_arr[$i]['opportunity'][$j]['discount'] = round($discount,2);
                        }
                        else
                        {
                            $quote_arr[$i]['opportunity'][$j]['discount'] = round(@$row['discount'],2);
                        }
                    break;
                }
                if($count == 1)
                {
                    $quote_arr[$i]['opportunity'][$j]['current_stage'] = getRoleShortName(@$row['approval_at']); 
                }
                else
                {
                    $quote_arr[$i]['opportunity'][$j]['current_stage'] = getRoleShortName(@$row['approval_at']); 
                }

                $j++;
            }
            $i++;
        }//exit;
        $data['quoteSearch'] = @$quote_arr;
        $data['opportunities'] = $this->quote_model->get_opportunities($lead_id);
        $data['productCategories'] = $this->quote_model->getProductCategories($lead_id);

        

        $data['channel_partners']=$this->Common_model->get_data('channel_partner',array('company_id'=>$this->session->userdata('company'),'status'=>1));
        $billing_name = $this->Common_model->get_dropdown("billing", "billing_info_id", "name", $where_con);
        foreach($billing_name as $key=>$value)
        {
            $billing_arr[] = array('id'=>$key,'name'=>$value);
        }
        $data['billing_name'] = $billing_arr;
        $customer = getCustomerByLead($lead_id);
        $data['customer_name'] = $customer['name'];
        $data['lead_id'] = $lead_id;
        //$data['products'] = $this->Common_model->get_data('product',array('status'=>1));
        $discount_types = get_advance_types();
        foreach($discount_types as $key=>$value)
        {
            $discount_arr[] = array('id'=>$key,'name'=>$value);
        }
        $data['discount_types'] = $discount_arr;
        $data['dealers']    =   $this->quote_model->getDistributors();
        $sql = "select u.user_id, d.distributor_name, employee_id from user u "
                . "JOIN distributor_details d ON d.user_id=u.user_id "
                . " WHERE u.role_id=12 and u.status=1 and u.company_id=".$this->session->userdata('company');
        $query = $this->db->query($sql);
        $stock_res = $query->result_array();
        $stock_arr = array();
        foreach ($stock_res as $key => $value) 
        {
            $stock_arr[] = array('id'=>$value['user_id'],'name'=>$value['distributor_name']. ' ('.$value['employee_id'].')');
        }
        $data['stockist'] = $stock_arr;



        $this->session->sess_destroy();
        echo json_encode($data);

    }

    public function quoteAdd()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $opportunity_id = $post_data['opportunity_id'];
        $lead_id = $post_data['lead_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $this->db->trans_begin();
        $opportunities = implode(",", $opportunity_id);
        $opp_ids = $opportunity_id;
        $opp_number = array();
        foreach($opp_ids as $key=>$op_id)
        {
            $opp_number[] = $this->Common_model->get_value('opportunity',array('opportunity_id'=>$op_id),'opp_number');
        }
        $opportunity_number = implode(',', $opp_number);
        $oppCount = count($opportunity_id);
        
        $q = 'SELECT q.quote_id,q.quote_number from lead l
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
        $currency_count = $this->quote_model->check_opportunity_currency($opportunities);
        if($currency_count == 0)
        {
            $this->session->sess_destroy();
            $data['response_id'] = 0;
            $data['response'] = 'Selected Opportunities('.$opportunity_number.') must have same Currency!';
            echo json_encode($data); //exit();
            header("Status: 400 Bad Request",true,400); exit();
        }
        $currency_availablity_check = $this->quote_model->currency_availablity_check($opportunities);
        if($currency_availablity_check==0)
        {
            $this->session->sess_destroy();
            $data['response_id'] = 0;
            $data['response'] = 'Please Add Currency Conversion Factor in Currency Convertor Screen.Then Try Again !';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        if($quoteExist == 1)
        {
            $this->session->sess_destroy();
            $data['response_id'] = 0;
            $data['response'] = 'A quote with quote ID: '.$quote_number.' already exists for the selected Opportunities. Please add a revision to that instead!';
            echo json_encode($data); 
            header("Status: 400 Bad Request",true,400); exit;

        }
        else
        {
            goto quote_start;
            quote_start:
            $lead_str_arr = get_current_unique_numbers("quote","quote_counter","quote_id");
            $quote_counter=$lead_str_arr[0];
            $quote_number=$lead_str_arr[1];
            $channel_partner_id=$post_data['channel_partner_id'];
            $billing_id = $post_data['billing_name'];
            $dataArr2 = array(
                "billing_info_id" => $billing_id,
                "discount"        => $post_data['discount'],
                "created_by"      => $_SESSION['user_id'],
                "status"          => 1
            );
            /* Phase2 update: Inserting additional terms like warranty, advance, dealer commission etc
            ** Mahesh: 16th August 2017
            ** START
            **/
            $dataArr3 = array(
                "created_by"         => $this->session->userdata('user_id'),
                "billing_info_id"    => $billing_id,
                "channel_partner_id" => $channel_partner_id,
                "warranty"           => $post_data['warranty'],
                "advance_type"       => $post_data['advance_type'],
                "advance"            => $post_data['advance'],
                'company_id'         => $this->session->userdata('company')
                );
            $balance_payment_days = @$post_data['balance_payment_days'];
            if($balance_payment_days!='')
            {
                $dataArr3 ["balance_payment_days"]=  $balance_payment_days;
            }
            $dealer_commission = @$post_data['dealer_commission'];
            $dealer = @$post_data['dealer'];
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
            if ($billing_id == 3) $dataArr2['stockist_id'] = $post_data['stokist_id'];
            $dataArr3['status'] = getQuoteStatusByDiscount($post_data['discount']);
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
            $qr_dataArr ["warranty"]=  $post_data['warranty'];
            $qr_dataArr ["advance_type"]=  $post_data['advance_type'];
            $qr_dataArr ["advance"]=  $post_data['advance'];

            $balance_payment_days = @$post_data['balance_payment_days'];
            if($balance_payment_days!='')
            {
                $qr_dataArr ["balance_payment_days"]=  $balance_payment_days;
            }
            $dealer_commission = @$post_data['dealer_commission'];
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
            foreach ($opportunity_id as $op_id) 
            {
                $productDetails = getProductDetailsForOpprotunity($op_id);
                $getFinalValue = getFinalValueAfterConversion($productDetails['mrp'],$productDetails['currency_id']); 
                $total_converted_value= $getFinalValue[0];
                $currency_factor=$getFinalValue[1];
                $dataArr2 = array(
                    "quote_id"          => $quote_id,
                    "opportunity_id"    => $op_id,
                    "sub_category_id"   => $post_data['sub_category_id'][$op_id],
                    "mrp"               => $productDetails['mrp'],
                    "ed"                => $productDetails['ed'],
                    "vat"               => $productDetails['vat'],
                    "gst"               => $productDetails['gst'],
                    "freight_insurance" => $productDetails['freight_insurance'],
                    "currency_id"       => $productDetails['currency_id'],
                    'total_value'       => $total_converted_value,
                    'currency_factor'   => $currency_factor
                    );
                $quote_details_id = $this->Common_model->insert_data('quote_details', $dataArr2);
                addOpportunityStatusByQuote($op_id, $quote_status);

                //Margin Analysis auto approval
                $ma_data = array(
                                'quote_revision_id'   =>  $quote_revision_id,
                                'opportunity_id'       =>  $op_id,
                                'discount_type'        =>  1,
                                'discount'             =>  0,
                                'status'               =>  2
                                );
            

                $this->Common_model->insert_data('quote_op_margin_approval',$ma_data);
                $i++;
            }

            leadStatusUpdate($post_data['lead_id']);
            //quotation_pdf($quote_revision_id,$_SESSION['user_id'],$quote_number);
            
            $this->session->sess_destroy();
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $data['response_id'] = 0;
                $data['response'] = "There\'s a problem occured while adding Opportunity!";
                header("Status: 400 Bad Request",true,400);
                echo json_encode($data); exit();
                    
            }
            else
            {
                $this->db->trans_commit();
                $data['response_id'] = 1;
                $data['response'] = "Quote ".$quote_number." has been Added successfully!";
                header("Status: 201 Created");
                echo json_encode($data); exit();
            }
        }
    }

    public function viewQuote()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $lead_id = $post_data['lead_id'];
        $quote_id = $post_data['quote_id'];
        $quote_revision_id = $post_data['quote_revision_id'];
        $_SESSION['company'] = $post_data['company_id'];
        
        $data['opportunities'] = get_opportunities_in_quote($lead_id,$quote_id,$quote_revision_id);
        $data['quote_number'] = getQuoteReferenceID1($lead_id, $quote_id);
        $res = get_channel_partner_details($quote_id);
        $data['billing_through'] = $res['name'];
        $data['currency_code'] =  get_quote_currency_details($quote_id);
        $quoteRevisions = getQuoteRevisions($quote_id);
        $i=1; $current_waiting_revision = ''; $j=0;
        foreach($quoteRevisions as $row) 
        {
            if($i==count($quoteRevisions)&&($row['status']==3))
            {
                $current_waiting_revision = $row['quote_revision_id'];
            }

            $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($row['quote_revision_id']);
            switch ($quote_format_type) 
            {
                case 1: // Old Format
                    $discount = ($row['discount']);
                    $quote_price = getQuotePrice($row['quote_id'], $row['discount']);
                break;
                case 2: // New Format
                    
                    if($i==1)
                    {
                        $discount = 0;
                        $quote_price = getQuotePrice($row['quote_id'], $discount);
                    }
                    else
                    {
                        $qrow = getQuoteRevisionPrice($row['quote_revision_id']);
                        $quote_price = round($qrow['quote_price']);
                        $cost = round($qrow['cost']);
                        $discount_amt = ($cost-$quote_price);
                        $discount = ($discount_amt/$cost)*100;
                    }
                break;
            }
            $revision_arr[$j]['billing'] = $row['name'];
            $revision_arr[$j]['discount'] = round($discount,2);
            $revision_arr[$j]['total_price'] = indian_format_price($quote_price);
            $revision_arr[$j]['status'] = getQuoteRevisionStatusLabel($row['status'],$i);
            $i++; $j++;
        }
        $k=0;
        $data['quote_approval_list'] = array();
        if($current_waiting_revision!='')
        {
            $op_ma_results = getOpportunityMarginAnalysisStatus($current_waiting_revision);
            foreach ($op_ma_results as $op_ma_row) 
            {
                $quote_approval_list[$k]['opportunity_list'] = $op_ma_row['opportunity'];
                $quote_approval_list[$k]['disocunt'] = format_advance($op_ma_row['discount'],$op_ma_row['discount_type']);
                if($op_ma_row['status']==1&&$op_ma_row['approval_at']!='')
                {
                    $quote_approval_list[$k]['current_stage'] = 'At'.getRoleShortName($op_ma_row['approval_at']);
                }
                else
                {
                   $quote_approval_list[$k]['current_stage'] = ''; 
                }
                $quote_approval_list[$k]['status'] = getMarginAnalysisStatus($op_ma_row['status']); 
                if($op_ma_row['status']==3)
                {
                    $quote_approval_list[$k]['status'] = ' At '.getRoleShortName($op_ma_row['close_at']);
                }
                if($op_ma_row['close_at']!='')
                { 
                    $quote_approval_list[$k]['final_approver'] = getRoleShortName($op_ma_row['close_at']);
                }
                else
                {
                    $quote_approval_list[$k]['final_approver'] = '';
                }
                $k++;
            }
            $data['quote_approval_list'] = $quote_approval_list;
        }
        $data['revisions'] = $revision_arr;
        $this->session->sess_destroy();
        echo json_encode($data);
    }
    public function addQuoteRevision()
    {   
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        if($post_data['submitAddRevision'] != '')
        {
            $op_id = $post_data['op_id'];
            if(!$op_id)
            {
                $data1['response'] =0;
                $data1['error']='Please select at least one opportunity to revise the quote';
                header("Status: 404 Not Found",true,404);
                goto start;
            }

            $this->db->trans_begin();
            $_SESSION['user_id'] = $post_data['user_id'];
            $_SESSION['role_id'] = $post_data['role_id'];
            $quote_id = $post_data['quote_id'];
            $billing_id = $post_data['billing_name'];
            $advance = $post_data['advance'];
            if($advance=='') $advance = 0;
            $balance_payment_days = $post_data['balance_payment_days'];
            $dealer_commission = $post_data['dealer_commission'];
            $dealer = $post_data['dealer'];
            $warranty = $post_data['warranty'];
            $advance_type = $post_data['advance_type'];


            $prev_quote_revision_id = $post_data['prev_quote_revision_id'];
            $rev_op_id = @$post_data['rev_op_id'];
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
                $data1['response'] =0;
                $data1['error']='No Changes has been made';
                header("Status: 404 Not Found",true,404);
                goto start;
            }
            $rbhExisted = checkRbhExistByQuote($quote_id);
            $dataArr2 = array(
                "quote_id" => $quote_id,
                "billing_info_id" => $billing_id,
                // "warranty" => $warranty,
                "advance_type" => $advance_type,
                "advance" => $advance,
                "created_by" => $_SESSION['user_id']
            );
            if ($billing_id == 3) $dataArr2['stockist_id'] = $post_data['stokist_id'];
            if($balance_payment_days !='') $dataArr2['balance_payment_days'] = $balance_payment_days;
            // if($dealer_commission !='') $dataArr2['dealer_commission'] = $dealer_commission;
            if($dealer !='') $dataArr2['dealer_id'] = $dealer;

            $dataArr2['status'] = 3;
            $quotePresentStatus = getCurrentQuoteStatus($quote_id);
            updateOtherQuoteRevisionStatus($quote_id,3);


            $quote_revision_id = $this->Common_model->insert_data('quote_revision', $dataArr2);
            $quoteStatus = 6;
            
            $discount_type = $post_data['discount_type'];
            $discount = $post_data['discount'];
            
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
                                     'discount'             =>  @$disc_val,
                                     'warranty'             =>  $warranty[@$opportunity_id],
                                     'dealer_id'            =>  $dealer,
                                     'dealer_commission'    =>  $dealer_commission[@$opportunity_id]
                                     );

                    // Free supply items
                    if(in_array($opportunity_id, $rev_op_id))
                    {
                        $free_products_arr = @$post_data['product_id_'.$opportunity_id];
                        $free_qty_arr = @$post_data['qty_'.$opportunity_id];
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
                            $m_data = marginAnalysis($data);
                            $dp = $row['unit_dp'];
                            $variance_percentage = round(((($order_value/$row['required_quantity'])-$dp)/$dp)*100,2);
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
                                     'warranty'             =>  $warranty[@$opportunity_id],
                                     'dealer_id'            =>  $dealer,
                                     'dealer_commission'    =>  $dealer_commission[@$opportunity_id],
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
            $lead_id = $post_data['lead_id'];
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                // $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                //                     <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                //                     <div class="icon"><i class="fa fa-check"></i></div>
                //                     <strong>Error!</strong> There\'s a problem occured while adding a revision to Quote!
                //                  </div>');

                // redirect(SITE_URL.'openQuoteDetails/'.icrm_encode($lead_id));
                $data1['response'] =0;
                $data1['error']='There\'s a problem occured while adding a revision to Quote!';
                header("Status: 404 Not Found",true,404);
                goto start;
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
                                $to = $urow['email_id'];
                                //echo $urow['email_id'].'<br>';
                                $encoded_id = icrm_encode($orow['margin_approval_id'].'_'.$urow['user_id']);
                                $email_data = getQuoteApprovalEmailData($quote_revision_id,$quote_id,$orow['approval_at'],$orow['opportunity_id'],'');
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
                // $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                //                         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                //                         <div class="icon"><i class="fa fa-check"></i></div>
                //                         <strong>Success!</strong> New revision to Quote has been Added successfully!
                //                      </div>');
                // redirect(SITE_URL.'openQuoteDetails/'.icrm_encode($lead_id));
                $data1['response'] =1;
                $data1['error']='New revision to Quote has been Added successfully!';
                header("HTTP/1.1 200 OK");
                goto start;
            }
           
            start:
            $this->session->sess_destroy(); 
            echo json_encode($data1);
        }
    }
    public function quoteRevision() 
    {
        
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $quote_id = $post_data['quote_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        if ($quote_id=='') {
            redirect(SITE_URL . 'openLeads');
        }

        $lead = getLeadDetailsByQuote($quote_id);
        $data['opportunities'] = getQuoteOpportunities($quote_id);
        $data['leadStatus'] = $lead['status'];
        $data['channel_partner_id']  = $this->Common_model->get_value('quote',array('quote_id'=>$quote_id),'channel_partner_id');
        $data['row']  = $this->quote_model->getLatestRevisionDetails($quote_id);
        $margin_details = $this->Common_model->get_data('quote_op_margin_approval',array('quote_revision_id'=>$data['row']['quote_revision_id']));

        //print_r($margin_details); exit();
        $op_details = array();
        if($margin_details)
        {   $i=0;
            foreach ($margin_details as $mrow) {
                $op_details[$i] = $mrow;
                $i++;
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
        $data['billing_name'] = $this->Common_model->get_data("billing",$where_con);
        /*Fetching New Channel Partners */
        $data['channel_partners']=$this->Common_model->get_data('channel_partner',array('company_id'=>$this->session->userdata('company'),'status'=>1));
        /* Mahesh Phase2 Capture additional terms in quote start */
        $data['products'] = $this->Common_model->get_data('product',array('status'=>1,'company_id'=>$this->session->userdata('company')),array('product_id','name','description','mrp'));
        $currency_code = get_quote_currency_details($quote_id);
        $dt = get_advance_types($currency_code);
        foreach($dt as $key =>$value)
        {
            $dt_array[]=array('id'=>$key,'name'=>$value);
        }
        $data['discount_types'] = $dt_array;
        $data['dealers']    =   $this->quote_model->getDistributors();
        $data['free_supply_item_percentage'] = $this->Common_model->get_data('free_supply_item_percentage',array('item_id = 1'));
        /* Mahesh Phase2 Capture additional terms in quote END */        
        $this->session->sess_destroy(); 
        echo json_encode($data);
        
    }
    public function quote_tracking()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['role_id'] = $post_data['role_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $_SESSION['products'] = $post_data['products'];
        $_SESSION['company'] = $post_data['company_id'];
        $searchParams = array(
                'opportunity_details' => $post_data['opportunity_details'],
                'customer_name'       => $post_data['customer'],
                'quote_id'            => $post_data['quote_number']
            );

         $config = get_paginationConfig();

        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();

        
        $quoteSearch = array();
        $quoteResults = $this->MarginAnalysis_model->getQuotesList($current_offset, $config['per_page'],$searchParams);
       // echo $this->db->last_query(); exit;
        if($quoteResults)
        {

            foreach ($quoteResults as $row) {
                $quoteSearch[$row['quote_revision_id']][] = $row;
            }
        }
        $i=0;
        $quote_arr = array();
        foreach (@$quoteSearch as $quote_revision_id => $opportunities_arr)
        {
            $j=0;
            $count = count($opportunities_arr);

            foreach ($opportunities_arr as $row)
            {
                $quote_format_type = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id);
                $quote_arr[$i]['quote_number'] = $row['quote_number'].' Rev-'.getQuoteRevisionNumber($row['quote_id'],$row['quote_revision_id']);
                $quote_arr[$i]['customer'] = $row['customer_name'];
                
                $status_format = ($quote_format_type==1||$row['quote_revision_status']==1)?1:2;
                switch ($status_format) 
                {
                    case 1: // Quote Status
                        if($j==0)
                        {
                            $quote_arr[$i]['status'] = ($row['quote_revision_status']==3)?'Waiting for approval':getQuoteStatus(@$row['status']); 
                        }
                    break;
                    case 2: 
                        // Individual opp status
                        switch($row['quote_revision_status'])
                        {
                            case 2 : case 3: $quote_arr[$i]['opportunity'][$j]['status'] = getQuoteApprovalStatusLabel(@$row['approval_status']);
                            case 4:
                                    if($j == 0)
                                    {
                                        $quote_arr[$i]['opportunity'][$j]['status'] = 'Previous Quotes';
                                    }
                            break;
                        }
                    break;
                }
                if($count == 1)
                {
                    $quote_arr[$i]['opportunity'][$j]['final_approver']  = getRoleShortName(@$row['close_at']); 
                }
                else
                {
                    $quote_arr[$i]['opportunity'][$j]['final_approver'] = getRoleShortName(@$row['close_at']); 
                }
                
                

                if($count==1)
                {
                    $quote_arr[$i]['opportunity'][$j]['opportunities'] = $row['opportunity'];

                }
                else
                {
                    $quote_arr[$i]['opportunity'][$j]['opportunities'] = $row['opportunity'];
                }

                switch ($quote_format_type) 
                {
                    case 1: // Old Format
                        if($count == 1)
                        {
                            $quote_arr[$i]['opportunity'][$j]['discount'] = round(@$row['discount'],2).'%';
                        }
                        else
                        {
                            $quote_arr[$i]['opportunity'][$j]['discount'] = round(@$row['discount'],2).'%';
                        }
                    break;
                    case 2: // New Format
                       
                        $cost = round($row['mrp']);
                        $discount = ($row['opp_discount_type']==1)?$row['opp_discount']:round(($row['opp_discount']/$cost)*100,2);
                        $quote_arr[$i]['opportunity'][$j]['discount'] = round($discount,2).'%';
                    break;
                }
                if($count == 1)
                {
                    $quote_arr[$i]['opportunity'][$j]['current_stage'] = getRoleShortName(@$row['approval_at']); 
                }
                else
                {
                    $quote_arr[$i]['opportunity'][$j]['current_stage'] = getRoleShortName(@$row['approval_at']); 
                }

                $j++;
            }
            $i++;
        }
        //echo "<pre>"; print_r($quoteSearch); exit;
        $data['quoteSearch'] = @$quote_arr;
        $this->session->sess_destroy();
        echo json_encode($data);
    }


    public function freeSupplyItem(){
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $free_supply_item_percentage = $this->Common_model->get_data('free_supply_item_percentage',array('item_id = 1'));
        $products = $this->Common_model->get_data('product',array('status = 1'));
        $free_supply_items = array();
        // $post_data['mrp'] = 20000;
        foreach($products as $key){

            if($key['mrp'] < $post_data['dp']*$free_supply_item_percentage[0]['percentage']/100){
                $free_supply_items[] = $key;
            }
        }
        // $post_data['mrp'] = 20000;
        // $a = $post_data['mrp'].'*'.$free_supply_item_percentage[0]['percentage']."/100";
        // $this->db->from('product');
        // $this->db->where('mrp < ',$post_data['mrp'].'*'.$free_supply_item_percentage[0]['percentage'].'/100');

        // $res = $this->db->get();
        // echo '<pre>'; print_r($this->db->last_query());die;
		// $result =  $res->row_array();
        echo json_encode($free_supply_items);
    }
}