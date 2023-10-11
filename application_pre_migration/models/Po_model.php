<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Po_model extends CI_Model {
	//prasad new enhancements phase2
	public function get_total_po_rows($searchParams)
	{  
        $role_id = $this->session->userdata('role_id');
	    $this->db->select('*');
		$this->db->from('purchase_order po');
		
	    if($role_id==8 || $role_id==7 || $role_id==6)
	    {   
	    	$this->db->join('user u','po.user_id=u.user_id');
	    	$this->db->join('user_location ul','u.user_id=ul.user_id');
	    	$this->db->where('ul.location_id IN ('.$this->session->userdata('locationString').')');
		    $this->db->where('ul.status',1);
		    if($searchParams['users_id']!='')
			$this->db->where('po.user_id',$searchParams['users_id']);
	    }
	    else
	    {
			$this->db->where('po.user_id',$this->session->userdata('user_id'));
		}
		if($searchParams['purchase_order_id']!='')
		$this->db->where('po.purchase_order_id',$searchParams['purchase_order_id']);
	    if($searchParams['start_date']!='')
		$this->db->where('date(po.created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(po.created_time)<=', $searchParams['end_date']);
		if($searchParams['approval_status']!='')
		$this->db->where('po.status',$searchParams['approval_status']);
	    if($role_id==8 || $role_id==7 || $role_id==6)
	   {
	   	$this->db->group_by('po.purchase_order_id');
	   }
		$res = $this->db->get();
		return $res->num_rows();
	}
    //prasad new enhancements phase2
	public function po_list($searchParams, $per_page, $current_offset)
	{    
		 $role_id = $this->session->userdata('role_id');
	    $this->db->select('po.*, group_concat(distinct(concat(p.name, " - ", p.description, " (Qty -", pp.qty, ")")) separator "<br>") as product_details,
	    	sum( CASE WHEN pa.discount_type = 1 THEN (pp.qty*pp.unit_price)*(1-pa.discount/100) ELSE (pp.qty*pp.unit_price)-pa.discount END ) as order_value,
	    	d.distributor_name, concat(u.first_name," ",u.last_name," (",u.employee_id,")") as user, cnpr.contract_note_id,sum(p.dp*pp.qty) as dp_value');
		$this->db->from('purchase_order po');
		$this->db->join('po_products pp','po.purchase_order_id = pp.purchase_order_id','INNER');
		$this->db->join('product p','p.product_id = pp.product_id','INNER');
		$this->db->join('distributor_details d','d.user_id = po.user_id','LEFT');
		$this->db->join('user u','po.user_id=u.user_id','LEFT');
		$this->db->join('po_revision pr','pr.purchase_order_id = po.purchase_order_id AND pr.status = 1','INNER');
		$this->db->join('po_product_approval pa','pa.po_revision_id = pr.po_revision_id AND pa.product_id = pp.product_id','INNER');		
		$this->db->join('contract_note_po_revision cnpr','pr.po_revision_id = cnpr.po_revision_id','left');
		if($role_id==8 || $role_id==7 || $role_id==6)
	    {   
	    	$this->db->join('user_location ul','u.user_id=ul.user_id');
	    	$this->db->where('ul.location_id IN ('.$this->session->userdata('locationString').')');
		    $this->db->where('ul.status',1);
		    $this->db->where('u.role_id',5);
		    if($searchParams['users_id']!='')
			$this->db->where('po.user_id',$searchParams['users_id']);
	    }
	    else
	    {
			$this->db->where('po.user_id',$this->session->userdata('user_id'));
		}
		if($searchParams['purchase_order_id']!='')
		$this->db->where('po.purchase_order_id',$searchParams['purchase_order_id']);
		if($searchParams['start_date']!='')
		$this->db->where('date(po.created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(po.created_time)<=', $searchParams['end_date']);
		if($searchParams['approval_status']!='')
		$this->db->where('po.status',$searchParams['approval_status']);
	    $this->db->group_by('po.purchase_order_id');
	    $this->db->order_by('po.purchase_order_id','desc');
	    $this->db->limit($per_page, $current_offset);
	    $res = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	//prasad new enhancements phase2
	public function get_po_results($po_id)
	{
		$this->db->select('CONCAT(p.name," (",p.description,")") as product_name,pp.product_id, pg.name as segment,pp.*,pa.discount,pa.discount_type,p.dp');
		$this->db->from('po_products pp');
		$this->db->join('po_revision pr','pr.purchase_order_id = '.$po_id.' AND pr.status=1');
		$this->db->join('po_product_approval pa','pa.po_revision_id = pr.po_revision_id AND pa.product_id = pp.product_id','INNER');
		$this->db->join('product p','pp.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id = pg.group_id');
		$this->db->where('pp.purchase_order_id',$po_id);
		$res=$this->db->get();
		return $res->result_array();
	}
	//prasad new enhancements phase2
	public function get_stockist_list() {
        $sql = "select u.user_id, d.distributor_name, employee_id from user u "
                . "JOIN distributor_details d ON d.user_id=u.user_id "
                . " WHERE u.role_id=12 and u.status=1 ";
        $query = $this->db->query($sql);
        return $query->result_array();
       
    }
    //prasad new enhancements phase2
	 public function po_download_list($searchParams)
	{
		$this->db->select('po.*,b.name as billing_name');
		$this->db->from('purchase_order po');
		$this->db->join('billing b','po.billing_info_id=b.billing_info_id');
		if($searchParams['purchase_order_id']!='')
		$this->db->where('po.purchase_order_id',$searchParams['purchase_order_id']);
		if($searchParams['billing_id']!='')
		$this->db->where('po.billing_info_id',$searchParams['billing_id']);
		if($searchParams['users_id']!='')
		$this->db->where('po.user_id',$searchParams['users_id']);
		$res = $this->db->get();
		return $res->result_array();
	}

	public function distributorOpportunityRows($searchParams,$product_ids,$po_id, $check = 1)
	{
		$statusValues = '(1,2,3,4,5,10)';
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');

		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('o.opportunity_id');
		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('opportunity_status os', 'os.status = o.status');
		$this->db->join('product_group pg', 'pg.group_id = p.group_id');

		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		if($check==2)
		{
			$this->db->join('purchase_order po','po.purchase_order_id = '.$po_id);
			$this->db->join('purchase_order_opportunity poo','poo.opportunity_id = o.opportunity_id AND poo.purchase_order_id = po.purchase_order_id AND poo.status=1');
		}
		//$this->db->join('purchase_order po','u.user_id=po.user_id');
        //$this->db->where('po.purchase_order_id',$po_id);

		if($searchParams['opportunity_id']!='')
		$this->db->where('o.opportunity_id',$searchParams['opportunity_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['opp_status']!='')
		$this->db->where('o.status', $searchParams['opp_status']);
		//if($searchParams['product_id']!='')
		//$this->db->where('p.product_id', $searchParams['product_id']);
        if(count($searchParams['product_id']) > 0)
        {
        	//$products = implode(",", $searchParams['product_id']);
        	if(@$searchParams['product_id'][0] != '')
			$this->db->where_in('pg.category_id', $searchParams['product_id']);
        }
                if($searchParams['start_date']!='')
		$this->db->where('o.created_time >=', $searchParams['start_date']);
                if($searchParams['end_date']!='')
		$this->db->where('o.created_time<=', $searchParams['end_date']." 23:59:59");

		//mahesh code: 21st sep 2016, new filters
        if($searchParams['source_of_lead']!='')
		$this->db->where('l.source_id',$searchParams['source_of_lead']);

		if($searchParams['region_id']!=''){
			$this->db->where('l3.parent_id',$searchParams['region_id']);
			$this->db->join('location l2','l2.location_id=l1.parent_id');
			$this->db->join('location l3','l3.location_id=l2.parent_id');
		}

		if(@$searchParams['opp_category'] != '')
		{
			$month = date('m');
			$month1 = $month + 1;
			$year = date('Y');		
			$day = getOpportunityCategorizationDate();
			$hotDay = $year."-".$month."-".$day;
			//$hotDate = "2016-12-28";
			$warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
			switch(@$searchParams['opp_category'])
			{
				case 1:
					$this->db->where('o.expected_order_conclusion <= ', $hotDay);
					break;
				case 2:
					$this->db->where('o.expected_order_conclusion > ', $hotDay);
					$this->db->where('o.expected_order_conclusion <= ', $warmDate);
					break;
				default:
					$this->db->where('o.expected_order_conclusion > ', $warmDate);
					break;
			}
		}		

		$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		$this->db->where('o.status IN '.$statusValues);
		if($role_id!=8 && $role_id!=7)
		{
			$this->db->where('u.user_id ',$this->session->userdata('user_id'));
		}
		//$this->db->where('u.role_id',5);
		$this->db->where_in('op.product_id',$product_ids);
		$res = $this->db->get();
		return $res->num_rows();
	}


	public function distributorOpportunityResults($searchParams, $per_page, $current_offset,$product_ids,$po_id, $check = 1)
	{
		$statusValues = ($check == 2)?'(6,7,8)':'(1,2,3,4,5,10)';
		$role_id = $this->session->userdata('role_id');
		$reportees = $this->session->userdata('reportees');
		$reportees = ($role_id == 4 || $role_id == 5)?0:$reportees;
		$this->db->select('o.opportunity_id, l.lead_id as lead_id, os.name as stage, o.created_time, o.status, o.required_quantity,
			u.user_id, concat("ID : ", l.lead_id, " - ", c.name, " ", c.name1, " (", l1.location, ")") as lead, p.rrp,
			concat(p.name, " (", p.description, ")") as product, o.status as status, o.expected_order_conclusion as oDate, 
			concat(u.first_name, " ", u.last_name, " - ", u.employee_id, " (", r.name, ")") as user, l.user_id,
			l.created_time as lCTime, o.created_time as oCTime');
		$this->db->from('opportunity o');
		$this->db->join('lead l', 'o.lead_id = l.lead_id');

		$this->db->join('opportunity_product op', 'op.opportunity_id = o.opportunity_id');
		$this->db->join('product p', 'p.product_id = op.product_id');
		$this->db->join('product_group pg', 'pg.group_id = p.group_id');

		
		$this->db->join('opportunity_status os', 'os.status = o.status');

		$this->db->join('customer c','c.customer_id = l.customer_id');
		$this->db->join('contact cn','cn.contact_id = l.contact_id');
		$this->db->join('user u','u.user_id = l.user_id');
		$this->db->join('location l1','l1.location_id = l.location_id');
		$this->db->join('role r','r.role_id = u.role_id');
		//$this->db->join('purchase_order po','u.user_id=po.user_id');
        //$this->db->where('po.purchase_order_id',$po_id);
		if($searchParams['opportunity_id']!='')
		$this->db->where('o.opportunity_id',$searchParams['opportunity_id']);
		if($searchParams['customer']!='')
		$this->db->where('l.customer_id', $searchParams['customer']);
		if($searchParams['created_user']!='')
		$this->db->where('l.user_id', $searchParams['created_user']);
		if($searchParams['opp_status']!='')
		$this->db->where('o.status', $searchParams['opp_status']);
		//if($searchParams['product_id']!='')
		//$this->db->where('p.product_id', $searchParams['product_id']);
        if(count($searchParams['product_id']) > 0)
        {
        	//$products = implode(",", $searchParams['product_id']);
        	if(@$searchParams['product_id'][0] != '')
			$this->db->where_in('pg.category_id', $searchParams['product_id']);
        }

        if($searchParams['start_date']!='')
		$this->db->where('o.created_time >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('o.created_time<=', $searchParams['end_date']." 23:59:59");
        //mahesh code: 21st sep 2016, new filters
        if($searchParams['source_of_lead']!='')
		$this->db->where('l.source_id',$searchParams['source_of_lead']);

		if($searchParams['region_id']!=''){
			$this->db->where('l3.parent_id',$searchParams['region_id']);
			$this->db->join('location l2','l2.location_id=l1.parent_id');
			$this->db->join('location l3','l3.location_id=l2.parent_id');
		}
		if(@$searchParams['opp_category'] != '')
		{
			$month = date('m');
			$month1 = $month + 1;
			$year = date('Y');		
			$day = getOpportunityCategorizationDate();
			$hotDay = $year."-".$month."-".$day;
			//$hotDate = "2016-12-28";
			$warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
			switch(@$searchParams['opp_category'])
			{
				case 1:
					$this->db->where('o.expected_order_conclusion <= ', $hotDay);
					break;
				case 2:
					$this->db->where('o.expected_order_conclusion > ', $hotDay);
					$this->db->where('o.expected_order_conclusion <= ', $warmDate);
					break;
				default:
					$this->db->where('o.expected_order_conclusion > ', $warmDate);
					break;
			}
		}

		$this->db->where('l.location_id IN ('.$this->session->userdata('locationString').')');
		if($role_id!=8 && $role_id!=7)
		{
			$this->db->where('u.user_id ',$this->session->userdata('user_id'));
		}
		$this->db->where_in('op.product_id',$product_ids);
		$this->db->where('o.status IN '.$statusValues);
		//$this->db->where('u.role_id',5);
		$this->db->order_by('o.opportunity_id', 'DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		$data = $res->result_array();
	    return $data;
	}	
	 public function getSearchCustomer($customer_id)
    {
        $this->db->select('c.customer_id, concat(c.name, " (", l.location, ")") as customer');
        $this->db->from('customer c'); 
        $this->db->join('customer_location cl', 'cl.customer_id = c.customer_id');   
        $this->db->join('location l', 'l.location_id = cl.location_id');
        $this->db->where('c.customer_id', $customer_id);  
        $res = $this->db->get();
        if($res->num_rows() > 0)
        {
            $data = $res->result_array();
            return $data[0];
        }
        else
            return array('customer_id' => '', 'customer' => 'Select Customer');
 

    }
    public function getSearchUser($user_id)
	{
		$q = "SELECT u.user_id, case when (u.role_id != 5) then
			concat(u.first_name, ' ', u.last_name, ' - ', u.employee_id, ' (', r.name, ')') 
			else concat(d.distributor_name, ' - ', u.employee_id, ' (', r.name, ')') end cName from user u
			INNER JOIN role r on r.role_id = u.role_id
			INNER JOIN distributor_details d ON d.user_id = u.user_id where u.user_id = '".$user_id."'";
		$res = $this->db->query($q);
        if($res->num_rows() > 0)
        {
            $data = $res->result_array();
            return $data[0];
        }
        else
            return array('user_id' => '', 'cName' => 'Select Owner');
	}

	public function getLeadDetails($check = 0)
	{
		$this->db->select('l.lead_id, CONCAT(c.name," ",c.name1," - ",c.department," (",loc.location,")") as CustomerName');
		$this->db->from('lead l');
		$this->db->where('l.user_id', $this->session->userdata('user_id'));
		$this->db->where('l.status >', 1);
		$this->db->where('l.status <', 20);
		if($check == 0)
		$this->db->where('l.visit_requirement', 1);
		if($check==3){
			$this->db->where('l.site_readiness_id >', 0);
			$this->db->where('l.relationship_id >', 0);
		}
		$this->db->join('customer c','l.customer_id = c.customer_id');
		$this->db->join('customer_location_contact clc','clc.customer_id = c.customer_id AND clc.location_id = l.location_id AND clc.contact_id = l.contact_id');
		$this->db->join('location loc','loc.location_id = clc.location_id');
		$this->db->order_by('l.lead_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
	public function get_opportunity_product_details($po_id)
	{
		$this->db->select('o.opportunity_id,o.expected_order_conclusion,o.expected_invoicing_date,CONCAT(p.name," (",p.description,")") as product_name,o.required_quantity,pop.status');
		$this->db->from('purchase_order_opportunity pop');
		$this->db->join('opportunity o','pop.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','op.product_id=p.product_id');
		$this->db->where('pop.purchase_order_id',$po_id);
		$status =array(1,3,4);
		$this->db->where_in('pop.status',$status);
		$this->db->group_by('o.opportunity_id');
		$this->db->order_by('o.opportunity_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function get_total_po_opp_tag_rows($searchParams)
	{  
        $role_id = $this->session->userdata('role_id');
	    $this->db->select('*');
		$this->db->from('purchase_order po');
		//$this->db->join('billing b','po.billing_info_id=b.billing_info_id');
		$this->db->join('purchase_order_opportunity poo','po.purchase_order_id=poo.purchase_order_id');
		if($role_id==8 || $role_id==7)
	    {   
	    	$this->db->join('user u','po.user_id=u.user_id');
	    	$this->db->join('user_location ul','u.user_id=ul.user_id');
	    	$this->db->where('ul.location_id IN ('.$this->session->userdata('locationString').')');
		    $this->db->where('ul.status',1);
		    $this->db->where('u.role_id',5);
		    if($searchParams['users_id']!='')
			$this->db->where('po.user_id',$searchParams['users_id']);
	    }
	    else
	    {
			$this->db->where('po.user_id',$this->session->userdata('user_id'));
		}
		$this->db->where('poo.status',1);
		if($searchParams['purchase_order_id']!='')
		$this->db->where('po.purchase_order_id',$searchParams['purchase_order_id']);
		if($searchParams['opp_id']!='')
		$this->db->where('poo.opportunity_id',$searchParams['opp_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(po.created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(po.created_time)<=', $searchParams['end_date']);
	    if($role_id==8 || $role_id==7)
	   {
	   	$this->db->group_by('poo.opportunity_id');
	   }
		$res = $this->db->get();
		return $res->num_rows();
	}
    //prasad new enhancements phase2
	public function po_opp_tagged_list($searchParams, $per_page, $current_offset)
	{    
		 $role_id = $this->session->userdata('role_id');
	    $this->db->select('po.*,poo.opportunity_id,poo.po_op_id,o.status as opp_status, CONCAT(p.name,"(",p.description,")") as product, o.required_quantity');
		$this->db->from('purchase_order po');
		//$this->db->join('billing b','po.billing_info_id=b.billing_info_id');
		$this->db->join('purchase_order_opportunity poo','po.purchase_order_id=poo.purchase_order_id');
		$this->db->join('opportunity o','o.opportunity_id=poo.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('product p','p.product_id = op.product_id');
		if($role_id==8 || $role_id==7 )
	    {   
	    	$this->db->join('user u','po.user_id=u.user_id');
	    	$this->db->join('user_location ul','u.user_id=ul.user_id');
	    	$this->db->where('ul.location_id IN ('.$this->session->userdata('locationString').')');
		    $this->db->where('ul.status',1);
		    $this->db->where('u.role_id',5);
		    if($searchParams['users_id']!='')
			$this->db->where('po.user_id',$searchParams['users_id']);
	    }
	    else
	    {
			$this->db->where('po.user_id',$this->session->userdata('user_id'));
		}
		$this->db->where('poo.status',1);
		if($searchParams['purchase_order_id']!='')
		$this->db->where('po.purchase_order_id',$searchParams['purchase_order_id']);
	    if($searchParams['opp_id']!='')
		$this->db->where('poo.opportunity_id',$searchParams['opp_id']);	
	    if($searchParams['start_date']!='')
		$this->db->where('date(po.created_time) >=', $searchParams['start_date']);
        if($searchParams['end_date']!='')
		$this->db->where('date(po.created_time)<=', $searchParams['end_date']);
	    if($role_id==8 || $role_id==7)
	   {
	   	$this->db->group_by('poo.opportunity_id');
	   }
	   $this->db->order_by('po.purchase_order_id','desc');
		$this->db->limit($per_page, $current_offset);
	    $res = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_product_total_no_of_rows($searchParams)
	{   
		$user_id=$this->session->userdata('user_id');
		$this->db->select('p.description,p.name,p.product_id');
		$this->db->from('user u');
		$this->db->join('user_product up','u.user_id=up.user_id');
		$this->db->join('product p','up.product_id=p.product_id');
		$this->db->where('up.status',1);
		if($searchParams['product_id']!='')
		$this->db->where('up.product_id',$searchParams['product_id']);
	    $this->db->where('u.user_id',$user_id);
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	public function product_results($searchParams)
	{	
		$user_id=$this->session->userdata('user_id');
		$this->db->select('p.description,p.name,p.product_id');
		$this->db->from('user u');
		$this->db->join('user_product up','u.user_id=up.user_id');
		$this->db->join('product p','up.product_id=p.product_id');
		$this->db->where('up.status',1);
		if($searchParams['product_id']!='')
		$this->db->where('up.product_id',$searchParams['product_id']);
	    $this->db->where('u.user_id',$user_id);
		//$this->db->limit($per_page, $current_offset);
	    $res = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_po_stock($searchParams)
	{
		$user_id = $this->session->userdata('user_id');
		$this->db->select('pp.product_id,sum(pp.qty) as quantity');
		$this->db->from('purchase_order po');
		$this->db->join('po_products pp','po.purchase_order_id=pp.purchase_order_id');
		$this->db->where('po.user_id',$user_id);
		if($searchParams['product_id']!='')
		$this->db->where('pp.product_id',$searchParams['product_id']);
		$this->db->where('po.status not in (1,3)');
		$this->db->group_by('pp.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_distributor_won_opportunities($searchParams)
	{
		$user_id = $this->session->userdata('user_id');
		$this->db->select('sum(o.required_quantity) as req_qty,op.product_id');
		$this->db->from('purchase_order po');
		$this->db->join('purchase_order_opportunity pop','po.purchase_order_id=pop.purchase_order_id');
		$this->db->join('opportunity o','pop.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->where('po.user_id',$user_id);
		$this->db->where('pop.status',1);
		if($searchParams['product_id']!='')
		$this->db->where('op.product_id',$searchParams['product_id']);
		$this->db->group_by('op.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function get_dist_opening_stock($searchParams)
	{
		$user_id = $this->session->userdata('user_id');
		$this->db->select('dps.product_id,sum(dps.opening_stock) as opening_stock');
		$this->db->from('dealer_product_stock dps');
		$this->db->where('dps.user_id',$user_id);
		if($searchParams['product_id']!='')
		$this->db->where('dps.product_id',$searchParams['product_id']);
		$this->db->group_by('dps.product_id');
		$res=$this->db->get();
		return $res->result_array();
	}
	public function download_product_results($searchParams)
	{
		$user_id=$this->session->userdata('user_id');
		$this->db->select('p.description,p.name,p.product_id');
		$this->db->from('user u');
		$this->db->join('user_product up','u.user_id=up.user_id');
		$this->db->join('product p','up.product_id=p.product_id');
		$this->db->where('up.status',1);
		if($searchParams['product_id']!='')
		$this->db->where('up.product_id',$searchParams['product_id']);
	    $this->db->where('u.user_id',$user_id);
		$res = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_user_products()
	{
		$user_id=$this->session->userdata('user_id');
		$this->db->select('p.description,p.name,p.product_id,p.rrp');
		$this->db->from('user u');
		$this->db->join('user_product up','u.user_id=up.user_id');
		$this->db->join('product p','up.product_id=p.product_id');
		$this->db->where('up.status',1);
		$this->db->where('u.user_id',$user_id);
		$res = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $res->result_array();
	}
	public function get_previous_opportunity($opp_id)
	{
        $this->db->select('status');
        $this->db->from('opportunity_status_history');
        $this->db->where('opportunity_id',$opp_id);
        $this->db->where('status!=',10);
        $this->db->order_by('opportunity_status_id','desc');
        $this->db->limit(1);
        $res=$this->db->get();
        $previous_status=$res->row_array();
        return $previous_status['status'];
	}

	// Mahesh: 08-10-2017
	public function get_user_product_segments()
	{
		$user_id=$this->session->userdata('user_id');
		$this->db->select('pg.*');
		$this->db->from('user u');
		$this->db->join('user_product up','u.user_id=up.user_id');
		$this->db->join('product p','up.product_id=p.product_id');
		$this->db->join('product_group pg','p.group_id = pg.group_id');
		$this->db->where('up.status',1);
		$this->db->where('u.user_id',$user_id);
		$this->db->group_by('pg.group_id');
		$res = $this->db->get();
	  //  echo $this->db->last_query();exit;
		return $res->result_array();
	}

	// Mahesh: 08-10-2017
	public function getUserProductsBySegment($segment_id)
	{
		$user_id=$this->session->userdata('user_id');
		$this->db->select('p.product_id,CONCAT(p.name," (",p.description,")") as product, p.dp as unit_price');
		$this->db->from('user u');
		$this->db->join('user_product up','u.user_id=up.user_id');
		$this->db->join('product p','up.product_id=p.product_id');
		$this->db->where('up.status',1);
		$this->db->where('p.availability',1);
		$this->db->where('u.user_id',$user_id);
		$this->db->where('p.group_id',$segment_id);
		$res = $this->db->get();
	    //echo $this->db->last_query();exit;
		return $res->result_array();
	}

	// Mahesh: 09-10-2017
	public function getProductPriceInfo($products)
	{
		$this->db->select('product_id,dp,mrp,base_price,rrp,freight_insurance,gst');
		$this->db->from('product');
		$this->db->where_in('product_id',$products);
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			return $res->result_array();
		}
	}

	//Srilekha: 3Nov17
	public function check_po_documents()
	{
		$role_id=$this->session->userdata('role_id');
		$user_id=$this->session->userdata('user_id');
		$status = array(1,2,4,5);
		if($role_id == 5)
		{
			$this->db->select('po.purchase_order_id');
			$this->db->from('purchase_order po');
			$this->db->where('po.purchase_order_id NOT IN (SELECT purchase_order_id FROM po_document)');
			$this->db->where_in('po.status',$status);
			$this->db->where('po.user_id',$user_id);
			$res = $this->db->get();
			if($res->num_rows()>0)
				return $res->result_array();
		}
	}
}