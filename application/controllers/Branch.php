<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class Branch extends Base_controller {

	public  function __construct() 
	{
        parent::__construct();

		$this->load->model("Branch_model");
	}
    
    /**
    * get Branch List
    * params: $company_id
    * return: $branch(array)
    **/
	public function branch() 
	{   //echo CI_VERSION;exit;
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Branch";
        $data['nestedView']['cur_page'] = 'branch';
        $data['nestedView']['parent_page'] = 'branch';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Branch';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Branch', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('searchbranch', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'branchName' => $this->input->post('branchName', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'branchName' => $this->session->userdata('branchName'),
                );
            } else {
                $searchParams = array(
                    'branchName' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'branch/';
        # Total Records
        $config['total_rows'] = $this->Branch_model->branchTotalRows($searchParams);

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
        $data['branches'] = $this->Branch_model->branchResults($searchParams, $config['per_page'], $current_offset);
        // echo"<pre>";print_r($_SESSION);exit;
        $data['displayList'] = 1;
        //$var = getSuperusermailid(); 
        //echo $var; exit;
        $this->load->view('branch/branchView', $data);
    }
    /**
    * Adding New Branch To Application
    * return: Branch Details
    **/
    public function addbranch() 
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Branch";
        $data['nestedView']['cur_page'] = 'branch';
        $data['nestedView']['parent_page'] = 'branch';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/branch.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Branch';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Branch', 'class' => 'active', 'url' => SITE_URL . 'branch');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add Branch', 'class' => 'active', 'url' => '');

        # Sending Location Hierarchy
        $data['geos'] = $this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>2));
        $data['competitorSelected'] = array();
        $data['flg'] = 1;
        $data['val'] = 0;
        # Load page with all branch details
        $this->load->view('branch/branchView', $data);
    }
    /**
    * Editing Branch
    * return: Fetching Branch previously inserted data
    **/
    public function editbranch($encoded_id) 
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Branch";
        $data['nestedView']['cur_page'] = 'branch';
        $data['nestedView']['parent_page'] = 'branch';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/branch.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Branch';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Branch', 'class' => 'active', 'url' => SITE_URL . 'branch');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Branch', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);
            $where = array('branch_id' => $value);
            $data['branchEdit'] = $this->Common_model->get_data('branch', $where);
            $region = $data['branchEdit'][0]['region_id'];
            $country_id = $this->Common_model->get_value('location',array('location_id'=>$region),'parent_id');
            $geo_id = $this->Common_model->get_value('location',array('location_id'=>$country_id),'parent_id');
            
            $data['geos'] = $this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>2));
            $data['countries'] = $this->Common_model->get_data('location',array('status'=>1,'parent_id'=>$geo_id));
            $data['regions'] = $this->Common_model->get_data('location',array('status'=>1,'parent_id'=>$country_id));
            $data['geo_id'] = $geo_id;
            $data['country_id'] = $country_id;
            $data['region_id'] = $region;

        }
        $data['flg'] = 1;
        $data['val'] = 1;
        # Load page with all Branch details
        $this->load->view('branch/editbranchView', $data);
    }
    /**
    * Deactivating Branch
    * params: $branch_id(int)
    **/
    public function deletebranch($encoded_id) 
    {
        $branch_id = @icrm_decode($encoded_id);
        $where = array('branch_id' => $branch_id);
        $dataArr = array('status' => 2);
        $this->Common_model->update_data('branch', $dataArr, $where);
        
        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Branch has been De-Activated successfully!
							 </div>');
        redirect(SITE_URL . 'branch');
    }

    /**
    * Reactivating Branch
    * params: $branch_id(int)
    **/
    public function activatebranch($encoded_id) 
    {
        $branch_id = @icrm_decode($encoded_id);
        $where = array('branch_id' => $branch_id);
        $dataArr = array('status' => 1);
        $this->Common_model->update_data('branch', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Branch has been Activated successfully!
							 </div>');
        redirect(SITE_URL . 'branch');
    }
    /**
    * Inserting Branch Details to DB
    * return: $branch_details(array)
    **/
    public function branchAdd() 
    {
        if ($this->input->post('submitbranch') != "") 
        {
            
            $name=$this->input->post('name');
            $branch_id = @icrm_decode($this->input->post('branch_id',TRUE));
           
            $flag=$this->Branch_model->is_branchNameExist($name,$branch_id);
            
            if($flag == 0)
            {
                $dataArr = array(
                    'name' => $name,
                    'region_id' => $this->input->post('region'),
                    'status'	=> 1
                        );
                if($branch_id == "")
                {
                    $dataArr['created_by'] = $this->session->userdata('user_id');
                    $dataArr['created_time'] = date('Y-m-d H:i:s');
                    $dataArr['company_id'] = $this->session->userdata('company');
                        //Insert
                    $this->Common_model->insert_data('branch', $dataArr);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Branch has been added successfully!
                                                                 </div>');
                }
                else
                {	
                    $dataArr['modified_by'] = $this->session->userdata('user_id');
                    $dataArr['modified_time'] = date('Y-m-d H:i:s');

                    $where = array('branch_id' => $branch_id);

                        //Update
                    $this->Common_model->update_data('branch',$dataArr, $where);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Branch has been updated successfully!
                                                                 </div>');


                }
                redirect(SITE_URL . 'branch');
            }
            else
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> Branch already Exist!
                    </div>');
                 redirect(SITE_URL . 'branch');                                                             
            }
        }
        else
        {
           
            redirect(SITE_URL . 'branch');
            
        }
    }
    /**
    * Downloading Branch Details in Excel
    * Fetching All details of branch
    **/
    public function downloadbranch() 
    {
        if ($this->input->post('downloadbranch') != '') {

            $searchParams = array('branchName' => $this->input->post('branchName', TRUE));
            $branches = $this->Branch_model->branchDetails($searchParams);

            $header = '';
            $data = '';
            $titles = array('S.NO', 'Branch');
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
            if (count($branches) > 0) {

                foreach ($branches as $branch) {
                    $data.='<tr>';
                    $data.='<td align="center">' . $j . '</td>';
                    $data.='<td>' . $branch['name'] . '</td>';
                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'branch_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }


}