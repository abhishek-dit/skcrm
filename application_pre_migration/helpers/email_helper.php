<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Email order conclusion date past reminder to lead user
 * created by Mahesh on 16th july 2016, 6:30 PM
*/

function reminder_orderConclusionDatePast() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('opportunity o');
	$CI->db->join('lead l','l.lead_id = o.lead_id','inner');
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$CI->db->where('o.expected_order_conclusion<',date('Y-m-d'));
	$where = 'o.expected_order_conclusion = "'.date('Y-m-d').'" - INTERVAL 1 DAY ';
	$CI->db->where($where);
	$CI->db->where('o.status>=',1);
	$CI->db->where('o.status<=',5);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Reminder: Opportunity expected order conclusion date update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Order conclusion date update is required for the following opportunities </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Opportunity ID</th>
									<th style="border: 1px solid black;">Lead Details</th>
									<th style="border: 1px solid black;">Product</th>
									<th style="border: 1px solid black;">Order Conclusion Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = orderConclusionResultsByUser($row['user_id'],1);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['opportunity_id'].'</td>
							<td style="border: 1px solid black;">'.$row1['lead'].'</td>
							<td style="border: 1px solid black;">'.$row1['product'].'</td>
							<td style="border: 1px solid black;">'.$row1['expected_order_conclusion'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message;
		// sending email
		send_email($to,$subject,$message);

	}

}

/*
 * Email order conclusion date past Escalation1 to lead user, next level reporting managers
 * created by Mahesh on 16th july 2016, 08:13 PM
*/

function escalation1_orderConclusionDatePast() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('opportunity o');
	$CI->db->join('lead l','l.lead_id = o.lead_id','inner');
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$CI->db->where('o.expected_order_conclusion<',date('Y-m-d'));
	$where = 'o.expected_order_conclusion = "'.date('Y-m-d').'" - INTERVAL 2 DAY ';
	$CI->db->where($where);
	$CI->db->where('o.status>=',1);
	$CI->db->where('o.status<=',5);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Escalation1: Opportunity expected order conclusion date update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Order conclusion date update is required for the following opportunities </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Opportunity ID</th>
									<th style="border: 1px solid black;">Lead Details</th>
									<th style="border: 1px solid black;">Product</th>
									<th style="border: 1px solid black;">Order Conclusion Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = orderConclusionResultsByUser($row['user_id'],2);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['opportunity_id'].'</td>
							<td style="border: 1px solid black;">'.$row1['lead'].'</td>
							<td style="border: 1px solid black;">'.$row1['product'].'</td>
							<td style="border: 1px solid black;">'.$row1['expected_order_conclusion'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		$cc = getReporting($row['user_id'], 1);
		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message.'<br>'.$cc;
		// sending email
		send_email($to,$subject,$message, $cc);

	}

}

/*
 * Email order conclusion date past Escalation to lead user, next level reporting managers,next higher level reporting mangers
 * created by Mahesh on 16th july 2016, 08:19 PM
*/

function escalation2_orderConclusionDatePast() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('opportunity o');
	$CI->db->join('lead l','l.lead_id = o.lead_id','inner');
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$CI->db->where('o.expected_order_conclusion<',date('Y-m-d'));
	$where = 'o.expected_order_conclusion = "'.date('Y-m-d').'" - INTERVAL 4 DAY ';
	$CI->db->where($where);
	$CI->db->where('o.status>=',1);
	$CI->db->where('o.status<=',5);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Escalation2: Opportunity expected order conclusion date update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Order conclusion date update is required for the following opportunities </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Opportunity ID</th>
									<th style="border: 1px solid black;">Lead Details</th>
									<th style="border: 1px solid black;">Product</th>
									<th style="border: 1px solid black;">Order Conclusion Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = orderConclusionResultsByUser($row['user_id'],4);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['opportunity_id'].'</td>
							<td style="border: 1px solid black;">'.$row1['lead'].'</td>
							<td style="border: 1px solid black;">'.$row1['product'].'</td>
							<td style="border: 1px solid black;">'.$row1['expected_order_conclusion'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		$cc = getReporting($row['user_id'], 2);
		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message.'<br>'.$cc;
		// sending email
		send_email($to,$subject,$message, $cc);

	}

}

/*
 * Email if demo detail not updated after end date reminder to lead user
 * created by Mahesh on 16th july 2016, 08:22 PM
*/

function reminder_postDemoNotUpdated() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('demo d');
	$CI->db->join('opportunity o','o.opportunity_id = d.opportunity_id','inner');
	$CI->db->join('lead l','l.lead_id = o.lead_id','inner');
	$CI->db->where('d.end_date<',date('Y-m-d'));
	$CI->db->where('d.remarks2',NULL);
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$where = 'd.end_date = "'.date('Y-m-d').'" - INTERVAL 1 DAY ';
	$CI->db->where($where);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Reminder: Post Demo update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Post Demo update is required for the following opportunities </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Customer Name</th>
									<th style="border: 1px solid black;">Opportunity</th>
									<th style="border: 1px solid black;">Demo</th>
									<th style="border: 1px solid black;">Start Date</th>
									<th style="border: 1px solid black;">End Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = postDemoNotUpdatedResultsByUser($row['user_id'],1);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['CustomerName'].'</td>
							<td style="border: 1px solid black;">'.$row1['opportunity'].'</td>
							<td style="border: 1px solid black;">'.$row1['demo'].'</td>
							<td style="border: 1px solid black;">'.$row1['start_date'].'</td>
							<td style="border: 1px solid black;">'.$row1['end_date'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message;
		// sending email
		send_email($to,$subject,$message);

	}

}

/*
 * Email if demo detail not updated after end date Escalation to lead user, next level reporting mangers
 * created by Mahesh on 16th july 2016, 08:48 PM
*/

function escalation1_postDemoNotUpdated() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('demo d');
	$CI->db->join('opportunity o','o.opportunity_id = d.opportunity_id','inner');
	$CI->db->join('lead l','l.lead_id = o.lead_id','inner');
	$CI->db->where('d.end_date<',date('Y-m-d'));
	$CI->db->where('d.remarks2',NULL);
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$where = 'd.end_date = "'.date('Y-m-d').'" - INTERVAL 2 DAY ';
	$CI->db->where($where);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Escalation1: Post Demo update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Post Demo update is required for the following opportunities </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Customer Name</th>
									<th style="border: 1px solid black;">Opportunity</th>
									<th style="border: 1px solid black;">Demo</th>
									<th style="border: 1px solid black;">Start Date</th>
									<th style="border: 1px solid black;">End Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = postDemoNotUpdatedResultsByUser($row['user_id'],2);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['CustomerName'].'</td>
							<td style="border: 1px solid black;">'.$row1['opportunity'].'</td>
							<td style="border: 1px solid black;">'.$row1['demo'].'</td>
							<td style="border: 1px solid black;">'.$row1['start_date'].'</td>
							<td style="border: 1px solid black;">'.$row1['end_date'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';
		$cc = getReporting($row['user_id'], 1);
		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message.'<br>'.$cc;
		// sending email
		send_email($to,$subject,$message, $cc);

	}

}

/*
 * Email if demo detail not updated after end date Escalation2 to lead user, next level reporting mangers,next to next level reporting managers
 * created by Mahesh on 16th july 2016, 08:48 PM
*/

function escalation2_postDemoNotUpdated() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('demo d');
	$CI->db->join('opportunity o','o.opportunity_id = d.opportunity_id','inner');
	$CI->db->join('lead l','l.lead_id = o.lead_id','inner');
	$CI->db->where('d.end_date<',date('Y-m-d'));
	$CI->db->where('d.remarks2',NULL);
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$where = 'd.end_date = "'.date('Y-m-d').'" - INTERVAL 4 DAY ';
	$CI->db->where($where);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Escalation2: Post Demo update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Post Demo update is required for the following opportunities </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Customer Name</th>
									<th style="border: 1px solid black;">Opportunity</th>
									<th style="border: 1px solid black;">Demo</th>
									<th style="border: 1px solid black;">Start Date</th>
									<th style="border: 1px solid black;">End Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = postDemoNotUpdatedResultsByUser($row['user_id'],4);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['CustomerName'].'</td>
							<td style="border: 1px solid black;">'.$row1['opportunity'].'</td>
							<td style="border: 1px solid black;">'.$row1['demo'].'</td>
							<td style="border: 1px solid black;">'.$row1['start_date'].'</td>
							<td style="border: 1px solid black;">'.$row1['end_date'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		$cc = getReporting($row['user_id'], 2);
		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message.'<br>'.$cc;
		// sending email
		send_email($to,$subject,$message, $cc);

	}

}

/*
 * Email if visit detail not updated after end date reminder to lead user
 * created by Mahesh on 16th july 2016, 08:53 PM
*/

function reminder_postVisitNotUpdated() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('visit v');
	$CI->db->join('lead l','l.lead_id = v.lead_id','inner');
	$CI->db->where('l.user_id',$CI->session->userdata('user_id'));
	$CI->db->where('v.end_date<',date('Y-m-d'));
	$CI->db->where('v.remarks2',NULL);
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$where = 'v.end_date = "'.date('Y-m-d').'" - INTERVAL 1 DAY ';
	$CI->db->where($where);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Reminder: Post visit update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Post visit update is required for the following customer visits </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Customer Name</th>
									<th style="border: 1px solid black;">Purpose</th>
									<th style="border: 1px solid black;">Start Date</th>
									<th style="border: 1px solid black;">End Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = postVisitNotUpdatedResultsByUser($row['user_id'],1);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['CustomerName'].'</td>
							<td style="border: 1px solid black;">'.$row1['Purpose'].'</td>
							<td style="border: 1px solid black;">'.$row1['start_date'].'</td>
							<td style="border: 1px solid black;">'.$row1['end_date'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message;
		// sending email
		send_email($to,$subject,$message);

	}

}

/*
 * Email if visit detail not updated after end date Escalation1 to lead user, next level reporting manangers
 * created by Mahesh on 16th july 2016, 09:06 PM
*/

function escalation1_postVisitNotUpdated() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('visit v');
	$CI->db->join('lead l','l.lead_id = v.lead_id','inner');
	$CI->db->where('l.user_id',$CI->session->userdata('user_id'));
	$CI->db->where('v.end_date<',date('Y-m-d'));
	$CI->db->where('v.remarks2',NULL);
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$where = 'v.end_date = "'.date('Y-m-d').'" - INTERVAL 2 DAY ';
	$CI->db->where($where);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Escalation1: Post visit update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Post visit update is required for the following customer visits </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Customer Name</th>
									<th style="border: 1px solid black;">Purpose</th>
									<th style="border: 1px solid black;">Start Date</th>
									<th style="border: 1px solid black;">End Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = postVisitNotUpdatedResultsByUser($row['user_id'],2);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['CustomerName'].'</td>
							<td style="border: 1px solid black;">'.$row1['Purpose'].'</td>
							<td style="border: 1px solid black;">'.$row1['start_date'].'</td>
							<td style="border: 1px solid black;">'.$row1['end_date'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		$cc = getReporting($row['user_id'], 1);
		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message.'<br>'.$cc;
		// sending email
		send_email($to,$subject,$message, $cc);

	}

}

/*
 * Email if visit detail not updated after end date Escalation2 to lead user, next level reporting manangers and their reporting managers
 * created by Mahesh on 16th july 2016, 09:07 PM
*/

function escalation2_postVisitNotUpdated() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('visit v');
	$CI->db->join('lead l','l.lead_id = v.lead_id','inner');
	$CI->db->where('l.user_id',$CI->session->userdata('user_id'));
	$CI->db->where('v.end_date<',date('Y-m-d'));
	$CI->db->where('v.remarks2',NULL);
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$where = 'v.end_date = "'.date('Y-m-d').'" - INTERVAL 4 DAY ';
	$CI->db->where($where);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		$subject = 'Escalation2: Post visit update is required';
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Post visit update is required for the following customer visits </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Customer Name</th>
									<th style="border: 1px solid black;">Purpose</th>
									<th style="border: 1px solid black;">Start Date</th>
									<th style="border: 1px solid black;">End Date</th>
								</tr>
							</thead>
						<tbody>';
		$results = postVisitNotUpdatedResultsByUser($row['user_id'],4);
		foreach ($results as $row1) {
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['CustomerName'].'</td>
							<td style="border: 1px solid black;">'.$row1['Purpose'].'</td>
							<td style="border: 1px solid black;">'.$row1['start_date'].'</td>
							<td style="border: 1px solid black;">'.$row1['end_date'].'</td>
						</tr>';
		}
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		$cc = getReporting($row['user_id'], 2);
		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message.'<br>'.$cc;
		// sending email
		send_email($to,$subject,$message, $cc);

	}

}

/*
 * Email Notification to lead user when lead apporval
 * created by Mahesh on 17th july 2016, 01:15 PM
*/

function notification_leadApproval() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('lead l');
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$where = ' l.approved_time < NOW() AND l.approved_time > NOW() - INTERVAL 5 MINUTE ';
	$CI->db->where($where);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Please find the details below. </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Lead ID</th>
									<th style="border: 1px solid black;">Customer</th>
									<th style="border: 1px solid black;">Status</th>
								</tr>
							</thead>
						<tbody>';
		$results = email_getUserApprovedLeads($row['user_id']);
		$leads_str = ''; $i=0;
		foreach ($results as $row1) {
			if($i>0) $leads_str.=', ';
			$leads_str.=$row1['lead_id'];
			$l_status = ($row1['status'] == 20)? "Rejected":"Approved";
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['lead_id'].'</td>
							<td style="border: 1px solid black;">'.$row1['customer'].'</td>
							<td style="border: 1px solid black;">'.$l_status.'</td>
						</tr>';
			$i++;
		}
		if($i==1)
			$subject = 'Notification: Lead '.$leads_str.' status has been changed';
		else
			$subject = 'Notification: Leads '.$leads_str.' status has been changed';
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message;
		// sending email
		send_email($to,$subject,$message);

	}

}

/*
 * Email Notification to lead user when lead assigned
 * created by Mahesh on 17th july 2016, 02:52 PM
*/

function notification_leadAssigned() {
	$CI = & get_instance();
	$CI->db->select('u.email_id,concat(u.first_name," ",u.last_name) as user,u.user_id');
	$CI->db->from('lead l');
	$CI->db->join('user u','u.user_id = l.user_id','inner');
	$where = ' (CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_time ) ELSE (l.re_routed_time) END) < NOW() AND (CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_time ) ELSE (l.re_routed_time) END) > NOW() - INTERVAL 5 MINUTE ';
	//$where  = ' CASE WHEN (l.type=2 AND l.re_routed_time IS NULL) THEN (l.created_time ) ELSE (l.re_routed_time) END ';
	$CI->db->where($where);
	$CI->db->group_by('l.user_id');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	foreach ($query->result_array() as $row) {
		
		// send email reminder
		$to = @$row['email_id'];
		
		$message = '<p>Hi '.$row['user'].',</p>';
		$message .= '<p>Please find the details below. </p>';
		$message .= '<table style="border-collapse: collapse;">
							<thead style="color:#ffffff; background-color:#6B9BCF;">
								<tr>
									<th style="border: 1px solid black;">Lead ID</th>
									<th style="border: 1px solid black;">Customer</th>
									<th style="border: 1px solid black;">Assigned By</th>
								</tr>
							</thead>
						<tbody>';
		$results = email_getUserAssignedLeads($row['user_id']);
		$leads_str = ''; $i=0;
		foreach ($results as $row1) {
			if($i>0) $leads_str.=', ';
			$leads_str.=$row1['lead_id'];
			$message.= '<tr>
							<td style="border: 1px solid black;">'.$row1['lead_id'].'</td>
							<td style="border: 1px solid black;">'.$row1['customer'].'</td>
							<td style="border: 1px solid black;">'.$row1['assignedBy'].'</td>
						</tr>';
			$i++;
		}
		if($i==1)
			$subject = 'Notification: Lead '.$leads_str.' has been assigned';
		else
			$subject = 'Notification: Leads '.$leads_str.' have been assigned';
		$message .= '</tody>
					</table><br>';

		$message .= '<p>Regards,</p>';
		$message .= '<p>iCRM,<br>SkanRay</p>';

		if(@PRINT_MAIL==1)
		echo $to.'<br>'.$subject.'<br>'.$message;
		// sending email
		send_email($to,$subject,$message);

	}

	

}
/* file end: ./application/helpers/email_helper.php */
