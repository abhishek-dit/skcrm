<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends CI_Model {

	public function userResults($current_offset, $per_page, $searchParams)
	{
		
		$this->db->select('u.*,r.name as role, b.name as branch');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('branch b','b.branch_id=u.branch_id','INNER');
		//$this->db->where('u.status',1);
		$not_include_roles = array(1,2,13); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		// if($this->session->userdata('company')!='')
		// $this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		if($searchParams['user_status']!='')
		$this->db->like('u.status',$searchParams['user_status']);
		if($searchParams['companyName']!='')
		$this->db->where('u.user_id',$searchParams['companyName']);
		$this->db->order_by('u.user_id','DESC');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		return $res->result_array();
	}
	
	public function userTotalRows($searchParams)
	{
		$this->db->select('u.*,r.name as role');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		//$this->db->where('u.status',1);
		$not_include_roles = array(1,2,13); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		// if($this->session->userdata('company')!='')
		// $this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		if($searchParams['user_status']!='')
			$this->db->like('u.status',$searchParams['user_status']);
		if($searchParams['companyName']!='')
		$this->db->where('u.user_id',$searchParams['companyName']);
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	public function userDetails($searchParams)
	{
		
		$this->db->select('u.*,d.*,r.name as role');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->join('distributor_details d','d.user_id = u.user_id','LEFT');
		//$this->db->where('u.status',1);
		$not_include_roles = array(1,2,13); // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
		$this->db->where_not_in('u.role_id',$not_include_roles);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		if($searchParams['user_status']!='')
			$this->db->like('u.status',$searchParams['user_status']);
		$this->db->order_by('u.user_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	// GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
	public function getAdminRoles(){
		$this->db->where('status',1);
		$this->db->where_not_in('role_id',array(1,2,13));
		$res = $this->db->get('role');
		return $res->result_array();
	}

	// GET Location Details by ID
	public function getLocationDetailsById($locationId){
		$this->db->where('location_id',$locationId);
		$res = $this->db->get('location');
		return $res->row_array();
	}

	// GET locations hirarchy traversing from bottom to top
	public function getParentHirarchy($locationId){
		
		$loc_hirarchy=array();
		$location = $this->getLocationDetailsById($locationId);
		$loc_hirarchy[]=$location;
		while ($location['parent_id']!=NULL) {
			
			$location = $this->getLocationDetailsById($location['parent_id']);
			$loc_hirarchy[]=$location;
			/*if($location['parent_id']==NULL)
				return false;
			else {
				$loc_hirarchy[]=$location;
				$loc_hirarchy[]=$this->getParentRecursive($location['parent_id'],true);
			}*/
		}
		$loc_hirarchy=array_reverse($loc_hirarchy);
		return $loc_hirarchy;

	}
	
	/* GET USER PRODUCTS
	** @param: products array
	** return: product groups(array)
	** author: mahesh created on: 27th june 2:45 PM updated on:
	*/
	public function getProdocutIdsListByUserId($userID){
		$this->db->select('GROUP_CONCAT(p.product_id) AS productIds_list');
		$this->db->from('product p');
		$this->db->join('user_product up','up.product_id=p.product_id');
		$this->db->where('up.user_id',$userID);
		$this->db->where('p.company_id',$this->session->userdata('company'));
		$this->db->where('up.status',1);
		$res = $this->db->get();
		$data = $res->row_array();
		return $data['productIds_list'];
	}

	/* GET UNIQUE PRODUCT GROUPS BY PRODUCTS
	** @param: products array
	** return: product group Ids(string)
	** author: mahesh created on: 27th june 2:55 PM updated on:
	*/
	public function getUniqueProductGroupsByProdocuts($products=array()){
		if(count($products)>0){
			$this->db->select('GROUP_CONCAT(DISTINCT(p.group_id)) AS productGroupIds_list');
			$this->db->from('product p');
			$this->db->where_in('p.product_id',$products);
			$this->db->where('p.company_id',$this->session->userdata('company'));
			//$this->db->group_by('p.group_id');
			$res = $this->db->get();
			$data = $res->row_array();
			//echo $this->db->last_query();
			return $data['productGroupIds_list'];
		}
	}

	/* GET UNIQUE PRODUCT CATEGORIES BY PRODUCT GROUPS
	** @param: product categories array
	** return: product group Ids(array)
	** author: mahesh created on: 27th june 3:05PM updated on:
	*/
	public function getUniqueProductCategoreisByProdocutGroups($productGroups=array()){
		if(count($productGroups)>0){
			$this->db->distinct();
			$this->db->select('pc.*');
			$this->db->from('product_group pg');
			$this->db->join('product_category pc','pc.category_id=pg.category_id','INNER');
			$this->db->where_in('pg.group_id',$productGroups);
			$this->db->where('pc.company_id',$this->session->userdata('company'));
			$res = $this->db->get();
			return $res->result_array();
		}
	}

	/* GET PRODUCT GROUPS BY CATEGORY
	** @param: product category(int)
	** @param: product groups(array)
	** return: product groups(array)
	** author: mahesh created on: 27th june 3:30PM updated on:
	*/
	public function getProductGroupsByCategory($productCategoryID,$in_productGroups=array()){
		if(count($productCategoryID)>0){
			$this->db->select('pg.*');
			$this->db->from('product_group pg');
			$this->db->join('product_category pc','pc.category_id=pg.category_id','INNER');
			$this->db->where('pc.company_id',$this->session->userdata('company'));
			$this->db->where('pg.category_id',$productCategoryID);
			if(count($in_productGroups)>0)
			$this->db->where_in('pg.group_id',$in_productGroups);
			$res = $this->db->get();
			return $res->result_array();
		}
	}

	/* GET PRODUCTsS BY GROUP
	** @param: product group(int)
	** @param: products(array)
	** return: product groups(array)
	** author: mahesh created on: 27th june 3:30PM updated on:
	*/
	public function getProductsByGroup($groupId,$in_products=array()){
		if(count($groupId)>0){
			$this->db->select('*');
			$this->db->from('product');
			$this->db->where('group_id',$groupId);
			$this->db->where('company_id',$this->session->userdata('company'));
			if(count($in_products)>0)
			$this->db->where_in('product_id',$in_products);
			$res = $this->db->get();
			return $res->result_array();
		}
	}

	/* GET ROLES of SALES ENGINEER,RSM,RBH,NSM,COUNTRY HEAD,SALES DIRECTOR,GLOBAL HEAD which are having product targets
	** @param: current_offset(int)
	** @param: per_page(int)
	** @param: searchParams(array)
	** return: roles result array(array)
	** author: mahesh created on: 30th june 3:12PM updated on:
	*/
	public function getProductTargetRoles(){
		$this->db->where('status',1);
		$this->db->where_in('role_id',get_productTargetRoles());
		$res = $this->db->get('role');
		return $res->result_array();
	}

	/* GET PRODUCT TARGET USER RESULTS FOR THE CURRENT PAGE
	** @param: current_offset(int)
	** @param: per_page(int)
	** @param: searchParams(array)
	** return: users result array(array)
	** author: mahesh created on: 30th june 3:15PM updated on:
	*/
	public function productTargetUserResults($current_offset, $per_page, $searchParams)
	{
		
		$this->db->select('u.*,r.name as role');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->where('u.status',1);
		$include_roles = get_productTargetRoles(); // GET product target roles
		$this->db->where_in('u.role_id',$include_roles);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		$this->db->order_by('u.user_id');
		$this->db->limit($per_page, $current_offset);
		$res = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		return $res->result_array();
	}
	
	/* GET PRODUCT TARGET USER TOTAL ROWS COUNT
	** @param: searchParams(array)
	** return: users TOTAL ROWS(INT)
	** author: mahesh created on: 30th june 3:20PM updated on:
	*/
	public function productTargetUserTotalRows($searchParams)
	{
		
		$this->db->select('u.*,r.name as role');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		$this->db->where('u.status',1);
		$include_roles = get_productTargetRoles(); // GET product target roles
		$this->db->where_in('u.role_id',$include_roles);
		//$this->db->where('u.status',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		$res = $this->db->get();
		return $res->num_rows();
	}
	
	/* GET PRODUCT TARGET USER TOTAL ROWS
	** @param: searchParams(array)
	** return: users TOTAL ROWS(ARRAY)
	** author: mahesh created on: 30th june 3:25PM updated on:
	*/
	public function productTargetUserDetails($searchParams)
	{
		
		$this->db->select('u.*,r.name as role');
		$this->db->from('user u');
		$this->db->join('role r','r.role_id=u.role_id','INNER');
		//$this->db->where('u.status',1);
		$include_roles = get_productTargetRoles(); // GET product target roles
		$this->db->where_in('u.role_id',$include_roles);
		$this->db->where('u.status',1);
		if($this->session->userdata('company')!='')
		$this->db->where('u.company_id',$this->session->userdata('company'));
		if($searchParams['user_role']!='')
			$this->db->where('u.role_id',$searchParams['user_role']);
		if($searchParams['user_name']!='')
			$this->db->where('concat(u.first_name, " ", u.last_name) like "%'.$searchParams['user_name'].'%"');
		if($searchParams['employeeId']!='')
			$this->db->like('u.employee_id',$searchParams['employeeId']);
		if($searchParams['email']!='')
			$this->db->like('u.email_id',$searchParams['email']);
		if($searchParams['mobile']!='')
			$this->db->like('u.mobile_no',$searchParams['mobile']);
		$this->db->order_by('u.user_id');
		$res = $this->db->get();
		//echo $this->db->last_query(); die();
		return $res->result_array();
	}

	/* GET USER PRODUCTS
	** @param: userID(int)
	** return: products(array)
	** author: mahesh created on: 30th june 03:51 PM updated on:
	*/
	public function getUserProducts($userID){
		$this->db->select('p.*');
		$this->db->from('product p');
		$this->db->join('user_product up','up.product_id=p.product_id');
		$this->db->join('product_group pg','pg.group_id=p.group_id');
		$this->db->join('product_category pc','pc.category_id=pg.category_id');
		$this->db->where('up.user_id',$userID);
		$this->db->where('up.status',1);
		$this->db->where('p.status',1);
		$this->db->where('p.target',1);
		$res = $this->db->get();
		return $res->result_array();
	}

	/* GET USER PRODUCT Targets for an year
	** @param: userID(int)
	** @param: year(int) default: current year
	** return: products(array)
	** author: mahesh created on: 1st juyly 12:50 PM updated on: 12th july 2016 12:30 PM
	*/
	public function getUserProductTargets($userID,$financial_years=array()){
		if(count($financial_years)==0){ // if financial years not specified get current financial years targets
			$current_month = date('m');
			$current_year = date('Y');
			if($current_month<=3){
				$yr1 = $current_year-1;
				$yr2 = $current_year;
			}
			else{
				$yr1 = $current_year;
				$yr2 = $current_year+1;
			}
			$financial_years = array($yr1,$yr2);
		}
		
		$this->db->select();
		$this->db->from('user_product_target');
		$this->db->where('user_id',$userID);
		$this->db->where_in('year_id',$financial_years);
		$this->db->where('status',1);
		$res = $this->db->get();
		return $res->result_array();
	}

	/* GET USER PRODUCT Targets for a particular month and year
	** @param: userID(int)
	** @param: year(int)
	** @param: month(int) 
	** return: products(array)
	** author: mahesh created on: 1st juyly 12:50 PM updated on: 12th july 2016 12:30 PM
	*/
	public function getUserProductTargetsForMonth($userID,$month,$year){
		
		
		$this->db->select('sum(quantity) quantity, product_id');
		$this->db->from('user_product_target');
		$this->db->where('user_id',$userID);
		if($month != 0)
		{
			$this->db->where('month_id',$month);
			$this->db->where('year_id',$year);
		}
		else
		{
			$this->db->where('((year_id = '.$year.' AND month_id > 3) OR (year_id = '.($year + 1).' AND month_id < 4))');
		}
		$this->db->where('status',1);
		$this->db->group_by('product_id');
		$res = $this->db->get();
		//echo $this->db->last_query(); die();
		return $res->result_array();

	}

	/* Phase2  by Prasad  start */
	public function getUserWeeklyProductTargets($userID,$fy_id)
	  {	
		$this->db->select('*');
		$this->db->from('custom_fy_week fw');
		$this->db->join('weekly_user_product_target upt','fw.fy_week_id=upt.fy_week_id');
		$this->db->where('upt.user_id',$userID);
		$this->db->where('fw.fy_id',$fy_id);
		$this->db->where('fw.status',1);
		$res = $this->db->get();
		return $res->result_array();
	}


	public function get_last_punch_in($user_id)
	{   
		$var = FALSE;
		$this->db->select('*');
		$this->db->from('punch_in');
		$this->db->where('user_id',$user_id);
		$this->db->order_by('punch_in_id','DESC');
		$this->db->limit(1);
		$res= $this->db->get();
		$result = $res->row_array();
		if(count($result)>0)
		{
			if(@$result['end_time']=='NULL' || @$result['end_time']=='')
			{
				$var = TRUE;
			}
		}
		return $var;
	}

	/* Phase2  by Prasad  end */
}