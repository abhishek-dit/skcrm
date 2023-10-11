<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class New_outstanding_format extends Base_controller {

    public function __construct() 
    {
        parent::__construct();
       $this->load->model('New_outstanding_format_m');
       $this->load->library('excel');
    }
    /*Phase2 changes new enhancement 
      created by prasad on april 12
    */
    public function new_so_amount_upload()
    {
    	# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Outstanding Upload";
		$data['nestedView']['cur_page'] = 'new_so_amount_upload';
		$data['nestedView']['parent_page'] = 'new_so_amount_upload';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Upload';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Outstanding Amount Upload','class'=>'active','url'=>'');
		$data['months'] = $this->New_outstanding_format_m->get_months();
        $data['updated_record']=$this->New_outstanding_format_m->get_last_updated_record();
		$this->load->view('new_so_bulk_upload/so_amount_view_page',$data);
    }
    public function generate_new_so_outstanding_xl()
	{  
		//Fetching regions
		$regions = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
		$this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('SO Outstanding Upload');
        $this->excel->getActiveSheet()->setCellValue('A1', 'Region');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Total Outstanding(L)');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Collections Planned for the Month(L)');
        $this->excel->getActiveSheet()->setCellValue('D1', 'MTD Collections(L) ');
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $exceldata="";
        if(count($regions)>0)
        {
            $arr=array();
            $i=1;
            foreach ($regions as $reg)
            {      /*strip_tags(html_entity_decode())*/
                $exceldata=array();
                $exceldata[] = @$reg['location'];
                $exceldata[] = '';
                $exceldata[] = '';
                $exceldata[] = '';
                $arr[]=$exceldata;
            }
            $this->excel->getActiveSheet()->fromArray($arr, null, 'A2');
        }
        else
        {
            $exceldata[]="No Records Found";
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A2');
            $this->excel->getActiveSheet()->mergeCells('A1:G1');
        }

        $filename='so_outstanding_upload'.date('Y-m-d h:i:s').'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
        $objWriter->save('php://output');
        exit;   
	}

    public function insert_new_so_amount_upload()
    {
        if($this->input->post('submit'))
        {  
            $month_id     = $this->input->post('month_id');
            $year_id      = $this->input->post('year_id');
            $posted_date  = date('Y-m-t',strtotime($year_id.'-'.$month_id.'-01'));
            $current_date = date('Y-m-t');
            if($posted_date > $current_date)
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <div class="icon"><i class="fa fa-times"></i></div>
                <strong>Error!</strong> Please upload data for present dates only.
                </div>');
                redirect(SITE_URL.'new_so_amount_upload');exit;
            }

            //fetching financial year id for the given date.
            $fy_id = $this->New_outstanding_format_m->get_financial_year_for_given_date($posted_date);
            if($fy_id=='')
            {
                $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <div class="icon"><i class="fa fa-times"></i></div>
                <strong>Error!</strong> Please add the financial year for the given dates.
                </div>');
                redirect(SITE_URL.'new_so_amount_upload');exit;
            }

            $this->load->library('excel');
            //Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)    
            $configUpload['upload_path'] = FCPATH.'application/uploads/excel/';
            $configUpload['allowed_types'] = 'xls|xlsx|csv';
            $configUpload['max_size'] = '5000';
            $this->load->library('upload', $configUpload);
            $this->upload->do_upload('userfile');  
            $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
            $file_name = $upload_data['file_name']; //uploded file name
            //echo $file_name;exit;
            $extension = $upload_data['file_ext'];    // uploded file extension
            switch ($extension) 
            {
                case '.xlsx':
                    $objReader = PHPExcel_IOFactory::createReader('Excel2007'); // For excel 2007  
                break;
                case '.xls':
                    $objReader = PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003  
                break;
                default:
                    # code...
                break;
            }
            //Set to read only
            $objReader->setReadDataOnly(true);          
            //Load excel file
            $objPHPExcel = $objReader->load(FCPATH.'application/uploads/excel/'.$file_name);      
            $totalrows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel         
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            
            $allQrys = '';
            $missing = 0;
            $inserted_files = 0;
            $updated_files = 0;
            $row_count = 0;
            
            #begin insertion 
            $this->db->trans_begin();
            $upload_data = array(
            'file_name'    => $file_name,
            'created_by'   => $this->session->userdata('user_id'),
            'created_time' => date('Y-m-d h:i:s'),
            'type'         => 5);
            $upload_id = $this->Common_model->insert_data('upload_csv',$upload_data);

            $remarks_missing_text='';
            //loop from first data untill last data
            for($i=2;$i<=$totalrows;$i++)
            {
                $region = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();           
                $ot_amount = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); 
                $planned_collections = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); 
                $actual_collections = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); 
                
                //Removing special characters.
                $ot_amount = clean($ot_amount);
                $planned_collections = clean($planned_collections);
                $actual_collections = clean($actual_collections);

                // If all columns empty skip that row
                if($region==''&&$ot_amount==''&&$planned_collections==''&&$actual_collections=='')
                {
                    continue;
                }
                $row_count++;
                
                if($region!='' &&$ot_amount !='' &&$planned_collections !=''&&$actual_collections!=''&& $month_id!='' &&$year_id!='' &&$fy_id !='')
                {   
                    $region_id = $this->Common_model->get_value('location',array('location'=>$region,'territory_level_id'=>4,'status'=>1),'location_id');
                    $query = 'SELECT * from new_so_outstanding_amount WHERE region_id="'.$region_id.'"
                                    AND month_id="'.$month_id.'" AND year_id="'.$year_id.'" ';

                    $updated_count=$this->Common_model->get_no_of_rows($query);
                    $res=$this->db->query($query);
                    $result=$res->row_array();
                    if($updated_count <= 0)
                    {
                        $qry='select * from location where location_id="'.$region_id.'"';
                        $count = $this->Common_model->get_no_of_rows($qry);

                        if($count > 0)
                        {   
                            $dat4=array(
                                'region_id'           => $region_id,
                                'ot_amount'           => $ot_amount,
                                'collections_planned' => $planned_collections,
                                'actual_collections'  => $actual_collections,
                                'month_id'            => $month_id,
                                'year_id'             => $year_id,
                                'fy_id'               => $fy_id,
                                'created_by'          => $this->session->userdata('user_id'),
                                'created_time'        => date('Y-m-d h:i:s'),
                                'upload_id'           => $upload_id,
                                'status'              => 1);
                            $ot_id= $this->Common_model->insert_data('new_so_outstanding_amount',$dat4);
                            $dat4['outstanding_id']=$ot_id;
                            $this->Common_model->insert_data('new_so_amount_history',$dat4);
                            $inserted_files++;
                        }
                        else
                        {
                            $missing++;
                            $remarks_missing_text.=$region.' Doesnt Exists,';
                        }
                    }
                    else
                    {
                        $updated_files++;
                        $dat5=array(
                                'region_id'           => $region_id,
                                'ot_amount'           => $ot_amount,
                                'collections_planned' => $planned_collections,
                                'actual_collections'  => $actual_collections,
                                'month_id'            => $month_id,
                                'year_id'             => $year_id,
                                'fy_id'               => $fy_id,
                                'created_by'          => $this->session->userdata('user_id'),
                                'created_time'        => date('Y-m-d h:i:s'),
                                'upload_id'           => $upload_id,
                                'status'              => 1,
                                'outstanding_id'      => $result['outstanding_id']
                            );
                        $this->Common_model->insert_data('new_so_amount_history',$dat5);
                        $this->Common_model->update_data('new_so_outstanding_amount',array('ot_amount'=>$ot_amount,'collections_planned'=>$planned_collections,'actual_collections'=>$actual_collections,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d h:i:s'),'upload_id'=>$upload_id),array('region_id'=>$region_id,'month_id'=>$month_id,'year_id'=>$year_id));
                    }
                }
                else
                {
                    $missing++;
                    $region_id = $this->Common_model->get_value('location',array('location'=>$region,'territory_level_id'=>4,'status'=>1),'location_id');
                    if($region_id=='')
                    {
                        $remarks_missing_text.=$region.' Region doesn\'t exists,';
                    }
                    elseif($region_id!='')
                    {
                        if($ot_amount =='')
                        {
                            $remarks_missing_text.='Outstanding amount is missing for '.$region .',';
                            $quantity = NULL;
                        }
                        if($actual_collections =='')
                        {
                            $remarks_missing_text.='Actual collections is missing for '.$region .',';
                            $quantity = NULL;
                        }
                        if($planned_collections=='')
                        {
                            $remarks_missing_text.='collections Planned  is missing for '.$region .',';
                            $quantity = NULL;
                        }
                    }
                    else
                    {
                        if($region =='')
                        {
                            $remarks_missing_text.='Region is missing,';
                            $product_name = NULL;
                        }
                    }
                }
            }
            if($missing > 0)
            {   
                $this->db->trans_commit();
                $this->session->set_flashdata('response',
                '<div class="alert alert-warning alert-white rounded">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <div class="icon"><i class="fa fa-times"></i></div>
                <strong>Warning!</strong> Out of '.$row_count.' records '.$inserted_files.' are inserted, Missed: '.$missing.' , Updated: '.$updated_files.' , remarks are '.$remarks_missing_text.'.Please Check and upload again! 
                </div>');
                redirect(SITE_URL.'new_so_amount_upload');exit;
            }
            else
            {  
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Error!</strong> There\'s a problem occured while uploading Outstanding Amount!
                    </div>');
                    redirect(SITE_URL.'new_so_amount_upload');
                    #transaction Failed
                }
                else
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa fa-check"></i></div>
                    <strong>Success!</strong>Out of '.$row_count.' records '.$inserted_files.' are inserted, Missed: '.$missing.' , Updated: '.$updated_files.' !
                    </div>');
                    redirect(SITE_URL.'new_so_amount_upload');
                    #Transaction success!
                }
            }
        }
    }
     public function new_so_amount_list()
    {     
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "SO Amount Upload List";
        $data['nestedView']['cur_page'] = 'new_so_amount_list';
        $data['nestedView']['parent_page'] = 'new_so_amount_list';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'SO Amount Upload List';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'SO Amount upload List', 'class' => '', 'url' =>'');
        $data['pageDetails'] = 'so_amount_list';
        # Search Functionality
        $psearch=$this->input->post('search', TRUE);
        $start_date = $this->input->post('start_date');
        if($start_date!='')
        {
            $start_date = date('Y-m-d',strtotime($start_date));
        }
        else
        {
            $start_date = '';
        }
        $end_date = $this->input->post('end_date');
        if($end_date!='')
        {
            $end_date = date('Y-m-d',strtotime($end_date));
        }
        else
        {
            $end_date = '';
        }
        if($psearch!='') {
        $searchParams=array(
                      'upload_id'=>$this->input->post('upload_id'),
                      'start_date'=>$start_date,
                      'end_date'=>$end_date
                      );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'upload_id'=>$this->session->userdata('upload_id'),
                      'start_date'=>$this->session->userdata('start_date'),
                      'end_date'=>$this->session->userdata('end_date'),
                      );
            }
            else {
                $searchParams=array(
                      'upload_id'=>'',
                      'start_date'=>'',
                      'end_date'=>''
                       );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;
       // print_r($searchParams);exit;
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'new_so_amount_list/'; 
        # Total Records
        $config['total_rows'] = $this->New_outstanding_format_m->so_amount_rows($searchParams);
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
        $data['searchResults'] = $this->New_outstanding_format_m->so_amount_results($searchParams,$config['per_page'], $current_offset);
        $this->load->view('new_so_bulk_upload/new_so_amount_list', $data);
    }
    public function download_new_so_bulk_upload()
    {   
        $start_date = $this->input->post('start_date');
        if($start_date!='')
        {
            $start_date = date('Y-m-d',strtotime($start_date));
        }
        else
        {
            $start_date = '';
        }
        $end_date = $this->input->post('end_date');
        if($end_date!='')
        {
            $end_date = date('Y-m-d',strtotime($end_date));
        }
        else
        {
            $end_date = '';
        }
        $searchParams=array(
             'upload_id' => $this->input->post('upload_id', TRUE),
             'start_date' => $start_date,
             'end_date' => $end_date
            );
         $searchResults = $this->New_outstanding_format_m->so_upload_list($searchParams);
        $this->excel->setActiveSheetIndex(0);
           $this->excel->getActiveSheet()->setTitle('Outstanding Bulk Upload');
            //$this->excel->getActiveSheet()->setCellValue('A1', 'Outstanding  Excel Sheet');
            $this->excel->getActiveSheet()->setCellValue('A1', 'S.No.');
            $this->excel->getActiveSheet()->setCellValue('B1', 'upload_id');
            $this->excel->getActiveSheet()->setCellValue('C1', 'File');
            $this->excel->getActiveSheet()->setCellValue('D1', 'Uploaded By');
            $this->excel->getActiveSheet()->setCellValue('E1', 'Uploaded Time');
            /*$this->excel->getActiveSheet()->mergeCells('A1:C1');
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
            $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
           
            */            
            $exceldata="";
             if(count($searchResults)>0)
            {
                $i=1;
                $arr=array();
                foreach ($searchResults as $row)
                {      
                        $exceldata=array();
                        $exceldata[] = $i++;
                        $exceldata[] = @$row['upload_id'];
                        $exceldata[] = @$row['file_name'];
                        $exceldata[] = getUserName(@$row['upload_id']);
                        $exceldata[] = @$row['created_time'];
                       // echo "<br>";
                        $arr[]=$exceldata;
                       
                }
               
                $this->excel->getActiveSheet()->fromArray($arr, null, 'A2');
            }
            else
            {
                 $exceldata[]="No Records Found";
                 $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A2');
                 $this->excel->getActiveSheet()->mergeCells('A1:G1');
            }
           
          
            $filename=' OutStanding Amount List.xlsx'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
            $objWriter->save('php://output');
    }
      public function download_new_so_bulk_upload_details()
    {
        $upload_id=icrm_decode($this->uri->segment(2));
        $results=$this->Common_model->get_data('new_so_outstanding_amount',array('upload_id'=>$upload_id));
        
        

       
        $data ='';
        $data.='<h3>OutStanding Collections For the month of '.date('F',strtotime($results[0]['year_id'].'-'.$results[0]['month_id'].'-01')).' '.$results[0]['year_id'];
        $data.= '<table border="1">';
        $data.='<thead>';
        $data.='<tr>';
        $data.='<th>Sno</th>';
        $data.='<th>Region</th>';
        $data.='<th>OutStanding Amount</th>';
        $data.='<th>Collections Planned for the Month</th>';
        $data.='<th>MTD Collections</th>';
        $data.='</tr>';
        $data.='</thead>';
        $data.='<tbody>';
        /*$this->excel->getActiveSheet()->mergeCells('A1:C1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
       
        */
         if(count($results)>0)
        {
            $i=1;
            foreach ($results as $row)
            {  
                $reg=$this->Common_model->get_data_row('location',array('location_id'=>$row['region_id']));     
                $data.='<tr>';
                $data.='<td>'.$i++.'</td>';
                $data.='<td>'.@$reg['location'].'</td>';
                $data.='<td>'.@$row['ot_amount'].'</td>';
                $data.='<td>'.@$row['collections_planned'].'</td>';
                $data.='<td>'.@$row['actual_collections'].'</td>';
                $data.='</tr>';    
            }
        }
        else
        {
             $data.='<tr><td colspan="15" align="center">No Records Found </td></tr>';
        }
       
      
        $data.='</tbody>';
        $data.='</table>';
        $time = date("Ymdhis");
        $xlFile='SOuploadedrecords_'.$time.'.xls'; 
        header("Content-type: application/x-msdownload"); 
        # replace excelfile.xls with whatever you want the filename to default to
        header("Content-Disposition: attachment; filename=".$xlFile."");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $data;
    }
    public function get_new_outstanding_report()
    {
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Outstanding Upload Report";
        $data['nestedView']['cur_page'] = 'get_new_outstanding_report';
        $data['nestedView']['parent_page'] = 'get_new_outstanding_report';
        
        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
        $data['nestedView']['css_includes'] = array();
        
        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Report';
        $data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label'=>'Outstanding Report','class'=>'active','url'=>'');
       
        $data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
        # Search Functionality
        $psearch=$this->input->post('search', TRUE);
        if($psearch!='') {
        $searchParams=array(
                      'region_id'=>$this->input->post('region_id'),
                      'month_id'=>$this->input->post('month_id'),
                      'year_id'=>$this->input->post('year_id'),
                      );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'region_id'=>$this->session->userdata('region_id'),
                      'month_id'=>$this->session->userdata('month_id'),
                      'year_id'=>$this->session->userdata('year_id'),
                      );
            }
            else {
                $searchParams=array(
                      'region_id'=>'',
                      'month_id'=>date('m'),
                      'year_id'=>date('Y'),
                       );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;
        $data['months'] = $this->New_outstanding_format_m->get_report_months($searchParams);
        $config['base_url'] = SITE_URL.'get_new_outstanding_report/'; 
        $data['outstanding_results'] = $this->New_outstanding_format_m->fetch_os_results($searchParams);
        $this->load->view('new_so_bulk_upload/new_outstanding_report',$data);
    }
    public function download_new_so_report()
    {
        $search = $this->input->post('download',TRUE);
        if($search!='')
        {
            $searchParams = array(
            'region_id' => $this->input->post('region_id',TRUE),
            'month_id' => $this->input->post('month_id',TRUE),
            'year_id' => $this->input->post('year_id',TRUE));
        }
        $results=$this->New_outstanding_format_m->fetch_os_results($searchParams);
        $this->excel->setActiveSheetIndex(0);
         $style = array  (
                        'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        )
                    );
        $remarks='';
        $remarks.='OutStanding Report for the month of '.date('M',strtotime($searchParams['year_id'].'-'.$searchParams['month_id'].'-01')).' '.$searchParams['year_id'];
        if($searchParams['region_id']!='')
        {
             $reg=$this->Common_model->get_data_row('location',array('location_id'=>$searchParams['region_id'],'status'=>1));
             $remarks.=' In '.$reg['location'].' Region';
        }
        $this->excel->getActiveSheet()->setTitle('OutStanding Report');
        $this->excel->getActiveSheet()->mergeCells('A1:E1')->setCellValue('A1', $remarks);
        //$this->excel->getActiveSheet()->mergeCells('D1:F1')->setCellValue('D1', $as_on_date);
        $this->excel->getActiveSheet()->getStyle("A1:E1")->applyFromArray($style)->getFont()->setBold('true');
        $this->excel->getActiveSheet()->setCellValue('A2', 'S.No');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Region');
        $this->excel->getActiveSheet()->setCellValue('C2', 'Outstanding Amount (In lacs)');
        $this->excel->getActiveSheet()->setCellValue('D2', 'Collections Planned for the Month (In lacs)');
        $this->excel->getActiveSheet()->setCellValue('E2', 'MTD Collections (In lacs)');
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
        $exceldata="";
        if(count($results)>0)
        {
            $arr=array();
            $i=1;
            foreach ($results as $row)
            {      
                    $exceldata=array();
                    $exceldata[] = @$i;
                    $exceldata[] = @$row['location'];
                    $exceldata[] = @$row['ot_amount'];
                    $exceldata[] = @$row['collections_planned'];
                    $exceldata[] = @$row['actual_collections'];
                    
                    $arr[]=$exceldata;
                    $i++;
                   
            }
             $this->excel->getActiveSheet()->fromArray($arr, null, 'A3');
        }
        else
        {
            $exceldata[]="No Records Found";
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
            $this->excel->getActiveSheet()->mergeCells('A3:F3');
        }
        $filename='OutStanding Report.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
        foreach(range('A2','F2') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $objWriter->save('php://output');
    }
}