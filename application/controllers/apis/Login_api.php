<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type,Accept,Authorization');
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Login_api extends CI_Controller {

	public  function __construct() 
	{
        parent::__construct();

		//$this->load->helper(array('form', 'url'));
		//$this->load->library('form_validation');
		//$this->load->library('global_functions');
		//$this->load->library('curl_operations');
		//$this->load->library('encrypt');
		$this->load->model("Common_model");
		$this->load->model("Branch_model");
		//mahesh 3rd august 2016 03:59 pm
		date_default_timezone_set('Asia/Kolkata');
	}

	public function login()
	{
	 $json = file_get_contents('php://input');
	 $post_data1 = base64_decode($json);
     $post_data = json_decode($post_data1,TRUE);
     $user_id = $post_data['user_id'];
	 $password = $post_data['password'];
     $this->load->model('loginmodel');
     $count = $this->loginmodel->user_login($user_id,$password);
	 
        if($count == 1)
        {
            $details = $this->loginmodel->user_details($user_id,$password);
            if($details['status']==2) // Inactive user
            {
               $data['response'] =0;
               $data['error']='Access Denied ! Please Contact Admin.';
            //   header("Status: 404 Not Found",true,404);
            }
            elseif ($details['role_id']==1||$details['role_id']==2||$details['role_id']==3||$details['role_id']==5||$details['role_id']==13||$details['role_id']==14||$details['role_id']==15||$details['role_id']==11||$details['role_id']==12||$details['role_id']==10) 
            {
            	$data['response'] =0;
                $data['error']="Access Denied ! You Don't have Access to this application.";
            }
            else
            {
              //  header("HTTP/1.1 200 OK");
                $data['response'] =1;
                $data['user_id'] = $details['user_id'];
                $data['employee_id'] = $details['employee_id'];
                $data['name'] = $details['first_name'].' '.$details['last_name'];
				$data['region'] = $details['location'];
                $data['company'] = $details['company_id'];
				$data['company_name'] = $details['company'];
				$_SESSION['user_id'] = $details['user_id'];
				$_SESSION['employee_id'] = $details['employee_id'];
				$_SESSION['name'] = $details['first_name'].' '.$details['last_name'];
				$_SESSION['company'] = $details['company_id'];
				$_SESSION['company_name'] = $details['company'];
                if($details['role_id'] == 13)
                {
                    $data['s_role_id'] = $details['role_id'];
                    $data['role_id'] = 2;
					$data['role_name'] = 'Admin';
					$_SESSION['s_role_id'] = $details['role_id'];
					$_SESSION['role_id'] = 2;
					$_SESSION['role_name'] = 'Admin';
                }
                else 
                {
                    $data['role_id'] = $details['role_id'];
					$data['role_name'] = $details['role'];
					$_SESSION['role_id'] = $details['role_id'];
					$_SESSION['role_name'] = $details['role'];
                }

                if($details['role_id'] == 13)
                {
                    $role_details = $this->loginmodel->role_details($details['role_id']);
					$data['role'] = $role_details;
					$_SESSION['role'] = $role_details;

                }
                $l = getUserLocations($details['user_id']);
				$data['locationString'] = getQueryArray($l);
				$_SESSION['locationString'] = $data['locationString'];
                $data['reportees'] = getReportingUsers($details['user_id']);
				$_SESSION['reportees'] =  $data['reportees'];
				$data['products'] = getUserProducts($details['user_id']);
				$_SESSION['products'] =  $data['products'];
				$data['userProductReportees'] = getUserProductReportees();
				$_SESSION['userProductReportees'] =  $data['userProductReportees'];
				$data['is_punched_in'] = $this->loginmodel->get_last_punch_in();

                //Insert login time, mahesh code 3rd august 2016 03:06 pm 
                $user_log_data = array(
                                'user_id'			=> $details['user_id'],
                                'login_time' 		=> date('Y-m-d H:i:s'),
                                'ip_address' 		=> $post_data['ip_address'],
                                'user_agent_info'   => $_SERVER['HTTP_USER_AGENT']
							);
				$this->db->insert('user_logs',$user_log_data);
				$userLogId = $this->db->insert_id();
				
				//$log_qry1 = 'UPDATE live_location SET end_time = NULL WHERE user_id = '.$details['user_id'].' ORDER BY user_id DESC LIMIT 1';
				//$this->db->query($log_qry1);
			}
			// $log_qry2 = 'UPDATE mobile_live_location SET end_time = NULL WHERE user_id = '.$user_id.' ORDER BY mobile_live_location_id';
	  		//added on 25-03-2022 to update mobile_live_location end

			//added on 25-03-2022 to update mobile_live_location
			// $this->db->query($log_qry2);
        }
        else
        {
            $data['response'] =0;
            $data['error']='Username or Password do not match.';
           // header("Status: 404 Not Found",true,404);
		}
		$this->session->sess_destroy(); 
		echo json_encode($data);
		
    }

	public function logout()
	{
		$json = file_get_contents('php://input');
        
	       $post_data1 = base64_decode($json);
              $post_data = json_decode($post_data1,TRUE);
        
		//UPDATE USER LOG , mahesh 3rd august 2016 03:43 pm
		$user_id = $post_data['user_id'];
		$this->db->trans_begin();
		if($user_id)
		{
			$log_qry = 'UPDATE user_logs SET logout_time = "'.date('Y-m-d H:i:s').'" WHERE user_id = '.$user_id.' ORDER BY log_id DESC LIMIT 1';
			$this->db->query($log_qry);

			// $log_qry1 = 'UPDATE live_location SET end_time = "'.date('Y-m-d H:i:s').'" WHERE user_id = '.$user_id.' ORDER BY user_id DESC LIMIT 1';
			// $this->db->query($log_qry1);

			// $log_qry2 = 'UPDATE mobile_live_location SET end_time = "'.date('Y-m-d H:i:s').'" WHERE user_id = '.$user_id.' ORDER BY mobile_live_location_id';
			//added on 25-03-2022 to update mobile_live_location end
	
			//added on 25-03-2022 to update mobile_live_location
			// $this->db->query($log_qry2);
		}
		$this->db->trans_commit();
		$data['response'] = "Logged Out Successfully";
		echo json_encode($data);
		header("HTTP/1.1 200 OK");
	}

	
	

	public function punch_in()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
		$user_id = $post_data['user_id'];
        $check = $this->Branch_model->check_punch_in($user_id);
        if($check['punch_in_id']>0)
        {
        	$data['response'] ='You are already Punched In';
            echo json_encode($data);
			header("Status: 208 Not Found",true,208);
        }
        else
        {
        	$this->db->trans_begin();
        	$dataArr = array(
        			  'user_id'            => $post_data['user_id'],
        			  'start_time'         => date('Y-m-d H:i:s'),
        			  'longitude'          => $post_data['longitude'],
        			  'latitude'	       => $post_data['latitude'],
        			  'battery_percentage' => $post_data['battery_percent'],
        			  'status'			   => 1,
        			  'created_by'         => $post_data['user_id'],
        			  'created_time'       => date('Y-m-d H:i:s')
        			 );
        	$this->Common_model->insert_data('punch_in',$dataArr);

			$log_qry1 = 'UPDATE live_location SET end_time = NULL WHERE user_id = '.$user_id.' ORDER BY live_location_id DESC LIMIT 1';
			
			//added on 25-03-2022 to update mobile_live_location
			$log_qry2 = 'UPDATE mobile_live_location SET end_time = NULL WHERE user_id = '.$user_id.' ORDER BY mobile_live_location_id';
	  		//added on 25-03-2022 to update mobile_live_location end


			$this->db->query($log_qry1);
			//added on 25-03-2022 to update mobile_live_location
			$this->db->query($log_qry2);
	  		//added on 25-03-2022 to update mobile_live_location end

        	if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$data['response'] = "Something Went Wrong";
				echo json_encode($data);
				header("Status: 404 Not Found",true,404);
			}
			else
			{
				$this->db->trans_commit();
				$data['response'] = "Punched in Successfully";
				echo json_encode($data);
				header("HTTP/1.1 200 OK");
			}
        }
	}

	public function punch_out()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $user_id = $post_data['user_id'];
        $this->db->trans_begin();
        $dataArr = array(
        			  'end_time'           => date('Y-m-d H:i:s'),
        			  'status'			   => 2,
        			  'modified_by'        => $post_data['user_id'],
        			  'modified_time'      => date('Y-m-d H:i:s')
        			 );
        $punch_in_id = $this->Common_model->get_value('punch_in',array('end_time'=>NULL,'user_id'=>$user_id),'punch_in_id');
		$this->Common_model->update_data('punch_in',$dataArr,array('punch_in_id'=>$punch_in_id));
		
		$log_qry1 = 'UPDATE live_location SET end_time = "'.date('Y-m-d H:i:s').'" WHERE user_id = '.$user_id.' ORDER BY live_location_id DESC LIMIT 1';
		
		//added on 25-03-2022 to update mobile_live_location
		$log_qry2 = 'UPDATE mobile_live_location SET end_time = "'.date('Y-m-d H:i:s').'" WHERE user_id = '.$user_id.' ORDER BY mobile_live_location_id';
		//added on 25-03-2022 to update mobile_live_location end


		$this->db->query($log_qry1);
		//added on 25-03-2022 to update mobile_live_location
		$this->db->query($log_qry2);
		//added on 25-03-2022 to update mobile_live_location end
		
        if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = "Something Went Wrong";
			echo json_encode($data);
			header("Status: 404 Not Found",true,404);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = "Punched out Successfully";
			echo json_encode($data);
			header("HTTP/1.1 200 OK");
		}
	}

	public function get_se_data()
	{
		$json = file_get_contents('php://input');
        
	 $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        //$company_id = $post_data['company_id'];
		$data['se_list'] = $this->Branch_model->get_SE_users();
		//$this->session->sess_destroy();
        echo json_encode($data);
	}

	public function force_update()
	{
		$data['version'] = '1.1.6';
        echo json_encode($data);
	}
}
?>
