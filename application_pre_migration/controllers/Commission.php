<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Commission extends Base_controller {
	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Commission_model");
	}
	public function commission_report()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Commission Report ";
		$data['nestedView']['cur_page'] = 'commission_report';
		$data['nestedView']['parent_page'] = 'commission_report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Commission Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Commission Report','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('search', TRUE);
	//	echo $this->input->post('payment_status', TRUE);//exit;
		if($psearch!='') {
		$searchParams=array(
					  'customer_name'=>$this->input->post('customer_name', TRUE),
					  'product_name'=>$this->input->post('product_name', TRUE),
					  'so_number'=>$this->input->post('so_number', TRUE),
					  'distributor_name'=>$this->input->post('distributor_name'),
					  'payment_status'=>$this->input->post('payment_status', TRUE),
					  'invoice_status'=>$this->input->post('invoice_status', TRUE),
					  'start_date'=>$this->input->post('start_date', TRUE),
					  'end_date'=>$this->input->post('end_date', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'customer_name'=>$this->session->userdata('customer_name'),
					  'product_name'=>$this->session->userdata('product_name'),
					  'distributor_name'=>$this->session->userdata('distributor_name'),
					  'so_number'=>$this->session->userdata('so_number'),
					  'payment_status'=>$this->session->userdata('payment_status'),
					  'invoice_status'=>$this->session->userdata('invoice_status'),
					  'start_date'=>$this->session->userdata('start_date'),
					  'end_date'=>$this->session->userdata('end_date'),
							  );
			}
			else {
				$searchParams=array(
					  'customer_name'=>'',
					  'product_name'=>'',
					  'distributor_name'=>'',
					  'so_number'=>'',
					  'payment_status'=>'',
					  'invoice_status'=>'',
					  'start_date'=>'',
					  'end_date'=>'',
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		//print_r($searchParams);exit;
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'commission_report/'; 
		# Total Records
	    $config['total_rows'] = $this->Commission_model->commissionTotalRows($searchParams);
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
	   	$data['commission_results'] = $this->Commission_model->commission_results($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		//echo 'hi';exit;
		$this->load->view('commission/commission_report', $data);
	}
	public function otr_commission_report()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Commission Report ";
		$data['nestedView']['cur_page'] = 'commission_report';
		$data['nestedView']['parent_page'] = 'commission_report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/po.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Commission Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Commission Report','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('search', TRUE);
	//	echo $this->input->post('payment_status', TRUE);//exit;
		if($psearch!='') {
		$searchParams=array(
					  'customer_name'=>$this->input->post('customer_name', TRUE),
					  'product_name'=>$this->input->post('product_name', TRUE),
					  'so_number'=>$this->input->post('so_number', TRUE),
					  'distributor_name'=>$this->input->post('distributor_name'),
					  'payment_status'=>$this->input->post('payment_status', TRUE),
					  'invoice_status'=>$this->input->post('invoice_status', TRUE),
					  'start_date'=>$this->input->post('start_date', TRUE),
					  'end_date'=>$this->input->post('end_date', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'customer_name'=>$this->session->userdata('customer_name'),
					  'product_name'=>$this->session->userdata('product_name'),
					  'distributor_name'=>$this->session->userdata('distributor_name'),
					  'so_number'=>$this->session->userdata('so_number'),
					  'payment_status'=>$this->session->userdata('payment_status'),
					  'invoice_status'=>$this->session->userdata('invoice_status'),
					  'start_date'=>$this->session->userdata('start_date'),
					  'end_date'=>$this->session->userdata('end_date'),
							  );
			}
			else {
				$searchParams=array(
					  'customer_name'=>'',
					  'product_name'=>'',
					  'distributor_name'=>'',
					  'so_number'=>'',
					  'payment_status'=>2,
					  'invoice_status'=>'',
					  'start_date'=>'',
					  'end_date'=>'',
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		//print_r($searchParams);exit;
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'commission_report/'; 
		# Total Records
	    $config['total_rows'] = $this->Commission_model->commissionTotalRows($searchParams);
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
	   	$data['commission_results'] = $this->Commission_model->commission_results($searchParams,$config['per_page'], $current_offset);
		//print_r($data['commission_results']);die();
		$data['displayList'] = 1;
		//echo 'hi';exit;
		$this->load->view('commission/otr_commission_report', $data);
	}
	public function add_dealer_payment()
	{
		if($this->input->post('tag_submit'))
		{
			$dealer_id=$this->input->post('dealer_id');
			$cnote_id=$this->input->post('cnote_id');
			//print_r($cnote_id);exit;
			$this->db->trans_begin();
			foreach ($cnote_id as $key => $value) 
                {
                    $dat = array(
                        'user_id' => $dealer_id[$key],
                        'contract_note_id' => $value,
                        'status' =>1,
                        'created_by'=> $this->session->userdata('user_id'),
                        'created_time'=>date('Y-m-d h:i:s')
                        );
                    $this->Common_model->insert_data('dealer_commission_payment',$dat);
                    $this->Common_model->update_data('contract_note',array('dealer_payment_status'=>1),array('contract_note_id'=>$cnote_id[$key]));
                }
                 if($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> There\'s a problem occured while Updating dealer commission !
                                         </div>');
                   
                    //echo 'transaction failed';
                        redirect(SITE_URL.'otr_commission_report');
                        
                }
                else
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Success!</strong>Dealer Commission has been successfully updated !
                                         </div>');
                     redirect(SITE_URL.'otr_commission_report');
                    //echo 'transaction success';
                }
		}
	}
}
