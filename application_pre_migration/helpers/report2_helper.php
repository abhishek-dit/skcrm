<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_user_product_segments()
{
	$ci = & get_instance();
	$ci->db->select('pg.*');
	$ci->db->from('product_group pg');
	$ci->db->join('product_category pc','pg.category_id = pc.category_id','left');
	$ci->db->join('product p','p.group_id = pg.group_id','left');
	$ci->db->where('pc.company_id',$ci->session->userdata('company'));
	$ci->db->where('p.product_id IN ('.$ci->session->userdata('products').')');
	$ci->db->group_by('pg.group_id');
	$ci->db->order_by('pg.rank');
	$res = $ci->db->get();
	if($res->num_rows()>0)
		return $res->result_array();
}

function get_margin_data($searchParams)
{
	$ci = & get_instance();
	// Get margin data for product segments
	/*$segment_margin_data = $ci->Report2_model->get_segmentMarginData($searchParams);
	echo $ci->db->last_query();
	echo '<pre>'; print_r($segment_margin_data); exit;*/
	$product_results = $ci->Report2_model->get_productMarginData($searchParams);
	/*echo $ci->db->last_query();
	echo '<pre>'; print_r($searchParams); exit;*/
	$product_margin_data = array(); $segment_margin_data = array();
	if($product_results)
	{
		foreach ($product_results as $pdata) {
			if($pdata['order_value']>0)
			{
				$product_margin_data[$pdata['group_id']][] = $pdata;
				if(array_key_exists($pdata['group_id'], $segment_margin_data))
				{
					$order_value = 	$segment_margin_data[$pdata['group_id']]['order_value']+$pdata['order_value'];
					$basic_price = 	$segment_margin_data[$pdata['group_id']]['basic_price']+$pdata['basic_price'];
					$segment_total_qty = 	$segment_margin_data[$pdata['group_id']]['segment_total_qty']+$pdata['product_total_qty'];
					$segment_margin_data[$pdata['group_id']] = array('group_id'=>$pdata['group_id'],'segment'=>$pdata['segment'],
						'order_value'=>$order_value,'basic_price'=>$basic_price,'segment_total_qty'=>$segment_total_qty);
				}
				else
				{
					$segment_margin_data[$pdata['group_id']] = array('group_id'=>$pdata['group_id'],'segment'=>$pdata['segment'],
						'order_value'=>$pdata['order_value'],'basic_price'=>$pdata['basic_price'],'segment_total_qty'=>$pdata['product_total_qty']);
				}
			}
		}
	}
	if($searchParams['mr_customer_id']!='')
	{
		$dist_cnote_product_results = $ci->Report2_model->get_DistributorCNoteProductMarginData($searchParams);
		//echo '<pre>'; print_r($dist_cnote_product_results); exit;
		if($dist_cnote_product_results)
		{
			foreach ($dist_cnote_product_results as $pdata) {
				if($pdata['order_value']>0)
				{
					$product_margin_data[$pdata['group_id']][] = $pdata;
					if(array_key_exists($pdata['group_id'], $segment_margin_data))
					{
						$order_value = 	$segment_margin_data[$pdata['group_id']]['order_value']+$pdata['order_value'];
						$basic_price = 	$segment_margin_data[$pdata['group_id']]['basic_price']+$pdata['basic_price'];
						$segment_total_qty = 	$segment_margin_data[$pdata['group_id']]['segment_total_qty']+$pdata['product_total_qty'];
						$segment_margin_data[$pdata['group_id']] = array('group_id'=>$pdata['group_id'],'segment'=>$pdata['segment'],
							'order_value'=>$order_value,'basic_price'=>$basic_price,'segment_total_qty'=>$segment_total_qty);
					}
					else
					{
						$segment_margin_data[$pdata['group_id']] = array('group_id'=>$pdata['group_id'],'segment'=>$pdata['segment'],
							'order_value'=>$pdata['order_value'],'basic_price'=>$pdata['basic_price'],'segment_total_qty'=>$pdata['product_total_qty']);
					}
				}
			}
		}
	}
	$data = array('segment_data'=>$segment_margin_data,'product_data'=>$product_margin_data);
	return $data;
}

function getProductsBySegment($segment_id)
{
	if($segment_id!='')
	{
		$ci = & get_instance();
		$ci->db->select('p.*');
		$ci->db->from('product p');
		$ci->db->where('p.group_id',$segment_id);
		$ci->db->order_by('p.rank');
		$res = $ci->db->get();
		if($res->num_rows()>0)
			return $res->result_array();
	}
}

function get_cnote_margin_data($searchParams,$offset,$per_page)
{
	$ci = & get_instance();
	// Get margin data for product segments
	/*$segment_margin_data = $ci->Report2_model->get_segmentMarginData($searchParams);
	echo $ci->db->last_query();
	echo '<pre>'; print_r($segment_margin_data); exit;*/
	$product_results = $ci->Report2_model->get_regularCNoteMarginData($searchParams,$offset,$per_page);
	//echo '<pre>'; print_r($product_results); exit;
	return $product_results;
}

function getCustomerListInLoggedInUserLocations($region='')
{
	$ci = & get_instance();
	$userLocations = $ci->session->userdata('locationString');
	$ci->db->select('c.*');
	$ci->db->from('customer c');
	$ci->db->join('customer_location cl','cl.customer_id = c.customer_id','left');
	if($region!='')
	{
		$ci->db->join('locationl l1','l1.location_id = ul.location_id','left');
		$ci->db->join('location l2','l2.location_id = l1.parent_id','left');
		$ci->db->join('location l3','l3.location_id = l2.parent_id','left');
		$ci->db->where('l3.parent_id',$region);
	}
	$ci->db->where('cl.location_id IN ('.$userLocations.')');
	$ci->db->group_by('c.customer_id');
	$res = $ci->db->get();
	if($res->num_rows()>0)
	{
		return $res->result_array();
	}
}

function getOrderStatusLabel($status)
{
	$ret = '';
	switch ($status) {
		case 1:
			$ret = 'Cleared';
		break;
		case 2:
			$ret = 'Invoiced';
		break;
		case 3:
			$ret = 'Not Cleared';
		break;
	}
	return $ret;
}

// 25-01-2018
function getRegularCNoteHighestApprover($cnote_id)
{
	if($cnote_id!='')
	{
		$ci = & get_instance();
		$ci->db->select('CONCAT(u.first_name," ",u.last_name) as highest_approver, mah.approval_hist_id');
		$ci->db->from('contract_note_quote_revision cnqr');
		$ci->db->join('quote_op_margin_approval ma','cnqr.quote_revision_id = ma.quote_revision_id','left');
		$ci->db->join('quote_op_margin_approval_history mah','ma.margin_approval_id = mah.margin_approval_id','left');
		$ci->db->join('user u','u.user_id = mah.created_by','left');
		$ci->db->where('cnqr.contract_note_id',$cnote_id);
		$ci->db->order_by('mah.approved_by desc,mah.approval_hist_id desc');
		$ci->db->limit(1);
		$res = $ci->db->get();
		if($res->num_rows()>0)
		{
			$row = $res->row_array();
			if($row['approval_hist_id']!='')
			{
				return $row['highest_approver'];
			}
		}
	}

}

function getPurchaseOrderCNoteHighestApprover($cnote_id)
{
	if($cnote_id!='')
	{
		$ci = & get_instance();
		$ci->db->select('CONCAT(u.first_name," ",u.last_name) as highest_approver, pah.approval_hist_id');
		$ci->db->from('contract_note_po_revision cnpr');
		$ci->db->join('po_product_approval pa','cnpr.po_revision_id = pa.po_revision_id','left');
		$ci->db->join('po_product_approval_history pah','pa.approval_id = pah.approval_id','left');
		$ci->db->join('user u','u.user_id = pah.created_by','left');
		$ci->db->where('cnpr.contract_note_id',$cnote_id);
		$ci->db->order_by('pah.approved_by desc,pah.approval_hist_id desc');
		$ci->db->limit(1);
		$res = $ci->db->get();
		if($res->num_rows()>0)
		{
			$row = $res->row_array();
			if($row['approval_hist_id']!='')
			{
				return $row['highest_approver'];
			}
		}
	}

}