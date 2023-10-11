<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model {

	public function categoryDetails($searchParams)
	{
		
		$this->db->select('pc.category_id, pc.name, pc.created_time,pc.description,pc.modified_by, pc.modified_time, c.name as CompanyName');
		$this->db->from('product_category pc');
		if($searchParams['categoryName']!='')
		$this->db->like('pc.name',$searchParams['categoryName']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->where('pc.status', 1);
		$this->db->join('company c','pc.company_id = c.company_id','left');
		/*$this->db->join('category_sub_category csc','csc.category_id = pc.category_id','left');
		$this->db->join('sub_category sc','sc.sub_category_id = csc.sub_category_id AND sc.status=1','left');*/
		$this->db->group_by('pc.category_id');
		$res = $this->db->get();
		$data = $res->result_array();
		/*$i = 0;
		foreach($res->result_array() as $row)
		{
			$data[$i]['competitors'] = '';
			$j = 0;
			$q = "SELECT c.name `competitor` from product_category pc
				inner join product_category_competitor pcc on pc.category_id = pcc.category_id
				inner join competitor c on c.competitor_id = pcc.competitor_id
				where pcc.status = 1 and c.status = 1 and pc.category_id = ".$row['category_id'];
			$r = $this->db->query($q);
			foreach($r->result_array() as $rr)
			{
				if($j > 0) $data[$i]['competitors'].=', ';
				$data[$i]['competitors'].=$rr['competitor'];
				$j++;
			}
			$i++;
		}*/
		return $data;
	}
	
	public function categoryResults($searchParams, $per_page, $current_offset)
	{
		
		$this->db->select('pc.category_id, pc.name, pc.status, pc.description,c.name as CompanyName');
		$this->db->from('product_category pc');
		if($searchParams['categoryName']!='')
		$this->db->like('pc.name',$searchParams['categoryName']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->join('company c','pc.company_id = c.company_id','left');
		/*$this->db->join('category_sub_category csc','csc.category_id = pc.category_id','left');
		$this->db->join('sub_category sc','sc.sub_category_id = csc.sub_category_id AND sc.status=1','left');*/
		$this->db->group_by('pc.category_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('pc.category_id','DESC');
		$res = $this->db->get();

		$data = $res->result_array();
		/*$i = 0;
		foreach($res->result_array() as $row)
		{
			$data[$i]['competitors'] = '';
			$j = 0;
			$q = "SELECT c.name `competitor` from product_category pc
				inner join product_category_competitor pcc on pc.category_id = pcc.category_id
				inner join competitor c on c.competitor_id = pcc.competitor_id
				where pc.category_id = ".$row['category_id'];
			$r = $this->db->query($q);
			foreach($r->result_array() as $rr)
			{
				if($j > 0) $data[$i]['competitors'].=', ';
				$data[$i]['competitors'].=$rr['competitor'];
				$j++;
			}
			$i++;
		}*/
		return $data;
	}
	
	public function categoryTotalRows($searchParams)
	{
		
		$this->db->from('product_category');
		if($searchParams['categoryName']!='')
		$this->db->like('name',$searchParams['categoryName']);
		$this->db->where('company_id', $this->session->userdata('company'));
		$res = $this->db->get();
		//echo $this->db->last_query();die();
		return $res->num_rows();
	}

	public function subCategoryResults($searchParams, $per_page, $current_offset)
	{
		
		$this->db->select('sub_category_id, name, status');
		$this->db->from('sub_category');
		if($searchParams['subCategoryName']!='')
		$this->db->like('name',$searchParams['subCategoryName']);
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('sub_category_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function subCategoryTotalRows($searchParams)
	{
		$this->db->select('sub_category_id');
		$this->db->from('sub_category');
		if($searchParams['subCategoryName']!='')
		$this->db->like('name',$searchParams['subCategoryName']);
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	public function subCategoryDetails($searchParams)
	{
		$this->db->select('*');
		$this->db->from('sub_category');
		$this->db->where('status', 1);
		if($searchParams['subCategoryName']!='')
		$this->db->like('name',$searchParams['subCategoryName']);
		$res = $this->db->get();
		return $res->result_array();
	}

	public function groupResults($searchParams, $per_page, $current_offset)
	{
		
		$this->db->select('pg.group_id, pg.name, pg.status, pg.description, pc.name as CategoryName');
		$this->db->from('product_group pg');
		if($searchParams['groupName']!='')
		$this->db->like('pg.name',$searchParams['groupName']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id', $searchParams['category_id']);
		if($searchParams['groupDescription']!='')
		$this->db->like('pg.description', $searchParams['groupDescription']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->join('product_category pc ','pc.category_id = pg.category_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('group_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function groupTotalRows($searchParams)
	{
		$this->db->select('pg.group_id');
		$this->db->from('product_group pg');
		if($searchParams['groupName']!='')
		$this->db->like('pg.name',$searchParams['groupName']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id', $searchParams['category_id']);
		if($searchParams['groupDescription']!='')
		$this->db->like('pg.description', $searchParams['groupDescription']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->join('product_category pc ','pc.category_id = pg.category_id');
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	public function groupDetails($searchParams)
	{
		
		$this->db->select('pg.*,pc.name as CategoryName,pc.category_id as category');
		$this->db->from('product_group pg');
		if($searchParams['groupName']!='')
		$this->db->like('pg.name',$searchParams['groupName']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id', $searchParams['category_id']);
		if($searchParams['groupDescription']!='')
		$this->db->like('pg.description', $searchParams['groupDescription']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->where('pg.status', 1);
		$this->db->join('product_category pc ','pc.category_id = pg.category_id');
		$this->db->order_by('group_id','DESC');
		$res = $this->db->get();
		$data = $res->result_array();
		$i = 0;
		foreach($res->result_array() as $row)
		{
			$data[$i]['competitors'] = '';
			$j = 0;
			$q = "SELECT c.name `competitor` from product_category pc
				inner join product_category_competitor pcc on pc.category_id = pcc.category_id
				inner join competitor c on c.competitor_id = pcc.competitor_id
				where pcc.status = 1 and c.status = 1 and pc.category_id = ".$row['category'];
			$r = $this->db->query($q);
			foreach($r->result_array() as $rr)
			{
				if($j > 0) $data[$i]['competitors'].=', ';
				$data[$i]['competitors'].=$rr['competitor'];
				$j++;
			}
			$i++;
		}
		return $data;
		
	}
	
	public function competitorResults($searchParams, $per_page, $current_offset)
	{
		$competitor_id=array('29');
		$this->db->select('competitor_id, name, rating, status');
		$this->db->from('competitor');
		if($searchParams['competitorName']!='')
		$this->db->like('name',$searchParams['competitorName']);
		$this->db->where_not_in('competitor_id',$competitor_id);
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('competitor_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	public function competitorTotalRows($searchParams)
	{
		$competitor_id=array('29');
		$this->db->from('competitor');
		if($searchParams['competitorName']!='')
		$this->db->like('name',$searchParams['competitorName']);
		$this->db->where_not_in('competitor_id',$competitor_id);
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	public function competitorDetails($searchParams)
	{
		$competitor_id=array('29');
		$this->db->select();
		$this->db->from('competitor');
		if($searchParams['competitorName']!='')
		$this->db->like('name',$searchParams['competitorName']);
		$this->db->where('status', 1);
		$this->db->where_not_in('competitor_id',$competitor_id);
		$res = $this->db->get();

		return $res->result_array();
	}
	
	/*Phase2 update: Prasad 09-08-2017*/
	public function productDetails($searchParams)
	{
		$this->db->select('p.*, pc.name as CategoryName, pg.name as GroupName, pg.description as groupDescription,pt.name as pt_name');
		$this->db->from('product p');
		if($searchParams['productName']!='')
		$this->db->like('p.name',$searchParams['productName']);
	    if($searchParams['product_type_id']!='')
		$this->db->like('p.product_type_id',$searchParams['product_type_id']);
		if($searchParams['group_id']!='')
		$this->db->where('p.group_id',$searchParams['group_id']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id',$searchParams['category_id']);
		if($searchParams['productDescription']!='')
		$this->db->like('p.description',$searchParams['productDescription']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->where('p.status', 1);
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('product_type pt','p.product_type_id=pt.product_type_id','left');
		
		$res = $this->db->get();
		return $res->result_array();
	}

	/*Phase2 udpate: Prasad 09-08-2017*/
	public function productResults($searchParams, $per_page, $current_offset)
	{
		
		$this->db->select('p.product_id, p.name, p.status, p.mrp, p.rrp,p.base_price, p.dp, pc.name as CategoryName, p.description, pg.name as GroupName,pt.name as pt_name');
		$this->db->from('product p');
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('product_type pt','p.product_type_id=pt.product_type_id','left');
		if($searchParams['productName']!='')
		$this->db->like('p.name',$searchParams['productName']);
		if($searchParams['group_id']!='')
		$this->db->where('p.group_id',$searchParams['group_id']);
	    if($searchParams['product_type_id']!='')
		$this->db->where('p.product_type_id',$searchParams['product_type_id']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id',$searchParams['category_id']);
		if($searchParams['productDescription']!='')
		$this->db->like('p.description',$searchParams['productDescription']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('product_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}
	
	/*Phase2 udpate: Prasad 09-08-2017*/
	public function productTotalRows($searchParams)
	{
		
		$this->db->from('product p');
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('product_type pt','p.product_type_id=pt.product_type_id','left');
		if($searchParams['productName']!='')
		$this->db->like('p.name',$searchParams['productName']);
		if($searchParams['group_id']!='')
		$this->db->where('p.group_id',$searchParams['group_id']);
	    if($searchParams['product_type_id']!='')
		$this->db->where('p.product_type_id',$searchParams['product_type_id']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id',$searchParams['category_id']);
		if($searchParams['productDescription']!='')
		$this->db->like('p.description',$searchParams['productDescription']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function demoProductResults($searchParams, $per_page, $current_offset)
	{
		
		$this->db->select('dpd.demo_product_id, dpd.status, dpd.location, dpd.serial_number, 
							concat(p.name, " (", p.description, ")") as ProductName, pc.name as CategoryName, 
							concat(pg.name, " (", pg.description, ")") as GroupName, l1.location as city,
							b.name as branch, l.location as region');
		$this->db->from('demo_product_details dpd');
		if($searchParams['location']!='')
		$this->db->like('dpd.location',$searchParams['location']);
		if($searchParams['serialNumber']!='')
		$this->db->like('dpd.serial_number',$searchParams['serialNumber']);
		if($searchParams['product_id']!='')
		$this->db->where('dp.product_id',$searchParams['product_id']);
		if($searchParams['group_id']!='')
		$this->db->where('p.group_id',$searchParams['group_id']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id',$searchParams['category_id']);
		if($searchParams['region_id']!='')
		$this->db->where('b.region_id',$searchParams['region_id']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->join('demo_product dp ','dp.demo_product_id = dpd.demo_product_id');
		$this->db->join('product p ','dp.product_id = p.product_id');
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('branch b ','b.branch_id = dpd.branch_id');
		$this->db->join('location l ','l.location_id = b.region_id');
		$this->db->join('location l1 ','l1.location_id = dpd.city_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('dp.demo_product_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoProductDetails($searchParams)
	{
		$this->db->select('dpd.location, dpd.serial_number, dpd.modified_by, dpd.modified_time, dpd.created_time,
						p.name as ProductName, p.description as ProductDescription, pc.name as CategoryName,  
							pg.name as GroupName, pg.description as GroupDescription, p.rrp, l1.location as city,
							b.name as branch, l.location as region, b.region_id as region_id');
		$this->db->from('demo_product_details dpd');
		if($searchParams['location']!='')
		$this->db->like('dpd.location',$searchParams['location']);
		if($searchParams['serialNumber']!='')
		$this->db->like('dpd.serial_number',$searchParams['serialNumber']);
		if($searchParams['product_id']!='')
		$this->db->where('dp.product_id',$searchParams['product_id']);
		if($searchParams['group_id']!='')
		$this->db->where('p.group_id',$searchParams['group_id']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id',$searchParams['category_id']);
		if($searchParams['region_id']!='')
		$this->db->where('b.region_id',$searchParams['region_id']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->where('dpd.status', 1);
		$this->db->join('demo_product dp ','dp.demo_product_id = dpd.demo_product_id');
		$this->db->join('product p ','dp.product_id = p.product_id');
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('branch b ','b.branch_id = dpd.branch_id');
		$this->db->join('location l ','l.location_id = b.region_id');
		$this->db->join('location l1 ','l1.location_id = dpd.city_id');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function demoProductTotalRows($searchParams)
	{
		$this->db->from('demo_product_details dpd');
		if($searchParams['location']!='')
		$this->db->like('dpd.location',$searchParams['location']);
		if($searchParams['serialNumber']!='')
		$this->db->like('dpd.serial_number',$searchParams['serialNumber']);
		if($searchParams['product_id']!='')
		$this->db->where('dp.product_id',$searchParams['product_id']);
		if($searchParams['group_id']!='')
		$this->db->where('p.group_id',$searchParams['group_id']);
		if($searchParams['category_id']!='')
		$this->db->where('pc.category_id',$searchParams['category_id']);
		if($searchParams['region_id']!='')
		$this->db->where('b.region_id',$searchParams['region_id']);
		$this->db->where('pc.company_id', $this->session->userdata('company'));
		$this->db->join('demo_product dp ','dp.demo_product_id = dpd.demo_product_id');
		$this->db->join('product p ','dp.product_id = p.product_id');
		$this->db->join('product_group pg ','p.group_id = pg.group_id');
		$this->db->join('product_category pc ','pg.category_id = pc.category_id');
		$this->db->join('branch b ','b.branch_id = dpd.branch_id');
		$this->db->join('location l ','l.location_id = b.region_id');
		$this->db->join('location l1 ','l1.location_id = dpd.city_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function getDemoProduct($product_id)
	{
		$data = [];
		$this->db->select('dpd.demo_product_id as demoProductId, dpd.serial_number as serialNumber, dpd.location as location, p.name as ProductName');
		$this->db->from('demo_product_details dpd');
		$this->db->where('dp.product_id',$product_id);
		$this->db->where('dpd.status',1);
		$this->db->join('demo_product dp ','dp.demo_product_id = dpd.demo_product_id');
		$this->db->join('product p ','dp.product_id = p.product_id');
		$this->db->order_by('dp.demo_product_id','DESC');
		$res = $this->db->get();
		foreach($res->result_array() as $row)
        {
            $data[$row['demoProductId']] = $row['ProductName'].' ('.$row['serialNumber'].' - '.$row['location'].')';
        }
        return $data;
	}

	public function checkCategoryAvailability($data)
	{
		$this->db->from('product_category pc');
		$this->db->where('name', $data['name']);
		$this->db->where_not_in('category_id', $data['category_id']);
		$res = $this->db->get();
		return $res->num_rows();	
	}

	public function checkGroupAvailability($data)
	{
		$this->db->from('product_group pg');
		$this->db->where('name', $data['name']);
		$this->db->where_not_in('group_id', $data['group_id']);
		$res = $this->db->get();
		return $res->num_rows();	
	}

	public function checkCompetitorAvailability($data)
	{
		$this->db->from('competitor c');
		$this->db->where('name', $data['name']);
		$this->db->where_not_in('competitor_id', $data['competitor_id']);
		$res = $this->db->get();
		return $res->num_rows();	
	}

	public function checkProductAvailability($data)
	{
		$this->db->from('product p');
		$this->db->where('name', $data['name']);
		$this->db->where_not_in('product_id', $data['product_id']);
		$res = $this->db->get();
		return $res->num_rows();	
	}

	public function checkDemoProductSerialNumberAvailability($data)
	{
		$this->db->from('demo_product_details dpd');
		$this->db->where('serial_number', $data['serial_number']);
		$this->db->where_not_in('demo_product_id', $data['demo_product_id']);
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function getUsersByProductCategory($category_id = 0)
	{
		$this->db->select('up.user_id');
		$this->db->from('user_product up');
		$this->db->where('up.status',1);
		$this->db->where('pg.category_id',$category_id);
		$this->db->join('product p ','p.product_id = up.product_id');
		$this->db->join('product_group pg ','pg.group_id = p.group_id');
		$this->db->group_by('up.user_id');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function getNewPCUsers()
	{
		$this->db->select('user_id');
		$this->db->from('user');
		$this->db->where_in('role_id', array(7,9,10,11));
		$this->db->where('status', 1);
		$res = $this->db->get();
		return $res->result_array();
	}

	/*mahesh code: 24-12-2017 updted on:11-01-2019 12:57*/
	function getLoggedInUserProductGroupsDropdown($category_id)
	{
		$this->db->select('pg.*');
		$this->db->from('product_group pg');
		$this->db->join('product p','pg.group_id = p.group_id');
		if($this->session->userdata('role_id')!=2) // If not admin get only assigned product groups
		{
			$this->db->join('user_product up','p.product_id = up.product_id and up.status = 1');
			$this->db->where('up.user_id',$this->session->userdata('user_id'));
		}
		$this->db->where('pg.category_id',$category_id);
		$this->db->group_by('pg.group_id');
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			$data = array();
			foreach($res->result_array() as $row)
                {
                    $data[$row['group_id']] = $row['name'];
                }
                return $data;
		}
		return 0;
	}
	function getProductGroupsDropdown($category_id)
	{
		$this->db->select('pg.*');
		$this->db->from('product_group pg');
		$this->db->where('pg.category_id',$category_id);
		$this->db->group_by('pg.group_id');
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			$data = array();
			foreach($res->result_array() as $row)
                {
                    $data[$row['group_id']] = $row['name'];
                }
                return $data;
		}
		return 0;
	}

	/*mahesh code: 24-12-2017 updated on : 01-03-2018 22:18 PM*/
	function getLoggedInUserProductsDropdown($group_id)
	{
		$this->db->select('p.*');
		$this->db->from('product p');
		$this->db->join('user_product up','p.product_id = up.product_id and up.status = 1');
		$this->db->where('up.user_id',$this->session->userdata('user_id'));
		$this->db->where('p.group_id',$group_id);
		$this->db->where('p.availability',1);
		$this->db->group_by('p.product_id');
		$res = $this->db->get();
		if($res->num_rows()>0)
		{
			$data = array();
			foreach($res->result_array() as $row)
                {
                    $data[$row['product_id']] = $row['name'].'('.$row['description'].')';
                }
                return $data;
		}
		return 0;
	}
}