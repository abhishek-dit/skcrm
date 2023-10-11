<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Calendar extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
        $this->load->library('user_agent');
		$this->load->model("Calendar_model");
		$this->load->model("Product_model");
		$this->load->model("contact_model");
	}

	public function visit()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Visit";
		$data['nestedView']['cur_page'] = 'visit';
		$data['nestedView']['parent_page'] = 'visit';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Visit';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Visit','class'=>'active','url'=>'');
		
		# Search Functionality
        $psearch = $this->input->post('searchVisit', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'leadId' => $this->input->post('leadId', TRUE),
                'customer' => $this->input->post('customer', TRUE),
                'startDate' => $this->input->post('startDate', TRUE),
                'endDate' => $this->input->post('endDate', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'leadId' => $this->session->userdata('leadId'),
                    'customer' => $this->session->userdata('customer'),
                    'startDate' => $this->session->userdata('startDate'),
                    'endDate' => $this->session->userdata('endDate')
                );
            } else {
                $searchParams = array(
                    'leadId' => '',
                    'customer' => '',
                    'startDate' => '',
                    'endDate' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;

		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'visit/'; 
		# Total Records
	    $config['total_rows'] = $this->Calendar_model->visitTotalRows($searchParams);
		
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
	   	$data['visitSearch'] = $this->Calendar_model->visitResults($searchParams, $config['per_page'], $current_offset);
	   	$data['customer'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
	   	//print_r($data['visitSearch']);exit();
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('calendar/visitView', $data);

	}

	public function addVisit()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Visit";
		$data['nestedView']['cur_page'] = 'visit';
		$data['nestedView']['parent_page'] = 'visit';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Visit';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Visit','class'=>'active','url'=>SITE_URL.'visit');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Plan Visit','class'=>'active','url'=>'');

 		$leads = $this->Calendar_model->getLeadDetails();
 		$data['leads'] = array(''=>'Select Lead');
 		foreach ($leads as $lead) 
 		{
 			$data['leads'][$lead['lead_id']] = "Lead ID - ".$lead['lead_id']." (".$lead['CustomerName'].")";
 		}

 		$data['visitEdit'][0]['lead_id'] = $this->input->post('lead_id');
 		$data['purpose'] = array(''=>'Select Purpose') + $this->Common_model->get_dropdown('visit_purpose','purpose_id','name',[]);
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('calendar/visitView', $data);

	}

	public function editVisit($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Visit";
		$data['nestedView']['cur_page'] = 'visit';
		$data['nestedView']['parent_page'] = 'visit';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Visit';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Visit','class'=>'active','url'=>SITE_URL.'visit');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Visit','class'=>'active','url'=>'');
			
		$leads = $this->Calendar_model->getLeadDetails();
 		$data['leads'] = array(''=>'Select Lead');
 		foreach ($leads as $lead) 
 		{
 			$data['leads'][$lead['lead_id']] = $lead['CustomerName'];
 		}

		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		
		if(@icrm_decode($encoded_id)!='')
		{
			
			$value = @icrm_decode($encoded_id);
			$where = array('visit_id' => $value);
			$data['visitEdit'] = $this->Common_model->get_data('visit', $where);
			if($data['visitEdit'][0]['end_date']<date('Y-m-d'))
			{
				$data['visitEdit'][0]['is_expired'] = 1;
			}
		}
		$this->validateEditUrl(@$data['visitEdit'],'visit');
		$data['purpose'] = array(''=>'Select Purpose') + $this->Common_model->get_dropdown('visit_purpose','purpose_id','name',[]);
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('calendar/visitView', $data);
	}

	public function deleteVisit($encoded_id)
	{
		$visit_id=@icrm_decode($encoded_id);
		$where = array('visit_id' => $visit_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('visit',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Visit has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL.'visit');
	}

	public function activateVisit($encoded_id)
	{
		$visit_id=@icrm_decode($encoded_id);
		$where = array('visit_id' => $visit_id);

		$results = $this->Common_model->get_data('visit', $where);
		$data = array('visit_id' => '','lead_id'=>$results[0]['lead_id'],'start_date'=>$results[0]['start_date'],'end_date'=>$results[0]['end_date']);
		$result_check = $this->Calendar_model->checkVisitAvailability($data);
		if($result_check)
		{
			$this->session->set_flashdata('activate_error','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> Visit has already been planed!
								 </div>');
			//redirect(SITE_URL.'planVisit');
			redirect($this->agent->referrer());
		}

		$dataArr = array('status' => 1);
		$this->Common_model->update_data('visit',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Visit has been Activated successfully!
							 </div>');
		redirect(SITE_URL.'visit');

	}

	public function visitAdd()
	{
		if($this->input->post('submitVisit') != "")
		{
			//print_r($_POST);
			$visit_id = $this->input->post('visit_id');
			$dataArr = array('visit_id' => $visit_id,
					'lead_id' => $this->input->post('lead'),
					'purpose_id'=>$this->input->post('purpose'),
					'start_date' => $this->input->post('start_date'),
					'end_date' => $this->input->post('end_date'),
					'remarks1' => $this->input->post('remarks1'));
			//print_r($dataArr);exit();

			$result_check = $this->Calendar_model->checkVisitAvailability($dataArr);

			if($result_check)
			{
				$this->session->set_flashdata('error','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> Visit has already been planed!
									 </div>');
				//redirect(SITE_URL.'planVisit');
				redirect($this->agent->referrer());
			}

			//$dataArr = $_POST[];
			if($visit_id == "")
			{
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				//Insert
				$visit_id = $this->Common_model->insert_data('visit',$dataArr);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Visit has been planed successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('visit_id' => $visit_id);

				//Update
				$this->Common_model->update_data('visit',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Visit has been updated successfully!
									 </div>');
			}

			redirect(SITE_URL.'visit');
		}
	}

	public function downloadVisit()
	{
		if($this->input->post('downloadVisit')!='') {
			
			$searchParams=array( 'leadId'=>$this->input->post('leadId', TRUE),
									'customer'=>$this->input->post('customer', TRUE),
									'startDate'=>$this->input->post('startDate', TRUE),
									'endDate' => $this->input->post('endDate'), TRUE);
			$visits = $this->Calendar_model->visitDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Customer Name','Purpose','Start Date','End Date','Remarks','Modified By','Modified Time');
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
			if(count($visits)>0)
			{
				
				foreach($visits as $visit)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$visit['CustomerName'].'</td>';
					$data.='<td>'.$visit['Purpose'].'</td>';
					$data.='<td>'.$visit['start_date'].'</td>';
					$data.='<td>'.$visit['end_date'].'</td>';
					$data.='<td>'.$visit['remarks1'].'</td>';
					$data.='<td>'.getUserName($visit['modified_by']).'</td>';
					$data.='<td>'.$visit['modified_time'].'</td>';
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
			$xlFile='visit_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function viewCalendar()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "View Calendar";
		$data['nestedView']['cur_page'] = 'viewCalendar';
		$data['nestedView']['parent_page'] = 'viewCalendar';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.css"></link>';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.print.css" media="print"></link>';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'View Calendar';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'View Calendar','class'=>'active','url'=>'');
		
		# Search Results
		$user_id = $this->session->userdata('user_id');
		if($_POST)
		{
			$user_id = @$_REQUEST['reporteeUser'];
		}
		//echo $user_id; exit();
	   	$data['visitCalendarDetails'] = $this->Calendar_model->visitCalendarDetails($user_id);
	   	$data['demoCalendarDetails'] = $this->Calendar_model->demoCalendarDetails($user_id);

	   	$data['user_id'] = $user_id;
		//print_r(json_encode($data['visitSearch']));
		$data['displayList'] = 1;
		$this->load->view('calendar/calendarView', $data);

	}

	public function viewDemoCalendar()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "View Demo Calendar";
		$data['nestedView']['cur_page'] = 'viewDemoCalendar';
		$data['nestedView']['parent_page'] = 'viewDemoCalendar';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.css"></link>';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.print.css" media="print"></link>';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'View Demo Calendar';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'View Demo Calendar','class'=>'active','url'=>'');

		$data['product_id'] = $this->input->post('product');
		$data['demo_product_id'] = $this->input->post('demoProduct');

		$data['products'] =  array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name');
		$data['demoProducts'] =  array(''=>'Select Demo Product') + $this->Product_model->getDemoProduct($data['product_id']);

		$data['demoResults'] = $this->Calendar_model->demoCalendarResults($data['product_id'], $data['demo_product_id']);
		if($_POST)
		{
			$data['flag'] = '1';
		}
		$this->load->view('calendar/demoCalendarView', $data);
	}

	public function demo()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo";
		$data['nestedView']['cur_page'] = 'demo';
		$data['nestedView']['parent_page'] = 'demo';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Demo','class'=>'active','url'=>'');
		
		# Search Functionality
        $psearch = $this->input->post('searchOpportunity', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'opportunityId' => $this->input->post('opportunityId', TRUE),
                'customer' => $this->input->post('customer', TRUE),
                'startDate' => $this->input->post('startDate', TRUE),
                'endDate' => $this->input->post('endDate', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'opportunityId' => $this->session->userdata('opportunityId'),
                    'customer' => $this->session->userdata('customer'),
                    'startDate' => $this->session->userdata('startDate'),
                    'endDate' => $this->session->userdata('endDate')
                );
            } else {
                $searchParams = array(
                    'opportunityId' => '',
                    'customer' => '',
                    'startDate' => '',
                    'endDate' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;

		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'demo/'; 
		# Total Records
	    $config['total_rows'] = $this->Calendar_model->demoTotalRows($searchParams);
		
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
	   	$data['demoSearch'] = $this->Calendar_model->demoResults($searchParams,$config['per_page'], $current_offset);
	   	$data['customer'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('calendar/demoView', $data);
	}

	public function addDemo()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo";
		$data['nestedView']['cur_page'] = 'demo';
		$data['nestedView']['parent_page'] = 'demo';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.css"></link>';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.print.css" media="print"></link>';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Demo','class'=>'active','url'=>SITE_URL.'demo');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Plan Demo','class'=>'active','url'=>'');

 		$leads = $this->Calendar_model->getLeadDetails(1);
 		$data['leads'] = array(''=>'Select Lead');
 		foreach ($leads as $lead) 
 		{
 			$data['leads'][$lead['lead_id']] = "Lead ID - ".$lead['lead_id']." (".$lead['CustomerName'].")";
 		}
 		
 		$data['demoEdit'][0]['lead_id'] = $this->input->post('lead_id');
 		$data['demoEdit'][0]['opportunity_id'] = $this->input->post('opportunity_id');
 		$data['demoEdit'][0]['demo_product_id'] = $this->input->post('demo_product_id');

		$results = $this->Calendar_model->getOpportunity($data['demoEdit'][0]['lead_id']);
        $data['opportunities'] = array('Select Opportunity');
        foreach ($results as $key=>$value) 
        {
            $data['opportunities'][$key] = $value;
        }

        $results = $this->Calendar_model->getDemo($data['demoEdit'][0]['opportunity_id']);
        $data['demos'] = array(''=>'Select Demo Machine');
        foreach ($results as $key=>$value) 
        {
            $data['demos'][$key] = $value;
        }

		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('calendar/demoView', $data);
	}

	public function editDemo($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo";
		$data['nestedView']['cur_page'] = 'demo';
		$data['nestedView']['parent_page'] = 'demo';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Demo','class'=>'active','url'=>SITE_URL.'demo');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Demo','class'=>'active','url'=>'');
			
		$leads = $this->Calendar_model->getLeadDetails();
 		$data['leads'] = array(''=>'Select Lead');
 		foreach ($leads as $lead) 
 		{
 			$data['leads'][$lead['lead_id']] = $lead['CustomerName'];
 		}

		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('demo_id' => $value);
			$data['demoEdit'] = $this->Common_model->get_data('demo', $where);
			if($data['demoEdit'][0]['end_date']<date('Y-m-d'))
			{
				$data['demoEdit'][0]['is_expired'] = 1;
			}
		}
		$this->validateEditUrl(@$data['demoEdit'],'demo');
		$lead_id = $this->Common_model->get_value('opportunity', array('opportunity_id'=>$data['demoEdit'][0]['opportunity_id']),'lead_id');
		$results = $this->Calendar_model->getOpportunity($lead_id);
        $data['opportunities'] = array('Select Opportunity');
        foreach ($results as $key=>$value) 
        {
            $data['opportunities'][$key] = $value;
        }

        $results = $this->Calendar_model->getDemo($data['demoEdit'][0]['opportunity_id']);
        $data['demos'] = array(''=>'Select Demo Machine');
        foreach ($results as $key=>$value) 
        {
            $data['demos'][$key] = $value;
        }

        $data['demoEdit'][0]['lead_id'] = $lead_id;
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('calendar/demoView', $data);
	}

	public function demoAdd()
	{
		if($this->input->post('submitDemo') != "")
		{
			$demo_id = $this->input->post('demo_id');
			$opportunity_id = $this->input->post('opportunity');
			$product_id = $this->Common_model->get_value('opportunity_product', array('opportunity_id'=>$opportunity_id), 'product_id');
			$dataArr = array('demo_id' => $demo_id,
					'opportunity_id' => $opportunity_id,
					'product_id' => $product_id,
					'demo_product_id' => $this->input->post('demo'),
					'start_date' => $this->input->post('start_date'),
					'end_date' => $this->input->post('end_date'),
					'remarks1' => $this->input->post('remarks1'));
			//print_r($dataArr);exit();

			$result_check = $this->Calendar_model->checkDemoAvailability($dataArr);

			if($result_check)
			{
				$this->session->set_flashdata('error','<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> Demo has already been booked!
									 </div>');
				//redirect(SITE_URL.'planDemo');
				redirect($this->agent->referrer());
			}

			//$dataArr = $_POST[];
			if($demo_id == "")
			{
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				//Insert
				$demo_id = $this->Common_model->insert_data('demo',$dataArr);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Demo has been booked successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('demo_id' => $demo_id);

				//Update
				$this->Common_model->update_data('demo',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Demo has been updated successfully!
									 </div>');
			}

			redirect(SITE_URL.'demo');
		}
	}

	public function deleteDemo($encoded_id)
	{
		$demo_id=@icrm_decode($encoded_id);
		$where = array('demo_id' => $demo_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('demo',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Demo has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL.'demo');
	}

	public function activateDemo($encoded_id)
	{
		$demo_id=@icrm_decode($encoded_id);
		$where = array('demo_id' => $demo_id);

		$results = $this->Common_model->get_data('demo', $where);
		$data = array('demo_id' => '','demo_product_id'=>$results[0]['demo_product_id'],'start_date'=>$results[0]['start_date'],'end_date'=>$results[0]['end_date']);
		$result_check = $this->Calendar_model->checkDemoAvailability($data);
		if($result_check)
		{
			$this->session->set_flashdata('activate_error','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> Demo has already been booked!
								 </div>');
			//redirect(SITE_URL.'planDemo');
			redirect($this->agent->referrer());
		}

		$dataArr = array('status' => 1);
		$this->Common_model->update_data('demo',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Demo has been Activated successfully!
							 </div>');
		redirect(SITE_URL.'demo');

	}

	public function downloadDemo()
	{
		if($this->input->post('downloadDemo')!='') {
			$searchParams=array( 'opportunityId'=>$this->input->post('opportunityId', TRUE),
									'customer'=>$this->input->post('customer', TRUE),
									'startDate'=>$this->input->post('startDate', TRUE),
									'endDate' => $this->input->post('endDate'), TRUE);
			
			$demos = $this->Calendar_model->demoDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Customer Name','Opportunity','Demo','Start Date','End Date','Remarks');
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
			if(count($demos)>0)
			{
				
				foreach($demos as $demo)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$demo['CustomerName'].'</td>';
					$data.='<td>'.$demo['opportunity'].'</td>';
					$data.='<td>'.$demo['demo'].'</td>';
					$data.='<td>'.$demo['start_date'].'</td>';
					$data.='<td>'.$demo['end_date'].'</td>';
					$data.='<td>'.$demo['remarks1'].'</td>';
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
			$xlFile='demo_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function demoDetails()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo Details";
		$data['nestedView']['cur_page'] = 'demoDetails';
		$data['nestedView']['parent_page'] = 'demoDetails';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.css"></link>';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.fullcalendar/fullcalendar/fullcalendar.print.css" media="print"></link>';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo Details';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Demo Details','class'=>'active','url'=>'');		
		# Search Functionality
		$psearch=$this->input->post('searchDemoProduct', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'location'=>$this->input->post('location', TRUE),
					  'serialNumber'=>$this->input->post('serialNumber', TRUE),
					  'branch'=>$this->input->post('branch', TRUE),
					  'city'=>$this->input->post('city',TRUE)
					  		);
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'location'=>$this->session->userdata('location'),
					  'serialNumber'=>$this->session->userdata('serialNumber'),
					  'branch'=>$this->session->userdata('branch'),
					  'city'=>$this->session->userdata('city')
							  );
			}
			else {
				$searchParams=array(
					  'location'=>'',
					  'serialNumber'=>'',
					  'branch'=>'',
					  'city'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		//print_r($data['searchParams']);die();
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'demoDetails/'; 
		# Total Records
	    $config['total_rows'] = $this->Calendar_model->demoProductTotalRows($searchParams);
		
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
	   	$data['demoProductSearch'] = $this->Calendar_model->demoProductResults($searchParams,$config['per_page'], $current_offset);
		$data['displayList'] = 1;
		# Load page with all shop details
		$this->load->view('calendar/demoDetailsView', $data);
	}

	public function downloadDemoDetails()
	{
		if($this->input->post('downloadDemoProduct')!='') {
			
			$searchParams=array('location'=>$this->input->post('location', TRUE),
									'serialNumber'=>$this->input->post('serialNumber', TRUE),
									'branch'=>$this->input->post('branch', TRUE));
			$demoProducts = $this->Calendar_model->demoProductDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Product Category','Material Group', 'Group Description', 'Product', 'Product Description', 
					'RRP (Rs)','Serial Number', 'City', 'Location Name','Region', 'Sales Branch Office', 'Responsible Person (RBH of Region)', 'Created Time','Modified By','Modified Time');
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
			if(count($demoProducts)>0)
			{
				
				foreach($demoProducts as $demoProduct)
				{
					$data.='<tr>';
					$data.='<td valign="top" align="center">'.$j.'</td>';
					$data.='<td valign="top">'.$demoProduct['CategoryName'].'</td>';
					$data.='<td valign="top">'.$demoProduct['GroupName'].'</td>';
					$data.='<td valign="top">'.$demoProduct['GroupDescription'].'</td>';
					$data.='<td valign="top">'.$demoProduct['ProductName'].'</td>';
					$data.='<td valign="top">'.$demoProduct['ProductDescription'].'</td>';
					$data.='<td valign="top">'.$demoProduct['rrp'].'</td>';
					$data.='<td valign="top">'.$demoProduct['serial_number'].'</td>';
					$data.='<td valign="top">'.$demoProduct['city'].'</td>';
					$data.='<td valign="top">'.$demoProduct['location'].'</td>';
					$data.='<td valign="top">'.$demoProduct['region'].'</td>';
					$data.='<td valign="top">'.$demoProduct['branch'].'</td>';
					$data.='<td valign="top">'.getRBHforRegion($demoProduct['region_id']).'</td>';
					$data.='<td valign="top">'.DateFormatAM($demoProduct['created_time']).'</td>';
					$data.='<td valign="top">'.getUserName($demoProduct['modified_by']).'</td>';
					$data.='<td valign="top">'.DateFormatAM($demoProduct['modified_time']).'</td>';
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
			$xlFile='demodetails_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function downloadDemoCalendarDetails($encoded_id)
	{
		$demos = $this->Calendar_model->getDemoCalendarDetails(@icrm_decode($encoded_id));
		
		$header = '';
		$data ='';
		$titles = array('S.NO','Booked By','Customer Name','Start Date','End Date');
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
		if(count($demos)>0)
		{
			
			foreach($demos as $demo)
			{
				$data.='<tr>';
				$data.='<td align="center" valign="top">'.$j.'</td>';
				$data.='<td valign="top">'.$demo['booked_by'].'</td>';
				$data.='<td valign="top">'.$demo['customer'].'</td>';
				$data.='<td valign="top">'.$demo['start'].'</td>';
				$data.='<td valign="top">'.$demo['end'].'</td>';
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
		$xlFile='democalendardetails_'.$time.'.xls'; 
		header("Content-type: application/x-msdownload"); 
		# replace excelfile.xls with whatever you want the filename to default to
		header("Content-Disposition: attachment; filename=".$xlFile."");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $data;
	}

	public function getOpportunity()
	{
		$lead_id = @$_REQUEST['lead_id'];
        $results = $this->Calendar_model->getOpportunity($lead_id);
        $opportunities='<option value="">Select Opportunity</option>';
        foreach ($results as $key=>$value) 
        {
            $opportunities.='<option value="' . $key . '">' . $value . '</option>';
        }
       	echo $opportunities;
	}

	public function getDemo()
	{
		$opportunity_id = @$_REQUEST['opportunity_id'];
        $results = $this->Calendar_model->getDemo($opportunity_id);
        $demo = '<option value="">Select Demo Machine</option>';
        foreach ($results as $key=>$value) 
        {
            $demo.='<option value="' . $key . '">' . $value . '</option>';
        }
       	echo $demo;
	}

	public function getDemoCalendar()
	{
		$demo_product_id = @$_REQUEST['demo_product_id'];
		$data = $this->Calendar_model->getDemoCalendar($demo_product_id);
		echo json_encode($data);
	}

	//mahesh 14th july 2016 5:46 PM
	public function update_visitFeedback(){

		if($this->input->post('visitUpdate_submit')!=''){

			$encoded_id = $this->input->post('encoded_id');
			$visit_id = icrm_decode($encoded_id);
			$where = array('visit_id'=>$visit_id);
			$data = array('remarks2' 	  => $this->input->post('remarks2'),
						  'modified_by'   => $this->session->userdata('user_id'),
						  'modified_time' => date('Y-m-d H:i:s'));
			//update visit feedback
			$this->Common_model->update_data('visit',$data,$where);

			$this->session->set_flashdata('activate_error','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Visit Feedback has been updated successfully!
								 </div>');
			//redirect(SITE_URL.'planDemo');
			redirect(SITE_URL.'visit');			
		}
	}

	//mahesh 14th july 2016 06:56 PM
	public function update_demoFeedback(){

		if($this->input->post('demoUpdate_submit')!=''){

			$encoded_id = $this->input->post('encoded_id');
			$demo_id = icrm_decode($encoded_id);
			$where = array('demo_id'=>$demo_id);
			$data = array('remarks2' 	  => $this->input->post('remarks2'),
						  'modified_by'   => $this->session->userdata('user_id'),
						  'modified_time' => date('Y-m-d H:i:s'));
			//update visit feedback
			$this->Common_model->update_data('demo',$data,$where);

			$this->session->set_flashdata('activate_error','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Demo Feedback has been updated successfully!
								 </div>');
			//redirect(SITE_URL.'planDemo');
			redirect(SITE_URL.'demo');			
		}
	}
}