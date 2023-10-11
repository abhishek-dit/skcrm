<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Cron extends CI_controller
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Kolkata');
		$this->load->model('Common_model');
	}

	public function cron_daily($print_mail = 0)
	{

		//send_email('navee.naveen@gmail.com','Testing-Daily Cron','cron test - daily cron'.date('Y m d H:i:s'));


		define('PRINT_MAIL', $print_mail);

		reminder_orderConclusionDatePast();
		escalation1_orderConclusionDatePast();
		escalation2_orderConclusionDatePast();

		reminder_postDemoNotUpdated();
		escalation1_postDemoNotUpdated();
		escalation2_postDemoNotUpdated();

		reminder_postVisitNotUpdated();
		escalation1_postVisitNotUpdated();
		escalation2_postVisitNotUpdated();
	}

	public function cron_5minute($print_mail = 0)
	{

		define('PRINT_MAIL', $print_mail);

		//send_email('navee.naveen@gmail.com','Testing-5 minute Cron','cron test - 5 minute cron'.date('Y m d H:i:s'));

		if (date('H') == 7 && date('i') >= 0 && date('i') < 5) {
			//send_email('navee.naveen@gmail.com','Testing-Before daily cron','cron test - before daily cron'.date('Y m d H:i:s'));
			$this->cron_daily();
		}
		notification_leadApproval();
		notification_leadAssigned();
	}

	public function cron_test()
	{
		send_email('navee.naveen@gmail.com', 'Test', 'cron test' . date('Y m d H:i:s'));
	}

	public function cron_test1()
	{
		send_email('navee.naveen@gmail.com', 'Testing-direct cron', 'cron test - direct' . date('Y m d H:i:s'));
	}

	//punchout at the end of the day , if user is not punch out 
	public function force_punch_out()
	{
		$data = array(
			'end_time'     =>    date('Y-m-d H:i:s'),
			'modified_by'   =>    '1',
			'modified_time' =>    date('Y-m-d H:i:s')
		);
		$where = array('end_time' => '');
		$this->Common_model->update_data('punch_in', $data, array('end_time is null'));
	}

	//Deleting live location data until previous month
	public function delete_live_loc_records()
	{
		$delete_date = date('Y-m-d', strtotime(date('Y-m-01') . ' -1 MONTH'));
		$sql = 'delete from live_location where created_date <"' . $delete_date . '"';
		$this->db->query($sql);
	}

	//auto mail sending for opportunity and visit count status
	public function auto_mail_daily_status()
	{
		$CI = &get_instance();

		$end_date = date('Y-m-d', strtotime('-1 days'));
		$start_date = date("Y-m-01", strtotime($end_date));

		$month = date('m');
		$year = date('Y');
		$day = getOpportunityCategorizationDate();
		$hotDay = $year . "-" . $month . "-" . $day;
		//$hotDate = "2016-12-28";
		$warmDate = date('Y-m-d', strtotime($hotDay . " +1 month "));

		// get details 30days
		$start_date_thirty = date('Y-m-d', strtotime('-30 days'));
		$end_date_thirty = date('Y-m-d');
		$CI->db->select('SUM(o.required_quantity * p.dp) as quantity_dp');
		$CI->db->from('opportunity o');
		$CI->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id', 'left');
		$CI->db->join('product p', 'p.product_id = op.product_id', 'left');
		$CI->db->where('date(o.created_time)>=', $start_date);
		$CI->db->where('date(o.created_time)<=', $end_date);
		$CI->db->where('date(o.expected_order_conclusion) > ', $warmDate);

		$res = $CI->db->get();
		$opportunity_thirty_inlakhs = $res->row_array();

		// get 30 days opportunity in lakhs
		$thirty_day_total = 0;
		$thirty_day_total = valueInLakhs($opportunity_thirty_inlakhs['quantity_dp'], 2);

		// get details 60days
		$start_date_sixty = date('Y-m-d', strtotime('-60 days'));
		$end_date_sixty = date('Y-m-d');
		$CI->db->select('SUM(o.required_quantity * p.dp) as quantity_dp');
		$CI->db->from('opportunity o');
		$CI->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id', 'left');
		$CI->db->join('product p', 'p.product_id = op.product_id', 'left');
		$CI->db->where('date(o.created_time)>=', $start_date);
		$CI->db->where('date(o.created_time)<=', $end_date);
		$CI->db->where('date(o.expected_order_conclusion) <= ', $hotDay);

		$res = $CI->db->get();
		$opportunity_sixty_inlakhs = $res->row_array();

		// get 60 days opportunity in lakhs
		$sixty_day_total = 0;
		$sixty_day_total = valueInLakhs($opportunity_sixty_inlakhs['quantity_dp'], 2);

		// get details more then 60days
		$start_date_sixty = date('Y-m-d', strtotime('-60 days'));
		$CI->db->select('SUM(o.required_quantity * p.dp) as quantity_dp');
		$CI->db->from('opportunity o');
		$CI->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id', 'left');
		$CI->db->join('product p', 'p.product_id = op.product_id', 'left');
		$CI->db->where('date(o.created_time)>=', $start_date);
		$CI->db->where('date(o.created_time)<=', $end_date);
		$CI->db->where('date(o.expected_order_conclusion)>', $hotDay);
		$CI->db->where('date(o.expected_order_conclusion)<=', $warmDate);
		$res = $CI->db->get();
		$opportunity_morethen_sixty_inlakhs = $res->row_array();

		// get more then 60 days opportunity in lakhs
		$morethen_sixty_day_total = 0;
		$morethen_sixty_day_total = valueInLakhs($opportunity_morethen_sixty_inlakhs['quantity_dp'], 2);

		$grand_total = ($thirty_day_total + $sixty_day_total + $morethen_sixty_day_total);

		$tomorrow_date = date('Y-m-d', strtotime('0 days'));

		// checking Visit Details	
		$visit_cold_call = $this->Common_model->get_data('visit', array('purpose_id' => 1, 'date(start_date) =' => $tomorrow_date));
		$visit_cold_conference = $this->Common_model->get_data('visit', array('purpose_id' => 9, 'date(start_date) =' => $tomorrow_date));
		$visit_courtesy_call = $this->Common_model->get_data('visit', array('purpose_id' => 10, 'date(start_date) =' => $tomorrow_date));
		$visit_dealer = $this->Common_model->get_data('visit', array('purpose_id' => 7, 'date(start_date) =' => $tomorrow_date));
		$visit_demo = $this->Common_model->get_data('visit', array('purpose_id' => 5, 'date(start_date) =' => $tomorrow_date));
		$visit_negotiation = $this->Common_model->get_data('visit', array('purpose_id' => 3, 'date(start_date) =' => $tomorrow_date));
		$visit_order_follow_up = $this->Common_model->get_data('visit', array('purpose_id' => 2, 'date(start_date) =' => $tomorrow_date));
		$visit_payment_collection = $this->Common_model->get_data('visit', array('purpose_id' => 4, 'date(start_date) =' => $tomorrow_date));
		$visit_training = $this->Common_model->get_data('visit', array('purpose_id' => 8, 'date(start_date) =' => $tomorrow_date));

		$visits_details = array();
		$visits_details = array(
			'Cold Call' => count($visit_cold_call),
			'Conference' => count($visit_cold_conference),
			'Courtesy Call' => count($visit_courtesy_call),
			'Dealer' => count($visit_dealer),
			'Demo' => count($visit_demo),
			'Negotiation' => count($visit_negotiation),
			'Order Follow Up' => count($visit_order_follow_up),
			'Visit Payment Collection' => count($visit_payment_collection),
			'Training' => count($visit_training)

		);
		$random_array = array('0' => 'a', '1' => 'b', '2' => 'c', '3' => 'd', '4' => 'e', '5' => 'f', '6' => 'g', '7' => 'h', '8' => 'i');

		$firstarrayvalue = array();
		$secondarrayvalue = array();
		$i = 0;
		foreach ($visits_details as $key => $value) {
			if ($value > 0) {
				$firstarrayvalue[$key] = $value;
			}
			$i++;
		}
		$j = 0;
		foreach ($firstarrayvalue as $key => $valeuArray) {
			$secondarrayvalue[$key][$random_array[$j]] =  $valeuArray;
			$j++;
		}

		// Quote Generated
		$CI->db->select('l3.location as RegionName,CONCAT(`c`.`name`, " ", " (", `loc`.`location`, ")") as custName,count(*) as count');
		$CI->db->from('lead l');
		$CI->db->join('(select lead_id, count(*) as count_lead from visit group by lead_id) v', 'v.lead_id = l.lead_id', 'left');
		$CI->db->join('location loc', 'loc.location_id = l.location_id');
		$CI->db->join('customer c', 'c.customer_id = l.customer_id');
		$CI->db->join('location l1', 'l1.location_id = loc.parent_id', 'left');
		$CI->db->join('location l2', 'l2.location_id = l1.parent_id', 'left');
		$CI->db->join('location l3', 'l3.location_id = l2.parent_id', 'left');
		$CI->db->join('location l4', 'l4.location_id = l3.parent_id', 'left');
		$CI->db->where('date(l.created_time) >=', $start_date);
		$CI->db->where('date(l.created_time) <=', $end_date);
		$CI->db->where('l.status', 7);
		$CI->db->group_by('l3.location,c.name');
		$CI->db->order_by('l3.location');
		$res = $CI->db->get();
		$full_quote_details = $res->result_array();

		// get full_quote count
		$full_quote_count = 0;
		foreach ($full_quote_details as $value) {
			$full_quote_count += $value['count'];
		}


		$messagecontenttop = '<div style="background:#f1f2f3"><div style="background:#ffffff;width:600px;margin:0 auto;padding:15px;font-family:Arial"><p>Dear Sir,</p>';
		$messagecontenttop .= '<p>Please find the Daily Report for Sales team.</p>';
		$message .= '<table style="border-collapse: collapse;width:100%">
							<thead style="color:#ffffff; background-color:#6B9BCF;font-family:Arial;">
							<tr>
   							 	<td colspan="3" style="text-align:center;border: 1px solid #ccc;padding:8px 0"><b>Summary</b></td>
  							</tr>
								<tr>
									<th style="border: 1px solid #ccc;padding:8px 0">Sl No</th>
									<th style="border: 1px solid #ccc;padding:8px 0">Parameter</th>
									<th style="border: 1px solid #ccc;padding:8px 0">Value in Lakhs</th>
								</tr>
							</thead>
						<tbody>';
		$message .= '   
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">1</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">Opportunities Created</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . round($grand_total) . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">1a</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">Conclusion in 30 days</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . round($thirty_day_total) . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">1b</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">Conclusion in 60 days</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . round($sixty_day_total) . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">1c</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">Conclusion more than 60 days</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . round($morethen_sixty_day_total) . '</td>
						</tr>
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						</tr>
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">2</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">Visits as on  ' . $tomorrow_date . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						</tr>';
		if (count($secondarrayvalue) > 0) {
			foreach ($secondarrayvalue as $key => $visitValue) {
				foreach ($visitValue as $keyValue => $row) {
					$message .= '<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">2' . $keyValue . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $key . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $row . '</td>
						</tr>';
				}
			}
		} else {
			$message .= '<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						<td style="border: 1px solid #ccc;padding:8px 10px">No Records Found</td>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
				        </tr>';
		}
		$message .= '<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						</tr>
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">3</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">Quotations generated</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $full_quote_count . '</td>
						</tr>
						';


		$message .= '</tody></table><br><br>';

		// Opportunity Section Start
		$message .= '<table style="border-collapse: collapse;width:100%">
						<thead style="color:#ffffff; background-color:#6B9BCF;">
						<tr>
   							 <td colspan="6" style="text-align:center;border: 1px solid #ccc;padding:8px 0px"><b>Opportunity</b></td>
  						</tr>
						<tr>
   							 <td colspan="6" style="text-align:center;border: 1px solid #ccc;padding:8px 0px"><b>Value in Lakhs</b></td>
  						</tr>
						<tr>
						<th style="border: 1px solid #ccc;padding:8px 0">Sl No</th>
						<th style="border: 1px solid #ccc;padding:8px 0">Region</th>
						<th style="border: 1px solid #ccc;padding:8px 0">Less than 30 Days</th>
						<th style="border: 1px solid #ccc;padding:8px 0">30 - 60 Days</th>
						<th style="border: 1px solid #ccc;padding:8px 0">More than 60 Days</th>
						<th style="border: 1px solid #ccc;padding:8px 0">Grand Total</th>
						</tr>
						</thead>
						<tbody>';

		// getting region details
		$CI->db->select('l.location_id,l.location');
		$CI->db->from('location l');
		$CI->db->where('parent_id', 3);
		$CI->db->order_by('l.location');
		$res = $CI->db->get();
		$region_details = $res->result_array();
		$sno_opp = 1;
		$overall_opp_cold = 0;
		$overall_opp_hot = 0;
		$overall_opp_warm = 0;
		$grand_total_opportunity = 0;
		$overall_grand_total = 0;

		foreach ($region_details as $opprtunity_region) {

			//check region wise opportunity cold details
			$CI->db->select('SUM(o.required_quantity * p.dp) as quantity_dp');
			$CI->db->from('opportunity o');
			$CI->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id', 'left');
			$CI->db->join('product p', 'p.product_id = op.product_id', 'left');
			$CI->db->join('user u', 'u.user_id=o.created_by', 'left');
			$CI->db->join('branch b', 'b.branch_id=u.branch_id', 'left');
			$CI->db->join('location l', 'l.location_id=b.region_id', 'left');
			$CI->db->where('l.location_id', $opprtunity_region['location_id']);
			$CI->db->where('date(o.created_time)>=', $start_date);
			$CI->db->where('date(o.created_time)<=', $end_date);
			$CI->db->where('date(o.expected_order_conclusion) > ', $warmDate);


			$res = $CI->db->get();
			$opportunity_cold_details = $res->row_array();
			$opp_cold_count = valueInLakhs($opportunity_cold_details['quantity_dp'], 2);

			//check region wise opportunity hot details
			$CI->db->select('SUM(o.required_quantity * p.dp) as quantity_dp');
			$CI->db->from('opportunity o');
			$CI->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id', 'left');
			$CI->db->join('product p', 'p.product_id = op.product_id', 'left');
			$CI->db->join('user u', 'u.user_id=o.created_by', 'left');
			$CI->db->join('branch b', 'b.branch_id=u.branch_id', 'left');
			$CI->db->join('location l', 'l.location_id=b.region_id', 'left');
			$CI->db->where('date(o.created_time)>=', $start_date);
			$CI->db->where('date(o.created_time)<=', $end_date);
			$CI->db->where('l.location_id', $opprtunity_region['location_id']);
			$CI->db->where('date(o.expected_order_conclusion) <= ', $hotDay);
			$res = $CI->db->get();
			$opportunity_hot_details = $res->row_array();
			$opp_hot_count = valueInLakhs($opportunity_hot_details['quantity_dp'], 2);

			//check region wise opportunity warm details
			$CI->db->select('SUM(o.required_quantity * p.dp) as quantity_dp');
			$CI->db->from('opportunity o');
			$CI->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id', 'left');
			$CI->db->join('product p', 'p.product_id = op.product_id', 'left');
			$CI->db->join('user u', 'u.user_id=o.created_by', 'left');
			$CI->db->join('branch b', 'b.branch_id=u.branch_id', 'left');
			$CI->db->join('location l', 'l.location_id=b.region_id', 'left');
			$CI->db->where('l.location_id', $opprtunity_region['location_id']);
			$CI->db->where('date(o.created_time)>=', $start_date);
			$CI->db->where('date(o.created_time)<=', $end_date);
			$CI->db->where('date(o.expected_order_conclusion)>', $hotDay);
			$CI->db->where('date(o.expected_order_conclusion)<=', $warmDate);

			$res = $CI->db->get();
			$opportunity_warm_details = $res->row_array();
			$opp_warm_count = valueInLakhs($opportunity_warm_details['quantity_dp'], 2);

			$grand_total_opportunity = floatval($opp_cold_count) + floatval($opp_hot_count) + floatval($opp_warm_count);
			$overall_opp_cold += floatval($opp_cold_count);
			$overall_opp_hot += floatval($opp_hot_count);
			$overall_opp_warm += floatval($opp_warm_count);
			$overall_grand_total += floatval($grand_total_opportunity);
			$message .= '   
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $sno_opp . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $opprtunity_region['location'] . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . floatval($opp_cold_count) . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . floatval($opp_hot_count) . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . floatval($opp_warm_count) . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px"><b>' . floatval($grand_total_opportunity) . '</b></td>
						</tr>';
			$sno_opp++;
		}
		$message .= '   
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px;background:#ededed"></td>
						<td style="border: 1px solid #ccc;padding:8px 10px;background:#ededed"><b>Grand Total</b></td>
						<td style="border: 1px solid #ccc;padding:8px 10px;background:#ededed"><b>' . $overall_opp_cold . '</b></td>
						<td style="border: 1px solid #ccc;padding:8px 10px;background:#ededed"><b>' . $overall_opp_hot . '</b></td>
						<td style="border: 1px solid #ccc;padding:8px 10px;background:#ededed"><b>' . $overall_opp_warm . '</b></td>
						<td style="border: 1px solid #ccc;padding:8px 10px;background:#ededed"><b>' . $overall_grand_total . '</b></td>
						</tr>';
		$message .= '</tody></table><br><br>';

		// Quote Selection Start
		$message .= '<table style="border-collapse: collapse;width:100%">
						<thead style="color:#ffffff; background-color:#6B9BCF;border: 1px solid #ccc;">
						<tr>
   							 <td colspan="4" style="text-align:center;border: 1px solid #ccc;padding:8px 0px"><b>Quotes</b></td>
  						</tr>
						<tr>
						<th style="border: 1px solid #ccc;padding:8px 10px">Sl No</th>
						<th style="border: 1px solid #ccc;padding:8px 10px">Region</th>
						<th style="border: 1px solid #ccc;padding:8px 10px">Customer Name</th>
						<th style="border: 1px solid #ccc;padding:8px 10px">Quotation</th>
						</tr>
						</thead>
						<tbody>';

		$sno = 1;
		$quote_grand_total = 0;
		// getting quote count region wise
		$CI = &get_instance();
		$CI->db->select('l3.location as RegionName,CONCAT(`c`.`name`, " ", " (", `loc`.`location`, ")") as custName,count(*) as count');
		$CI->db->from('lead l');
		$CI->db->join('(select lead_id, count(*) as count_lead from visit group by lead_id) v', 'v.lead_id = l.lead_id', 'left');
		$CI->db->join('location loc', 'loc.location_id = l.location_id');
		$CI->db->join('customer c', 'c.customer_id = l.customer_id');
		$CI->db->join('location l1', 'l1.location_id = loc.parent_id', 'left');
		$CI->db->join('location l2', 'l2.location_id = l1.parent_id', 'left');
		$CI->db->join('location l3', 'l3.location_id = l2.parent_id', 'left');
		$CI->db->join('location l4', 'l4.location_id = l3.parent_id', 'left');

		$CI->db->where('date(l.created_time) >=', $start_date);
		$CI->db->where('date(l.created_time) <=', $end_date);
		$CI->db->where('l.status', 7);
		$CI->db->group_by('l3.location,c.name');
		$CI->db->order_by('l3.location');
		$res = $CI->db->get();
		$quote_details = $res->result_array();


		// echo"<pre>";print_r(array_keys($quote_details,'Central'));exit;
		// $user_details = $CI->Common_model->get_data_row('user',array('created_by'=> 1));
		if (count($quote_details) > 0) {
			foreach ($quote_details as $value) {
				// echo"<pre";print_r($quote_count_details);exit;
				$message .= '   
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $sno . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $value['RegionName'] . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $value['custName'] . '</td>
						<td style="border: 1px solid #ccc;padding:8px 10px">' . $value['count'] . '</td>
						</tr>';
				$sno++;
				$quote_grand_total += $value['count'];
			}
			$message .= '   
						<tr>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						<td style="border: 1px solid #ccc;padding:8px 10px"></td>
						<td style="border: 1px solid #ccc;padding:8px 10px"><b>Grand Total</b></td>
						<td style="border: 1px solid #ccc;padding:8px 10px"><b>' . $quote_grand_total . '</b></td>
						</tr>';
		} else {
			$message .= '   
					<tr>
					<td colspan="4" style="text-align:center;border: 1px solid #ccc;padding:8px 0px;font-weight:bold">No Records Found</td>
				  </tr>';
		}
		$message .= '</tody></table><br><br>';


		$messagecontentbottom .= '<p>Regards,</p>';
		$messagecontentbottom .= '<p>iCRM,<br>SkanRay</p></div></div>';
		$mailcontent = $messagecontenttop.$message.$messagecontentbottom;

		//excel export functionality
		$file = "daily_status_report_" . time() . ".xls";
		$_SESSION['daily_status_file'] = $file;
		// header("Content-type: application/vnd.ms-excel");
		// header("Content-Description: File Transfer");
		// header("Content-Disposition: attachment; filename=$file");
		file_put_contents('./uploads/daily_status_report/' . $file, $message);
		// echo $message;
		$subject = 'CRM Daily Report: '.$tomorrow_date;
		//get email id
		$to = array();
		// $email_address = $CI->Common_model->get_data('email', array('status' => 1));
		// foreach ($email_address as $key => $email) {
		// 	$to[] = $email['email_id'];
		// }
		$to = array_unique($this->config->item('cron_mail_check'));
		// get attachment 
		$file_path = FCPATH . "uploads/daily_status_report/";
		$attachment = array();
		$attachment[$file] = $file_path . $_SESSION['daily_status_file'];
		if (count($to) > 0) {
			$to_email = implode(',', $to);
			// send_email($to_email,$subject,$message);
			send_email($to_email, $subject, $mailcontent, $cc = null, $from = 'noreply@skanray-access.com', $from_name = 'Skanray ICRM', $bcc = NULL, $replyto = NULL,  $attachment);
		}
		unset($_SESSION['daily_status_file']);
	}

	// get the live location report place,street,city based on longitude,latitude
	public function get_live_location_details(){
		//get the stored users live location longitude,latitude
		$end_date = date('Y-m-d', strtotime('-90 days'));
		$start_date = date("Y-m-d", strtotime('0 days'));
		$this->db->select('ll.*,CONCAT(u.first_name," ",u.last_name) as lead_owner');
		$this->db->from('live_location ll');
		$this->db->join('user u','u.user_id=ll.user_id');
		$this->db->where('live_location_id NOT IN (SELECT live_location_id FROM live_location_report)');
		$this->db->where('date(ll.created_time)>=', $end_date);
		$this->db->where('date(ll.created_time)<=', $start_date );
		$res = $this->db->get();
		$result = $res->result_array();

		if(count($result) > 0){
			foreach($result as $row){
			$request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$row['latitude'].','.$row['longitude'].'&key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc'; 
			$file_contents = file_get_contents($request);
			$json_decode = json_decode($file_contents);

			if(isset($json_decode->results[0]->formatted_address) && !empty($json_decode->results[0]->formatted_address))
		{
			$address = $json_decode->results[0]->formatted_address;
		}
		else
		{
			$address = '';
		}

		$response = array();
		$response = explode(",",$address);

		if(isset($response[0])){ $first  =  $response[0];  } else { $first  = ''; }
		if(isset($response[1])){ $second =  $response[1];  } else { $second = ''; } 
		if(isset($response[2])){ $third  =  $response[2];  } else { $third  = ''; }
		if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = ''; }
		if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = ''; }

		if($second !='' || $third !='' || $fourth !='' || $fifth!= ''){
			$insert_array = array();
			$insert_array = array(
				 'live_location_id' =>$row['live_location_id'],
				 'user_id'=>$row['user_id'],
				 'latitude'     => $row['latitude'],
				 'longitude'    => $row['longitude'],
				 'created_time'=>$row['created_time'], 
				 'created_date'=>$row['created_date'],
				 'street' => $third,
				 'place' => $second,
				 'city' => $fourth.$fifth
				);
				$this->Common_model->insert_data('live_location_report',$insert_array);
		}
		}
		}
	}

}
