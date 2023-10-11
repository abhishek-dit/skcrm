<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Otr extends Base_controller {

    public function __construct() 
    {
    	parent::__construct();
    	$this->load->model('Otr_m');
    }
    public function otr_list()
    {     
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Approved Contract Note List";
        $data['nestedView']['cur_page'] = 'otr_list';
        $data['nestedView']['parent_page'] = 'otr_list';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Cnote Approved List';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Approved Contract Note List', 'class' => '', 'url' =>'');
        $data['pageDetails'] = 'otr_list';
        //$data['lead_id'] = $lead_id;
        $user_id = $this->session->userdata('user_id');
        //retreving user locations
        $locations =  getUserLocations($user_id);

        # Search Functionality
        $psearch=$this->input->post('search', TRUE);
        if($psearch!='') {
        $searchParams=array(
                      'contract_note_id'=>$this->input->post('contract_note_id'),
                      'cnote_type'=>$this->input->post('cnote_type'),
                      'billing_party'=>$this->input->post('billing_party')
                      );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'contract_note_id'=>$this->session->userdata('contract_note_id'),
                      'cnote_type'=>$this->session->userdata('cnote_type'),
                      'billing_party'=>$this->session->userdata('billing_party')
                      );
            }
            else {
                $searchParams=array(
                      'contract_note_id'=>'',
                      'cnote_type'=>'',
                      'billing_party'=>'',
                       );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;
       // print_r($searchParams);exit;
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'otr_list/'; 
        # Total Records
        $config['total_rows'] = $this->Otr_m->contract_note_total_rows($searchParams,$locations);
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
        $data['searchResults'] = $this->Otr_m->contract_note_results($searchParams,$config['per_page'], $current_offset,$locations);
        //print_r($data['searchResults']); exit;
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name");
       $this->load->view('otr/otr_list', $data);
    }
}