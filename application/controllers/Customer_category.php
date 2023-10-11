<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Customer_category extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Customer_category_model');
       
        
    }

    public function customer_category() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Customer Category";
        $data['nestedView']['cur_page'] = 'customer_category';
        $data['nestedView']['parent_page'] = 'customer_category';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Customer Category';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Customer Category', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('search', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'category_name' => $this->input->post('category_name', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'category_name' => $this->session->userdata('category_name'),
                );
            } else {
                $searchParams = array(
                    'category_name' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'customer_category/';
        # Total Records
        $config['total_rows'] = $this->Customer_category_model->categoryTotalRows($searchParams);

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
        $data['categories'] = $this->Customer_category_model->categoryResults($searchParams, $config['per_page'], $current_offset);
        //print_r($data['categorySearch']);die();
        $data['displayList'] = 1;
        
        $this->load->view('customer_category/categoryView', $data);
    }

    public function addcustomer_category() 
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Customer Category";
        $data['nestedView']['cur_page'] = 'customer_category';
        $data['nestedView']['parent_page'] = 'customer_category';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_category.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Customer Category';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Customer Category', 'class' => 'active', 'url' => SITE_URL . 'customer_category');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add Customer Category', 'class' => 'active', 'url' => '');


        
        $data['flg'] = 1;
        $data['val'] = 0;
        # Load page with all shop details
        $this->load->view('customer_category/categoryView', $data);
    }

    public function editcustomer_category($encoded_id) 
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Customer Category";
        $data['nestedView']['cur_page'] = 'customer_category';
        $data['nestedView']['parent_page'] = 'customer_category';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_category.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Customer Category';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Customer Category', 'class' => 'active', 'url' => SITE_URL . 'customer_category');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Customer Category', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);
            $where = array('category_id' => $value);
            $data['categoryEdit'] = $this->Common_model->get_data('customer_category', $where);
        }
        
        $data['flg'] = 1;
        $data['val'] = 1;
        # Load page with all shop details
        $this->load->view('customer_category/categoryView', $data);
    }

    public function deletecustomer_category($encoded_id) {
        $category_id = @icrm_decode($encoded_id);
        $where = array('category_id' => $category_id);
        $dataArr = array('status' => 2);
        $this->Common_model->update_data('customer_category', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Customer Category has been De-Activated successfully!
							 </div>');
        redirect(SITE_URL . 'customer_category');
    }

    public function activatecustomer_category($encoded_id) 
    {
        $category_id = @icrm_decode($encoded_id);
        $where = array('category_id' => $category_id);
        $dataArr = array('status' => 1);
        $this->Common_model->update_data('customer_category', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Customer Category has been Activated successfully!
							 </div>');
        redirect(SITE_URL . 'customer_category');
    }

    public function customer_categoryAdd() 
    {
        if ($this->input->post('submitcategory') != "") 
        {
            //print_r($_POST);
            $name=$this->input->post('name');
            $category_id = @icrm_decode($this->input->post('category_id',TRUE));


            $flag=$this->Customer_category_model->is_categoryNameExist($name,$category_id);
            if($flag == 0)
            {
                $dataArr = array(
                    'name' => $name
                        );
                if($category_id == "")
                {
                    $dataArr['created_by'] = $this->session->userdata('user_id');
                    $dataArr['created_time'] = date('Y-m-d H:i:s');
                    $dataArr['company_id'] = $this->session->userdata('company');
                        //Insert
                   
                    $this->Common_model->insert_data('customer_category', $dataArr);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Customer Category has been added successfully!
                                                                 </div>');
                }
                else
                {	
                    $dataArr['modified_by'] = $this->session->userdata('user_id');
                    $dataArr['modified_time'] = date('Y-m-d H:i:s');
                    $where = array('category_id' => $category_id);

                        //Update
                    $this->Common_model->update_data('customer_category',$dataArr, $where);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Customer Category has been updated successfully!
                                                                 </div>');


                }
                //$dataArr = $_POST[];

                redirect(SITE_URL . 'customer_category');
            }
            else
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> Customer Category Name already Exist!
                    </div>');
                 redirect(SITE_URL . 'customer_category');                                                             
            }
        }
        else
        {
           
            redirect(SITE_URL . 'customer_category');
            
        }
    }

    public function downloadcustomer_category() {
        if ($this->input->post('downloadcategory') != '') {

            $searchParams = array('category_name' => $this->input->post('category_name', TRUE));
            $categories = $this->Customer_category_model->categoryDetails($searchParams);

            $header = '';
            $data = '';
            $titles = array('S.NO', 'Category');
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
            if (count($categories) > 0) {

                foreach ($categories as $category) {
                    $data.='<tr>';
                    $data.='<td align="center">' . $j . '</td>';
                    $data.='<td>' . $category['name'] . '</td>';
                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'customer_Category_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }

    public function sub_category() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Sub Category";
        $data['nestedView']['cur_page'] = 'sub_category';
        $data['nestedView']['parent_page'] = 'sub_category';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Sub Category';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Sub Category', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('search', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'sub_category' => $this->input->post('sub_category', TRUE),
                'category'     => $this->input->post('category',TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'sub_category' => $this->session->userdata('sub_category'),
                    'category'  => $this->session->userdata('category'),
                );
            } else {
                $searchParams = array(
                    'sub_category' => '',
                    'category'     => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'sub_category/';
        # Total Records
        $config['total_rows'] = $this->Customer_category_model->subTotalRows($searchParams);

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
        $data['categories'] = $this->Customer_category_model->subResults($searchParams, $config['per_page'], $current_offset);
        //echo $this->db->last_query(); exit();
        //print_r($data['categories']);die();
        $data['displayList'] = 1;
        
        $this->load->view('customer_category/subcategoryView', $data);
    }

    public function addsub_category() 
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Sub Category";
        $data['nestedView']['cur_page'] = 'sub_category';
        $data['nestedView']['parent_page'] = 'sub_category';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_category.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Sub Category';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Sub Category', 'class' => 'active', 'url' => SITE_URL . 'sub_category');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add Sub Category', 'class' => 'active', 'url' => '');

        $data['customer_category'] = $this->Common_model->get_data('customer_category',array('status'=>1,'company_id'=>$_SESSION['company']));

        
        $data['flg'] = 1;
        $data['val'] = 0;
        # Load page with all shop details
        $this->load->view('customer_category/subcategoryView', $data);
    }

    public function editsub_category($encoded_id) 
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Sub Category";
        $data['nestedView']['cur_page'] = 'sub_category';
        $data['nestedView']['parent_page'] = 'sub_category';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_category.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Sub Category';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Sub Category', 'class' => 'active', 'url' => SITE_URL . 'sub_category');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Sub Category', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);
            $where = array('category_sub_id' => $value);
            $data['categoryEdit'] = $this->Common_model->get_data('customer_sub_category', $where);
            $data['category_id'] = $this->Common_model->get_value('customer_category_details',array('category_sub_id'=>$value),'category_id');
            //echo $data['category_id']; exit();
        }
        $data['customer_category'] = $this->Common_model->get_data('customer_category',array('status'=>1,'company_id'=>$_SESSION['company']));
        $data['flg'] = 1;
        $data['val'] = 1;
        # Load page with all shop details
        $this->load->view('customer_category/subcategoryView', $data);
    }

    public function deletesub_category($encoded_id) {
        $sub_category_id = @icrm_decode($encoded_id);
        $where = array('category_sub_id' => $sub_category_id);
        $dataArr = array('status' => 2);
        $this->Common_model->update_data('customer_sub_category', $dataArr, $where);
        $this->Common_model->update_data('customer_category_details', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <div class="icon"><i class="fa fa-check"></i></div>
                                <strong>Success!</strong> Sub Category has been De-Activated successfully!
                             </div>');
        redirect(SITE_URL . 'sub_category');
    }

    public function activatesub_category($encoded_id) 
    {
        $sub_category_id = @icrm_decode($encoded_id);
        $where = array('category_sub_id' => $sub_category_id);
        $dataArr = array('status' => 1);
        $this->Common_model->update_data('customer_sub_category', $dataArr, $where);
        $this->Common_model->update_data('customer_category_details', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <div class="icon"><i class="fa fa-check"></i></div>
                                <strong>Success!</strong> Sub Category has been Activated successfully!
                             </div>');
        redirect(SITE_URL . 'sub_category');
    }

    public function sub_categoryAdd() 
    {
        if ($this->input->post('submitcategory') != "") 
        {
            //print_r($_POST);
            $name=$this->input->post('name');
            $sub_category_id = @icrm_decode($this->input->post('sub_category_id',TRUE));

            $category_id = $this->input->post('category_id');
            $flag=$this->Customer_category_model->is_subcategoryNameExist($name,$sub_category_id,$category_id);
            //echo $this->db->last_query(); exit;
            if($flag == 0)
            {
                $dataArr = array(
                    'name' => $name
                        );
                if($sub_category_id == "")
                {
                    $dataArr['created_by'] = $this->session->userdata('user_id');
                    $dataArr['created_time'] = date('Y-m-d H:i:s');

                  
                        //Insert
                   
                    $customer_sub_id = $this->Common_model->insert_data('customer_sub_category', $dataArr);
                    $sub_arr = array(
                                    'category_id'      => $category_id,
                                    'category_sub_id'  => $customer_sub_id,
                                    'weight'           => 20
                                    );
                    $customer_sub_id = $this->Common_model->insert_data('customer_category_details', $sub_arr);
                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Sub Category has been added successfully!
                                                                 </div>');
                }
                else
                {   
                    $dataArr['modified_by'] = $this->session->userdata('user_id');
                    $dataArr['modified_time'] = date('Y-m-d H:i:s');
                    $where = array('category_sub_id' => $sub_category_id);

                        //Update
                    $this->Common_model->update_data('customer_sub_category',$dataArr, $where);

                    $where1 = array('category_sub_id'=>$sub_category_id);
                    $data1 = array('status'=>2);
                    $this->Common_model->update_data('customer_category_details',$data1, $where1);

                    $this->Customer_category_model->insert_update($category_id,$sub_category_id);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Sub Category has been updated successfully!
                                                                 </div>');


                }
                //$dataArr = $_POST[];

                redirect(SITE_URL . 'sub_category');
            }
            else
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> Sub Category Name already Exist!
                    </div>');
                 redirect(SITE_URL . 'sub_category');                                                             
            }
        }
        else
        {
           
            redirect(SITE_URL . 'sub_category');
            
        }
    }

    public function downloadsub_category() {
        if ($this->input->post('downloadcategory') != '') {

            $searchParams = array(
                                'sub_category' => $this->input->post('sub_category', TRUE),
                                'category'     => $this->input->post('category',TRUE)
                                 );
            $categories = $this->Customer_category_model->subDetails($searchParams);

            $header = '';
            $data = '';
            $titles = array('S.NO','Category', 'Sub Category');
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
            if (count($categories) > 0) {

                foreach ($categories as $category) {
                    $data.='<tr>';
                    $data.='<td align="center">' . $j . '</td>';
                    $data.='<td>' . $category['customer'] . '</td>';
                    $data.='<td>' . $category['name'] . '</td>';
                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'sub_Category_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }

}
