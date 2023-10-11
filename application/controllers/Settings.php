<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';



class Settings extends Base_controller {

    public function __construct() {
        parent::__construct();
         $this->load->model("Common_model");
          $this->load->model("Settings_m");
          $this->load->model("Contract_model");
    }

    public function incentive_settings()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Settings List";
        $data['nestedView']['cur_page'] = 'Settings List';
        $data['nestedView']['parent_page'] = 'Settings List';
        
        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['css_includes'] = array();
        
        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Settings';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Settings','class'=>'active','url'=>'');
        
        # Search Functionality
        $psearch=$this->input->post('search', TRUE);
        if($psearch!='') {
        $searchParams=array(
                            'fy_id'     =>  $this->input->post('fy_id', TRUE),
                            'inc_role'  =>  $this->input->post('role_id',TRUE)
                              );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                                'fy_id'     =>  $this->session->userdata('fy_id'),
                                'inc_role'  =>  $this->session->userdata('inc_role')
                              );
            }
            else {
                $searchParams=array(
                                    'fy_id'     =>  '',
                                    'inc_role'  =>  ''
                                  );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;
        
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'incentive_settings/'; 
        # Total Records
        $config['total_rows'] = $this->Settings_m->settingsTotalRows($searchParams);
        
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
        $data['role_list']=$this->Settings_m->get_roles();
        $data['fy_year']=$this->Common_model->get_data('financial_year',array('status'=>1,'company_id'=>$this->session->userdata('company')));

        $data['incentivelist'] = $this->Settings_m->settingsResults($searchParams,$config['per_page'], $current_offset);

        //print_r($data['categorySearch']);die();
        $data['displayList'] = 1;

        $this->load->view('settings/settings_view', $data);
    }

    public function add_incentive_settings()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Settings";
        $data['nestedView']['cur_page'] = 'settings';
        $data['nestedView']['parent_page'] = 'settings';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Settings';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Settings', 'class' => 'active', 'url' => '');
        
        $data['role']=$this->Settings_m->get_roles();
        $data['flg']=1;
        $data['fy_year']=$this->Common_model->get_data('financial_year',array('status'=>1,'company_id'=>$this->session->userdata('company')));


        $this->load->view('settings/settings_view', $data);
    }

    public function incentive_insert_settings()
    {
        $fy_id=$this->input->post('fy_id',TRUE);
        $role_id=$this->input->post('role_id',TRUE);
        $gradeb_pp_ul=$this->input->post('gradeb_pp_ul',TRUE);
        $gradeb_pp_ll=$this->input->post('gradeb_pp_ll',TRUE);
        $gradeb_sp_ll=$this->input->post('gradeb_sp_ll',TRUE);
        $gradeb_sp_ul=$this->input->post('gradeb_sp_ul',TRUE);
        if($gradeb_pp_ul!='' || $gradeb_pp_ll!='' || $gradeb_sp_ll!='' || $gradeb_sp_ul!='' )
        {
            $section='Grade B';
        }
        else
        {
            $section='';
        }
        $check=$this->Common_model->get_data_row('incentives',array('fy_id'=>$fy_id,'role_id'=>$role_id,'status'=>1,'company_id'=>$this->session->userdata('company')));
        if($check['incentives_id']!='')
        {
            $update_arr=array(
                                'to_date'       =>  date('Y-m-d'),
                                'modified_by'   =>  $this->session->userdata('user_id'),
                                'modified_time' =>  date('Y-m-d H:i:s'),
                                'status'        =>  2
                             );
            $this->Common_model->update_data('incentives',$update_arr,array('incentives_id'=>$check['incentives_id']));
        }
        $insert=array(
                        'fy_id'         =>  $fy_id,
                        'role_id'       =>  $role_id,
                        'value'         =>  $this->input->post('value',TRUE),
                        'upper_value'   =>  $this->input->post('upper_value',TRUE),
                        //'os_percent'  =>  $this->input->post('os_amount',TRUE),
                        'section1'      =>  'Grade A',
                        'pp_ll'         =>  $this->input->post('gradea_pp_ll',TRUE),
                        'pp_ul'         =>  $this->input->post('gradea_pp_ul',TRUE),
                        'sp_ll'         =>  $this->input->post('gradea_sp_ll',TRUE),
                        'sp_ul'         =>  $this->input->post('gradea_sp_ul',TRUE),
                        'section2'      =>  $section,
                        'pp2_ll'        =>  $this->input->post('gradeb_pp_ll',TRUE),
                        'pp2_ul'        =>  $this->input->post('gradeb_pp_ul',TRUE),
                        'sp2_ll'        =>  $this->input->post('gradeb_sp_ll',TRUE),
                        'sp2_ul'        =>  $this->input->post('gradeb_sp_ul',TRUE),
                        'from_date'     =>  date('Y-m-d'),
                        'company_id'    =>  $this->session->userdata('company'),
                        'created_by'    =>  $this->session->userdata('user_id'),
                        'created_time'  =>  date('Y-m-d H:i:s'),
                        'status'        =>  1
                    );
        $this->Common_model->insert_data('incentives',$insert);
        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> Settings Has Been Added Successfully!
                                     </div>');
        redirect(SITE_URL . 'incentive_settings');
    }

    public function view_incentive_settings($encoded_id)
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "View Incentive Settings";
        $data['nestedView']['cur_page'] = 'settings';
        $data['nestedView']['parent_page'] = 'settings';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'View Incentive Settings';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Settings', 'class' => 'active', 'url' => SITE_URL . 'incentive_settings');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'View Incentive Settings', 'class' => 'active', 'url' => '');  

        $incentive_id = @icrm_decode($encoded_id);

        $data['incentive_result']=$this->Settings_m->get_incentive_data($incentive_id);

        $this->load->view('settings/view_incentive_settings_view', $data);

    }

    public function quoteRevStatusChange(){
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manually Change Quote Revision Status";
        $data['nestedView']['cur_page'] = 'settings';
        $data['nestedView']['parent_page'] = 'settings';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manually Change Quote Revision Status';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manually Change Quote Revision Status', 'class' => 'active', 'url' => '');  
    
        $this->load->view('settings/quote_rev_status_change_view', $data);

    }

    public function updateRevStatus(){
       $revId = $this->input->post('rev_id');
       $leadId = $this->input->post('lead_id');
       $where = array('quote_revision_id' => $revId);

       $whereLead = array('lead_number' => $leadId);
       $getLeadID=$this->Common_model->get_data('lead',$whereLead);
       $lead_id = $getLeadID[0]['lead_id'];
       $quoteDetails = $this->Contract_model->getAllLeadQuotes($lead_id);
       $revIDStatus = $quoteDetails[0]['quote_revision_id'];
       $whereRev = array('quote_revision_id' => $revIDStatus);
       $quoteIDStaus = $quoteDetails[0]['quote_id'];
       $whereQuote = array('quote_id' => $quoteIDStaus);
       $this->Common_model->update_data('quote_revision',array('status' => 4),$whereRev);
       $this->Common_model->update_data('quote',array('status' => 2),$whereQuote); 


       $this->Common_model->update_data('quote_revision',array('status' => 1),$where);
       $quoteID1=$this->Common_model->get_data('quote_revision',$where);
       $quoteID = $quoteID1[0]['quote_id'];
       $where1 = array('quote_id' => $quoteID);
       $this->Common_model->update_data('quote',array('status' => 6),$where1);

       $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Quote Revision Status has been Updated successfully!
							 </div>');
		redirect(SITE_URL.'quoteRevStatusChange');
    }

}