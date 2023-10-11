<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class Live_location extends Base_controller {

    public  function __construct() 
    {
        parent::__construct();

        $this->load->model("Location_model");
    }
    public function live_location()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Live Location";
        $data['nestedView']['cur_page'] = 'live_location';
        $data['nestedView']['parent_page'] = 'live_location';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        // $data['nestedView']['js_includes'][] =  '<script src="http://182.156.75.105:443/socket.io/socket.io.js"></script>';
        // $data['nestedView']['js_includes'][] =  '<script src="https://www.skanray-access.com/iCRM/index.php/login"></script>';
        //$data['nestedView']['js_includes'][] =  '<script src="http://13.126.121.68:8080/socket.io/socket.io.js"></script>';
        // $data['nestedView']['js_includes'][] =  '<script  src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>';
        $data['nestedView']['js_includes'][] =  '<script  src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Live Location';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Live Location', 'class' => 'active', 'url' => '');

        $reportees=$this->session->userdata('reportees');
        $data['users'] = $this->Location_model->get_user_reportees_for_live_location($reportees);
        $data['regions'] = $this->Common_model->get_data('location',array('territory_level_id' => 4),array('location_id','location','latitude','longitude'));
        //echo "<pre>";print_r($data['regions']);die;
        

        $this->load->view('livelocation/live_location_view', $data);

    } 
    public function live_location_list()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Track Live Location";
        $data['nestedView']['cur_page'] = 'track_live_location';
        $data['nestedView']['parent_page'] = 'live_location';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';

        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Track Live Location';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'Track Live Location','class'=>'active','url'=>'');
            
        $reportees=$this->session->userdata('reportees');
        $data['users'] = $this->Location_model->get_user_reportees($reportees);

        $user_id = $this->input->post('user_id',TRUE);
        $from_date = $this->input->post('from_date',TRUE);
        $to_date = $this->input->post('to_date',TRUE);
        $results = array();
        

        $this->load->view('livelocation/live_location_list', $data);
    } 

    public function track_live_location()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Live Location";
        $data['nestedView']['cur_page'] = 'new_live_location';
        $data['nestedView']['parent_page'] = 'live_location';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        // $data['nestedView']['js_includes'][] =  '<script src="https://powerful-shore-65853.herokuapp.com/socket.io/socket.io.js"></script>';
        // $data['nestedView']['js_includes'][] =  '<script  src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>';
        //$data['nestedView']['js_includes'][] =  '<script src="http://13.126.121.68:8080/socket.io/socket.io.js"></script>';
        // $data['nestedView']['js_includes'][] =  '<script src="http://182.156.75.105:443/socket.io/socket.io.js"></script>';
        $data['nestedView']['js_includes'][] =  '<script src="https://www.skanray-access.com:3000/socket.io/socket.io.js"></script>';

        $data['nestedView']['js_includes'][] =  '<script  src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Live Location';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Live Location', 'class' => 'active', 'url' => '');

        $reportees=$this->session->userdata('reportees');
        $data['users'] = $this->Location_model->get_user_reportees($reportees);
        $data['regions'] = $this->Common_model->get_data('location',array('territory_level_id' => 4),array('location_id','location','latitude','longitude'));
        $data['lat_regions'] = $this->Location_model->get_lat_long();
        
        $data['form_action'] = SITE_URL.'track_live_location';

        

        $this->load->view('livelocation/moving_live_location_view', $data);

    } 

    public function fetch_live_location()
    {
        $user_id = @$_REQUEST['user_id'];
        $from_date = @$_REQUEST['from_date'];
        $to_date = @$_REQUEST['to_date'];
        $results = array();
        if($from_date=='' || $from_date=='null')
        {
            $from_date = date('Y-m-d');
        }
        if($to_date=='' || $to_date=='null')
        {
            $to_date = date('Y-m-d');
        }
        /*if(($user_id!='')&&(($from_date=='' || $from_date=='null')&& ($to_date=='' || $to_date=='null')))
        {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
        }*/
        if($user_id!='')
        {
            $results = $this->Location_model->get_live_tracking_records($user_id,$from_date,$to_date);
        }
        echo json_encode($results);
    }
}

