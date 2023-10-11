<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Location_model extends CI_Model {

	public function geoResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('l.location_id, l.location');
		$this->db->from('location l');
		if($searchParams['geoName']!='')
		$this->db->like('l.location',$searchParams['geoName']);
		$this->db->where('tl.name', 'Geo');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.location_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function geoDetails($searchParams)
	{
		$this->db->select('l.location, l.created_time, l.modified_by, l.modified_time');
		$this->db->from('location l');
		if($searchParams['geoName']!='')
		$this->db->like('l.location',$searchParams['geoName']);
		$this->db->where('tl.name', 'Geo');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function geoTotalRows($searchParams)
	{
		$this->db->from('location l');
		if($searchParams['geoName']!='')
		$this->db->like('l.location',$searchParams['geoName']);
		$this->db->where('tl.name', 'Geo');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function countryResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('l.location_id, l.location, l1.location as GeoName');
		$this->db->from('location l');
		if($searchParams['countryName']!='')
		$this->db->like('l.location',$searchParams['countryName']);
		if($searchParams['geo_id']!='')
		$this->db->where('l.parent_id',$searchParams['geo_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->where('tl.name', 'Country');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.location_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function countryDetails($searchParams)
	{
		$this->db->select('l.location, l.created_time, l.modified_by, l.modified_time, l1.location as GeoName');
		$this->db->from('location l');
		if($searchParams['countryName']!='')
		$this->db->like('l.location',$searchParams['countryName']);
		if($searchParams['geo_id']!='')
		$this->db->where('l.parent_id',$searchParams['geo_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->where('tl.name', 'Country');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function countryTotalRows($searchParams)
	{
		$this->db->from('location l');
		if($searchParams['countryName']!='')
		$this->db->like('l.location',$searchParams['countryName']);
		if($searchParams['geo_id']!='')
		$this->db->where('l.parent_id',$searchParams['geo_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->where('tl.name', 'Country');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function regionResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('l.location_id, l.location, l1.location as CountryName');
		$this->db->from('location l');
		if($searchParams['regionName']!='')
		$this->db->like('l.location',$searchParams['regionName']);
		if($searchParams['country_id']!='')
		$this->db->where('l.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->where('tl.name', 'Region');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.location_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function regionDetails($searchParams)
	{
		$this->db->select('l.location, l.created_time, l.modified_by, l.modified_time, l1.location as CountryName');
		$this->db->from('location l');
		if($searchParams['regionName']!='')
		$this->db->like('l.location',$searchParams['regionName']);
		if($searchParams['country_id']!='')
		$this->db->where('l.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->where('tl.name', 'Region');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function regionTotalRows($searchParams)
	{
		$this->db->from('location l');
		if($searchParams['regionName']!='')
		$this->db->like('l.location',$searchParams['regionName']);
		if($searchParams['country_id']!='')
		$this->db->where('l.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->where('tl.name', 'Region');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function stateResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('l.location_id, l.location, l1.location as RegionName, l2.location as CountryName');
		$this->db->from('location l');
		if($searchParams['stateName']!='')
		$this->db->like('l.location',$searchParams['stateName']);
		if($searchParams['region_id']!='')
		$this->db->where('l.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l1.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->where('tl.name', 'State');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.location_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function stateDetails($searchParams)
	{
		$this->db->select('l.location,l.tag, l.created_time, l.modified_by, l.modified_time, l1.location as RegionName, l2.location as CountryName');
		$this->db->from('location l');
		if($searchParams['stateName']!='')
		$this->db->like('l.location',$searchParams['stateName']);
		if($searchParams['region_id']!='')
		$this->db->where('l.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l1.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->where('tl.name', 'State');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function stateTotalRows($searchParams)
	{
		$this->db->from('location l');
		if($searchParams['stateName']!='')
		$this->db->like('l.location',$searchParams['stateName']);
		if($searchParams['region_id']!='')
		$this->db->where('l.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l1.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->where('tl.name', 'State');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function districtResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('l.location_id, l.location, l1.location as StateName, l2.location as RegionName, l3.location as CountryName');
		$this->db->from('location l');
		if($searchParams['districtName']!='')
		$this->db->like('l.location',$searchParams['districtName']);
		if($searchParams['state_id']!='')
		$this->db->where('l.parent_id',$searchParams['state_id']);
		if($searchParams['region_id']!='')
		$this->db->where('l1.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l2.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->where('tl.name', 'District');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.location_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function districtDetails($searchParams)
	{
		$this->db->select('l.location, l.created_time, l.modified_by, l.modified_time, l1.location as StateName, l2.location as RegionName, l3.location as CountryName');
		$this->db->from('location l');
		if($searchParams['districtName']!='')
		$this->db->like('l.location',$searchParams['districtName']);
		if($searchParams['state_id']!='')
		$this->db->where('l.parent_id',$searchParams['state_id']);
		if($searchParams['region_id']!='')
		$this->db->where('l1.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l2.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->where('tl.name', 'District');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function districtTotalRows($searchParams)
	{
		$this->db->from('location l');
		if($searchParams['districtName']!='')
		$this->db->like('l.location',$searchParams['districtName']);
		if($searchParams['state_id']!='')
		$this->db->where('l.parent_id',$searchParams['state_id']);
		if($searchParams['region_id']!='')
		$this->db->where('l1.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l2.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->where('tl.name', 'District');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function cityResults($searchParams, $per_page, $current_offset)
	{
		$this->db->select('l.location_id, l.location, l1.location as DistrictName, l2.location as StateName, l3.location as RegionName, l4.location as CountryName');
		$this->db->from('location l');
		if($searchParams['cityName']!='')
		$this->db->like('l.location',$searchParams['cityName']);
		if($searchParams['district_id']!='')
		$this->db->where('l.parent_id',$searchParams['district_id']);
		if($searchParams['state_id']!='')
		$this->db->where('l1.parent_id',$searchParams['state_id']);
		if($searchParams['region_id']!='')
		$this->db->where('l2.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l3.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		$this->db->where('tl.name', 'City');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$this->db->limit($per_page, $current_offset);
		$this->db->order_by('l.location_id','DESC');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function cityDetails($searchParams)
	{
		$this->db->select('l.location, l.created_time, l.modified_by, l.modified_time, l1.location as DistrictName, l2.location as StateName, l3.location as RegionName, l4.location as CountryName');
		$this->db->from('location l');
		if($searchParams['cityName']!='')
		$this->db->like('l.location',$searchParams['cityName']);
		if($searchParams['district_id']!='')
		$this->db->where('l.parent_id',$searchParams['district_id']);
		if($searchParams['state_id']!='')
		$this->db->where('l1.parent_id',$searchParams['state_id']);
		if($searchParams['region_id']!='')
		$this->db->where('l2.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l3.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		$this->db->where('tl.name', 'City');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->result_array();
	}

	public function cityTotalRows($searchParams)
	{
		$this->db->from('location l');
		if($searchParams['cityName']!='')
		$this->db->like('l.location',$searchParams['cityName']);
		if($searchParams['district_id']!='')
		$this->db->where('l.parent_id',$searchParams['district_id']);
		if($searchParams['state_id']!='')
		$this->db->where('l1.parent_id',$searchParams['state_id']);
		if($searchParams['region_id']!='')
		$this->db->where('l2.parent_id',$searchParams['region_id']);
		if($searchParams['country_id']!='')
		$this->db->where('l3.parent_id',$searchParams['country_id']);
		$this->db->join('location l1','l1.location_id = l.parent_id','left');
		$this->db->join('location l2','l2.location_id = l1.parent_id','left');
		$this->db->join('location l3','l3.location_id = l2.parent_id','left');
		$this->db->join('location l4','l4.location_id = l3.parent_id','left');
		$this->db->where('tl.name', 'City');
		$this->db->join('territory_level tl ','tl.territory_level_id = l.territory_level_id');
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function checkAvailability($array)
	{
		$this->db->from('location l');
		$this->db->where('parent_id', $array['parent_id']);
		$this->db->where_not_in('location_id', $array['location_id']);
		$this->db->where('location', $array['location']);
		$res = $this->db->get();
		return $res->num_rows();
	}

	public function get_user_reportees($user_reportees)
	{
		if($this->session->userdata('role_id')!=2)
		{
			$this->db->select('user_id,first_name,employee_id');
			$this->db->from('user');
			$this->db->where('status',1);
			$this->db->where('user_id in ('.$user_reportees.')');
			$res=$this->db->get();
			return $res->result_array();
		}
		else
		{
			$this->db->select('user_id,first_name,employee_id');
			$this->db->from('user');
			$this->db->where('status',1);
			$this->db->where('user_id!=',$this->session->userdata('user_id'));
			$this->db->where('company_id',$this->session->userdata('company'));
			$res=$this->db->get();
			return $res->result_array();
		}
	}
	public function get_live_tracking_records($user_id,$from_date,$to_date)
	{
		$this->db->select();
		$this->db->from('live_location');
		$this->db->where('user_id',$user_id);
		$this->db->where('created_date >=',$from_date);
		$this->db->where('created_date <=',$to_date);
		$res=$this->db->get();
		return $res->result_array();
	}

	public function get_user_reportees_for_live_location($user_reportees)
	{
		if($this->session->userdata('role_id')!=2 && $this->session->userdata('role_id')!=14)
		{
			$this->db->select('user_id,first_name,employee_id');
			$this->db->from('user');
			$this->db->where('status',1);
			$this->db->where('user_id in ('.$user_reportees.')');
			$res=$this->db->get();
			return $res->result_array();
		}
		else
		{
			$this->db->select('user_id,first_name,employee_id');
			$this->db->from('user');
			$this->db->where('status',1);
			$this->db->where('user_id!=',$this->session->userdata('user_id'));
			$this->db->where('company_id',$this->session->userdata('company'));
			$res=$this->db->get();
			return $res->result_array();
		}
	}

	public function get_lat_long()
	{
		$this->db->select('latitude as lat,longitude as long');
		$this->db->from('location');
		$this->db->where('territory_level_id',4);
		$this->db->where('latitude IS NOT NULL');
		$this->db->where('longitude IS NOT NULL');
		$res=$this->db->get();
		return $res->result_array();
	}

}