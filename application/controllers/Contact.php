<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Contact extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdminModel");
        $this->load->model("contact_model");
         $this->load->model("common_model");
    }

    /**
    * Fetchin Contact Details
     * return: Contact List(array)
    **/
    public function Contact() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Contact";
        $data['nestedView']['cur_page'] = 'contact';
        $data['nestedView']['parent_page'] = 'contact';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Contact';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Contact', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('searchContact', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'contactName' => $this->input->post('contactName', TRUE),
                'c_speciality' => $this->input->post('c_speciality', TRUE),
                'customer' => $this->input->post('customer', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'contactName' => $this->session->userdata('contactName'),
                    'c_speciality' => $this->session->userdata('c_speciality'),
                    'customer' => $this->session->userdata('customer')
                );
            } else {
                $searchParams = array(
                    'contactName' => '',
                    'c_speciality' => '',
                    'customer' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;




        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'contact/';
        # Total Records
        $config['total_rows'] = $this->contact_model->contactTotalRows($searchParams);

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
        $contactSearch = array();
        $contactSearch = $this->contact_model->get_details($current_offset, $config['per_page'], @$searchParams);
        $data['s_cus'] = $this->contact_model->getSearchCustomer(@$searchParams['customer']);

        $data['contact_data'] = array();


        //$data['SpecialityInfo'] = $this->contact_model->getSpecialityInfo();
        $data['SpecialityInfo'] = array('' => 'Select Speciality') + $this->Common_model->get_dropdown('speciality', 'speciality_id', 'name', array('company_id'=>$this->session->userdata('company')));

        # Loading the data array to send to View
        $data['contactSearch'] = @$contactSearch['resArray'];
        $data['count'] = @$contactSearch['count'];
        $data['displayList'] = 1;
        $role = $this->session->userdata('role_id');
        $editCheck = 1;
        if ($role == 1 || $role == 2 || $role == 3)
            $editCheck = 0;

        $data['editCheck'] = $editCheck;
        $data['parent'] = 0;
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
        # Load page with all Contact details
        $this->load->view('customer/contactView', $data);
    }
    /**
    * For Adding NewContact
    * return: Customer Details
    **/
   public function addContact() {

        $parent = 0;
        if ($this->input->post('add') != '') {
            if ($this->input->post('add') == 1)
                $parent = 1;
            else
                $parent = 2;
        }
        switch ($parent) {
            case 1:
                $page = 'newLead';
                $label = 'Create a new Lead';
                break;
            case 2:
                $page = 'assignLeads';
                $label = 'Assign Lead';
                break;
            default:
                $page = 'contact';
                $label = 'Manage Contact';
                break;
        }
        //$page = ($parent == 1)?'newLead':'customer';
        //$label = ($parent == 1)?'Create a new Lead':'Manage Customer';
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Contact";
        $data['nestedView']['cur_page'] = $page;
        $data['nestedView']['parent_page'] = $page;

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Add New Contact';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => $label, 'class' => 'active', 'url' => SITE_URL . $page);
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add New', 'class' => 'active', 'url' => '');

        $data['speciality'] = $this->Common_model->get_dropdown("speciality", 'speciality_id', "name", array('status' => 1,'company_id'=>$this->session->userdata('company')));
        $data['flg'] = 1;
        $data['val'] = 0;
        $data['parent'] = $parent;
        $data['editCheck'] = 0;
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
        # Load page with all shop details
        $this->load->view('customer/contactView', $data);
    }
    #Editing Contact Details
    public function editContact($encoded_id) {
        $role = $this->session->userdata('role_id');
        $val = 2;
        $editCheck = 1;
        if ($role == 1 || $role == 2 || $role == 3)
        {
            $editCheck = 0;
            $val = 1;
        }

        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Contact";
        $data['nestedView']['cur_page'] = 'contact';
        $data['nestedView']['parent_page'] = 'contact';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        if($editCheck==1)
        {
            $data['nestedView']['breadCrumbTite'] = 'View Contact';
        }
        else
        {
            $data['nestedView']['breadCrumbTite'] = 'Edit Contact';
        }
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Contact', 'class' => 'active', 'url' => SITE_URL . 'contact');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Contact', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);

            $where = array('contact_id' => $value);
            $data['contact_data'] = $this->contact_model->getContactData($value);
            $data['customerDetails'] = $this->contact_model->getCustomerDetails($value);
            $data['speciality'] = $this->Common_model->get_dropdown("speciality", 'speciality_id', "name", array('status' => 1,'company_id'=>$this->session->userdata('company')));
           
        }
        $data['val'] = $val;
        $data['flg'] = 1;
        $data['parent'] = 0;
        $data['editCheck'] = $editCheck;
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);

        $this->load->view('customer/contactView', $data);
    }
    #Deactivating Contact
    public function deleteContact($encoded_id) {
       
        $customer_id = @icrm_decode($encoded_id);
        $where = array('contact_id' => $customer_id);
        $dataArr = array('status' => 2);
        $this->Common_model->update_data('contact', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Contact has been De-Activated successfully!
								 </div>');
        redirect(SITE_URL . 'contact');
    }
    #Activating Contact
    public function activateContact($encoded_id) {
        $customer_id = @icrm_decode($encoded_id);
        $where = array('contact_id' => $customer_id);
        $dataArr = array('status' => 1);
        $this->Common_model->update_data('contact', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Contact has been Activated successfully!
								 </div>');
        redirect(SITE_URL . 'contact');
    }
    #Inserting Contact Details
    public function contactAdd() {

        if ($this->input->post('submitContact') != "") {

            $contact_id = $this->input->post('contact_id');
            //$dataArr = $_POST[];
            $contact_id = $this->global_functions->decode_icrm($this->input->post('contact_id'));
            if ($contact_id == "") {
                $isd1 = $this->input->post('isd1');
                $isd2 = $this->input->post('isd2');
                $isd3 = $this->input->post('isd3');


                $telephone_no = $isd1 . "-" . $this->input->post('telephone');
                $mobile_no = $isd2 . "-" . $this->input->post('mobile_no');
                $fax_no = $isd3 . "-" . $this->input->post('fax');
                $dataArr = array(
                    // 'customer_id' => $this->input->post('customer_id'),
                    'salutation'=>$this->input->post('salutation'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'speciality_id' => $this->input->post('speciality_id'),
                    'telephone' => $telephone_no,
                    'mobile_no' => $mobile_no,
		     'created_by'=>$this->session->userdata('user_id'),
		     'created_time'=> date('Y-m-d H:i:s'),
                    'fax' => $fax_no,
                    'email' => $this->input->post('email'),
                    'address1' => $this->input->post('address1'),
                    'address2' => $this->input->post('address2'),
                    'pincode' => $this->input->post('pincode')
                );
                 //Insert
                $contact_id = $this->Common_model->insert_data('contact', $dataArr);

                $customer_id = $this->input->post('customer_id');
                $location_id = $this->Common_model->get_value('customer_location', array('customer_id' => $customer_id), 'location_id');
                $dataArr2 = array(
                    "customer_id" => $customer_id,
                    "location_id" => $location_id,
                    "contact_id" => $contact_id
                );
                $this->Common_model->insert_data('customer_location_contact', $dataArr2);
                $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Contact has been Added successfully!
									 </div>');
                $parent = $this->input->post('parent');
                if ($parent == 1)
                    redirect(SITE_URL . 'newLead');
                else if ($parent == 2)
                    redirect(SITE_URL . 'assignLeads');
                else
                    redirect(SITE_URL . 'contact');
            } else {

                $isd1 = $this->input->post('isd1');
                $isd2 = $this->input->post('isd2');
                $isd3 = $this->input->post('isd3');
                $telephone_no = $isd1 . "-" . $this->input->post('telephone');
                $mobile_no = $isd2 . "-" . $this->input->post('mobile_no');
                $fax_no = $isd3 . "-" . $this->input->post('fax');
                $dataArr = array(
                    'salutation'=>$this->input->post('salutation'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'speciality_id' => $this->input->post('speciality_id'),
                    'telephone' => $telephone_no,
                    'mobile_no' => $mobile_no,
		     'modified_by'=> $this->session->userdata('user_id'),
                     'modified_time'=> date('Y-m-d H:i:s'),
                    'fax' => $fax_no,
                    'email' => $this->input->post('email'),
                    'address1' => $this->input->post('address1'),
                    'address2' => $this->input->post('address2'),
                    'pincode' => $this->input->post('pincode')
                );

                $where = array('contact_id' => $contact_id);
                //Update
                $this->Common_model->update_data('contact', $dataArr, $where);
                /*                $customer_id = $this->input->post('customer_id');
                  $location_id = $this->Common_model->get_value('customer_location', array('customer_id' => $customer_id), 'location_id');
                  $dataArr2 = array(
                  "customer_id" => $customer_id,
                  "location_id" => $location_id,
                  "contact_id" => $contact_id
                  );
                  $this->Common_model->update_data('customer_location_contact', $dataArr2, $where);
                 */ $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Contact has been updated successfully!
									 </div>');
                redirect(SITE_URL . 'contact');
            }
        }
    }
    #Fetch Sub Category Details through ajax call
    function get_sub_category() {
        $cat_id = $this->input->post('cat_id');
        $this->customer_model->get_sub_category_dropdown_ajax($cat_id);
    }
    #Download Contacts
    public function downloadContact() {
        //print_r($_POST);
        if ($this->input->post('downloadContact') != '') {



            $search_params = array(
                'contactName' => $this->input->post('contactName'),
                'c_speciality' => $this->input->post('c_speciality'),
                'customer' => $this->input->post('customer'),
            );

            $customers = $this->contact_model->get_download_details($search_params);

            $header = '';
            $data = '';
            $titles = array('S.NO', 'First Name', 'Customer', 'Speciality', 'Email ', 'Mobile No','Created By','Crated Time','Modified By','Modified Time');
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
            if (count($customers) > 0) {

                foreach ($customers as $customer) {
                    $data.='<tr>';
                    $data.='<td align="center">' . $j . '</td>';
                    $data.='<td>' . $customer['first_name'] . '</td>';
                    $data.='<td>' . $customer['customer'] . '</td>';
                    $data.='<td>' . $customer['speciality'] . '</td>';
                    $data.='<td>' . $customer['email'] . '</td>';
                    $data.='<td>' . $customer['mobile_no'] . '</td>';
                    $data.='<td>' . getUserName($customer['created_by']) . '</td>';
                    $data.='<td>' . $customer['created_time'] . '</td>';
                    $data.='<td>' . getUserName($customer['modified_by']) . '</td>';
                    $data.='<td>' . $customer['modified_time'] . '</td>';
                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles)) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'contact_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }
#Fetch Customer through ajax call
function get_customer_address(){
        $customer_id=$_POST['id'];
        echo $this->common_model->get_value('customer' ,array('customer_id'=>$customer_id),"address1" );
    }
}
