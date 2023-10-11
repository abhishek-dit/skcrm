<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Customer extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdminModel");
        $this->load->model("customer_model");
        $this->load->model("common_model");
        $this->load->model("Ajax_m");
    }

    public function Customer() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Customer";
        $data['nestedView']['cur_page'] = 'customer';
        $data['nestedView']['parent_page'] = 'customer';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Customer';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Customer', 'class' => 'active', 'url' => '');

        # Search Functionality
        # Search Functionality
        $psearch = $this->input->post('searchCustomer', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'customerName' => $this->input->post('s_customerName', TRUE),
                's_location' => $this->input->post('s_location', TRUE),
                'department' => $this->input->post('s_department', TRUE),
                'category_id' => $this->input->post('s_category_id', TRUE),
                'category_sub_id' => $this->input->post('s_category_sub_id', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'customerName' => $this->session->userdata('customerName'),
                    's_location' => $this->session->userdata('s_location'),
                    'department' => $this->session->userdata('department'),
                    'category_id' => $this->session->userdata('category_id'),
                    'category_sub_id' => $this->session->userdata('category_sub_id')
                );
            } else {
                $searchParams = array(
                    'customerName' => '',
                    's_location' => '',
                    'department' => '',
                    'category_id' => '',
                    'category_sub_id' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['search_data'] = $searchParams;


        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'customer/';
        # Total Records
        $config['total_rows'] = $this->customer_model->customerTotalRows($searchParams);

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
        $customerSearch = array();
        $customerSearch = $this->customer_model->get_details($current_offset, $config['per_page'], $searchParams);

        # Loading the data array to send to View
        $data['customerSearch'] = $customerSearch['resArray'];
        $data['count'] = $customerSearch['count'];
        //$data['companyName'] = $companyName;
        $data['categories'] = $this->customer_model->get_category_drop_down();
        $data['search_data'] = $searchParams;
        $data['displayList'] = 1;
        $role = $this->session->userdata('role_id');
        $editCheck = 1;
        if ($role == 1 || $role == 2 || $role == 3)
            $editCheck = 0;
        //print_r($search_params ); 
        if (isset($data['search_data']['category_id']) && $data['search_data']['category_id'] != NULL) {
            $data['s_sub_categories'] = $this->customer_model->get_sub_category_dropdown($data['search_data']['category_id']);
        }
        $data['s_loc'] = $this->customer_model->getSearchLocation(@$searchParams['s_location']);
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
        $data['editCheck'] = $editCheck;
       
        $this->load->view('customer/customerView', $data);
    }

    public function addCustomer() {
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
                $page = 'customer';
                $label = 'Manage Customer';
                break;
        }
        //$page = ($parent == 1)?'newLead':'customer';
        //$label = ($parent == 1)?'Create a new Lead':'Manage Customer';
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Customer";
        $data['nestedView']['cur_page'] = $page;
        $data['nestedView']['parent_page'] = $page;

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Add New Customer';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => $label, 'class' => 'active', 'url' => SITE_URL . $page);
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add New', 'class' => 'active', 'url' => '');

        $data['categories'] = $this->customer_model->get_category_drop_down();
        //$data['search_data'] = $search_params;
        //print_r($search_params ); 
        if (isset($data['search_data']['category_id']) && $data['search_data']['category_id'] != NULL) {
            $data['s_sub_categories'] = $this->customer_model->get_sub_category_dropdown($data['search_data']['category_id']);
            // print_r($data['s_sub_categories'] ); die();
        }
        $data['flg'] = 1;
        $data['val'] = 0;
        $data['parent'] = $parent;
        $data['editCheck'] = 0;
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
        $data['cust_beds'] = $this->customer_model->get_customer_beds();
        $data['cust_speciality'] = $this->customer_model->get_customer_speciality();
        // echo "<pre>";print_r($data['cust_speciality']);die;
        # Load page with all shop details
        $this->load->view('customer/customerView', $data);
    }

    public function editCustomer($encoded_id) {
        $role = $this->session->userdata('role_id');
        $data['val'] = 2;
        $editCheck = 1;
        if ($role == 1 || $role == 2 || $role == 3 || $role==14)
        {
            $editCheck = 0;
            $data['val'] = 1;
        }
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Customer";
        $data['nestedView']['cur_page'] = 'customer';
        $data['nestedView']['parent_page'] = 'customer';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        if($editCheck==1)
        {
            $data['nestedView']['breadCrumbTite'] = 'View Customer';;
        }
        else
        {
            $data['nestedView']['breadCrumbTite'] = 'Edit Customer';
        }
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        if($this->session->userdata('role_id')!=14)
            $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Customer', 'class' => 'active', 'url' => SITE_URL . 'customer');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Customer', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);

            $where = array('customer_id' => $value);
            $data['customer_data'] = $this->Common_model->get_data('customer', $where);
            $data['edit_customer_data'] = $data['customer_data'][0];
            //print_r(explode('-', $data['customer_data'][0]['telephone'])); exit;
            $data['categories'] = $this->customer_model->get_category_drop_down();
            $data['sub_categories'] = $this->customer_model->get_sub_category_dropdown($data['customer_data'][0]['category_id']);
            $data['city'] = $this->customer_model->getLocation($value);
        }
        $data['flg'] = 1;
        $data['parent'] = 0;
        $data['editCheck'] = $editCheck;
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
        $data['customer_installation'] = $this->customer_model->getInstallations($value);
        $data['cust_beds'] = $this->customer_model->get_customer_beds();
        $data['cust_speciality']=$this->customer_model->get_customer_speciality();
        $data['cust_special'] = $this->common_model->get_value('customer',array('customer_id'=>$value),'customer_speciality_id');
        //$ar = explode(', ', $data['cust_special']);
        //print_r( $ar);die;
        # Load page with all shop details
        $this->load->view('customer/customerView', $data);
    }

    public function deleteCustomer($encoded_id) {
        //echo 'hi';
        $customer_id = @icrm_decode($encoded_id);
        $where = array('customer_id' => $customer_id);
        $dataArr = array('status' => 2);
        $this->Common_model->update_data('customer', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Customer has been De-Activated successfully!
								 </div>');
        if($this->session->userdata('role_id')!=14)
        {
            redirect(SITE_URL . 'customer');
        }    
        else
        {
            redirect(SITE_URL . 'approveCustomers');
        }
    }

    public function activateCustomer($encoded_id) {
        $customer_id = @icrm_decode($encoded_id);
        $where = array('customer_id' => $customer_id);
        $dataArr = array('status' => 4);
        $this->Common_model->update_data('customer', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Customer has been Activated successfully!
								 </div>');
        redirect(SITE_URL . 'customer');
    }

    public function customerAdd() {
        if ($this->input->post('submitCustomer') != "") {
           // $get_SE_list = $this->Common_model->get_data('user',array('status'=>1,'role_id'=>4,'company_id'=>$this->session->userdata('company')),array('email_id','first_name','user_id'));
            $cutomer_id = $this->input->post('customer_id');
            $cutomer_id = @icrm_decode($this->input->post('customer_id'));
            $name = $this->input->post('name');
            $flag=$this->Ajax_m->is_customerNameExist($name,$cutomer_id);
            // echo "<pre>"; print_r($_POST); 
            // echo "<pre>"; print_r(json_encode($_POST['speciality_id']));
            // exit;
            //print_r($_POST['edit_customer_data']); exit;
            if($flag==0)
            {
                if ($cutomer_id == "") 
                {
                    $isd1 = $this->input->post('isd1');
                    $isd2 = $this->input->post('isd2');
                    $isd3 = $this->input->post('isd3');
                    $salutation = $this->input->post('salutation');
                    if ($salutation == 0)
                        $salutation = "";
                    if ($this->input->post('telephone') != '') {
                        $telephone_no = $isd1 . "-" . $this->input->post('telephone');
                    } else {
                        $telephone_no = NULL;
                    }
                    if ($this->input->post('mobile') != '') {
                        $mobile_no = $isd2 . "-" . $this->input->post('mobile');
                    } else {
                        $mobile_no = NULL;
                    }
                    if ($this->input->post('fax') != '') {
                        $fax_no = $isd3 . "-" . $this->input->post('fax');
                    } else {
                        $fax_no = NULL;
                    }
                    $name = $this->input->post('name');
                    $dataArr = array(
                        'name'            => $this->input->post('name'),
                        'name1'           => '',
                        'category_id'     => $this->input->post('category_id'),
                        'category_sub_id' => $this->input->post('category_sub_id'),
                        'email'           => $this->input->post('email'),
                        'fax'             => $fax_no,
                        'mobile'          => $mobile_no,
                        'telephone'       => $telephone_no,
                        'website'         => $this->input->post('website'),
                        'address1'        => $this->input->post('address1'),
                        'address2'        => $this->input->post('address2'),
                        'address3'        => $this->input->post('address3'),
                        'landmark'        => $this->input->post('landmark'),
                        'remarks2'        => $this->input->post('customer_code'),
                        'pincode'         => ($this->input->post('pincode')!='')?$this->input->post('pincode'):NULL,
                        'pan'             => $this->input->post('pan'),
                        'tan'             => $this->input->post('tan'),
                        'tin'             => $this->input->post('tin'),
                        'company_id'      => $this->session->userdata('company'),
                        'status'          => 4,
                        'gst'             => $this->input->post('gst'),
                        'created_by'      => $_SESSION['user_id'],
                        'created_time'    => date('Y-m-d H:i:s'),
                        'longitude'       => $this->input->post('longitude'),
                        'latitude'        => $this->input->post('latitude'),
                        'customer_bed_id' => $this->input->post('bed_id'),
                        'customer_speciality_id' => json_encode($_POST['speciality_id']));


                    //Insert
                    $customer_id = $this->Common_model->insert_data('customer', $dataArr);
                    $this->add_customer_installation($customer_id);
                   
                   $location_details = array(
                        'customer_id' => $customer_id,
                        'location_id' => $this->input->post('city_id'));
                    $customer_id = $this->Common_model->insert_data('customer_location', $location_details);

                    
                    $mails = array();
                    $subject = "Approval For Customer";
                    $body = "Hi, <br><br>";
                    //$body.=\n;
                    $body.= "New Customer <strong>".$name."</strong> Is added Into application.";
                    $body.="<br><br>";
                    $body.= 'Please Approve The Customer';
                    $body.="<br><br><br><br><br><br>";
                    $body.="<p>Regards,<br>iCRM,<br>SkanRay</p>";
                    /*foreach($get_SE_list as $key=>$value)
                    {
                        $mails[$key] = $value['email_id'];
                    }*/
                    $to = "crm@skanray.com";
                    //$cc = "crm@skanray.com";
                    if(count($to)>0)
                    {
                        send_email($to,$subject,$body);
                    }
                    $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><div class="icon"><i class="fa fa-check"></i></div><strong>Success!</strong> Customer has been Added successfully!</div>');
                    $parent = $this->input->post('parent');
                    if ($parent == 1)
                        redirect(SITE_URL . 'newLead');
                    else if ($parent == 2)
                        redirect(SITE_URL . 'assignLeads');
                    else
                        redirect(SITE_URL . 'customer');
                } 
                else 
                {
                    $arr = $this->Common_model->get_data_row('customer',array('customer_id'=>$cutomer_id),array('name','category_id','category_sub_id','email','fax','mobile','telephone','website','address1','address2','address3','landmark','pincode','pan','tan','tin','gst','longitude','latitude','customer_bed_id','customer_speciality_id'));
                    $check = 0;
                    $diff_arr = array();
                    $isd1 = $this->input->post('isd1');
                    $isd2 = $this->input->post('isd2');
                    $isd3 = $this->input->post('isd3');
                    if ($this->input->post('telephone') != '') {
                        $telephone_no = $isd1 . "-" . $this->input->post('telephone');
                    } else {
                        $telephone_no = NULL;
                    }
                    if ($this->input->post('mobile') != '') {
                        $mobile_no = $isd2 . "-" . $this->input->post('mobile');
                    } else {
                        $mobile_no = NULL;
                    }
                    if ($this->input->post('fax') != '') {
                        $fax_no = $isd3 . "-" . $this->input->post('fax');
                    } else {
                        $fax_no = NULL;
                    }
                    if($this->session->userdata('role_id')==14)
                    {
                        $name = $this->input->post('name');
                    }
                    else
                    {
                        $name = $this->input->post('edit_name');
                    }
                    $dataArr = array(
                        'name'            => $this->input->post('name'),
                        'category_id'     => $this->input->post('category_id'),
                        'category_sub_id' => $this->input->post('category_sub_id'),
                        'email'           => $this->input->post('email'),
                        'fax'             => $fax_no,
                        'mobile'          => $mobile_no,
                        'telephone'       => $telephone_no,
                        'website'         => $this->input->post('website'),
                        'address1'        => $this->input->post('address1'),
                        'address2'        => $this->input->post('address2'),
                        'address3'        => $this->input->post('address3'),
                        'landmark'        => $this->input->post('landmark'),
                        //'modified_by'     => $_SESSION['user_id'],
                        'pincode'         => ($this->input->post('pincode')!='')?$this->input->post('pincode'):NULL,
                        'pan'             => $this->input->post('pan'),
                        'tan'             => $this->input->post('tan'),
                        'tin'             => $this->input->post('tin'),
                        'gst'             => $this->input->post('gst'),
                        //'status'          => 4,
                        //'modified_time'   => date('Y-m-d H:i:s'),
                        'longitude'       => $this->input->post('longitude'),
                        'latitude'        => $this->input->post('latitude'),
                        'customer_bed_id' => $this->input->post('bed_id'),
                        'customer_speciality_id' => json_encode($_POST['speciality_id']));
                    $diff_arr = array_diff_assoc($arr,$dataArr);
                    if(count($diff_arr)==1 || count($diff_arr)==2)
                    {
                        if(array_key_exists('latitude', $diff_arr))
                        {
                            $check++;
                        }
                        elseif(array_key_exists('longitude', $diff_arr))
                        {
                            $check++;
                        }
                        else
                        {
                            $check=0;
                        }
                    }
                    if($check>0)
                    {
                        $dataArr['status'] = 1;
                    }
                    else
                    {
                        $dataArr['status'] = 4;
                    }
                    $dataArr['modified_by'] = $_SESSION['user_id'];
                    $dataArr['modified_time'] = date('Y-m-d H:i:s');
                    
                    $where = array('customer_id' => $cutomer_id);
                    if ($this->input->post('customer_code') != '')
                        $dataArr['remarks2'] = $this->input->post('customer_code');
                    //Update
                    $this->Common_model->update_data('customer', $dataArr, $where);

                    $location_details = array(
                        'location_id' => $this->input->post('city_id'));
                    $this->Common_model->update_data('customer_location', $location_details, $where);
                   
                    $this->add_customer_installation($cutomer_id);
                    //$mails = array();
                    $subject = "Approval For Customer";
                    $body = "Hi, <br><br>";
                    //$body.=\n;
                    $body.= "Customer <strong>".$name."</strong> Got Updated In the Application.";
                    $body.="<br><br>";
                    $body.= 'Please Approve The Customer';
                    $body.="<br><br><br><br><br><br>";
                    $body.="<p>Regards,<br>iCRM,<br>SkanRay</p>";
                    /*foreach($get_SE_list as $key=>$value)
                    {
                        $mails[$key] = $value['email_id'];
                    }*/
                    $to="crm@skanray.com";
                   // $cc = "crm@skanray.com";
                    if(count($to)>0 && $check==0)
                    {
                        send_email($to,$subject,$body);
                    }
                    if($this->session->userdata('role_id')!=14)
                    {
                        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Success!</strong> Customer has been updated successfully!
                                         </div>');
                        redirect(SITE_URL . 'customer');
                    }
                    else
                    {
                        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Success!</strong> Customer has been updated successfully!
                                         </div>');
                        redirect(SITE_URL . 'approveCustomers');
                    }
                    
                }
            }
            else
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> Customer already Exist!
                    </div>');
                 redirect(SITE_URL . 'customer');                                                             
            }
            
        }
    }
    function get_sub_category() {
        $cat_id = $this->input->post('cat_id');
        $this->customer_model->get_sub_category_dropdown_ajax($cat_id);
    }

    public function downloadCustomer() {
        ini_set('memory_limit', '256M');
        if ($this->input->post('downloadCustomer') != '') {
            $search_params = array(
                'customerName' => $this->input->post('s_customerName'),
                's_location' => $this->input->post('s_location'),
                'department' => $this->input->post('s_department'),
                'category_id' => $this->input->post('s_category_id'),
                'category_sub_id' => $this->input->post('s_category_sub_id')
            );
            $customers = $this->customer_model->get_download_details($search_params);
            //echo $this->db->last_query();exit;
            $header = '';
            $data = '';
            $titles = array('S.NO', 'Customer Name', 'SAP code', 'Department', 'Category', 'Sub Category', 'Email', 'Telephone', 'Fax', 'Mobile', 'Website', 'City', 'Address1', 'Address2', 'Address3', 'Landmark', 'Pincode', 'Pan', 'TIN', 'TAN', 'status', 'Modified By', 'Modified Time');
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
                    $data.='<td>' . $customer['name'] . '</td>';
                    $data.='<td>' . $customer['remarks2'] . '</td>';
                    //$data.='<td>' . $customer['name1'] . '</td>';
                    //$data.='<td>' . $customer['salutation'] . '</td>';
                    $data.='<td>' . $customer['department'] . '</td>';
                    $data.='<td>' . $customer['category_name'] . '</td>';
                    $data.='<td>' . $customer['category_sub_name'] . '</td>';
                    $data.='<td>' . $customer['email'] . '</td>';
                    $data.='<td>' . $customer['telephone'] . '</td>';
                    $data.='<td>' . $customer['fax'] . '</td>';
                    $data.='<td>' . $customer['mobile'] . '</td>';
                    $data.='<td>' . $customer['website'] . '</td>';
                    $data.='<td>' . $customer['location'] . '</td>';
                    $data.='<td>' . $customer['address1'] . '</td>';
                    $data.='<td>' . $customer['address2'] . '</td>';
                    $data.='<td>' . $customer['address3'] . '</td>';
                    $data.='<td>' . $customer['landmark'] . '</td>';
                    $data.='<td>' . $customer['pincode'] . '</td>';
                    $data.='<td>' . $customer['pan'] . '</td>';
                    $data.='<td>' . $customer['tan'] . '</td>';
                    $data.='<td>' . $customer['tin'] . '</td>';
                    $data.='<td>' . statusCheck($customer['status']) . '</td>';
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
            $xlFile = 'customer_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }

    public function customer_installation_add() 
    {
      
        $is_submit = $this->input->post('customer_install');
        
        if ($is_submit == 'Customer Installation') {
            
            $customer_id = @icrm_decode($this->input->post('id'));
            $status=$this->add_customer_installation($customer_id);
            if($status ==1){
            $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Customer Installation Base has been Added successfully!
									 </div>');
            }else{
                $this->session->set_flashdata('response', '<div class="alert alert-danger alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Error!</strong> Data has not been provided!
									 </div>'); 
            }
            
        }
       
            $this->load->library('user_agent');
            redirect($this->agent->referrer());
    }

    function add_customer_installation($customer_id = 0) {
        if ($customer_id != 0) {
            //customer installation
           
            $competitors = $this->input->post('competitors');
            $product_model = $this->input->post('product_model');
            $quantity = $this->input->post('quantity');
            $make = $this->input->post('make');
            $year_of_purchase = $this->input->post('year_of_purchase');
            $replacement_year = $this->input->post('replacement_year');
           
            if (count($competitors) > 0 && $competitors[0]!=NULL) {
                $i = 0;
                foreach ($competitors as $v) {
                    if($competitors[$i] != '')
                    {
                        $dataArr = array(
                            'customer_id' => $customer_id,
                            'competitors' => $competitors[$i],
                            'product_model' => $product_model[$i],
                            'quantity' => ($quantity[$i] != '')?$quantity[$i]:NULL,
                            'make' => $make[$i],
                            'year_of_purchase' => ($year_of_purchase[$i] != '')?$year_of_purchase[$i]:NULL,
                            'replacement_year' => ($replacement_year[$i] != '')?$replacement_year[$i]:NULL,
                            'created_by' => $_SESSION['user_id'],
                            'created_time' => date('Y-m-d H:i:s'));


                        //print_r($dataArr); die();
                        //Insert
                        $this->Common_model->insert_data('customer_installed', $dataArr);
                    }
                    $i++;
                }
                return 1;
            }else{
                
                return 0;
            } 
           
        }
    }

    public function approveCustomers()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Approve Customer";
        $data['nestedView']['cur_page'] = 'approveCustomers';
        $data['nestedView']['parent_page'] = 'approveCustomers';
        
        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
        
        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Approve Customers';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'Approve Customers','class'=>'active','url'=>'');

        # Search Functionality
        $psearch=$this->input->post('searchcustomer', TRUE);
        if($psearch!='') {
        $searchParams=array(
                      'customer'=>$this->input->post('s_customerName', TRUE),
                      's_location' => $this->input->post('s_location', TRUE),
                              );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'customer'   =>$this->session->userdata('customer'),
                      's_location' => $this->session->userdata('s_location'),
                              );
            }
            else {
                $searchParams=array(
                      'customer'   => '',
                      's_location' => '',
                                  );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;
        
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'approveCustomers/'; 
        # Total Records
        $config['total_rows'] = $this->customer_model->customerAppTotalRows($searchParams);
        
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
        $data['searchResults'] = $this->customer_model->customerAppRsults($searchParams,$config['per_page'], $current_offset);
        $data['s_loc'] = $this->customer_model->getSearchLocation(@$searchParams['s_location']);

        $this->load->view('customer/approveCustomerview', $data);
    }
    public function approveCustomer($encoded_id)
    {
        $this->db->trans_begin();
        $customer_id=@icrm_decode($encoded_id);
        $customer_details = $this->Common_model->get_data_row('customer',array('customer_id'=>$customer_id));
        $where = array('customer_id' => $customer_id);
        $dataArr = array('status' => 1, 'modified_by' => $this->session->userdata('user_id'), 'modified_time' => date('Y-m-d H:i:s'));
        $this->Common_model->update_data('customer',$dataArr, $where);
        $get_SE_id = $this->Common_model->get_value('customer',array('customer_id'=>$customer_id),'created_by');
        $get_SE_email = $this->Common_model->get_value('user',array('user_id'=>$get_SE_id),'email_id');
        //print_r($get_SE_email);die;
        $mails = array();
        $cc = "crm@skanray.com";
        $subject = "Customer Is Approved";
        $body = "Hi, <br><br>";
        $body.= "Customer <strong>".$customer_details['name']."</strong> Is Approved.";
        $body.="<br><br><br><br><br><br>";
        $body.="<p>Regards,<br>iCRM,<br>SkanRay</p>";
        $to=$get_SE_email;
        send_email($to,$subject, $body);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Error!</strong> There was a problem while approving a Lead!
                                 </div>');
            redirect(SITE_URL.'approveCustomers');
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Customer had been Approved successfully!
                                 </div>');
            redirect(SITE_URL.'approveCustomers');
        }   
    }

    public function rejectCustomer($encoded_id)
    {
        $this->db->trans_begin();
        $customer_id=@icrm_decode($encoded_id);
        $customer_details = $this->Common_model->get_data_row('customer',array('customer_id'=>$customer_id));
        $where = array('customer_id' => $customer_id);
        $dataArr = array('status' => 3, 'modified_by' => $this->session->userdata('user_id'), 'modified_time' => date('Y-m-d H:i:s'));
        $this->Common_model->update_data('customer',$dataArr, $where);
        $mails = array();
        $get_SE_id = $this->Common_model->get_value('customer',array('customer_id'=>$customer_id),'created_by');
        $get_SE_email = $this->Common_model->get_value('user',array('user_id'=>$get_SE_id),'email_id');
        $cc = "crm@skanray.com";
        $subject = "Customer Is Rejected";
        $body = "Hi, <br><br>";
        $body.= "Customer <strong>".$customer_details['name']."</strong> Is Rejected.";
        $body.="<br><br><br><br><br><br>";
        $body.="<p>Regards,<br>iCRM,<br>SkanRay</p>";
        $to=$get_SE_email;
        send_email($to,$subject, $body);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Error!</strong> There was a problem while approving a Lead!
                                 </div>');
            redirect(SITE_URL.'approveCustomers');
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Customer had been Rejected successfully!
                                 </div>');
            redirect(SITE_URL.'approveCustomers');
        }   
    }
    public function getCnoteGeneratedCustomerList() 
    {
            ini_set('memory_limit', '256M');
            $customers = $this->customer_model->getCnoteGeneratedCustomerData();
            $header = '';
            $data = '';
            $titles = array('S.NO', 'Customer Name', 'Customer code');
            $data = '<table border="1">';
            $data.='<thead>';
            $data.='<tr>';
            foreach ($titles as $title) 
            {
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
                    $data.='<td>' . $customer['name'] . '</td>';
                    $data.='<td>' . $customer['customer_code'] . '</td>';
                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles)) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'customer_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
       
    }

}
