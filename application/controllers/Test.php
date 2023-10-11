<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Test extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		//$this->load->model("AdminModel");
		
	}

	public function index(){

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

	public function getClosedLeadFunnel()
	{
		$this->db->select('o.opportunity_id,o.status as opportunity_status,l.lead_id,l.status as lead_status, lsh.created_time as closed_time,l.user_id,
			concat(u.first_name," ",u.last_name) as user, l4.location as region, c.name as customer, DATE(lsh.created_time) as closed_date');
		$this->db->from('opportunity o');
		$this->db->join('lead l','l.lead_id = o.lead_id','inner');
		$this->db->join('user u','l.user_id = u.user_id');
		$this->db->join('lead_status_history lsh','l.lead_id = lsh.lead_id AND l.status = lsh.status','inner');
		$this->db->join('location l1','l.location_id = l1.location_id');
		$this->db->join('location l2','l2.location_id = l1.parent_id');
		$this->db->join('location l3','l3.location_id = l2.parent_id');
		$this->db->join('location l4','l4.location_id = l3.parent_id');
		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->where('l.status in (21,22)'); // leads closed or dropped
		$this->db->where('o.status < 6'); //open opportunities
		$this->db->order_by('l.lead_id');	
		$res = $this->db->get();
		$results = $res->result_array();
		//echo count($results);
		//echo '<pre>'; print_r($results); echo '</pre>';
		if($results)
		{
			$titles = array('Lead ID','Customer','Owner','Region','Opportunity ID','Lead Status','Closed Date');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			$drop_status = 8;
			$remarks = 'Auto dropped due to closing lead';
			foreach ($results as $op_row) {
				// Drop open opportunity
				$where2 = array('opportunity_id' => $op_row['opportunity_id']);
				$data2 = array('status' => $drop_status,
							'remarks2'	=> $remarks,
							'modified_by' => $op_row['user_id'], 
							'modified_time' => $op_row['closed_time'],
							'closed_by'		=>	$op_row['user_id'], 
							'closed_time' => $op_row['closed_time']
							);
				$this->Common_model->update_data('opportunity',$data2, $where2);
				$data3 = array('status' => $drop_status,
							'opportunity_id'	=> $op_row['opportunity_id'],
							'created_by' => $op_row['user_id'], 
							'created_time' => $op_row['closed_time']
							);
				$this->Common_model->insert_data('opportunity_status_history',$data3);
				$status = '';
				if($op_row['lead_status']==21)
					$status = 'Dropped';
				if($op_row['lead_status']==22)
					$status = 'Closed';
				$data.='<tr>';
				$data.='<td valign="top">'.@$op_row['lead_id'].'</td>';
				$data.='<td valign="top">'.@$op_row['customer'].'</td>';
				$data.='<td valign="top">'.@$op_row['user'].'</td>';
				$data.='<td valign="top">'.@$op_row['region'].'</td>';
				$data.='<td valign="top">'.@$op_row['opportunity_id'].'</td>';
				$data.='<td valign="top">'.@$status.'</td>';
				$data.='<td valign="top">'.@$op_row['closed_date'].'</td>';
				$data.='</tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$file_name = 'closedLeadsFunnel';
			$xlFile=$file_name.'_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
		}
	}
	
	function php_info()
	{
		echo phpinfo();
	}
}