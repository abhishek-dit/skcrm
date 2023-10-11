<?php 
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Headers: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar_api extends CI_Controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Common_model");
		$this->load->model("Calendar_model");
        $this->load->model("ajax_model");
        $this->load->model("quote_model");        
	}

	public function addVisit()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $_SESSION['role_id'] = $post_data['role_id'];
        $leads = $this->Calendar_model->getLeadDetails();
        $lead_arr = array();
        foreach ($leads as $key=>$lead) 
        {
            $lead_arr[$key]['lead_id'] = $lead['lead_id'];
            $lead_arr[$key]['name'] = "Lead ID - ".$lead['lead_number']." (".$lead['CustomerName'].")";
        }
        $data['leads'] = $lead_arr;
        // $data['dealerList'] = $this->Common_model->get_data('distributor_details',array('1'=> 1));
        // $userLocation = $this->Common_model->get_data('user_location',array('user_id'=> $post_data['user_id']));
		// // echo '<pre>'; print_r($user_id);die;
		
        // $data['dealerList'] = $this->Calendar_model->getDealerByLocation($userLocation);
        $data['dealerList'] = $this->quote_model->getDistributors();
        $data['purpose'] = $this->Common_model->get_data('visit_purpose',array());
        $this->session->sess_destroy();
        echo json_encode($data); 

    }

    public function visitAdd()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $dataArr = array
                    (
                        'visit_id'   => '',
                        'lead_id'    => $post_data['lead'],
                        'purpose_id' => $post_data['purpose'],
                        'start_date' => $post_data['start_date'],
                        'end_date'   => $post_data['end_date'],
                        'remarks1'   => $post_data['remarks1'],
                        'dealer_id'  => $post_data['dealer'],
                        'customer_id'=> $post_data['customer'],
                        'city'       => $post_data['city']
                    );
        $result_check = $this->Calendar_model->checkVisitAvailability($dataArr);
        if($result_check)
        {
            $data['response'] = 'Visit has already been planned!';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        $this->db->trans_begin();
        $dataArr['created_by'] = $post_data['user_id'];
        $dataArr['created_time'] = date('Y-m-d H:i:s');
        $visit_id = $this->Common_model->insert_data('visit',$dataArr);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Visit has been planned successfully!';
            echo json_encode($data);
            header("HTTP/1.1 201 Created"); exit;
        }

    }

    public function visit()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $searchParams = array(
                'leadId'    => $post_data['lead_number'],
                'customer'  => $post_data['customer'],
                'startDate' => $post_data['startDate'],
                'endDate'   => $post_data['endDate']
            );
        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords(); 
        $data['visitSearch'] = $this->Calendar_model->visitResults($searchParams, $config['per_page'], $current_offset);
        $this->session->sess_destroy();
        echo json_encode($data);

    }

    public function editvisit()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        // echo '<pre>'; print_r($post_data);die;
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $_SESSION['role_id'] = $post_data['role_id'];
        $visit_id = $post_data['visit_id'];
        $where = array('visit_id' => $visit_id);

        $data['visitEdit'] = $this->Common_model->get_data_row('visit', $where);
        $data['customer'] = $this->Calendar_model->get_lead_customer($data['visitEdit']['lead_id']);
        
        $data['dealerList'] = $this->quote_model->getDistributors();

        $data['purpose'] = $this->Common_model->get_data('visit_purpose',array());
        echo json_encode($data);
    }

    public function updatevisit()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);;
        $visit_id = $post_data['visit_id'];
        $lead_id = $post_data['lead_id'];
        $dataArr = array
                    (
                        'lead_id'    => $lead_id,
                        'visit_id'   => $visit_id,
                        'purpose_id' => $post_data['purpose'],
                        'start_date' => $post_data['start_date'],
                        'end_date'   => $post_data['end_date'],
                        'remarks1'   => $post_data['remarks1'],
                        'dealer_id'  => $post_data['dealer'],
                        'customer_id'=> $post_data['customer'],
                        'city'       => $post_data['city']
                    );
        $result_check = $this->Calendar_model->checkVisitAvailability($dataArr);
        if($result_check)
        {
            $data['response'] = 'Visit has already been planned!';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        $this->db->trans_begin();        
        $dataArr['modified_by'] = $post_data['user_id'];
        $dataArr['modified_time'] = date('Y-m-d H:i:s');
        $where = array('visit_id' => $visit_id);
        // $vplan = $this->Common_model->get_data('visit', $where);
		// 		if($vplan[0]['start_date'] != $dataArr['start_date']){
		// 			$dataArr['status'] = 3;
		// 		}
        $this->Common_model->update_data('visit',$dataArr, $where);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Visit has been Updated successfully!';
            echo json_encode($data);
            header("HTTP/1.1 201 Created"); exit;
        }


    }

    public function getreportees()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['reportees'] = $post_data['reportees'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $val = @trim($post_data['name']);
        $level = 0;
        $data = $this->ajax_model->getReporteesWithUser($val, $level);
        $this->session->sess_destroy();
        echo json_encode($data);
    }


    public function viewCalendar()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $user_id = $post_data['user_id'];
        $reportees = $post_data['reportees'];
        if($reportees!='')
        {
            $where = $reportees;
        }
        else
        {
            $where = $user_id;
        }
        $data['visitCalendarDetails'] = $this->Calendar_model->visitCalendarDetails($where);
        $data['demoCalendarDetails'] = $this->Calendar_model->demoCalendarDetails($where);
        echo json_encode($data);
    }

    public function deleteVisit()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $visit_id = $post_data['visit_id'];
        $where = array('visit_id' => $visit_id);
        $dataArr = array('status' => 5);
        $this->db->trans_begin();
        $this->Common_model->update_data('visit',$dataArr, $where);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Visit has been De-Activated successfully!';
            echo json_encode($data);
            header("HTTP/1.1 201 Created"); exit;
        }

    }


    public function activateVisit()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $visit_id = $post_data['visit_id'];
        $where = array('visit_id' => $visit_id);
        $results = $this->Common_model->get_data_row('visit', $where);
        $data_res = array('visit_id' => '','lead_id'=>$results['lead_id'],'start_date'=>$results['start_date'],'end_date'=>$results['end_date'],'customer_id'=> $results['customer_id'],'dealer_id'=> $results['dealer_id'],'city'=> $results['city']);
        // echo '<pre>'; print_r($data_res);die;
        $result_check = $this->Calendar_model->checkVisitAvailability($data_res);
        if($result_check)
        {
            $data['response'] = 'Visit has already been planned!';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        $dataArr = array('status' => 1);
        $this->db->trans_begin();
        $this->Common_model->update_data('visit',$dataArr, $where);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Visit has been Activated successfully!';
            echo json_encode($data);
            header("HTTP/1.1 201 Created"); exit;
        }

    }


    public function update_visitFeedback()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $visit_id = $post_data['visit_id'];
        $where = array('visit_id'=>$visit_id);
        $data_arr = array('remarks2'      => $post_data['remarks2'],
                           'status'       => $post_data['status'],
                           'modified_by'   => $post_data['user_id'],
                           'modified_time' => date('Y-m-d H:i:s'));

        $this->db->trans_begin();
        $this->Common_model->update_data('visit',$data_arr,$where);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Visit Feedback has been updated successfully!';
            echo json_encode($data);
            header("HTTP/1.1 201 Created"); exit;
        }
    }

    public function addDemo()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $_SESSION['role_id'] = $post_data['role_id'];
        $leads = $this->Calendar_model->getLeadDetails(1);
        $lead_arr = array();
        foreach ($leads as $key=>$lead) 
        {
            $lead_arr[$key]['lead_id'] = $lead['lead_id'];
            $lead_arr[$key]['name'] = "Lead ID - ".$lead['lead_number']." (".$lead['CustomerName'].")";
        }
        $data['leads'] = $lead_arr;
        $this->session->sess_destroy();
        echo json_encode($data); 

    }

    public function getOpportunity()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $lead_id = $post_data['lead_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $results = $this->Calendar_model->getOpportunity($lead_id);
        $opportunities = array();
        foreach ($results as $key=>$value) 
        {
            $opportunities[]= array('opportunity_id'=>$key,'name'=>$value);
        }
        $lead_detail = $this->Calendar_model->getLeadDetail($lead_id);
		$data = array('opportunities' => $opportunities,
		'nameofinstitute' => trim($lead_detail['nameofistitue']),
		'contactdetail' => trim($lead_detail['contactdetails']),
		'address' => trim($lead_detail['address'])
		);
        $this->session->sess_destroy();
        echo json_encode($data);
    }

    public function getDemo()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $opportunity_id = @$post_data['opportunity_id'];
        $results = $this->Calendar_model->getDemo($opportunity_id);
        $demo = array();
        foreach ($results as $key => $value) {
            $demo[]= array('demo_id'=>$key,'name'=>$value);
            $product = $value;
		}
		$opportunity_detail = $this->Common_model->get_data_row('opportunity',array('opportunity_id'=>$opportunity_id));
		$key_makers_detail = $this->Common_model->get_data_row('contact',array('contact_id'=>$opportunity_detail['decision_maker1']));
		$data = array(
			'demo' => $demo,
		    'key_makers' => trim($key_makers_detail['first_name']),
			'unit_details_with_specific_model' => $product
		);
        $this->session->sess_destroy();
        echo json_encode($data);
    }

    public function demoAdd()
    {   
                    if ($this->input->post('nature_of_demo') != '' ) {
                        $dataArr = array();
                        if($this->input->post('lead_id') !=''){
                            $dataArr['lead_id'] = $this->input->post('lead_id');
                        }
                        if($this->input->post('opportunity_id') !=''){
                        $dataArr['opportunity_id'] = $this->input->post('opportunity_id');
                        $dataArr['product_id'] = $this->Common_model->get_value('opportunity_product', array('opportunity_id' => $this->input->post('opportunity_id')), 'product_id');
                        }
                        if($this->input->post('product_category_id') !=''){
                        $dataArr['product_category_id'] = $this->input->post('product_category_id');
                        }
                        if($this->input->post('name_of_units_demonstrated') !=''){
                        $dataArr['name_of_units_demonstrated'] = $this->input->post('name_of_units_demonstrated');
                        }
                        if($this->input->post('nature_of_demo') !=''){
                        $dataArr['nature_of_demo'] = $this->input->post('nature_of_demo');
                        }
                        if($this->input->post('demo_machine') !=''){
                        $dataArr['demo_machine'] = $this->input->post('demo_machine');
                        $dataArr['demo_product_id'] = $this->input->post('demo_machine');
                        }
                        if($this->input->post('start_date') !=''){
                        $dataArr['start_date'] = $this->input->post('start_date');
                        }
                        if($this->input->post('end_date') !=''){
                        $dataArr['end_date'] = $this->input->post('end_date');
                        }
                        if($this->input->post('region') !=''){
                        $dataArr['region'] = $this->input->post('region');
                        }
                        if($this->input->post('requesting_employee_name') !=''){
                        $dataArr['requesting_employee_name'] = $this->input->post('requesting_employee_name');
                        }
                        if($this->input->post('date_of_installation') !=''){
                        $dataArr['date_of_installation'] = $this->input->post('date_of_installation');
                        }
                        if($this->input->post('installed_by') !=''){
                        $dataArr['installed_by'] = $this->input->post('installed_by');
                        }
                        if($this->input->post('name_units_installed') !=''){
                        $dataArr['name_units_installed'] = $this->input->post('name_units_installed');
                        }
                        if($this->input->post('unit_details_with_specific_model') !=''){
                        $dataArr['unit_details_with_specific_model'] = $this->input->post('unit_details_with_specific_model');
                        }
                        if($this->input->post('competition_info_configuration') !=''){
                        $dataArr['competition_info_configuration'] = $this->input->post('competition_info_configuration');
                        }
                        if($this->input->post('no_interactions_end_users') !=''){
                        $dataArr['no_interactions_end_users'] = $this->input->post('no_interactions_end_users');
                        }
                        if($this->input->post('name_of_institute') !=''){
                        $dataArr['name_of_institute'] = $this->input->post('name_of_institute');
                        }
                        if($this->input->post('contact_detail') !=''){
                        $dataArr['contact_detail'] = $this->input->post('contact_detail');
                        }
                        if($this->input->post('name_of_contact_institute') !=''){
                        $dataArr['name_of_contact_institute'] = $this->input->post('name_of_contact_institute');
                        }
                        if($this->input->post('key_decision_makers') !=''){
                        $dataArr['key_decision_makers'] = $this->input->post('key_decision_makers');
                        }
                        if($this->input->post('event_details') !=''){
                        $dataArr['event_details'] = $this->input->post('event_details');
                        }
                        if($this->input->post('serial_number') !=''){
                        $dataArr['serial_number'] = $this->input->post('serial_number');
                        }
                        if($this->input->post('unit_details_with_specific_model') !=''){
                        $dataArr['unit_details_with_specific_model'] = $this->input->post('unit_details_with_specific_model');
                         }
                        if($this->input->post('customer_complaint_future_prospect') !=''){
                        $dataArr['customer_complaint_future_prospect'] = $this->input->post('customer_complaint_future_prospect');
                        }
                        if($this->input->post('customer_complaint_future_prospect_details') !=''){
                        $dataArr['customer_complaint_future_prospect_details'] = $this->input->post('customer_complaint_future_prospect_details');
                        }
                        //file upload
                        if (count($_FILES['attach_demo_request_form']['name']) > 0) {
                        $config['upload_path']   = "./uploads/demo_image/";
                        $config['allowed_types'] = 'jpg|pdf';
                        $config['max_size']      = 2000000;
                        $config['overwrite'] = true;
                        $this->load->library('upload');
            
                        $i = 0;
                        foreach ($_FILES['attach_demo_request_form'] as $key => $value) {
                            if (!empty($_FILES['attach_demo_request_form']['name'][$i])) {
                                $image_parts = pathinfo($_FILES['attach_demo_request_form']['name'][$i]);
                                $image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
                                $image_type = $image_parts['extension'];
                                $filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;
            
                                $_FILES['userfile']['name'] = $filename;
                                $_FILES['userfile']['type'] = $_FILES['attach_demo_request_form']['type'][$i];
                                $_FILES['userfile']['tmp_name'] = $_FILES['attach_demo_request_form']['tmp_name'][$i];
                                $_FILES['userfile']['error'] = $_FILES['attach_demo_request_form']['error'][$i];
                                $_FILES['userfile']['size'] = $_FILES['attach_demo_request_form']['size'][$i];
                                $config['file_name']   = $_FILES['userfile']['name'];
            
                                $this->upload->initialize($config);
                                $this->upload->do_upload('userfile');
            
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
                        if (count($_FILES['attach_demo_letter']['name']) > 0) {
                        $config['upload_path']   = './uploads/demo_image';
                        $config['allowed_types'] = 'jpg|pdf';
                        $config['max_size']      = 2000000;
                        $config['overwrite'] = true;
                        $this->load->library('upload');
            
                        $i = 0;
                        foreach ($_FILES['attach_demo_letter'] as $key => $value) {
                            if (!empty($_FILES['attach_demo_letter']['name'][$i])) {
                                $image_parts = pathinfo($_FILES['attach_demo_letter']['name'][$i]);
                                $image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
                                $image_type = $image_parts['extension'];
                                $filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;
            
                                $_FILES['userfile_letter']['name'] = $filename;
                                $_FILES['userfile_letter']['type'] = $_FILES['attach_demo_letter']['type'][$i];
                                $_FILES['userfile_letter']['tmp_name'] = $_FILES['attach_demo_letter']['tmp_name'][$i];
                                $_FILES['userfile_letter']['error'] = $_FILES['attach_demo_letter']['error'][$i];
                                $_FILES['userfile_letter']['size'] = $_FILES['attach_demo_letter']['size'][$i];
                                $config['file_name']   = $_FILES['userfile_letter']['name'];
            
                                $this->upload->initialize($config);
                                $this->upload->do_upload('userfile_letter');
            
                                //file name and file path
                                $letter_presale_priority_filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
                                $letter_presale_priority_filename1[] = $filename;
            
                                $dataArr['letter_file_path'] = json_encode($letter_presale_priority_filepath);
                                $dataArr['letter_file_name'] = json_encode($letter_presale_priority_filename1);
                            }
                            $i++;
                        }
                        }
                        if($this->input->post('nature_of_demo') == 'pre_sale' || $this->input->post('nature_of_demo') == 'pre_sale_priority' || $this->input->post('nature_of_demo') == 'marketing'){
                        $result_check = $this->Calendar_model->checkDemoAvailability($dataArr);
                            if($result_check != 0 )
                            {
                                $data['response'] = 'Demo has already been booked!';
                                echo json_encode($data);
                                header("Status: 400 Bad Request",true,400); exit;
                            }
                        }
                        $dataArr['created_by'] = $this->input->post('user_id');
                        $dataArr['created_time'] = date('Y-m-d H:i:s');
                        $demo_id = $this->Common_model->insert_data('demo',$dataArr);
                        send_mail_demo_details($demo_id);
                        $data['response'] = 'Demo has been planned successfully!';
                        echo json_encode($data);
                        header("HTTP/1.1 201 Created"); exit;
                    }
                    else{
                        $data['response'] = 'Please Enter Mandatory Fields';
                        echo json_encode($data);
                        header("Status: 400 Bad Request",true,400); exit;
                    }

    }

    public function demo()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $searchParams = array(
                'opportunityId' => $post_data['opportunity_number'],
                'customer'      => $post_data['customer'],
                'startDate'     => $post_data['startDate'],
                'endDate'       => $post_data['endDate']
            );
        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords(); 
        $data['demoSearch'] = $this->Calendar_model->demoResults($searchParams, $config['per_page'], $current_offset);
        $this->session->sess_destroy();
        echo json_encode($data);

    }

    public function editdemo()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $demo_id = $post_data['demo_id'];
        $opportunity_id = $post_data['opportunity_id'];
        $where = array('demo_id' => $demo_id);
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['user_id'] = $post_data['user_id'];
        $data['demoEdit'] = $this->Common_model->get_data_row('demo', $where);
        $lead_id = $this->Common_model->get_value('opportunity',array('opportunity_id'=>$opportunity_id),'lead_id');
        $data['customer'] = $this->Calendar_model->get_lead_customer($lead_id);
        $data['opportunity'] = $this->Calendar_model->getOpportunity_for_edit_demo($lead_id,$opportunity_id);
        $demo_machine = $this->Calendar_model->getDemoname_for_api($data['demoEdit']['demo_product_id']);
        $data['opportunity']['demo_machine'] = $demo_machine['demoProduct'];
        $this->session->sess_destroy();
        echo json_encode($data);
    }

    public function updatedemo()
    {   
    
        if ($this->input->post('demo_id') != '' || $this->input->post('nature_of_demo')) {
            $demo_id = $this->input->post('demo_id');
            $demo_details = $this->Common_model->get_data_row('demo',array('demo_id'=>$demo_id,'start_date'=>$this->input->post('start_date'),'end_date'=>$this->input->post('end_date')));
            $dataArr = array();
            if($this->input->post('lead_id') !=''){
                $dataArr['lead_id'] = $this->input->post('lead_id');
            }
            if($this->input->post('opportunity_id') !=''){
            $dataArr['opportunity_id'] = $this->input->post('opportunity_id');
            $dataArr['product_id'] = $this->Common_model->get_value('opportunity_product', array('opportunity_id' => $this->input->post('opportunity_id')), 'product_id');
            }
            if($this->input->post('product_category_id') !=''){
                $dataArr['product_category_id'] = $this->input->post('product_category_id');
            }
            if($this->input->post('name_of_units_demonstrated') !=''){
            $dataArr['name_of_units_demonstrated'] = $this->input->post('name_of_units_demonstrated');
            }
            if($this->input->post('nature_of_demo') !=''){
            $dataArr['nature_of_demo'] = $this->input->post('nature_of_demo');
            }
            if($this->input->post('demo_machine') !=''){
            $dataArr['demo_machine'] = $this->input->post('demo_machine');
            $dataArr['demo_product_id'] = $this->input->post('demo_machine');
            }
            if($this->input->post('start_date') !=''){
            $dataArr['start_date'] = $this->input->post('start_date');
            }
            if($this->input->post('end_date') !=''){
            $dataArr['end_date'] = $this->input->post('end_date');
            }
            if($this->input->post('region') !=''){
                $dataArr['region'] = $this->input->post('region');
            }
            if($this->input->post('requesting_employee_name') !=''){
                $dataArr['requesting_employee_name'] = $this->input->post('requesting_employee_name');
            }
            if($this->input->post('date_of_installation') !=''){
            $dataArr['date_of_installation'] = $this->input->post('date_of_installation');
            }
            if($this->input->post('installed_by') !=''){
            $dataArr['installed_by'] = $this->input->post('installed_by');
            }
            if($this->input->post('name_units_installed') !=''){
            $dataArr['name_units_installed'] = $this->input->post('name_units_installed');
            }
            if($this->input->post('unit_details_with_specific_model') !=''){
            $dataArr['unit_details_with_specific_model'] = $this->input->post('unit_details_with_specific_model');
            }
            if($this->input->post('competition_info_configuration') !=''){
            $dataArr['competition_info_configuration'] = $this->input->post('competition_info_configuration');
            }
            if($this->input->post('no_interactions_end_users') !=''){
            $dataArr['no_interactions_end_users'] = $this->input->post('no_interactions_end_users');
            }
            if($this->input->post('name_of_institute') !=''){
            $dataArr['name_of_institute'] = $this->input->post('name_of_institute');
            }
            if($this->input->post('contact_detail') !=''){
            $dataArr['contact_detail'] = $this->input->post('contact_detail');
            }
            if($this->input->post('name_of_contact_institute') !=''){
            $dataArr['name_of_contact_institute'] = $this->input->post('name_of_contact_institute');
            }
            if($this->input->post('key_decision_makers') !=''){
            $dataArr['key_decision_makers'] = $this->input->post('key_decision_makers');
            }
            if($this->input->post('event_details') !=''){
            $dataArr['event_details'] = $this->input->post('event_details');
            }
            if($this->input->post('serial_number') !=''){
            $dataArr['serial_number'] = $this->input->post('serial_number');
            }
            if($this->input->post('unit_details_with_specific_model') !=''){
            $dataArr['unit_details_with_specific_model'] = $this->input->post('unit_details_with_specific_model');
             }
            if($this->input->post('customer_complaint_future_prospect') !=''){
            $dataArr['customer_complaint_future_prospect'] = $this->input->post('customer_complaint_future_prospect');
            }
            if($this->input->post('customer_complaint_future_prospect_details') !=''){
            $dataArr['customer_complaint_future_prospect_details'] = $this->input->post('customer_complaint_future_prospect_details');
            }
            //file upload
            if (count($_FILES['attach_demo_request_form']['name']) > 0) {
            $config['upload_path']   = "./uploads/demo_image/";
            $config['allowed_types'] = 'jpg|pdf';
            $config['max_size']      = 2000000;
            $config['overwrite'] = true;
            $this->load->library('upload');

            $i = 0;
            foreach ($_FILES['attach_demo_request_form'] as $key => $value) {
                if (!empty($_FILES['attach_demo_request_form']['name'][$i])) {
                    $image_parts = pathinfo($_FILES['attach_demo_request_form']['name'][$i]);
                    $image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
                    $image_type = $image_parts['extension'];
                    $filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

                    $_FILES['userfile']['name'] = $filename;
                    $_FILES['userfile']['type'] = $_FILES['attach_demo_request_form']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $_FILES['attach_demo_request_form']['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $_FILES['attach_demo_request_form']['error'][$i];
                    $_FILES['userfile']['size'] = $_FILES['attach_demo_request_form']['size'][$i];
                    $config['file_name']   = $_FILES['userfile']['name'];

                    $this->upload->initialize($config);
                    $this->upload->do_upload('userfile');

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
            if (count($_FILES['attach_demo_letter']['name']) > 0) {
            $config['upload_path']   = './uploads/demo_image';
            $config['allowed_types'] = 'jpg|pdf';
            $config['max_size']      = 2000000;
            $config['overwrite'] = true;
            $this->load->library('upload');

            $i = 0;
            foreach ($_FILES['attach_demo_letter'] as $key => $value) {
                if (!empty($_FILES['attach_demo_letter']['name'][$i])) {
                    $image_parts = pathinfo($_FILES['attach_demo_letter']['name'][$i]);
                    $image_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($image_parts['filename']));
                    $image_type = $image_parts['extension'];
                    $filename =  str_replace('.', '_', $image_name) . time() . '.' . $image_type;

                    $_FILES['userfile_letter']['name'] = $filename;
                    $_FILES['userfile_letter']['type'] = $_FILES['attach_demo_letter']['type'][$i];
                    $_FILES['userfile_letter']['tmp_name'] = $_FILES['attach_demo_letter']['tmp_name'][$i];
                    $_FILES['userfile_letter']['error'] = $_FILES['attach_demo_letter']['error'][$i];
                    $_FILES['userfile_letter']['size'] = $_FILES['attach_demo_letter']['size'][$i];
                    $config['file_name']   = $_FILES['userfile_letter']['name'];

                    $this->upload->initialize($config);
                    $this->upload->do_upload('userfile_letter');

                    //file name and file path
                    $letter_presale_priority_filepath[] = SITE_URL1 . 'uploads/demo_image/' . $filename;
                    $letter_presale_priority_filename1[] = $filename;

                    $dataArr['letter_file_path'] = json_encode($letter_presale_priority_filepath);
                    $dataArr['letter_file_name'] = json_encode($letter_presale_priority_filename1);
                }
                $i++;
            }
            }   
                if($demo_details){
                    if($this->input->post('nature_of_demo') == 'pre_sale' || $this->input->post('nature_of_demo') == 'pre_sale_priority' || $this->input->post('nature_of_demo') == 'marketing'){
                        $result_check = $this->Calendar_model->checkDemoAvailability($dataArr);
                        if($result_check != 0)
                        {
                            $data['response'] = 'Demo has already been planned!';
                            echo json_encode($data);
                            header("Status: 400 Bad Request",true,400); exit;
                        }
                    }
                }
            $this->db->trans_begin();
            $dataArr['modified_by'] = $post_data['user_id'];
            $dataArr['modified_time'] = date('Y-m-d H:i:s');
            $where = array('demo_id' => $demo_id);
            $this->Common_model->update_data('demo',$dataArr, array('demo_id' => $demo_id));

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $data['response'] = 'Something Went Wrong';
                echo json_encode($data);
                header("Status: 400 Bad Request",true,400); exit;
            }
            else
            {
                $this->db->trans_commit();
                $data['response'] = 'Demo has been updated successfully!';
                echo json_encode($data);
                header("HTTP/1.1 201 Created"); exit;
            }
        }else{
                $data['response'] = 'Please Give Demo ID or Enter Mandatory Fields';
                echo json_encode($data);
                header("Status: 400 Bad Request",true,400); exit;
        }

    }

    public function deleteDemo()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $demo_id = $post_data['demo_id'];
        $where = array('demo_id' => $demo_id);
        $dataArr = array('status' => 2);
        $this->db->trans_begin();
        $this->Common_model->update_data('demo',$dataArr, $where);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Demo has been De-Activated successfully!';
            echo json_encode($data);
            header("HTTP/1.1 201 Created"); exit;
        }

    }


    public function activateDemo()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $demo_id = $post_data['demo_id'];
        $where = array('demo_id' => $demo_id);
        $results = $this->Common_model->get_data_row('demo', $where);
        $data_res = array('demo_id' => '','demo_product_id'=>$results['demo_product_id'],'start_date'=>$results['start_date'],'end_date'=>$results['end_date']);
        $result_check = $this->Calendar_model->checkDemoAvailability($data_res);
        if($result_check)
        {
            $data['response'] = 'Demo has already been planned!';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        $dataArr = array('status' => 1);
        $this->db->trans_begin();
        $this->Common_model->update_data('demo',$dataArr, $where);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Demo has been Activated successfully!';
            echo json_encode($data);
            header("HTTP/1.1 201 Created"); exit;
        }

    }


    public function update_demoFeedback()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $demo_id = $post_data['demo_id'];
        $where = array('demo_id'=>$demo_id);
        $data_arr = array('remarks2'      => $post_data['remarks2'],
                      'modified_by'   => $post_data['user_id'],
                      'modified_time' => date('Y-m-d H:i:s'));

        $this->db->trans_begin();
        $this->Common_model->update_data('demo',$data_arr,$where);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400); exit;
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Demo Feedback has been updated successfully!';
            echo json_encode($data);
            header("HTTP/1.1 201 Created"); exit;
        }
    }

}