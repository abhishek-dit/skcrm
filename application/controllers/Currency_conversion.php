<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Base_controller.php';

class Currency_conversion extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Currency_conversion_Model");
    }

    # Fetching currency conversion for the given company currency
    public function currency_conversion()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Currency Conversion";
		$data['nestedView']['cur_page'] = 'add_currency_conversion';
		$data['nestedView']['parent_page'] = 'currency_conversion';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Currency Conversion';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Currency Conversion','class'=>'active','url'=>'');
		$data['flg'] = 1;
		$data['val'] = 0;
		$company_currency = $this->Common_model->get_value('company',array('company_id'=>$_SESSION['company']),'currency_id');
		$data['currency'] = $this->Common_model->get_data('currency',array('status'=>1,'currency_id!='=>$company_currency));
		$data['currency_transactions'] =$this->Currency_conversion_Model->get_company_currency_transactions($company_currency);
		$data['company_currency'] = $this->Common_model->get_data_row('currency',array('currency_id'=>$company_currency));

		$this->load->view('currency_conversion/currency_conversion_view', $data);
	}
	# Inserting currency conversion data
	public function insert_currency_conversion()
	{
		$from_currency = $this->input->post('from_currency',TRUE);
		$to_currency = $this->input->post('to_currency',TRUE);
		$value = $this->input->post('value',TRUE);
		$company_currency = $this->Common_model->get_value('company',array('company_id'=>$_SESSION['company']),'currency_id');
		$get_company_currency_data = $this->Common_model->get_data('currency_transaction',array('to_currency_id'=>$company_currency,'status'=>1));
		$from_currency_id_data = array_column($get_company_currency_data, 'from_currency_id');
		$count = 0;
		$from_currency_arr = array();
		$this->db->trans_begin();
		for($j=0; $j<count($get_company_currency_data); $j++)
		{
			$compare = in_array($from_currency_id_data[$j], $from_currency);
			if($compare == '')
			{
				$count++;
				$from_currency_arr[] = array(
					'from_currency_id'	=>	$get_company_currency_data[$j]['from_currency_id']
					);
			}
		}
		if($count>0)
		{
			foreach($from_currency_arr as $row)
			{
				# Fetch data with From and To currency id
				$old_curr_data = $this->Currency_conversion_Model->get_old_currency_data($row['from_currency_id'],$company_currency);


				# Update Transaction data
				$update_trans_data = array(
				'status'		=>	2,
				'modified_time'	=>	date('Y-m-d H:i:s'),
				'modified_by'	=>	$this->session->userdata('user_id')
				);
				$update_trans_where = array(
				'currency_transaction_id'	=>	$old_curr_data['currency_transaction_id']
			 	);
				$this->Common_model->update_data('currency_transaction',$update_trans_data,$update_trans_where);


				# Get transaction history id
				$old_trans_id = $this->Common_model->get_value('currency_transaction_history',array('currency_transaction_id'=>$old_curr_data['currency_transaction_id'],'end_date'=>NULL),'currency_transaction_history_id');

				# Update history Data
				$history_trans_update_data = array(
				'end_date'		=>	date('Y-m-d'),
				'modified_time'	=>	date('Y-m-d H:i:s'),
				'modified_by'	=>	$this->session->userdata('user_id'),
				'status'		=>	2
				);
				$history_trans_update_where = array('currency_transaction_history_id'=>$old_trans_id);
				$this->Common_model->update_data('currency_transaction_history',$history_trans_update_data,$history_trans_update_where);

			}
		}
		for($i=0; $i<count($from_currency); $i++)
		{
			# Fetch data with From and To currency id
			$old_currency_data = $this->Currency_conversion_Model->get_old_currency_data($from_currency[$i],$to_currency[$i]);


			# Get transaction history id
			$old_currency_transaction_id = $this->Common_model->get_value('currency_transaction_history',array('currency_transaction_id'=>$old_currency_data['currency_transaction_id'],'end_date'=>NULL),'currency_transaction_history_id');


			# If data is Exist with from and to currency id's
			if($old_currency_data['value']!='' && $old_currency_data['value']!= $value[$i])
			{
				# Update Transaction data
				$update_data = array(
				'value'			=>	$value[$i],
				'modified_time'	=>	date('Y-m-d H:i:s'),
				'modified_by'	=>	$this->session->userdata('user_id')
				);
				$update_where = array(
				'currency_transaction_id'	=>	$old_currency_data['currency_transaction_id']
			 	);
				$this->Common_model->update_data('currency_transaction',$update_data,$update_where);


				# Update history Data
				$history_update_data = array(
				'end_date'		=>	date('Y-m-d'),
				'modified_time'	=>	date('Y-m-d H:i:s'),
				'modified_by'	=>	$this->session->userdata('user_id'),
				'status'		=>	2
				);
				$history_update_where = array('currency_transaction_history_id'=>$old_currency_transaction_id);
				$this->Common_model->update_data('currency_transaction_history',$history_update_data,$history_update_where);



				# Insert History Data
				$history_insert = array(
				'currency_transaction_id'	=>	$old_currency_data['currency_transaction_id'],
				'from_currency_id'			=>	$from_currency[$i],
				'to_currency_id'			=>	$to_currency[$i],
				'value'						=>	$value[$i],
				'start_date'				=>	date('Y-m-d'),
				'status'					=>	1,
				'created_by'				=>	$this->session->userdata('user_id'),
				'created_time'				=>	date('Y-m-d H:i:s')
			    );
				$this->Common_model->insert_data('currency_transaction_history',$history_insert);
			}

			elseif(count($old_currency_data)===0)
			{
				
				# Insert Transaction Data
				$conversion_insert = array(
				'from_currency_id'	=>	$from_currency[$i],
				'to_currency_id'	=>	$to_currency[$i],
				'value'				=>	$value[$i],
				'status'			=>	1,
				'created_by'		=>	$this->session->userdata('user_id'),
				'created_time'		=>	date('Y-m-d H:i:s')
				);

				$conversion_id = $this->Common_model->insert_data('currency_transaction',$conversion_insert);

				# Insert History Data
				$conversion_history_insert = array(
				'currency_transaction_id'	=>	$conversion_id,
				'from_currency_id'			=>	$from_currency[$i],
				'to_currency_id'			=>	$to_currency[$i],
				'value'						=>	$value[$i],
				'start_date'				=>	date('Y-m-d'),
				'status'					=>	1,
				'created_by'				=>	$this->session->userdata('user_id'),
				'created_time'				=>	date('Y-m-d H:i:s')
			    );



				$this->Common_model->insert_data('currency_transaction_history',$conversion_history_insert);

			} 
		}
		if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <div class="icon"><i class="fa fa-check"></i></div>
                                <strong>Error!</strong> There\'s a problem occured while adding Currency Conversion !
                             </div>');
            redirect(SITE_URL.'currency_conversion');
        }
        else
        {
        	$this->db->trans_commit();
        	$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong>Currency Conversion has been added successfully !
                                     </div>');
            redirect(SITE_URL.'currency_conversion');
        }
	}

}