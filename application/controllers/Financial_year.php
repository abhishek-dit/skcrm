<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class Financial_year extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Financialyear_model");
	}
    /* phase 2 changes new controller 
       Modified by prasad */
	public function financial_year() 
	{
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Financial Year";
        $data['nestedView']['cur_page'] = 'financialyear';
        $data['nestedView']['parent_page'] = 'financialyear';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Financial Year';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Financial Year', 'class' => 'active', 'url' => '');

        # Search Functionality
        # Search Functionality
        $psearch = $this->input->post('searchyear', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'fy_year'  => $this->input->post('fy_year',TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'fy_year'  => $this->session->userdata('fy_year')
                );
            } else {
                $searchParams = array(
                    'fy_year'  => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['search_data'] = $searchParams;
       // print_r($data['search_data']);exit;

        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'financial_year/';
        # Total Records
        $config['total_rows'] = $this->Financialyear_model->financialyearTotalRows($searchParams);

        $config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
        $data['total_rows'] = $config['total_rows'];

        $this->pagination->initialize($config);
        $data['pagination_links'] = $this->pagination->create_links();
        $current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        if ($data['pagination_links'] != '') {
            $data['last'] = $this->pagination->cur_page * $config['per_page'];
            if ($data['last'] > $data['total_rows']) {
                $data['last'] = $data['total_rows'];
            }
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $config['per_page']) + 1) . ' to ' . ($data['last']) . ' of ' . $data['total_rows'];
        }
        $data['sn'] = $current_offset + 1;
        /* pagination end */


        # Two query results - Available shop details and count of rows - are returned
        
       $data['yearSearch'] = $this->Financialyear_model->financial_year_details($searchParams,$config['per_page'], $current_offset);
       $data['fy_years'] = $this->Common_model->get_data('financial_year',array('status'=>1,'company_id'=>$this->session->userdata('company')));
      // print_r($data['fy_years']);exit;
       // $data['search_data'] = $searchParams;
        $data['displayList'] = 1;

        $this->load->view('financialyear/financial_view', $data);
    }

	public function add_financial_year()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Financial Year";
		$data['nestedView']['cur_page'] = 'financialyear';
		$data['nestedView']['parent_page'] = 'financialyear';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Financial Year';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Financial Year','class'=>'active','url'=>'');

 		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('financialyear/financial_view', $data);

	}
	public function insert_financial_year()
	{
		if($this->input->post('submit'))
		{
			$start_date = $this->input->post('start_date');
			$msg_start_date = $start_date;
			$end_date   = $this->input->post('end_date');
			$fy_name   =  $this->input->post('financial_year_name');
            $fy_year=array(
            	'start_date' => $start_date,
            	'end_date'   => $end_date,
            	'company_id' => $this->session->userdata('company'),
            	'name'       => $fy_name,
            	'status'     => 1
            	);
            if($start_date < $end_date)
            {
            	$fy_year_id = $this->Common_model->insert_data('financial_year',$fy_year);
            	$j=1;
            	while($start_date<$end_date)
            	{
            		$present_week_start=date('Y-m-d',strtotime($start_date));
            		$present_week_end=date('Y-m-d',strtotime('+6 days',strtotime($start_date)));
            		$month_last_date=date('Y-m-t',strtotime($present_week_start));
            		if($present_week_end<=$month_last_date)
            		{
            			$present_week_end=$present_week_end;
            		}
            		else
            		{
            			$present_week_end=$month_last_date;
            		}
            		$month_number = date('m',strtotime($present_week_start));
            		$year_no= date('Y',strtotime($present_week_start));
					$dat = array(
						'start_date' => $present_week_start,
						'end_date'   => $present_week_end,
						'week_no'    => $j,
						'month_no'   => $month_number,
						'fy_id'      => $fy_year_id,
						'status'     => 1,
						'year_no'    => $year_no
						);
					$this->Common_model->insert_data('fy_week',$dat);
					//echo $this->db->last_query();
					$start_date = date('Y-m-d', strtotime('+'.(1).' days', strtotime($present_week_end)));
					$j++;
            	}
            }
            $start_date = $this->input->post('start_date');
			$end_date   = $this->input->post('end_date');
            if($start_date < $end_date)
            {   
            	
            	$i=1;
            	while($start_date < $end_date)
            	{
            		//echo $start_date;
					$day = date('N', strtotime($start_date));
					if($day<=4)
					{
	                    $week_start = date('Y-m-d', strtotime('-'.($day-1).' days', strtotime($start_date)));
	                    $week_end = date('Y-m-d', strtotime('+'.(7-$day).' days', strtotime($start_date)));
	                    if($week_end >$end_date)
						{
							$week_end=$end_date;
							//echo 'hiiii';
						}
					}
					else
					{
						$present_week_end = date('Y-m-d', strtotime('+'.(7-$day).' days', strtotime($start_date)));
						$week_start=date('Y-m-d', strtotime('+'.(1).' days', strtotime($present_week_end)));
						$week_end = date('Y-m-d', strtotime('+'.(6).' days', strtotime($week_start)));
						if($week_end >$end_date)
						{
							$week_end=$end_date;
							//echo 'hi';
						}
					}
					$week_number = getWeeks($week_start);
					$month_number = date('m',strtotime($week_start));
					$year_no= date('Y',strtotime($week_start));
					$dat = array(
						'start_date' => $week_start,
						'end_date'   => $week_end,
						'week_no'    => $i,
						'month_no'   => $month_number,
						'fy_id'      => $fy_year_id,
						'status'     => 1,
						'year_no'    => $year_no
						);
					$this->Common_model->insert_data('custom_fy_week',$dat);
					//echo $this->db->last_query();
					$start_date = date('Y-m-d', strtotime('+'.(1).' days', strtotime($week_end)));
					$i++;
				}

				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Financial Year for ('.$msg_start_date.' to '.$end_date.') added successfully!
							 </div>');
		        redirect(SITE_URL.'retrieve_weeks/'.icrm_encode($fy_year_id));
			}
			else
			{
				$this->session->set_flashdata('error','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> Start Date ('.$msg_start_date.') should be less than End Date ('.$end_date.')!
									 </div>');
				redirect(SITE_URL.'financial_year');
			}
		}
	}
	
	public function retrieve_weeks($encoded_id)
	{   
		$financial_year = @icrm_decode($encoded_id);
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = " Financial Year Weeks";
		$data['nestedView']['cur_page'] = 'financialyearWeeks';
		$data['nestedView']['parent_page'] = 'financialyear';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Financial Year Weeks';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Financial Year Weeks','class'=>'active','url'=>'');
			//$financial_year = $this->input->post('fy_year');
			$res = $this->Common_model->get_data('custom_fy_week',array('status'=>1,'fy_id'=>$financial_year));
			$data['year'] =  $this->Common_model->get_data_row('financial_year',array('status'=>1,'fy_id'=>$financial_year)); 
			if(count($res) > 0)
			{
				$weeks = array();
				foreach ($res as $key => $value)
				{
					if(array_key_exists(@$keys, $weeks))
					{
						$weeks[$value['month_no']]['week'][$value['week_no']]=array(
							'start_date' => $value['start_date'],
							'end_date'   => $value['end_date'],
							'week_no'    => $value['week_no'],
							'month_name' => date('F',mktime(0,0,0, $value['month_no'],10))
							);
					}
					else
					{
						$weeks[$value['month_no']]['month'] = date('F',mktime(0,0,0, $value['month_no'],10));
						$weeks[$value['month_no']]['week'][$value['week_no']]=array(
							'start_date' => $value['start_date'],
							'end_date'   => $value['end_date'],
							'week_no'    => $value['week_no'],
							'month_name' =>  date('F',mktime(0,0,0, $value['month_no'],10))
							);
					}
				}
				$data['weeks'] = $weeks;
				$data['flag'] = 2;
				$this->load->view('financialyear/get_weeks',$data);
			}
			else
			{
				$this->session->set_flashdata('error','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> Something went wrong!
									 </div>');
				redirect(SITE_URL.'get_weeks');
			}

	}

	
}