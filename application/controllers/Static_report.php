<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Static_report extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->helper('report_helper');
        $this->load->helper('static_report_helper');
        $this->load->model('Report_model');
	}
	public function static_funnel_report()
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
								'measure'=>1,
								'fy_dates'=>$fy_dates,
								'duration'=>'');
		$data['searchFilters']=$searchFilters;
		
		/*$date='';
		$month_no='';
		$dd=get_quarter_start_end_dates($date,$searchFilters);
		echo $this->db->last_query();
		print_r($dd);exit;
		*/$date=date('Y-m-d');
		$res=get_month_no_by_date($date);
		$data['chart1Data1'] = static_get_funnel_chart1($searchFilters);
		//print_r($data['chart1Data']); exit();
		$this->load->view('report/static_funnel_report2', $data);
	}

	public function static_funnel_chart2()
	{   
		$fy_dates=get_start_end_dates($this->input->post('vtime'));
		$x_category = $this->input->post('x_category',TRUE);
		$series_name = $this->input->post('series_name',TRUE);
		$searchFilters = array( 'vtime'	=>	$this->input->post('vtime'),
								'measure' => $this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'region'=> $this->input->post('region'),
								'fy_dates'=>$fy_dates
								);
		//print_r($searchFilters);exit;
		$chart2Data = static_get_funnel_chart2($x_category,$series_name,$searchFilters);
		echo $chart2Data;
	}

	public function static_funnel_chart3()
	{   $fy_dates=get_start_end_dates($this->input->post('vtime'));
		$x_category2 = $this->input->post('x_category2',TRUE);
		$series_name2 = $this->input->post('series_name2',TRUE);
		$searchFilters = array( 'vtime'	=>	$this->input->post('vtime'),
								'measure' => $this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'region'=> $this->input->post('region'),
								'fy_dates'=>$fy_dates
								);
		$chart3Data = static_get_funnel_chart3($x_category2,$series_name2,$searchFilters);
		echo $chart3Data;
	}
	public function static_filter_funnel_chart()
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
	    $chart1Data=static_get_funnel_chart1($searchFilters);
	    echo $chart1Data;exit;

	}
	public function static_get_filter_duration()
	{
		$vtime=$this->input->post('vtime');
		$searchFilters=array();
		$searchFilters['duration']='';
		$fy_dates=get_start_end_dates($vtime,'',$searchFilters);
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
	public function static_opp_lost_report()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Opportunity Lost Report";
		$data['nestedView']['cur_page'] = 'outstandingReport';
		$data['nestedView']['parent_page'] = 'outstandingReport';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts.js"></script>';
		//$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-3d.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/exporting.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/funnel.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/highcharts-more.js"></script>';

		$data['nestedView']['css_includes'] = array();
		//$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Opportunity Lost Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Opportunity Lost Report','class'=>'active','url'=>'');
		$report_by = 1;
		$fy_dates=get_start_end_dates('y');
	    $searchFilters = array('from_date'	=>	'',
							   'to_date'	=>	'',
								'vtime'	=>	'y',
								'fy_dates'=>$fy_dates,
								'duration'=>''
								);
	   // echo $report_by;exit;
		$data['chart1Data'] = json_encode(staticGetOpportunityLostChart1DataReason($report_by,$searchFilters));
		$data['chart10Data'] = json_encode(staticGetOpportunityLostChart1DataCompetitor($report_by,$searchFilters));
		$this->load->view('report/static_opportunity_lost', $data);
	}

	public function static_getOpportunityLostChart2Data()
	{
		$lost_for = $this->input->post('lost_for');
		$report_by=$this->input->post('report_by');
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'		=>	$this->input->post('vtime'),
								'duration'	=>	$this->input->post('duration'),
								'duration_text'	=>	$this->input->post('duration_text'));
		$chartData = staticGetOpportunityLostChart2Data($lost_for,$report_by,$searchFilters);
		echo $chartData;
	}

	public function static_getOpportunityLostChart3Data()
	{
		$lost_for = $this->input->post('lost_for');
		$region = $this->input->post('region');
		$segment = $this->input->post('segment');
		$report_by=$this->input->post('report_by');
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'duration'	=>	$this->input->post('duration'),
								'duration_text'	=>	$this->input->post('duration_text'));
		$chartData = staticGetOpportunityLostChart3Data($lost_for,$region,$segment,$report_by,$searchFilters);
		echo $chartData;
	}
	public function static_opportunity_lost_report_filter()
	{
		$report_by=$this->input->post('report_by');
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'duration'=>$this->input->post('duration'),
								'duration_text'=>$this->input->post('duration_text'));
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		$data=array();
		$data['chart1Data']=staticGetOpportunityLostChart1DataReason($report_by,$searchFilters);
		$data['chart10Data']=staticGetOpportunityLostChart1DataCompetitor($report_by,$searchFilters);
		echo json_encode($data,JSON_NUMERIC_CHECK);
	}
	public function get_filter_duration_ol()
	{
		$vtime=$this->input->post('vtime');
		$searchFilters=array();
		$searchFilters['duration']='';
		$fy_dates=get_start_end_dates($vtime,'',$searchFilters);
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
	public function static_target_vs_sales_report()
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

		$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	
		$fy_dates=get_start_end_dates('y');
		//$data['role_id']	 = $_SESSION['role_id'];  
		$data['role_id'] = 8;
		$searchFilters = array('from_date'	=>	'',
								'to_date'	=>	'',
								'vtime'	=>	'y',
								'measure' =>1 ,
								'groups'=> '',
								'users'=> '',
								'regions'=> '',
								'fy_dates'=>$fy_dates,
								'zone'=> 1
								);
		$data['searchFilters']=$searchFilters;
		$data['chart1Data']=static_getTargetVsSalesChart1Data($searchFilters);	
		$this->load->view('report/target_vs_sales_report', $data);
	}
	public function static_targetVsSalesChart2Data()
	{
		$searchFilters = array('from_date'	=>	$this->input->post('from_date'),
								'to_date'	=>	$this->input->post('to_date'),
								'vtime'	=>	$this->input->post('vtime'),
								'measure' => $this->input->post('measure'),
								'groups'=> $this->input->post('groups'),
								'users'=> $this->input->post('users'),
								'regions'=> $this->input->post('regions'),
								'zone'=> $this->input->post('zone'),
								'duration_text'=>$this->input->post('duration_text'),
								'duration'=>$this->input->post('duration')
								);
		$fy_dates=get_start_end_dates($this->input->post('vtime'),'',$searchFilters);
		$searchFilters['fy_dates']=$fy_dates;
		$chart1Data=static_getTargetVsSalesChart1Data($searchFilters);
		echo $chart1Data;
	}

	public function static_tvs_2_report()
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

		$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
		$user_id=$this->session->userdata('user_id');
		$reportees=$this->session->userdata('reportees');
		$user_reportees= $reportees.','.$user_id;
		//print_r($user_reportees);exit;
		$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	
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
			'duration'=>$this->input->post('duration'),
			'view_page' => 2);
		}
		else
		{
			$searchParams = array(
			'date_from' => '',
			'date_to' => '',
			'timeline' => 'y',
			'measure' => 1,
			'groups' => 1,
			'users' => '',
			'regions' => '',
			'view_page' => 2);
		}
		$searchParams['zone'] = $searchParams['view_page'];
		$searchParams['vtime'] = $searchParams['timeline'];
		$searchParams['from_date'] = $searchParams['date_from'];
		$searchParams['to_date'] = $searchParams['date_to'];

		#additional Data
		$data['searchParams'] = $searchParams;
		$data['table_data'] = static_getTargetVsSalesChart1Data($searchParams);
		$this->load->view('report/tvs_2_report', $data);
	} 
	public function fresh_business_bar()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Fresh BUsiness Report";
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
		
		/*$date='';
		$month_no='';
		$dd=get_quarter_start_end_dates($date,$searchFilters);
		echo $this->db->last_query();
		print_r($dd);exit;
		*/$date=date('Y-m-d');
		$res=get_month_no_by_date($date);
		$data['chart1Data1'] = static_get_fresh_business_bar($searchFilters);
		$this->load->view('report/fresh_business_bar', $data);
	} 
	public function fresh_business_filter_bar()
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
	    $chart1Data=static_get_fresh_business_bar($searchFilters);
	    echo $chart1Data;exit;
	}
	public function fresh_business_bar2()
	{
		$x_category = $this->input->post('x_category',TRUE);
		$series_name = $this->input->post('series_name',TRUE);
		$searchFilters = array( 'vtime'	=>	$this->input->post('vtime'),
								'measure' => $this->input->post('measure'),
								'users'=> $this->input->post('users'),
								'region'=> $this->input->post('region')
								);
		//print_r($searchFilters);exit;
		$chart2Data = fresh_business_bar2_chart($x_category,$series_name,$searchFilters);
		echo $chart2Data;
	}
}