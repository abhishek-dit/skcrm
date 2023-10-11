<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class User_product_target extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("User_m");
	}
	/* phase2 changes
	   new controller
	   modified by prasad 
	*/
	//weekly user product targets
	public function weekly_user_product_targets()
	{
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
        $config['base_url'] = SITE_URL . 'weekly_user_product_targets/';
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

        $this->load->view('user_product_target/weekly_user_product_target', $data);
	}

	public function bulk_upload_weekly_user_product_targets($encoded_id)
	{
		$user_id=@icrm_decode($encoded_id);
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = @$user[0];
		$data['role_id']=@$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];

		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] ="Bulk Upload Weekly User Product Targets";
		$data['nestedView']['cur_page'] = 'productTargetUser';
		$data['nestedView']['parent_page'] = 'productTargetUser';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
		 $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/weekly_user_product_target.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Bulk Upload Weekly User Product Targets';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage User Product Targets','class'=>'','url'=>SITE_URL.'weekly_user_product_targets');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>@$user[0]['first_name'].'('.@$role[0]['name'].')','class'=>'active','url'=>'');

		$data['encoded_id'] = $encoded_id;
		$data['fy_years'] = $this->Common_model->get_data('financial_year',array('status'=>1));
		$current_month = date('m');
		$current_year = date('Y');
		$financial_year_months = array();
		// defining year
		$curdate=date('Y-m-d');
		//retreving financial year id based on year
		$data['fy_id'] = $this->Common_model->get_value('financial_year',array("start_date<="=>$curdate,"end_date>="=>$curdate,'status'=>1),'fy_id');

		$this->load->view('user_product_target/bulk_upload_weekly_user_product_targets', $data);
	}

	public function download_weekly_user_product_target_csv()
	{
		$user_id=@icrm_decode($this->input->post('encoded_id'));
		$user = $this->Common_model->get_data('user',array('user_id'=>$user_id));
		$data['user'] = @$user[0];
		$data['role_id']=@$user[0]['role_id'];
		$role = $this->Common_model->get_data('role',array('role_id'=>$data['role_id'])); 
		$data['role_level_id'] = $role[0]['role_level_id'];

		$data['encoded_id'] = $user_id;
		/* FORMATTING MONTHS  START*/
		$months_arr = getMonths();
		$months = array();
		foreach ($months_arr as $month) {
			$months[$month['month_id']]= $month['month'];
		}
		$financial_year_months = array();
		//retreving financial year id based on year
		$fy_id = $this->input->post('fy_id');
		$fy_results= $this->Common_model->get_data_row('financial_year',array('fy_id'=>$fy_id));
		$res = $this->Common_model->get_data('custom_fy_week',array('status'=>1,'fy_id'=>$fy_id));
		
			$weeks = array();
			foreach ($res as $key => $value)
			{
				if(array_key_exists(@$keys, $weeks))
				{
					$weeks[$value['month_no']]['week'][$value['week_no']]=array(
						'start_date' => $value['start_date'],
						'end_date'   => $value['end_date'],
						'week_no'    => $value['week_no'],
						'fy_week_id' => $value['fy_week_id'],
						'month_name' => date('F',mktime(0,0,0, $value['month_no'],10))
						);
				}
				else
				{
					$weeks[$value['month_no']]['month'] = date('F',mktime(0,0,0, $value['month_no'],10));
					$weeks[$value['month_no']]['week'][$value['week_no']]=array(
						'start_date' => $value['start_date'],
						'end_date'   => $value['end_date'],
						'week_no'    => $value['week_no'],
						'fy_week_id' => $value['fy_week_id'],
						'month_name' =>  date('F',mktime(0,0,0, $value['month_no'],10))
						);
				}
			}
	
		$user_products = $this->User_m->getUserProducts($user_id);
		// GET USER ASSIGNED PRODUCTS

		//get user product targets for the current year
		$product_targets = $this->User_m->getUserWeeklyProductTargets($user_id,$fy_id);
		$user_product_targets = array();
		if(count($product_targets)>0){
			foreach ($product_targets as $pt_row) {
				$user_product_targets[$pt_row['product_id']][$pt_row['fy_week_id']]=$pt_row['quantity'];
			}
		}
		//$data['user_product_targerts'] = $user_product_targerts;

			$data ='';
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			$data.='<th rowspan="2">Product ID</th>';
			$data.='<th rowspan="2">Product Code</th>';
			$data.='<th rowspan="2">Product Name</th>';
			foreach ($weeks as $key => $value)
			{
				$data.= '<th colspan='.count($value['week']).'>'.$value['month'].'</th>';
				
			}
			$data.='</tr>';
			$data.='<tr>';
			foreach ($weeks as $key => $value)
			{
				foreach($value['week'] as $k1 => $v1 )
				{
					$data.= '<th>W'.$v1['week_no'].'</th>';
				}
			}
			$data.'</tr>';
			
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
					 foreach ($weeks as $key => $value) 
					 	{ 
					 		foreach ($value['week'] as $k1 => $v1) {
					 			 $quantity='';
		                        if(@$user_product_targets[$product['product_id']][$v1['fy_week_id']]>0);
		                        $quantity = @$user_product_targets[$product['product_id']][$v1['fy_week_id']];
		                        $data.='<td>'.@$quantity.'</td>';
		                    }
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
			$time = date('Y',strtotime($fy_results['start_date'])).'-'.date('y',strtotime($fy_results['end_date'])).'_'.date("his");
			$xlFile='weeklyproductTargets_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
	}

	public function csv_upload_weekly_user_product_targets()
	{
        $encoded_id = $this->input->post('encoded_id');
		$user_id=@icrm_decode($encoded_id);
		$fy_id = $this->input->post('fy_year');
		$fy_results= $this->Common_model->get_data_row('financial_year',array('fy_id'=>$fy_id));
        $res = $this->Common_model->get_data('custom_fy_week',array('status'=>1,'fy_id'=>$fy_id));
		
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
				if($j==0 || $j==1)
				{
					 $j++;
					 continue;
				}
				$product_id = $emapData[0];
				$pos = 2; 
				foreach($res as $key => $value) 
				{
					$i=$value['week_no']+$pos;
					
					$quantity = $emapData[$i];
					if($quantity == '')
					{
						$quantity = 0;
					}
					//UPDATE EXIST LOCATIONS AND INSERTING NEW LOCATIONS
					$qry = "INSERT INTO weekly_user_product_target( user_id, product_id,fy_week_id,quantity, status) 
								VALUES (".$user_id.",".$product_id.",".$value['fy_week_id'].",'".$quantity."',1)  
								ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)";
						$this->db->query($qry);
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
									<strong>Error!</strong> There\'s a problem occured while adding user product targets for userID( '.$user_id.' )!
								 </div>');
			redirect(SITE_URL.'weekly_user_product_targets');
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<div class="icon"><i class="fa fa-check"></i></div>
									<strong>Success!</strong> User product targets has been added successfully for userID( '.$user_id.' ) for financial year '.date('Y',strtotime($fy_results['start_date'])).'-'.date('y',strtotime($fy_results['end_date'])).'!
								 </div>');
			redirect(SITE_URL.'weekly_user_product_targets');
		}
	}
}