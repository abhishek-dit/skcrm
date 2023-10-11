<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Location extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Location_model");
	}

	public function locationAdd()
	{
		if($this->input->post('submitLocation') != "")
		{
			//print_r($_POST);
			$location_id = $this->input->post('location_id');
			$dataArr = array('location' => $this->input->post('name'),
								'parent_id'=>$this->input->post('parent'));

			//$dataArr = $_POST[];
			if($location_id == "")
			{
				$territory_level_id = $this->input->post('territory_level_id');
				$dataArr['territory_level_id'] = $territory_level_id;
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				$territory_level_name = $this->Common_model->get_value('territory_level', array('territory_level_id'=>$territory_level_id), 'name');
				//Insert
				$location_id = $this->Common_model->insert_data('location',$dataArr);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> '.$territory_level_name.' has been added successfully!
									 </div>');
			}
			else
			{	
				$territory_level_id = $this->Common_model->get_value('location', array('location_id'=>$location_id), 'territory_level_id');
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('location_id' => $location_id);

				$territory_level_name = $this->Common_model->get_value('territory_level', array('territory_level_id'=>$territory_level_id), 'name');
				//Update
				$this->Common_model->update_data('location',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> '.$territory_level_name.' has been updated successfully!
									 </div>');
			}
			redirect(SITE_URL.strtolower($territory_level_name));
		}
	}

	public function geo()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Geo";
		$data['nestedView']['cur_page'] = 'geo';
		$data['nestedView']['parent_page'] = 'geo';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Geo';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Geo','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchGeo', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'geoName'=>$this->input->post('geoName', TRUE),
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'geoName'=>$this->session->userdata('geoName'),
							  );
			}
			else {
				$searchParams=array(
					  'geoName'=>'',
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'geo/'; 
		# Total Records
	    $config['total_rows'] = $this->Location_model->geoTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		
		# Search Results
	   	$data['geoSearch'] = $this->Location_model->geoResults($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('location/geoView', $data);

	}

	public function addGeo()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Geo";
		$data['nestedView']['cur_page'] = 'geo';
		$data['nestedView']['parent_page'] = 'geo';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Geo';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Geo','class'=>'active','url'=>SITE_URL.'geo');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Geo','class'=>'active','url'=>'');

		$data['territory_level_id'] = $this->Common_model->get_value('territory_level', array('name'=>'Geo'), 'territory_level_id');
		$data['parent_id'] = $this->Common_model->get_value('territory_level', array('name'=>'World'), 'territory_level_id');
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('location/geoView', $data);

	}

	public function editGeo($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Geo";
		$data['nestedView']['cur_page'] = 'geo';
		$data['nestedView']['parent_page'] = 'geo';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Geo';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Geo','class'=>'active','url'=>SITE_URL.'geo');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Geo','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('location_id' => $value);
			$data['geoEdit'] = $this->Common_model->get_data('location', $where);
		}
		$this->validateEditUrl(@$data['geoEdit'],'geo');
		$data['parent_id'] = $this->Common_model->get_value('territory_level', array('name'=>'World'), 'territory_level_id');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('location/geoView', $data);
	}

	public function downloadGeo()
	{
		if($this->input->post('downloadGeo')!='') {
			
			$searchParams=array('geoName'=>$this->input->post('geoName', TRUE));
			$geos = $this->Location_model->geoDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Geo Name','Created Time','Modified By','Modified Time');
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
			 $j=1;
			if(count($geos)>0)
			{
				
				foreach($geos as $geo)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$geo['location'].'</td>';
					$data.='<td>'.DateFormatAM($geo['created_time']).'</td>';
					$data.='<td>'.getUserName($geo['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($geo['modified_time']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='geo_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function country()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Country";
		$data['nestedView']['cur_page'] = 'country';
		$data['nestedView']['parent_page'] = 'country';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Country';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Country','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchCountry', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'countryName'=>$this->input->post('countryName', TRUE),
					  'geo_id'=>$this->input->post('geo', TRUE),
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'countryName'=>$this->session->userdata('countryName'),
					  'geo_id'=>$this->session->userdata('geo_id'),
							  );
			}
			else {
				$searchParams=array(
					  'countryName'=>'',
					  'geo_id'=>'',
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'country/'; 
		# Total Records
	    $config['total_rows'] = $this->Location_model->countryTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */

		$data['geoDetails'] = array(''=>'Select Geo') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('territory_level_id' => 2));
		# Search Results
	   	$data['countrySearch'] = $this->Location_model->countryResults($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('location/countryView', $data);

	}

	public function addCountry()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Country";
		$data['nestedView']['cur_page'] = 'country';
		$data['nestedView']['parent_page'] = 'country';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';

		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Country';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Country','class'=>'active','url'=>SITE_URL.'country');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Country','class'=>'active','url'=>'');

		$data['territory_level_id'] = $this->Common_model->get_value('territory_level', array('name'=>'Country'), 'territory_level_id');
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('location/countryView', $data);

	}

	public function editCountry($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Country";
		$data['nestedView']['cur_page'] = 'country';
		$data['nestedView']['parent_page'] = 'country';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Country';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Country','class'=>'active','url'=>SITE_URL.'country');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Country','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('location_id' => $value);
			$data['countryEdit'] = $this->Common_model->get_data('location', $where);
			$data['parentInfo'] = getParentLocation($value);
		}
		$this->validateEditUrl(@$data['countryEdit'],'country');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('location/countryView', $data);
	}

	public function downloadCountry()
	{
		if($this->input->post('downloadCountry')!='') {
			
			$searchParams=array('countryName'=>$this->input->post('countryName', TRUE),
									'geo_id'=>$this->input->post('geo', TRUE));
			$countries = $this->Location_model->countryDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Geo','Country Name','Created Time','Modified By','Modified Time');
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
			 $j=1;
			if(count($countries)>0)
			{
				
				foreach($countries as $country)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$country['GeoName'].'</td>';
					$data.='<td>'.$country['location'].'</td>';
					$data.='<td>'.DateFormatAM($country['created_time']).'</td>';
					$data.='<td>'.getUserName($country['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($country['modified_time']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='country_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}
	
	public function region()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Region";
		$data['nestedView']['cur_page'] = 'region';
		$data['nestedView']['parent_page'] = 'region';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Region';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Region','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchRegion', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'regionName'=>$this->input->post('regionName', TRUE),
					  'country_id'=>$this->input->post('country', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'regionName'=>$this->session->userdata('regionName'),
					  'country_id'=>$this->session->userdata('country_id')
							  );
			}
			else {
				$searchParams=array(
					  'regionName'=>'',
					  'country_id'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'region/'; 
		# Total Records
	    $config['total_rows'] = $this->Location_model->regionTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		$data['countryDetails'] = array(''=>'Select Country') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('territory_level_id' => 3));
		# Search Results
	   	$data['regionSearch'] = $this->Location_model->regionResults($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('location/regionView', $data);

	}

	public function addRegion()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Region";
		$data['nestedView']['cur_page'] = 'region';
		$data['nestedView']['parent_page'] = 'region';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Region';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Region','class'=>'active','url'=>SITE_URL.'region');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Region','class'=>'active','url'=>'');

		$data['territory_level_id'] = $this->Common_model->get_value('territory_level', array('name'=>'Region'), 'territory_level_id');
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('location/regionView', $data);

	}

	public function editRegion($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Region";
		$data['nestedView']['cur_page'] = 'region';
		$data['nestedView']['parent_page'] = 'region';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Region';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Region','class'=>'active','url'=>SITE_URL.'region');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Region','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('location_id' => $value);
			$data['regionEdit'] = $this->Common_model->get_data('location', $where);
			$data['parentInfo'] = getParentLocation($value);
		}
		$this->validateEditUrl(@$data['regionEdit'],'region');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('location/regionView', $data);
	}

	public function downloadRegion()
	{
		if($this->input->post('downloadRegion')!='') {
			
			$searchParams=array('regionName'=>$this->input->post('regionName', TRUE),
									'country_id'=>$this->input->post('country', TRUE));
			$regions = $this->Location_model->regionDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Country','Region Name','Created Time','Modified By','Modified Time');
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
			 $j=1;
			if(count($regions)>0)
			{
				
				foreach($regions as $region)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$region['CountryName'].'</td>';
					$data.='<td>'.$region['location'].'</td>';
					$data.='<td>'.DateFormatAM($region['created_time']).'</td>';
					$data.='<td>'.getUserName($region['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($region['modified_time']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='region_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function state()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage State";
		$data['nestedView']['cur_page'] = 'state';
		$data['nestedView']['parent_page'] = 'state';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage State';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage State','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchState', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'stateName'=>$this->input->post('stateName', TRUE),
					  'country_id'=>$this->input->post('country', TRUE),
					  'region_id'=>$this->input->post('region', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'stateName'=>$this->session->userdata('stateName'),
					  'country_id'=>$this->session->userdata('country_id'),
					  'region_id'=>$this->session->userdata('region_id')
							  );
			}
			else {
				$searchParams=array(
					  'stateName'=>'',
					  'country_id'=>'',
					  'region_id'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		//print_r($searchParams);exit();
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'state/'; 
		# Total Records
	    $config['total_rows'] = $this->Location_model->stateTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		
		$data['countryDetails'] = array(''=>'Select Country') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('territory_level_id' => 3));
		if($searchParams['country_id']!='')
			$data['regionDetails'] = array(''=>'Select Region') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('parent_id' => $searchParams['country_id']));
		else
			$data['regionDetails'] = array(''=>'Select Region');
		# Search Results
	   	$data['stateSearch'] = $this->Location_model->stateResults($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('location/stateView', $data);

	}

	public function addState()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage State";
		$data['nestedView']['cur_page'] = 'state';
		$data['nestedView']['parent_page'] = 'state';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage State';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage State','class'=>'active','url'=>SITE_URL.'state');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add State','class'=>'active','url'=>'');

		$data['territory_level_id'] = $this->Common_model->get_value('territory_level', array('name'=>'State'), 'territory_level_id');
		
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('location/stateView', $data);

	}

	public function editState($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage State";
		$data['nestedView']['cur_page'] = 'state';
		$data['nestedView']['parent_page'] = 'state';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage State';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage State','class'=>'active','url'=>SITE_URL.'state');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit State','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('location_id' => $value);
			$data['stateEdit'] = $this->Common_model->get_data('location', $where);
			$data['parentInfo'] = getParentLocation($value);
		}
		$this->validateEditUrl(@$data['stateEdit'],'state');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('location/stateView', $data);
	}

	public function downloadState()
	{
		if($this->input->post('downloadState')!='') {
			
			$searchParams=array('stateName'=>$this->input->post('stateName', TRUE),
									'country_id'=>$this->input->post('country', TRUE),
					  				'region_id'=>$this->input->post('region', TRUE));
			//print_r($searchParams);exit();
			$states = $this->Location_model->stateDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Country Name','Region Name','State Name','Created Time','Modified By','Modified Time');
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
			 $j=1;
			if(count($states)>0)
			{
				
				foreach($states as $state)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$state['CountryName'].'</td>';
					$data.='<td>'.$state['RegionName'].'</td>';
					$data.='<td>'.$state['location'].'</td>';
					$data.='<td>'.DateFormatAM($state['created_time']).'</td>';
					$data.='<td>'.getUserName($state['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($state['modified_time']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='state_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function district()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage District";
		$data['nestedView']['cur_page'] = 'district';
		$data['nestedView']['parent_page'] = 'district';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage District';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage District','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchDistrict', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'districtName'=>$this->input->post('districtName', TRUE),
					  'country_id'=>$this->input->post('country', TRUE),
					  'region_id'=>$this->input->post('region', TRUE),
					  'state_id'=>$this->input->post('state', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'districtName'=>$this->session->userdata('districtName'),
					  'country_id'=>$this->session->userdata('country_id'),
					  'region_id'=>$this->session->userdata('region_id'),
					  'state_id'=>$this->session->userdata('state_id')
							  );
			}
			else {
				$searchParams=array(
					  'districtName'=>'',
					  'country_id'=>'',
					  'region_id'=>'',
					  'state_id'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'district/'; 
		# Total Records
	    $config['total_rows'] = $this->Location_model->districtTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		$data['countryDetails'] = array(''=>'Select Country') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('territory_level_id' => 3));
		if($searchParams['country_id']!='')
			$data['regionDetails'] = array(''=>'Select Region') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('parent_id' => $searchParams['country_id']));
		else
			$data['regionDetails'] = array(''=>'Select Region');
		if($searchParams['region_id']!='')
			$data['stateDetails'] = array(''=>'Select State') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('parent_id' => $searchParams['region_id']));
		else
			$data['stateDetails'] = array(''=>'Select State');
		# Search Results
	   	$data['districtSearch'] = $this->Location_model->districtResults($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('location/districtView', $data);

	}

	public function addDistrict()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage District";
		$data['nestedView']['cur_page'] = 'district';
		$data['nestedView']['parent_page'] = 'district';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage District';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage District','class'=>'active','url'=>SITE_URL.'district');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add District','class'=>'active','url'=>'');

		$data['territory_level_id'] = $this->Common_model->get_value('territory_level', array('name'=>'District'), 'territory_level_id');
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('location/districtView', $data);

	}

	public function editDistrict($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage District";
		$data['nestedView']['cur_page'] = 'district';
		$data['nestedView']['parent_page'] = 'district';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage District';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage District','class'=>'active','url'=>SITE_URL.'district');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit District','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('location_id' => $value);
			$data['districtEdit'] = $this->Common_model->get_data('location', $where);
			$data['parentInfo'] = getParentLocation($value);
		}
		$this->validateEditUrl(@$data['districtEdit'],'district');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('location/districtView', $data);
	}

	public function downloadDistrict()
	{
		if($this->input->post('downloadDistrict')!='') {
			
			$searchParams=array('districtName'=>$this->input->post('districtName', TRUE),
									'country_id'=>$this->input->post('country', TRUE),
									'region_id'=>$this->input->post('region', TRUE),
									'state_id'=>$this->input->post('state', TRUE));
			$districts = $this->Location_model->districtDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Country','Region','State','District Name','Created Time','Modified By','Modified Time');
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
			 $j=1;
			if(count($districts)>0)
			{
				
				foreach($districts as $district)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$district['CountryName'].'</td>';
					$data.='<td>'.$district['RegionName'].'</td>';
					$data.='<td>'.$district['StateName'].'</td>';
					$data.='<td>'.$district['location'].'</td>';
					$data.='<td>'.DateFormatAM($district['created_time']).'</td>';
					$data.='<td>'.getUserName($district['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($district['modified_time']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='district_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function city()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage City";
		$data['nestedView']['cur_page'] = 'city';
		$data['nestedView']['parent_page'] = 'city';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage City';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage City','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchCity', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'cityName'=>$this->input->post('cityName', TRUE),
					  'country_id'=>$this->input->post('country', TRUE),
					  'region_id'=>$this->input->post('region', TRUE),
					  'state_id'=>$this->input->post('state', TRUE),
					  'district_id'=>$this->input->post('district', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'cityName'=>$this->session->userdata('cityName'),
					  'country_id'=>$this->session->userdata('country_id'),
					  'region_id'=>$this->session->userdata('region_id'),
					  'state_id'=>$this->session->userdata('state_id'),
					  'district_id'=>$this->session->userdata('district_id')
							  );
			}
			else {
				$searchParams=array(
					  'cityName'=>'',
					  'country_id'=>'',
					  'region_id'=>'',
					  'state_id'=>'',
					  'district_id'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'city/'; 
		# Total Records
	    $config['total_rows'] = $this->Location_model->cityTotalRows($searchParams);
		
		$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
		$data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links(); 
		$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		if($data['pagination_links']!= '') {
			$data['last']=$this->pagination->cur_page*$config['per_page'];
			if($data['last']>$data['total_rows']){
				$data['last']=$data['total_rows'];
			}
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
         } 
		 $data['sn'] = $current_offset + 1;
		/* pagination end */
		$data['countryDetails'] = array(''=>'Select Country') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('territory_level_id' => 3));
		if($searchParams['country_id']!='')
			$data['regionDetails'] = array(''=>'Select Region') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('parent_id' => $searchParams['country_id']));
		else
			$data['regionDetails'] = array(''=>'Select Region');
		if($searchParams['region_id']!='')
			$data['stateDetails'] = array(''=>'Select State') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('parent_id' => $searchParams['region_id']));
		else
			$data['stateDetails'] = array(''=>'Select State');
		if($searchParams['state_id']!='')
			$data['districtDetails'] = array(''=>'Select District') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('parent_id' => $searchParams['state_id']));
		else
			$data['districtDetails'] = array(''=>'Select District');
		# Search Results
	   	$data['citySearch'] = $this->Location_model->cityResults($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('location/cityView', $data);

	}

	public function addCity()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage City";
		$data['nestedView']['cur_page'] = 'city';
		$data['nestedView']['parent_page'] = 'city';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage City';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage City','class'=>'active','url'=>SITE_URL.'city');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add City','class'=>'active','url'=>'');

		$data['territory_level_id'] = $this->Common_model->get_value('territory_level', array('name'=>'City'), 'territory_level_id');
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('location/cityView', $data);

	}

	public function editCity($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage City";
		$data['nestedView']['cur_page'] = 'city';
		$data['nestedView']['parent_page'] = 'city';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/location.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage City';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage City','class'=>'active','url'=>SITE_URL.'city');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit City','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('location_id' => $value);
			$data['cityEdit'] = $this->Common_model->get_data('location', $where);
			$data['parentInfo'] = getParentLocation($value);
		}
		$this->validateEditUrl(@$data['cityEdit'],'city');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('location/cityView', $data);
	}

	public function downloadCity()
	{
		if($this->input->post('downloadCity')!='') {
			
			$searchParams=array('cityName'=>$this->input->post('cityName', TRUE),
									'country_id'=>$this->input->post('country', TRUE),
									'region_id'=>$this->input->post('region', TRUE),
									'state_id'=>$this->input->post('state', TRUE),
									'district_id'=>$this->input->post('district', TRUE));
			$cities = $this->Location_model->cityDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Country','Region','State','District','City Name','Created Time','Modified By','Modified Time');
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
			 $j=1;
			if(count($cities)>0)
			{
				
				foreach($cities as $city)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$city['CountryName'].'</td>';
					$data.='<td>'.$city['RegionName'].'</td>';
					$data.='<td>'.$city['StateName'].'</td>';
					$data.='<td>'.$city['DistrictName'].'</td>';
					$data.='<td>'.$city['location'].'</td>';
					$data.='<td>'.DateFormatAM($city['created_time']).'</td>';
					$data.='<td>'.getUserName($city['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($city['modified_time']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='city_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function checkLocationAvailability()
	{
		$parent_location_id = @$_REQUEST['parent_location'];
		$location = @$_REQUEST['location'];
		$location_id = @$_REQUEST['location_id'];
		$array = array('parent_id'=>$parent_location_id,'location'=>$location,'location_id'=>$location_id);
		$result = $this->Location_model->checkAvailability($array);
		echo $result;
	}
}