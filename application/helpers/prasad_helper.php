<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function po_opportunity_status($status)
{
	if($status==1)
	{
		$opp_status="Tagged";
	}
	elseif($status==2)
	{
		$opp_status="Untagged";
	}
	elseif($status==3)
	{
		$opp_status="Closed Won";
	}
	elseif($status==4)
	{
		$opp_status="Closed Lost";
	}
	return $opp_status;
}
function date_difference_two_days($created_date,$modified_date)
{
	//echo $created_date;exit;
	$days='';
	if($created_date!=''&& $modified_date!='')
	{
		$CI=& get_instance();
		$created_date1=date('Y-m-d',strtotime($created_date));
		$modified_date1=date('Y-m-d',strtotime($modified_date));
		$res=strtotime($modified_date1)-strtotime($created_date1);
		$days=$res/86400;
	}
		return $days;
}
function lead_opp_status($created_date,$modified_date,$status)
{
	$days='';
	if($status<=19)
	{
		    $created_date1=date('Y-m-d',strtotime($created_date));
			$modified_date1=date('Y-m-d');
			$res=strtotime($modified_date1)-strtotime($created_date1);
		    $days=$res/86400;
	}
	else
	{   
		if($modified_date!='')
		{
			$created_date1=date('Y-m-d',strtotime($created_date));
			$modified_date1=date('Y-m-d',strtotime($modified_date));
			$res=strtotime($modified_date1)-strtotime($created_date1);
		    $days=$res/86400;
		}
	}
	return $days;
}
function get_opp_life_time($oCTime,$opp_mtime,$status)
{   $days='';
	if($status<=5)
	{
		$created_date=date('Y-m-d',strtotime($oCTime));
		$modified_date=date('Y-m-d');
		$res=strtotime($modified_date)-strtotime($created_date);
	    $days=$res/86400;
	}
	else
	{   
		if($opp_mtime!='')
		{
			$created_date=date('Y-m-d',strtotime($oCTime));
			$modified_date=date('Y-m-d',strtotime($opp_mtime));
			$res=strtotime($modified_date)-strtotime($created_date);
		    $days=$res/86400;
		}
	}
	return $days;
}
function cnote_status_array()
{
	$status_array=array(
		'1'=>'Waiting at SO Entry',
		'2'=>'Completed',
		'3'=>'Waiting at Clear for invoice'
		);
	return $status_array;
}

// Get Month Name
function get_month_name($month_number)
{
	$time = mktime(0, 0, 0, $month_number);
	$name = strftime("%b", $time);
	return $name;
}
// To validate months format
function validateDate($date)
{
    $d = DateTime::createFromFormat('M-Y', $date);
    return $d && $d->format('M-Y') === $date;
}

// to get month number
function get_month_number($val)
{
	$month_arr=array('1'=>'JAN','2'=>'FEB','3'=>'MAR','4'=>'APR','5'=>'MAY','6'=>'JUN',
		             '7'=>'JUL','8'=>'AUG','9'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DEC');
	$res=array_search(strtolower($val), array_map('strtolower', $month_arr));
	if($res!='')
	{
		return $res;
	}
}
// To get months array
function get_upload_months()
{
	$months=array('JAN','FEB','MAR','APR','MAY','JUN',
		             'JUL','AUG','SEP','OCT','NOV','DEC');
	return $months;
}
function get_extra_warranty_cost($total_val,$dp_val,$warranty,$default_warranty)
{   
	//echo $dp_val.'hi';exit;
	if($warranty > 0)
	{
		$cost_of_warranty = get_preference('cost_of_maintaining_warranty','margin_settings'); 
		$f=$warranty/12; //= warranty_in_years
        $k=$cost_of_warranty; 
        $results=array();
        if($warranty > $default_warranty)
        {
        	$war_dis_value= $dp_val*pow((1+$k/100),($f-1))-$dp_val;
        }
        else{
        	$war_dis_value=0;
        }
        $results['war_dis_value']=round($war_dis_value);
        $results['grand_total']=round($total_val+$war_dis_value);
		return $results;
	}
	else
	{
		$results['grand_total']=round($total_val);
		return $results;
	}
}
function get_uploaded_file_data($upload_id)
{
	$ci=& get_instance();
	$ci->db->select();
	$ci->db->from('new_so_outstanding_amount');
	$ci->db->where('upload_id',$upload_id);
	$res=$ci->db->get();
	return $res->num_rows();
}
function upload_date_format($date,$format='M-Y')
{
	$timestamp = strtotime($date);
	return date($format,$timestamp);
}

function get_po_count_by_status($status)
{
    $CI=& get_instance();
    $start_date=date('Y-m-d', strtotime('-7 days'));
    $end_date=date('Y-m-d');
    $CI->db->select('count(*) as po_count');
    $CI->db->from('purchase_order');
    $CI->db->where('status',$status);
    $CI->db->where('date(modified_time)>=',$start_date);
    $CI->db->where('date(modified_time)<=',$end_date);
    $CI->db->where('user_id',$CI->session->userdata('user_id'));
    $CI->db->where('company_id',$CI->session->userdata('company'));
    $res=$CI->db->get();
    $row=$res->row_array();
    return ($row['po_count']>0)?$row['po_count']:0;
}

function margin_allowed_roles()
{
	$allowed_roles=array(1,2,3,8,9,10,11,12,13);
	return $allowed_roles;
}

function format_upload_amount($str_amount)
{
	if( strpos($str_amount, ',') !== false )
	{
		$amount= str_replace(',','',trim($str_amount));
		$latest_amount=round(trim($amount),2);
	}
	else
	{
		$latest_amount=$str_amount;
	}
	//$new_amount=round($amount,2);
	return $latest_amount;
}


function truncate_missed_record_tables()
{
	$ci=& get_instance();
	$tables=array('missing_product_files');
	foreach($tables as $key=>$value)
	{
		$ci->db->truncate($value);
	} 
}

function report_user_locations($searchfilters)
{   
	$CI= & get_instance();
	$CI->db->select('ul.user_id as users_id');
	$CI->db->from('location l1');
	$CI->db->join('location l2','l1.location_id=l2.parent_id');
	$CI->db->join('location l3','l2.location_id=l3.parent_id');
	$CI->db->join('location l4','l3.location_id=l4.parent_id');
	$CI->db->join('user_location ul','l1.location_id=ul.location_id or l2.location_id=ul.location_id or l3.location_id=ul.location_id or l4.location_id=ul.location_id');
	$CI->db->where('l1.location_id',$searchfilters['regions']);
	$CI->db->where('l1.territory_level_id',4);
	$CI->db->group_by('ul.user_id');
	$res=$CI->db->get();
	//echo $CI->db->last_query();exit;
	$users_id =$res->result_array();
	$user_arr = array();
	foreach ($users_id as $user) 
	{
		$user_arr[] = $user['users_id'];
	}
	$users_id=implode(',',$user_arr); 
	return $users_id;
}

function report_user_locations_by_region($loc)
{   
	$CI= & get_instance();
	$CI->db->select('ul.user_id as users_id');
	$CI->db->from('location l1');
	$CI->db->join('location l2','l1.location_id=l2.parent_id');
	$CI->db->join('location l3','l2.location_id=l3.parent_id');
	$CI->db->join('location l4','l3.location_id=l4.parent_id');
	$CI->db->join('user_location ul','l1.location_id=ul.location_id or l2.location_id=ul.location_id or l3.location_id=ul.location_id or l4.location_id=ul.location_id');
	$CI->db->join('user u','ul.user_id=u.user_id');
	$CI->db->where('u.company_id',$CI->session->userdata('company'));
	$CI->db->where('l1.location_id',$loc);
	$CI->db->where('l1.territory_level_id',4);
	$CI->db->group_by('ul.user_id');
	$res=$CI->db->get();
	$users_id =$res->result_array();
	$user_arr = array();
	foreach ($users_id as $user) 
	{
		$user_arr[] = $user['users_id'];
	}
	$users_id=implode(',',$user_arr); 
	return $users_id;
}
function open_order_status()
{
	$cn_status=array(
		0=>'Fresh not cleared',
		1=>'Fresh open orders cleared',
		2=>'Old not cleared',
		3=>'Old open orders cleared');
	return $cn_status;
}
function get_pro_name_by_id($product_id)
{
	$ci=& get_instance();
	$res=$ci->Common_model->get_value('product',array('product_id'=>$product_id),'description');
	return $res;
}
function update_closed_time_opportunity_status($opportunity_id)
{    
	$CI = & get_instance();
	$arr=array(
		'closed_time'=>date('Y-m-d H:i:s'),
	    'closed_by'=>$CI->session->userdata('user_id'));
	$CI->Common_model->update_data('opportunity',$arr,array('opportunity_id'=>$opportunity_id));
}
function clean($string) {
   return preg_replace("/[^0-9.]/", "", $string);
}
function get_current_unique_numbers($table,$column,$order_by)
{
	$CI = & get_instance();
	$company_id=$CI->session->userdata('company');
	$CI->db->select($column);
	$CI->db->from($table);
	$CI->db->where('company_id',$company_id);
	$CI->db->order_by($order_by,'DESC');
	$CI->db->limit(1);
	$res=$CI->db->get();
	$result=$res->row_array();
	$short_name = $CI->Common_model->get_value('company',array('company_id'=>$company_id),'short_name');

	if($result[$column]=='')
	{
		$num=1;
		return array($num,$short_name.$num);
	}
	else
	{   
		$num= $result[$column]+1;
		return array($num,$short_name.$num);
	}
}
function check_unique_numbers_constraint($table,$column,$data)
{
	$CI = & get_instance();
	$company_id=$CI->session->userdata('company');
	$CI->db->from($table);
	$CI->db->where('company_id',$company_id);
	$CI->db->where($column,$data);
	$res=$CI->db->get();
	if($res->num_rows()>0)
	{

		 throw new Exception("Value exists");
	}
	else
	{
		return true;
	}
	
}

function check_unique_numbers_constraints_exception($table,$column,$data)
{
	$CI = & get_instance();
	$company_id=$CI->session->userdata('company');
	$CI->db->from($table);
	$CI->db->where('company_id',$company_id);
	$CI->db->where($column,$data);
	$res=$CI->db->get();
	if($res->num_rows()>0)
	{
		return 0;
	}
	else
	{
		/*$prefix =$CI->Common_model->get_value('company',array('company_id'=>$company_id),'short_name');
		return $prefix.$data;*/
		return $data;
	}
	
}
 function get_customer_currency($customer_location,$product_id)
{
	$ci = & get_instance();
	$ci->db->select('l5.currency_id');
	$ci->db->from('location l1'); //city
	$ci->db->join('location l2','l1.parent_id = l2.location_id');//district
	$ci->db->join('location l3','l2.parent_id = l3.location_id');//state
	$ci->db->join('location l4','l3.parent_id = l4.location_id');//region
	$ci->db->join('location l5','l4.parent_id = l5.location_id');//country
	$ci->db->where('l1.location_id',$customer_location);
	$ci->db->where('l1.territory_level_id',7);
	$res=$ci->db->get();
	$loc= $res->row_array();
	//Fetching company default currency
	$default_currency = $ci->Common_model->get_value('company',array('company_id'=>$ci->session->userdata('company'),'status'=>1),'currency_id');
	if($loc['currency_id']==$default_currency)
	{
		return array(1,$loc['currency_id']);
	}
	else
	{
		return array(2,$loc['currency_id']);
	}

}
function get_channel_partner_details($quote_id)
{
	$ci = & get_instance();
	$ci->db->select('cp.*');
	$ci->db->from('quote q');
	$ci->db->join('channel_partner cp','q.channel_partner_id=cp.channel_partner_id');
	$ci->db->where('q.quote_id',$quote_id);
	$ci->db->where('q.company_id',$_SESSION['company']);
	$res= $ci->db->get();
	return $res->row_array();
}
function get_current_quote_number($quote_id)
{	
	$ci = & get_instance();
	$quote_number= $ci->Common_model->get_value('quote',array('quote_id'=>$quote_id,'company_id'=>$ci->session->userdata('company')),'quote_number');
	return $quote_number;
}
function get_opp_number($opp_id)
{
	$ci = & get_instance();
	$opp_number= $ci->Common_model->get_value('opportunity',array('opportunity_id'=>$opp_id),'opp_number');
	return $opp_number;
}
function get_quote_number($q_id)
{
	$ci = & get_instance();
	$quote_number= $ci->Common_model->get_value('quote',array('quote_id'=>$q_id),'quote_number');
	return $quote_number;
}
function get_quote_currency_details($quote_id)
{
	$ci = & get_instance();
	$ci->db->select('c.code');
	$ci->db->from('currency c');
	$ci->db->join('quote_details q','c.currency_id=q.currency_id');
	$ci->db->where('q.quote_id',$quote_id);
	$res = $ci->db->get();
	$result = $res->row_array();
	return $result['code'];
}
function get_quote_currency_forms($quote_id)
{
	$ci = & get_instance();
	$ci->db->select('c.name');
	$ci->db->from('currency c');
	$ci->db->join('quote_details q','c.currency_id=q.currency_id');
	$ci->db->where('q.quote_id',$quote_id);
	$res = $ci->db->get();
	$result = $res->row_array();
	return $result['name'];
}
function get_user_dist_country()
{
	$ci = & get_instance();
	$location= $ci->session->userdata('locationString');
	$ci = & get_instance();
	$ci->db->select('l5.currency_id,l5.location_id');
	$ci->db->from('location l1'); //city
	$ci->db->join('location l2','l1.parent_id = l2.location_id');//district
	$ci->db->join('location l3','l2.parent_id = l3.location_id');//state
	$ci->db->join('location l4','l3.parent_id = l4.location_id');//region
	$ci->db->join('location l5','l4.parent_id = l5.location_id');//country
	$ci->db->where('l1.location_id IN ('.$location.')');
	$ci->db->where('l1.territory_level_id',7);
	$res=$ci->db->get();
	$loc= $res->row_array();
	//Fetching company default currency
	$default_currency = $ci->Common_model->get_value('company',array('company_id'=>$ci->session->userdata('company'),'status'=>1),'currency_id');
	if($loc['currency_id']==$default_currency)
	{
		return array(1,$loc['location_id'],$loc['currency_id']);
	}
	else
	{   
        $check_products = getUserProductsBySegmentCheckCurrencys($loc['currency_id']);
        if(count($check_products)>0)
        {
			return array(2,$loc['location_id'],$loc['currency_id']);
		}
		else
		{
			return array(1,$loc['location_id'],$default_currency);
		}
	}
}
function getUserProductsBySegmentCheckCurrencys($currency_id)
{  
	$ci = & get_instance();
	$user_id=$ci->session->userdata('user_id');
	$ci->db->select('p.product_id,CONCAT(p.name," (",p.description,")") as product, pc.dp as unit_price');
	$ci->db->from('user u');
	$ci->db->join('user_product up','u.user_id=up.user_id');
	$ci->db->join('product_currency pc','up.product_id=pc.product_id');
	$ci->db->join('product p','pc.product_id=p.product_id');
	$ci->db->where('up.status',1);
	$ci->db->where('p.availability',1);
	$ci->db->where('u.user_id',$user_id);
	$ci->db->where('pc.currency_id',$currency_id);
	$ci->db->where('p.company_id',$ci->session->userdata('company'));
	$res = $ci->db->get();
    return $res->result_array();
}
function getFinalValueAfterConversion($mrp,$currency_id)
{   
	$CI=& get_instance();
	$default_currency = $CI->Common_model->get_value('company',array('company_id'=>$CI->session->userdata('company'),'status'=>1),'currency_id');
	if($default_currency==$currency_id)
	{
		return array($mrp,1);
	}
	else
	{
		$factor=$CI->Common_model->get_value('currency_transaction',array('from_currency_id'=>$currency_id,'to_currency_id'=>$default_currency,'status'=>1),'value');
		if($factor=='')
		{
			return array(0,0);
		}
		else
		{
			$total_value=$mrp*$factor;
			return array($total_value,$factor);
		}
	}
}

function getProbabilityBar_api($opportunity_id)
{
	$probability = getProbabilityForOpportunity($opportunity_id);
	//$probability = 70;
	$bar = ($probability < 30)?'danger':(($probability < 65)?'warning':'success');
	//$bar = 'danger';
	$data['bar'] = $bar;
	$data['probability'] = $probability;
	/*$ret = '<div class="progress progress-striped active">
                <div class="progress-bar progress-bar-'.$bar.'"  data-toggle="tooltip" title="'.$probability.'%" style=" width: '.$probability.'%">'.$probability.'%</div>
			</div>';*/
	return $data;

}

function getOpStatusBar_api($status = 0, $stage = '')
{
	$data['bar'] = '';
	if($status == 6 || $status == 7 || $status == 8)
	{
		$bar = ($status == 6)?'success':(($status == 7)?'danger':'warning');
		/*$ret = '<div class="progress progress-striped active">
                <div class="progress-bar progress-bar-'.$bar.'"  data-toggle="tooltip" title="'.$stage.'" style=" width: 100%">'.$stage.'</div>
			</div>';	*/
		$data['bar'] = $bar;
		$data['stage'] = $stage;	
	}
	return $data;
}
function get_opportunities_in_quote($lead_id,$quote_id,$quote_revision_id)
{
	$CI=& get_instance();
	$CI->load->model("quote_model");
	$quoteResults = $CI->quote_model->getQuoteDetailsByLead_api($lead_id,$quote_id,$quote_revision_id);
	//print_r($quoteResults);
	$quoteSearch = array();
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
        foreach ($opportunities_arr as $row)
        {
            $quote_arr['opportunities'][] = $row['opportunity'];

        }
    }
    if(count($quote_arr)>0)
    {
    	$opportunities = implode(',', $quote_arr['opportunities']);
    }
    else
    {
    	$opportunities = array();
    }

    return $opportunities;
}

function quotation_pdf($qid,$user_id,$quote_number) 
{
	$CI=& get_instance();
    $quote_revision_id = $qid;
    $CI->load->model("quote_model");
    $CI->load->library('Pdf');
    $CI->load->library('numbertowords');
    $CI->load->library('user_agent');
    $data['quotation'] = $CI->quote_model->get_quote_details1($quote_revision_id);
    $quotation_id = $data['quotation']['quotation_id'];
    $quote_id = $data['quotation']['quotation_id'];
    $data['quote_id'] = $quote_id;
    $data['quote_revision_number'] = getQuoteRevisionNumber($quotation_id,$quote_revision_id);
    $data['free_supply_items'] = $CI->quote_model->getQuoteRevisionFreeSupplyItems($quote_revision_id);
    $data['quote_info'] = $CI->Common_model->get_data_row('quote',array('quote_id'=>$quotation_id));
    $data['company_label'] = $CI->quote_model->get_company_lable_details($quotation_id);
    $data['quote_date'] =  format_date($data['quotation']['created_time']);
    $data['tax_type']   =   tax_type($data['quote_date']);
    $data['quote_format_type'] = getQuoteFormatTypeByQuoteRevisionID($quote_revision_id);
    $data['quote_revision_id'] = $quote_revision_id;
    $data['table_width']="1550";
    switch ($data['quote_format_type']) {
        case 1: // Old Format
            $quote_view_file = 'quotation_view1';
        break;
        
        case 2:
            $quote_view_file = 'quotation_pdf';
        break;
    }
    $quote_content = $CI->load->view('quote/'.$quote_view_file, $data, true);
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
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetFont('dejavusans', '', 9);

    // add a page
    $pdf->AddPage();
    $image1 = assets_url() . "images/skanray-logo.png";
    
    $pdf->writeHTML($quote_content, true, false, true, false, '0');
    //$pdf_name="Skanray Quote-".$ref."_".date('M-d-Y_h:i:s').".pdf";
    $pdf_name = 'Skanray_Quotation.pdf';
    $pdf_save_path=FCPATH.'downloads';
    $pdf_file_path=$pdf_save_path.$pdf_name;
    //echo $pdf_file_path; exit;
    $pdf->Output('C:\xampp\htdocs\sabado-icrm\downloads\Skanray_Quotation.pdf', 'F');
    $docs=array();
    $docs['Skanray_quotation_'.$quote_number.'.pdf']='C:\xampp\htdocs\sabado-icrm\downloads\Skanray_Quotation.pdf';

    $to = $CI->Common_model->get_value('user',array('user_id'=>$user_id),'email_id');
    $subject = 'Quotation PDF file';
    $body = 'Please Find Below Attachment for Quotation PDF File';
    $cc = NULL;
    $from = 'noreply@skanray-access.com';
    $from_name='Skanray ICRM';
    $bcc=NULL;
    $replyto=NULL;
    send_email( $to,$subject, $body,$cc=NULL,$from='noreply@skanray-access.com',$from_name='Skanray ICRM', $bcc=NULL, $replyto=NULL,  $docs);
    unlink('C:\xampp\htdocs\sabado-icrm\downloads\Skanray_Quotation.pdf');
    return 1;
}
function get_cnote_customer_data($contract_note_id,$lead_id)
{
   $ci = & get_instance();
   $lead_details = $ci->Contract_model->get_lead_details($lead_id);
   $output = array('subject'=>'','message'=>'','email_id'=>'');
   if($lead_details['email']!='')
   {    
	    $cnote_number = $ci->Common_model->get_value('contract_note',array('contract_note_id'=>$contract_note_id),'cnote_number');
		$subject = $lead_details['customer_name'].': Contract Note Approval Request from '.$lead_details['owner_name'];
		$message = '';
		$message .= '<p>Dear  '.$lead_details['contact_name'].'<br>'; 
		$message .= '<p>Contract Note has been successfully generated with Cnote Number : '.$cnote_number.'.</p>';
		$message .= '<p> Please click on the approve button to proceed for further steps. </p>';
		$message .= '<br><table border="0">
		<tr>
			<td style="background-color: green;border-color: green;border: 2px solid green;border-radius:5px;padding: 10px;text-align: center;">
				<a href="'.SITE_URL.'cnoteApprovalAction/1/{ENCODED_ID}" style="display: block;color: #ffffff;font-size: 15px;text-decoration: none;">APPROVE CNote</a>
			</td>
			<td width="70"></td>
			<td style="background-color: red;border-color: red;border: 2px solid red;border-radius:5px;padding: 10px;text-align: center;">
				<a href="'.SITE_URL.'cnoteApprovalAction/2/{ENCODED_ID}" style="display: block;color: #ffffff;font-size: 15px;text-decoration: none;">REJECT CNote</a>
			</td>
		</tr>
	</table>';

     $message .= '<p>Regards,<br>iCRM,<br>Skanray</p>';
	 $output = array('subject'=>$subject,'message'=>$message,'email_id'=>$lead_details['email']);
	}
	return $output;	 
}
function getToplevelUsers()
{
	$ci = & get_instance();
	$ci->db->select('group_concat(user_id) as user_ids');
	$ci->db->from('user');
	$ci->db->where('role_id IN(8,9,10,11)');
	$ci->db->where('company_id',$ci->session->userdata('company'));
	$res = $ci->db->get();
	$res1 = $res->row_array();
	return $res1['user_ids'];
}
function trim_ck_editor_data($value)
{
        $res3 = '';
        if($value!='')
        {
                $res = trim(strip_tags($value));
        //      $res1 = preg_replace('/-/', " ", $res);
                $res2 = preg_replace('/[\r\n]+/', "\n", $res);
                $res3 = preg_replace('/[ \t]+/', ' ',$res2);
        }
        return $res3;
}
