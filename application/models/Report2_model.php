<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Report2_model extends CI_Model 
{
	public function get_segmentMarginData($searchParams)
	{
		$this->db->select('pg.group_id, pg.name as segment,
			ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.mrp)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.mrp)*(1-ma.discount/100) ELSE (o.required_quantity*qd.mrp)-ma.discount END ) END )) AS order_value,
			ROUND(SUM(o.required_quantity*p.base_price)) as basic_price, SUM(o.required_quantity) as segment_total_qty
			');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id = cnqr.contract_note_id');
		$this->db->join('quote_revision qr','qr.quote_revision_id = cnqr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id = qd.quote_id');
		$this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id = op.opportunity_id');
		$this->db->join('product p','p.product_id = op.product_id');
		$this->db->join('product_group pg','pg.group_id = p.group_id');
		//$this->db->join('product_price_history pph','p.product_id = pph.product_id ');
		$this->db->join('lead l','l.lead_id=o.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->group_by('pg.group_id');
		$this->db->where('cn.cnote_type',1);
		/*$where = 'DATE(cn.created_time) between pph.start_date and IF(pph.end_date IS NULL,CURRENT_DATE(),pph.end_date)';
		$this->db->where($where);*/
		$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		$this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
		if($searchParams['mr_region']!='')
		{
			$this->db->where('l4.location_id',$searchParams['mr_region']);
		}
		if($searchParams['mr_user']!='')
		{
			$this->db->where('l.user_id',$searchParams['mr_user']);
		}
		if($searchParams['mr_fromDate']!='')
		{
			$this->db->where('DATE(cn.created_time) >=',$searchParams['mr_fromDate']);
		}
		if($searchParams['mr_toDate']!='')
		{
			$this->db->where('DATE(cn.created_time) <=',$searchParams['mr_toDate']);
		}
		if($searchParams['mr_segment']!='')
		{
			$this->db->where('p.group_id ',$searchParams['mr_segment']);
		}
		if($searchParams['mr_product']!='')
		{
			$this->db->where('p.product_id ',$searchParams['mr_product']);
		}
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			return $res->result_array();
		}
	}

	public function get_productMarginData($searchParams)
	{
		$this->db->select('p.product_id, CONCAT(p.description,"(",p.name,")") as product_name, p.description, p.name,
			ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)- (ma.discount*qd.currency_factor) END ) END )) AS order_value,
			ROUND(SUM(o.required_quantity*p.base_price)) as basic_price, SUM(o.required_quantity) as product_total_qty,p.group_id,pg.name as segment,ROUND(SUM(p.dp)) as total_dp, p.dp as unit_dp
			');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id = cnqr.contract_note_id');
		$this->db->join('quote_revision qr','qr.quote_revision_id = cnqr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id = qd.quote_id');
		$this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id = op.opportunity_id');
		$this->db->join('product p','p.product_id = op.product_id');
		$this->db->join('product_group pg','pg.group_id = p.group_id');
		//$this->db->join('product_price_history pph','p.product_id = pph.product_id ');
		$this->db->join('lead l','l.lead_id=o.lead_id');
		$this->db->join('location l1','l.location_id=l1.location_id');
		$this->db->join('location l2','l1.parent_id=l2.location_id');
		$this->db->join('location l3','l2.parent_id=l3.location_id');
		$this->db->join('location l4','l3.parent_id=l4.location_id');
		$this->db->join('quote_op_margin_approval ma','ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id','LEFT');
		$this->db->group_by('p.product_id');
		$this->db->where('cn.cnote_type',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where = 'DATE(cn.created_time) between pph.start_date and IF(pph.end_date IS NULL,CURRENT_DATE(),pph.end_date)';
		$this->db->where($where);*/
		$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		$this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
		if($searchParams['mr_region']!='')
		{
			$this->db->where('l4.location_id',$searchParams['mr_region']);
		}
		if($searchParams['mr_user']!='')
		{
			$this->db->where('l.user_id',$searchParams['mr_user']);
		}
		if($searchParams['mr_fromDate']!='')
		{
			$this->db->where('DATE(cn.created_time) >=',$searchParams['mr_fromDate']);
		}
		if($searchParams['mr_toDate']!='')
		{
			$this->db->where('DATE(cn.created_time) <=',$searchParams['mr_toDate']);
		}
		if($searchParams['mr_segment']!='')
		{
			$this->db->where('p.group_id ',$searchParams['mr_segment']);
		}
		if($searchParams['mr_product']!='')
		{
			$this->db->where('p.product_id ',$searchParams['mr_product']);
		}
		if($searchParams['mr_customer_id']!='')
		{
			$this->db->where('l.customer_id',$searchParams['mr_customer_id']);
		}
		if($searchParams['mr_dealer_id']!='')
		{
			$this->db->where('qr.dealer_id',$searchParams['mr_dealer_id']);
		}
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			return $res->result_array();
		}
	}

	public function get_DistributorCNoteProductMarginData($searchParams)
	{
		$k = get_preference('cost_of_maintaining_warranty','margin_settings');
		$this->db->select('p.product_id, CONCAT(p.description,"(",p.name,")") as product_name,
			ROUND(SUM( CASE WHEN pa.discount_type = 1 THEN (pp.qty*pp.total_value)*(1-pa.discount/100) ELSE (pp.qty*pp.total_value)-(pa.discount*pp.currency_factor) END + 
				CASE WHEN (po.warranty - po.default_warranty) > 0 
			THEN (ROUND((pp.qty*pp.total_value)*POW((1+'.$k.'/100),	ROUND(po.warranty/12,2)-1) - (pp.qty*pp.total_value) ) ) ELSE 0 END
				)) AS order_value,
			ROUND(SUM(pp.qty*p.base_price)) as basic_price, SUM(pp.qty) as product_total_qty,p.group_id,pg.name as segment,p.name,p.description
			');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_po_revision cnpr','cn.contract_note_id = cnpr.contract_note_id');
		$this->db->join('po_revision pr','pr.po_revision_id = cnpr.po_revision_id');
		$this->db->join('purchase_order po','po.purchase_order_id = pr.purchase_order_id');
		$this->db->join('po_products pp','po.purchase_order_id = pp.purchase_order_id');
		$this->db->join('product p','p.product_id = pp.product_id');
		$this->db->join('product_group pg','pg.group_id = p.group_id');
		//$this->db->join('product_price_history pph','p.product_id = pph.product_id ');
		$this->db->join('user u','u.user_id = po.user_id');
		
		$this->db->join('po_product_approval pa','pa.po_revision_id = pr.po_revision_id AND pp.product_id = pa.product_id','LEFT');
		$this->db->group_by('p.product_id');
		$this->db->where('cn.cnote_type',2);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
		/*$where = 'DATE(cn.created_time) between pph.start_date and IF(pph.end_date IS NULL,CURRENT_DATE(),pph.end_date)';
		$this->db->where($where);*/
		$this->db->where('po.user_id IN ('.$this->session->userdata('reportees').')');
		$this->db->where('pp.product_id IN ('.$this->session->userdata('products').')');
		if($searchParams['mr_region']!='')
		{
			$this->db->join('user_location ul','ul.user_id = u.user_id');
			$this->db->join('location l1','ul.location_id=l1.location_id');
			$this->db->join('location l2','l1.parent_id=l2.location_id');
			$this->db->join('location l3','l2.parent_id=l3.location_id');
			//$this->db->join('location l4','l3.parent_id=l4.location_id');
			$region_where = '(l1.location_id = '.$searchParams['mr_region'].') OR (l2.location_id = '.$searchParams['mr_region'].') OR (l3.location_id = '.$searchParams['mr_region'].') OR (l3.parent_id = '.$searchParams['mr_region'].')';
			$this->db->where($region_where);
		}
		if($searchParams['mr_user']!='')
		{
			$this->db->where('po.user_id',$searchParams['mr_user']);
		}
		if($searchParams['mr_fromDate']!='')
		{
			$this->db->where('DATE(cn.created_time) >=',$searchParams['mr_fromDate']);
		}
		if($searchParams['mr_toDate']!='')
		{
			$this->db->where('DATE(cn.created_time) <=',$searchParams['mr_toDate']);
		}
		if($searchParams['mr_segment']!='')
		{
			$this->db->where('p.group_id ',$searchParams['mr_segment']);
		}
		if($searchParams['mr_product']!='')
		{
			$this->db->where('p.product_id ',$searchParams['mr_product']);
		}
		if($searchParams['mr_dealer_id']!='')
		{
			$this->db->where('po.user_id',$searchParams['mr_dealer_id']);
		}
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			return $res->result_array();
		}
	}

	// Fetching CNote Margin Data: Here using union to get the regular, purchase order
	// public function get_regularCNoteMarginData($searchParams,$offset='',$per_page='')
	// {
	// 	$this->db->select('
	// 		');
	// 	$qry = 'SELECT cn.cnote_number as cnote_id, group_concat(distinct(concat(p.description, " (Qty -", o.required_quantity, ")")) separator "<br>") as product_details,
	// 			ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END )) AS order_value,
	// 			ROUND(SUM(o.required_quantity*p.base_price)) as basic_price, SUM(o.required_quantity) as product_total_qty,
	// 			l4.location as region,c.name as customer, dd.distributor_name, qr.advance_type,qr.advance,qr.balance_payment_days,qr.dealer_commission,
	// 			qr.warranty, ROUND(qr.warranty/12,2) as warranty_in_years, SUM(fp.quantity*fp.unit_price) as free_value, SUM(o.required_quantity*p.dp) as dp,
	// 			CONCAT(u.first_name," ",u.last_name) as sales_engineer, cn.created_time as cnote_created_time, cn.cnote_type, cn.SO_number,"" as default_warranty,
	// 			ROUND(SUM(o.required_quantity*qd.total_value)) as mrp_value, 
	// 			group_concat(distinct(concat(o.opportunity_id,"@@",p.name,"@@",p.description, "@@", o.required_quantity, "@@", ma.warranty, "@@", cn.cnote_type, "@@", qr.advance_type, "@@", qr.advance, "@@", qr.balance_payment_days, "@@", qr.dealer_commission, "@@", dd.distributor_name, "@@", p.mrp)) separator "|") as product_info_str,
	// 			CONCAT(u2.first_name," ",u2.last_name) as invoice_cleared_by, cn.status as contract_note_status, r.short_name as cnote_created_user_role,l.lead_number as lead_id,p.mrp as mrp
	// 			FROM contract_note cn
	// 			JOIN contract_note_quote_revision cnqr ON cn.contract_note_id = cnqr.contract_note_id
	// 			JOIN quote_revision qr ON qr.quote_revision_id = cnqr.quote_revision_id
	// 			JOIN quote_details qd ON qr.quote_id = qd.quote_id
	// 			JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
	// 			JOIN opportunity_product op ON o.opportunity_id = op.opportunity_id
	// 			JOIN product p ON p.product_id = op.product_id
	// 			JOIN product_group pg ON pg.group_id = p.group_id
	// 			JOIN lead l ON l.lead_id=o.lead_id
	// 			JOIN location l1 ON l.location_id=l1.location_id
	// 			JOIN location l2 ON l1.parent_id=l2.location_id
	// 			JOIN location l3 ON l2.parent_id=l3.location_id
	// 			JOIN location l4 ON l3.parent_id=l4.location_id
	// 			LEFT JOIN quote_op_margin_approval ma ON ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id
	// 			JOIN customer c ON c.customer_id = l.customer_id
	// 			LEFT JOIN distributor_details dd ON qr.dealer_id = dd.user_id
	// 			LEFT JOIN quote_op_margin_approval_history mah ON ma.margin_approval_id = mah.margin_approval_id
	// 			LEFT JOIN user u ON u.user_id = cn.created_by
	// 			LEFT JOIN role r ON u.role_id = r.role_id
	// 			LEFT JOIN free_products fp ON fp.contract_note_id = cn.contract_note_id
	// 			LEFT JOIN user u2 ON u2.user_id = cn.approved_by
	// 			WHERE cn.cnote_type = 1 AND cn.company_id = '.$this->session->userdata('company').'
	// 			AND l.location_id IN ('.$this->session->userdata('locationString').')
	// 			AND op.product_id IN ('.$this->session->userdata('products').')
	// 		';
	// 	if($searchParams['mr_region']!='')
	// 	{
	// 		$qry .= ' AND l4.location_id = '.$searchParams['mr_region'].' ';
	// 	}
	// 	if($searchParams['mr_user']!='')
	// 	{
	// 		$qry .= ' AND l.user_id = '.$searchParams['mr_user'].' ';
	// 	}
	// 	if($searchParams['mr_fromDate']!='')
	// 	{
	// 		$qry .= ' AND DATE(cn.created_time) >= "'.$searchParams['mr_fromDate'].'" ';
	// 	}
	// 	if($searchParams['mr_toDate']!='')
	// 	{
	// 		$qry .= ' AND DATE(cn.created_time) <= "'.$searchParams['mr_toDate'].'" ';
	// 	}
	// 	if($searchParams['mr_segment']!='')
	// 	{
	// 		$qry .= ' AND p.group_id = '.$searchParams['mr_segment'].' ';
	// 	}
	// 	if($searchParams['mr_product']!='')
	// 	{
	// 		$qry .= ' AND p.product_id = '.$searchParams['mr_product'].' ';
	// 	}

	// 	// $dealer_qry = 'SELECT cn1.contract_note_id as cnote_id, group_concat(distinct(concat(p1.description, " (Qty -", pp.qty, ")")) separator "<br>") as product_details,
	// 	// 		ROUND(SUM( CASE WHEN pa.discount_type = 1 THEN (pp.qty*pp.total_value)*(1-pa.discount/100) ELSE (pp.qty*pp.total_value)-(pa.discount*pp.currency_factor) END )) AS order_value,
	// 	// 		ROUND(SUM(pp.qty*p1.base_price)) as basic_price, SUM(pp.qty) as product_total_qty,"" as region,dd1.distributor_name as customer, dd1.distributor_name, po.advance_type,po.advance,po.balance_payment_days,0 as dealer_commission,
	// 	// 		po.warranty, ROUND(po.warranty/12,2) as warranty_in_years,0 as free_value,  SUM(pp.qty*p1.dp) as dp,
	// 	// 		"" as sales_engineer, cn1.created_time as cnote_created_time, cn1.cnote_type, cn1.SO_number,po.default_warranty, ROUND(SUM(pp.qty*pp.total_value)) as mrp_value, 
	// 	// 		group_concat(distinct(concat("","@@",p1.name,"@@",p1.description, "@@", pp.qty)) separator "|") as product_info_str,
	// 	// 		CONCAT(u3.first_name," ",u3.last_name) as invoice_cleared_by, cn1.status as contract_note_status, "Distributor"  as cnote_created_user_role,pr.po_revision_id as revision_id,p1.mrp as mrp
	// 	// 		FROM contract_note cn1
	// 	// 		JOIN contract_note_po_revision cnpr ON cn1.contract_note_id = cnpr.contract_note_id
	// 	// 		JOIN po_revision pr ON pr.po_revision_id = cnpr.po_revision_id
	// 	// 		JOIN purchase_order po ON po.purchase_order_id = pr.purchase_order_id
	// 	// 		JOIN po_products pp ON po.purchase_order_id = pp.purchase_order_id
	// 	// 		JOIN product p1 ON p1.product_id = pp.product_id
	// 	// 		JOIN product_group pg1 ON pg1.group_id = p1.group_id
	// 	// 		LEFT JOIN po_product_approval pa ON pa.po_revision_id = pr.po_revision_id AND pp.product_id = pa.product_id
	// 	// 		LEFT JOIN po_product_approval_history pah ON pa.approval_id = pah.approval_id
	// 	// 		LEFT JOIN distributor_details dd1 ON dd1.user_id = po.user_id
	// 	// 		LEFT JOIN user u3 ON u3.user_id = cn1.approved_by
	// 	// 		WHERE cn1.cnote_type = 2  AND cn1.company_id = '.$this->session->userdata('company').'
	// 	// 		AND po.user_id IN ('.$this->session->userdata('reportees').')
	// 	// 		AND pp.product_id IN ('.$this->session->userdata('products').')
	// 	// 	';

	// 	$dealer_qry = 'SELECT cn1.contract_note_id as cnote_id, group_concat(distinct(concat(p1.description, " (Qty -", pp.qty, ")")) separator "<br>") as product_details,
	// 			ROUND(SUM( CASE WHEN pa.discount_type = 1 THEN (pp.qty*pp.total_value)*(1-pa.discount/100) ELSE (pp.qty*pp.total_value)-(pa.discount*pp.currency_factor) END )) AS order_value,
	// 			ROUND(SUM(pp.qty*p1.base_price)) as basic_price, SUM(pp.qty) as product_total_qty,"" as region,dd1.distributor_name as customer, dd1.distributor_name, po.advance_type,po.advance,po.balance_payment_days,0 as dealer_commission,
	// 			po.warranty, ROUND(po.warranty/12,2) as warranty_in_years,0 as free_value,  SUM(pp.qty*p1.dp) as dp,
	// 			"" as sales_engineer, cn1.created_time as cnote_created_time, cn1.cnote_type, cn1.SO_number,po.default_warranty, ROUND(SUM(pp.qty*pp.total_value)) as mrp_value, 
	// 			group_concat(distinct(concat("","@@",p1.name,"@@",p1.description, "@@", pp.qty, "@@", ma1.warranty, "@@", cn1.cnote_type, "@@", po.advance_type, "@@", po.advance, "@@", po.balance_payment_days, "@@", 0, "@@", dd1.distributor_name, "@@", p1.mrp)) separator "|") as product_info_str,
	// 			CONCAT(u3.first_name," ",u3.last_name) as invoice_cleared_by, cn1.status as contract_note_status, "Distributor"  as cnote_created_user_role,pr.po_revision_id as revision_id,p1.mrp as mrp
	// 			FROM contract_note cn1
	// 			JOIN contract_note_po_revision cnpr ON cn1.contract_note_id = cnpr.contract_note_id
	// 			JOIN po_revision pr ON pr.po_revision_id = cnpr.po_revision_id
	// 			JOIN purchase_order po ON po.purchase_order_id = pr.purchase_order_id
	// 			JOIN po_products pp ON po.purchase_order_id = pp.purchase_order_id
	// 			JOIN product p1 ON p1.product_id = pp.product_id
	// 			JOIN product_group pg1 ON pg1.group_id = p1.group_id
	// 			LEFT JOIN po_product_approval pa ON pa.po_revision_id = pr.po_revision_id AND pp.product_id = pa.product_id
	// 			LEFT JOIN po_product_approval_history pah ON pa.approval_id = pah.approval_id
	// 			LEFT JOIN distributor_details dd1 ON dd1.user_id = po.user_id
	// 			LEFT JOIN user u3 ON u3.user_id = cn1.approved_by
	// 			JOIN contract_note_quote_revision cnqr1 ON cn1.contract_note_id = cnqr1.contract_note_id
	// 			JOIN quote_revision qr1 ON qr1.quote_revision_id = cnqr1.quote_revision_id
	// 			JOIN quote_details qd1 ON qr1.quote_id = qd1.quote_id
	// 			JOIN opportunity o1 ON o1.opportunity_id = qd1.opportunity_id
	// 			LEFT JOIN quote_op_margin_approval ma1 ON ma1.quote_revision_id = qr1.quote_revision_id AND o1.opportunity_id = ma1.opportunity_id
	// 			WHERE cn1.cnote_type = 2  AND cn1.company_id = '.$this->session->userdata('company').'
	// 			AND po.user_id IN ('.$this->session->userdata('reportees').')
	// 			AND pp.product_id IN ('.$this->session->userdata('products').')
	// 		';

	// 	/*if($searchParams['mr_region']!='')
	// 	{
	// 		$dealer_qry .= ' AND l4.location_id = '.$searchParams['mr_region'].' ';
	// 	}*/
	// 	if($searchParams['mr_user']!='')
	// 	{
	// 		//$dealer_qry .= ' AND l.user_id = '.$searchParams['mr_user'].' ';
	// 	}
	// 	else
	// 	{
	// 		$dealer_qry .= ' AND po.user_id IN ('.$this->session->userdata('reportees').')
	// 			AND pp.product_id IN ('.$this->session->userdata('products').') ';
	// 	}
	// 	if($searchParams['mr_fromDate']!='')
	// 	{
	// 		$dealer_qry .= ' AND DATE(cn1.created_time) >= "'.$searchParams['mr_fromDate'].'" ';
	// 	}
	// 	if($searchParams['mr_toDate']!='')
	// 	{
	// 		$dealer_qry .= ' AND DATE(cn1.created_time) <= "'.$searchParams['mr_toDate'].'" ';
	// 	}
	// 	if($searchParams['mr_segment']!='')
	// 	{
	// 		$dealer_qry .= ' AND p1.group_id = '.$searchParams['mr_segment'].' ';
	// 	}
	// 	if($searchParams['mr_product']!='')
	// 	{
	// 		$dealer_qry .= ' AND p1.product_id = '.$searchParams['mr_product'].' ';
	// 	}
	// 	$dealer_qry .= ' GROUP BY cn1.contract_note_id ';
	// 	$qry .= ' GROUP BY cn.contract_note_id ';
	// 	$qry .= ' UNION '.$dealer_qry;
	// 	$qry .= ' ORDER BY cnote_id DESC ';
	// 	$num_qry = $qry;
	// 	if($offset!=''&&$per_page!='')
	// 		$qry .= ' LIMIT '.$offset.' , '.$per_page;
	// 	$res = $this->db->query($qry);
	// 	if($res->num_rows()>0)
	// 	{
	// 		$data = array();

	// 		if($offset!=''&&$per_page!='')
	// 		{
	// 			$res1 = $this->db->query($num_qry);
	// 			$data['count'] = $res1->num_rows();
	// 		}
	// 		else
	// 		{
	// 			$data['count'] = $res->num_rows();
	// 		}
	// 		// echo "<pre>";print_r($this->db->last_query());die;
	// 		$data['resultArray'] = $res->result_array();
	// 		// echo "<pre>";print_r($data['resultArray']);die;
	// 		return $data;
	// 	}
	// }


	public function get_regularCNoteMarginData($searchParams,$offset='',$per_page='')
	{
		$this->db->select('
			');
		$qry = 'SELECT cn.contract_note_id as cnote_id, group_concat(distinct(concat(p.description, " (Qty -", o.required_quantity, ")")) separator "<br>") as product_details,
				ROUND(SUM( CASE WHEN ma.margin_approval_id IS NULL THEN (o.required_quantity*qd.total_value)*(1-qr.discount/100) ELSE ( CASE WHEN ma.discount_type = 1 THEN (o.required_quantity*qd.total_value)*(1-ma.discount/100) ELSE (o.required_quantity*qd.total_value)-(ma.discount*qd.currency_factor) END ) END )) AS order_value,
				ROUND(SUM(o.required_quantity*p.base_price)) as basic_price, SUM(o.required_quantity) as product_total_qty,
				l4.location as region,c.name as customer, dd.distributor_name, qr.advance_type,qr.advance,qr.balance_payment_days,qr.dealer_commission,
				qr.warranty, ROUND(qr.warranty/12,2) as warranty_in_years, SUM(fp.quantity*fp.unit_price) as free_value, SUM(o.required_quantity*p.dp) as dp,
				CONCAT(u.first_name," ",u.last_name) as sales_engineer, cn.created_time as cnote_created_time, cn.cnote_type, cn.SO_number,"" as default_warranty,
				ROUND(SUM(o.required_quantity*qd.total_value)) as mrp_value, 
				group_concat(distinct(concat(o.opportunity_id,"@@",p.name,"@@",p.description, "@@", o.required_quantity)) separator "|") as product_info_str,
				CONCAT(u2.first_name," ",u2.last_name) as invoice_cleared_by, cn.status as contract_note_status, r.short_name as cnote_created_user_role,l.lead_number as lead_id,p.mrp as mrp
				FROM contract_note cn
				JOIN contract_note_quote_revision cnqr ON cn.contract_note_id = cnqr.contract_note_id
				JOIN quote_revision qr ON qr.quote_revision_id = cnqr.quote_revision_id
				JOIN quote_details qd ON qr.quote_id = qd.quote_id
				JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
				JOIN opportunity_product op ON o.opportunity_id = op.opportunity_id
				JOIN product p ON p.product_id = op.product_id
				JOIN product_group pg ON pg.group_id = p.group_id
				JOIN lead l ON l.lead_id=o.lead_id
				JOIN location l1 ON l.location_id=l1.location_id
				JOIN location l2 ON l1.parent_id=l2.location_id
				JOIN location l3 ON l2.parent_id=l3.location_id
				JOIN location l4 ON l3.parent_id=l4.location_id
				LEFT JOIN quote_op_margin_approval ma ON ma.quote_revision_id = qr.quote_revision_id AND o.opportunity_id = ma.opportunity_id
				JOIN customer c ON c.customer_id = l.customer_id
				LEFT JOIN distributor_details dd ON qr.dealer_id = dd.user_id
				LEFT JOIN quote_op_margin_approval_history mah ON ma.margin_approval_id = mah.margin_approval_id
				LEFT JOIN user u ON u.user_id = cn.created_by
				LEFT JOIN role r ON u.role_id = r.role_id
				LEFT JOIN free_products fp ON fp.contract_note_id = cn.contract_note_id
				LEFT JOIN user u2 ON u2.user_id = cn.approved_by
				WHERE cn.cnote_type = 1 AND cn.company_id = '.$this->session->userdata('company').'
				AND l.location_id IN ('.$this->session->userdata('locationString').')
				AND op.product_id IN ('.$this->session->userdata('products').')
			';
		if(isset($searchParams['mr_region']) && !empty($searchParams['mr_region']))
		{
			$qry .= ' AND l4.location_id = '.$searchParams['mr_region'].' ';
		}
		if(isset($searchParams['mr_user']) && !empty($searchParams['mr_user']))
		{
			$qry .= ' AND l.user_id = '.$searchParams['mr_user'].' ';
		}
		if(isset($searchParams['mr_fromDate']) && !empty($searchParams['mr_fromDate']))
		{
			$qry .= ' AND DATE(cn.created_time) >= "'.$searchParams['mr_fromDate'].'" ';
		}
		if(isset($searchParams['mr_toDate']) && !empty($searchParams['mr_toDate']))
		{
			$qry .= ' AND DATE(cn.created_time) <= "'.$searchParams['mr_toDate'].'" ';
		}
		if(isset($searchParams['mr_segment']) && !empty($searchParams['mr_segment']))
		{
			$qry .= ' AND p.group_id = '.$searchParams['mr_segment'].' ';
		}
		if(isset($searchParams['mr_product']) && !empty($searchParams['mr_product']))
		{
			$qry .= ' AND p.product_id = '.$searchParams['mr_product'].' ';
		}

		$dealer_qry = 'SELECT cn1.contract_note_id as cnote_id, group_concat(distinct(concat(p1.description, " (Qty -", pp.qty, ")")) separator "<br>") as product_details,
				ROUND(SUM( CASE WHEN pa.discount_type = 1 THEN (pp.qty*pp.total_value)*(1-pa.discount/100) ELSE (pp.qty*pp.total_value)-(pa.discount*pp.currency_factor) END )) AS order_value,
				ROUND(SUM(pp.qty*p1.base_price)) as basic_price, SUM(pp.qty) as product_total_qty,"" as region,dd1.distributor_name as customer, dd1.distributor_name, po.advance_type,po.advance,po.balance_payment_days,0 as dealer_commission,
				po.warranty, ROUND(po.warranty/12,2) as warranty_in_years,0 as free_value,  SUM(pp.qty*p1.dp) as dp,
				"" as sales_engineer, cn1.created_time as cnote_created_time, cn1.cnote_type, cn1.SO_number,po.default_warranty, ROUND(SUM(pp.qty*pp.total_value)) as mrp_value, 
				group_concat(distinct(concat("","@@",p1.name,"@@",p1.description, "@@", pp.qty)) separator "|") as product_info_str,
				CONCAT(u3.first_name," ",u3.last_name) as invoice_cleared_by, cn1.status as contract_note_status, "Distributor"  as cnote_created_user_role,pr.po_revision_id as revision_id,p1.mrp as mrp
				FROM contract_note cn1
				JOIN contract_note_po_revision cnpr ON cn1.contract_note_id = cnpr.contract_note_id
				JOIN po_revision pr ON pr.po_revision_id = cnpr.po_revision_id
				JOIN purchase_order po ON po.purchase_order_id = pr.purchase_order_id
				JOIN po_products pp ON po.purchase_order_id = pp.purchase_order_id
				JOIN product p1 ON p1.product_id = pp.product_id
				JOIN product_group pg1 ON pg1.group_id = p1.group_id
				LEFT JOIN po_product_approval pa ON pa.po_revision_id = pr.po_revision_id AND pp.product_id = pa.product_id
				LEFT JOIN po_product_approval_history pah ON pa.approval_id = pah.approval_id
				LEFT JOIN distributor_details dd1 ON dd1.user_id = po.user_id
				LEFT JOIN user u3 ON u3.user_id = cn1.approved_by
				WHERE cn1.cnote_type = 2  AND cn1.company_id = '.$this->session->userdata('company').'
				AND po.user_id IN ('.$this->session->userdata('reportees').')
				AND pp.product_id IN ('.$this->session->userdata('products').')
			';

		/*if($searchParams['mr_region']!='')
		{
			$dealer_qry .= ' AND l4.location_id = '.$searchParams['mr_region'].' ';
		}*/
		if(isset($searchParams['mr_user']) && !empty($searchParams['mr_user']))
		{
			//$dealer_qry .= ' AND l.user_id = '.$searchParams['mr_user'].' ';
		}
		else
		{
			$dealer_qry .= ' AND po.user_id IN ('.$this->session->userdata('reportees').')
				AND pp.product_id IN ('.$this->session->userdata('products').') ';
		}
		if(isset($searchParams['mr_fromDate']) && !empty($searchParams['mr_fromDate']))
		{
			$dealer_qry .= ' AND DATE(cn1.created_time) >= "'.$searchParams['mr_fromDate'].'" ';
		}
		if(isset($searchParams['mr_toDate']) && !empty($searchParams['mr_toDate']))
		{
			$dealer_qry .= ' AND DATE(cn1.created_time) <= "'.$searchParams['mr_toDate'].'" ';
		}
		if(isset($searchParams['mr_segment']) && !empty($searchParams['mr_segment']))
		{
			$dealer_qry .= ' AND p1.group_id = '.$searchParams['mr_segment'].' ';
		}
		if(isset($searchParams['mr_product']) && !empty($searchParams['mr_product']))
		{
			$dealer_qry .= ' AND p1.product_id = '.$searchParams['mr_product'].' ';
		}
		$dealer_qry .= ' GROUP BY cn1.contract_note_id ';
		$qry .= ' GROUP BY cn.contract_note_id ';
		$qry .= ' UNION '.$dealer_qry;
		$qry .= ' ORDER BY cnote_id DESC ';
		$num_qry = $qry;
		if($offset>='0'&&$per_page!='')
			$qry .= ' LIMIT '.$offset.' , '.$per_page;
		$res = $this->db->query($qry);
		if($res->num_rows()>0)
		{
			$data = array();

			if($offset>='0'&&$per_page!='')
			{
				$res1 = $this->db->query($num_qry);
				$data['count'] = $res1->num_rows();
			}
			else
			{
				$data['count'] = $res->num_rows();
			}
			$data['resultArray'] = $res->result_array();
			// echo "<pre>";print_r($data['resultArray']);die;
			return $data;
		}
	}

	public function getCustomersAutoComplete($region,$term)
	{
		$this->db->select('c.customer_id,c.name');
		$this->db->from('customer c');
		$this->db->join('customer_location cl','c.customer_id = cl.customer_id');
		$this->db->join('location l1','l1.location_id = cl.location_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->like('c.name',$term);
		if($region!='')
		{
			$this->db->where('l3.parent_id',$region);
		}
		$this->db->limit(20);
		$this->db->group_by('c.customer_id');
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			return $res->result_array();
		}
	}

	public function getDealersAutoComplete($region,$term)
	{
		$this->db->select('dd.user_id,dd.distributor_name');
		$this->db->from('distributor_details dd');
		$this->db->join('user_location ul','dd.user_id = ul.user_id and ul.status=1');
		$this->db->join('location l1','l1.location_id = ul.location_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->like('dd.distributor_name',$term);
		if($region!='')
		{
			$region_where = '(l1.location_id = '.$region.' OR l2.location_id = '.$region.' OR l3.location_id = '.$region.' OR l3.parent_id = '.$region.')';
			$this->db->where($region_where);
		}
		$this->db->limit(20);
		$this->db->group_by('dd.user_id');
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			return $res->result_array();
		}
	}
}