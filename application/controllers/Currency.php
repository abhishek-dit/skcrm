<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Currency extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("CurrencyModel");
         $this->load->model("common_model");
    }
    #Fetching currency details
    public function currency() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Currency";
        $data['nestedView']['cur_page'] = 'currency';
        $data['nestedView']['parent_page'] = 'currency';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Currency';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Currency', 'class' => 'active', 'url' => '');

        # Search Functionality
        $psearch = $this->input->post('searchCurrency', TRUE);
        if ($psearch != '') {
            $searchParams = array(
                'currency_name' => $this->input->post('currency_name', TRUE),
                'code' => $this->input->post('code', TRUE)
            );
            $this->session->set_userdata($searchParams);
        } else {

            if ($this->uri->segment(2) != '') {
                $searchParams = array(
                    'currency_name' => $this->session->userdata('currency_name'),
                    'code' => $this->session->userdata('code')
                );
            } else {
                $searchParams = array(
                    'currency_name' => '',
                    'code' => ''
                );
                $this->session->unset_userdata(array_keys($searchParams));
            }
        }
        $data['searchParams'] = $searchParams;
		# Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'currency/';
        # Total Records
        $config['total_rows'] = $this->CurrencyModel->currencyTotalRows($searchParams);
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
		$data['currency'] = $this->CurrencyModel->currencyResults($searchParams, $config['per_page'],$current_offset);
		# Loading the data array to send to View
        $data['displayList'] = 1;
        $this->load->view('currency/currencyView', $data);
    }

    public function add_currency()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Currency";
		$data['nestedView']['cur_page'] = 'add_currency';
		$data['nestedView']['parent_page'] = 'currency';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Currency';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Currency','class'=>'active','url'=>SITE_URL.'currency');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Currency','class'=>'active','url'=>'');
		$data['flg'] = 1;
		$data['val'] = 0;
		$this->load->view('currency/currencyView', $data);

	}

    public function editCurrency($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Currency";
		$data['nestedView']['cur_page'] = 'currency';
		$data['nestedView']['parent_page'] = 'currency';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Currency';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Currency','class'=>'active','url'=>SITE_URL.'currency');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Currency','class'=>'active','url'=>'');
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('currency_id' => $value);
			$data['curEdit'] = $this->Common_model->get_data('currency', $where);
		}
		
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('currency/currencyView', $data);
	}

    public function currency_add()
	{
		if($this->input->post('submit') != "")
		{
			$currency_id = $this->input->post('currency_id');
			$dataArr = array('name' => $this->input->post('currency_name'),
							'code'=>$this->input->post('currency_code'),
						    'status'=>1);

			if($currency_id == "")
			{
				
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');

				//Insert
				$currency_id = $this->Common_model->insert_data('currency',$dataArr);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> '.$dataArr['currency_name'].' has been added successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('currency_id' => $currency_id);
				//Update
				$this->Common_model->update_data('currency',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> '.$dataArr['currency_name'].' has been updated successfully!
									 </div>');
			}
			redirect(SITE_URL.'currency');
		}
	}

   

    public function downloadCurrency() {
        //print_r($_POST);
        if ($this->input->post('downloadCurrency') != '') {
		    $search_params = array(
                'currency_name' => $this->input->post('currency_name'),
                'code' => $this->input->post('code')
            );

            $currency = $this->CurrencyModel->get_download_details($search_params);

            $header = '';
            $data = '';
            $titles = array('S.NO', 'Name', 'Code');
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
            if (count($currency) > 0) {

                foreach ($currency as $row) {
                    $data.='<tr>';
                    $data.='<td align="center">' . $j . '</td>';
                    $data.='<td>' . $row['name'] . '</td>';
                    $data.='<td>' . $row['code'] . '</td>';
                    $data.='</tr>';
                    $j++;
                }
            } else {
                $data.='<tr><td colspan="' . (count($titles) + 1) . '" align="center">No Results Found</td></tr>';
            }
            $data.='</tbody>';
            $data.='</table>';
            $time = date("Ymdhis");
            $xlFile = 'currency_' . $time . '.xls';
            header("Content-type: application/x-msdownload");
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=" . $xlFile . "");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
        }
    }
    /*
    Checking whether currency code is existed or not through ajax call
    */
    public function isCurCodeExist(){
            
		$currency_code = $this->input->post('currency_name');
        $currency_id = $this->input->post('currency_id');
		$data = '';
		echo $this->CurrencyModel->is_currencyCodeExist($currency_code,$currency_id);
  	}
}

