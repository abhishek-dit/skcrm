<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Base_controller.php';
class Ajax_ci extends Base_controller {
	function __construct(){
		parent::__construct();
								
		$this->load->model('Ajax_m','',TRUE);
	}//__construct()
	
	/**
	* get locations by parent
	* author: mahesh , created on: 17th june 2016 12:00 PM, updated on: --
	* params: $parentId(int)
	* return: $locations(array)
	**/
	function getCountriesByGeo(){
		$locationParentId = $this->input->post('locationParentId');
		$role_level_id = $this->input->post('role_level_id');
		$data = '';
		$locations = $this->Ajax_m->getLocationsByParent($locationParentId);
			if($role_level_id>4)
			$data .= '<option value="">Select Country</option>';
			foreach($locations as $location) {
				if($role_level_id==3||$role_level_id==4){
				$data .= '<div class="radio"><label> <input type="checkbox" name="country[]" value="'.$location['location_id'].'" class="icheck"> '.$location['location'].'</label></div>';
				}
				else {
					$data .= '<option value="'.$location['location_id'].'">'.$location['location'].'</option>';
				}
			}
		
		echo $data;
  	}
	
	/**
	* get locations by parent
	* author: mahesh , created on: 17th june 2016 12:00 PM, updated on: --
	* params: $parentId(int)
	* return: $locations(array)
	**/
	function getRegionsByCountry(){
		$locationParentId = $this->input->post('locationParentId');
		$role_level_id = $this->input->post('role_level_id');
		$data = '';
		$locations = $this->Ajax_m->getLocationsByParent($locationParentId);
			
			$data .= '<option value="">Select Region</option>';
			foreach($locations as $location) {
				$data .= '<option value="'.$location['location_id'].'">'.$location['location'].'</option>';
			}
		
		echo $data;
  	}
	
	/**
	* get States By by parent
	* author: mahesh , created on: 17th june 2016 12:00 PM, updated on: --
	* params: $parentId(int)
	* return: $locations(array)
	**/
	function getStatesByRegion(){
		$locationParentId = $this->input->post('locationParentId');
		$role_level_id = $this->input->post('role_level_id');
		$data = '';
		$locations = $this->Ajax_m->getLocationsByParent($locationParentId);
			
			foreach($locations as $location) {
				$data .= '<div class="radio"><label> <input type="checkbox" name="state[]" value="'.$location['location_id'].'" class="icheck state_cb"> '.$location['location'].'</label></div>';
			}
		
		echo $data;
  	}
	
	/**
	* get Districts By by parent
	* author: mahesh , created on: 17th june 2016 12:00 PM, updated on: --
	* params: $parentId(int)
	* return: $locations(string)
	**/
	function getDistrictsByState(){
		$states = @$this->input->post('states');
		$current_districts = @$this->input->post('current_districts');
		/*if($current_districts=='')
		$current_districts = array();*/
		$role_level_id = $this->input->post('role_level_id');
		$data = '';
		foreach($states as $stateId) {
			
		$locations = $this->Ajax_m->getLocationsByParent($stateId);
			$locationName = $this->Ajax_m->getLocationNameById($stateId);
			$data .= '<h5>'.$locationName.'</h5>';
			
			foreach($locations as $location) {
				$chkd = (is_array($current_districts)&&in_array($location['location_id'],$current_districts))?'checked':'';
				$data .= '<div class="radio"><label> <input type="checkbox" name="district[]" value="'.$location['location_id'].'" '.$chkd.' class="icheck district_cb"> '.$location['location'].'</label></div>';
				$data .= '<input type="hidden" name="district_parent['.$location['location_id'].']" value="'.$stateId.'">';
			}
		}
		
		echo $data;
  	}
	
	/**
	* get Cities By by parent
	* author: mahesh , created on: 17th june 2016 12:00 PM, updated on: --
	* params: $parentId(int)
	* return: $locations(string)
	**/
	function getCitiesByDistrict(){
		$districts = @$this->input->post('districts');
		$current_cities = @$this->input->post('current_cities');
		$role_level_id = $this->input->post('role_level_id');
		$states = @$this->input->post('states');
		$data = '';
		// SORT STATES BY NAME
		$this->db->where_in('location_id',$states);
		$this->db->order_by('location','ASC');
		$sres = $this->db->get('location');
		foreach($sres->result_array() as $state)
		{
			$data .= '<h5>'.$state['location'].'</h5>';
			// SORT DISTRICTS BY NAME
			$this->db->where_in('parent_id',$state['location_id']);
			$this->db->where_in('location_id',$districts);
			$this->db->order_by('location','ASC');
			$dres = $this->db->get('location');
			foreach($dres->result_array() as $district) {
				
			$locations = $this->Ajax_m->getLocationsByParent($district['location_id']);
			$data .= '<h6>'.$district['location'].'</h6>';
			
				foreach($locations as $location) {
					$chkd = (is_array($current_cities)&&in_array($location['location_id'],$current_cities))?'checked':'';
					$data .= '<div class="radio"><label> <input type="checkbox" name="city[]" value="'.$location['location_id'].'" '.$chkd.' class="icheck city_cb"> '.$location['location'].'</label></div>';
					$data .= '<input type="hidden" name="city_parent['.$location['location_id'].']" value="'.$district['location_id'].'">';
				}
			}
		}
		
		echo $data;
  	}
	
	/**
	* get Product groups by categories
	* author: mahesh , created on: 20th june 2016 12:45 PM, updated on: --
	* params:
	* return: $productgroups(string)
	**/
	function getPorductGroupsByCategories(){
		$productCategories = @$this->input->post('productCategories');
		//$current_districts = @$this->input->post('current_districts');

		$data = '';
		foreach($productCategories as $productCatId) {
			
			$productGroups = $this->Ajax_m->getPorductGroupsByCategory($productCatId);
			$productCategoryName = $this->Ajax_m->getProductCategoryNameById($productCatId);
			$data .= '<h5><input type="checkbox" name="chkAllPG" value="1" id="prodCat'.$productCatId.'" checked class="icheck chkAllPG"> '.$productCategoryName.'</h5>';
			//$data .= '<div class="radio"><label> <input type="checkbox" name="chkAllPG" value="1" id="prodCat'.$productCatId.'" checked class="icheck chkAllPG"> All</label></div>';
			foreach($productGroups as $productGroup) {
				//$chkd = (is_array($current_districts)&&in_array($location['location_id'],$current_districts))?'checked':'';
				$data .= '<div class="radio"><label> <input type="checkbox" name="productGroup[]" value="'.$productGroup['group_id'].'" checked class="icheck productGroup prodCat'.$productCatId.'"> '.$productGroup['name'].'</label></div>';
			}
		}
		
		echo $data;
  	}
	
	/**
	* get Products by groups
	* author: mahesh , created on: 20th june 2016 01:22 PM, updated on: --
	* params:
	* return: $products(string)
	**/
	function getPorductsByGroup(){
		$productCategories = @$this->input->post('productCategories');
		$productGroups = @$this->input->post('productGroups');
		//$current_districts = @$this->input->post('current_districts');

		$data = '';
		foreach($productCategories as $productCatId) {
			
			// SORT Groups BY NAME
			$this->db->where_in('group_id',$productGroups);
			$this->db->where('category_id',$productCatId);
			$this->db->where('status',1);
			$this->db->order_by('name','ASC');
			$pg_res = $this->db->get('product_group');
			//$productGroups = $this->Ajax_m->getPorductGroupsByCategory($productCatId);
			$productCategoryName = $this->Ajax_m->getProductCategoryNameById($productCatId);
			$data .= '<h5><input type="checkbox" name="chkAllPG" value="1" id="prodCat'.$productCatId.'" checked class="icheck chkAllPG"> '.$productCategoryName.'</h5>';
			//$data .= '<div class="radio"><label> <input type="checkbox" name="chkAllPG" value="1" id="prodCat'.$productCatId.'" checked class="icheck chkAllPG"> All</label></div>';
			foreach($pg_res->result_array() as $productGroup) {
				$data .= '<h6><input type="checkbox" name="chkAllProd" value="1" id="prodGroup'.$productGroup['group_id'].'" checked class="icheck chkAllProd"> '.$productGroup['name'].'</h6>';
				// SORT Products BY NAME
				$this->db->where('group_id',$productGroup['group_id']);
				$this->db->where('status',1);
				$this->db->order_by('name','ASC');
				$p_res = $this->db->get('product');
				foreach($p_res->result_array() as $product) {
					//$chkd = (is_array($current_districts)&&in_array($location['location_id'],$current_districts))?'checked':'';
					$data .= '<div class="radio"><label> <input type="checkbox" name="product[]" value="'.$product['product_id'].'" checked class="icheck product pr prodGroup'.$productGroup['group_id'].' prodCat'.$productCatId.'"> '.$product['description'].'</label></div>';
				}
			}
		}
		
		echo $data;
  	}

  	/**
	* check employee id exist or not
	* author: mahesh , created on: 23rd june 2016 05:40 PM, updated on: --
	* @param: $employee_id(string)
	* @param: $user_id(int)
	* return: 1/0(boolean)
	**/
	function is_employeeIdExist(){
		$employee_id = $this->input->post('employee_id');
		$user_id = $this->input->post('user_id');
		$data = '';
		echo $this->Ajax_m->is_employeeIdExist($employee_id,$user_id);
  	}

  	/**
	* check employee email exist or not
	* author: mahesh , created on: 23rd june 2016 07:00 PM, updated on: --
	* @param: $email(string)
	* @param: $user_id(int)
	* return: 1/0(boolean)
	**/
	function is_employeeEmailExist(){
		$email = $this->input->post('email');
		$user_id = $this->input->post('user_id');
		$data = '';
		echo $this->Ajax_m->is_employeeEmailExist($email,$user_id);
  	}

  	/**
	* Get competitors by product category
	* author: mahesh , created on: 5th july 2016 04:30 PM, updated on: --
	* @param: $product_category(string)
	* return: competitors_options_list(String)
	**/
	function getCompetitorsByProductCategory(){
		$category_id = $this->input->post('category_id');
		$data = '<option value="">Select competitors</option>';
		$competitors =  $this->Ajax_m->getCompetitorsByProductCategory($category_id);
		if($competitors){
			foreach ($competitors as $competitor_row) {
				$data .= '<option value="'.$competitor_row['competitor_id'].'">'.$competitor_row['name'].'</option>';
			}
		}
		echo $data;
  	}

  	//mahesh 7th july 04:52 pm
	public function checkLocations_assignInactiveUserOpenLeads(){

		//print_r($_POST);
		$assign_user = $this->input->post('assign_user');
		$user_locations = getUserLocations($assign_user);
		$leads = $this->input->post('lead');
		$lead_customer_location = $this->input->post('lead_customer_location');
		$non_location_leads = array();
		foreach ($leads as $lead_id) {
			$lead_location = $lead_customer_location[$lead_id];
			if(!in_array($lead_location, $user_locations)){
				$non_location_leads[] = $lead_id;
			}
		}
		if(count($non_location_leads)>0){

			if(count($non_location_leads)==1)
				echo 'Lead ID '.implode(',', $non_location_leads).' customer location is not under the selected user';
			else
				echo 'Lead ID(s) '.implode(',', $non_location_leads).' customer locations are not under the selected user';
		}

	}


	/**
	* get countries by multi geos
	* author: mahesh , created on: 8th july 2016 06:15 PM, updated on: --
	* params: $parentIds(string)
	* return: $locations(array)
	**/
	function getCountriesByGeoMulti(){
		$locationParentIds_str = $this->input->post('locationParentId');
		$data = '';
		$geo_arr = explode(',', $locationParentIds_str);
		//removing empty/null elements if any
		$geo_arr = array_filter($geo_arr);
		//looping geos array
		foreach($geo_arr as $geoId){

			$locations = $this->Ajax_m->getLocationsByParent($geoId);
			
			$data .= '<option value="">Select Country</option>';
			foreach($locations as $location) {
				$data .= '<option value="'.$location['location_id'].'">'.$location['location'].'</option>';
			}
		}
		
		
		echo $data;
  	}
	
	/**
	* get regions by multi countries
	* author: mahesh , created on: 8th july 2016 06:20 PM, updated on: --
	* params: $parentIds(string)
	* return: $locations(array)
	**/
	function getRegionsByCountryMulti(){
		$locationParentIds_str = $this->input->post('locationParentId');
		$data = '';
		$countries_arr = explode(',', $locationParentIds_str);
		//removing empty/null elements if any
		$countries_arr = array_filter($countries_arr);
		//looping geos array
		foreach($countries_arr as $countryId){

			$locations = $this->Ajax_m->getLocationsByParent($countryId);
			
			$data .= '<option value="">Select Country</option>';
			foreach($locations as $location) {
				$data .= '<option value="'.$location['location_id'].'">'.$location['location'].'</option>';
			}
		}
		
		
		echo $data;
  	}

	/**
	* get States By by multiple regions
	* author: mahesh , created on: 8th july 2016 05:53 PM, updated on: --
	* params: $parentId(int)
	* return: $locations(array)
	**/
	function getStatesByRegionMulti(){
		$locationParentIds_str = $this->input->post('locationParentIds');
		$data = '';
		$region_arr = explode(',', $locationParentIds_str);
		//removing empty/null elements if any
		$region_arr = array_filter($region_arr);
		//looping regions array
		foreach ($region_arr as $regionId) {
			$regionName = $this->Ajax_m->getLocationNameById($regionId);
			$data .= '<h5>'.$regionName.'</h5>';
			$locations = $this->Ajax_m->getLocationsByParent($regionId);
			foreach($locations as $location) {
				$data .= '<div class="radio"><label> <input type="checkbox" name="state[]" value="'.$location['location_id'].'" class="icheck state_cb"><input type="hidden" value="'.$location['parent_id'].'" name="state_parent['.$location['location_id'].']"> '.$location['location'].'</label></div>';
			}
		}
		
		
		echo $data;
  	}
	
	
	/**
	* get Contacts by speciality, locations
	* author: mahesh , created on: 9th july 4:25 PM, updated on: --
	* params: 
	* return: $contacts(json)
	**/
	function getContactsByLocationSpeciality(){
		$specialities = $this->input->post('speciality_id');
		if($specialities)
		$specialities = array_filter(@$specialities);
		$geo = $this->input->post('geo',TRUE);
		$country = $this->input->post('country',TRUE);
		if(!$country) //  if no countries existed
		{
			goto merge_locations;
		}
		else {
			foreach($country as $countryId){
				$location = getLocationById($countryId);
				if (($key = array_search($location['parent_id'], $geo)) !== false) {
					unset($geo[$key]);
				}
			}
		}
		
		$region = $this->input->post('region',TRUE);
		if(!$region) //  if no regions existed
		{
			goto merge_locations;
		}
		else {
			foreach($region as $regionId){
				$location = getLocationById($regionId);
				if (($key = array_search($location['parent_id'], $country)) !== false) {
					unset($country[$key]);
				}
			}
		}
		
		$state = $this->input->post('state',TRUE);
		if(!$state) //  if no states existed
		{
			goto merge_locations;
		}
		else {
			$state_parent = $this->input->post('state_parent',TRUE);
			foreach($state as $stateId){
				$state_parentId = $state_parent[$stateId];
				if (($key = array_search($state_parentId, $region)) !== false) {
					unset($region[$key]);
				}
			}
		}
		
		$district = $this->input->post('district',TRUE);
		if(!$district) //  if no districts existed
		{
			goto merge_locations;
		}
		else {
			$district_parent = $this->input->post('district_parent',TRUE);
			foreach($district as $districtId){
				$district_parentId = $district_parent[$districtId];
				if (($key = array_search($district_parentId, $state)) !== false) {
					unset($state[$key]);
				}
			}
		}
		
		$city = $this->input->post('city',TRUE);
		if(!$city) //  if no cities existed
		{
			goto merge_locations;
		}
		else {
			$city_parent = $this->input->post('city_parent',TRUE);
			foreach($city as $cityId){
				$city_parentId = $city_parent[$cityId];
				if (($key = array_search($city_parentId, $district)) !== false) {
					unset($district[$key]);
				}
			}
		}
		
		merge_locations:
		if(count(@$geo)>0){
			foreach($geo as $geoId){
			$user_locations[$geoId]=array('territory_level_id'=>2,'location_id'=>$geoId);
			}
		}
		if(count(@$country)>0){
			foreach($country as $countryId){
			$user_locations[$countryId]=array('territory_level_id'=>3,'location_id'=>$countryId);
			}
		}
		if(count(@$region)>0){
			foreach($region as $regionId){
			$user_locations[$regionId]=array('territory_level_id'=>4,'location_id'=>$regionId);
			}
		}
		if(@$state){
			foreach(@$state as $stateId){
			$user_locations[$stateId]=array('territory_level_id'=>5,'location_id'=>$stateId);
			}
		}
		if(@$district){
			foreach(@$district as $districtId){
			$user_locations[$districtId]=array('territory_level_id'=>6,'location_id'=>$districtId);
			}
		}
		if(@$city){
			foreach(@$city as $cityId){
			$user_locations[$cityId]=array('territory_level_id'=>7,'location_id'=>$cityId);
			}
		}
		
		//print_r($user_locations);
		$locations = array();
		if(count($user_locations)>0){
			
			foreach($user_locations as $res1)
			{
				$territory_level_id = $res1['territory_level_id'];
				$loc = $res1['location_id'];
				$level = 7 - $territory_level_id;
				$q2 = 'SELECT l'.$level.'.location_id as location from location l0';
				for($i = 1; $i <= $level; $i++)
				{
					$j = $i - 1;
					$q2 .= ' left join location l'.$i.' on l'.$j.'.location_id = l'.$i.'.parent_id';
				}
				$q2 .= ' where l0.location_id = "'.$loc.'" order by l'.$level.'.location_id';
				
				$r2 = $this->db->query($q2);
				foreach($r2->result_array() as $res2)
				{
					$locations[] = $res2['location'];
				}
				//echo $q2; die();
			}	
		}
		//print_r($locations);
		
		//FETCHING CONTACTS EMAILS QUERY
		$this->db->select('GROUP_CONCAT(email) AS contact_emails');
		$this->db->from('contact c');
		$this->db->join('customer_location_contact clc','clc.contact_id = c.contact_id');
		if(count($specialities)>0)
		$this->db->where_in('c.speciality_id',$specialities);
		if(count($locations)>0)
		$this->db->where_in('clc.location_id',$locations);
		
		$res = $this->db->get();
		$row = $res->row_array();
		//echo $this->db->last_query();
		//print_r($row);
		$contact_emails =  $row['contact_emails'];
		$ce_arr = explode(',',$contact_emails);
		$ce_arr = array_filter($ce_arr);
		$ce_count = (count($ce_arr)>0)?count($ce_arr):0;
		$ce_str = implode(',',$ce_arr);
		echo json_encode(array('count'=>@$ce_count,'contact_emails'=>@$ce_str));
		
		
  	}
	

	/**
	* check customer code exist or not
	* author: mahesh , created on: 19th july  2016 02:00 AM, updated on: --
	* @param: $customer code(string)
	* @param: $customer id(int)
	* return: 1/0(boolean)
	**/
	function is_customerCodeExist(){
		$customer_code = $this->input->post('customer_code');
		$customer_id = icrm_decode($this->input->post('customer_id'));
		$data = '';
		echo $this->Ajax_m->is_customerCodeExist($customer_code,$customer_id);
  	}
        
        function is_specialityNameExist(){
            
		$speciality_name = $this->input->post('speciality_name');
                $speciality_id = icrm_decode($this->input->post('speciality_id'));
		$data = '';
		echo $this->Ajax_m->is_specialityNameExist($speciality_name,$speciality_id);
  	}

  	#Channel partner: 11-10-2018
  	public function is_channel_partnerNameExist()
  	{
  		$name = $this->input->post('channel_partner_name');
                $channel_partner_id = icrm_decode($this->input->post('channel_partner_id'));
		$data = '';
		echo $this->Ajax_m->is_channel_partnerNameExist($name,$channel_partner_id);
  	}
	
}