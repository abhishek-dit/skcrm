<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Speciality extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('speciality_model');
        $this->load->model('ajax_m');
        
    }

    public function speciality() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Speciality";
        $data['nestedView']['cur_page'] = 'speciality';
        $data['nestedView']['parent_page'] = 'speciality';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Speciality';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Speciality', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('searchSpeciality', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'specialityName' => $this->input->post('specialityName', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'specialityName' => $this->session->userdata('specialityName'),
                );
            } else {
                $searchParams = array(
                    'specialityName' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;
//        print_r($data['searchParams']);
//        die();
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'speciality/';
        # Total Records
        $config['total_rows'] = $this->speciality_model->specialityTotalRows($searchParams);

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
        $data['specialities'] = $this->speciality_model->specialityResults($searchParams, $config['per_page'], $current_offset);
        //print_r($data['categorySearch']);die();
        $data['displayList'] = 1;
        
        $this->load->view('customer/specialityView', $data);
    }

    public function addSpeciality() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Speciality";
        $data['nestedView']['cur_page'] = 'speciality';
        $data['nestedView']['parent_page'] = 'speciality';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Speciality';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Speciality', 'class' => 'active', 'url' => SITE_URL . 'specialityy');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add Speciality', 'class' => 'active', 'url' => '');


        
        $data['competitorSelected'] = array();
        $data['flg'] = 1;
        $data['val'] = 0;
        # Load page with all shop details
        $this->load->view('customer/specialityView', $data);
    }

    public function editSpeciality($encoded_id) {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Speciality";
        $data['nestedView']['cur_page'] = 'productSpeciality';
        $data['nestedView']['parent_page'] = 'productSpeciality';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Speciality';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Speciality', 'class' => 'active', 'url' => SITE_URL . 'speciality');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Speciality', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);
            $where = array('speciality_id' => $value);
            $data['specialityEdit'] = $this->Common_model->get_data('speciality', $where);
        }
        
        $data['flg'] = 1;
        $data['val'] = 1;
        # Load page with all shop details
        $this->load->view('customer/specialityView', $data);
    }

    public function deleteSpeciality($encoded_id) {
        $speciality_id = @icrm_decode($encoded_id);
        $where = array('speciality_id' => $speciality_id);
        $dataArr = array('status' => 2);
        $this->Common_model->update_data('speciality', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Speciality has been De-Activated successfully!
							 </div>');
        redirect(SITE_URL . 'speciality');
    }

    public function activateSpeciality($encoded_id) {
        $speciality_id = @icrm_decode($encoded_id);
        $where = array('speciality_id' => $speciality_id);
        $dataArr = array('status' => 1);
        $this->Common_model->update_data('speciality', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Speciality has been Activated successfully!
							 </div>');
        redirect(SITE_URL . 'speciality');
    }

    public function specialityAdd() 
    {
        if ($this->input->post('submitSpeciality') != "") 
        {
            //print_r($_POST);
            $name=$this->input->post('name');
            $speciality_id = @icrm_decode($this->input->post('speciality_id',TRUE));

            $flag=$this->ajax_m->is_specialityNameExist($name,$speciality_id);
            if($flag == 0)
            {
                $dataArr = array(
                    'name' => $name
                        );
                if($speciality_id == "")
                {
                    $dataArr['created_by'] = $this->session->userdata('user_id');
                    $dataArr['created_time'] = date('Y-m-d H:i:s');
                    $dataArr['company_id'] = $this->session->userdata('company');
                        //Insert
                    $this->Common_model->insert_data('speciality', $dataArr);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Speciality has been added successfully!
                                                                 </div>');
                }
                else
                {   
                    $dataArr['modified_by'] = $this->session->userdata('user_id');
                    $dataArr['modified_time'] = date('Y-m-d H:i:s');
                    $where = array('speciality_id' => $speciality_id);

                        //Update
                    $this->Common_model->update_data('speciality',$dataArr, $where);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Speciality has been updated successfully!
                                                                 </div>');


                }
                //$dataArr = $_POST[];

                redirect(SITE_URL . 'speciality');
            }
            else
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> Speciality already Exist!
                    </div>');
                 redirect(SITE_URL . 'speciality');                                                             
            }
        }
        else
        {
           
            redirect(SITE_URL . 'speciality');
            
        }
    }

    public function downloadSpeciality() {
        if ($this->input->post('downloadSpeciality') != '') {

            $searchParams = array('specialityName' => $this->input->post('specialityName', TRUE));
            $specialities = $this->speciality_model->specialityDetails($searchParams);

            $header = '';
            $data = '';
            $titles = array('S.NO', 'Speciality');
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
            if (count($specialities) > 0) {

                foreach ($specialities as $speciality) {
                    $data.='<tr>';
                    $data.='<td align="center">' . $j . '</td>';
                    $data.='<td>' . $speciality['name'] . '</td>';
                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'speciality_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }

    

}
