<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class User extends Base_controller {

	public  function __construct() 
	{
        parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('global_functions');
		$this->load->library('curl_operations');
		$this->load->model("User_m");
	}


	public function Users() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage Users";
        $data['nestedView']['cur_page'] = 'user';
        $data['nestedView']['parent_page'] = 'user';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Users';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Users', 'class' => 'active', 'url' => '');

        # Search Functionality

        # Search Functionality
        $psearch=$this->input->post('searchUser', TRUE);
        if($psearch!='') {
        $searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'email' => $this->input->post('email', TRUE),
                'mobile' => $this->input->post('mobile', TRUE)
                              );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'user_role'=>$this->session->userdata('user_role'),
                      'user_name'=>$this->session->userdata('user_name'),
                      'employeeId'=>$this->session->userdata('employeeId'),
                      'email'=>$this->session->userdata('email'),
                      'mobile'=>$this->session->userdata('mobile')
                              );
            }
            else {
                $searchParams=array(
                      'user_role'=>'',
                      'user_name'=>'',
                      'employeeId' =>'',
                      'email' => '',
                      'mobile' => ''
                                  );
                $this->session->set_userdata($searchParams);
            }
            
        }
        $data['search_data'] = $searchParams;


        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'users/';
        # Total Records
        $config['total_rows'] = $this->User_m->userTotalRows($searchParams);

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

        # Loading the data array to send to View
        $data['userSearch'] = $this->User_m->userResults($current_offset, $config['per_page'], $searchParams);
        // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
        $data['roles'] = $this->User_m->getAdminRoles();
        $data['searchParams'] = $searchParams;

        $this->load->view('user/usersView', $data);
    }
	//mahesh 15th jun 2016 12pm
	public function addUser()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Users";
		$data['nestedView']['cur_page'] = 'user';
		$data['nestedView']['parent_page'] = 'user';
		$data['nestedView']['enableFormWizard']=1;
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/fuelux/loader.min.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/custom/manage-user.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Users';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Users','class'=>'','url'=>'users');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Create User','class'=>'active','url'=>'');
		
		# Form data
		$data['companies'] = $this->Common_model->get_data('company',array('status'=>1));
		// GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN, Super User
		$this->db->where('status',1);
		$this->db->where_not_in('role_id',array(1,2,13));
		$res = $this->db->get('role');
		//$data['roles'] = $this->Common_model->get_data('role',array('status'=>1));
		$data['roles'] = $res->result_array();
		$data['geos'] = $this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>2));
		$data['sess_company'] = $this->session->userdata('company');
		$data['productCategories'] = $this->Common_model->get_data('product_category',array('status'=>1,'company_id'=>$data['sess_company']));
		$data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
		$data['branch'] = array(''=>'Select Branch') + $this->Common_model->get_dropdown('branch', 'branch_id', 'name', []);
		$data['role_id'] =''; $data['role_level_id'] ='';
		if($this->input->post('role_id',TRUE)>0){
			$data['role_id'] =$this->input->post('role_id',TRUE);
			$res = $this->Common_model->get_data('role',array('role_id'=>$data['role_id']));
			$data['role_level_id'] =$res[0]['role_level_id'];
		}
		$this->load->view('user/addUser', $data);
	}
	
	//mahesh 20th jun 2016 03:45 pm
	public function insertUser()
	{
		//echo '<pre>'.print_r($_POST).'</pre>';
		$this->load->model("Ajax_m"); $error=false;
		$empID = $this->input->post('employee_id',TRUE);
		$email = $this->input->post('email',TRUE);
		// check user EmpID is empty
		if($empID==''){

			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> User Employee ID is empty!
								 </div>');
			$error= true;
		}
		// check user EmpID already exist
		if($this->Ajax_m->is_employeeIdExist($empID,'')){

			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> Employee ID '.$empID.' already exists for another user!
								 </div>');
			$error= true;
		}
		// check user email is empty
		if($email==''){

			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> User Email is empty!
								 </div>');
			$error= true;
		}
		// check user email already exist
		if($this->Ajax_m->is_employeeEmailExist($email,'')){

			$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> User Email '.$email.' is already exist for another user!
								 </div>');
			$error= true;
			
		}
		if($error){
			redirect(SITE_URL.'addUser');
			exit;
		}
		//gettitng locations
		$role_id = $this->input->post('role_id',TRUE);
		$user_locations = array();
		switch($role_id) {
			case 11: // Global Head
				$user_locations[]=1;
			break;
			case 10: // Sales Director
				if($this->input->post('geo',TRUE))
				$user_locations = $this->input->post('geo',TRUE);
			break;
			case 9: // Country Head
			case 8: // NSM (National Sales Manager)
				if($this->input->post('country',TRUE))
				$user_locations = $this->input->post('country',TRUE);
			break;
			case 7: // RBH
			case 6: // RSM
			/// adding user location fot OTR added by suresh on 15th May 2017
			case 15: // OTR
			/// ende adding user location fot OTR
				$region_loc = $this->input->post('region',TRUE);
				if($region_loc!='')
				{
					$user_locations[] = $region_loc;
				}
				else
				{
					$country_loc = $this->input->post('country',TRUE);
					if($country_loc!='')
					$user_locations[] = $country_loc;
				}
			break;
			case 5: // Distributor
			case 4: // Sales Engineer (SE)
				
					$region = $this->input->post('region',TRUE);
					$state = $this->input->post('state',TRUE);
					if(!$state) //  if no states existed
					{
						$user_locations[]=$region;
					}
					else {
						$district = $this->input->post('district',TRUE);
						if(!$district) { // if no districts existed
							$user_locations = $state;
						}
						else {
							$city = $this->input->post('city',TRUE);
							$district_parent = $this->input->post('district_parent',TRUE);
							
							//removing states if districts existed
							foreach($district as $districtId) {
								$dist_stateId = $district_parent[$districtId];
								if (($key = array_search($dist_stateId, $state)) !== false) {
									unset($state[$key]);
								}
							}
							if($city) { // if cities existed
								$city_parent = $this->input->post('city_parent',TRUE);
								// removing districts if cities existed
								foreach($city as $cityId) {
									$city_districtId = $city_parent[$cityId];
									if (($key = array_search($city_districtId, $district)) !== false) {
										unset($district[$key]);
									}
								}
							}
							
							$user_locations = array_merge($state,$district);
							if($city) {
								$user_locations = array_merge($user_locations,$city);
							}
						}
					}
					
					
					
			break;
			
		}
		
		// GETTING products
		$user_products = array();
		switch($role_id) {
			case 11: // Global Head
			case 10: // Sales Director
			case 9: // Country Head
			case 7: // RBH
			// assigning all products to OTR added by suresh on 15th May 2017
			case 15: // OTR
			// ended assigning all products to OTR added by suresh on 15th May 2017
				$allProducts = getAllProducts();
				foreach($allProducts as $productArr) {
					$user_products[] = $productArr['product_id'];
				}
			break;
			case 4: // sales engineer
			case 5: // distributor
			case 6: // RSM
			case 8: // NSM
				$product = $this->input->post('product',TRUE);
				if($product)
				$user_products = $product;
			break;
		}
		//echo '<pre>'.print_r($user_locations).'</pre>';
		//echo '<pre>'.print_r($user_products).'</pre>';
		
		$this->db->trans_begin();
		$password = generatePassword($this->input->post('employee_id',TRUE), $this->input->post('first_name',TRUE), $this->input->post('mobile',TRUE));
		//$password = 'abcd';
		$mobile = $this->input->post('mobile',TRUE);
		$isd1 = $this->input->post('isd1',TRUE);
		$mobile_no = $isd1."-".$mobile;
		$alternate = $this->input->post('alternate_number',TRUE);
		if($alternate != '')
		{
			$isd2 = $this->input->post('isd2',TRUE);
			$alternate = $isd2."-".$alternate;
		}

		// GETTING INPUT TEXT VALUES
		$user_data = array( 
					'employee_id'		=>	$this->input->post('employee_id',TRUE),
					'company_id'		=>	$this->session->userdata('company'),
					'role_id'			=>	$role_id,
					'first_name'		=>	$this->input->post('first_name',TRUE),
					'last_name'			=>	$this->input->post('last_name',TRUE),
					'employee_id'		=>	$this->input->post('employee_id',TRUE),
					'email_id'			=>	$this->input->post('email',TRUE),
					'mobile_no'			=>	$mobile_no,
					'alternate_number'	=>	$alternate,
					'branch_id'			=>	$this->input->post('branch_id',TRUE),
					'city'				=>	$this->input->post('cityName', TRUE),
					'address1'			=>	$this->input->post('address1',TRUE),
					'address2'			=>	$this->input->post('address2',TRUE),
					'password'			=>	md5($password),
					'email_id'			=>	$this->input->post('email',TRUE),
					'created_by'		=>	$this->session->userdata('user_id'),
					'created_time'		=>	date('Y-m-d H:i:s')
							);
		$userId = $this->Common_model->insert_data('user',$user_data);
		
		if($role_id==5||$role_id==12) { // if user role is distributor,stokist
			$distributor_data = array( 
					'user_id'				=>  $userId,
					'distributor_name'		=>	$this->input->post('distributor_name',TRUE),
					'PAN_number'			=>	$this->input->post('pan_number',TRUE),
					'TIN_number'			=>	$this->input->post('tin_number',TRUE),
					'TAN_number'			=>	$this->input->post('tan_number',TRUE),
					'service_tax_number'	=>	$this->input->post('service_tax_number',TRUE),
					'sales_tax_number'		=>	$this->input->post('sales_tax_number',TRUE),
					'excise_number'			=>	$this->input->post('excise_number',TRUE),
					'bank_name'				=>	$this->input->post('bank_name',TRUE),
					'branch'				=>	$this->input->post('branch',TRUE),
					'ac_name'				=>	$this->input->post('ac_name',TRUE),
					'ac_no'					=>	$this->input->post('ac_no',TRUE),
					'ifsc'					=>	$this->input->post('ifsc',TRUE),
					'created_by'			=>	$this->session->userdata('user_id'),
					'created_time'			=>	date('Y-m-d H:i:s')
							);
			$this->Common_model->insert_data('distributor_details',$distributor_data);
		}
		
                
		//INESRTING USER COMPANY ROLE HISTORY
		$company_role_history_data = array('user_id'=>$userId,'role_id'=>$role_id,'company_id'=>$this->session->userdata('company'),'start_date'=>date('Y-m-d'));
		$this->Common_model->insert_data('user_company_role_history',$company_role_history_data);
		
		//LOOPING USER LOCATIONS
		$user_locations = array_filter($user_locations);
		if(count($user_locations)>0){
			$user_locations_data = array(); $user_locations_history_data = array();
			foreach($user_locations as $location){
				$user_locations_data[]=array('user_id'=>$userId,'location_id'=>$location);
				$user_locations_history_data[]=array('user_id'=>$userId,'location_id'=>$location,'start_date'=>date('Y-m-d'));
			}
			
			//INSERTING USER LOCATIONS
			$this->Common_model->insert_batch_data('user_location',$user_locations_data);
			//INSERTING USER LOCATIONS HISTORY
			$this->Common_model->insert_batch_data('user_location_history',$user_locations_history_data);
		}
		
		//LOOPING USER PRODUCTS
		$user_products = array_filter($user_products);
		if(count($user_products)>0){
			$user_products_data = array(); $user_products_history_data = array();
			foreach($user_products as $product){
				$user_products_data[]=array('user_id'=>$userId,'product_id'=>$product);
				$user_products_history_data[]=array('user_id'=>$userId,'product_id'=>$product,'start_date'=>date('Y-m-d'));
			}
			
			//INSERTING USER LOCATIONS
			$this->Common_model->insert_batch_data('user_product',$user_products_data);
			//INSERTING USER LOCATIONS HISTORY
			$this->Common_model->insert_batch_data('user_product_history',$user_products_history_data);
		}

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while adding user!
								 </div>');
			redirect(SITE_URL.'users');
				
		}
		else
		{
			$this->db->trans_commit();
                        
                        $to=$this->input->post('email',TRUE);
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
									<strong>Success!</strong> User has been added successfully!
								 </div>');
			redirect(SITE_URL.'users');
		}
		
	}

	public function deleteUser($encoded_id)
	{
		//echo 'hi';
			$user_id=@icrm_decode($encoded_id);
			$where = array('user_id' => $user_id);
			//deactivating user
			$dataArr = array('status' => 2);
			$this->Common_model->update_data('user',$dataArr, $where);
			//deactivating user products
			$dataArr = array('status' => 3);
			$this->Common_model->update_data('user_product',$dataArr, $where);
			//deactivating user locations
			$dataArr = array('status' => 3);
			$this->Common_model->update_data('user_location',$dataArr, $where);

			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User has been De-Activated successfully!
								 </div>');
			redirect(SITE_URL.'users');

	}
	
	public function activateUser($encoded_id)
	{
			$user_id=@icrm_decode($encoded_id);
			$where = array('user_id' => $user_id);
			//activating user
			$dataArr = array('status' => 1);
			$this->Common_model->update_data('user',$dataArr, $where);
			//activating user products
			$dataArr = array('status' => 1);
			$this->Common_model->update_data('user_product',$dataArr, $where);
			//activating user locations
			$dataArr = array('status' => 1);
			$this->Common_model->update_data('user_location',$dataArr, $where);
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User has been Activated successfully!
								 </div>');
			redirect(SITE_URL.'users');

	}
	
	public function downloadUser()
	{
		if($this->input->post('downloadUser')!='') {
			
			$searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'email' => $this->input->post('email', TRUE),
                'mobile' => $this->input->post('mobile', TRUE)
                              );
			$users = $this->User_m->userDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','First Name','Last Name','Employee/Distributor/Stockist ID','Role','Email','Mobile','Address1','Address2','Distributor/Stockist Name','PAN Number','TIN Number','TAN Number','Service Tax Number','Sales Tax Number','Excise Number','Bank','Bank Branch','Account Name','Account Number','IFSC');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th align="center">'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($users)>0)
			{
				
				foreach($users as $row)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td align="center">'.$row['first_name'].'</td>';
					$data.='<td align="center">'.$row['last_name'].'</td>';
					$data.='<td align="center">'.$row['employee_id'].'</td>';
					$data.='<td align="center">'.$row['role'].'</td>';
					$data.='<td align="center">'.$row['email_id'].'</td>';
					$data.='<td align="center">'.$row['mobile_no'].'</td>';
					$data.='<td align="center">'.$row['address1'].'</td>';
					$data.='<td align="center">'.$row['address2'].'</td>';
					//distrbutor/Stockist details
					if($row['role_id']==5||$row['role_id']==7){
						$data.='<td align="center">'.$row['distributor_name'].'</td>';
						$data.='<td align="center">'.$row['PAN_number'].'</td>';
						$data.='<td align="center">'.$row['TIN_number'].'</td>';
						$data.='<td align="center">'.$row['TAN_number'].'</td>';
						$data.='<td align="center">'.$row['service_tax_number'].'</td>';
						$data.='<td align="center">'.$row['sales_tax_number'].'</td>';
						$data.='<td align="center">'.$row['excise_number'].'</td>';
						$data.='<td align="center">'.$row['bank_name'].'</td>';
						$data.='<td align="center">'.$row['branch'].'</td>';
						$data.='<td align="center">'.$row['ac_name'].'</td>';
						$data.='<td align="center">'.$row['ac_no'].'</td>';
						$data.='<td align="center">'.$row['ifsc'].'</td>';
					}
					else{
						for($k=1;$k<=12;$k++){
							$data.='<td align="center">NA</td>';
						}
					}
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
			$xlFile='user_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function viewUserDetails($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "View User Details";
        $data['nestedView']['cur_page'] = 'user';
        $data['nestedView']['parent_page'] = 'user';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'View User Details';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Users', 'class' => '', 'url' => SITE_URL . 'users');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'View User Details', 'class' => 'active', 'url' => '');
			$user_id=@icrm_decode($encoded_id);
			if($user_id>0){
				$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
				$data['user'] = $user[0];
				$data['role_id']=$user[0]['role_id'];
				$data['branch_id'] = $user[0]['branch_id'];
				$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
				$branch = $this->Common_model->get_data('branch',array('branch_id'=>$data['branch_id']));
				$data['userBranch'] = $branch[0]['name'];
				$data['role_level_id'] = $role[0]['role_level_id'];
				if ($data['role_id']==5||$data['role_id']==12) { // if distributor,stockist
					$distributor = $this->Common_model->get_data('distributor_details',array('user_id'=>$user_id));
					if($distributor)
					$data['distributor'] = $distributor[0];
				}
				$data['userRole'] = $role[0]['name'];
				//print_r($role); exit;
				// GET USER LOCATIONS WITH ID,NAME
				$this->db->select('l.*');
				$this->db->from('user_location ul');
				$this->db->join('location l','l.location_id=ul.location_id','INNER');
				$this->db->where('ul.status',1);
				$this->db->where('ul.user_id',$user_id);
				$this->db->order_by('l.territory_level_id ASC,l.location ASC');
				$lres = $this->db->get();
				$raw_user_locations = $lres->result_array();
				//echo $this->db->last_query(); exit();
				$user_locations = array(); $parent_hirarchy = array();$i=0;
				//$loc_hirarchy = $this->User_m->getParentHirarchy(18);
				//echo '<pre>';print_r($loc_hirarchy);echo '</pre>'; exit;
				switch ($data['role_level_id']) {

					case 2: case 3: case 4: case 5: case 6: //sales director, country head, National sales manager, regional Branch head, regional branch manager
						$parent = array(); $childs = array();
						//print_r($raw_user_locations); 
						foreach($raw_user_locations as $location){
							if($i==0){
								//$parent = $this->User_m->getLocationDetailsById($location['parent_id']);
								$parent_hirarchy = $this->User_m->getParentHirarchy($location['parent_id']);
							}
							$user_locations[]=array('location_id'=>$location['location_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
							$i++;
						}
						//print_r($user_locations);
					break;
					case 7: // Distributors, Sales Engineers
						$parent = array(); $childs = array();
						$states = array(); $districts = array(); $cities = array();
						foreach($raw_user_locations as $location){
							if($i==0){
								$locaton_territary_level = $location['territory_level_id'];
								
								$parent_hirarchy = $this->User_m->getParentHirarchy($location['parent_id']);
								switch ($locaton_territary_level) {
									case 6: // If highest level is district
										array_pop($parent_hirarchy); // remove state
										break;
									
									case 7: // if highest level is city
										array_pop($parent_hirarchy); // remove district
										array_pop($parent_hirarchy); // remove state
									break;
								}
							}
							switch ($location['territory_level_id']) {
								case 4:
									$user_locations[]=array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
								break;
								case 5:
									$states[$location['location_id']]=array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());	
								break;
								case 6:
									// check if state already exists in array or not
									if(array_key_exists($location['parent_id'], $states)) {
										$states[$location['parent_id']]['childs'][$location['location_id']] = array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
									}
									else {
										$parent_state = $this->User_m->getLocationDetailsById($location['parent_id']);
										$dist  = array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
										$states[$parent_state['location_id']]=array('location_id'=>$parent_state['location_id'],'parent_id'=>$parent_state['parent_id'],'territory_level_id'=>$parent_state['territory_level_id'],'location_name'=>$parent_state['location'],'childs'=>array($location['location_id']=>$dist));
										$districts[$location['location_id']]=$dist;
									}
									//echo '<pre>';print_r($states); echo '</pre>';
								break;
								case 7:
									// check if district already exists in array or not
									if(array_key_exists($location['parent_id'], $districts)) {

										//get distrit
										$parent_district = $districts[$location['parent_id']];
										//print_r($parent_district); exit;
										//get state id of parent district
										$state_id = $parent_district['parent_id'];
										//pushing into states district childs
										$states[$state_id]['childs'][$location['parent_id']]['childs'][$location['location_id']] = array('location_id'=>$location['location_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
										//print_r($states);
										
									}
									else {
										$parent_district = $this->User_m->getLocationDetailsById($location['parent_id']);
										$parent_state = $this->User_m->getLocationDetailsById($parent_district['parent_id']);
										$city = array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
										$dist  = array('location_id'=>$parent_district['location_id'],'parent_id'=>$parent_district['parent_id'],'territory_level_id'=>$parent_district['territory_level_id'],'location_name'=>$parent_district['location'],'childs'=>array($location['location_id']=>$city));
										$districts[$dist['location_id']]=$dist;
										// check if state already exists in array or not
										if(array_key_exists($parent_district['parent_id'], $states)) {
											$states[$parent_state['location_id']]['childs'][$parent_district['location_id']] = $dist;
										}
										else {
										$states[$parent_state['location_id']]=array('location_id'=>$parent_state['location_id'],'parent_id'=>$parent_state['parent_id'],'territory_level_id'=>$parent_state['territory_level_id'],'location_name'=>$parent_state['location'],'childs'=>array($location['parent_id']=>$dist));
										}

										//print_r($states);
										//print_r($districts);
								}
								break;
							}
							
							$i++;
						}
						if(count($states)>0)
						$user_locations = $states;
					break; // end case for distributors, sales engineers
				}
				//print_r($user_locations);exit;
				//print_r($parent_hirarchy); exit();
				// parent hirarchy string
				$parent_hirarchy_str='';
				if(count($parent_hirarchy)>0) {
					foreach ($parent_hirarchy as $loc) {
						$parent_hirarchy_str.='<i class="fa fa-angle-right"></i> '.$loc['location'].' ';
					}
				}
				$data['user_locations'] = $user_locations;
				$data['parent_hirarchy_str'] = $parent_hirarchy_str;
				//echo '<pre>';print_r($states); echo '</pre>';
				//echo '<pre>';print_r($districts); echo '</pre>'; exit;
				// GET USER PRODUCTS WITH ID
				$products_list = $this->User_m->getProdocutIdsListByUserId($user_id);
				$products = explode(',',$products_list);
				//print_r($products); //exit;
				$productGroups_list =  $this->User_m->getUniqueProductGroupsByProdocuts($products);
				$product_groups = explode(',', $productGroups_list);
				//print_r($product_groups);
				$product_categories =  $this->User_m->getUniqueProductCategoreisByProdocutGroups($product_groups);
				//print_r($product_categories); exit;
				$user_products = array();
				if(count(@$product_categories)>0){
					foreach ($product_categories as $productCategory) {
						
						// get product groups in current category
						$productGroups = $this->User_m->getProductGroupsByCategory($productCategory['category_id'],$product_groups);
						$categoryChilds = array();
						if(count($productGroups)>0){
							foreach ($productGroups as $productGroup) {
								
								// get products in current group
								$group_products = $this->User_m->getProductsByGroup($productGroup['group_id'],$products);
								$groupChilds = array();
								if(count($group_products)>0){
									foreach ($group_products as $group_product) {

										$groupChilds[]=array('product_name'=>$group_product['name'],'product_id'=>$group_product['product_id']);
									}
								}
								$categoryChilds[]=array('product_name'=>$productGroup['name'],'childs'=>$groupChilds);
							}
						}
						$user_products[]=array('product_name'=>$productCategory['name'],'childs'=>$categoryChilds);
					}
				}
				$data['user_products'] = $user_products;
			}
			$this->load->view('user/view-user-details', $data);

	}

	// 27th june 4:30 PM
	public function editUser($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = @$user[0];
		$data['role_id']=@$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Edit User";
		$data['nestedView']['cur_page'] = 'user';
		$data['nestedView']['parent_page'] = 'user';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit User';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Users','class'=>'','url'=>SITE_URL.'users');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit '.@$user[0]['first_name'].'('.@$role[0]['name'].')','class'=>'active','url'=>'');

		$data['en_user_id'] = $encoded_id;
		$this->load->view('user/edit-user', $data);
	}

	// 27th june 5:10 PM
	public function editUserDetails($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = $user[0];
		$data['role_id']=$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];
		$data['isd'] = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
		$data['branch'] = array(''=>'Select Branch') + $this->Common_model->get_dropdown('branch', 'branch_id', 'name', []);

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Edit User Details";
		$data['nestedView']['cur_page'] = 'user';
		$data['nestedView']['parent_page'] = 'user';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/custom/manage-user.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit User Details';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Users','class'=>'','url'=>SITE_URL.'users');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit '.@$user[0]['first_name'].'('.@$role[0]['name'].')','class'=>'','url'=>SITE_URL.'editUser/'.$encoded_id);
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit User Details','class'=>'active','url'=>'');

		
		if ($data['role_id']==5 || $data['role_id'] == 12) { // if distributor
			$distributor = $this->Common_model->get_data('distributor_details',array('user_id'=>$user_id));
			$data['distributor'] = $distributor[0];
		}
		$data['userRole'] = $role[0]['name']; 
		$data['user_id'] = $user_id; 
		$data['encoded_id'] = $encoded_id; 
		$this->load->view('user/edit-user-details', $data);
	}

	// 27th june 7:05 PM
	public function updateUserDetails(){

		// TRANSACTION BEGIN
		$this->db->trans_begin();
		$where = array('user_id'=>$this->input->post('user_id',TRUE));
		$role_id = $this->input->post('role_id',TRUE);
		$mobile = $this->input->post('mobile',TRUE);
		$isd1 = $this->input->post('isd1',TRUE);
		$mobile_no = $isd1."-".$mobile;
		$alternate = $this->input->post('alternate_number',TRUE);
		if($alternate != '')
		{
			$isd2 = $this->input->post('isd2',TRUE);
			$alternate = $isd2."-".$alternate;
		}

		$user_data = array( 
					'employee_id'		=>	$this->input->post('employee_id',TRUE),
					'company_id'		=>	$this->session->userdata('company'),
					'first_name'		=>	$this->input->post('first_name',TRUE),
					'last_name'			=>	$this->input->post('last_name',TRUE),
					'employee_id'		=>	$this->input->post('employee_id',TRUE),
					'email_id'			=>	$this->input->post('email',TRUE),
					'mobile_no'			=>	$mobile_no,
					'alternate_number'	=>	$alternate,
					'branch_id'			=>	$this->input->post('branch_id',TRUE),
					'city'				=>	$this->input->post('city', TRUE),
					'address1'			=>	$this->input->post('address1',TRUE),
					'address2'			=>	$this->input->post('address2',TRUE),
					'email_id'			=>	$this->input->post('email',TRUE),
					'modified_by'		=>	$this->session->userdata('user_id'),
					'modified_time'		=>	date('Y-m-d H:i:s')
							);
		$userId = $this->Common_model->update_data('user',$user_data,$where);
		
		if($role_id==5 || $role_id == 12) { // if user role is distributor
			$distributor_data = array( 
					'distributor_name'		=>	$this->input->post('distributor_name',TRUE),
					'PAN_number'			=>	$this->input->post('pan_number',TRUE),
					'TIN_number'			=>	$this->input->post('tin_number',TRUE),
					'TAN_number'			=>	$this->input->post('tan_number',TRUE),
					'service_tax_number'	=>	$this->input->post('service_tax_number',TRUE),
					'sales_tax_number'		=>	$this->input->post('sales_tax_number',TRUE),
					'excise_number'			=>	$this->input->post('excise_number',TRUE),
					'bank_name'				=>	$this->input->post('bank_name',TRUE),
					'branch'				=>	$this->input->post('branch',TRUE),
					'ac_name'				=>	$this->input->post('ac_name',TRUE),
					'ac_no'					=>	$this->input->post('ac_no',TRUE),
					'ifsc'					=>	$this->input->post('ifsc',TRUE),
					'modified_by'			=>	$this->session->userdata('user_id'),
					'modified_time'			=>	date('Y-m-d H:i:s')
							);
			$this->Common_model->update_data('distributor_details',$distributor_data,$where);
		}

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while updating user details!
								 </div>');
			redirect(SITE_URL.'users');
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User details has been updated successfully!
								 </div>');
			redirect(SITE_URL.'users');
		}
		
	}

	// 28th june 12:48 PM
	public function editUserLocations($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = $user[0];
		$data['role_id']=$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Edit User Details";
		$data['nestedView']['cur_page'] = 'user';
		$data['nestedView']['parent_page'] = 'user';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/custom/manage-user.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit User Details';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Users','class'=>'','url'=>SITE_URL.'users');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit '.@$user[0]['first_name'].'('.@$role[0]['name'].')','class'=>'','url'=>SITE_URL.'editUser/'.$encoded_id);
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit User Locations','class'=>'active','url'=>'');

		
		
		if ($data['role_id']==5) { // if distributor
			$distributor = $this->Common_model->get_data('distributor_details',array('user_id'=>$user_id));
			$data['distributor'] = $distributor[0];
		}
		$data['userRole'] = $role[0]['name']; 
		$data['user_id'] = $user_id;
		$data['encoded_id'] = $encoded_id; 

		// GET USER LOCATIONS WITH ID,NAME
		$this->db->select('l.*');
		$this->db->from('user_location ul');
		$this->db->join('location l','l.location_id=ul.location_id','INNER');
		$this->db->where('ul.status',1);
		$this->db->where('ul.user_id',$user_id);
		$this->db->order_by('l.territory_level_id ASC,l.location ASC');
		$lres = $this->db->get();
		$raw_user_locations = $lres->result_array();

		$user_locations = array(); $parent_hirarchy = array();$i=0;
				switch ($data['role_level_id']) {

					case 2: case 3: case 4: case 5: case 6: //sales director, country head, National sales manager, regional Branch head, regional branch manager
						$parent = array(); $childs = array();

						foreach($raw_user_locations as $location){
							if($i==0){
								$parent_id = $location['parent_id'];
							}
							$user_locations[$location['location_id']]=array('location_id'=>$location['location_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
							$i++;
						}
					$data['user_locations']=$user_locations;
					case 7: // Distributors, Sales Engineers
						$states = array(); $districts = array(); $cities = array(); $level_depth = '';
						foreach($raw_user_locations as $location){
							if($i==0){

								$parent_id = $location['parent_id'];
							}
							if(count($raw_user_locations)==($i+1)){
								$level_depth = $location['territory_level_id'];
							}
							switch ($location['territory_level_id']) {
								case 4:
									$user_locations[]=array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
								break;
								case 5:
									$states[$location['location_id']]=array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());	
								break;
								case 6:
									// check if state already exists in array or not
									if(array_key_exists($location['parent_id'], $states)) {
										$states[$location['parent_id']]['childs'][$location['location_id']] = array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
									}
									else {
										$parent_state = $this->User_m->getLocationDetailsById($location['parent_id']);
										$dist  = array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
										$states[$parent_state['location_id']]=array('location_id'=>$parent_state['location_id'],'parent_id'=>$parent_state['parent_id'],'territory_level_id'=>$parent_state['territory_level_id'],'location_name'=>$parent_state['location'],'childs'=>array($location['location_id']=>$dist));
										$districts[$location['location_id']]=$dist;
									}
									//echo '<pre>';print_r($states); echo '</pre>';
								break;
								case 7:
									// check if district already exists in array or not
									if(array_key_exists($location['parent_id'], $districts)) {

										//get distrit
										$parent_district = $districts[$location['parent_id']];
										//print_r($parent_district); exit;
										//get state id of parent district
										$state_id = $parent_district['parent_id'];
										//pushing into states district childs
										$states[$state_id]['childs'][$location['parent_id']]['childs'][$location['location_id']] = array('location_id'=>$location['location_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
										//print_r($states);
										
									}
									else {
										$parent_district = $this->User_m->getLocationDetailsById($location['parent_id']);
										$parent_state = $this->User_m->getLocationDetailsById($parent_district['parent_id']);
										$city = array('location_id'=>$location['location_id'],'parent_id'=>$location['parent_id'],'territory_level_id'=>$location['territory_level_id'],'location_name'=>$location['location'],'childs'=>array());
										$dist  = array('location_id'=>$parent_district['location_id'],'parent_id'=>$parent_district['parent_id'],'territory_level_id'=>$parent_district['territory_level_id'],'location_name'=>$parent_district['location'],'childs'=>array($location['location_id']=>$city));
										$districts[$dist['location_id']]=$dist;
										// check if state already exists in array or not
										if(array_key_exists($parent_district['parent_id'], $states)) {
											$states[$parent_state['location_id']]['childs'][$parent_district['location_id']] = $dist;
										}
										else {
										$states[$parent_state['location_id']]=array('location_id'=>$parent_state['location_id'],'parent_id'=>$parent_state['parent_id'],'territory_level_id'=>$parent_state['territory_level_id'],'location_name'=>$parent_state['location'],'childs'=>array($location['parent_id']=>$dist));
										}

										//print_r($states);
										//print_r($districts);
								}
								break;
							}
							
							$i++;
						}
						$data['cur_states']=$states;
						$data['cur_districts']=$districts;
						$data['level_depth'] = $level_depth;
					break;
				}
				$parent_hirarchy = $this->User_m->getParentHirarchy(@$parent_id);

				// get all geos
				$data['geos'] = $this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>2));
				if(@$parent_hirarchy[1]){
					$data['cur_geo'] = $parent_hirarchy[1];
					// get current geo countries
					$data['countries'] = $this->Common_model->get_data('location',array('status'=>1,'parent_id'=>$data['cur_geo']['location_id']));
				}
				if(@$parent_hirarchy[2]){
					$data['cur_country'] = $parent_hirarchy[2];
					// get current country regions
					$data['regions'] = $this->Common_model->get_data('location',array('status'=>1,'parent_id'=>$data['cur_country']['location_id']));
				}
				//IF OTR and TERRITORY IS country
				if(@$data['role_id']==15&&$raw_user_locations[0]['territory_level_id']==3){
					$data['cur_country'] = $this->Common_model->get_data_row('location',array('location_id'=>$raw_user_locations[0]['location_id']));;
					// get current country regions
					$data['regions'] = $this->Common_model->get_data('location',array('status'=>1,'parent_id'=>$data['cur_country']['location_id']));
				}
				if(@$parent_hirarchy[3]){
					$data['cur_region'] = $parent_hirarchy[3];
					
				}
				if(@$data['cur_region'])
				// get current region states
				$data['states'] = $this->Common_model->get_data('location',array('status'=>1,'parent_id'=>$data['cur_region']['location_id']));
			//echo '<pre>'; print_r($data); exit;
		$this->load->view('user/edit-user-locations', $data);
	}

	// mahesh 28th june 2016 7:04 PM
	public function updateUserLocations(){
		//echo '<pre>'.print_r($_POST).'</pre>';
		//gettitng locations
		$role_id = $this->input->post('role_id',TRUE);
		$user_id = $this->input->post('user_id',TRUE);
		$user_locations = array();
		switch($role_id) {
			case 11: // Global Head
				$user_locations[]=1;
			break;
			case 10: // Sales Director
				if($this->input->post('geo',TRUE))
				$user_locations = $this->input->post('geo',TRUE);
			break;
			case 9: // Country Head
			case 8: // NSM (National Sales Manager)
				if($this->input->post('country',TRUE))
				$user_locations = $this->input->post('country',TRUE);
			break;
			case 7: // RBH
			case 6: // RSM
			case 15: // OTR
				/*if($this->input->post('region',TRUE))
				$user_locations[] = $this->input->post('region',TRUE);*/
				$region_loc = $this->input->post('region',TRUE);
				if($region_loc!='')
				{
					$user_locations[] = $region_loc;
				}
				else
				{
					$country_loc = $this->input->post('country',TRUE);
					if($country_loc!='')
					$user_locations[] = $country_loc;
				}
			break;
			case 5: // Distributor
			case 4: // Sales Engineer (SE)
				
					$region = $this->input->post('region',TRUE);
					$state = $this->input->post('state',TRUE);
					if(!$state) //  if no states existed
					{
						$user_locations[]=$region;

					}
					else {
						$district = $this->input->post('district',TRUE);
						if(!$district) { // if no districts existed
							$user_locations = $state;
						}
						else {
							$city = $this->input->post('city',TRUE);
							$district_parent = $this->input->post('district_parent',TRUE);
							
							//removing states if districts existed
							foreach($district as $districtId) {
								$dist_stateId = $district_parent[$districtId];
								if (($key = array_search($dist_stateId, $state)) !== false) {
									unset($state[$key]);
								}
							}
							if($city) { // if cities existed
								$city_parent = $this->input->post('city_parent',TRUE);
								// removing districts if cities existed
								foreach($city as $cityId) {
									$city_districtId = $city_parent[$cityId];
									if (($key = array_search($city_districtId, $district)) !== false) {
										unset($district[$key]);
									}
								}
							}
							
							$user_locations = array_merge($state,$district);
							if($city) {
								$user_locations = array_merge($user_locations,$city);
							}
						}
					}
					
					
					
			break;
			
		} // end of switch

		// transaction begins
		$this->db->trans_begin();
		//DEACTIVATING USER CURRENT ALL LOCATIONS
		$where = array('user_id'=>$user_id);
		$data = array('status'=>2);
		$this->Common_model->update_data('user_location',$data, $where);


		//LOOPING USER LOCATIONS
		$user_locations = array_filter($user_locations);
		if(count($user_locations)>0){
			foreach ($user_locations as $location_id) {

				//UPDATE EXIST LOCATIONS AND INSERTING NEW LOCATIONS
				$qry = "INSERT INTO user_location( user_id, location_id, status) 
						VALUES (".$user_id.",".$location_id.",'1')  
						ON DUPLICATE KEY UPDATE status = VALUES(status);";
				$this->db->query($qry);
				//echo $this->db->last_query().'<br>';
			}
		}
		

		if($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while updating user locations!
								 </div>');
			redirect(SITE_URL.'users');
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User locations has been updated successfully!
								 </div>');
			redirect(SITE_URL.'users');
		}
	}

	// mahesh 29th june 11:10 AM
	public function editUserProducts($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = $user[0];
		$data['role_id']=$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Edit User Details";
		$data['nestedView']['cur_page'] = 'user';
		$data['nestedView']['parent_page'] = 'user';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/custom/manage-user.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Edit User Details';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Users','class'=>'','url'=>SITE_URL.'users');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit '.@$user[0]['first_name'].'('.@$role[0]['name'].')','class'=>'','url'=>SITE_URL.'editUser/'.$encoded_id);
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit User Products','class'=>'active','url'=>'');

		
		
		$data['userRole'] = $role[0]['name']; 
		$data['user_id'] = $user_id; 
		$data['encoded_id'] = $encoded_id; 
		// GET USER PRODUCTS WITH ID,NAME
				$products_list = $this->User_m->getProdocutIdsListByUserId($user_id);
				$products = explode(',',$products_list);
				//print_r($products); //exit;
				$productGroups_list =  $this->User_m->getUniqueProductGroupsByProdocuts($products);
				$product_groups = explode(',', $productGroups_list);
				//print_r($product_groups);
				$product_categories =  $this->User_m->getUniqueProductCategoreisByProdocutGroups($product_groups);
				//print_r($product_categories); exit;
				$user_products = array();
				if(count(@$product_categories)>0){
					foreach ($product_categories as $productCategory) {
						
						// get product groups in current category
						$productGroups = $this->User_m->getProductGroupsByCategory($productCategory['category_id']);
						$categoryChilds = array();
						if(count($productGroups)>0){
							foreach ($productGroups as $productGroup) {
								
								// get products in current group
								$group_products = $this->User_m->getProductsByGroup($productGroup['group_id']);
								$groupChilds = array();
								if(count($group_products)>0){
									foreach ($group_products as $group_product) {

										$groupChilds[$group_product['product_id']]=array('name'=>$group_product['description'],'product_id'=>$group_product['product_id']);
									}
								}
								$categoryChilds[$productGroup['group_id']]=array('group_id'=>$productGroup['group_id'],'name'=>$productGroup['name'],'childs'=>$groupChilds);
							}
						}
						$user_products[$productCategory['category_id']]=array('category_id'=>$productCategory['category_id'],'name'=>$productCategory['name'],'childs'=>$categoryChilds);
					}
				}
				$data['user_products'] = $user_products;
				$data['product_categories'] = $product_categories;
				$data['product_groups'] = $product_groups;
				$data['products'] = $products;
				//all product categoreis
				$data['all_productCategories'] = $this->Common_model->get_data('product_category',array('status'=>1));
				
		$this->load->view('user/edit-user-products', $data);
	}

	// mahesh 29th june 12:43 PM
	public function updateUserProducts(){

		$role_id = $this->input->post('role_id',TRUE);
		$user_id = $this->input->post('user_id',TRUE);
		// GETTING products
		$user_products = array();
		switch($role_id) {
			case 11: // Global Head
			case 10: // Sales Director
			case 9: // Country Head
			case 7: // RBH
				$allProducts = getAllProducts();
				foreach($allProducts as $productArr) {
					$user_products[] = $productArr['product_id'];
				}
			break;
			case 4: // sales engineer
			case 5: // distributor
			case 6: // RSM
			case 8: // NSM
				$product = $this->input->post('product',TRUE);
				if($product)
				$user_products = $product;
			break;
		}

		$this->db->trans_begin();
		//DEACTIVATING USER ASSIGNED PRODUCTS
		$where = array('user_id'=>$user_id);
		$data = array('status'=>2);
		$this->Common_model->update_data('user_product',$data, $where);


		//LOOPING USER PRODUCTS
		$user_products = array_filter($user_products);
		if(count($user_products)>0){
			foreach ($user_products as $product_id) {

				//UPDATE EXIST LOCATIONS AND INSERTING NEW LOCATIONS
				$qry = "INSERT INTO user_product( user_id, product_id, status) 
						VALUES (".$user_id.",".$product_id.",'1')  
						ON DUPLICATE KEY UPDATE status = VALUES(status);";
				$this->db->query($qry);
				//echo $this->db->last_query().'<br>';
			}
		}
		


		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while updating user products!
								 </div>');
			redirect(SITE_URL.'users');
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User products has been updated successfully!
								 </div>');
			redirect(SITE_URL.'users');
		}

	}

	//mahesh 29th jun 2016 04:58 pm
	public function changeUserRole($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = $user[0];
		$data['role_id']=$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];
		$data['user_id'] = $user_id;
		$data['encoded_id'] = $encoded_id;
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Manage Users";
		$data['nestedView']['cur_page'] = 'user';
		$data['nestedView']['parent_page'] = 'user';
		$data['nestedView']['enableFormWizard']=1;
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/fuelux/loader.min.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/custom/manage-user.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/fuelux/css/fuelux-responsive.min.css" />';
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Users';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Users','class'=>'','url'=>SITE_URL.'users');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit '.@$user[0]['first_name'].'('.@$role[0]['name'].')','class'=>'','url'=>SITE_URL.'editUser/'.$encoded_id);
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Change User Role','class'=>'active','url'=>'');
		
		# Form data
		$data['companies'] = $this->Common_model->get_data('company',array('status'=>1));
		// GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN, DISTRIBUTOR, STOCKIST, Super User
		$this->db->where('status',1);
		$this->db->where_not_in('role_id',array(1,2,5,12,13,$user[0]['role_id']));
		$res = $this->db->get('role');
		//$data['roles'] = $this->Common_model->get_data('role',array('status'=>1));
		$data['roles'] = $res->result_array();
		$data['geos'] = $this->Common_model->get_data('location',array('status'=>1,'territory_level_id'=>2));
		$data['sess_company'] = $this->session->userdata('company');
		$data['productCategories'] = $this->Common_model->get_data('product_category',array('status'=>1,'company_id'=>$data['sess_company']));
		
		$data['role_id'] =''; $data['role_level_id'] ='';
		if($this->input->post('role_id',TRUE)>0){
			$data['role_id'] =$this->input->post('role_id',TRUE);
			$res = $this->Common_model->get_data('role',array('role_id'=>$data['role_id']));
			$data['role_level_id'] =$res[0]['role_level_id'];
		}
		$this->load->view('user/change-user-role', $data);
	}

	//mahesh 30th june 2016 , updated on 17th july 2016 07:45 PM
	public function updateUserRole(){

		$role_id = $this->input->post('role_id',TRUE);
		$user_id = $this->input->post('user_id',TRUE);
		//gettitng locations
		$user_locations = array();
		switch($role_id) {
			case 11: // Global Head
				$user_locations[]=1;
			break;
			case 10: // Sales Director
				if($this->input->post('geo',TRUE))
				$user_locations = $this->input->post('geo',TRUE);
			break;
			case 9: // Country Head
			case 8: // NSM (National Sales Manager)
				if($this->input->post('country',TRUE))
				$user_locations = $this->input->post('country',TRUE);
			break;
			case 7: // RBH
			case 6: // RSM
				if($this->input->post('region',TRUE))
				$user_locations[] = $this->input->post('region',TRUE);
			break;
			case 5: // Distributor
			case 4: // Sales Engineer (SE)
				
					$region = $this->input->post('region',TRUE);
					$state = $this->input->post('state',TRUE);
					if(!$state) //  if no states existed
					{
						$user_locations[]=$region;
					}
					else {
						$district = $this->input->post('district',TRUE);
						if(!$district) { // if no districts existed
							$user_locations = $state;
						}
						else {
							$city = $this->input->post('city',TRUE);
							$district_parent = $this->input->post('district_parent',TRUE);
							
							//removing states if districts existed
							foreach($district as $districtId) {
								$dist_stateId = $district_parent[$districtId];
								if (($key = array_search($dist_stateId, $state)) !== false) {
									unset($state[$key]);
								}
							}
							if($city) { // if cities existed
								$city_parent = $this->input->post('city_parent',TRUE);
								// removing districts if cities existed
								foreach($city as $cityId) {
									$city_districtId = $city_parent[$cityId];
									if (($key = array_search($city_districtId, $district)) !== false) {
										unset($district[$key]);
									}
								}
							}
							
							$user_locations = array_merge($state,$district);
							if($city) {
								$user_locations = array_merge($user_locations,$city);
							}
						}
					}
					
					
					
			break;
			
		}
		
		// GETTING products
		$user_products = array();
		switch($role_id) {
			case 11: // Global Head
			case 10: // Sales Director
			case 9: // Country Head
			case 7: // RBH
				$allProducts = getAllProducts();
				foreach($allProducts as $productArr) {
					$user_products[] = $productArr['product_id'];
				}
			break;
			case 4: // sales engineer
			case 5: // distributor
			case 6: // RSM
			case 8: // NSM
				$product = $this->input->post('product',TRUE);
				if($product)
				$user_products = $product;
			break;
		}
		//echo '<pre>'.print_r($user_locations).'</pre>';
		//echo '<pre>'.print_r($user_products).'</pre>';
		
		$this->db->trans_begin();
		//UPDATING USER ROLE
		$where = array('user_id'=>$user_id);
		$data = array('role_id'=>$role_id,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d H:i:s'));
		$this->Common_model->update_data('user',$data, $where);

		//UPDATING USER COMPANY ROLE HISTORY / ending current role
		$where = array('user_id'=>$user_id,'status'=>1);
		$data = array('status'=>2,'end_date'=>date('Y-m-d'));
		$this->Common_model->update_data('user_company_role_history',$data, $where);

		//INSERTING NEW USER COMPANY ROLE HISTORY
		$company_role_history_data = array('user_id'=>$user_id,'role_id'=>$role_id,'company_id'=>$this->session->userdata('company'),'start_date'=>date('Y-m-d'));
		$this->Common_model->insert_data('user_company_role_history',$company_role_history_data);
		
		//DEACTIVATING USER LOCATIONS IF HE HAD ALREADY ASSIGNED
		$where = array('user_id'=>$user_id);
		$data = array('status'=>2);
		$this->Common_model->update_data('user_location',$data, $where);
		//LOOPING USER LOCATIONS
		$user_locations = array_filter($user_locations);
		if(count($user_locations)>0){
			$user_locations_data = array(); $user_locations_history_data = array();
			foreach($user_locations as $location_id){
				
				//UPDATE EXIST LOCATIONS AND INSERTING NEW LOCATIONS
				$qry = "INSERT INTO user_location( user_id, location_id, status) 
						VALUES (".$user_id.",".$location_id.",'1')  
						ON DUPLICATE KEY UPDATE status = VALUES(status);";
				$this->db->query($qry);
				//echo $this->db->last_query().'<br>';
			}
			
			
		}
		
		//DEACTIVATING USER ASSIGNED PRODUCTS
		$where = array('user_id'=>$user_id);
		$data = array('status'=>2);
		$this->Common_model->update_data('user_product',$data, $where);
		//LOOPING USER PRODUCTS
		$user_products = array_filter($user_products);
		if(count($user_products)>0){
			$user_products_data = array(); $user_products_history_data = array();
			foreach($user_products as $product_id){
				
				//UPDATE EXIST LOCATIONS AND INSERTING NEW LOCATIONS
				$qry = "INSERT INTO user_product( user_id, product_id, status) 
						VALUES (".$user_id.",".$product_id.",'1')  
						ON DUPLICATE KEY UPDATE status = VALUES(status);";
				$this->db->query($qry);
				//echo $this->db->last_query().'<br>';
			}
			
		}

		/*change user lead status history----------------start---*/
		// get user open leads
		$this->db->where('user_id',$user_id);
		$this->db->where('status<',19);
		$lres = $this->db->get('lead');
		$lresults = $lres->result_array();
		if(count($lresults)>0){
			$lhdata = array();
			foreach ($lresults as $lrow) {
				$lhdata[] = array('lead_id'=>$lrow['lead_id'],
								 'status'=>19,
								 'created_by'=>$this->session->userdata('user_id'),
								 'created_time'=>date('Y-m-d H:i:s')
								 );
			}
			//update user lead status
			$lu_data = array('status'=>19);
			$lu_where= array('user_id'=>$user_id,'status<'=>19);
			$this->Common_model->update_data('lead',$lu_data,$lu_where);
			//INSERTING LEAD STATUS HISTORY
			$this->Common_model->insert_batch_data('lead_status_history',$lhdata);
		}
		/*change user lead status history----------------end---*/


		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while changing user role!
								 </div>');
			redirect(SITE_URL.'users');
				//echo 'transaction failed';
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User role has been changed successfully!
								 </div>');
			redirect(SITE_URL.'users');
			//echo 'transaction success';
		}

		//exit;
	}

	//mahesh 30th june 2:35 pm
	public function productTargetUsers() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage User Product Targets";
        $data['nestedView']['cur_page'] = 'productTargetUser';
        $data['nestedView']['parent_page'] = 'productTargetUser';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage User Product Targets';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage User Product Targets', 'class' => 'active', 'url' => '');

        # Search Functionality

        # Search Functionality
        $psearch=$this->input->post('searchUser', TRUE);
        if($psearch!='') {
        $searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'email' => $this->input->post('email', TRUE),
                'mobile' => $this->input->post('mobile', TRUE)
                              );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'user_role'=>$this->session->userdata('user_role'),
                      'user_name'=>$this->session->userdata('user_name'),
                      'employeeId'=>$this->session->userdata('employeeId'),
                      'email'=>$this->session->userdata('email'),
                      'mobile'=>$this->session->userdata('mobile')
                              );
            }
            else {
                $searchParams=array(
                      'user_role'=>'',
                      'user_name'=>'',
                      'employeeId' =>'',
                      'email' => '',
                      'mobile' => ''
                                  );
                $this->session->set_userdata($searchParams);
            }
            
        }
        $data['search_data'] = $searchParams;


        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'productTargetUsers/';
        # Total Records
        $config['total_rows'] = $this->User_m->productTargetUserTotalRows($searchParams);

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

        # Loading the data array to send to View
        $data['userSearch'] = $this->User_m->productTargetUserResults($current_offset, $config['per_page'], $searchParams);
        // GET ROLES of SALES ENGINEER,RSM,RBH,NSM,COUNTRY HEAD,SALES DIRECTOR,GLOBAL HEAD which are having product targets
        $data['roles'] = $this->User_m->getProductTargetRoles();
        $data['searchParams'] = $searchParams;

        $this->load->view('user/product-target-users', $data);
    }

    // 30th june 03:39 PM
	public function assignUserProductTargets($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = @$user[0];
		$data['role_id']=@$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Assign User Product Targets";
		$data['nestedView']['cur_page'] = 'productTargetUser';
		$data['nestedView']['parent_page'] = 'productTargetUser';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Assign User Product Targets';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage User Product Targets','class'=>'','url'=>SITE_URL.'productTargetUsers');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>@$user[0]['first_name'].'('.@$role[0]['name'].')','class'=>'active','url'=>'');

		$data['encoded_id'] = $encoded_id;
		/* FORMATTING MONTHS  START */
		$months_arr = getMonths();
		$months = array();
		foreach ($months_arr as $month) {
			$months[$month['month_id']]= $month['month'];
		}
		//print_r($months_arr); exit;
		$current_month = date('m');
		$current_year = date('Y');
		$financial_year_months = array();
		// defining year
		if($current_month<=3){
			$yr1 = $current_year-1;
			$yr2 = $current_year;
		}
		else{
			$yr1 = $current_year;
			$yr2 = $current_year+1;
		}
		//looping from apirl to december
		for($i=4;$i<=12;$i++) {
			
			$financial_year_months[] = array('month_id'=>$i,'month'=>$months[$i],'year'=>$yr1);
		}
		//looping from january to march
		for($i=1;$i<=3;$i++) {
			$yr = ($current_month>$i)?$current_year:($current_year+1);
			$financial_year_months[] = array('month_id'=>$i,'month'=>$months[$i],'year'=>$yr2);
		}
		/*FORMATTING MONTHS END*/
		$data['months'] = $financial_year_months;
		$data['user_products'] = $this->User_m->getUserProducts($user_id);
		// GET USER ASSIGNED PRODUCTS

		//get user product targets for the current year
		$product_targets = $this->User_m->getUserProductTargets($user_id);
		$user_product_targerts = array();
		if(count($product_targets)>0){
			foreach ($product_targets as $pt_row) {
				$user_product_targerts[$pt_row['product_id']][$pt_row['month_id']][$pt_row['year_id']]=$pt_row['quantity'];
			}
		}

		$data['user_product_targerts'] = $user_product_targerts;

		$this->load->view('user/assign-user-product-targets', $data);
	}

	//mahesh 1st july 12:30PM
	public function updateProductTargets(){

		
		$encoded_id = $this->input->post('encoded_id');
		$user_id=@icrm_decode($encoded_id);
		$user_products = $this->User_m->getUserProducts($user_id);

		//$product_ar = $this->input->post('product');
		//echo count($_POST); echo ini_get('max_input_vars');
		$year_ar = $this->input->post('year');
		//$year = date('Y');
		$months = getMonths();

		//get user product targets for the current year
		/*$product_targets = $this->User_m->getUserProductTargets($user_id);
		$user_product_targerts = array();
		if(count($product_targets)>0){
			foreach ($product_targets as $pt_row) {
				$user_product_targerts[$pt_row['product_id']][$pt_row['month_id']]=$pt_row['quantity'];
			}
		}*/
		//echo '<pre>';print_r($_POST); echo '</pre>'; exit;
		//echo "<pre>";print_r($product_ar); echo '</pre>';

		//looping user products
		if(count($user_products)>0){
			$this->db->trans_begin();
			foreach ($user_products as $product) {
				
				//LOOPING MONTHS
				foreach ($months as $month) {

					$quantity = @$this->input->post('product_'.$product['product_id'].'_'.$month['month_id']);
					$year = @$year_ar[$month['month_id']];
					//UPDATE EXIST LOCATIONS AND INSERTING NEW LOCATIONS
					if($quantity != "")
					{
						$qry = "INSERT INTO user_product_target( user_id, product_id,year_id,month_id,quantity, status) 
								VALUES (".$user_id.",".$product['product_id'].",".$year.",".$month['month_id'].",".$quantity.",1)  
								ON DUPLICATE KEY UPDATE quantity = VALUES(quantity);";
						$this->db->query($qry);
					}
					//echo $this->db->last_query().'<br>';
				}
			}
		}

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while adding user product targets!
								 </div>');
			redirect(SITE_URL.'productTargetUsers');
			//echo 'transaction failed';
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User product targets has been added successfully!
								 </div>');
			redirect(SITE_URL.'productTargetUsers');
			//echo 'transaction success';
		}
	}

	// 4th july 10:56 AM
	public function bulkUploadUserProductTargets($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = @$user[0];
		$data['role_id']=@$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Bulk Upload User Product Targets";
		$data['nestedView']['cur_page'] = 'productTargetUser';
		$data['nestedView']['parent_page'] = 'productTargetUser';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Bulk Upload User Product Targets';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage User Product Targets','class'=>'','url'=>SITE_URL.'productTargetUsers');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>@$user[0]['first_name'].'('.@$role[0]['name'].')','class'=>'active','url'=>'');

		$data['encoded_id'] = $encoded_id;


		$this->load->view('user/bulk-upload-user-product-targets', $data);
	}

	// 4th july 11:16 AM
	public function downloadUserProductTargetsCsv($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = @$user[0];
		$data['role_id']=@$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];

		$data['encoded_id'] = $encoded_id;
		/* FORMATTING MONTHS  START*/
		$months_arr = getMonths();
		$months = array();
		foreach ($months_arr as $month) {
			$months[$month['month_id']]= $month['month'];
		}
		$current_month = date('m');
		$current_year = date('Y');
		$financial_year_months = array();
		// defining year
		if($current_month<=3){
			$yr1 = $current_year-1;
			$yr2 = $current_year;
		}
		else{
			$yr1 = $current_year;
			$yr2 = $current_year+1;
		}
		//looping from apirl to december
		for($i=4;$i<=12;$i++) {
			
			$financial_year_months[] = array('month_id'=>$i,'month'=>$months[$i],'year'=>$yr1);
		}
		//looping from january to march
		for($i=1;$i<=3;$i++) {
			$yr = ($current_month>$i)?$current_year:($current_year+1);
			$financial_year_months[] = array('month_id'=>$i,'month'=>$months[$i],'year'=>$yr2);
		}
		/* FORMATTING MONTHS  END*/
		$user_products = $this->User_m->getUserProducts($user_id);
		// GET USER ASSIGNED PRODUCTS

		//get user product targets for the current year
		$product_targets = $this->User_m->getUserProductTargets($user_id);
		$user_product_targerts = array();
		if(count($product_targets)>0){
			foreach ($product_targets as $pt_row) {
				$user_product_targerts[$pt_row['product_id']][$pt_row['month_id']][$pt_row['year_id']]=$pt_row['quantity'];
			}
		}

		$data['user_product_targerts'] = $user_product_targerts;

			$data ='';
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			$data.='<th>Product ID</th>';
			$data.='<th>Product Name</th>';
			$data.='<th>Product Description</th>';
			foreach ($financial_year_months as $month)
			{
				$data.= '<th>'.$month['month'].'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($user_products)>0)
			{
				
				foreach($user_products as $product)
				{
					$data.='<tr>';
					$data.='<td>'.@$product['product_id'].'</td>';
					$data.='<td>'.@$product['name'].'</td>';
					$data.='<td>'.substr(@$product['description'],0,250).'</td>';
					 foreach ($financial_year_months as $month) {
                        $quantity='';
                        if(@$user_product_targerts[$product['product_id']][$month['month_id']][$month['year']]>0);
                        $quantity = @$user_product_targerts[$product['product_id']][$month['month_id']][$month['year']];
                        $data.='<td>'.@$quantity.'</td>';
                     }
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="15" align="center">No Products Assigned</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='productTargets_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
	}

	//mahesh 4th july 02:42PM
	public function csvUploadUserProductTargets(){

		//echo '<pre>';print_r($_POST); echo '</pre>'; exit;
		$encoded_id = $this->input->post('encoded_id');
		$user_id=@icrm_decode($encoded_id);
		$user_products = $this->User_m->getUserProducts($user_id);

		//$year = date('Y');
		$months = getMonths();
		$current_month = date('m');
		$current_year = date('Y');
		$financial_year_months = array();
		// defining year
		if($current_month<=3){
			$yr1 = $current_year-1;
			$yr2 = $current_year;
		}
		else{
			$yr1 = $current_year;
			$yr2 = $current_year+1;
		}
		$user_product_ids = array();
		//looping user products
		if(count($user_products)>0){
			
			foreach ($user_products as $product) {
				$user_product_ids[]=$product['product_id'];
				
			}
		}

		$filename= $_FILES["uploadCsv"]["tmp_name"];
		$allQrys = '';
		 if($_FILES["uploadCsv"]["size"] > 0)
		 {
			 
		  	$file = fopen($filename, "r");
			$i=0;
			$j=0;
			$this->db->trans_begin();
	         while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
				
				 if($j==0) {
					 $j++;
					 continue;
				 }
				
				$product_id = $emapData[0];
				//LOOPING MONTHS
				$pos = 2; $mnth= 4; // assigning staring month from april
				for($m=1;$m<=12;$m++){
					$i=$m+$pos;
					
					$quantity = $emapData[$i];
					$year = ($mnth>=4)?$yr1:$yr2; // yr1 is for apr to dec, yr2 is for jan to mar
					//UPDATE EXIST LOCATIONS AND INSERTING NEW LOCATIONS
					if($quantity != '')
					{
						$qry = "INSERT INTO user_product_target( user_id, product_id,year_id,month_id,quantity, status) 
								VALUES (".$user_id.",".$product_id.",".$year.",".$mnth.",'".$quantity."',1)  
								ON DUPLICATE KEY UPDATE quantity = VALUES(quantity);";
						$this->db->query($qry);
					}
					//echo $this->db->last_query().'<br>';
					if($m==9)// if month is december reset month to jan
					$mnth = 1;
					else $mnth++; // incrementing month
				}
				
	          
	         }
	         fclose($file);
	        
			
		 }

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Error!</strong> There\'s a problem occured while adding user product targets!
								 </div>');
			redirect(SITE_URL.'productTargetUsers');
			//echo 'transaction failed';
				
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User product targets has been added successfully!
								 </div>');
			redirect(SITE_URL.'productTargetUsers');
			//echo 'transaction success';
		}
	}

	//mahesh 12th july 2016
	public function user_productTargetVsActual(){

		

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="User Product Target Vs Actual";
		$data['nestedView']['cur_page'] = 'userProductTargetVsActual';
		$data['nestedView']['parent_page'] = 'userProductTargetVsActual';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'User Product Target Vs Actual';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Target Vs Actual','class'=>'active','url'=>'');
		

		/* FORMATTING MONTHS  START */
		$months_arr = getMonths();
		$months = array();
		foreach ($months_arr as $month) {
			$months[$month['month_id']]= $month['month'];
		}
		//print_r($months_arr); exit;
		$current_month = date('m');
		$current_year = date('Y');
		$financial_year_months = array();
		// defining year
		if($current_month<=3){
			$yr1 = $current_year-1;
			$yr2 = $current_year;
		}
		else{
			$yr1 = $current_year;
			$yr2 = $current_year+1;
		}
		//looping from apirl to december
		for($i=4;$i<=12;$i++) {
			
			$financial_year_months[] = array('month_id'=>$i,'month'=>$months[$i],'year'=>$yr1);
		}
		//looping from january to march
		for($i=1;$i<=3;$i++) {
			$yr = ($current_month>$i)?$current_year:($current_year+1);
			$financial_year_months[] = array('month_id'=>$i,'month'=>$months[$i],'year'=>$yr2);
		}
		/*FORMATTING MONTHS END*/
		if($this->input->post('action')=='submit'){
			$month_year = $this->input->post('month_year');
			$user_id = $this->input->post('user_id');
			$arr = explode('_', $month_year);
			$cur_month = $arr[0];
			$cur_year = $arr[1];
			if($user_id=='')
			$user_id = $this->session->userdata('user_id');
		}
		else{
			$user_id = $this->session->userdata('user_id');
			$cur_month = date('m');

			$cur_year = ($cur_month<=3)?$yr2:$yr1;
		}

		$user_locations_arr = getUserLocations($user_id);
		$data['locationString'] = getQueryArray($user_locations_arr);
		$data['cur_month'] = $cur_month;
		$data['cur_year'] = $cur_year;
		$data['user_id'] = $user_id;
		$data['role_id'] = getUserRole($user_id);
		$data['months'] = $financial_year_months;
		$data['yr1'] = $yr1;
		//echo $user_id;
		$data['user_products'] = $this->User_m->getUserProducts($user_id);
		// GET USER ASSIGNED PRODUCTS


		//get user product targets for the current year
		$product_targets = $this->User_m->getUserProductTargetsForMonth($user_id,$cur_month,$cur_year);
		$user_product_targerts = array();
		if(count($product_targets)>0){
			foreach ($product_targets as $pt_row) {
				$user_product_targerts[$pt_row['product_id']]=$pt_row['quantity'];
			}
		}

		$data['user_product_targerts'] = $user_product_targerts;
		

		$this->load->view('user/user-product-targetVsActual', $data);
	}

}
?>