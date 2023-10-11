<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';
class Report extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->helper('report_helper');
        $this->load->model('Report_model');
        $this->load->library('excel');
	}

	public function stockInHand()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Stock In Hand Report";
		$data['nestedView']['cur_page'] = 'stockInHand';
		$data['nestedView']['parent_page'] = 'stockInHand';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Stock In Hand Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Stock In Hand Report','class'=>'active','url'=>'');
		$region='';
        $data['categoryWiseData'] = getCategoryByProducts();
        $data['chart2Data'] = getStockInHandChart2Data($region);
        $data['chart3Data'] = getStockInHandChart2Data($region,'Critical Care');
		//print_r($data['chart2Data']); exit;

		$this->load->view('report/stock_in_hand', $data);
	}
	
	public function getStockInHandChart3Data()
	{
		$region = $this->input->post('region');
		$segment = $this->input->post('segment');
		$chartData = getStockInHandChart3Data($region,$segment);
		echo $chartData;
	}
	public function stock_in_hand_table()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Stock In Hand Report";
		$data['nestedView']['cur_page'] = 'stockInHand';
		$data['nestedView']['parent_page'] = 'stockInHand';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Stock In Hand Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Stock In Hand Report','class'=>'active','url'=>'');
		
		$search = $this->input->post('search',TRUE);
		if($search!='')
		{
			$searchParams = array(
			'category' => $this->input->post('category',TRUE),
			'segment' => $this->input->post('segment',TRUE),
			'product' => $this->input->post('product',TRUE));
		}
		else
		{
			$searchParams = array(
			'category' => '',
			'segment' => '',
			'product' => '');
		}

		$results=$this->Report_model->get_stock_in_hand_products_table($searchParams);
        $segment=array();
        foreach($results as $key=>$value)
        {
        	$segment[$value['category_id']]['category_name']=$value['category_name'];
        	$segment[$value['category_id']]['segment'][$value['group_id']]['group_name']=$value['group_name'];
        	$segment[$value['category_id']]['segment'][$value['group_id']]['products'][]=$value;
        	
        }
        
        $products = array();
        $segments=array();
		if($searchParams['segment']!='')
		{
			$products = $this->Common_model->get_data('product',array('group_id'=>$searchParams['segment'],'product_type_id'=>1));
		}
		
		if($searchParams['category']!='')
		{
			$segments = $this->Common_model->get_data('product_group',array('category_id'=>$searchParams['category']));
		}
	       $latest_date = $this->Report_model->get_latest_bulk_upload_date();
		if($latest_date!='')
		{
			$data['as_on_date']=$latest_date;
		}
		else
		{
			$data['as_on_date']='';
		}
		$data['category_list']=$this->Common_model->get_data('product_category',array('status'=>1));
		$data['products'] = $products;
		$data['segments'] = $segments;
		$data['searchParams'] = $searchParams;
        $data['product_list']=$segment;

        $this->load->view('report/stock_in_hand_table', $data);
	}
	public function getProductsDropdownforstock()
	{
		$segment = $this->input->post('segment');
		$data = '<option value="">Select Product</option>';
		$results = $this->Common_model->get_data('product',array('group_id'=>$segment,'product_type_id'=>1));
		if($results)
		{
			foreach ($results as $row) {
				$data .= '<option value="'.$row['product_id'].'">'.$row['description'].'</option>';
			}
		}
		if($data)
		echo $data;
	}
	public function getsegmentDropdownforstock()
	{
		$category = $this->input->post('category');
		$data = '<option value="">Select Segment</option>';
		$results = $this->Common_model->get_data('product_group',array('category_id'=>$category));
		if($results)
		{
			foreach ($results as $row) {
				$data .= '<option value="'.$row['group_id'].'">'.$row['name'].'</option>';
			}
		}
		if($data)
		echo $data;
	}
	public function outstandingCollection()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Outstanding Report";
		$data['nestedView']['cur_page'] = 'outstandingReport';
		$data['nestedView']['parent_page'] = 'outstandingReport';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Outstanding Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Outstanding  Report','class'=>'active','url'=>'');

		/*$data['zones'] = $this->Common_model->get_data('location',array('level_id'=>3,'status'=>1));
		$data['warehouses'] = $this->Common_model->get_data('warehouse',array('status'=>1,'wh_id > '=> 1));
		$data['modality'] = $this->Common_model->get_data('modality',array('status'=>1));*/
		//print_r($data['warehouses']); exit;
		$data['chart1Data'] = getOutStandingCollectionChart1Data();
		//print_r($data['firstPieData']); exit;

		$this->load->view('report/outstanding_collection', $data);
	}

	public function getOutStandingCollectionChart2Data()
	{
		$region_name = $this->input->post('region_name');
		$chartData = getOutStandingCollectionChart2Data($region_name);
		echo $chartData;
	}

	public function getOutStandingCollectionChart3Data()
	{
		$region_name = $this->input->post('region_name');
		$customer_name = $this->input->post('customer_name');
		$chartData = getOutStandingCollectionChart3Data($region_name,$customer_name);
		echo $chartData;
	}

	public function opportunityLost()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Opportunity Lost Report";
		$data['nestedView']['cur_page'] = 'outstandingReport';
		$data['nestedView']['parent_page'] = 'outstandingReport';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Opportunity Lost Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Opportunity Lost Report','class'=>'active','url'=>'');
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['region']=$this->Report_model->get_user_location_regions();
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);
		$data['segment']=$this->Common_model->get_data('product_group',array('status'=>1));
		$report_by = 1;
		$searchFilters = array('from_date'	=>	'',
							   'to_date'	=>	'',
								'vtime'		=>	'y',
								'users'     =>   '',
								'region_filter'	=>	'',
								'duration'	=>	'',
								'duration_text'	=>'',
								'search_user_role' =>'',
								'segment'=>'');
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		
		$searchFilters['fy_dates']=$fy_dates;
	    $data['chart1Data'] = json_encode(getOpportunityLostChart1DataReason($report_by,$searchFilters));
	    $data['chart10Data'] = json_encode(getOpportunityLostChart1DataCompetitor($report_by,$searchFilters));

		$this->load->view('report/opportunity_lost', $data);
	}

	public function getOpportunityLostChart2Data()
	{
		$lost_for = $this->input->post('lost_for');
		$report_by=$this->input->post('report_by');
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'users'=> $this->input->post('users'),
								'region_filter'=>$this->input->post('region_filter'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text'),
								'vtime'	=>	$this->input->post('vtime'),
								'segment'=> $this->input->post('segment'));
		$chartData = getOpportunityLostChart2Data($lost_for,$report_by,$searchFilters);
		echo $chartData;

	}

	public function getOpportunityLostChart3Data()
	{
		$lost_for = $this->input->post('lost_for');
		$region1 = $this->input->post('region');
		$region=str_replace('/AB', '&', $region1);
		$segment = $this->input->post('segment');
		$report_by=$this->input->post('report_by');
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'users'=> $this->input->post('users'),
								'region_filter'=>$this->input->post('region_filter'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text'),
								'vtime'	=>	$this->input->post('vtime'),
								'seg'=> $this->input->post('seg'));
		$chartData = getOpportunityLostChart3Data($lost_for,$region,$segment,$report_by,$searchFilters);
		echo $chartData;
	}
	public function opportunity_lost_report_filter()
	{
		$report_by=$this->input->post('report_by');
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'duration'=>$this->input->post('duration'),
								'users'=> $this->input->post('users'),
								'region_filter'=>$this->input->post('region_filter'),
								'duration_text'=>$this->input->post('duration_text'),
								'segment'=>$this->input->post('segment'));
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$search_user_role=$this->Common_model->get_value('user',array('user_id'=>$this->input->post('users')),'role_id');
		$searchFilters['search_user_role']=$search_user_role;
		$searchFilters['fy_dates']=$fy_dates;
		$data=array();
		$data['chart1Data']=getOpportunityLostChart1DataReason($report_by,$searchFilters);
		$data['chart10Data']=getOpportunityLostChart1DataCompetitor($report_by,$searchFilters);
		echo json_encode($data,JSON_NUMERIC_CHECK);
	}
	public function dependent_users()
	{
		$region=$this->input->post('region');
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		$users=$this->Report_model->region_user_locations($region,$user_reportees);
		echo $users;
	}

	public function freshBusiness()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Fresh Business Report";
		$data['nestedView']['cur_page'] = 'freshBusinessReport';
		$data['nestedView']['parent_page'] = 'freshBusinessReport';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Fresh Business Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Fresh Business Report','class'=>'active','url'=>'');

		$data['region']=$this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	
		$fy_dates=get_start_end_dates('y');
		$searchFilters = array('from_date'	=>	'',
								'to_date'	=>	'',
								'vtime'	=>	'y',
								'region'=> '',
								'users'=>'',
								'measure'=>1,
								'fy_dates'=>$fy_dates,
								'duration'=>'');
		$data['searchFilters']=$searchFilters;
		$data['chart1Data1'] = fresh_business_chart1($searchFilters);

		$this->load->view('report/fresh_business', $data);
	}

	public function getFreshBusinessChart1Data()
	{
		
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'region'=> $this->input->post('region'),
								'measure'=>$this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text')
								);

		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
	    $chart1Data=fresh_business_chart1($searchFilters);
	    echo $chart1Data;exit;
	}

	public function getFreshBusinessChart2Data()
	{
		$series_name = $this->input->post('series_name');
		$category1=$this->input->post('x_category');
		$category=str_replace('/AB', '&', $category1);
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'region'=> $this->input->post('region'),
								'measure'=>$this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text')
								);
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
        $chartData =FreshBusinessChart2Data($series_name,$category,$searchFilters);
		echo $chartData;
	}

	public function open_orders()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Open Order Report";
		$data['nestedView']['cur_page'] = 'open_orders';
		$data['nestedView']['parent_page'] = 'open_orders';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Open Orders Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Open Order Report','class'=>'active','url'=>'');
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['region']=$this->Report_model->get_user_location_regions();
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);
		$searchFilters = array('from_date'	=>	'',
								'to_date'	=>	'',
								'vtime'	    =>	'y',
								'users'     =>   '',
								'duration'	=>	'',
								'region'    =>'',
								'duration_text'	=>'');
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		$data['chart1Data'] = getOpenOrderChart1Data($searchFilters);

		$this->load->view('report/open_order', $data);
	}

	public function openOrderChart1Data()
	{
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	    =>	$this->input->post('vtime'),
								'duration'  =>  $this->input->post('duration'),
								'region'    =>  $this->input->post('region'), 
								'users'     =>  $this->input->post('users'),
								'duration_text'=>$this->input->post('duration_text'));
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		$chartData = getOpenOrderChart1Data($searchFilters);
		echo $chartData;
	}
	public function openOrderChart2Data()
	{   
		$status=$this->input->post('status');
		$category=$this->input->post('category');
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'duration'  =>  $this->input->post('duration'),
								'region'    =>  $this->input->post('region'), 
								'users'     =>  $this->input->post('users'),
								'duration_text'=>$this->input->post('duration_text'));
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;

		$chartData = getOpenOrderChart2Data($searchFilters,$status,$category);
		echo $chartData;
	}
	public function openOrderChart3Data()
	{   
		$status=$this->input->post('status');
		$category=$this->input->post('category');
		$segment=$this->input->post('segment');
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'duration'  =>  $this->input->post('duration'),
								'region'    =>  $this->input->post('region'), 
								'users'     =>  $this->input->post('users'),
								'duration_text'=>$this->input->post('duration_text'));
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		$chartData = getOpenOrderChart3Data($searchFilters,$status,$category,$segment);
		echo $chartData;
	}

	public function open_opportunities()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Open Opportunites Report";
		$data['nestedView']['cur_page'] = 'open_opportunities';
		$data['nestedView']['parent_page'] = 'open_opportunities';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Open Opportunities Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Open Opportunities Report','class'=>'active','url'=>'');
		$role_id=$this->session->userdata('role_id');
		if($role_id!=4 && $role_id!=5 && $role_id!=6 && $role_id!=7 && $role_id!=2 && $role_id!=3)
		{
			$region=$this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));
			$data['region']=$region;
		}
		$data['months_array']=array(4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec',1=>'Jan',2=>'Feb',3=>'Mar');
		$searchFilters=array(
			'cur_month'=>date('m'),
			'cur_year'=>date('Y')
			);
		if($role_id!=4 && $role_id!=5 && $role_id!=6 && $role_id!=7 && $role_id!=2 && $role_id!=3)
		{
			$searchFilters['regions']=6;
			$locations=get_cities_by_region($searchFilters['regions']);
			$loc_arr = array();
			foreach ($locations as $key => $value) 
			{
				$loc_arr[] = $value['location_id'];
			}
			$searchFilters['loc_string']=implode(",",$loc_arr);
		}
		elseif($role_id==6 || $role_id ==7)
		{
			$searchFilters['loc_string']=$this->session->userdata('locationString');
		}
		//print_r($searchFilters);exit;
		$data['searchFilters']=$searchFilters;
		$searchFilters['products']=$this->session->userdata('products');
		$data['chart1Data'] = json_encode(getOpenOpportunitiesChart1Data($searchFilters));
		$data['chart2Data'] = json_encode(getOpenOpportunitiesChart2Data($searchFilters));
		$data['chart3Data'] = json_encode(getOpenOpportunitiesChart3Data($searchFilters));
		$data['chart4Data'] = json_encode(getOpenOpportunitiesChart4Data($searchFilters));
		//echo $data['chart1Data'];exit;
		//$data['d'] = json_encode($data,JSON_NUMERIC_CHECK);
		$this->load->view('report/open_opportunities', $data);
	}
	public function openOpportunitiesFilterData()
	{   
		$role_id=$this->session->userdata('role_id');
		$searchFilters=array(
			'cur_month'=>$this->input->post('cur_month'),
			'cur_year'=> $this->input->post('cur_year'),
			);
		if($role_id!=4 && $role_id!=5 && $role_id!=6 && $role_id!=7 && $role_id!=2 && $role_id!=3)
		{
			$searchFilters['regions']=$this->input->post('region');
			$locations=get_cities_by_region($searchFilters['regions']);
			$loc_arr = array();
			foreach ($locations as $key => $value) 
			{
				$loc_arr[] = $value['location_id'];
			}
			//$searchFilters['loc_string']=implode(",",$loc_arr);
			$searchFilters['loc_string']=$this->session->userdata('locationString');
		}
		elseif($role_id==6 || $role_id ==7)
		{
			$searchFilters['loc_string']=$this->session->userdata('locationString');
		}
		$searchFilters['products']=$this->session->userdata('products');
		$data=array();
		$data['chart1Data'] = getOpenOpportunitiesChart1Data($searchFilters);
	    $data['chart2Data'] = getOpenOpportunitiesChart2Data($searchFilters);
		$data['chart3Data'] = getOpenOpportunitiesChart3Data($searchFilters);
		$data['chart4Data'] = getOpenOpportunitiesChart4Data($searchFilters);

		echo json_encode($data,JSON_NUMERIC_CHECK);
		//print_r($data);
	}

	public function margin_analysis_report()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Margin Analysis Report";
		$data['nestedView']['cur_page'] = 'margin_analysis_report';
		$data['nestedView']['parent_page'] = 'margin_analysis_report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Margin Analysis Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Margin Analysis Report','class'=>'active','url'=>'');

		$searchFilters = array('from_date'	=>	'',
								'to_date'	=>	'',
								'vtime'	=>	'y',
								'sales' => 1,
								'groups'=> 2,
								'segment'=> '',
								'regions'=> '',
								'top'=> 10
								);
		$data['chart1Data']=getMarginAnalysisChart1Data($searchFilters);
		//print_r($data['chart1Data']);exit;
		$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));		
		$data['groups'] = $this->Common_model->get_data('product_group',array('status'=>1));

		$this->load->view('report/margin_analysis_report', $data);
	}
	public function getMarginAnalysisChart2Data()
	{
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'sales' => $this->input->post('sales'),
								'groups'=> $this->input->post('groups'),
								'segment'=> $this->input->post('segment'),
								'regions'=> $this->input->post('regions'),
								'top'=> $this->input->post('top')
								);
		$chart1Data=getMarginAnalysisChart1Data($searchFilters);
		echo $chart1Data;
	}

	public function target_vs_sales_report()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Target Vs Sales Report";
		$data['nestedView']['cur_page'] = 'target_vs_sales_report';
		$data['nestedView']['parent_page'] = 'target_vs_sales_report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href=" '.assets_url().'js/jquery.icheck/skins/square/blue.css">';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Target Vs Sales Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Target Vs Sales','class'=>'active','url'=>'');

		//$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['region']=$this->Report_model->get_user_location_regions();
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);
		//$data['role_id']	 = $_SESSION['role_id'];  
		$data['role_id'] = $this->session->userdata('role_id');
		$fy_dates=get_custom_start_end_dates('y');
		$searchFilters = array('from_date'	=>	'',
								'to_date'	=>	'',
								'vtime'	=>	'y',
								'measure' =>2 ,
								'groups'=> '',
								'users'=> '',
								'regions'=> '',
								'zone'=> 1,
								'fy_dates'=>$fy_dates,
								'duration'=>'',
								'duration_text'=>''
								);
		$data['chart1Data']=getTargetVsSalesChart1Data($searchFilters);	
		/*$array=array(1,2,3,4,5,6);
		function recursive($array,$index)
		{
			if($index==-1)return;
			recursive($array,$index-1);
			echo $array[$index].'-';exit;
			
		}
		recursive($array,4);
		exit;*/
		$this->load->view('report/target_vs_sales_report', $data);
	}
	public function targetVsSalesChart2Data()
	{
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'measure' => $this->input->post('measure'),
								'groups'=> $this->input->post('groups'),
								'users'=> $this->input->post('users'),
								'regions'=> $this->input->post('regions'),
								'zone'=> $this->input->post('zone'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text')
								);
		$fy_dates=get_custom_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		$chart1Data=getTargetVsSalesChart1Data($searchFilters);
		echo $chart1Data;
	}

	public function tvs_2_report()
	{	
		/*echo "<pre>";
		print_r($_POST); exit();*/
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Target Vs Sales Report";
		$data['nestedView']['cur_page'] = 'tvs_2_report';
		$data['nestedView']['parent_page'] = 'tvs_2_report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href=" '.assets_url().'js/jquery.icheck/skins/square/blue.css">';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Target Vs Sales Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Target Vs Sales Report','class'=>'active','url'=>'');

		//$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['region']=$this->Report_model->get_user_location_regions();
		//$data['users'] = $this->Report_model->get_user_reportees($user_reportees);
		//$data['role_id']	 = $_SESSION['role_id'];  
		$data['role_id'] = 8;

		$search = $this->input->post('search',TRUE);
		if($search!='')
		{
			$searchParams = array(
			'date_from' => $this->input->post('date_from',TRUE),
			'date_to' => $this->input->post('date_to',TRUE),
			'timeline' => $this->input->post('timeline',TRUE),
			'measure' => $this->input->post('measure',TRUE),
			'groups' => $this->input->post('groups',TRUE),
			'users' => $this->input->post('users',TRUE),
			'regions' => $this->input->post('regions',TRUE),
			'dur'=>$this->input->post('duration'),
			'view_page' => 2);
		}
		else
		{
			$searchParams = array(
			'date_from' => '',
			'date_to' => '',
			'timeline' => 'y',
			'measure' => 2,
			'groups' => 1,
			'users' => '',
			'regions' => '',
			'dur'=>'',
			'view_page' => 2);
		}
		$searchParams['duration']=$searchParams['dur'];
		$searchParams['zone'] = $searchParams['view_page'];
		$searchParams['vtime'] = $searchParams['timeline'];
		$searchParams['from_date'] = $searchParams['date_from'];
		$searchParams['to_date'] = $searchParams['date_to'];

        $fy_dates=get_custom_start_end_dates($searchParams['vtime'],'',$searchParams);
		$searchParams['fy_dates']=$fy_dates;
		$data['users'] = $this->Report_model->get_user_region_locations($searchParams['regions'],$user_reportees);
		//echo $searchParams['users']; exit();
		//echo "<pre>"; print_r($data['users']); exit();
		#additional Data
		$data['searchParams'] = $searchParams;
		$data['table_data'] = getTargetVsSalesChart1Data($searchParams);
		//echo "<pre>"; print_r($data['table_data']); exit();
		//print_r($searchParams);exit;
		$this->load->view('report/tvs_2_report', $data);
	}  

	public function download_target_vs_sales_report()
	{
		$searchFilters = array('vtime'	=>	$this->input->post('timeline'),
								'measure' => $this->input->post('measure'),
								'groups'=> $this->input->post('groups'),
								'users'=> $this->input->post('users'),
								'regions'=> $this->input->post('regions'),
								'zone'=> $this->input->post('zone'),
								'duration'=>$this->input->post('duration')
								);
		$fy_dates=get_custom_start_end_dates($this->input->post('timeline'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		if($searchFilters['users']!='')
	    {
	    	$users=$searchFilters['users'];
	    	$reportees=getReportingUsers($users);
	    	$user_id=$reportees.','.$users;
	    }
	    else
	    {
	    	$users=$this->session->userdata('user_id');
	    	$reportees=$this->session->userdata('reportees');
	    	$user_id=$reportees.','.$users;
	    }
		
		$role_id=getUserRole($users);
	    if($users != $this->session->userdata('user_id'))
		{
			$l = getUserLocations($users);
			$ul = getQueryArray($l);
			$up = getUserProducts($users);
			$userLocations = ($ul == '')? 0: $ul;
			$userProducts = ($up == '')? 0: $up;
		}
		else
		{
			$userLocations = ($this->session->userdata('locationString') == '')? 0: $this->session->userdata('locationString');
			$userProducts = ($this->session->userdata('products') == '')? 0: $this->session->userdata('products');
		}
	    $searchFilters['user_reportees_tvs']=$user_id;
	    $searchFilters['userProducts']=$userProducts;
	    $searchFilters['userLocations']=$userLocations;
	     if($searchFilters['fy_dates']['start_date']!='')
	    {
	    	$start_date=$searchFilters['fy_dates']['start_date'];
	    	$month=date('m',strtotime($start_date));
	    	$month1 = $month + 1;
	    	$year=date('Y',strtotime($start_date));
	    }
	    else
	    {
	    	$month = date('m');
	        $month1 = $month + 1;
	        $year = date('Y');		
	       
	    }	
	    $day = getOpportunityCategorizationDate();
	    $hotDay = $year."-".$month."-".$day;
	    $warmDate = date('Y-m-d',strtotime( $hotDay . " +1 month " ));
		if($searchFilters['groups']==2)
		{
			$location_wise = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
			//fetching previous target by region wise
			$user_target_arr=array();
			foreach($location_wise as $loc) 
			{
				$region_users= report_user_locations_by_region($loc['location_id']);
				$prev = $this->Report_model->user_targets_per_region($searchFilters,$region_users);
				$user_target_arr[$loc['location']]= $prev['pt'];
			}
			$prev_target_arr=array();
			foreach($location_wise as $loc) 
			{
				$region_users= report_user_locations_by_region($loc['location_id']);
				$prev = $this->Report_model->get_previous_target_by_region($searchFilters,$region_users);
			    //echo $CI->db->last_query();exit;
				$prev_target_arr[$loc['location']]= $prev['previous_target'];
			}
			//fetching previous target by region wise
			$curr_target_arr=array();
			foreach($location_wise as $loc) 
			{
				$region_users= report_user_locations_by_region($loc['location_id']);
				$cur_target = $this->Report_model->get_current_target_by_region($searchFilters,$region_users);
				$curr_target_arr[$loc['location']] = $cur_target['current_target'];
			}
			//fetching previous sales by region wise
			$previous_sales_by_region =$this->Report_model->get_previous_sales_by_region($searchFilters);
			$prev_sales_arr = array();
			if(count($previous_sales_by_region)>0)
			{
				foreach ($previous_sales_by_region as $key => $value)
				{
					$prev_sales_arr[$value['location']]['prev_sales'] = $value['previous_sales'];
				}
			}
			// fetching previous sales by region wise
            $current_sales_by_region =$this->Report_model->get_current_sales_by_region($searchFilters);
            $curr_sales_arr = array();
            if(count($current_sales_by_region)>0)
            {
            	foreach ($current_sales_by_region as $key => $value) 
            	{
            		$curr_sales_arr[$value['location']]['curr_sales'] = $value['current_sales'];
            	}
            }
			$open_orders_by_region =$this->Report_model->get_open_orders_by_region($searchFilters);
            $open_order_arr = array();
            if(count($open_orders_by_region)>0)
            {
            	foreach ($open_orders_by_region as $key => $value) 
            	{
            		$open_order_arr[$value['location']]['open_orders'] = $value['open_orders'];
            	}
            }
			$open_opportunities_by_region=$this->Report_model->get_open_opportunity_by_region($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters);
			$open_opp_arr = array();
            if(count($open_opportunities_by_region)>0)
            {
            	foreach ($open_opportunities_by_region as $key => $value) 
                {
                	$open_opp_arr[$value['location']]['hot'] = $value['Hot'];
                	$open_opp_arr[$value['location']]['warm'] = $value['Warm'];
                	$open_opp_arr[$value['location']]['cold'] = $value['Cold'];
                }
            }
            $region_array = array(); 
            if(count($user_target_arr)>0)
            {
            	$i = 0;
            	foreach ($user_target_arr as $key => $value) 
            	{
            		if(isset($prev_target_arr[$key]))
            		{ $previous_target = $prev_target_arr[$key]; }
            		else { $previous_target = 0; }

            		if(isset($prev_sales_arr[$key]['prev_sales']))
            		{ $previous_sales = $prev_sales_arr[$key]['prev_sales']; }
            		else { $previous_sales = 0; }

            		if(isset($curr_target_arr[$key]))
            		{ $current_target = $curr_target_arr[$key]; }
            		else { $current_target = 0; }

            		if(isset($curr_sales_arr[$key]['curr_sales']))
            		{ $current_sales = $curr_sales_arr[$key]['curr_sales']; }
            		else { $current_sales = 0; }

            		if(isset($open_order_arr[$key]['open_orders']))
            		{ $open_orders = $open_order_arr[$key]['open_orders']; }
            		else { $open_orders = 0; }

            		if(isset($open_opp_arr[$key]['hot']))
            		{ $hot = $open_opp_arr[$key]['hot']; }
            		else { $hot = 0; }

            		if(isset($open_opp_arr[$key]['warm']))
            		{ $warm = $open_opp_arr[$key]['warm']; }
            		else { $warm = 0; }

            		if(isset($open_opp_arr[$key]['cold']))
            		{ $cold = $open_opp_arr[$key]['cold']; }
            		else { $cold = 0; }

            		$backlog = $previous_target-$previous_sales;
			   		if($backlog<0) { $backlog = 0;}
			   		$cumm_target = $backlog+$current_target;
			   		$pending = ($backlog+$current_target)-$current_sales-$open_orders;
			   		if($pending<0) { $pending = 0;}

					$region_array[$i]['previous_target'] = @$previous_target;
            		$region_array[$i]['backlog'] = @$backlog;
            		$region_array[$i]['cumm_target'] = @$cumm_target;
            		$region_array[$i]['pending'] = @$pending;
            		$region_array[$i]['region_name'] = @$key;
            		$region_array[$i]['current_target'] = @$current_target;
            		$region_array[$i]['hot'] = @$hot;
            		$region_array[$i]['warm'] = @$warm;
            		$region_array[$i]['cold'] = @$cold;
            		$region_array[$i]['current_sales'] = @$current_sales;
            		$region_array[$i]['open_orders'] = @$open_orders;
            		$region_array[$i]['previous_sales'] = @$previous_sales;
            		$i++;
				}
            }
            $region_text='Target Vs Sales';
            if($searchFilters['regions']!='' )
            {
            	$region_name=$this->Common_model->get_value('location',array('location_id'=>$searchFilters['regions']),'location');
            	$region_text.=' in '.$region_name;
            }
            if($searchFilters['users']!='')
            {
            	$region_name=$this->Common_model->get_data_row('user',array('user_id'=>$searchFilters['users']));
            	$region_text.=' For '.$region_name['first_name'].'('.$region_name['employee_id'].' )';
            }
            $this->excel->setActiveSheetIndex(0);
            $style = array  (
						        'alignment' => array(
						        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						        )
						    );
			$this->excel->getActiveSheet()->setTitle('Target Vs Sales Report');
	        $this->excel->getActiveSheet()->mergeCells('A1:I1')->setCellValue('A1',"".$region_text);
	        $this->excel->getActiveSheet()->setCellValue('A2', 'S.No');
	        $this->excel->getActiveSheet()->setCellValue('B2', 'Region');
	        $this->excel->getActiveSheet()->setCellValue('C2', 'Backlog');
	        $this->excel->getActiveSheet()->setCellValue('D2', 'Current Target');
	        $this->excel->getActiveSheet()->setCellValue('E2', 'Cumm Target');
	        $this->excel->getActiveSheet()->setCellValue('F2', 'Current Sales');
	        $this->excel->getActiveSheet()->setCellValue('G2', 'Open Orders');
	        $this->excel->getActiveSheet()->setCellValue('H2', 'Pending');
	        $this->excel->getActiveSheet()->setCellValue('I2', 'Funnel Opp');
	        $this->excel->getActiveSheet()->getStyle("A1:I1")->applyFromArray($style);
	        $exceldata="";
	        if(count($region_array)>0)
	        {
	            $arr=array();
	            $i=1;
	            foreach ($region_array as $row)
	            {      
	                if($row['backlog'] >0 ||  $row['current_target']>0 ||  $row['cumm_target']>0 || $row['current_sales']>0 || $row['open_orders']>0 || $row['hot']>0 || $row['warm']>0 || $row['cold']>0 || $row['pending']>0 ) {
	                	$hot=($row['hot']>0)?$row['hot']:'0';
	                	$warm=($row['warm']>0)?$row['warm']:'0';
	                	$cold=($row['cold']>0)?$row['cold']:'0';
	                    $exceldata=array();
	                    $exceldata[] = @$i;
	                    $exceldata[] = @$row['region_name'];
	                    $exceldata[] = ($row['backlog']>0)?$row['backlog']:'0';
	                    $exceldata[] = ($row['current_target']>0)?$row['current_target']:'0';
	                    $exceldata[] = ($row['cumm_target']>0)?$row['cumm_target']:'0';
	                    $exceldata[] = ($row['current_sales']>0)?$row['current_sales']:'0';
	                    $exceldata[] = ($row['open_orders']>0)?$row['open_orders']:'0';
	                    $exceldata[] = ($row['pending']>0)?$row['pending']:'0';
	                    $exceldata[] = 'Hot :'.$hot.', Warm :'.$warm.', Cold :'.$cold;
	                    // echo "<br>";
	                    $arr[]=$exceldata;
	                    $i++;
	                }   
	            }
	             $this->excel->getActiveSheet()->fromArray($arr, null, 'A3');
	        }
	        $filename='Target Vs Sales Report.xlsx'; //save our workbook as this file name
	        header('Content-Type: application/vnd.ms-excel'); //mime type
	        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
	        header('Cache-Control: max-age=0');
	        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
	        foreach(range('A1','G1') as $columnID) {
	            $this->excel->getActiveSheet()->getColumnDimension($columnID)
	                ->setAutoSize(true);
	        }
	        $objWriter->save('php://output');
        }
        else
        {
        	$data2 = $this->Report_model->user_assigned_product_list_download($searchFilters);
		   	/*foreach ($data2 as $key2 => $value2) 
		   	{*/
	        	$pt3=$this->Report_model->get_previous_target_by_product_table_download($searchFilters);
	        	$pt_arr=array();
	        	foreach($pt3 as $row)
	        	{
	        		$pt_arr[$row['product_id']]['previous_target']=$row['previous_target'];
	        	}
		   		$previous_sales3 =$this->Report_model->get_previous_sales_category_download($searchFilters);
		   		foreach($previous_sales3 as $row)
		   		{
		   			$pt_arr[$row['product_id']]['previous_sales']=$row['previous_sales'];
		   		}
		   		$current_target = $this->Report_model->get_current_target_category_download($searchFilters);
		   		foreach($current_target as $row)
		   		{
		   			$pt_arr[$row['product_id']]['current_target']=$row['current_target'];
		   		}
		   		$current_sales = $this->Report_model->get_current_sales_category_download($searchFilters);
		   		foreach($current_sales as $row)
		   		{
		   			$pt_arr[$row['product_id']]['current_sales']=$row['current_sales'];
		   		}
		   		$open_orders =  $this->Report_model->get_open_orders_category_download($searchFilters);
		   		foreach($open_orders as $row)
		   		{
		   			$pt_arr[$row['product_id']]['open_orders']=$row['open_orders'];
		   		}
		   		$funnel_open_opp =$this->Report_model->get_open_opportunity_category_download($user_id, $role_id, $userProducts, $userLocations, $hotDay, $warmDate,$searchFilters);

		   		foreach($funnel_open_opp as $row)
		   		{
		   			$pt_arr[$row['product_id']]['hot']=$row['Hot'];
		   			$pt_arr[$row['product_id']]['warm']=$row['Warm'];
		   			$pt_arr[$row['product_id']]['cold']=$row['Cold'];
		   		}
		   		  $region_text='Target Vs Sales';
            if($searchFilters['regions']!='' )
            {
            	$region_name=$this->Common_model->get_value('location',array('location_id'=>$searchFilters['regions']),'location');
            	$region_text.=' in '.$region_name;
            }
            if($searchFilters['users']!='')
            {
            	$region_name=$this->Common_model->get_data_row('user',array('user_id'=>$searchFilters['users']));
            	$region_text.=' For '.$region_name['first_name'].'('.$region_name['employee_id'].' )';
            }
            $this->excel->setActiveSheetIndex(0);
            $style = array  (
						        'alignment' => array(
						        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						        )
						    );
			$this->excel->getActiveSheet()->setTitle('Target Vs Sales Report');
	        $this->excel->getActiveSheet()->mergeCells('A1:J1')->setCellValue('A1',"".$region_text);
	        $this->excel->getActiveSheet()->setCellValue('A2', 'S.No');
	        $this->excel->getActiveSheet()->setCellValue('B2', 'Segment');
	        $this->excel->getActiveSheet()->setCellValue('C2', 'Product');
	        $this->excel->getActiveSheet()->setCellValue('D2', 'Backlog');
	        $this->excel->getActiveSheet()->setCellValue('E2', 'Current Target');
	        $this->excel->getActiveSheet()->setCellValue('F2', 'Cumm Target');
	        $this->excel->getActiveSheet()->setCellValue('G2', 'Current Sales');
	        $this->excel->getActiveSheet()->setCellValue('H2', 'Open Orders');
	        $this->excel->getActiveSheet()->setCellValue('I2', 'Pending');
	        $this->excel->getActiveSheet()->setCellValue('J2', 'Funnel Opp');
	        $this->excel->getActiveSheet()->getStyle("A1:J1")->applyFromArray($style);
	        $exceldata="";
	        if(count($data2)>0)
	        {
	            $arr=array();
	            $i=1;
	            foreach ($data2 as $key => $value)
	            {   
	            	$row=array();
	            	$row['previous_target']=@$pt_arr[$value['product_id']]['previous_target'];
	            	$row['previous_sales']=@$pt_arr[$value['product_id']]['previous_sales'];
	            	$row['backlog']=@$pt_arr[$value['product_id']]['previous_target']-@$pt_arr[$value['product_id']]['previous_sales'];
	            	$row['current_target']=@$pt_arr[$value['product_id']]['current_target'];
	            	$row['current_sales']=@$pt_arr[$value['product_id']]['current_sales'];
	            	$row['open_orders']=@$pt_arr[$value['product_id']]['open_orders'];
	            	$row['backlog'] = $row['previous_target']-$row['previous_sales'];
			   		if($row['backlog']<0) { $row['backlog'] = 0;}
			   		$row['cumm_target'] = $row['backlog']+$row['current_target'];
			   		$row['pending'] = ($row['backlog']+$row['current_target'])-$row['current_sales']-$row['open_orders'];
			   		if($row['pending']<0) { $row['pending'] = 0;}	
                    $row['hot']=@$pt_arr[$value['product_id']]['hot'];
                    $row['warm']=@$pt_arr[$value['product_id']]['warm'];
                    $row['cold']=@$pt_arr[$value['product_id']]['cold'];

	                if($row['backlog'] >0 ||  $row['current_target']>0 ||  $row['cumm_target']>0 || $row['current_sales']>0 || $row['open_orders']>0 || $row['hot']>0 || $row['warm']>0 || $row['cold']>0 || $row['pending']>0 ) {
	                	$hot=($row['hot']>0)?$row['hot']:'0';
	                	$warm=($row['warm']>0)?$row['warm']:'0';
	                	$cold=($row['cold']>0)?$row['cold']:'0';
	                    $exceldata=array();
	                    $exceldata[] = @$i;
	                    $exceldata[] = @$value['segment_name'];
	                     $exceldata[] = @$value['product_name'];
	                    $exceldata[] = ($row['backlog']>0)?$row['backlog']:'0';
	                    $exceldata[] = ($row['current_target']>0)?$row['current_target']:'0';
	                    $exceldata[] = ($row['cumm_target']>0)?$row['cumm_target']:'0';
	                    $exceldata[] = ($row['current_sales']>0)?$row['current_sales']:'0';
	                    $exceldata[] = ($row['open_orders']>0)?$row['open_orders']:'0';
	                    $exceldata[] = ($row['pending']>0)?$row['pending']:'0';
	                    $exceldata[] = 'Hot :'.$hot.', Warm :'.$warm.', Cold :'.$cold;
	                    // echo "<br>";
	                    $arr[]=$exceldata;
	                    $i++;
	                }   
	            }
	             $this->excel->getActiveSheet()->fromArray($arr, null, 'A3');
	        }
	        $filename='Target Vs Sales Report.xlsx'; //save our workbook as this file name
	        header('Content-Type: application/vnd.ms-excel'); //mime type
	        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
	        header('Cache-Control: max-age=0');
	        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
	        foreach(range('A1','G1') as $columnID) {
	            $this->excel->getActiveSheet()->getColumnDimension($columnID)
	                ->setAutoSize(true);
	        }
	        $objWriter->save('php://output');
		   		
			//}
        }

	}
	public function get_filter_duration_table()
	{
		$vtime=$this->input->post('vtime');
		$dur = $this->input->post('dur');
		$searchFilters=array();
		$searchFilters['duration']=$dur;
		$fy_dates=get_custom_start_end_dates($vtime,'',$searchFilters);
		if($vtime=='m')
		{
			$res=get_custom_fy_months_array($fy_dates);

		}
		elseif($vtime=='q')
		{
			$res=get_custom_fy_quarter_array($fy_dates);
		}
		elseif($vtime=='w')
		{
			$res=get_custom_fy_week_array($fy_dates);
		}
		else
		{
			$res='';
		}
	    //echo $vtime;
		echo $res;

	}
	public function funnel_report()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Funnel Report";
		$data['nestedView']['cur_page'] = 'sales_by_dealer';
		$data['nestedView']['parent_page'] = 'sales_by_dealer';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Funnel Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Funnel Report','class'=>'active','url'=>'');

		$data['region']=$this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	
		$fy_dates=get_start_end_dates('y');
		$searchFilters = array('from_date'	=>	'',
								'to_date'	=>	'',
								'vtime'	=>	'y',
								'region'=> '',
								'users'=>'',
								'measure'=>2,
								'fy_dates'=>$fy_dates,
								'duration'=>'');
		$data['searchFilters']=$searchFilters;
		/*$date='';
		$month_no='';
		$dd=get_quarter_start_end_dates($date,$searchFilters);
		echo $this->db->last_query();
		print_r($searchFilters);exit;
		*/
		//$date=date('Y-m-d');$month_no=10;
		$data['chart1Data'] = get_funnel_chart1($searchFilters);
	//	print_r($searchFilters);exit;
		$this->load->view('report/funnel_report', $data);
	}

	public function funnel_chart2()
	{   
		$x_category = $this->input->post('x_category',TRUE);
		$series_name = $this->input->post('series_name',TRUE);
		$searchFilters = array( 'vtime'	=>	$this->input->post('vtime'),
								'measure' => $this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'region'=> $this->input->post('region'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text')
								);
		//print_r($searchFilters);exit;
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		
		$searchFilters['fy_dates']=$fy_dates;
		$chart2Data = get_funnel_chart2($x_category,$series_name,$searchFilters);
		echo $chart2Data;
	}

	public function funnel_chart3()
	{   $x_category2 = $this->input->post('x_category2',TRUE);
		$series_name2 = $this->input->post('series_name2',TRUE);
		$searchFilters = array( 'vtime'	=>	$this->input->post('vtime'),
								'measure' => $this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'region'=> $this->input->post('region'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text')
								);
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		$chart3Data = get_funnel_chart3($x_category2,$series_name2,$searchFilters);
		echo $chart3Data;
	}
	public function filter_funnel_chart()
	{  // $fy_dates=get_start_end_dates($this->input->post('vtime'));
		 $searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'region'=> $this->input->post('region'),
								'measure'=>$this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text')
								);
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		// print_r($searchFilters); exit;
	    $chart1Data=	get_funnel_chart1($searchFilters);
	   
	    echo $chart1Data;exit;

	}
	public function get_filter_funnel_table()
	{
		 $searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'region'=> $this->input->post('region'),
								'measure'=>$this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text')
								);
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		$category=$this->input->post('category');
		$series_name=$this->input->post('series_name');
		$searchFilters['search_date']=$this->input->post('search_date');
		$results=get_filter_funnel_table_list($category,$series_name,$searchFilters);
		echo $results;
	}
	public function get_filter_duration()
	{
		$vtime=$this->input->post('vtime');
		$searchFilters=array();
		$searchFilters['duration']='';
		$fy_dates=get_start_end_dates($vtime,'',$searchFilters);
		//print_r($fy_dates);
		if($vtime=='m')
		{
			$res=get_fy_months_array($fy_dates);

		}
		elseif($vtime=='q')
		{
			$res=get_fy_quarter_array($fy_dates);
		}
		elseif($vtime=='w')
		{
			$res=get_fy_week_array($fy_dates);
		}
		else
		{
			$res='';
		}
	    //echo $vtime;
		echo $res;

	}
	public function get_custom_filter_duration()
	{
		$vtime=$this->input->post('vtime');
		$searchFilters=array();
		$searchFilters['duration']='';
		$fy_dates=get_custom_start_end_dates($vtime,'',$searchFilters);
		//print_r($fy_dates);
		if($vtime=='m')
		{
			$res=get_custom_fy_months_array($fy_dates);

		}
		elseif($vtime=='q')
		{
			$res=get_custom_fy_quarter_array($fy_dates);
		}
		elseif($vtime=='w')
		{
			$res=get_custom_fy_week_array($fy_dates);
		}
		else
		{
			$res='';
		}
	    //echo $vtime;
		echo $res;

	}
	public function download_stock_in_hand_xl()
	{
		$search = $this->input->post('download',TRUE);
		if($search!='')
		{
			$searchParams = array(
			'category' => $this->input->post('category',TRUE),
			'segment' => $this->input->post('segment',TRUE),
			'product' => $this->input->post('product',TRUE));
		}
		else
		{
			$searchParams = array(
			'category' => '',
			'segment' => '',
			'product' => '');
		}
		$results=$this->Report_model->get_stock_in_hand_products_xl($searchParams);
		if($results[0]['as_on_date']!='')
		{
			$as_on_date =date('d-m-Y',strtotime(@$results[0]['as_on_date']));
		}
		else
		{
			$as_on_date=' ';
		}
		$this->excel->setActiveSheetIndex(0);
		 $style = array  (
				        'alignment' => array(
				        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				        )
				    );
        $this->excel->getActiveSheet()->setTitle('Product Stock In Hand');
        $this->excel->getActiveSheet()->mergeCells('A1:F1')->setCellValue('A1', 'Last Updated ON: '.$as_on_date);
        //$this->excel->getActiveSheet()->mergeCells('D1:F1')->setCellValue('D1', $as_on_date);
        $this->excel->getActiveSheet()->getStyle("A1:F1")->applyFromArray($style)->getFont()->setBold('true');
        $this->excel->getActiveSheet()->setCellValue('A2', 'S.No');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Product Code');
        $this->excel->getActiveSheet()->setCellValue('C2', 'Product Description');
        $this->excel->getActiveSheet()->setCellValue('D2', 'Segment');
        $this->excel->getActiveSheet()->setCellValue('E2', 'Category');
        $this->excel->getActiveSheet()->setCellValue('F2', 'Quantity');
        /*$this->excel->getActiveSheet()->setCellValue('G1', 'As on Date');*/
        $exceldata="";
        if(count($results)>0)
        {
            $arr=array();
            $i=1;
            foreach ($results as $row)
            {      
                    $exceldata=array();
                    $exceldata[] = @$i;
                    $exceldata[] = @$row['name'];
                    $exceldata[] = @$row['description'];
                    $exceldata[] = @$row['group_name'];
                    $exceldata[] = @$row['category_name'];
                    $exceldata[] = @$row['quantity'];
                    /*if(@$row['as_on_date']!='')
                    {
                        $exceldata[] =date('d-m-Y',strtotime(@$row['as_on_date']));
                    }
                    else
                    {
                      $exceldata[]='';
                    }*/
                    // echo "<br>";
                    $arr[]=$exceldata;
                    $i++;
                   
            }
             $this->excel->getActiveSheet()->fromArray($arr, null, 'A3');
        }
        else
        {
        	$exceldata[]="No Records Found";
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
            $this->excel->getActiveSheet()->mergeCells('A3:F3');
        }
        $filename='Product Stock List.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
        foreach(range('A2','F2') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $objWriter->save('php://output');
	}

	public function outstanding_report()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Outstanding Report";
		$data['nestedView']['cur_page'] = 'Outstanding Report';
		$data['nestedView']['parent_page'] = 'Outstanding Report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Outstanding Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Outstanding Report','class'=>'active','url'=>'');

		$data['region']=$this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));
		$data['sector']=$this->Common_model->get_data('customer_category',array('status'=>1));
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	
		$fy_dates=get_start_end_dates('y');
		$searchFilters = array(
								'region'=> '',
								'users'=>'',
								'sector'=>'');
		$data['searchFilters']=$searchFilters;
		$data['chart1Data1'] = outstanding_chart1($searchFilters);

		$this->load->view('report/outstanding_report', $data);
	}

	public function getoutstandingChart1Data()
	{
		
		$searchFilters = array(
								'region'=> $this->input->post('region'),
								'users'=> $this->input->post('users'),
								'sector'=>$this->input->post('sector')
								);

		$chart1Data=outstanding_chart1($searchFilters);
	    echo $chart1Data;exit;
	}

	public function getoutstandingChart2Data()
	{
		$series_name = $this->input->post('series_name');
		$category=$this->input->post('x_category');
		$searchFilters = array(
								'region'=> $this->input->post('region'),
								'users'=> $this->input->post('users'),
								'sector'=>$this->input->post('sector')
								);
		$chartData =outstanding_chart2($series_name,$category,$searchFilters);
		echo $chartData;
	}
	public function getoutstandingChart3Data()
	{
		$category1=$this->input->post('category');
		$category=str_replace('/AB', '&', $category1);
		$series_name = $this->input->post('series_name');
		$aging=$this->input->post('aging');
		$searchFilters = array(
								'region'=> $this->input->post('region'),
								'users'=> $this->input->post('users'),
								'sector'=>$this->input->post('sector')
								);
		$chartData = outstanding_chart3($searchFilters,$category,$series_name,$aging);
		echo $chartData;
	}
	  public function run_rate()
    {
    	# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Run Rate Projection ";
		$data['nestedView']['cur_page'] = 'run_rate';
		$data['nestedView']['parent_page'] = 'run_rate';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Run Rate Projection';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Run Rate Projection','class'=>'active','url'=>'');

		$data['region']=$this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));
		$data['product_category']=$this->Common_model->get_data('product_category',array('status'=>1));
		$data['products']=$this->Common_model->get_data('product',array('status'=>1));
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	

		$fy_dates=get_start_end_dates('y');
		$searchFilters = array('from_date'	=>	'',
								'to_date'	=>	'',
								'vtime'	    =>	'y',
								'region'    =>   '',
								'users'     =>   '',
								'measure'   =>   2,
								'fy_dates'  =>   $fy_dates,
								'duration'  =>   '',
								'range'	    =>   '',
								'category_id'=>  '',
								'product_id'=>   '',
							    'zone'      =>   1);
		$data['searchFilters']=$searchFilters;

		$data['chart1Data'] = runrate_chart1($searchFilters);
		
		$this->load->view('report/run_rate_projection', $data);
    }
     
    public function filter_runrate_chart()
	{  // $fy_dates=get_start_end_dates($this->input->post('vtime'));
		 $searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	'y',
								'region'=> $this->input->post('region'),
								'measure'=>$this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text'),
								'range'	=>	$this->input->post('range'),
								'category_id'=> $this->input->post('category_id'),
								'product_id'=>$this->input->post('product_id'),
								'zone'=>1
								);
		$fy_dates=get_start_end_dates('y','',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		// print_r($searchFilters); exit;
	    $chart1Data=runrate_chart1($searchFilters);
	   
	    echo $chart1Data;exit;

	}

	public function rr_pro_table()
	{	
		/*echo "<pre>";
		print_r($_POST); exit();*/
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Runrate Projection Report";
		$data['nestedView']['cur_page'] = 'rr_pro_table';
		$data['nestedView']['parent_page'] = 'rr_pro_table';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href=" '.assets_url().'js/jquery.icheck/skins/square/blue.css">';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Runrate Projection';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Runrate Projection Report','class'=>'active','url'=>'');

		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['region']=$this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	
		$data['product_category']=$this->Common_model->get_data('product_category',array('status'=>1));
		$cat_id=$this->input->post('category_id');
		$data['products']=$this->Report_model->get_product_data($cat_id);
		
		$fy_dates=get_start_end_dates('y');
		$search = $this->input->post('search',TRUE);
		if($search!='')
		{
			$searchParams = array(
			'from_date' => '',
			'to_date'   => '',
			'vtime'     => 'y',
			'measure'   => $this->input->post('measure',TRUE),
			'users'     => $this->input->post('users',TRUE),
			'region'    => $this->input->post('region',TRUE),
			'duration'  => $this->input->post('duration'),
			'range'     => $this->input->post('range'),
			'category_id'=>$this->input->post('category_id'),
			'product_id'=> $this->input->post('product_id'),
			'fy_dates'  => $fy_dates,
			'zone'      => 2,
		    'view_page' => 2);
		}
		else
		{
			$searchParams = array(
			'from_date' => '',
			'to_date' => '',
			'vtime' => 'y',
			'measure' => 1,
			'users' => '',
			'region' => '',
			'duration'=>'',
			'fy_dates'=>$fy_dates,
			'zone' => 2,
		    'range' =>'',
		    'category_id'=>'',
		    'product_id' =>'',
		    'view_page' =>2);
		}
		#additional Data
		$data['searchParams'] = $searchParams;
		$data['searchFilters']=$searchParams;
		$table_data1=runrate_chart1($searchParams);
		$data['table_data']=$table_data1[0];
		$data['conversion_rate']=$table_data1[1];
	    //print_r($data['table_data']);exit;
		$this->load->view('report/runrate_projection_table', $data);
	}

	
	public function download_rr_report()
	{   
		$search = $this->input->post('search',TRUE);
		$fy_dates=get_start_end_dates('y');
		$fy_year=get_current_fiancial_year();
		if($search!=''){
		$searchParams = array(
			'from_date' => '',
			'to_date'   => '',
			'vtime'     => 'y',
			'measure'   => $this->input->post('measure',TRUE),
			'users'     => $this->input->post('users',TRUE),
			'region'    => $this->input->post('region',TRUE),
			'duration'  => $this->input->post('duration',TRUE),
			'range'     => $this->input->post('range'),
			'category_id'=> $this->input->post('category_id'),
			'product_id'=> $this->input->post('product_id'),
			'fy_dates'  => $fy_dates,
			'zone'      => 2,
		    'view_page' => 2);
		}
		else
		{
			$searchParams = array(
			'from_date' => '',
			'to_date' => '',
			'vtime' => 'y',
			'measure' => 1,
			'users' => '',
			'region' => '',
			'duration'=>'',
			'fy_dates'=>$fy_dates,
			'category_id'=>'',
			'product_id'=>'',
			'zone' => 2,
		    'range' =>'',
		    'view_page' =>2);
		}
		$table_data1=runrate_chart1($searchParams);
		$results=$table_data1[0];
		$conversion_rate=$table_data1[1];
	    $region_text='Runrate Projection For FY Year: '.$fy_year['name'];
        if($searchParams['region']!='' )
        {
        	$region_name=$this->Common_model->get_value('location',array('location_id'=>$searchParams['region']),'location');
        	$region_text.=' in '.$region_name;
        }
        if($searchParams['users']!='')
        {
        	$region_name=$this->Common_model->get_data_row('user',array('user_id'=>$searchParams['users']));
        	$region_text.=' For '.$region_name['first_name'].'('.$region_name['employee_id'].' )';
        }
		$this->excel->setActiveSheetIndex(0);
		 $style = array  (
				        'alignment' => array(
				        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				        )
				    );
        $this->excel->getActiveSheet()->setTitle('RunRate Projection');
        $this->excel->getActiveSheet()->mergeCells('A1:I1')->setCellValue('A1',$region_text );

        //$this->excel->getActiveSheet()->mergeCells('D1:F1')->setCellValue('D1', $as_on_date);
        $this->excel->getActiveSheet()->getStyle("A1:F1")->applyFromArray($style)->getFont()->setBold('true');
        $this->excel->getActiveSheet()->setCellValue('A2', 'S.No');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Month');
        $this->excel->getActiveSheet()->setCellValue('C2', 'Funnel Created (In Lacs)');
        $this->excel->getActiveSheet()->setCellValue('D2', 'Closed Won (In Lacs)');
        $this->excel->getActiveSheet()->setCellValue('E2', 'Conversion Rate');
        $this->excel->getActiveSheet()->setCellValue('F2', 'Min Conversion Rate');
        $this->excel->getActiveSheet()->setCellValue('G2', 'Min Conversion Value (In Lacs)');
        $this->excel->getActiveSheet()->setCellValue('H2', 'Max Conversion Rate');
        $this->excel->getActiveSheet()->setCellValue('I2', 'Max Conversion Value (In Lacs)');
        $this->excel->getActiveSheet()->setCellValue('J2', 'Custom Conversion Rate');
        $this->excel->getActiveSheet()->setCellValue('K2', 'Custom Conversion Value (In Lacs)');
        /*$this->excel->getActiveSheet()->setCellValue('G1', 'As on Date');*/
        $exceldata="";
        if(count($results)>0)
        {
            $arr=array();
            $i=1;
            foreach ($results as $row)
            {      
                    $exceldata=array();
                    $exceldata[] = @$i;
                    $exceldata[] = @$row['month_name'];
                    $exceldata[] = @$row['new_op_val'];
                    $exceldata[] = @$row['new_sale_val'];
                    $exceldata[] = @$row['conversion_rate'];
                    if($row['min_con_rate']!='')
                    {
                    	$exceldata[] = @$row['min_con_rate'];
                    }
                    else
                    {
                    	$exceldata[]='--';
                    }
                    if($row['min_con_val']!='')
                    {
                    	$exceldata[] = @$row['min_con_val'];
                    }
                    else
                    {
                    	$exceldata[]='--';
                    }
                    if($row['max_con_rate']!='')
                    {
                    	$exceldata[] = @$row['max_con_rate'];
                    }
                    else
                    {
                    	$exceldata[]='--';
                    }
                    if($row['max_con_val']!='')
                    {
                    	$exceldata[] = @$row['max_con_val'];
                    }
                    else
                    {
                    	$exceldata[]='--';
                    }
                    if($row['cus_con_rate']!='')
                    {
                    	$exceldata[] = @$row['cus_con_rate'];
                    }
                    else
                    {
                    	$exceldata[]='--';
                    }
                    if($row['cus_con_val']!='')
                    {
                    	$exceldata[] = @$row['cus_con_val'];
                    }
                    else
                    {
                    	$exceldata[]='--';
                    }	
                    /*if(@$row['as_on_date']!='')
                    {
                        $exceldata[] =date('d-m-Y',strtotime(@$row['as_on_date']));
                    }
                    else
                    {
                      $exceldata[]='';
                    }*/
                    // echo "<br>";
                    $arr[]=$exceldata;
                    $i++;
                   
            }
            $this->excel->getActiveSheet()->fromArray($arr, null, 'A3');
        }
        else
        {
        	$exceldata[]="No Records Found";
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
            $this->excel->getActiveSheet()->mergeCells('A3:F3');
        }
        $filename='Runrate Projection.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
        foreach(range('A2','F2') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $objWriter->save('php://output');
	}
	public function incentives()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Incentives Report";
		$data['nestedView']['cur_page'] = 'Incentives Report';
		$data['nestedView']['parent_page'] = 'Incentives Report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

		$data['nestedView']['css_includes'] = array();

		# Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Incentives Report';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Incentives Report', 'class' => 'active', 'url' => '');


        $data['region']=$this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>4));

        $year = date('Y');
        $month = date('m');
        $data['fy_year']=$this->Report_model->get_incentives_financial_year();
        if($month == 4 || $month == 5 || $month == 6)
        {
        	$fy_id = $data['fy_year'][1]['fy_id'];
        }
        else
        {
        	$fy_year1=get_current_fiancial_year();
        	$fy_id = $fy_year1['fy_id'];
        }
        $quarter=get_incentives_quarters($fy_id);
        $qcount = count($quarter)-1;
        $q_row = $quarter[$qcount];
        $start_date = $q_row['start_date'];
        $end_date = $q_row['end_date'];
        $user_id=$this->session->userdata('user_id');
        $role=$this->session->userdata('role_id');
        if($role==6)
        {
        	$reportees=0;
        }
        else
        {
        	$reportees=$this->session->userdata('reportees');
        }
		
		$user_reportees= $reportees.','.$user_id;
		
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);
		$year_name=$this->Common_model->get_value('financial_year',array('fy_id'=>$fy_id),'name');
		$searchFilters = array(
								'vtime'			=>	'q',
								'regions'		=> 	'',
								//'users'			=>	'',
								'duration'		=>	'',
								'duration_text' =>	$q_row['quarter'],
								'year'			=>	$fy_id,
								'from_date'		=>	$start_date,
								'to_date'		=>	$end_date,
								'year_text'		=>	$year_name);
		if($role==4)
		{
			$searchFilters['users']=$this->session->userdata('user_id');
			$json = file_get_contents('php://input');
			$dat=incentives_user_chart1($searchFilters);
			$inc_data=json_decode($dat,true);
			$results=$inc_data['results'];
			$result_products=$inc_data['product_category'];
			$customer_data='';
			
			$j=1;
			$table_data='';
			$table_data.="<tr>";
			$table_data.="<th class=text-center  width=5%><strong>S.No </strong></th>
						  <th class=text-center  width=10%><strong>Role</strong></th>
						  <th class=text-center  width=25%><strong>User</strong></th>";
			foreach($result_products as $product)
			{
				$table_data.="<th class=text-center width=10%><strong>".$product['name']." Target  &nbsp;&nbsp; (in Lakhs)</strong></th>
				              <th class=text-center width=10%><strong>".$product['name']." Sales  &nbsp;&nbsp; (in Lakhs)</strong></th>";
			}
			$table_data.="<th class=text-center width=20%><strong>Incentive Amount (In Rs)</strong></th>";

			foreach($results as $key=>$value)
			{
				
				$customer_data.="<tr> <td style='font-size: 12;' align='center'>".$j++."</td><td  style='font-size :!2;' align='left'>".$value['role']."</td><td  style='font-size :!2;' align='left'>".$value['name']."</td>";
				foreach($result_products as $row)
				{
					
					$customer_data.="<td  style='font-size :!2;' align='right'>".$value['targets'][$row['category_id']]['current_target']."</td><td  style='font-size :!2;' align='right'>".$value['sales'][$row['category_id']]['current_sales']."</td>";
				}
				$customer_data.="<td  style='font-size :!2;' align='right'>".$value['incentive_amount']."</td></tr>";
			}
			$data_arr=array('results'=>$customer_data,'table_text'=>$inc_data['table_text'],'total_amount'=>$inc_data['total_amount'],'table_head'=>$table_data);
			$data['chart1Data1']=json_encode($data_arr);
		}
		else
		{
			$searchFilters['users']='';
			$data['chart1Data1'] = incentives_chart1($searchFilters);
		}
		$data['searchFilters']=$searchFilters;
		$data['quarter'] = $quarter;
		$data['role']=$role;
		$product_category=$this->Common_model->get_data('product_category',array('status'=>1));

        $this->load->view('report/incentives_report', $data);
	}
	public function filter_incentives_chart()
	{
		$quarter=$this->input->post('duration');
		$quat=explode('to', $quarter);
		$users1=$this->input->post('users',TRUE);
		$region=$this->input->post('region',TRUE);
		
		$from_date=$quat[0];
		$to_date=$quat[1];
		$searchFilters = array(
								'vtime'			=>	'q',
								'regions'		=> 	$region,
								//'users'			=>	$this->input->post('users',TRUE),
								'duration'		=>	$this->input->post('duration'),
								'year'			=>	$this->input->post('year',TRUE),
								'from_date'		=>	$from_date,
								'to_date'		=>	$to_date,
								'duration_text'	=>	$this->input->post('duration_text',TRUE),
								'year_text'		=>	$this->input->post('year_text',TRUE));
		$role 		=	$this->session->userdata('role_id');
		if($role==4 || $users1!='')
		{
			if($users1!=$this->session->userdata('user_id') && $users1!='')
			{
				$searchFilters['users']=$users1;
			}
			else
			{
				$searchFilters['users']=$this->session->userdata('user_id');
			}
			$json = file_get_contents('php://input');
			$dat=incentives_user_chart1($searchFilters);
			$inc_data=json_decode($dat,true);
			$results=$inc_data['results'];
			$result_products=$inc_data['product_category'];
			$customer_data='';
			$table_data='';
			$table_data.="<tr>";
			$table_data.="<th class=text-center  width=5%><strong>S.No </strong></th>
						  <th class=text-center  width=10%><strong>Role</strong></th>
						  <th class=text-center  width=25%><strong>User</strong></th>";
			foreach($result_products as $product)
			{
				$table_data.="<th class=text-center width=10%><strong>".$product['name']." Target  &nbsp;&nbsp; (in Lakhs)</strong></th>
				              <th class=text-center width=10%><strong>".$product['name']." Sales  &nbsp;&nbsp; (in Lakhs)</strong></th>";
			}
			$table_data.="<th class=text-center width=20%><strong>Incentive Amount (In Rs)</strong></th>";
			$j=1;
			foreach($results as $key=>$value)
			{
				
				$customer_data.="<tr> <td style='font-size: 12;' align='center'>".$j++."</td><td  style='font-size :!2;' align='left'>".$value['role']."</td><td  style='font-size :!2;' align='left'>".$value['name']."</td>";
				foreach($result_products as $row)
				{
					
					$customer_data.="<td  style='font-size :!2;' align='right'>".$value['targets'][$row['category_id']]['current_target']."</td><td  style='font-size :!2;' align='right'>".$value['sales'][$row['category_id']]['current_sales']."</td>";
				}
				$customer_data.="<td  style='font-size :!2;' align='right'>".$value['incentive_amount']."</td></tr>";
			}
			$data_arr=array('results'=>$customer_data,'table_text'=>$inc_data['table_text'],'total_amount'=>$inc_data['total_amount'],'table_head'=>$table_data);
			$chart1Data =json_encode($data_arr);

			
		}
		else
		{
			$searchFilters['users']=$users1;
			$chart1Data = incentives_chart1($searchFilters);
		}
		
	    echo $chart1Data;exit;
	}
	public function get_quarter_based_on_year()
	{
		$year_id=$this->input->post('year_id');
		$quat_dropdown=get_year_quarter_dropdown($year_id);
		echo $quat_dropdown;
	}
	public function get_incentives_chart2()
	{
		$quarter=$this->input->post('duration');
		$quat=explode('to', $quarter);
		$from_date=$quat[0];
		$to_date=$quat[1];
		$searchFilters = array(
								'vtime'			=>	'q',
								'regions'		=> 	$this->input->post('region',TRUE),
								'users'			=>	$this->input->post('users',TRUE),
								'duration'		=>	$this->input->post('duration'),
								'year'			=>	$this->input->post('year',TRUE),
								'from_date'		=>	$from_date,
								'to_date'		=>	$to_date,
								'series_name'	=>	$this->input->post('series_name',TRUE),
								'duration_text'	=>	$this->input->post('duration_text',TRUE),
								'year_text'		=>	$this->input->post('year_text',TRUE));

		$json = file_get_contents('php://input');
		$dat=incentives_chart2($searchFilters);
		$inc_data=json_decode($dat,true);
		$results=$inc_data['results'];
		$result_products=$this->Common_model->get_data('product_category',array('status'=>1));
		$customer_data='';
		$table_data='';
		$table_data.="<tr>";
		$table_data.="<th class=text-center  width=5%><strong>S.No </strong></th>
					  <th class=text-center  width=10%><strong>Role</strong></th>
					  <th class=text-center  width=25%><strong>User</strong></th>";
		foreach($result_products as $product)
		{
			$table_data.="<th class=text-center width=10%><strong>".$product['name']." Target  &nbsp;&nbsp; (in Lakhs)</strong></th>
			              <th class=text-center width=10%><strong>".$product['name']." Sales  &nbsp;&nbsp; (in Lakhs)</strong></th>";
		}
		$table_data.="<th class=text-center width=20%><strong>Incentive Amount (In Rs)</strong></th>";
		$j=1;
		foreach($results as $key=>$value)
		{
			
			$customer_data.="<tr> <td style='font-size: 12;' align='center'>".$j++."</td><td  style='font-size :!2;' align='left'>".$value['role']."</td><td  style='font-size :!2;' align='left'>".$value['name']."</td>";
			foreach($result_products as $row)
			{
				if(@$value['targets'][$row['category_id']][$value['user']]['current_target']=='')
				{
					$target=0;
				}
				else
				{
					$target=@$value['targets'][$row['category_id']][$value['user']]['current_target'];
				}
				if(@$value['sales'][$row['category_id']][$value['user']]['current_sales']=='')
				{
					$sales=0;
				}
				else
				{
					$sales=@$value['sales'][$row['category_id']][$value['user']]['current_sales'];
				}
				$customer_data.="<td  style='font-size :!2;' align='right'>".$target."</td><td  style='font-size :!2;' align='right'>".$sales."</td>";
			}
			$customer_data.="<td  style='font-size :!2;' align='right'>".$value['incentive_amount']."</td></tr>";
		}
		$data_arr=array('results'=>$customer_data,'table_text'=>$inc_data['table_text'],'total_amount'=>$inc_data['total_amount'],'table_head'=>$table_data);
		$chart1Data =json_encode($data_arr);




	    echo $chart1Data;exit;
	}

	public function download_incentives()
	{
		$users1 	=	$this->input->post('users',TRUE);
		$year_id	=	$this->input->post('year_id',TRUE);
		$quarter_id	=	$this->input->post('quarter_id',TRUE);
		$region 	=	$this->input->post('region',TRUE);
		$role 		=	$this->session->userdata('role_id');
		$user_roles =  $this->input->post('user_role',TRUE);
		$quat=explode('to', $quarter_id);
		$from_date=$quat[0];
		$to_date=$quat[1];
		$searchFilters = array(
								'vtime'			=>	'q',
								'regions'		=> 	$region,
								//'users'			=>	$users,
								'duration'		=>	$quarter_id,
								'year'			=>	$year_id,
								'from_date'		=>	$from_date,
								'to_date'		=>	$to_date,
								'series_name'	=>	'',
								'duration_text'	=>	'',
								'year_text'		=>	'');
		if($role==4)
		{
			$searchFilters['users']=$this->session->userdata('user_id');
		}
		else
		{
			$searchFilters['users']=$users1;
		}
		$session_user_id=$this->session->userdata('user_id');
		$inc_data=array();
		if($users1=='' && $role!=4)
		{
			
			if($searchFilters['users']!='')
		    {   
		    	//echo $searchFilters['users'];
		    	$users=$searchFilters['users'];
		    	$reportees=getReportingUsers($users);
		    	$reportee_users_id=$reportees.','.$users;
		    }
		    else
		    {
		    	$users=$this->session->userdata('user_id');
		    	$reportees=$this->session->userdata('reportees');
		    	$reportee_users_id=$reportees.','.$users;
		    }

		    if($user_roles!='')
		    {
		    	
		    	$roles[]=$this->Common_model->get_data_row('role',array('short_name'=>$user_roles));
		    }
		    else
		    {
		    	$roles=$this->Report_model->get_all_roles($reportee_users_id);
		    }
			//echo "<pre>"; print_r($roles); exit;
			
			foreach($roles as $row)
			{
				$role_based_users=$this->Report_model->get_role_based_users($row['role_id'],$reportee_users_id);
				$amount=0;
				$incentive_data=array();

				foreach($role_based_users as $users)
				{
					$check1=$check2=$check3=0;
					$cat_id='';
					$l = getUserLocations($users['user_id']);
					$ul = getQueryArray($l);
					$up = getUserProducts($users['user_id']);
					$userLocations = ($ul == '')? 0: $ul;
					$userProducts = ($up == '')? 0: $up;
					$reportees=getReportingUsers($users['user_id']);
		    		$user_id=$reportees.','.$users['user_id'];
		    		//$user_id=27;
		    		$product_category=$this->Report_model->get_product_category($userProducts);
		    		
		    		//print_r($searchFilters); exit;
		    		if(count($product_category)<=1)
					{
						$cat_id=$product_category[0]['category_id'];
					}
					else
					{
						$cat_id='';
					}
		    		$sales_without_category = $this->Report_model->get_incentive_user_sales($user_id,$searchFilters,$userLocations,$userProducts,$cat_id,$row['role_id']);
		    		$target_without_category = $this->Report_model->get_incentive_user_target($user_id,$searchFilters,$cat_id);

		    		$user_data=$this->Common_model->get_data_row('user',array('user_id'=>$users['user_id']));
	    			$incentive_data['name']=$user_data['first_name'].' '.$user_data['last_name'];
	    			$incentive_data['role']=$row['short_name'];
	    			//$incentive_data['region']=$sales_without_category['region'];
	    			$incentive_data['user']=$users['user_id'];
		    		foreach($product_category as $row1)
		    		{
		    			$incentive_user_target=$this->Report_model->get_incentive_user_target($user_id,$searchFilters,$row1['category_id']);
		    			//echo $CI->db->last_query(); exit;
		    			$incentive_user_sales=$this->Report_model->get_incentive_user_sales($user_id,$searchFilters,$userLocations,$userProducts,$row1['category_id'],$row['role_id']);
		    			
						if($incentive_user_target['current_target']!='')
						{
							$incentive_data['targets'][$row1['category_id']][$users['user_id']]=$incentive_user_target;
						}
						else
						{
							$incentive_data['targets'][$row1['category_id']][$users['user_id']]=array('current_target'=>0);	
						}
						if($incentive_user_sales['current_sales']>0)
						{
							$incentive_data['sales'][$row1['category_id']][$users['user_id']]=$incentive_user_sales;
						}
						else
						{
							$incentive_data['sales'][$row1['category_id']][$users['user_id']]=array('current_sales'=>0);	
						}
		    			
		    			if($incentive_user_sales['current_sales']>0 && $incentive_user_target['current_target']>0)
						{
							$percent = ($incentive_user_sales['current_sales'] / $incentive_user_target['current_target'])*100;
						}
						else
						{
							$percent=0;
						}
						$incentives=$this->Common_model->get_data_row('incentives',array('role_id'=>$row['role_id'],'fy_id'=>$searchFilters['year'],'status'=>1));

						if($percent >= $incentives['pp_ll'])
						{
							$check1++;
							if($percent>= 100)
							{
								$check2++;
							}
						}
						else if($percent >= $incentives['sp2_ll'] && $percent <= $incentives['sp2_ul'])
						{
							$check3++;
						}

						
		    		}
		    		

					if(count($product_category) == $check1 )
					{
						if($sales_without_category['current_sales']>0 && $target_without_category['current_target']>0)
						{   
							$inc_amt = round((($sales_without_category['current_sales']/$target_without_category['current_target'])*$incentives['value'])/2);
							

						}
						else
						{
							$inc_amt=0;
						}

						if($inc_amt>$incentives['upper_value']/2)
						{
							$inc_amt=$incentives['upper_value']/2;
						}
						$incentive_data['incentive_amount']=$inc_amt;
					}
					else if($row['role_id'] == 4)
					{
						//check given is grade B
						if(count($product_category) == ($check2+$check3)  && $check2>0)
						{
							
							$inc_amt = 15000/2;
						}
						else
						{
							$inc_amt = 0;
						}
						$incentive_data['incentive_amount']=$inc_amt;
					}
					else
					{
						//fail
						$inc_amt = 0;
						$incentive_data['incentive_amount']=$inc_amt;
					}
					$amount+=$inc_amt;
					$inc_data[]=$incentive_data;
				}
				
			}
			//$inc_data['product_category']=$pro_cat;
		}
		
		else
		{
			$json = file_get_contents('php://input');
			$dat=incentives_user_chart1($searchFilters);

			$inc_data=json_decode($dat,true);
			
		}
		//echo $role; exit;
		if($users1=='' && $role!=4)
		{
			$incentive_result=$inc_data;
			$pro_cat=$this->Common_model->get_data('product_category',array('status'=>1));
			$pro_cat_arr=$pro_cat;
			$arr=array();
        	$i=1;
        	foreach ($incentive_result as $key=>$res)
        	{
        		if($res['incentive_amount'] =='')
        		{
        			$inc_amt=0;
        		}
        		else
        		{
        			$inc_amt=$res['incentive_amount'];
        		}
        		$exceldata=array();
        		$exceldata[] = $i++;
        		$exceldata[] = @$res['role'];
        		$exceldata[] = @$res['name'];
        		foreach($pro_cat_arr as $row)
        		{
        			if(@$res['targets'][$row['category_id']][$res['user']]['current_target']!='' && @$res['sales'][$row['category_id']][$res['user']]['current_sales']!='')
        			{
        				
        				$target=$res['targets'][$row['category_id']][$res['user']]['current_target'];
        				$sales=$res['sales'][$row['category_id']][$res['user']]['current_sales'];
        				$rad_rate=round((@$res['sales'][$row['category_id']][$res['user']]['current_sales']/@$res['targets'][$row['category_id']][$res['user']]['current_target'])*100,2);
        				//echo $res['sales'][$row['category_id']][$res['user']]['current_sales'];
        				if($rad_rate==0)
	        			{
	        				$rad_rate=0;
	        			}
        			}
        			else
        			{
        				$rad_rate='--';
        				$target=0;
        				$sales=0;
        			}
        			$exceldata[] = $target;
        			$exceldata[] = $sales;
        			$exceldata[] = $rad_rate;
        		}
        		$exceldata[] = $res['incentive_amount'];
        		$arr[]=$exceldata;
        	}
		}
		else
		{
			$incentive_result=$inc_data['results'];
			$pro_cat_arr=$inc_data['product_category'];
			$arr=array();
        	$i=1;
        	foreach ($incentive_result as $key=>$res)
        	{
        		if($res['incentive_amount'] =='')
        		{
        			$inc_amt=0;
        		}
        		else
        		{
        			$inc_amt=$res['incentive_amount'];
        		}
        		$exceldata=array();
        		$exceldata[] = $i++;
        		$exceldata[] = @$res['role'];
        		$exceldata[] = @$res['name'];
        		foreach($pro_cat_arr as $row)
        		{
        			if(@$res['targets'][$row['category_id']]['current_target']!='' && @$res['sales'][$row['category_id']]['current_sales']!='')
        			{
        				
        				$target=$res['targets'][$row['category_id']]['current_target'];
        				$sales=$res['sales'][$row['category_id']]['current_sales'];
        				$rad_rate=round((@$res['sales'][$row['category_id']]['current_sales']/@$res['targets'][$row['category_id']]['current_target'])*100,2);
        				if($rad_rate==0)
	        			{
	        				$rad_rate=0;
	        			}
        			}
        			else
        			{
        				$rad_rate='--';
        				$target='--';
        				$sales='--';
        			}
        			$exceldata[] = $target;
        			$exceldata[] = $sales;
        			$exceldata[] = $rad_rate;
        		}
        		$exceldata[] = $res['incentive_amount'];
        		$arr[]=$exceldata;
        	}

		}
		$quarter=get_incentives_quarters($year_id);
        $qcount = count($quarter)-1;
        $q_row = $quarter[$qcount];
        $year_text=$this->Common_model->get_value('financial_year',array('fy_id'=>$year_id),'name');
        $post_quat=get_post_quarter_text($from_date,$to_date,$year_id);
        $text='Incentives For ( '.$year_text.' - '.$post_quat.' )';
        $alpha_arr=array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z',27=>'AA',28=>'AB',29=>'AC',30=>'AD',31=>'AE',32=>'AF',33=>'AG',34=>'AH',35=>'AI',36=>'AJ',37=>'AK',38=>'AL',39=>'AM',40=>'AN',41=>'AO',42=>'AP',43=>'AQ',44=>'AR',45=>'AS',46=>'AT',47=>'AU',48=>'AV',49=>'AW',50=>'AX',51=>'AY',52=>'AZ');
		$this->excel->setActiveSheetIndex(0);
		$style = array  (
						        'alignment' => array(
						        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						        )
						);
        $this->excel->getActiveSheet()->setTitle('Incentives Report');
        $this->excel->getActiveSheet()->mergeCells('A1:C1')->setCellValue('A1', '');
        $this->excel->getActiveSheet()->mergeCells('D1:G1')->setCellValue('D1', $text);
        $this->excel->getActiveSheet()->setCellValue('A2', 'S.NO');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Role');
        $this->excel->getActiveSheet()->setCellValue('C2', 'User');
        $row1 = 4;
        $con=2;
		foreach($pro_cat_arr as $row)
		{
			$this->excel->getActiveSheet()->setCellValue($alpha_arr[$row1].$con, $row['name'].' Target (In Lakhs)');
			$row1=$row1+1;
			$this->excel->getActiveSheet()->setCellValue($alpha_arr[$row1].$con, $row['name'].' Sales (In Lakhs)');
			$row1=$row1+1;
			$this->excel->getActiveSheet()->setCellValue($alpha_arr[$row1].$con, $row['name'].' Achieved %');
			$row1=$row1+1;
			
		}
		
        $this->excel->getActiveSheet()->setCellValue($alpha_arr[$row1].$con, 'Incentive Amount');
        $this->excel->getActiveSheet()->getStyle("A2:".$alpha_arr[$row1]."2")->applyFromArray($style)->getFont()->setBold('true');
        $exceldata="";
        if(count($incentive_result)>0)
        {
        	$arr;
        	$this->excel->getActiveSheet()->fromArray($arr, null, 'A3');
        }
		else
        {
             $exceldata[]="No Records Found";
             $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
             $this->excel->getActiveSheet()->mergeCells('A3:'.$alpha_arr[$row1].'3');
        }
        $filename='Incentives Report.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
        foreach(range('A2',$alpha_arr[$row1].'2') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $objWriter->save('php://output');

	}
	public function dependent_products()
	{
		$category_id=$this->input->post('category_id');
		$products=$this->Report_model->get_dependent_products($category_id);
		echo $products;
	}
}