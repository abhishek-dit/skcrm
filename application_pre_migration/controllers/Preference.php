<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Preference extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
        $this->load->model('Preference_model');
		
	}
	public function get_preference_list()
	{
		# Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Settings";
        $data['nestedView']['cur_page'] = 'settings';
        $data['nestedView']['parent_page'] = 'settings';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';
       

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';
       
        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Settings';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Settings', 'class' => '', 'url' => '');

        $company_id=$this->session->userdata('company');
        
        $preference_section_list=$this->Preference_model->get_preference_section_list();
        if(count($preference_section_list))
        {
            foreach($preference_section_list as $key=>$row)
            {
                $preference=$this->Preference_model->get_preference_list($row['section_id']);
                $preference_list[$key]['name']=$row['name'];
                $preference_list[$key]['preference_list']=$preference;
            }
        }
        $data['preference_list']=$preference_list;
        $data['preference_section_list']=$preference_section_list;

        $this->load->view('preference/preference_list', $data);

	}
    public function submit_settings()
    {
       
        $preference_name=$this->input->post('preference_name',TRUE);
        $preference_checkbox=$this->input->post('preference_checkbox',TRUE);
        $preference_old_checkbox_arr=$this->Common_model->get_data('preference',array('type'=>2));
        //echo "<pre>"; print_r($preference_checkbox); exit();
        $this->db->trans_begin();
        foreach($preference_name as $preference_id => $value)
        {
            $old_value=$this->Common_model->get_value('preference',array('preference_id'=>$preference_id),'value');
            if($old_value!=$value)
            {
                $update_data=array(
                               'value'          =>$value,
                               'modified_by'    =>$this->session->userdata('user_id'),
                               'modified_time'  =>date('Y-m-d H:i:s'));
                $update_where=array('preference_id'=>$preference_id);
                $this->Common_model->update_data('preference',$update_data,$update_where);
            }
        }
        foreach($preference_old_checkbox_arr as $row)
        {
            $update_data=array(
                           'value'          =>2,
                           'modified_by'    =>$this->session->userdata('user_id'),
                           'modified_time'  =>date('Y-m-d H:i:s'));
            $update_where=array('preference_id'=>$row['preference_id']);
            $this->Common_model->update_data('preference',$update_data,$update_where);
        }
        if(count($preference_checkbox)>0)
        {
            foreach($preference_checkbox as $preference_id1 => $value1)
            {
                $update_data=array(
                               'value'          =>1,
                               'modified_by'    =>$this->session->userdata('user_id'),
                               'modified_time'  =>date('Y-m-d H:i:s'));
                $update_where=array('preference_id'=>$preference_id1);
                $this->Common_model->update_data('preference',$update_data,$update_where);
            }
        }
        if($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Error!</strong> There\'s a problem occured while updating a Preference!
                                 </div>');
            redirect(SITE_URL.'settings'); exit();
                
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <div class="icon"><i class="fa fa-check"></i></div>
                                    <strong>Success!</strong> Preference Values are successfully Updated
                                 </div>');
            redirect(SITE_URL.'settings'); exit();
        }
    }
}