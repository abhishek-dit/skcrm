<?php 
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Headers: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_api extends CI_Controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Common_model");
		$this->load->model("Contact_model");
		$this->load->model("ajax_model");
        $this->load->model("campaign_model");
	}

	public function addContact()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $company_id = $post_data['company_id'];
		$speciality = $this->Common_model->get_dropdown("speciality", 'speciality_id', "name", array('status' => 1,'company_id'=>$company_id));
		foreach($speciality as $key=>$value)
		{
			$specialityArr[] = array('id'=>$key,'name'=>$value);
		}
		$isd = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
		foreach($isd as $key=>$value)
		{
			$isd_list[] = array('id'=>$key,'name'=>$value);
		}
		$data=array('speciality'=>$specialityArr,'isd'=>$isd_list);
		
		echo json_encode($data);
	}

	public function contactAdd()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
		$isd1 = $post_data['isd1'];
        $isd2 = $post_data['isd2'];
        $isd3 = $post_data['isd3'];
		$telephone_no = $isd1 . "-" . $post_data['telephone'];
        $mobile_no = $isd2 . "-" . $post_data['mobile_no'];
        $fax_no = $isd3 . "-" . $post_data['fax'];
        $this->db->trans_begin();
        $dataArr = array(
            'salutation'    => $post_data['salutation'],
            'first_name'    => $post_data['first_name'],
            'last_name'     => $post_data['last_name'],
            'speciality_id' => $post_data['speciality_id'],
            'telephone'     => $telephone_no,
            'created_by'    => $post_data['user_id'],
            'created_time'  => date('Y-m-d H:i:s'),
            'mobile_no'     => $mobile_no,
            'fax'           => $fax_no,
            'email'         => $post_data['email'],
            'address1'      => $post_data['address1'],
            'address2'      => $post_data['address2'],
            'created_by'    => $post_data['user_id']
        );

        //Insert
        $contact_id = $this->Common_model->insert_data('contact', $dataArr);

        $customer_id = $post_data['customer_id'];
        $location_id = $this->Common_model->get_value('customer_location', array('customer_id' => $customer_id), 'location_id');
        $dataArr2 = array(
            "customer_id" => $customer_id,
            "location_id" => $location_id,
            "contact_id"  => $contact_id
        );
        $this->Common_model->insert_data('customer_location_contact', $dataArr2);
        if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = 'Something Went Wrong';
			echo json_encode($data);
			header("Status: 400 Bad Request",true,400);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = 'Contact Has Been Added';
			echo json_encode($data);
			header("HTTP/1.1 201 Created");
		}
	}

	public function contact()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $searchParams['c_speciality'] = $post_data['speciality_id'];
        $searchParams['contactName'] = $post_data['contact_name'];
        $searchParams['customer'] = $post_data['customer_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords(); 

        $data['contactSearch'] = $this->Contact_model->get_contact_details($current_offset, $config['per_page'], $searchParams);
        $this->session->sess_destroy();
        echo json_encode($data);
	}

	public function updateContact()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $contact_id = $post_data['contact_id'];
        $isd2 = $post_data['isd2'];
        $isd3 = $post_data['isd3'];
        $mobile_no = $isd2 . "-" . $post_data['mobile_no'];
        $fax_no = $isd3 . "-" . $post_data['fax'];
        $this->db->trans_begin();
        $dataArr = array(
        	'mobile_no'     => $mobile_no,
            'fax'           => $fax_no,
            'modified_by'   => $post_data['user_id'],
            'modified_time' => date('Y-m-d H:i:s')
        );
        $this->Common_model->update_data('contact',$dataArr,array("contact_id"=>$contact_id));
        if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = 'Something Went Wrong';
			echo json_encode($data);
			header("Status: 400 Bad Request",true,400);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = 'Contact Has Been Updated';
			echo json_encode($data);
			header("HTTP/1.1 201 Created");
		}
	}

	public function editContact()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $contact_id = $post_data['contact_id'];
        $contact_data = $this->Contact_model->getContactData($contact_id);
        foreach($contact_data as $key=>$value)
        {
        	$contact_arr[$key] = $value;
        	$customer_details = $this->Contact_model->getCustomerDetails($value['contact_id']);
        	$contact_arr[$key]['customerDetails'] = $customer_details['customer'];
        	$contact_arr[$key]['specaility_name'] = $this->Common_model->get_value('speciality',array('speciality_id'=>$value['speciality_id']),'name');
        }
        $data['contact_data'] = $contact_arr;
        echo json_encode($data);

	}

    public function viewCampaignDocuments()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['role_id'] = $post_data['role_id'];
        $searchParams['campaignDocumentName'] = $post_data['document_name'];
        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords();
        $campaignDocumentSearch = $this->campaign_model->get_documents_details_for_roles($current_offset, $config['per_page'], @$searchParams);
        $data['campaignDocumentSearch'] = @$campaignDocumentSearch['resArray'];
        $data['url'] =  SITE_URL1;
        $this->session->sess_destroy(); 
        echo json_encode($data);
    }

}