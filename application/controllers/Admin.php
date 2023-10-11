<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Admin extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("AdminModel");
		$this->load->model("Ajax_m");
	}
    /* GET Company List
	** return: company list details
	*/
	public function company()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Companies";
		$data['nestedView']['cur_page'] = 'company';
		$data['nestedView']['parent_page'] = 'company';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Companies';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Companies','class'=>'active','url'=>'');

 		# Search Functionality
		$psearch=$this->input->post('searchCompany', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'companyName'=>$this->input->post('companyName', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'companyName'=>$this->session->userdata('companyName')
							  );
			}
			else {
				$searchParams=array(
					  'companyName'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'company/'; 
		# Total Records
	    $config['total_rows'] = $this->AdminModel->companyTotalRows($searchParams);
		
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
	   	$data['companySearch'] = $this->AdminModel->companyResults($searchParams,$config['per_page'], $current_offset);
		$data['displayList'] = 1;

		$this->load->view('admin/companyView', $data);

	}
	/* Adding New Company to Application
	** return: currency details
	*/
	public function addCompany()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Company";
		$data['nestedView']['cur_page'] = 'company';
		$data['nestedView']['parent_page'] = 'company';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Add New Company';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Companies','class'=>'active','url'=>SITE_URL.'company');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add New','class'=>'active','url'=>'');

 		
		$data['flg'] = 1;
		$data['val'] = 0;
		# Fetching Currency Details
		$data['currency'] = $this->Common_model->get_data('currency',array('status'=>1));
		# Load page with all Company details
		$this->load->view('admin/companyView', $data);

	}
	/* Edit Company Details
	** return: Fetching all details for selected company
	*/
	public function editCompany($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Company";
		$data['nestedView']['cur_page'] = 'company';
		$data['nestedView']['parent_page'] = 'company';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit Company';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Companies','class'=>'active','url'=>SITE_URL.'company');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Company','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			
			$value = @icrm_decode($encoded_id);
			$where = array('company_id' => $value);
			$data['companyEdit'] = $this->Common_model->get_data('company', $where);
			$data['currency'] = $this->Common_model->get_data('currency',array('status'=>1));
			//$data['companyEdit'] = $this->AdminModel->editCompanyDetails($value);
		}
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('admin/companyView', $data);

	}
	
	# Deactivating Company Details
	public function deleteCompany($encoded_id)
	{
		$company_id=@icrm_decode($encoded_id);
		$where = array('company_id' => $company_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('company',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Company has been De-Activated successfully!
								</div>');
		redirect(SITE_URL.'company');

	}
	
	# Reactivating Company 
	public function activateCompany($encoded_id)
	{
			$company_id=@icrm_decode($encoded_id);
			$where = array('company_id' => $company_id);
			$dataArr = array('status' => 1);
			$this->Common_model->update_data('company',$dataArr, $where);
			
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Company has been Activated successfully!
								 </div>');
			redirect(SITE_URL.'company');

	}
	
	/* Downloading the Companies List
	** return: File Download
	*/
	public function downloadCompany()
	{
		if($this->input->post('downloadCompany')!='') {
			
			$searchParams=array( 'companyName'=>$this->input->post('companyName', TRUE));
			#Fetching Details of company
			$companies = $this->AdminModel->companyDetails($searchParams);
			$header = '';
			$data ='';
			$titles = array('S.NO','Company Name','Short Name','PAN_number','TIN_number','CIN_number','TAN_number','Service Tax Number','Sales Tax Number','Excise Number','Area','Address','State','City','Country','Currency');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($companies)>0)
			{
				
				foreach($companies as $company)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$company['name'].'</td>';
					$data.='<td>'.$company['short_name'].'</td>';
					$data.='<td>'.$company['PAN_number'].'</td>';
					$data.='<td>'.$company['TIN_number'].'</td>';
					$data.='<td>'.$company['CIN_number'].'</td>';
					$data.='<td>'.$company['TAN_number'].'</td>';
					$data.='<td>'.$company['service_tax_number'].'</td>';
					$data.='<td>'.$company['sales_tax_number'].'</td>';
					$data.='<td>'.$company['excise_number'].'</td>';
					$data.='<td>'.$company['address1'].'</td>';
					$data.='<td>'.$company['address2'].'</td>';
					$data.='<td>'.$company['state'].'</td>';
					$data.='<td>'.$company['city'].'</td>';
					$data.='<td>'.$company['country'].'</td>';
					$data.='<td>'.$company['currency'].'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='company_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}
	
	/* Inserting Added Company Details to Database
	** return: Company Inserted ID
	*/
	public function companyAdd()
	{
		if($this->input->post('submitCompany') != "")
		{
			$company_id = $this->input->post('company_id');
			$name = $this->input->post('name');
			$flag=$this->Ajax_m->is_companyNameExist($name,$company_id);
			if($flag == 0)
			{
				if($company_id == "")
				{
					$dataArr = array(
						'name' => $this->input->post('name'),
						'PAN_number' => $this->input->post('PAN'),
						'TIN_number' => $this->input->post('TIN'),
						'CIN_number' => $this->input->post('CIN'),
						'TAN_number' => $this->input->post('TAN'),
						'service_tax_number' => $this->input->post('service'),
						'service_tax_number2' => $this->input->post('service2'),
						'sales_tax_number' => $this->input->post('sales'),
						'excise_number' => $this->input->post('excise'),
						'excise_number2' => $this->input->post('excise2'),
						'address1' => $this->input->post('address1'),
						'address2' => $this->input->post('address2'),
						'state' => $this->input->post('state'),
						'city' => $this->input->post('city'),
						'currency_id' => $this->input->post('currency_id',TRUE),
						'short_name'  => $this->input->post('short_name',TRUE),
						'country' => $this->input->post('country'),
						'bank_name'				=>	$this->input->post('bank_name',TRUE),
						'branch'				=>	$this->input->post('branch',TRUE),
						'ac_name'				=>	$this->input->post('ac_name',TRUE),
						'ac_no'					=>	$this->input->post('ac_no',TRUE),
						'ifsc'					=>	$this->input->post('ifsc',TRUE),
						'created_by' => $this->input->post('created_by'),
						'created_time' => date('Y-m-d H:i:s'));
					
					//print_r($dataArr); die();
					//Insert
					$comp_id = $this->Common_model->insert_data('company',$dataArr);
					$channel_partner_arr = array(
												'name'		   => $this->input->post('name'),
												'type'		   => 2,
												'company_id'   => $comp_id,
												'created_by'   => $this->input->post('created_by'),
												'created_time' => date('Y-m-d H:i:s'),
												'status'	   => 1
												);
					$this->Common_model->insert_data('channel_partner',$channel_partner_arr);
					$pre_settings = $this->Common_model->get_data('preference',array('company_id'=>1));
					$settings_count = count($pre_settings);
					for($i=0;$i<$settings_count;$i++)
					{
						$settings_arr = array(
											'section_id'	=>	$pre_settings[$i]['section_id'],
											'company_id'	=>	$comp_id,
											'section'		=>  $pre_settings[$i]['section'],
											'name'			=>	$pre_settings[$i]['name'],
											'value'			=>	$pre_settings[$i]['value'],
											'lable'			=>	$pre_settings[$i]['lable'],
											'type'			=>	$pre_settings[$i]['type'],
											'b_display'		=>	$pre_settings[$i]['b_display'],
											'rank'			=>	$pre_settings[$i]['rank'],
											'created_by'	=>	0,
											'created_time'	=>	date('Y-m-d H:i:s')

										 );
						$this->Common_model->insert_data('preference',$settings_arr);
					}
					$pre_quote_approval_arr = $this->Common_model->get_data('quote_approval_config',array('company_id'=>1));
					$quote_count = count($pre_quote_approval_arr);
					for($j=0;$j<$quote_count;$j++)
					{
						
						$quote_approval_arr = array(
								'gm_lower_limit'	=>	$pre_quote_approval_arr[$j]['gm_lower_limit'],
								'gm_lower_check'	=>	$pre_quote_approval_arr[$j]['gm_lower_check'],
								'gm_upper_limit'	=>	$pre_quote_approval_arr[$j]['gm_upper_limit'],
								'gm_upper_check'	=>	$pre_quote_approval_arr[$j]['gm_upper_check'],
								'nm_lower_limit'	=>	$pre_quote_approval_arr[$j]['nm_lower_limit'],
								'nm_lower_check'	=>	$pre_quote_approval_arr[$j]['nm_lower_check'],
								'nm_upper_limit'	=>	$pre_quote_approval_arr[$j]['nm_upper_limit'],
								'nm_upper_check'	=>	$pre_quote_approval_arr[$j]['nm_upper_check'],
								'status'			=>	1,
								'company_id'		=>	$comp_id
											   );
						$this->Common_model->insert_data('quote_approval_config',$quote_approval_arr);
					}

					

					//print $this->db->last_query(); die();
					
					$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											<div class="icon"><i class="fa fa-check"></i></div>
											<strong>Success!</strong> Company has been Added successfully!
										 </div>');
					redirect(SITE_URL.'company');
				}
				else
				{
					$dataArr = array(
						'name' => $this->input->post('name'),
						'PAN_number' => $this->input->post('PAN'),
						'TIN_number' => $this->input->post('TIN'),
						'CIN_number' => $this->input->post('CIN'),
						'TAN_number' => $this->input->post('TAN'),
						'service_tax_number' => $this->input->post('service'),
						'service_tax_number2' => $this->input->post('service2'),
						'sales_tax_number' => $this->input->post('sales'),
						'excise_number' => $this->input->post('excise'),
						'excise_number2' => $this->input->post('excise2'),
						'address1' => $this->input->post('address1'),
						'address2' => $this->input->post('address2'),
						'state' => $this->input->post('state'),
						'city' => $this->input->post('city'),
						'currency_id' => $this->input->post('currency_id',TRUE),
						'short_name'  => $this->input->post('short_name',TRUE),
						'country' => $this->input->post('country'),
						'bank_name'				=>	$this->input->post('bank_name',TRUE),
						'branch'				=>	$this->input->post('branch',TRUE),
						'ac_name'				=>	$this->input->post('ac_name',TRUE),
						'ac_no'					=>	$this->input->post('ac_no',TRUE),
						'ifsc'					=>	$this->input->post('ifsc',TRUE),
						'modified_by' => $this->input->post('created_by'),
						'modified_time' => date('Y-m-d H:i:s'));
					$where = array('company_id' => $company_id);
					//print_r($dataArr); die();

					//Update
					$this->Common_model->update_data('company',$dataArr, $where);

					$channel_partner_where = array('company_id' => $company_id,'type'=>2);
					$channel_partner_arr = array(
												'name'		    => $this->input->post('name'),
												'modified_by'   => $this->input->post('created_by'),
												'modified_time' => date('Y-m-d H:i:s')
												);
					
					$this->Common_model->update_data('channel_partner',$channel_partner_arr,$channel_partner_where);

					$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											<div class="icon"><i class="fa fa-check"></i></div>
											<strong>Success!</strong> Company has been updated successfully!
										 </div>');
					redirect(SITE_URL.'company');
				}
			}
			else
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> Company already Exist!
                    </div>');
                 redirect(SITE_URL . 'company');                                                             
            }
		}
		else
        {
           
            redirect(SITE_URL . 'company');
            
        }
	}

	/* Fetching All Admins belongs to Respective Company
	** return: Admin List
	*/
    public function adminUser()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Admin User";
		$data['nestedView']['cur_page'] = 'adminUser';
		$data['nestedView']['parent_page'] = 'adminUser';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Admin User';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Admin User','class'=>'active','url'=>'');

 		# Search Functionality
		$psearch=$this->input->post('searchAdminUser', TRUE);
		if($psearch!='') {
		$searchParams=array(
						'adminUserID' =>$this->input->post('adminUserID', TRUE),
					  	'adminUserName'=>$this->input->post('adminUserName', TRUE),
					  	'adminUserCompany'=>$this->input->post('adminUserCompany', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
						'adminUserID' =>$this->session->userdata('adminUserID'),
					  	'adminUserName'=>$this->session->userdata('adminUserName'),
					  	'adminUserCompany'=>$this->session->userdata('adminUserCompany')
							  );
			}
			else {
				$searchParams=array(
					  'adminUserID' => '',
					  'adminUserName'=>'',
					  'adminUserCompany'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'company/'; 
		# Total Records
	    $config['total_rows'] = $this->AdminModel->adminUserTotalRows($searchParams);
		
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
		
		#Company List
		$data['companies'] =  array(''=>'Select Company') + $this->Common_model->get_dropdown('company', 'company_id', 'name', []);

		# Search Results
	   	$data['adminUserSearch'] = $this->AdminModel->adminUserResults($searchParams,$config['per_page'], $current_offset);
		$data['displayList'] = 1;

		$this->load->view('admin/adminUserView', $data);

	}

	/* Fetching Details to add Admin to company
	** return: Company List
	*/
	public function addAdminUser()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Admin User";
		$data['nestedView']['cur_page'] = 'adminUser';
		$data['nestedView']['parent_page'] = 'adminUser';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Add New Admin User';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Admin User','class'=>'active','url'=>SITE_URL.'adminUser');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add New','class'=>'active','url'=>'');
		#Fetching All Countries ISD Details
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
 		$data['flg'] = 1;
		$data['val'] = 0;
		#Fetching Company list
		$data['companies'] = $this->Common_model->get_data('company',array('status'=>1));
		#Retreving Branch Details
		$data['branch'] = array(''=>'Select Branch') + $this->Common_model->get_dropdown('branch', 'branch_id', 'name', []);
		$data['companyEdit'] = array();
		# Load page with all shop details
		$this->load->view('admin/adminUserView', $data);

	}
	
	/* Editing User Admin Details
	** return: Get All details of selected Admin 
	*/
	public function editAdminUser($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Admin User";
		$data['nestedView']['cur_page'] = 'adminUser';
		$data['nestedView']['parent_page'] = 'adminUser';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit Admin User';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Admin User','class'=>'active','url'=>SITE_URL.'adminUser');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Admin User','class'=>'active','url'=>'');
		if(@icrm_decode($encoded_id)!='')
		{
			
			$value = @icrm_decode($encoded_id);
			$where = array('user_id' => $value);
			$data['adminUserEdit'] = $this->Common_model->get_data_row('user', $where);
			
			
		}
		$data['companies'] = $this->Common_model->get_data('company',array('status'=>1));
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
        $data['branch'] = array(''=>'Select Branch') + $this->Common_model->get_dropdown('branch', 'branch_id', 'name', []);
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('admin/adminUserView', $data);

	}
	
	# Deactivating Admin
	public function deleteAdminUser($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$where = array('user_id' => $user_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('user',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Admin User has been De-Activated successfully!
								</div>');
		redirect(SITE_URL.'adminUser');

	}
	
	# Activating Admin
	public function activateAdminUser($encoded_id)
	{
			$user_id=@icrm_decode($encoded_id);
			$where = array('user_id' => $user_id);
			$dataArr = array('status' => 1);
			$this->Common_model->update_data('user',$dataArr, $where);
			
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> Admin User has been Activated successfully!
								 </div>');
			redirect(SITE_URL.'adminUser');

	}
	
	#Downloading Admin List
	public function downloadAdminUser()
	{
		if($this->input->post('downloadAdminUser')!='') {
			
			$searchParams=array( 
							'adminUserCompany'=>$this->input->post('adminUserCompany', TRUE),
							'adminUserID' => $this->input->post('adminUserID', TRUE),
							'adminUserName' => $this->input->post('adminUserName', TRUE));
			#Fetching Admin List
			$companies = $this->AdminModel->adminUserDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO', 'Employee ID', 'Company', 'First Name','Last Name', 'Mobile Number','Email ID','Address','Address 1','Branch','State','City', 'Status');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($companies)>0)
			{
				
				foreach($companies as $company)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$company['employee_id'].'</td>';
					$data.='<td>'.$company['name'].'</td>';
					$data.='<td>'.$company['first_name'].'</td>';
					$data.='<td>'.$company['last_name'].'</td>';
					$data.='<td>'.$company['mobile_no'].'</td>';
					$data.='<td>'.$company['email_id'].'</td>';
					$data.='<td>'.$company['address1'].'</td>';
					$data.='<td>'.$company['address2'].'</td>';
					$data.='<td>'.$company['branch'].'</td>';
					$data.='<td>'.$company['state'].'</td>';
					$data.='<td>'.$company['city'].'</td>';
					$data.='<td>'.statusCheck($company['status']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)+1).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='adminUser_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}
	
	/* Inserting Admin User Details To the application
	** return: Get Inserted user Id
	*/
	public function adminUserAdd()
	{
		if($this->input->post('submitAdminUser') != "")
		{
            $isd = $this->input->post('isd');
            $mobile_no = $isd. "-" .$this->input->post('mobile_no');
			$user_id = $this->input->post('user_id');
			$company_id = $this->input->post('company_id');
			$dataArr = array(
					'employee_id' => $this->input->post('employee_id'),
					'company_id' => $company_id,
					'role_id' => 2,
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'mobile_no' => $mobile_no,
					'email_id' => $this->input->post('email_id'),
					'address1' => $this->input->post('address1'),
					'address2' => $this->input->post('address2'),
					'state' => $this->input->post('state'),
					'city' => $this->input->post('city'),
					'branch_id' => $this->input->post('branch_id'));



			if($user_id == "")
			{   
				# Generating Password Based on First Name, Employee Id, Mobile Number
				$password = generatePassword($this->input->post('employee_id'), $this->input->post('first_name'), $this->input->post('mobile_no'));
				$dataArr['password'] = md5($password);
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				$insert_user_id = $this->Common_model->insert_data('user',$dataArr);
				
				$to=$this->input->post('email_id',TRUE);
                $subject="Welcome to iCRM";
                $body="Hi ".$this->input->post('first_name',TRUE).",<br><br>";
                        $body.= " Welcome to iCRM.<br>
                          Your login details as follows  <br><br>
                          <strong>Username:</strong>".$this->input->post('employee_id',TRUE)."<br>
                          <strong>password:</strong>".$password."  <br>
                    <br><br>
                    Thanks,<br>
                    iCRM Team.
                    ";
                
                
               send_email( $to,$subject, $body); 
				

				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Admin User has been Added successfully!
									 </div>');
				
				redirect(SITE_URL.'adminUser');
			}
			else
			{
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$dataArr['company_id'] = $company_id;

				$where = array('user_id' => $user_id);

				//Update
				$this->Common_model->update_data('user',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Admin User has been updated successfully!
									 </div>');
				redirect(SITE_URL.'adminUser');
			}
		}
	}

	/* Fetching All Admins belongs to Respective Company
	** return: Admin Email List
	*/
    public function adminEmail()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Email";
		$data['nestedView']['cur_page'] = 'adminEmail';
		$data['nestedView']['parent_page'] = 'adminEmail';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Email';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Email','class'=>'active','url'=>'');

 		# Search Functionality
		$psearch=$this->input->post('searchAdminEmail', TRUE);
		if($psearch!='') {
		$searchParams=array(
						'adminEmailId' =>$this->input->post('adminEmailId', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
						'adminEmailId' =>$this->session->userdata('adminEmailId')
							  );
			}
			else {
				$searchParams=array(
					  'adminEmailId' => ''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'company/'; 
		# Total Records
	    $config['total_rows'] = $this->AdminModel->adminUserTotalRows($searchParams);
		
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
		
		#Company List
		$data['companies'] =  array(''=>'Select Company') + $this->Common_model->get_dropdown('company', 'company_id', 'name', []);

		# Search Results
	   	$data['adminUserSearch'] = $this->AdminModel->adminEmailDetails($searchParams,$config['per_page'], $current_offset);
		$data['displayList'] = 1;

		$this->load->view('admin/adminEmailView.php', $data);

	}
	/* Fetching Details to add Admin to Email
	** return: Email List
	*/
	public function addAdminEmail()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Email";
		$data['nestedView']['cur_page'] = 'adminEmail';
		$data['nestedView']['parent_page'] = 'adminEmail';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Add New Email';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Email','class'=>'active','url'=>SITE_URL.'adminUser');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add New','class'=>'active','url'=>'');
		#Fetching All Countries ISD Details
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
 		$data['flg'] = 1;
		$data['val'] = 0;
		#Fetching Company list
		$data['companies'] = $this->Common_model->get_data('company',array('status'=>1));
		#Retreving Branch Details
		$data['branch'] = array(''=>'Select Branch') + $this->Common_model->get_dropdown('branch', 'branch_id', 'name', []);
		$data['companyEdit'] = array();
		# Load page with all shop details
		$this->load->view('admin/adminEmailView', $data);

	}
	/* Inserting Admin User Details To the application
	** return: Get Inserted user Id
	*/
	public function adminEmailAdd()
	{
		if($this->input->post('submitAdminEmail') != "")
		{
            $id = $this->input->post('id');
			$dataArr = array(
					'email_id' => $this->input->post('email_id'),
					'status' => 1,
				);
			$flag =$this->Ajax_m->is_emailExist($dataArr['email_id']);

			if($flag == 0){
				if($id == "")
				{   
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				$this->Common_model->insert_data('email',$dataArr);

				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Admin Email has been Added successfully!
									 </div>');
				
				redirect(SITE_URL.'adminEmail');
			}
			else
			{
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('id' => $id);
				//Update
				$this->Common_model->update_data('user',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Admin Email has been updated successfully!
									 </div>');
				redirect(SITE_URL.'adminEmail');
			}
			}else{
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> Email ID already Exist!
                    </div>');
                 redirect(SITE_URL . 'adminEmail'); 
			}

		}
	}
	/* Editing Email Admin Details
	** return: Get All details of selected Admin Email Id
	*/
	public function editAdminEmail($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Email";
		$data['nestedView']['cur_page'] = 'adminEmail';
		$data['nestedView']['parent_page'] = 'adminEmail';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit Admin User';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Edit Email','class'=>'active','url'=>SITE_URL.'adminUser');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Admin User','class'=>'active','url'=>'');
		if(@icrm_decode($encoded_id)!='')
		{
			
			$value = @icrm_decode($encoded_id);
			$where = array('id' => $value);
			$data['adminEmailEdit'] = $this->Common_model->get_data_row('email', $where);
			
			
		}
		$data['companies'] = $this->Common_model->get_data('company',array('status'=>1));
        $data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
        $data['branch'] = array(''=>'Select Branch') + $this->Common_model->get_dropdown('branch', 'branch_id', 'name', []);
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('admin/adminEmailView', $data);

	}

	# Deactivating Admin Email
	public function deleteAdminEmail($encoded_id)
	{
		$id=@icrm_decode($encoded_id);
		$where = array('id' => $id);
		$dataArr = array('status' => 0);
		$this->Common_model->update_data('email',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Admin Email has been De-Activated successfully!
								</div>');
		redirect(SITE_URL.'adminEmail');

	}
	
	
}