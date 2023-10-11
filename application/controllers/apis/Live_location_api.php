<?php 
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Headers: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Live_location_api extends CI_Controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Common_model");
		$this->load->model("Calendar_model");
		$this->load->model("ajax_model");
	}

	public function live_location_insert()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
		$this->db->trans_begin();
        // $dataArr = array
        //             (
        //                 'user_id'      => $post_data['user_id'],
        //                 'latitude'     => $post_data['latitude'],
        //                 'longitude'    => $post_data['longitude'],
        //                 'created_date' => date('Y-m-d'),
        //                 'created_time' => date('Y-m-d H:i:s')
        //             );
		// $this->Common_model->insert_data('live_location',$dataArr);
		
		// Added on 11-08-2021
		//echo "<pre>";print_r($post_data);die;
		if(isset($post_data[0]) && !empty($post_data[0]))
		{ 
			foreach ($post_data as $key => $value) {
				$dataArr = array
						(
							'user_id'      => $value['user_id'],
							'latitude'     => $value['latitude'],
							'longitude'    => $value['longitude'],
							'created_date' => date('Y-m-d'),
							'created_time' => date('Y-m-d H:i:s')
						);
				$this->Common_model->insert_data('live_location',$dataArr);

				//Added on 25-03-2022 to get data in new table mobile_live_location
				$user_id_mobile = $this->Common_model->get_value('mobile_live_location',array('user_id'=>$value['user_id']),"user_id");

				if($user_id_mobile)
				{
					$update_data=array(
						'latitude'     => $value['latitude'],
						'longitude'    => $value['longitude'],
						'created_date' => date('Y-m-d'),
						'created_time' => date('Y-m-d H:i:s')
					);
					$t_where = array('user_id'   => $user_id_mobile);
					$this->Common_model->update_data('mobile_live_location',$update_data,$t_where);
				}
				else
				{
					$dataArr = array
					(
						'user_id'      => $value['user_id'],
						'latitude'     => $value['latitude'],
						'longitude'    => $value['longitude'],
						'created_date' => date('Y-m-d'),
						'created_time' => date('Y-m-d H:i:s')
					);
					$this->Common_model->insert_data('mobile_live_location',$dataArr);
				}
				//Added on 25-03-2022 to get data in new table mobile_live_location end


			}
		}
		else
		{
			//Added on 25-03-2022 to get data in new table mobile_live_location
			$user_id_mobile = $this->Common_model->get_value('mobile_live_location',array('user_id'=>$post_data['user_id']),"user_id");
			//echo "<pre>";print_r($user_id_mobile);die;
			if($user_id_mobile)
			{
				$update_data=array(
					'latitude'     => $post_data['latitude'],
					'longitude'    => $post_data['longitude'],
					'created_date' => date('Y-m-d'),
					'created_time' => date('Y-m-d H:i:s')
				);
				$t_where = array('user_id'   => $user_id_mobile);
				$this->Common_model->update_data('mobile_live_location',$update_data,$t_where);
			}
			else
			{
				$dataArrMobile = array
				(
					'user_id'      => $post_data['user_id'],
					'latitude'     => $post_data['latitude'],
					'longitude'    => $post_data['longitude'],
					'created_date' => date('Y-m-d'),
					'created_time' => date('Y-m-d H:i:s')
				);
				$this->Common_model->insert_data('mobile_live_location',$dataArrMobile);
			}
			//Added on 25-03-2022 to get data in new table mobile_live_location end

			$dataArr = array
                    (
                        'user_id'      => $post_data['user_id'],
                        'latitude'     => $post_data['latitude'],
                        'longitude'    => $post_data['longitude'],
                        'created_date' => date('Y-m-d'),
                        'created_time' => date('Y-m-d H:i:s')
                    );
			$this->Common_model->insert_data('live_location',$dataArr);
			
		}
		// Added on 11-08-2021 end

        if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['response'] = "There\'s a problem occured while adding Opportunity!";
			echo json_encode($data);
			header("Status: 400 Bad Request",true,400);
		}
		else
		{
			$this->db->trans_commit();
			$data['response'] = "Location Details Captured successfully!";
			echo json_encode($data);
			header("Status: 201 Created");
			
		}
	}

	public function get_near_by_customers()
	{
		$json = file_get_contents('php://input');
        $post_data1 = base64_decode($json);
		$post_data = json_decode($post_data1,TRUE);
        $latitude = $post_data['latitude'];
        $longitude = $post_data['longitude'];
        $radius = $post_data['radius'];
        $result['results'] = $this->Calendar_model->get_near_by_customers($latitude,$longitude,$radius);

        echo json_encode($result);
	}
}