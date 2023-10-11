<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Calendar extends Base_controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('user_agent');
		$this->load->model("Calendar_model");
		$this->load->model("Product_model");
		$this->load->model("contact_model");
		$this->load->model("quote_model");
	}
	/**
	 * Fetching Visit List
	 * params: $company_id(int)
	 * return: $visit Details(array)
	 **/
	public function visit()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Visit";
		$data['nestedView']['cur_page'] = 'visit';
		$data['nestedView']['parent_page'] = 'visit';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['css_includes'] = array();

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Visit';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Visit', 'class' => 'active', 'url' => '');

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
		$config['base_url'] = SITE_URL . 'visit/';
		# Total Records
		$config['total_rows'] = $this->Calendar_model->visitTotalRows($searchParams);

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

		# Search Results
		$data['visitSearch'] = $this->Calendar_model->visitResults($searchParams, $config['per_page'], $current_offset);
		$data['customer'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		$data['displayList'] = 1;
		$this->load->view('calendar/visitView', $data);
	}
	/**
	 * Adding New Visit Data
	 * return: Lead Details
	 **/
	public function addVisit()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Visit";
		$data['nestedView']['cur_page'] = 'visit';
		$data['nestedView']['parent_page'] = 'visit';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['css_includes'] = array();

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Visit';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Visit', 'class' => 'active', 'url' => SITE_URL . 'visit');
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Plan Visit', 'class' => 'active', 'url' => '');
		#Fetching Lead Details
		$leads = $this->Calendar_model->getLeadDetails();
		$data['leads'] = array('' => 'Select Lead');
		foreach ($leads as $lead) {
			$data['leads'][$lead['lead_id']] = "Lead ID - " . $lead['lead_number'] . " (" . $lead['CustomerName'] . ")";
		}

		$data['visitEdit'][0]['lead_id'] = $this->input->post('lead_id');
		$data['purpose'] = array('' => 'Select Purpose') + $this->Common_model->get_dropdown('visit_purpose', 'purpose_id', 'name', []);
		//$data['dealerList'] = array(''=>'Select Dealer') + $this->Common_model->get_dropdown('distributor_details','user_id','distributor_name',[]);
		$data['dealerList'] = $this->Common_model->get_data('distributor_details', array('1' => 1));
		//print_r($data['dealerList']);die;
		// $user_id=$this->session->userdata('user_id');
		// $userLocation = $this->Common_model->get_data('user_location',array('user_id'=> $user_id));
		// echo '<pre>'; print_r($user_id);die;

		// $data['dealerList'] = $this->Calendar_model->getDealerByLocation($userLocation);
		// echo $locations;die;
		// echo '<pre>'; print_r($data['dealerList']);die;
		$data['dealerList'] = $this->quote_model->getDistributors();
		$data['customer'] = array('' => 'Select Customer') + $this->Common_model->get_dropdown('customer', 'customer_id', 'name', []);
		$data['flg'] = 1;
		$data['val'] = 0;
		//print_r($data);die;
		# Load page with all visit details
		$this->load->view('calendar/visitView', $data);
	}
	/**
	 * Editing Visit Details
	 * params: $visit_id(int)
	 * return: $visitDetails(array)
	 **/
	public function editVisit($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Visit";
		$data['nestedView']['cur_page'] = 'visit';
		$data['nestedView']['parent_page'] = 'visit';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['css_includes'] = array();

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Visit';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Visit', 'class' => 'active', 'url' => SITE_URL . 'visit');
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Visit', 'class' => 'active', 'url' => '');
		#Fetching Lead Details	
		$leads = $this->Calendar_model->getLeadDetails();
		$data['leads'] = array('' => 'Select Lead');
		foreach ($leads as $lead) {
			$data['leads'][$lead['lead_id']] = $lead['CustomerName'];
		}
		if (@icrm_decode($encoded_id) != '') {

			$value = @icrm_decode($encoded_id);
			$where = array('visit_id' => $value);
			$data['visitEdit'] = $this->Common_model->get_data('visit', $where);
			if ($data['visitEdit'][0]['end_date'] < date('Y-m-d')) {
				$data['visitEdit'][0]['is_expired'] = 1;
			}
		}
		$this->validateEditUrl(@$data['visitEdit'], 'visit');
		#Fetching Purpose List
		// $user_id=$this->session->userdata('user_id');
		// $userLocation = $this->Common_model->get_data('user_location',array('user_id'=> $user_id));
		// // echo '<pre>'; print_r($user_id);die;

		// $data['dealerList'] = $this->Calendar_model->getDealerByLocation($userLocation);

		$data['dealerList'] = $this->quote_model->getDistributors();
		$data['purpose'] = array('' => 'Select Purpose') + $this->Common_model->get_dropdown('visit_purpose', 'purpose_id', 'name', []);
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all visit details
		$this->load->view('calendar/visitView', $data);
	}
	#Deactivating Visit Request
	public function deleteVisit($encoded_id)
	{
		$visit_id = @icrm_decode($encoded_id);
		$where = array('visit_id' => $visit_id);
		$dataArr = array('status' => 5);
		$this->Common_model->update_data('visit', $dataArr, $where);

		$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Visit has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL . 'visit');
	}
	#Activating Visit Request
	public function activateVisit($encoded_id)
	{
		$visit_id = @icrm_decode($encoded_id);
		$where = array('visit_id' => $visit_id);

		$results = $this->Common_model->get_data('visit', $where);
		$data = array('visit_id' => '', 'lead_id' => $results[0]['lead_id'], 'start_date' => $results[0]['start_date'], 'end_date' => $results[0]['end_date']);
		$result_check = $this->Calendar_model->checkVisitAvailability($data);
		if ($result_check) {
			$this->session->set_flashdata('activate_error', '<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> Visit has already been planed!
								 </div>');
			redirect($this->agent->referrer());
		}

		$dataArr = array('status' => 1);
		$this->Common_model->update_data('visit', $dataArr, $where);

		$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Visit has been Activated successfully!
							 </div>');
		redirect(SITE_URL . 'visit');
	}

	/**
	 * Creating New Visit Request
	 * params: Visit Data
	 * return: $visit_id
	 **/
	public function visitAdd()
	{
		if ($this->input->post('submitVisit') != "") {
			$visit_id = $this->input->post('visit_id');
			$lead_id = $this->input->post('lead');
			$customer_id = $this->input->post('customer');
			$dealer_id = $this->input->post('dealer');
			if ($lead_id == "") {
				// $lead_id = "8264";
				$lead_id = "";
			} else {
				$lead_id;
			}
			if ($customer_id == "") {
				// $customer_id = "24891";
				$customer_id = "";
			} else {
				$customer_id;
			}
			if ($dealer_id == "") {
				// $dealer_id = "641";
				$dealer_id = "";
			} else {
				$dealer_id;
			}
			//echo $lead_id;die;
			$dataArr = array(
				'visit_id' => $visit_id,
				'lead_id' => $lead_id,
				'customer_id' => $customer_id,
				'dealer_id' => $dealer_id,
				'city' => $this->input->post('city'),
				'purpose_id' => $this->input->post('purpose'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'remarks1' => $this->input->post('remarks1')
			);
			//print_r($dataArr);die;
			$currDate1 = explode(' ', date("Y-m-d h:i"));
			$currDate = $currDate1[0];
			$currTime1 = $currDate1[1];
			$currTime = date("H:i", strtotime($currTime1));
			$startDate1 = explode('  ', $this->input->post('start_date'));
			$startDate = $startDate1[0];
			$startTime1 = $startDate1[1];
			$startTime = date("H:i", strtotime($startTime1));

			$endDate1 = explode('  ', $this->input->post('end_date'));
			$endDate = $endDate1[0];
			$endTime1 = $endDate1[1];
			$endTime = date("H:i", strtotime($endTime1));
			// echo "<pre>";print_r($currTime);
			// echo "<pre>";print_r($startTime);die;

			if ($endDate < $startDate) {
				// echo '<script>alert("Start Date is less than current date")</script>';
				$this->session->set_flashdata('error', '<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong>Start Date is less than End date!
									 </div>');
				redirect($this->agent->referrer());
				// Start date is in front of end date!
			} else {
				if ($endTime < $startTime) {
					// echo '<script>alert("Start Time is less than current time")</script>';
					$this->session->set_flashdata('error', '<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong>Start Time is less than current time!
									 </div>');
					redirect($this->agent->referrer());
				} else {
					#checking the visit availability
					$result_check = $this->Calendar_model->checkVisitAvailability($dataArr);

					if ($result_check) {
						$this->session->set_flashdata('error', '<div class="alert alert-danger alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> Visit has already been planed!
								</div>');
						redirect($this->agent->referrer());
					}
					#Insert
					if ($visit_id == "") {
						$dataArr['created_by'] = $this->session->userdata('user_id');
						$dataArr['created_time'] = date('Y-m-d H:i:s');
						//Insert
						//print_r($dataArr);die;
						$visit_id = $this->Common_model->insert_data('visit', $dataArr);

						$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Visit has been planed successfully!
								</div>');
					} else {
						$dataArr['modified_by'] = $this->session->userdata('user_id');
						$dataArr['modified_time'] = date('Y-m-d H:i:s');
						$where = array('visit_id' => $visit_id);
						// $vplan = $this->Common_model->get_data('visit', $where);
						// if($vplan[0]['start_date'] != $dataArr['start_date']){
						// 				$dataArr['status'] = 3;
						// }

						//Update
						$this->Common_model->update_data('visit', $dataArr, $where);

						$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Visit has been updated successfully!
									 </div>');
					}

					redirect(SITE_URL . 'visit');
				}
			}
		}
		//     #checking the visit availability
		// 	$result_check = $this->Calendar_model->checkVisitAvailability($dataArr);

		// 	if($result_check)
		// 	{
		// 		$this->session->set_flashdata('error','<div class="alert alert-danger alert-white rounded">
		// 								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		// 								<div class="icon"><i class="fa fa-check"></i></div>
		// 								<strong>Error!</strong> Visit has already been planed!
		// 							 </div>');
		// 		redirect($this->agent->referrer());
		// 	}
		//     #Insert
		// 	if($visit_id == "")
		// 	{
		// 		$dataArr['created_by'] = $this->session->userdata('user_id');
		// 		$dataArr['created_time'] = date('Y-m-d H:i:s');
		// 		//Insert
		// 		//print_r($dataArr);die;
		// 		$visit_id = $this->Common_model->insert_data('visit',$dataArr);

		// 		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
		// 								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		// 								<div class="icon"><i class="fa fa-check"></i></div>
		// 								<strong>Success!</strong> Visit has been planed successfully!
		// 							 </div>');
		// 	}
		// 	else
		// 	{	
		// 		$dataArr['modified_by'] = $this->session->userdata('user_id');
		// 		$dataArr['modified_time'] = date('Y-m-d H:i:s');
		// 		$where = array('visit_id' => $visit_id);
		// 		// $vplan = $this->Common_model->get_data('visit', $where);
		// 		// if($vplan[0]['start_date'] != $dataArr['start_date']){
		// 		// 				$dataArr['status'] = 3;
		// 		// }

		// 		//Update
		// 		$this->Common_model->update_data('visit',$dataArr, $where);

		// 		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
		// 								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		// 								<div class="icon"><i class="fa fa-check"></i></div>
		// 								<strong>Success!</strong> Visit has been updated successfully!
		// 							 </div>');
		// 	}

		// 	redirect(SITE_URL.'visit');
		// }
	}
	# Downloading Visit List
	public function downloadVisit()
	{
		if ($this->input->post('downloadVisit') != '') {

			$searchParams = array(
				'leadId' => $this->input->post('leadId', TRUE),
				'customer' => $this->input->post('customer', TRUE),
				'startDate' => $this->input->post('startDate', TRUE),
				'endDate' => $this->input->post('endDate'), TRUE
			);
			$visits = $this->Calendar_model->visitDetails($searchParams);

			$header = '';
			$data = '';
			$titles = array('S.NO', 'Customer Name', 'Purpose', 'Start Date', 'End Date', 'Remarks', 'Modified By', 'Modified Time');
			$data = '<table border="1">';
			$data .= '<thead>';
			$data .= '<tr>';
			foreach ($titles as $title) {
				$data .= '<th>' . $title . '</th>';
			}
			$data .= '</tr>';
			$data .= '</thead>';
			$data .= '<tbody>';
			$j = 1;
			if (count($visits) > 0) {

				foreach ($visits as $visit) {
					if (!empty($visit['LeadNumber'])) {
						$CustomerName = $visit['LeadNumber'];
					} elseif (!empty($visit['CName'])) {
						$CustomerName = $visit['CName'];
					} elseif (!empty($visit['DistName'])) {
						$CustomerName = $visit['DistName'];
					} elseif (!empty($visit['City'])) {
						$CustomerName = $visit['City'];
					} else {
						$CustomerName = '';
					}

					$data .= '<tr>';
					$data .= '<td align="center">' . $j . '</td>';
					$data .= '<td>' . $CustomerName . '</td>';
					$data .= '<td>' . $visit['Purpose'] . '</td>';
					$data .= '<td>' . $visit['start_date'] . '</td>';
					$data .= '<td>' . $visit['end_date'] . '</td>';
					$data .= '<td>' . $visit['remarks1'] . '</td>';
					$data .= '<td>' . getUserName($visit['modified_by']) . '</td>';
					$data .= '<td>' . $visit['modified_time'] . '</td>';
					$data .= '</tr>';
					$j++;
				}
			} else {
				$data .= '<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
			}
			$data .= '</tbody>';
			$data .= '</table>';
			$time = date("Ymdhis");
			$xlFile = 'visit_' . $time . '.xls';
			header("Content-type: application/x-msdownload");
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=" . $xlFile . "");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
		}
	}
	/**
	 * Fetching plan and demo details
	 * return: $Demo and $visit
	 **/
	public function viewCalendar()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "View Calendar";
		$data['nestedView']['cur_page'] = 'viewCalendar';
		$data['nestedView']['parent_page'] = 'viewCalendar';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/moment.min.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.css"></link>';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.print.css" media="print"></link>';

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'View Calendar';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'View Calendar', 'class' => 'active', 'url' => '');

		# Search Results
		$user_id = $this->session->userdata('user_id');
		if ($_POST) {
			$user_id = @$_REQUEST['reporteeUser'];
		}
		#VisitDetails
		$data['visitCalendarDetails'] = $this->Calendar_model->visitCalendarDetails($user_id);
		#DemoDetails
		$data['demoCalendarDetails'] = $this->Calendar_model->demoCalendarDetails($user_id);
		$data['user_id'] = $user_id;
		$data['displayList'] = 1;
		$this->load->view('calendar/calendarView', $data);
	}
	#View Demo Details
	public function viewDemoCalendar()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "View Demo Calendar";
		$data['nestedView']['cur_page'] = 'viewDemoCalendar';
		$data['nestedView']['parent_page'] = 'viewDemoCalendar';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.css"></link>';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.print.css" media="print"></link>';

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'View Demo Calendar';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'View Demo Calendar', 'class' => 'active', 'url' => '');

		$data['product_id'] = $this->input->post('product');
		$data['demo_product_id'] = $this->input->post('demoProduct');

		$data['products'] =  array('' => 'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', array('company_id' => $this->session->userdata('company')));
		$data['demoProducts'] =  array('' => 'Select Demo Product') + $this->Product_model->getDemoProduct($data['product_id']);

		$data['demoResults'] = $this->Calendar_model->demoCalendarResults($data['product_id'], $data['demo_product_id']);
		if ($_POST) {
			$data['flag'] = '1';
		}
		$this->load->view('calendar/demoCalendarView', $data);
	}
	/**
	 * Fetching Demo List
	 * params: $Search Filters(int)
	 * return: Demo Results
	 **/
	public function demo()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo";
		$data['nestedView']['cur_page'] = 'demo';
		$data['nestedView']['parent_page'] = 'demo';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['css_includes'] = array();

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Demo', 'class' => 'active', 'url' => '');

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
		$config['base_url'] = SITE_URL . 'demo/';
		# Total Records
		$config['total_rows'] = $this->Calendar_model->demoTotalRows($searchParams);

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

		# Search Results
		$data['demoSearch'] = $this->Calendar_model->demoResults($searchParams, $config['per_page'], $current_offset);
		$data['customer'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('calendar/demoView', $data);
	}
	/**
	 * Creating New Demo
	 * return: lead(array) and opportunity results
	 **/
	public function addDemo()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo";
		$data['nestedView']['cur_page'] = 'demo';
		$data['nestedView']['parent_page'] = 'demo';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.css"></link>';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.print.css" media="print"></link>';

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Demo', 'class' => 'active', 'url' => SITE_URL . 'demo');
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Plan Demo', 'class' => 'active', 'url' => '');
		#Fetch Lead Details
		$leads = $this->Calendar_model->getLeadDetails(1);
		$data['leads'] = array('' => 'Select Lead');
		foreach ($leads as $lead) {
			$data['leads'][$lead['lead_id']] = "Lead ID - " . $lead['lead_number'] . " (" . $lead['CustomerName'] . ")";
		}

		$data['demoEdit'][0]['lead_id'] = $this->input->post('lead_id');
		$data['demoEdit'][0]['opportunity_id'] = $this->input->post('opportunity_id');
		$data['demoEdit'][0]['demo_product_id'] = $this->input->post('demo_product_id');
		#Fetch Opportunity Details
		$results = $this->Calendar_model->getOpportunity($data['demoEdit'][0]['lead_id']);

		$data['opportunities'] = array('' => 'Select Opportunity');
		foreach ($results as $key => $value) {
			$data['opportunities'][$key] = $value;
		}

		$results = $this->Calendar_model->getDemo($data['demoEdit'][0]['opportunity_id']);
		$data['demos'] = array('' => 'Select Demo Machine');
		foreach ($results as $key => $value) {
			$data['demos'][$key] = $value;
		}
		// get regions details
		$data['regions'] = $this->Common_model->get_data('location', array('parent_id' => 3));
		
		// get user region 
		$data['user_detail'] = $this->Common_model->get_data_row('user', array('user_id' => $this->session->userdata('user_id')));
		$data['user_branch'] = $this->Common_model->get_data_row('branch', array('branch_id' => $data['user_detail']['branch_id']));
		$region = $this->Common_model->get_data_row('location', array('location_id' => $data['user_branch']['region_id']));
		$data['user_region'] = $region['location'];
		$data['flg'] = 1;
		$data['val'] = 0;
		$data['demoEdit'][0]['product_category_id'] = 0;
		$data['demoEdit'][0]['nature_of_demo'] = '';
		$data['demoEdit'][0]['region'] = '';
		$data['demoEdit'][0]['unit_details_with_specific_model'] = '';
		$data['demoEdit'][0]['no_interactions_end_users'] = '';
		$data['demoEdit'][0]['competition_info_configuration'] = '';
		$data['demoEdit'][0]['name_of_units_demonstrated'] = '';
		$data['demoEdit'][0]['contact_detail'] = '';
		$data['demoEdit'][0]['unit_details'] = '';
		// $data['demoEdit'][0]['planned_timeline'] = '';
		$data['demoEdit'][0]['requesting_employee_name'] = '';
		$data['demoEdit'][0]['name_of_institute'] = '';
		$data['demoEdit'][0]['name_of_contact_institute'] = '';
		$data['demoEdit'][0]['key_decision_makers'] = '';
		$data['demoEdit'][0]['existing_unit_details'] = '';
		$data['demoEdit'][0]['file_path'] = '';
		$data['demoEdit'][0]['letter_file_path'] = '';
		$data['demoEdit'][0]['units_for_display'] = '';
		$data['demoEdit'][0]['event_details'] = '';
		$data['demoEdit'][0]['installed_by'] = '';
		$data['demoEdit'][0]['name_units_installed'] = '';
		// $data['demoEdit'][0]['timeline_post_sale_demo'] = '';
		$data['demoEdit'][0]['serial_number'] = '';
		$data['demoEdit'][0]['customer_complaint_future_prospect'] = '';
		$data['demoEdit'][0]['customer_complaint_future_prospect_details'] = '';

		// echo"<pre>";print_r($_SESSION);exit;
		# Load page with all Demo details
		$this->load->view('calendar/demoView', $data);
	}
	#Edit Demo
	public function editDemo($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo";
		$data['nestedView']['cur_page'] = 'demo';
		$data['nestedView']['parent_page'] = 'demo';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['css_includes'] = array();

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Demo', 'class' => 'active', 'url' => SITE_URL . 'demo');
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Demo', 'class' => 'active', 'url' => '');

		// $leads = $this->Calendar_model->getLeadDetails();
		// $data['leads'] = array(''=>'Select Lead');
		// foreach ($leads as $lead) 
		// {
		// 	$data['leads'][$lead['lead_id']] = $lead['CustomerName'];
		// }
		#Fetch Lead Details
		$leads = $this->Calendar_model->getLeadDetails(1);
		$data['leads'] = array('' => 'Select Lead');
		foreach ($leads as $lead) {
			$data['leads'][$lead['lead_id']] = "Lead ID - " . $lead['lead_number'] . " (" . $lead['CustomerName'] . ")";
		}

		if (@icrm_decode($encoded_id) != '') {
			$value = @icrm_decode($encoded_id);
			$where = array('demo_id' => $value);
			$data['demoEdit'] = $this->Common_model->get_data('demo', $where);
			if ($data['demoEdit'][0]['end_date'] < date('Y-m-d')) {
				$data['demoEdit'][0]['is_expired'] = 1;
			}
		}
		$this->validateEditUrl(@$data['demoEdit'], 'demo');
		$lead_id = $this->Common_model->get_value('opportunity', array('opportunity_id' => $data['demoEdit'][0]['opportunity_id']), 'lead_id');

		$results = $this->Calendar_model->getOpportunity($lead_id);
		$data['opportunities'] = array('' => 'Select Opportunity');
		foreach ($results as $key => $value) {
			$data['opportunities'][$key] = $value;
		}
		$data['customer'] = $this->Calendar_model->get_lead_customer($lead_id);
		$results = $this->Calendar_model->getDemo($data['demoEdit'][0]['opportunity_id']);
		$data['demos'] = array('' => 'Select Demo Machine');
		foreach ($results as $key => $value) {
			$data['demos'][$key] = $value;
		}
		// get regions details
		$data['regions'] = $this->Common_model->get_data('location', array('parent_id' => 3));
		// get user region 
		$data['user_detail'] = $this->Common_model->get_data_row('user', array('user_id' => $this->session->userdata('user_id')));
		$data['user_branch'] = $this->Common_model->get_data_row('branch', array('branch_id' => $data['user_detail']['branch_id']));
		$region = $this->Common_model->get_data_row('location', array('location_id' => $data['user_branch']['region_id']));
		$data['user_region'] = $region['location'];

		$data['demoEdit'][0]['lead_id'] = $lead_id;
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all Demo details
		$this->load->view('calendar/demoView', $data);
	}

	/**
	 * Inserting Demo Details
	 * params: Demo Columns
	 * return: demo list
	 **/
	public function demoAdd()
	{

		if ($this->input->post('submitDemo') != "") {
			$demo_id = $this->input->post('demo_id');
			$opportunity_id = $this->input->post('opportunity');
			$product_category_value = $this->input->post('product_category');
			$product_id = $this->Common_model->get_value('opportunity_product', array('opportunity_id' => $opportunity_id), 'product_id');
			$product_category_id = $this->Common_model->get_value('product_category', array('name' => $product_category_value), 'category_id');
			$dataArr = array(
				'demo_id' => $demo_id,
				// 'product_id' => $product_id,
				'product_category_id' => $this->input->post('product_category_id'),
				'requesting_employee_name' => $this->input->post('requesting_employee_name'),
				// 'name_of_institute' => $this->input->post('name_of_institute'),
				// 'name_of_contact_institute' => $this->input->post('name_of_contact_institute'),
				// 'contact_detail' => $this->input->post('contact_detail'),
				// 'key_decision_makers' => $this->input->post('key_decision_makers'),
				// 'existing_unit_details' => $this->input->post('existing_unit_details'),
				// 'region' => $this->input->post('region'),
				'nature_of_demo' => $this->input->post('nature_of_demo'),
				'remarks1' => $this->input->post('remarks1')
				// 'unit_details' => $this->input->post('unit_details')
			);

			if ($this->input->post('nature_of_demo') != '') {

				if ($this->input->post('nature_of_demo') == 'pre_sale_priority') {
					$dataArr['lead_id'] = $this->input->post('lead_presale_priority');
					$dataArr['opportunity_id'] = $this->input->post('opportunity_presale_priority');
					$dataArr['product_id'] = $this->Common_model->get_value('opportunity_product', array('opportunity_id' => $this->input->post('opportunity_presale_priority')), 'product_id');
					$dataArr['name_of_units_demonstrated'] = $this->input->post('name_of_units_demonstrated_presale_priority');
					$dataArr['demo_machine'] = $this->input->post('demo_presale_priority');
					$dataArr['demo_product_id'] = $this->input->post('demo_presale_priority');
					$dataArr['start_date'] = $this->input->post('start_date_presale_priority');
					$dataArr['end_date'] = $this->input->post('end_date_presale_priority');
					$dataArr['unit_details_with_specific_model'] = $this->input->post('unit_details_with_specific_model_presale_priority');
					$dataArr['competition_info_configuration'] = $this->input->post('competition_info_configuration_presale_priority');
					$dataArr['no_interactions_end_users'] = $this->input->post('no_interactions_end_users_presale_priority');
					// $dataArr['planned_timeline'] = $this->input->post('planned_timeline_presale_priority');
					$dataArr['name_of_institute'] = $this->input->post('name_of_institute_presale_priority');
					$dataArr['contact_detail'] = $this->input->post('contact_detail_presale_priority');
					$dataArr['name_of_contact_institute'] = $this->input->post('address_presale_priority');
					$dataArr['key_decision_makers'] = $this->input->post('key_decision_makers_presale_priority');

					//file upload
					if (count($_FILES['preSalePriorityFile']['name']) > 0) {
						$config['upload_path']   = "./uploads/demo_image/";
						$config['allowed_types'] = 'jpg|pdf';
						$config['max_size']      = 2000000;
						$config['overwrite'] = true;
						$this->load->library('upload');

						$i = 0;
						foreach ($_FILES['preSalePriorityFile'] as $key => $value) {
							if (!empty($_FILES['preSalePriorityFile']['name'][$i])) {
								$image_parts = pathinfo($_FILES['preSalePriorityFile']['name'][$i]);
								$image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
								$image_type = $image_parts['extension'];
								$filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

								$_FILES['userfile_presale_priority']['name'] = $filename;
								$_FILES['userfile_presale_priority']['type'] = $_FILES['preSalePriorityFile']['type'][$i];
								$_FILES['userfile_presale_priority']['tmp_name'] = $_FILES['preSalePriorityFile']['tmp_name'][$i];
								$_FILES['userfile_presale_priority']['error'] = $_FILES['preSalePriorityFile']['error'][$i];
								$_FILES['userfile_presale_priority']['size'] = $_FILES['preSalePriorityFile']['size'][$i];
								$config['file_name']   = $_FILES['userfile_presale_priority']['name'];

								$this->upload->initialize($config);
								$this->upload->do_upload('userfile_presale_priority');
								// if (!$this->upload->do_upload('userfile_presale_priority'))
                                // {
                                //  $error = array('error' => $this->upload->display_errors());
								//  echo"<pre>";print_r($_FILES);
								//  echo"<pre>";print_r($error);
                                //  exit;
                                //   }


								//file name and file path
								$filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
								$filename1[] = $filename;

								$dataArr['file_path'] = json_encode($filepath);
								$dataArr['file_name'] = json_encode($filename1);
							}
							$i++;
						}
					}

					//file upload
					if (count($_FILES['preSalePriorityFileLetter']['name']) > 0) {
						$config['upload_path']   = './uploads/demo_image';
						$config['allowed_types'] = 'jpg|pdf';
						$config['max_size']      = 2000000;
						$config['overwrite'] = true;
						$this->load->library('upload');

						$i = 0;
						foreach ($_FILES['preSalePriorityFileLetter'] as $key => $value) {
							if (!empty($_FILES['preSalePriorityFileLetter']['name'][$i])) {
								$image_parts = pathinfo($_FILES['preSalePriorityFileLetter']['name'][$i]);
								$image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
								$image_type = $image_parts['extension'];
								$filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

								$_FILES['userfile_presale_priority_letter']['name'] = $filename;
								$_FILES['userfile_presale_priority_letter']['type'] = $_FILES['preSalePriorityFileLetter']['type'][$i];
								$_FILES['userfile_presale_priority_letter']['tmp_name'] = $_FILES['preSalePriorityFileLetter']['tmp_name'][$i];
								$_FILES['userfile_presale_priority_letter']['error'] = $_FILES['preSalePriorityFileLetter']['error'][$i];
								$_FILES['userfile_presale_priority_letter']['size'] = $_FILES['preSalePriorityFileLetter']['size'][$i];
								$config['file_name']   = $_FILES['userfile_presale_priority_letter']['name'];

								$this->upload->initialize($config);
								$this->upload->do_upload('userfile_presale_priority_letter');

								//file name and file path
								$letter_presale_priority_filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
								$letter_presale_priority_filename1[] = $filename;

								$dataArr['letter_file_path'] = json_encode($letter_presale_priority_filepath);
								$dataArr['letter_file_name'] = json_encode($letter_presale_priority_filename1);
							}
							$i++;
						}
					}
				}
				if ($this->input->post('nature_of_demo') == 'pre_sale') {
					$dataArr['lead_id'] = $this->input->post('lead');
					$dataArr['opportunity_id'] = $opportunity_id;
					$dataArr['product_id'] = $this->Common_model->get_value('opportunity_product', array('opportunity_id' => $opportunity_id), 'product_id');
					// $dataArr['name_of_units_demonstrated'] = $this->input->post('name_of_units_demonstrated');
					$dataArr['demo_machine'] = $this->input->post('demo');
					$dataArr['demo_product_id'] = $this->input->post('demo');
					$dataArr['start_date'] = $this->input->post('start_date_presale');
					$dataArr['end_date'] = $this->input->post('end_date_presale');
					// $dataArr['key_decision_makers_individual'] = $this->input->post('key_decision_makers_individual');
					$dataArr['unit_details_with_specific_model'] = $this->input->post('unit_details_with_specific_model');
					$dataArr['competition_info_configuration'] = $this->input->post('competition_info_configuration');
					$dataArr['no_interactions_end_users'] = $this->input->post('no_interactions_end_users');
					// $dataArr['planned_timeline'] = $this->input->post('planned_timeline_presale');
					$dataArr['name_of_institute'] = $this->input->post('name_of_institute_presale');
					$dataArr['contact_detail'] = $this->input->post('contact_detail_presale');
					$dataArr['name_of_contact_institute'] = $this->input->post('address_presale');
					$dataArr['key_decision_makers'] = $this->input->post('key_decision_makers_presale');
					 //file upload
					 if (count($_FILES['presaleFile']['name']) > 0) {
                        $config['upload_path']   = './uploads/demo_image';
                        $config['allowed_types'] = 'jpg|pdf';
                        $config['max_size']      = 2000000;
                        $config['overwrite'] = true;
                        $this->load->library('upload');

                        $i = 0;
                        foreach ($_FILES['presaleFile'] as $key => $value) {
                            if (!empty($_FILES['presaleFile']['name'][$i])) {
                                $image_parts = pathinfo($_FILES['presaleFile']['name'][$i]);
                                $image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
                                $image_type = $image_parts['extension'];
                                $filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

                                $_FILES['userfile_presale']['name'] = $filename;
                                $_FILES['userfile_presale']['type'] = $_FILES['presaleFile']['type'][$i];
                                $_FILES['userfile_presale']['tmp_name'] = $_FILES['presaleFile']['tmp_name'][$i];
                                $_FILES['userfile_presale']['error'] = $_FILES['presaleFile']['error'][$i];
                                $_FILES['userfile_presale']['size'] = $_FILES['presaleFile']['size'][$i];
                                $config['file_name']   = $_FILES['userfile_presale']['name'];

                                $this->upload->initialize($config);
                                $this->upload->do_upload('userfile_presale');

                                //file name and file path
                                $filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
                                $filename1[] = $filename;

                                $dataArr['file_path'] = json_encode($filepath);
                                $dataArr['file_name'] = json_encode($filename1);
                            }
                            $i++;
                        }
                    }
                    //file upload
                    if (count($_FILES['preSaleFileLetter']['name']) > 0) {
                        $config['upload_path']   = './uploads/demo_image';
                        $config['allowed_types'] = 'jpg|pdf';
                        $config['max_size']      = 2000000;
                        $config['overwrite'] = true;
                        $this->load->library('upload');

                        $i = 0;
                        foreach ($_FILES['preSaleFileLetter'] as $key => $value) {
                            if (!empty($_FILES['preSaleFileLetter']['name'][$i])) {
                                $image_parts = pathinfo($_FILES['preSaleFileLetter']['name'][$i]);
                                $image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
                                $image_type = $image_parts['extension'];
                                $filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

                                $_FILES['userfile_presale_letter']['name'] = $filename;
                                $_FILES['userfile_presale_letter']['type'] = $_FILES['preSaleFileLetter']['type'][$i];
                                $_FILES['userfile_presale_letter']['tmp_name'] = $_FILES['preSaleFileLetter']['tmp_name'][$i];
                                $_FILES['userfile_presale_letter']['error'] = $_FILES['preSaleFileLetter']['error'][$i];
                                $_FILES['userfile_presale_letter']['size'] = $_FILES['preSaleFileLetter']['size'][$i];
                                $config['file_name']   = $_FILES['userfile_presale_letter']['name'];

                                $this->upload->initialize($config);
                                $this->upload->do_upload('userfile_presale_letter');

                                //file name and file path
                                $letter_presale_filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
                                $letter_presale_filename1[] = $filename;

                                $dataArr['letter_file_path'] = json_encode($letter_presale_filepath);
                                $dataArr['letter_file_name'] = json_encode($letter_presale_filename1);
                            }
                            $i++;
                        }
                    }

					
				}
				if ($this->input->post('nature_of_demo') == 'marketing') {
					$dataArr['lead_id'] = $this->input->post('lead_marketing');
					$dataArr['opportunity_id'] = $this->input->post('opportunity_marketing');
					$dataArr['product_id'] = $this->Common_model->get_value('opportunity_product', array('opportunity_id' => $this->input->post('opportunity_marketing')), 'product_id');
					$dataArr['demo_machine'] = $this->input->post('demo_marketing');
					$dataArr['demo_product_id'] = $this->input->post('demo_marketing');
					$dataArr['start_date'] = $this->input->post('start_date_marketing');
					$dataArr['end_date'] = $this->input->post('end_date_marketing');
					$dataArr['event_details'] = $this->input->post('event_details');
					$dataArr['units_for_display'] = $this->input->post('units_for_display');
					// $dataArr['planned_timeline'] = $this->input->post('planned_timeline_marketing');
					$dataArr['name_of_institute'] = $this->input->post('name_of_institute_marketing');
					$dataArr['contact_detail'] = $this->input->post('contact_detail_marketing');
					$dataArr['name_of_contact_institute'] = $this->input->post('address_marketing');
					$dataArr['key_decision_makers'] = $this->input->post('key_decision_makers_marketing');
					$dataArr['name_of_units_demonstrated'] = $this->input->post('name_of_units_demonstrated_marketing');


					//file upload
                    if (count($_FILES['marketingRequestFile']['name']) > 0) {
                        $config['upload_path']   = './uploads/demo_image';
                        $config['allowed_types'] = 'jpg|pdf';
                        $config['max_size']      = 2000000;
                        $config['overwrite'] = true;
                        $this->load->library('upload');

                        $i = 0;
                        foreach ($_FILES['marketingRequestFile'] as $key => $value) {
                            if (!empty($_FILES['marketingRequestFile']['name'][$i])) {
                                $image_parts = pathinfo($_FILES['marketingRequestFile']['name'][$i]);
                                $image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
                                $image_type = $image_parts['extension'];
                                $filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

                                $_FILES['userfile_marketing']['name'] = $filename;
                                $_FILES['userfile_marketing']['type'] = $_FILES['marketingRequestFile']['type'][$i];
                                $_FILES['userfile_marketing']['tmp_name'] = $_FILES['marketingRequestFile']['tmp_name'][$i];
                                $_FILES['userfile_marketing']['error'] = $_FILES['marketingRequestFile']['error'][$i];
                                $_FILES['userfile_marketing']['size'] = $_FILES['marketingRequestFile']['size'][$i];
                                $config['file_name']   = $_FILES['userfile_marketing']['name'];
                                $this->upload->initialize($config);
                                $this->upload->do_upload('userfile_marketing');
                
                                //file name and file path
                                $filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
                                $filename1[] = $filename;

                                $dataArr['file_path'] = json_encode($filepath);
                                $dataArr['file_name'] = json_encode($filename1);
                            }
                            $i++;
                        }
                    }
                    
                    //file upload
                    if (count($_FILES['marketingLetterFile']['name']) > 0) {
                        $config['upload_path']   = './uploads/demo_image';
                        $config['allowed_types'] = 'jpg|pdf';
                        $config['max_size']      = 2000000;
                        $config['overwrite'] = true;
                        $this->load->library('upload');

                        $i = 0;
                        foreach ($_FILES['marketingLetterFile'] as $key => $value) {
                            if (!empty($_FILES['marketingLetterFile']['name'][$i])) {
                                $image_parts = pathinfo($_FILES['marketingLetterFile']['name'][$i]);
                                $image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
                                $image_type = $image_parts['extension'];
                                $filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

                                $_FILES['userfile_letter']['name'] = $filename;
                                $_FILES['userfile_letter']['type'] = $_FILES['marketingLetterFile']['type'][$i];
                                $_FILES['userfile_letter']['tmp_name'] = $_FILES['marketingLetterFile']['tmp_name'][$i];
                                $_FILES['userfile_letter']['error'] = $_FILES['marketingLetterFile']['error'][$i];
                                $_FILES['userfile_letter']['size'] = $_FILES['marketingLetterFile']['size'][$i];
                                $config['file_name']   = $_FILES['userfile_letter']['name'];

                                $this->upload->initialize($config);
                                $this->upload->do_upload('userfile_letter');

                                //file name and file path
                                $letter_filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
                                $letter_filename1[] = $filename;

                                $dataArr['letter_file_path'] = json_encode($letter_filepath);
                                $dataArr['letter_file_name'] = json_encode($letter_filename1);
                            }
                            $i++;
                        }
                    }
				}
				if ($this->input->post('nature_of_demo') == 'post_sale') {
					$dataArr['lead_id'] = $this->input->post('lead_post_sale');
					$dataArr['opportunity_id'] = $this->input->post('opportunity_post_sale');
					$dataArr['product_id'] = $this->Common_model->get_value('opportunity_product', array('opportunity_id' => $this->input->post('opportunity_post_sale')), 'product_id');
					$dataArr['demo_machine'] = $this->input->post('demo_post_sale');
					$dataArr['demo_product_id'] = $this->input->post('demo_post_sale');
					$dataArr['start_date'] = $this->input->post('start_date_post_sale');
					$dataArr['end_date'] = $this->input->post('end_date_post_sale');
					$dataArr['date_of_installation'] = $this->input->post('date_of_installation');
					$dataArr['installed_by'] = $this->input->post('installed_by');
					$dataArr['name_units_installed'] = $this->input->post('name_units_installed');
					// $dataArr['timeline_post_sale_demo'] = $this->input->post('timeline_post_sale_demo');
					// $dataArr['planned_timeline'] = $this->input->post('planned_timeline_postsale');
					$dataArr['name_of_institute'] = $this->input->post('name_of_institute_postsale');
					$dataArr['contact_detail'] = $this->input->post('contact_detail_postsale');
					$dataArr['name_of_contact_institute'] = $this->input->post('address_postsale');
					$dataArr['key_decision_makers'] = $this->input->post('key_decision_postsale');
					$dataArr['serial_number'] = $this->input->post('serial_number_postsale');
					$dataArr['name_of_units_demonstrated'] = $this->input->post('name_of_units_demonstrated_postsale');
					$dataArr['unit_details_with_specific_model'] = $this->input->post('unit_details_with_specific_model_postsale');

					//file upload
					if (count($_FILES['preSalePriorityFile']['name']) > 0) {
						$config['upload_path']   = "./uploads/demo_image/";
						$config['allowed_types'] = 'jpg|pdf';
						$config['max_size']      = 2000000;
						$config['overwrite'] = true;
						$this->load->library('upload');

						$i = 0;
						foreach ($_FILES['attachReportFile'] as $key => $value) {
							if (!empty($_FILES['attachReportFile']['name'][$i])) {
								$image_parts = pathinfo($_FILES['attachReportFile']['name'][$i]);
								$image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
								$image_type = $image_parts['extension'];
								$filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

								$_FILES['userfile_postsale']['name'] = $filename;
								$_FILES['userfile_postsale']['type'] = $_FILES['attachReportFile']['type'][$i];
								$_FILES['userfile_postsale']['tmp_name'] = $_FILES['attachReportFile']['tmp_name'][$i];
								$_FILES['userfile_postsale']['error'] = $_FILES['attachReportFile']['error'][$i];
								$_FILES['userfile_postsale']['size'] = $_FILES['attachReportFile']['size'][$i];
								$config['file_name']   = $_FILES['userfile_postsale']['name'];

								$this->upload->initialize($config);
								$this->upload->do_upload('userfile_postsale');

								//file name and file path
								$filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
								$filename1[] = $filename;

								$dataArr['file_path'] = json_encode($filepath);
								$dataArr['file_name'] = json_encode($filename1);
							}
							$i++;
						}
					}
				}
				if ($this->input->post('nature_of_demo') == 'existing_customer_visit') {
					$dataArr['lead_id'] = $this->input->post('lead_existing');
					$dataArr['opportunity_id'] = $this->input->post('opportunity_existing');
					$dataArr['product_id'] = $this->Common_model->get_value('opportunity_product', array('opportunity_id' => $this->input->post('opportunity_existing')), 'product_id');
					$dataArr['demo_machine'] = $this->input->post('demo_existing');
					$dataArr['demo_product_id'] = $this->input->post('demo_existing');
					$dataArr['start_date'] = $this->input->post('start_date_existing');
					$dataArr['end_date'] = $this->input->post('end_date_existing');
					$dataArr['customer_complaint_future_prospect'] = $this->input->post('customer_complaint_future_prospect');
					$dataArr['customer_complaint_future_prospect_details'] = $this->input->post('customer_complaint_future_prospect_details');
					// $dataArr['planned_timeline'] = $this->input->post('planned_timeline_existing');
					$dataArr['name_of_institute'] = $this->input->post('name_of_institute_existing');
					$dataArr['contact_detail'] = $this->input->post('contact_detail_existing');
					$dataArr['name_of_contact_institute'] = $this->input->post('address_existing');
					$dataArr['key_decision_makers'] = $this->input->post('key_decision_existing');
					$dataArr['serial_number'] = $this->input->post('serial_number_existing');
					$dataArr['unit_details_with_specific_model'] = $this->input->post('unit_details_with_specific_model_existing');
					$dataArr['name_of_units_demonstrated'] = $this->input->post('name_of_units_demonstrated_existing');

				}
			}
			if($this->input->post('nature_of_demo') == 'pre_sale' || $this->input->post('nature_of_demo') == 'pre_sale_priority' || $this->input->post('nature_of_demo') == 'marketing'){
				$result_check = $this->Calendar_model->checkDemoAvailability($dataArr);
			}				
				if ($result_check != 0) {
					$this->session->set_flashdata('error', '<div class="alert alert-danger alert-white rounded">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<div class="icon"><i class="fa fa-check"></i></div>
					<strong>Error!</strong> Demo has already been booked for this timings! Start Date: <b>'.$dataArr['start_date'].'</b>  and   End Date:  <b>'.$dataArr['end_date'].'</b>
					</div>');
					//redirect(SITE_URL.'planDemo');
					redirect($this->agent->referrer());
				}
				
				//$dataArr = $_POST[];
				if ($demo_id == "") {
					$dataArr['created_by'] = $this->session->userdata('user_id');
					$dataArr['created_time'] = date('Y-m-d H:i:s');
					//Insert
					$demo_id = $this->Common_model->insert_data('demo', $dataArr);
				//send mail to admin for new plan demo
				send_mail_demo_details($demo_id);
				$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Demo has been booked successfully!
									 </div>');
			} else {
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('demo_id' => $demo_id);

				//Update
				$this->Common_model->update_data('demo', $dataArr, $where);

				$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Demo has been updated successfully!
									 </div>');
			}

			redirect(SITE_URL . 'demo');
		}
	}
	#Deactivating demo requests
	public function deleteDemo($encoded_id)
	{
		$demo_id = @icrm_decode($encoded_id);
		$where = array('demo_id' => $demo_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('demo', $dataArr, $where);

		$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Demo has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL . 'demo');
	}
	#Reactivating Demo
	public function activateDemo($encoded_id)
	{
		$demo_id = @icrm_decode($encoded_id);
		$where = array('demo_id' => $demo_id);

		$results = $this->Common_model->get_data('demo', $where);
		$data = array('demo_id' => '', 'demo_product_id' => $results[0]['demo_product_id'], 'start_date' => $results[0]['start_date'], 'end_date' => $results[0]['end_date']);
		$result_check = $this->Calendar_model->checkDemoAvailability($data);
		if ($result_check) {
			$this->session->set_flashdata('activate_error', '<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> Demo has already been booked!
								 </div>');
			//redirect(SITE_URL.'planDemo');
			redirect($this->agent->referrer());
		}

		$dataArr = array('status' => 1);
		$this->Common_model->update_data('demo', $dataArr, $where);

		$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Demo has been Activated successfully!
							 </div>');
		redirect(SITE_URL . 'demo');
	}
	#Downloading Demo Details.
	public function downloadDemo()
	{
		if ($this->input->post('downloadDemo') != '') {
			$searchParams = array(
				'opportunityId' => $this->input->post('opportunityId', TRUE),
				'customer' => $this->input->post('customer', TRUE),
				'startDate' => $this->input->post('startDate', TRUE),
				'endDate' => $this->input->post('endDate'), TRUE
			);

			$demos = $this->Calendar_model->demoDetails($searchParams);

			$header = '';
			$data = '';
			$titles = array('S.NO', 'Customer Name','Lead ID', 'Opportunity', 'Product Details', 'Start Date', 'End Date');
			$data = '<table border="1">';
			$data .= '<thead>';
			$data .= '<tr>';
			foreach ($titles as $title) {
				$data .= '<th>' . $title . '</th>';
			}
			$data .= '</tr>';
			$data .= '</thead>';
			$data .= '<tbody>';
			$j = 1;
			if (count($demos) > 0) {

				foreach ($demos as $demo) {
					$data .= '<tr>';
					$data .= '<td align="center">' . $j . '</td>';
					$data .= '<td>' . $demo['CustomerName'] . '</td>';
					$data .= '<td>' . $demo['lead_number'] . '</td>';
					$data .= '<td>' . $demo['opportunity'] . '</td>';
					$data .= '<td>' . $demo['product_name'].'-'.$demo['product_description']. '</td>';
					$data .= '<td>' . $demo['start_date'] . '</td>';
					$data .= '<td>' . $demo['end_date'] . '</td>';
					$data .= '</tr>';
					$j++;
				}
			} else {
				$data .= '<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
			}
			$data .= '</tbody>';
			$data .= '</table>';
			$time = date("Ymdhis");
			$xlFile = 'demo_' . $time . '.xls';
			header("Content-type: application/x-msdownload");
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=" . $xlFile . "");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
		}
	}
	#Fetching Demo data
	public function demoDetails()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo Details";
		$data['nestedView']['cur_page'] = 'demoDetails';
		$data['nestedView']['parent_page'] = 'demoDetails';

		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.extend.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.css"></link>';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.fullcalendar/fullcalendar/fullcalendar.print.css" media="print"></link>';

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo Details';
		$data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Demo Details', 'class' => 'active', 'url' => '');
		# Search Functionality
		$psearch = $this->input->post('searchDemoProduct', TRUE);
		if ($psearch != '') {
			$searchParams = array(
				'location' => $this->input->post('location', TRUE),
				'serialNumber' => $this->input->post('serialNumber', TRUE),
				'branch' => $this->input->post('branch', TRUE),
				'city' => $this->input->post('city', TRUE)
			);
			$this->session->set_userdata($searchParams);
		} else {

			if ($this->uri->segment(2) != '') {
				$searchParams = array(
					'location' => $this->session->userdata('location'),
					'serialNumber' => $this->session->userdata('serialNumber'),
					'branch' => $this->session->userdata('branch'),
					'city' => $this->session->userdata('city')
				);
			} else {
				$searchParams = array(
					'location' => '',
					'serialNumber' => '',
					'branch' => '',
					'city' => ''
				);
				$this->session->unset_userdata(array_keys($searchParams));
			}
		}
		$data['searchParams'] = $searchParams;
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL . 'demoDetails/';
		# Total Records
		$config['total_rows'] = $this->Calendar_model->demoProductTotalRows($searchParams);

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

		# Search Results
		$data['demoProductSearch'] = $this->Calendar_model->demoProductResults($searchParams, $config['per_page'], $current_offset);
		$data['displayList'] = 1;
		# Load page with all Demo details
		$this->load->view('calendar/demoDetailsView', $data);
	}
	#Download Demo Details In Excel
	public function downloadDemoDetails()
	{
		if ($this->input->post('downloadDemoProduct') != '') {

			$searchParams = array(
				'location' => $this->input->post('location', TRUE),
				'serialNumber' => $this->input->post('serialNumber', TRUE),
				'branch' => $this->input->post('branch', TRUE)
			);
			$demoProducts = $this->Calendar_model->demoProductDetails($searchParams);

			$header = '';
			$data = '';
			$titles = array(
				'S.NO', 'Product Category', 'Material Group', 'Group Description', 'Product', 'Product Description',
				'RRP (Rs)', 'Serial Number', 'City', 'Location Name', 'Region', 'Sales Branch Office', 'Responsible Person (RBH of Region)', 'Created Time', 'Modified By', 'Modified Time'
			);
			$data = '<table border="1">';
			$data .= '<thead>';
			$data .= '<tr>';
			foreach ($titles as $title) {
				$data .= '<th>' . $title . '</th>';
			}
			$data .= '</tr>';
			$data .= '</thead>';
			$data .= '<tbody>';
			$j = 1;
			if (count($demoProducts) > 0) {

				foreach ($demoProducts as $demoProduct) {
					$data .= '<tr>';
					$data .= '<td valign="top" align="center">' . $j . '</td>';
					$data .= '<td valign="top">' . $demoProduct['CategoryName'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['GroupName'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['GroupDescription'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['ProductName'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['ProductDescription'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['rrp'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['serial_number'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['city'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['location'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['region'] . '</td>';
					$data .= '<td valign="top">' . $demoProduct['branch'] . '</td>';
					$data .= '<td valign="top">' . getRBHforRegion($demoProduct['region_id']) . '</td>';
					$data .= '<td valign="top">' . DateFormatAM($demoProduct['created_time']) . '</td>';
					$data .= '<td valign="top">' . getUserName($demoProduct['modified_by']) . '</td>';
					$data .= '<td valign="top">' . DateFormatAM($demoProduct['modified_time']) . '</td>';
					$data .= '</tr>';
					$j++;
				}
			} else {
				$data .= '<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
			}
			$data .= '</tbody>';
			$data .= '</table>';
			$time = date("Ymdhis");
			$xlFile = 'demodetails_' . $time . '.xls';
			header("Content-type: application/x-msdownload");
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=" . $xlFile . "");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
		}
	}
	#Download Demo Calendar Details
	public function downloadDemoCalendarDetails($encoded_id)
	{
		$demos = $this->Calendar_model->getDemoCalendarDetails(@icrm_decode($encoded_id));

		$header = '';
		$data = '';
		$titles = array('S.NO', 'Booked By', 'Customer Name', 'Start Date', 'End Date');
		$data = '<table border="1">';
		$data .= '<thead>';
		$data .= '<tr>';
		foreach ($titles as $title) {
			$data .= '<th>' . $title . '</th>';
		}
		$data .= '</tr>';
		$data .= '</thead>';
		$data .= '<tbody>';
		$j = 1;
		if (count($demos) > 0) {

			foreach ($demos as $demo) {
				$data .= '<tr>';
				$data .= '<td align="center" valign="top">' . $j . '</td>';
				$data .= '<td valign="top">' . $demo['booked_by'] . '</td>';
				$data .= '<td valign="top">' . $demo['customer'] . '</td>';
				$data .= '<td valign="top">' . $demo['start'] . '</td>';
				$data .= '<td valign="top">' . $demo['end'] . '</td>';
				$data .= '</tr>';
				$j++;
			}
		} else {
			$data .= '<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
		}
		$data .= '</tbody>';
		$data .= '</table>';
		$time = date("Ymdhis");
		$xlFile = 'democalendardetails_' . $time . '.xls';
		header("Content-type: application/x-msdownload");
		# replace excelfile.xls with whatever you want the filename to default to
		header("Content-Disposition: attachment; filename=" . $xlFile . "");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $data;
	}
	#Fetching Opportunity list through ajax call
	public function getOpportunity()
	{
		$data = array();
		$lead_id = @$_REQUEST['lead_id'];
		$results = $this->Calendar_model->getOpportunity($lead_id);
		$opportunities = '<option value="">Select Opportunity</option>';
		foreach ($results as $key => $value) {
			$opportunities .= '<option value="' . $key . '">' . $value . '</option>';
		}
		$lead_detail = $this->Calendar_model->getLeadDetail($lead_id);
		$data = array('opportunities' => $opportunities,
		'nameofinstitute' => trim($lead_detail['nameofistitue']),
		'contactdetail' => trim($lead_detail['contactdetails']),
		'address' => trim($lead_detail['address'])
		);
		echo json_encode($data);
	}
	#Fetching demo list through ajax request
	public function getDemo()
	{
		$data = array();
		$opportunity_id = @$_REQUEST['opportunity_id'];
		$results = $this->Calendar_model->getDemo($opportunity_id);
		$demo = '<option value="">Select Demo Machine</option>';
		$product ='';
		foreach ($results as $key => $value) {
			$demo .= '<option value="' . $key . '">' . $value . '</option>';
			$product = $value;
		}
		$opportunity_detail = $this->Common_model->get_data_row('opportunity',array('opportunity_id'=>$opportunity_id));
		$key_makers_detail = $this->Common_model->get_data_row('contact',array('contact_id'=>$opportunity_detail['decision_maker1']));
		$data = array(
			'demo' => $demo,
		    'key_makers' => trim($key_makers_detail['first_name']),
			'unit_details_with_specific_model' => $product
		);
		echo json_encode($data);
	}
	#Fetching Demo Calendar Data 
	public function getDemoCalendar()
	{
		$demo_product_id = @$_REQUEST['demo_product_id'];
		$data = $this->Calendar_model->getDemoCalendar($demo_product_id);
		echo json_encode($data);
	}

	//mahesh 14th july 2016 5:46 PM
	public function update_visitFeedback()
	{

		if ($this->input->post('visitUpdate_submit') != '') {

			$encoded_id = $this->input->post('encoded_id');
			$visit_id = icrm_decode($encoded_id);
			$where = array('visit_id' => $visit_id);
			$data = array(
				'remarks2' 	  => $this->input->post('remarks2'),
				'status'        => $this->input->post('status'),
				'modified_by'   => $this->session->userdata('user_id'),
				'modified_time' => date('Y-m-d H:i:s')
			);
			//update visit feedback
			$this->Common_model->update_data('visit', $data, $where);

			$this->session->set_flashdata('activate_error', '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Visit Feedback has been updated successfully!
								 </div>');
			//redirect(SITE_URL.'planDemo');
			redirect(SITE_URL . 'visit');
		}
	}

	//mahesh 14th july 2016 06:56 PM
	public function update_demoFeedback()
	{

		if ($this->input->post('demoUpdate_submit') != '') {

			$encoded_id = $this->input->post('encoded_id');
			$demo_id = icrm_decode($encoded_id);
			$where = array('demo_id' => $demo_id);
			$data = array(
				'remarks2' 	  => $this->input->post('remarks2'),
				'modified_by'   => $this->session->userdata('user_id'),
				'modified_time' => date('Y-m-d H:i:s')
			);
			//update visit feedback
			$this->Common_model->update_data('demo', $data, $where);

			$this->session->set_flashdata('activate_error', '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Demo Feedback has been updated successfully!
								 </div>');
			redirect(SITE_URL . 'demo');
		}
	}
}
