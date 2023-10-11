<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Campaign extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdminModel");
        $this->load->model("campaign_model");
        $this->load->model("customer_model");
    }

    public function Campaign() {

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Campaign";
        $data['nestedView']['cur_page'] = 'campaign';
        $data['nestedView']['parent_page'] = 'campaign';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Campaign';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Campaign', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('searchCampaign', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'campaignName' => $this->input->post('campaignName', TRUE),
                'fromDate' => $this->input->post('fromDate', TRUE),
                'toDate' => $this->input->post('toDate', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'campaignName' => $this->session->userdata('campaignName'),
                    'fromDate' => $this->session->userdata('fromDate'),
                    'toDate' => $this->session->userdata('toDate')
                );
            } else {
                $searchParams = array(
                    'campaignName' => '',
                    'fromDate' => '',
                    'toDate' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;




        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'campaign/';
        # Total Records
        $config['total_rows'] = $this->campaign_model->campaignTotalRows($searchParams);

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
        $search_fields = 0;
        $campaignSearch = array();
        $campaignSearch = $this->campaign_model->get_details($current_offset, $config['per_page'], @$searchParams);

        $data['campaign_data'] = array();

        $data['CustomerInfo'] = array('' => 'Select Customer') + $this->Common_model->get_dropdown('customer', 'customer_id', 'name', []);
        $data['s_loc'] = $this->customer_model->getSearchLocation(@$searchParams['location_id']);

        //$data['SpecialityInfo'] = $this->campaign_model->getSpecialityInfo();
        $data['SpecialityInfo'] = array('' => 'Select Speciality') + $this->Common_model->get_dropdown('speciality', 'speciality_id', 'name', []);
        $data['locations'] = array('' => 'Select Location') + $this->Common_model->get_dropdown("location", "location_id", "location", array('territory_level_id' => 7));

        # Loading the data array to send to View
        $data['campaignSearch'] = @$campaignSearch['resArray'];
        $data['count'] = @$campaignSearch['count'];
        $data['displayList'] = 1;


        # Load page with all shop details
        # Load page with all shop details
        $this->load->view('campaign/campaignView', $data);
    }

    public function addCampaign() {

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Campaign";
        $data['nestedView']['cur_page'] = 'campaign';
        $data['nestedView']['parent_page'] = 'campaign';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/manage-campaign.js"></script>';
        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Add New Campaign';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage campaign', 'class' => 'active', 'url' => SITE_URL . 'campaign');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add New', 'class' => 'active', 'url' => '');

        $data['specialityInfo'] = $this->Common_model->get_dropdown('speciality', 'speciality_id', 'name', []);
        $data['locations'] = array('' => 'Select Location') + $this->Common_model->get_dropdown("location", "location_id", "location", array('territory_level_id' => 7));

        $data['flg'] = 1;
        $data['val'] = 0;

        //mahesh code for multiselecting territorys
        $data['geos'] = $this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>2));

        # Load page with all shop details
        $this->load->view('campaign/campaignView', $data);
    }

    public function editCampaign($encoded_id) {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Campaign";
        $data['nestedView']['cur_page'] = 'campaign';
        $data['nestedView']['parent_page'] = 'campaign';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'View Campaign';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Campaign', 'class' => 'active', 'url' => SITE_URL . 'campaign');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'View Campaign', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);

            $where = array('campaign_id' => $value);
            $data['campaign_data'] = $this->campaign_model->getCampaignData($value);

            $data['specialityInfo'] = array('' => 'Select Speciality') + $this->Common_model->get_dropdown('speciality', 'speciality_id', 'name', []);
            $data['locations'] = array('' => 'Select Location') + $this->Common_model->get_dropdown("location", "location_id", "location", array('territory_level_id' => 7));
            $data['city'] = $this->customer_model->getLocation($value);
            // $data['search_data'] = $search_params;
            //$data['companyEdit'] = $this->AdminModel->editCompanyDetails($value);
        }
        $data['flg'] = 1;
        $data['val'] = 1;
        # Load page with all shop details
        $this->load->view('campaign/campaignView', $data);
    }

    public function campaignAdd() {

        if ($this->input->post('submitCampaign') != "") {



            $type = $this->input->post('campaign_type');

            //$dataArr = $_POST[];
            
             {
                $dataArr = array(
                    //'speciality_id' => $this->input->post('speciality_id'),
                    //'location_id' => $this->input->post('location_id'),
                    'type' => @$type,
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'campaign_date' => date('Y-m-d', strtotime($this->input->post('campaign_date'))),
                    'mail_content' => $this->input->post('mail_content'),
                    'subject' => $this->input->post('subject'),
                    'created_by' => $_SESSION['user_id'],
                    'created_time' => date('Y-m-d H:i:s')
                );
				// TRANSACTION BEGIN
				$this->db->trans_begin();
                //Insert
                $campaign_id = $this->Common_model->insert_data('campaign', $dataArr);
				//Insert campaign specializations
				$specializations_data = array();
				$specializations = $this->input->post('speciality_id');
				if(@$specializations){
					foreach(@$specializations as $specialization_id){
						$specializations_data[] = array('campaign_id'=>$campaign_id,
													'speciality_id'=>$specialization_id
												  );
					}
					
					$this->Common_model->insert_batch_data('campaign_speciality', $specializations_data);
				}
		//Fetching locations
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
		
		$campaign_locations_data = array();
		if(count(@$user_locations)>0){
			foreach($user_locations as $locationId => $locArr){
				$campaign_locations_data[]=array('campaign_id'=>$campaign_id,
											'location_id'=>$locationId
											);
			}
			//INSERTING CAMPAIGN LOCATIONS
			$this->Common_model->insert_batch_data('campaign_location', $campaign_locations_data);
		}
		
                /*                 * *****************upload attachments********************** */
                $attachments = array();
                $value = $_FILES;
                $count = count($_FILES['file_name']['size']);

                if ($_FILES['file_name']['name'][0] != NULL) { // check upload fiels exist
                    foreach ($_FILES as $key => $value) { // 
                        for ($s = 0; $s <= $count - 1; $s++) {
                            $_FILES['userfile']['name'] = $value['name'][$s];
                            $_FILES['userfile']['type'] = $value['type'][$s];
                            $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
                            $_FILES['userfile']['error'] = $value['error'][$s];
                            $_FILES['userfile']['size'] = $value['size'][$s];

                            $f = file_upload('userfile', NULL, 'uploads/campaign_email/',FALSE);
                            if($f != '') $attachments[] = $f;
                        }
                    }
                    //print_r($attachments);die();
                    if (count($attachments) > 0) { // attachments Exists
                        foreach ($attachments as $attachment) {
                            $doc_array = array(
                                "campaign_id" => $campaign_id,
                                "name" => $attachment,
                                "created_by" => $_SESSION['user_id'],
                                "created_time" => date('Y-m-d H:i:s')
                            );
                            $this->Common_model->insert_data('campaign_attachment', $doc_array);
                        }
                    }
                }
				$i=0;
                if($type==1){
    				$cc_emails = $this->input->post('mail_to',TRUE);
                    if ($cc_emails!='') { 

                        $from = "noreply@skanray-access.com";
                        $to = "noreply@skanray-access.com";
                        $body = $this->input->post('mail_content');
                        $cc = '';
                        $bcc = $cc_emails;
                        $replyto = $from;
                        $subject = $this->input->post('subject',TRUE);
                        //$attachments
                        if (count($attachments) > 0) { // check attachment exists.
                            $cnt = 1;
                            $docs = array();
                            foreach ($attachments as $v) {
                                $ext = substr(strrchr($v, '.'), 1);
                                $docs["Attachment_$cnt" . '.' . $ext] = SITE_URL1 . "/uploads/campaign_email/" . $v;
                                $cnt++;
                            }
                        }

                        //entransys_send_email($from, $to, $body.'sadas', $cc, $bcc, $replyto, $subject, $docs);
                        
                        send_email( $to,$subject, $body, $cc, $from,$from_name='Skanray', $bcc, NULL,  $docs);                     
                    } // emails check [end]
                    $i=1;
                }
                //die(); 
				if ($this->db->trans_status() === FALSE)
				{
						$this->db->trans_rollback();
						$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											<div class="icon"><i class="fa fa-check"></i></div>
											<strong>Error!</strong> There\'s a problem occured while adding a campaign!
										 </div>');
						
				}
				else
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Campaign has been Added successfully!
									 </div>');
				}
                
                redirect(SITE_URL . 'campaign');
            } 
        }
    }

    function get_sub_category() {
        $cat_id = $this->input->post('cat_id');
        $this->customer_model->get_sub_category_dropdown_ajax($cat_id);
    }

    public function downloadCampaign() {
        //print_r($_POST);
        if ($this->input->post('downloadCampaign') != '') {



            $search_params = array(
                'campaignName' => $this->input->post('campaignName', TRUE),
                'fromDate' => $this->session->userdata('fromDate'),
                'toDate' => $this->session->userdata('toDate')
            );

            $campaigns_info = $this->campaign_model->get_download_details($search_params);

            $header = '';
            $data = '';
            $titles = array('S.NO', 'Specialities', 'Locations', 'Name', 'Campaign date', 'Status');
            $data = '<table border="1">';
            $data.='<thead>';
            $data.='<tr>';
            foreach ($titles as $title) {
                $data.= '<th>' . $title . '</th>';
            }
            $data.='</tr>';
            $data.='</thead>';
            $data.='<tbody>';
            $j = 1;
            if (count($campaigns_info) > 0) {

                foreach ($campaigns_info as $campaign) {
                    $data.='<tr>';
                    $data.='<td align="center">' . $j . '</td>';
                    $data.='<td>' . getCampaignSpecialities($campaign['campaign_id']) . '</td>';
                    $data.='<td>' . getCampaignLocations($campaign['campaign_id']) . '</td>';
                    $data.='<td>' . $campaign['name'] . '</td>';
                    $data.='<td>' . $campaign['campaign_date'] . '</td>';
                    $data.='<td>' . statusCheck($campaign['status']) . '</td>';

                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'campaign_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }

    /**     * *********************************** campaign documents starts here *********************************** */
    public function campaignDocuments() {

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Documents";
        $data['nestedView']['cur_page'] = 'Documents';
        $data['nestedView']['parent_page'] = 'Documents';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Documents';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Documents', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('searchCampaignDocument', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'campaignDocumentName' => $this->input->post('campaignDocumentName', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'campaignDocumentName' => $this->session->userdata('campaignDocumentName')
                );
            } else {
                $searchParams = array(
                    'campaignDocumentName' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;




        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'campaignDocuments/';
        # Total Records
        $config['total_rows'] = $this->campaign_model->campaignDocumentTotalRows($searchParams);

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
        $search_fields = 0;
        $campaignDocumentSearch = array();
        $campaignDocumentSearch = $this->campaign_model->get_documents_details($current_offset, $config['per_page'], @$searchParams);

        $data['campaignDocument_data'] = array();
        # Loading the data array to send to View
        $data['campaignDocumentSearch'] = @$campaignDocumentSearch['resArray'];
        $data['count'] = @$campaignDocumentSearch['count'];
        $data['displayList'] = 1;

        $this->load->view('campaign/campaignDocumentView', $data);
    }

    function addCampaignDocuments() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Documents";
        $data['nestedView']['cur_page'] = 'Documents';
        $data['nestedView']['parent_page'] = 'Document';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Add  Document';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Campaign', 'class' => 'active', 'url' => SITE_URL . 'campaignDocuments');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add New', 'class' => 'active', 'url' => '');

        $data['specialityInfo'] = array('' => 'Select Speciality') + $this->Common_model->get_dropdown('speciality', 'speciality_id', 'name', []);
        $data['locations'] = array('' => 'Select Location') + $this->Common_model->get_dropdown("location", "location_id", "location", array('territory_level_id' => 7));

        $data['flg'] = 1;
        $data['val'] = 0;
        $data['roles'] = $this->campaign_model->get_roles();
        # Load page with all shop details
        $this->load->view('campaign/campaignDocumentView', $data);
    }

    public function campaignDocumentsAdd() {

        if ($this->input->post('submitCampaignDocument') != "") {
            $campaign_document_id = $this->input->post('campaign_document_id');
            $campaign_document_id = $this->global_functions->decode_icrm($this->input->post('campaign_document_id'));
            if ($campaign_document_id == "") {

                if ($_FILES['file_name']['name'] != NULL) { // check upload fiels exist
                    $uploaded_file_name = file_upload('file_name', NULL, 'uploads/campaign_documents/', TRUE, 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx', 4096);
                }
                if (isset($uploaded_file_name['error'])) {
                    // if error in attachment uploaded
                    $this->session->set_flashdata('response', '<div class="alert alert-danger alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-times-circle"></i></div>
								<strong>Error!</strong> ' . $uploaded_file_name['error'] . ', try again.
							 </div>');
                    redirect(SITE_URL . 'addCampaignDocuments');
                } else {
                    // if attachment uploaded
                    $dataArr = array(
                        'name' => $this->input->post('name'),
                        'description' => $this->input->post('description'),
                        'path' => $uploaded_file_name,
                        'created_by' => $_SESSION['user_id'],
                        'created_time' => date('Y-m-d H:i:s')
                    );
                    //print_r($dataArr);
                    //Insert
                    $campaign_document_id = $this->Common_model->insert_data('campaign_document', $dataArr);

                    $roles = $this->input->post('role_id');
                    if (count($roles) > 0) {
                        foreach ($roles as $role_id) {
                            $dataArr = array(
                                'campaign_document_id' => $campaign_document_id,
                                'role_id' => $role_id
                            );
                            //print_r($dataArr);
                            //Insert
                            $campaign_id = $this->Common_model->insert_data('campaign_document_role', $dataArr);
                        }
                    }
                    $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Campaign Document has been Added successfully!
									 </div>');

                    redirect(SITE_URL . 'campaignDocuments');
                }
            } else {

                $dataArr = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'modified_by' => $_SESSION['user_id'],
                    'modified_time' => date('Y-m-d H:i:s')
                );
                $where = array('campaign_document_id' => $campaign_document_id);
                //print_r($dataArr); die();
                //Update
                $this->Common_model->update_data('campaign_document', $dataArr, $where);

                $this->db->delete('campaign_document_role', array('campaign_document_id' => $campaign_document_id));
                $roles = $this->input->post('role_id');
                if (count($roles) > 0) {
                    foreach ($roles as $role_id) {
                        $dataArr = array(
                            'campaign_document_id' => $campaign_document_id,
                            'role_id' => $role_id
                        );
                        //print_r($dataArr);
                        //Insert
                        $campaign_id = $this->Common_model->insert_data('campaign_document_role', $dataArr);
                    }
                }

                // die();    
                $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Campaign Document has been updated successfully!
									 </div>');
                redirect(SITE_URL . 'campaignDocuments');
            }
        }
    }

    public function deleteCampaignDocuments($encoded_id) {
        //echo 'hi';
        $customer_id = @icrm_decode($encoded_id);
        $where = array('campaign_document_id' => $customer_id);
        $dataArr = array('status' => 2);
        $this->Common_model->update_data('campaign_document', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Campaign Document has been De-Activated successfully!
								 </div>');
        redirect(SITE_URL . 'campaignDocuments');
    }

    public function activateCampaignDocuments($encoded_id) {
        $customer_id = @icrm_decode($encoded_id);
        $where = array('campaign_document_id' => $customer_id);
        $dataArr = array('status' => 1);
        $this->Common_model->update_data('campaign_document', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Campaign Document has been Activated successfully!
								 </div>');
        redirect(SITE_URL . 'campaignDocuments');
    }

    public function editCampaignDocuments($encoded_id) {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Documents";
        $data['nestedView']['cur_page'] = 'Campaign Documents';
        $data['nestedView']['parent_page'] = 'Campaign Documents';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Edit Documents';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Documents', 'class' => 'active', 'url' => SITE_URL . 'campaignDocuments');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Documents', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);

            $where = array('campaign_id' => $value);
            $data['campaign_document_data'] = $this->campaign_model->getCampaignDocumentsData($value);
            $assigned_roles = $this->campaign_model->getCampaignDocument_roles($value);
            foreach ($assigned_roles as $v) {
                $arr[] = $v['role_id'];
            }
            $data['campaign_roles_data'] = $arr;
            $data['roles'] = $this->campaign_model->get_roles();
        }
        $data['flg'] = 1;
        $data['val'] = 1;
        # Load page with all shop details
        $this->load->view('campaign/campaignDocumentView', $data);
    }

    /*     * *************************documents view for roles ************************* */

    public function viewCampaignDocuments() {

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Marketing Documents";
        $data['nestedView']['cur_page'] = 'viewCampaignDocuments';
        $data['nestedView']['parent_page'] = 'viewCampaignDocuments';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Marketing Documents';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Marketing Documents', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('searchCampaignDocument', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'campaignDocumentName' => $this->input->post('campaignDocumentName', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'campaignDocumentName' => $this->session->userdata('campaignDocumentName')
                );
            } else {
                $searchParams = array(
                    'campaignDocumentName' => ''
                );
               $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;




        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'viewCampaignDocuments/';
        # Total Records
        $config['total_rows'] = $this->campaign_model->campaignDocumentForRolesTotalRows($searchParams);

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
        $search_fields = 0;
        $campaignDocumentSearch = array();
        $campaignDocumentSearch = $this->campaign_model->get_documents_details_for_roles($current_offset, $config['per_page'], @$searchParams);

        $data['campaignDocument_data'] = array();
        
        # Loading the data array to send to View
        $data['campaignDocumentSearch'] = @$campaignDocumentSearch['resArray'];
        $data['count'] = @$campaignDocumentSearch['count'];
        $data['displayList'] = 1;

        $this->load->view('campaign/viewCampaignDocument', $data);
    }

    //19th july 2016, 00:31 am
    public function deactivateCampaign($encoded_id)
    {
        //echo 'hi';
        $campaign_id=@icrm_decode($encoded_id);
        $data = array('status'=>2);
        $where = array('campaign_id'=>$campaign_id);
        $this->Common_model->update_data('campaign',$data,$where);
        $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Campaign has been De-Activated successfully!
                                 </div>');
            redirect(SITE_URL.'campaign');
        
    }

    //19th july 2016, 00:31 am
    public function activateCampaign($encoded_id)
    {
        //echo 'hi';
        $campaign_id=@icrm_decode($encoded_id);
        $data = array('status'=>1);
        $where = array('campaign_id'=>$campaign_id);
        $this->Common_model->update_data('campaign',$data,$where);
        $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Campaign has been Activated successfully!
                                 </div>');
            redirect(SITE_URL.'campaign');
        
    }

}
