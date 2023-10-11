<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type,Accept,Authorization');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_api extends CI_Controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Common_model");
		$this->load->model("customer_model");
        $this->load->model("Ajax_m");
        $this->load->model("Report_model");
	}

	public function addCustomer()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['company'] = $post_data['company_id'];
        $sql = "select category_id,name from customer_category where status=1 and company_id=".$_SESSION['company'];
        $query = $this->db->query($sql);
        $res = $query->result_array();

		$data['categories'] = $res;
		$isd = $this->Common_model->get_dropdown('isd', 'isd', 'isd', []);
		foreach($isd as $key=>$value)
		{
			$isd_list[] = array('id'=>$key,'name'=>$value);
		}
        $data['isd'] = $isd_list;
        
        // To get Customer Beds start
        $sql1 = "select id,bed_range from customer_beds";
        $query1 = $this->db->query($sql1);
        $res1 = $query1->result_array();
        $data['cust_beds'] = $res1;
        // End

        // To get Customer Specility start
        $sql2 = "select id,name from customer_speciality";
        $query2 = $this->db->query($sql2);
        $res2 = $query2->result_array();
        $data['cust_speciality'] = $res2;
        // End
        

        $this->session->sess_destroy();
		echo json_encode($data);

	}

	function get_sub_category() 
	{
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $category_id = $post_data['category_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $sql = "SELECT sc.category_sub_id,sc.name FROM customer_sub_category sc,customer_category c,customer_category_details cd "
                . " WHERE c.category_id=cd.category_id "
                . " AND sc.category_sub_id=cd.category_sub_id "
                . " AND c.category_id= " . $this->db->escape($category_id)
                . " AND c.company_id= " . $_SESSION['company']
                . " AND sc.status =1 ";
        
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $data['category_list'] = $res;
        $this->session->sess_destroy();
		echo json_encode($data);
    }

    public function cityLocation()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['locationString'] = $post_data['locationString'];
		$val = @trim($post_data['city']);
		$data['city_list'] = getLocationInfo($val);
        $this->session->sess_destroy();
		echo json_encode($data);
	}

	public function customerAdd()
	{
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $_SESSION['user_id'] = $post_data['user_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $isd2 = $post_data['isd2'];
        if ($post_data['mobile'] != '') {
            $mobile_no = $isd2 . "-" . $post_data['mobile'];
        } else {
            $mobile_no = NULL;
        }
        $this->db->trans_begin();
        $dataArr = array(
            'name'            => $post_data['name'],
            'name1'           => '',
            'category_id'     => $post_data['category_id'],
            'category_sub_id' => $post_data['category_sub_id'],
            'email'           => $post_data['email'],
            'mobile'          => $mobile_no,
            'address1'        => $post_data['address1'],
            'remarks2'        => $post_data['customer_code'],
            'telephone'       => $post_data['telephone'],
            'pincode'         => $post_data['pincode'],
            'company_id'      => $this->session->userdata('company'),
			'status'		  => 4,
            'created_by'      => $_SESSION['user_id'],
            'created_time'    => date('Y-m-d H:i:s'),
            'customer_bed_id' => $post_data['customer_bed_id'],
            'customer_speciality_id	' => json_encode($post_data['customer_speciality_id']));
		//Insert
        $customer_id = $this->Common_model->insert_data('customer', $dataArr);
        
        //$this->add_customer_installation($customer_id,$_SESSION['user_id']);
       
        $location_details = array(
            'customer_id' => $customer_id,
            'location_id' => $post_data['city_id']);
        $customer_id = $this->Common_model->insert_data('customer_location', $location_details);
       
        $to = "crm@skanray.com";
        $subject =  "Approval For Customer";
        $body = "Hi, <br><br>";
        $body.= "New Customer <strong>".$post_data['name']."</strong> Is added Into application.";
        $body.="<br><br>";
        $body.= 'Please Approve The Customer';
        $body.="<br><br><br><br><br><br>";
        $body.="<p>Regards,<br>iCRM,<br>SkanRay</p>";
       
        send_email($to,$subject,$body);


        $this->session->sess_destroy();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
            $data['response'] = "Something Went Wrong";
			header("Status: 400 Bad Request",true,400);
            echo json_encode($data);
		}
		else
		{
			$this->db->trans_commit();
            $data['response'] = "Customer Created Successfully";
			header("HTTP/1.1 201 Created");
            echo json_encode($data);
		}
    }

    /*function add_customer_installation($customer_id = 0,$user_id) 
    {
    	$json = file_get_contents('php://input');
        $post_data = json_decode($json,TRUE);
        if ($customer_id != 0) {
            //customer installation
           
            $competitors = $post_data['competitors'];
            $product_model = $post_data['product_model'];
            $quantity = $post_data['quantity'];
            $make = $post_data['make'];
            $year_of_purchase = $post_data['year_of_purchase'];
            $replacement_year = $post_data['replacement_year'];
            if (count($competitors) > 0 && $competitors[0]!=NULL) {
                $i = 0;
                foreach ($competitors as $v) {
                    if($competitors[$i] != '')
                    {
                        $dataArr = array(
                            'customer_id'      => $customer_id,
                            'competitors'      => $competitors[$i],
                            'product_model'    => $product_model[$i],
                            'quantity'         => ($quantity[$i] != '')?$quantity[$i]:NULL,
                            'make'             => $make[$i],
                            'year_of_purchase' => ($year_of_purchase[$i] != '')?$year_of_purchase[$i]:NULL,
                            'replacement_year' => ($replacement_year[$i] != '')?$replacement_year[$i]:NULL,
                            'created_by'       => $user_id,
                            'created_time'     => date('Y-m-d H:i:s')
                        );

						//Insert
                        $this->Common_model->insert_data('customer_installed', $dataArr);
                    }
                    $i++;
                }
                return 1;
            }
            else
            {
                return 0;
            } 
           
        }
    }*/

    function customer()
    {
    	$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $searchParams['s_location'] = $post_data['s_location'];
        $searchParams['customerName'] = $post_data['customerName'];
        $searchParams['department'] = $post_data['department'];
        $searchParams['category_id'] = $post_data['category_id'];
        $searchParams['category_sub_id'] = $post_data['category_sub_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $_SESSION['locationString'] = $post_data['locationString'];
        $current_offset = ($post_data['segment']!='')?$post_data['segment']:0;
        $config['per_page'] = getDefaultPerPageRecords(); 

        $data['customerSearch'] = $this->customer_model->get_details($current_offset, $config['per_page'], $searchParams);
        $data['selected_search'] = $searchParams;
        $this->session->sess_destroy();
        echo json_encode($data);
    }

    public function updateCustomer()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $customer_id = $post_data['customer_id'];
        $isd2 = $post_data['isd2'];
        if ($post_data['mobile'] != '') {
            $mobile_no = $isd2 . "-" . $post_data['mobile'];
        } else {
            $mobile_no = NULL;
        }

        $this->db->trans_begin();
        $dataArr = array(
            'mobile'        => $mobile_no,
            'modified_by'   => $post_data['user_id'],
            'modified_time' => date('Y-m-d H:i:s')
        );
        $this->Common_model->update_data('customer',$dataArr,array("customer_id"=>$customer_id));
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data['response'] = 'Something Went Wrong';
            echo json_encode($data);
            header("Status: 400 Bad Request",true,400);
        }
        else
        {
            $this->db->trans_commit();
            $data['response'] = 'Customer Has Been Updated';
            echo json_encode($data);
            header("HTTP/1.1 201 Created");
        }
    }

    public function is_customer_code_exists()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $customer_code = $post_data['customer_code'];
        $customer_id = $post_data['customer_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $count = $this->Ajax_m->is_customerCodeExist($customer_code,$customer_id);
        $this->session->sess_destroy();
        if($count>0)
        {
            $data['response'] = "Customer Code Already Exits";
            echo json_encode($data);
        }
        else
        {
            $data['response'] = 'OK';
            echo json_encode($data);
        }
    }

    public function is_customername_exists()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $customer_name = $post_data['customer_name'];
        $customer_id = $post_data['customer_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $count = $this->Ajax_m->is_customerNameExist($customer_name,$customer_id);
        $this->session->sess_destroy();
        if($count>0)
        {
            $data['response'] = "Customer Name Already Exits";
            echo json_encode($data);
        }
        else
        {
            $data['response'] = 'OK';
            echo json_encode($data);
        }
    }

    public function editCustomer()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $customer_id = $post_data['customer_id'];
        $_SESSION['company'] = $post_data['company_id'];
        $where = array('customer_id' => $customer_id);
        $customer_data = $this->Common_model->get_data('customer', $where);
        foreach($customer_data as $key=>$value)
        {
            $arr[$key] = $value;
            $arr[$key]['category_name'] = $this->Common_model->get_value('customer_category',array('category_id'=>$value['category_id']),'name');
            $arr[$key]['sub_category_name'] = $this->Common_model->get_value('customer_sub_category',array('category_sub_id'=>$value['category_sub_id']),'name');
            $arr[$key]['cust_bed_id'] = $value['customer_bed_id'];
        }
        
        $data['customer_data'] = $arr;
        $data['city'] = $this->customer_model->getLocation($customer_id);
        $where1 = array('id' => $data['customer_data'][0]['cust_bed_id']);
        $data['cust_bed_range'] = $this->Common_model->get_data('customer_beds', $where1);
        $this->session->sess_destroy();
        echo json_encode($data);
    }

    public function stock_in_hand()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $searchParams['category'] = $post_data['category_id'];
        $searchParams['segment'] = $post_data['segment_id'];
        $searchParams['product'] = $post_data['product_id'];
        $_SESSION['company'] = $post_data['company_id'];

        $data['customerSearch'] = $this->Report_model->get_stock_in_hand_products_table($searchParams);
        $data['category_list']=$this->Common_model->get_data('product_category',array('status'=>1,'company_id'=>$_SESSION['company']));

        $this->session->sess_destroy();
        echo json_encode($data);
    }

    public function get_segment()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $category = $post_data['category_id'];
        $data['segments'] = $this->Common_model->get_data('product_group',array('category_id'=>$category,'status'=>1),array('group_id','name'));
        echo json_encode($data);
    }

    public function get_product()
    {
        $json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
        $post_data = json_decode($post_data1,TRUE);
        $segment = $post_data['segment_id'];
        $data['products'] = $this->Common_model->get_data('product',array('group_id'=>$segment,'product_type_id'=>1,'status'=>1),array('product_id','name','description'));
        echo json_encode($data);
    }

}