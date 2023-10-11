<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class Login extends CI_Controller {

	public  function __construct() 
	{
        parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('global_functions');
		$this->load->library('curl_operations');
		$this->load->library('encrypt');
		//mahesh 3rd august 2016 03:59 pm
		date_default_timezone_set('Asia/Kolkata');
	}

	public function login()
      	{   //echo "Testing";
 		if(isset($_POST['submit']))
		{       
			$user_id = $_POST['user_id'];
			$password = $_POST['password'];
			$this->load->model('loginmodel');
			$count = $this->loginmodel->user_login($user_id,$password);
			if($count == 1)
			{
				$details = $this->loginmodel->user_details($user_id,$password);
				if($details['status']==2) // Inactive user
				{
					$this->session->set_flashdata('response', '<div class="row"><div class="col-sm-1 col-md-1"></div><div class="col-sm-10 col-md-10"><div class="alert alert-danger alert-white rounded"  style="margin-top:20px;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="icon"><i class="fa fa-times-circle"></i><strong>Error!</strong> Access Denied. Contact Admin</div></div><div class="col-sm-1 col-md-1"></div></div>');
					redirect(SITE_URL.'login'); exit;
				}
				$_SESSION['user_id'] = $details['user_id'];
				$_SESSION['employee_id'] = $details['employee_id'];
				$_SESSION['name'] = $details['first_name'].' '.$details['last_name'];
				$_SESSION['company'] = $details['company_id'];
				$_SESSION['company_name'] = $details['company'];
				if($details['role_id'] == 13)
				{
					$_SESSION['s_role_id'] = $details['role_id'];
					$_SESSION['role_id'] = 2;
					$_SESSION['role_name'] = 'Admin';
				}
				else 
				{
					$_SESSION['role_id'] = $details['role_id'];
					$_SESSION['role_name'] = $details['role'];
				}

				if($details['role_id'] == 13)
				{
					$role_details = $this->loginmodel->role_details($details['role_id']);
					$_SESSION['role'] = $role_details;

				}
				$l = getUserLocations($_SESSION['user_id']);
				$_SESSION['locationString'] = getQueryArray($l);
				$_SESSION['reportees'] = getReportingUsers($_SESSION['user_id']);
				$_SESSION['products'] = getUserProducts($_SESSION['user_id']);
				$_SESSION['userProductReportees'] = getUserProductReportees();
				//Insert login time, mahesh code 3rd august 2016 03:06 pm 
				$user_log_data = array(
								'user_id'			=> $details['user_id'],
								'login_time' 		=> date('Y-m-d H:i:s'),
								'ip_address' 		=> get_client_ip(),
								'user_agent_info'   => $_SERVER['HTTP_USER_AGENT']
							);
				$this->db->insert('user_logs',$user_log_data);
				$userLogId = $this->db->insert_id();
				$this->load->view('icrm/homePage');
			}
			else
			{
				$this->session->set_flashdata('response', '<div class="row"><div class="col-sm-1 col-md-1"></div><div class="col-sm-10 col-md-10"><div class="alert alert-danger alert-white rounded"  style="margin-top:20px;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="icon"><i class="fa fa-times-circle"></i><strong>Error!</strong> Username / Password do not match.</div></div><div class="col-sm-1 col-md-1"></div></div>');
				redirect(SITE_URL.'login');
			} 

		}
		else
		{
			$this->load->view('user/login');	
		} 
	}

	public function logout()
	{
		//UPDATE USER LOG , mahesh 3rd august 2016 03:43 pm
		$user_id = $this->session->userdata('user_id');
		if($user_id)
		{
			$log_qry = 'UPDATE user_logs SET logout_time = "'.date('Y-m-d H:i:s').'" WHERE user_id = '.$user_id.' ORDER BY log_id DESC LIMIT 1';
			$this->db->query($log_qry);
		}
		$this->session->sess_destroy();
		redirect(SITE_URL.'login');
	}

	
	public function roles()
	{
		if(!isset($_SESSION['user_id']))
		{
			header('Location: '.SITE_URL.'login');exit;		
		}			
		if(!isset($_REQUEST['role']))
		{
			header('Location: '.SITE_URL.'home');exit;
		}
		else
		{
			$role_details = array();
			$this->load->model('loginmodel');
			$role = @$_REQUEST['role'];
			$user_id = @$_SESSION['user_id'];
			$employee_id = @$_SESSION['employee_id'];
			$name = @$_SESSION['name'];		
			$company = $_SESSION['company'];
			$company_name = $_SESSION['company_name'];				
			$s_role_id = @$_SESSION['s_role_id'];
			$role_details = @$_SESSION['role'];
			$locationString = @$_SESSION['locationString'];
			$reportees = @$_SESSION['reportees'];
			$products = @$_SESSION['products'];
			$userProductReportees = @$_SESSION['userProductReportees'];
			if($s_role_id == 13)
			{
				session_unset();
				switch($role)
				{
					case 1:
						$_SESSION['role_id'] = 1;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(1);
						break;					
					case 2:
						$_SESSION['role_id'] = 2;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(2);
						break;
					case 3:
						$_SESSION['role_id'] = 3;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(3);
						break;
					case 4:
						$_SESSION['role_id'] = 4;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(4);
						break;
					case 6:
						$_SESSION['role_id'] = 6;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(6);
						break;
					case 7:
						$_SESSION['role_id'] = 7;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(7);
						break;
					case 8:
						$_SESSION['role_id'] = 8;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(8);
						break;
					case 9:
						$_SESSION['role_id'] = 9;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(9);
						break;
					case 10:
						$_SESSION['role_id'] = 10;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(10);
						break;
					case 11:
						$_SESSION['role_id'] = 11;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(11);
						break;
					case 14:
						$_SESSION['role_id'] = 14;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(14);
						break;
					case 15: // OTR
						$_SESSION['role_id'] = 15;
						$_SESSION['role_name'] = $this->loginmodel->getRoleName(15);
						break;
				}
				$_SESSION['userProductReportees'] = $userProductReportees;
				$_SESSION['products'] = $products;
				$_SESSION['locationString'] = $locationString;
				$_SESSION['reportees'] = $reportees;
				$_SESSION['user_id'] = $user_id;
				$_SESSION['employee_id'] = $employee_id;
				$_SESSION['name'] = $name;	
				$_SESSION['company'] = $company;
				$_SESSION['company_name'] = $company_name;
				$_SESSION['s_role_id'] = $s_role_id;
				$_SESSION['role'] = array();
				$_SESSION['role'] = $role_details;					
				header('Location: '.SITE_URL.'home');exit;
			}
		}		
	}

	public function changePassword()
	{
		if(!isset($_SESSION['user_id']))
		{
			header('Location: '.SITE_URL.'login');exit;		
		}			
		$data['nestedView']['heading'] ="Change Password";
		$data['nestedView']['cur_page'] = 'ChangePassword';
		$data['nestedView']['parent_page'] = 'm';
		//$incFILE = "mod/ChangePassword.php";
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();

		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Change Password';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Change Password','class'=>'active','url'=>'');

		if(isset($_POST['submitChangePassword']))
		{
			$oldpassword = @$_POST['oldPassword'];
			$newpassword = @$_POST['newPassword'];
			$cnewpassword = @$_POST['cnewPassword'];

			$this->load->model('loginmodel');
			$flag = $this->loginmodel->change_password($oldpassword, $newpassword, $_SESSION['user_id']);
			header('Location: '.SITE_URL.'changePassword?flg='.$flag.'');exit;
		}
		else
		{
			$this->load->view('user/changePassword', $data);
		}
	}

	/*public function ForgotPassword()
	{
		if(isset($_POST['submitForgetPassword']))
		{
			$newPassword = @$_POST['newPassword'];
			$user_id = @$_POST['user_id'];
			$this->load->model('loginmodel');
			$this->loginmodel->updatePassword($user_id, $newPassword);
			header('Location: '.SITE_URL.'login?flg=3');exit;
		}
		else
		{
			$data['user_id'] = $this->global_functions->decode_icrm(@$_REQUEST['reset']);
			$this->load->view('user/forgotPassword',$data);
		}
	}*/

	// mahesh 14th july 12:35 PM
	public function forgotPassword() {
		
		if($this->input->post('forgetsubmit',TRUE)!=''){
			$employeeId = $this->input->post('employeeId',TRUE);
			$this->db->select();
				$this->db->where('employee_id',$employeeId);
				$query = $this->db->get('user');
					if($query->num_rows() >0){
						
						$row=$query->row_array();
						$to=$row['email_id'];
						if($to!=''){
							$subject='Reset Password Link - ICRM';
							$message ='Hi '.@$row['first_name'].' '.@$row['last_name'].', <br><br>';
							$message.='<a href="'.SITE_URL.'resetPassword?reset='.icrm_encode(@$row['user_id']).'&st='.icrm_encode(date('Y-m-d H:i:s')).'" target="_blank">Click to reset your Password</a>';
							$message .= '<br><br>Regards,<br>ICRM';
							//echo $message.'-----'; exit();
							$CC = "";
							
							$content = htmlspecialchars($message);
							$content = wordwrap($message);
							$content = wordwrap($message,70);
							//echo $to.'<br>'.$subject.'<br>'.$message; exit;
							
							$mail_status = send_email($to,$subject,$message);
							
							if($mail_status)
								$this->session->set_flashdata('response', '<div class="row"><div class="col-sm-1 col-md-1"></div><div class="col-sm-10 col-md-10"><div class="alert alert-success alert-white rounded"  style="margin-top:20px;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="icon"><i class="fa fa-check"></i></div><strong>Success!</strong> Reset Link Sent to Your Email!</div></div><div class="col-sm-1 col-md-1"></div></div>');
							else
								$this->session->set_flashdata('response', '<div class="row"><div class="col-sm-1 col-md-1"></div><div class="col-sm-10 col-md-10"><div class="alert alert-danger alert-white rounded"  style="margin-top:20px;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="icon"><i class="fa fa-times-circle"></i><strong>Error!</strong> There\'s a problem occurred while sending email</div></div><div class="col-sm-1 col-md-1"></div></div>');

						}
						else{

						$this->session->set_flashdata('response', '<div class="row"><div class="col-sm-1 col-md-1"></div><div class="col-sm-10 col-md-10"><div class="alert alert-danger alert-white rounded"  style="margin-top:20px;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="icon"><i class="fa fa-times-circle"></i><strong>Error!</strong> Email doesn\'t exist for this Employee Id, Please contact Admin to reset your password.</div></div><div class="col-sm-1 col-md-1"></div></div>');
						}
						redirect(SITE_URL.'login');
					}					
					 else {
							
						$this->session->set_flashdata('response', '<div class="row"><div class="col-sm-1 col-md-1"></div><div class="col-sm-10 col-md-10"><div class="alert alert-danger alert-white rounded"  style="margin-top:20px;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="icon"><i class="fa fa-times-circle"></i><strong>Error!</strong> Invalid Employee ID / Please Enter a Valid Employee ID.</div></div><div class="col-sm-1 col-md-1"></div></div>');
						redirect(SITE_URL.'login');
					}
			
		}
	}

	// mahesh 14th july 12:48 pm
	public function resetPassword() {
		
		$hdata['pageTitle']="Reset Password";
		$hdata['currentPage']="reset_password";
		$hdata['externalCss']=array();
		$hdata['externalJs'][] = '<script type="text/javascript" src="' . SITE_URL . 'assets/js/jquery.parsley/parsley.js"></script>';
		
		//$this->load->view('common/header',$hdata);
		$this->load->view('user/reset-password',@$hdata);
		//$this->load->view('common/footer');
	}
	//mahesh 14th july 12:52 PM
	public function resetPasswordAction(){
		//echo 'hi';print_r($_POST);
		if($this->input->post('submitForgetPassword',TRUE)!='')
		{
			$newPassword = $this->input->post('newPassword',TRUE);
			$cnewPassword = $this->input->post('cnewPassword',TRUE);
			$encrypt_id = $this->input->post('encrypt_id',TRUE);
			$user_id=icrm_decode($encrypt_id); 
			//echo $user_id.'--'; exit;
			if($user_id!=""){
				//$Qry='UPDATE user SET password="'.md5($newPassword).'" WHERE sso_id='.$decsso_id.'';
				$data=array('password'=>md5($newPassword),'modified_by'=>$user_id,'modified_time'=>date('Y-m-d H:i:s'));
						$this->db->where('user_id',$user_id);
						$this->db->update('user',$data);
						$res = $this->db->affected_rows();
					if($res>0) {
							$this->session->set_flashdata('response', '<div class="row"><div class="col-sm-1 col-md-1"></div><div class="col-sm-10 col-md-10"><div class="alert alert-success alert-white rounded"  style="margin-top:20px;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="icon"><i class="fa fa-check"></i></div><strong>Success!</strong> Your password has been reset successfully!</div></div><div class="col-sm-1 col-md-1"></div></div>');
							redirect(SITE_URL.'login');
						}
					}
		}
	}


}
?>