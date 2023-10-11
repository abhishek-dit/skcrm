<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Channel_partner extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Channel_partner_model');
        $this->load->model('ajax_m');
        
    }

    public function channel_partner() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Channel Partner";
        $data['nestedView']['cur_page'] = 'channel_partner';
        $data['nestedView']['parent_page'] = 'channel_partner';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Channel Partner';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Channel Partner', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('searchchannel', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'channelname' => $this->input->post('channelname', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'channelname' => $this->session->userdata('channelname'),
                );
            } else {
                $searchParams = array(
                    'channelname' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;

        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'channel_partner/';
        # Total Records
        $config['total_rows'] = $this->Channel_partner_model->channelTotalRows($searchParams);

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
        $data['channels'] = $this->Channel_partner_model->channelResults($searchParams, $config['per_page'], $current_offset);
        //print_r($data['categorySearch']);die();
        $data['displayList'] = 1;
        
        $this->load->view('channel_partner/channel_partnerView', $data);
    }

    public function addchannel_partner() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Channel Partner";
        $data['nestedView']['cur_page'] = 'channel_partner';
        $data['nestedView']['parent_page'] = 'channel_partner';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/old/osr.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Channel Partner';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Channel Partner', 'class' => 'active', 'url' => SITE_URL . 'channel_partner');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Add Channel Partner', 'class' => 'active', 'url' => '');


        
        $data['competitorSelected'] = array();
        $data['flg'] = 1;
        $data['val'] = 0;
        # Load page with all shop details
        $this->load->view('channel_partner/channel_partnerView', $data);
    }

    public function editchannel_partner($encoded_id) {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Channel Partner";
        $data['nestedView']['cur_page'] = 'channel_partner';
        $data['nestedView']['parent_page'] = 'channel_partner';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/channel_partner.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Channel Partner';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Channel Partner', 'class' => 'active', 'url' => SITE_URL . 'channel_partner');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Edit Channel Partner', 'class' => 'active', 'url' => '');
        //echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
        if (@icrm_decode($encoded_id) != '') {

            $value = @icrm_decode($encoded_id);
            $where = array('channel_partner_id' => $value);
            $data['channel_partnerEdit'] = $this->Common_model->get_data_row('channel_partner', $where);
        }
        
        $data['flg'] = 1;
        $data['val'] = 1;
        # Load page with all shop details
        $this->load->view('channel_partner/channel_partnerView', $data);
    }

    public function deletechannel_partner($encoded_id) {
        $channel_partner_id = @icrm_decode($encoded_id);
        $where = array('channel_partner_id' => $channel_partner_id);
        $dataArr = array('status' => 2);
        $this->Common_model->update_data('channel_partner', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		    <div class="icon"><i class="fa fa-check"></i></div>
			<strong>Success!</strong> Channel Partner has been De-Activated successfully!
			</div>');
        redirect(SITE_URL . 'channel_partner');
    }

    public function activatechannel_partner($encoded_id) {
        $channel_partner_id = @icrm_decode($encoded_id);
        $where = array('channel_partner_id' => $channel_partner_id);
        $dataArr = array('status' => 1);
        $this->Common_model->update_data('channel_partner', $dataArr, $where);

        $this->session->set_flashdata('response', '<div class="alert alert-success alert-white rounded">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			<div class="icon"><i class="fa fa-check"></i></div>
			<strong>Success!</strong> Channel Partner has been Activated successfully!
			</div>');
        redirect(SITE_URL . 'channel_partner');
    }

    public function channel_partnerAdd() {
        if ($this->input->post('submitchannel_partner') != "") {
                //print_r($_POST);
                $name=$this->input->post('name');
                $channel_partner_id = @icrm_decode($this->input->post('channel_partner_id',TRUE));



                $flag=$this->ajax_m->is_channel_partnerNameExist($name,$channel_partner_id);
                if($flag == 0){
                $dataArr = array(
                                'name'                  => $name,
                                'bank_name'             => $this->input->post('bank_name',TRUE),
                                'bank_address'          => $this->input->post('bank_address',TRUE),
                                'ifsc_code'             => $this->input->post('ifsc',TRUE),
                                'account_type'          => $this->input->post('ac_type',TRUE),
                                'account_number'        => $this->input->post('ac_no',TRUE),
                                'benificiary_name'      => $this->input->post('beneficiary_name',TRUE),
                                'benificiary_address'   => $this->input->post('beneficiary_address',TRUE),
                                'communication_address' => $this->input->post('communication_address',TRUE),
                                'type'                  => 1,
                                'company_id'            => $this->session->userdata('company'),
                                'city'                  => $this->input->post('city',TRUE)
                                );
                if($channel_partner_id == "")
                {
                    $dataArr['created_by'] = $this->session->userdata('user_id');
                    $dataArr['created_time'] = date('Y-m-d H:i:s');
                    //Insert
                    $this->Common_model->insert_data('channel_partner', $dataArr);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Channel Partner has been added successfully!
                        </div>');
                }
                else
                {	
                    $dataArr['modified_by'] = $this->session->userdata('user_id');
                    $dataArr['modified_time'] = date('Y-m-d H:i:s');
                    $where = array('channel_partner_id' => $channel_partner_id);

                    //Update
                    $this->Common_model->update_data('channel_partner',$dataArr, $where);

                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="icon"><i class="fa fa-check"></i></div>
                        <strong>Success!</strong> Channel Partner has been updated successfully!
                        </div>');
                }
                redirect(SITE_URL . 'channel_partner');
            }else{
                 $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> Channel Partner already Exist!
                    </div>');
                 redirect(SITE_URL . 'channel_partner');                                                             
            }
        }else{
           
             redirect(SITE_URL . 'channel_partner');
            
        }
    }

    public function downloadchannel_partner() {
        if ($this->input->post('downloadchannel_partner') != '') {

            $searchParams = array('channelname' => $this->input->post('channelname', TRUE));
            $channels = $this->Channel_partner_model->channelDetails($searchParams);

            $header = '';
            $data = '';
            $titles = array('S.NO', 'Channel Partner Name','Bank Name','Bank Address','Account Number','IFSC code','Account Type','Beneficiary Name','Beneficiary Address','Communication Address','City');
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
            if (count($channels) > 0) {

                foreach ($channels as $row) {
                    $data.='<tr>';
                    $data.='<td align="center">' . $j . '</td>';
                    $data.='<td>' . $row['name'] . '</td>';
                    $data.='<td>' . $row['bank_name'] . '</td>';
                    $data.='<td>' . $row['bank_address'] . '</td>';
                    $data.='<td>' . $row['account_number'] . '</td>';
                    $data.='<td>' . $row['ifsc_code'] . '</td>';
                    $data.='<td>' . $row['account_type'] . '</td>';
                    $data.='<td>' . $row['benificiary_name'] . '</td>';
                    $data.='<td>' . $row['benificiary_address'] . '</td>';
                    $data.='<td>' . $row['communication_address'] . '</td>';
                    $data.='<td>' . $row['city'] . '</td>';
                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'Channel_partner_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }

    

}
