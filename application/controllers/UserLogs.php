<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';

class UserLogs extends Base_controller {

	public  function __construct() 
	{
        parent::__construct();
		$this->load->library('global_functions');
		$this->load->model("UserLogs_m");
        $this->load->model("User_m");
	}


	public function userLogs() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Manage User Logs";
        $data['nestedView']['cur_page'] = 'userLogs';
        $data['nestedView']['parent_page'] = 'userLogs';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage User Logs';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage User Logs', 'class' => 'active', 'url' => '');

        # Search Functionality

        # Search Functionality
        $psearch=$this->input->post('searchUserLogs', TRUE);
        if($psearch!='') {
        $searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'fromDate' => $this->input->post('fromDate', TRUE),
                'toDate' => $this->input->post('toDate', TRUE),
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
                      'fromDate'=>$this->session->userdata('fromDate'),
                      'toDate'=>$this->session->userdata('toDate'),
                      'email'=>$this->session->userdata('email'),
                      'mobile'=>$this->session->userdata('mobile')
                              );
            }
            else {
                $searchParams=array(
                      'user_role'=>'',
                      'user_name'=>'',
                      'employeeId' =>'',
                      'fromDate' =>'',
                      'toDate' => '',
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
        $config['base_url'] = SITE_URL . 'userLogs/';
        # Total Records
        $config['total_rows'] = $this->UserLogs_m->userLogTotalRows($searchParams);

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
        $data['userSearch'] = $this->UserLogs_m->userLogResults($current_offset, $config['per_page'], $searchParams);
        // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
        $data['roles'] = $this->User_m->getAdminRoles();
        $data['searchParams'] = $searchParams;

        

        $this->load->view('user/users-logs', $data);
    }

    //mahesh 3rd august 2016 07:21 pm
    public function downloadUserLogs()
    {
        if($this->input->post('downloadUserLogs')!='') {
            
            $searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'fromDate' => $this->input->post('fromDate', TRUE),
                'toDate' => $this->input->post('toDate', TRUE),
                'email' => $this->input->post('email', TRUE),
                'mobile' => $this->input->post('mobile', TRUE)
                              );
            $users = $this->UserLogs_m->userLogDetails($searchParams);
            
            $header = '';
            $data ='';
            $titles = array('S.NO','Name','Role','Employee ID','Branch','Login Time','Last Active','IP','Browser','Punch In Time','Punch Out Time','Battery Status');
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
                    ini_set('memory_limit', '-1');
                    $data.='<tr>';
                    $data.='<td align="center">'.$j.'</td>';
                    $data.='<td align="center">'.@$row['first_name'].' '.@$row['last_name'].'</td>';
                    $data.='<td align="center">'.@$row['role'].'</td>';
                    $data.='<td align="center">'.@$row['employee_id'].'</td>';
                    $data.='<td align="center">'.@$row['branch'].'</td>';
                    $data.='<td align="center">'.DateFormatAM(@$row['login_time']).'</td>';
                    $data.='<td align="center">'.DateFormatAM((@$row['logout_time']==NULL)?@$row['last_active']:@$row['logout_time']).'</td>';
                    $data.='<td align="center">'.@$row['ip_address'].'</td>';
                    $data.='<td align="center">'.getBrowser(@$row['user_agent_info']).'</td>';
                    $data.='<td align="center">'.DateFormatAM(@$row['start_time']).'</td>';
                    $data.='<td align="center">'.DateFormatAM(@$row['end_time']).'</td>';
                    $data.='<td align="center">'.@$row['battery_percentage'].'%</td>';
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
            $xlFile='userlogs_'.$time.'.xls'; 
            header("Content-type: application/x-msdownload"); 
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=".$xlFile."");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
            
        }
    }

    public function punch_in_report()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Punch In Report";
        $data['nestedView']['cur_page'] = 'punch_in_userLogs';
        $data['nestedView']['parent_page'] = 'punch_in_userLogs';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Punch In Report';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Punch In Report', 'class' => 'active', 'url' => '');


        # Search Functionality

        # Search Functionality
        $psearch=$this->input->post('searchUserLogs', TRUE);
        if($psearch!='') {
        $searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'from_Date' => $this->input->post('from_Date', TRUE),
                'to_Date' => $this->input->post('to_Date', TRUE),
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
                      'from_Date'=>$this->session->userdata('from_Date'),
                      'to_Date'=>$this->session->userdata('to_Date'),
                      'mobile'=>$this->session->userdata('mobile')
                              );
            }
            else {
                $searchParams=array(
                      'user_role'=>'',
                      'user_name'=>'',
                      'employeeId' =>'',
                      'from_Date' =>'',
                      'to_Date' => '',
                      'mobile' => ''
                                  );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['search_data'] = $searchParams;


        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'punch_in_report/';
        # Total Records
        $config['total_rows'] = $this->UserLogs_m->PunchInTotalRows($searchParams);

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
        $data['userSearch'] = $this->UserLogs_m->punchInResults($current_offset, $config['per_page'], $searchParams);
        //echo $this->db->last_query(); exit;
        //echo "<pre>"; print_r($data['userSearch']); exit;
        // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
        $data['roles'] = $this->User_m->getAdminRoles();
        $data['searchParams'] = $searchParams;

        

        $this->load->view('user/punch_in_user_logs', $data);

    }

    public function download_punch_in_report()
    {
        if($this->input->post('downloadUserLogs')!='') {
            
            $searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'from_Date' => $this->input->post('from_Date', TRUE),
                'to_Date' => $this->input->post('to_Date', TRUE),
                'mobile' => $this->input->post('mobile', TRUE)
                              );
            $users = $this->UserLogs_m->punchInDetails($searchParams);
            
            $header = '';
            $data ='';
            $titles = array('S.NO','Name','Role','Employee ID','Branch','Punch In Time','Punch Out Time','Battery Status');
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
                    ini_set('memory_limit', '-1');
                    $data.='<tr>';
                    $data.='<td align="center">'.$j.'</td>';
                    $data.='<td align="center">'.@$row['first_name'].' '.@$row['last_name'].'</td>';
                    $data.='<td align="center">'.@$row['role'].'</td>';
                    $data.='<td align="center">'.@$row['employee_id'].'</td>';
                    $data.='<td align="center">'.@$row['branch'].'</td>';
                    $data.='<td align="center">'.DateFormatAM(@$row['start_time']).'</td>';
                    $data.='<td align="center">'.DateFormatAM(@$row['end_time']).'</td>';
                    $data.='<td align="center">'.@$row['battery_percentage'].'%</td>';
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
            $xlFile='punchin_report_'.$time.'.xls'; 
            header("Content-type: application/x-msdownload"); 
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=".$xlFile."");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
            
        }
    }

    public function punchinlogs() {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Punchin Logs";
        $data['nestedView']['cur_page'] = 'PunchinLogs';
        $data['nestedView']['parent_page'] = 'PunchinLogs';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Punchin Logs';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Manage Punchin Logs', 'class' => 'active', 'url' => '');

        # Search Functionality

        # Search Functionality
        $psearch=$this->input->post('searchUserLogs', TRUE);
        if($psearch!='') {
        $searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'from_Date' => $this->input->post('from_Date', TRUE),
                'to_Date' => $this->input->post('to_Date', TRUE),
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
                      'from_Date'=>$this->session->userdata('from_Date'),
                      'to_Date'=>$this->session->userdata('to_Date'),
                      'mobile'=>$this->session->userdata('mobile')
                              );
            }
            else {
                $searchParams=array(
                      'user_role'=>'',
                      'user_name'=>'',
                      'employeeId' =>'',
                      'from_Date' =>'',
                      'to_Date' => '',
                      'mobile' => ''
                                  );
                $this->session->set_userdata($searchParams);
            }
            
        }
        $data['search_data'] = $searchParams;


        # Default Records Per Page - always 10
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL . 'punchinlogs/';
        # Total Records
        $config['total_rows'] = $this->UserLogs_m->PunchInlogsTotalRows($searchParams);

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
        $data['userSearch'] = $this->UserLogs_m->punchInlogsResults($current_offset, $config['per_page'], $searchParams);
        // GET ALL ROLES EXCLUDING ADMIN, SUPER ADMIN
        $data['roles'] = $this->User_m->getAdminRoles();
        $data['searchParams'] = $searchParams;

        

        $this->load->view('user/punch-in-logs', $data);
    }

    //mahesh 3rd august 2016 07:21 pm
    public function download_punch_in_logs()
    {
        if($this->input->post('downloadUserLogs')!='') {
            
            $searchParams=array(
                'user_role' => $this->input->post('user_role', TRUE),
                'user_name' => $this->input->post('user_name', TRUE),
                'employeeId' => $this->input->post('employeeId', TRUE),
                'fromDate' => $this->input->post('fromDate', TRUE),
                'toDate' => $this->input->post('toDate', TRUE),
                'email' => $this->input->post('email', TRUE),
                'mobile' => $this->input->post('mobile', TRUE)
                              );
            $users = $this->UserLogs_m->punchInlogsDetails($searchParams);
            
            $header = '';
            $data ='';
            $titles = array('S.NO','Name','Role','Employee ID','Branch','Login Time','Last Active','IP','Browser','Punch In Time','Punch Out Time','Battery Status');
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
                    ini_set('memory_limit', '-1');
                    $data.='<tr>';
                    $data.='<td align="center">'.$j.'</td>';
                    $data.='<td align="center">'.@$row['first_name'].' '.@$row['last_name'].'</td>';
                    $data.='<td align="center">'.@$row['role'].'</td>';
                    $data.='<td align="center">'.@$row['employee_id'].'</td>';
                    $data.='<td align="center">'.@$row['branch'].'</td>';
                    $data.='<td align="center">'.DateFormatAM(@$row['login_time']).'</td>';
                    $data.='<td align="center">'.DateFormatAM((@$row['logout_time']==NULL)?@$row['last_active']:@$row['logout_time']).'</td>';
                    $data.='<td align="center">'.@$row['ip_address'].'</td>';
                    $data.='<td align="center">'.getBrowser(@$row['user_agent_info']).'</td>';
                    $data.='<td align="center">'.DateFormatAM(@$row['start_time']).'</td>';
                    $data.='<td align="center">'.DateFormatAM(@$row['end_time']).'</td>';
                    $data.='<td align="center">'.@$row['battery_percentage'].'%</td>';
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
            $xlFile='punchinlogs_'.$time.'.xls'; 
            header("Content-type: application/x-msdownload"); 
            # replace excelfile.xls with whatever you want the filename to default to
            header("Content-Disposition: attachment; filename=".$xlFile."");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $data;
            
        }
    }
}
?>