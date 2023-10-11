<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Outstanding_bulk_upload extends Base_controller {

    public function __construct() 
    {
        parent::__construct();
      //  $this->load->model('Cnote_rbh_approval_m');
        $this->load->model('So_amount_upload_model');
        $this->load->library('excel');
    }
    /*Phase2 changes new enhancement 
      created by prasad on 4th aug
    */
    public function outstanding_amount_upload()
    {
    	# Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Outstanding Amount Bulk Upload";
        $data['nestedView']['cur_page'] = 'outstanding_amount_upload';
        $data['nestedView']['parent_page'] = 'outstanding_amount_upload';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
         $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';
        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';
        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'SO Outstanding Amount Upload';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'SO Amount Bulkupload', 'class' => 'active', 'url' => '');
        //fetching last updated time
        $data['updated_record']=$this->So_amount_upload_model->get_last_updated_record();
        
        
       // echo $this->db->last_query();
       // print_r($data['updated_record']);exit;
        $this->load->view('so_bulk_upload/so_amount_bulk_upload',$data);
    }

    public function generate_so_outstanding_xl()
    {
        $this->load->library('excel');
        $month_1=date('M-Y');
        $months_arr1=explode('-', $month_1);
        $month_number1=get_month_number($months_arr1[0]);
        $year1=$months_arr1[1];

        $count1 = mktime( 0, 0, 0, $month_number1, 1, $year1);
        $month_2=strftime( '%b-%Y', strtotime( '+1 month', $count1));
        $months_arr2=explode('-', $month_2);
        $month_number2=get_month_number($months_arr2[0]);
        $year2=$months_arr2[1];

        $count2=mktime( 0, 0, 0, $month_number2, 1, $year2);
        $month_3=strftime( '%b-%Y', strtotime( '+1 month', $count2));
        $note=date('d-M-y');

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('SO OutStanding Amount Upload');
        $this->excel->getActiveSheet()->setCellValue('A1', '');
        $this->excel->getActiveSheet()->setCellValue('B1', '');
        $this->excel->getActiveSheet()->setCellValue('C1', '');
        $this->excel->getActiveSheet()->setCellValue('D1', '');
        $this->excel->getActiveSheet()->setCellValue('E1', '');
        $this->excel->getActiveSheet()->setCellValue('F1', '');
        $this->excel->getActiveSheet()->setCellValue('G1', '');
        $this->excel->getActiveSheet()->mergeCells('H1:J1')->setCellValue('H1', 'Collection Plan');
        $this->excel->getActiveSheet()->setCellValue('A2', 'SO Number');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Sales');
        $this->excel->getActiveSheet()->setCellValue('C2', 'Collections');
        $this->excel->getActiveSheet()->setCellValue('D2', 'UTR No/Chq No');
        $this->excel->getActiveSheet()->setCellValue('E2', 'Collection Date (Ex:'.$note.')');
        $this->excel->getActiveSheet()->setCellValue('F2', 'OutStanding Amount');
        $this->excel->getActiveSheet()->setCellValue('G2', 'OutStanding as on Date(Ex:'.$note.')');
        $this->excel->getActiveSheet()->setCellValue('H2', $month_1);
        $this->excel->getActiveSheet()->setCellValue('I2', $month_2);
        $this->excel->getActiveSheet()->setCellValue('J2', $month_3);

        $filename='SO Outstanding Amount.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
        foreach(range('A1','H1') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $objWriter->save('php://output');

    }
     /*Phase2 changes new enhancement 
      created by prasad on 4th aug
    */
    public function insert_outstanding_amount_upload()
    {   
        if($this->input->post('submit'))
        {
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
             $extension=$upload_data['file_ext'];    // uploded file extension
             switch ($extension) {
                 case '.xlsx':
                      $objReader= PHPExcel_IOFactory::createReader('Excel2007'); // For excel 2007  
                     break;
                 case '.xls':
                       $objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003  
                     break;
                 default:
                     # code...
                     break;
             }
             //$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
             
            // print_r($objReader);exit;  
            //Set to read only
            $objReader->setReadDataOnly(true);          
            //Load excel file
            $objPHPExcel=$objReader->load(FCPATH.'application/uploads/excel/'.$file_name);      
            $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel         
            $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);

            $missing = 0;
            $inserted_files = 0;
            $updated_files = 0;
            $row_count =0;  

            $this->db->trans_begin();

            
            // To get Month data in Excel
            for($i=2; $i <=2 ; $i++) 
            { 
                // Validate Month Number
                //$month_1= validateDate($objWorksheet->getCellByColumnAndRow(7,$i)->getValue());
                $month_data1=$objWorksheet->getCellByColumnAndRow(7,$i)->getValue();
                $months_arr1=explode('-', $month_data1);
                $val1 = is_numeric($months_arr1[0]);
                // Get months Array
                $mon=get_upload_months();
                if($val1 == 1)
                {
                  $convert_date1 = ($month_data1 - 25569) * 86400;
                  $converted_date1= gmdate("M-Y", $convert_date1);
                  //$month_1 = validateDate($converted_date1);
                  $months_arr1=explode('-', $converted_date1);
                }
                // Check month is valid
                if(in_array(strtoupper($months_arr1[0]), $mon))
                {
                    $month_1=1;
                }
                else
                {
                  $month_1=0;
                }
                // month number
                $month_number1=get_month_number($months_arr1[0]);
                // month in year
                $year1=$months_arr1[1];


                $month_data2=$objWorksheet->getCellByColumnAndRow(8,$i)->getValue();

                $months_arr2=explode('-', $month_data2);
                $val2 = is_numeric($months_arr2[0]);
                if($val2 == 1)
                {
                  $convert_date2 = ($month_data2 - 25569) * 86400;
                  $converted_date2= gmdate("M-Y", $convert_date2);
                  $months_arr2=explode('-', $converted_date2);
                }
                if(in_array(strtoupper($months_arr2[0]), $mon))
                {
                    $month_2=1;
                }
                else
                {
                  $month_2=0;
                }
                $month_number2=get_month_number($months_arr2[0]);
                $year2=$months_arr2[1];

                $month_data3=$objWorksheet->getCellByColumnAndRow(9,$i)->getValue();
                $months_arr3=explode('-', $month_data3);
                $val3 = is_numeric($months_arr3[0]);
                if($val3 == 1)
                {
                  $convert_date3 = ($month_data3 - 25569) * 86400;
                  $converted_date3= gmdate("M-Y", $convert_date3);
                  $months_arr3 = explode('-', $converted_date3);
                }
                if(in_array(strtoupper($months_arr3[0]), $mon))
                {
                    $month_3=1;
                }
                else
                {
                  $month_3=0;
                }
                $month_number3=get_month_number($months_arr3[0]);
                $year3=$months_arr3[1];

            } 
            if(($month_1!=0) && ($month_2!=0) && ($month_3!=0))
            {   
                 $upload_data = array(
                                      'file_name'  => $file_name,
                                      'created_by' => $this->session->userdata('user_id'),
                                      'type'       =>2,
                                      'created_time'=> date('Y-m-d h:i:s')
                                      );
                    $upload_id = $this->Common_model->insert_data('upload_csv',$upload_data);    
                    $reupload = $this->input->post('missing_files');
                    if($reupload!=1)
                    {
                        $this->Common_model->update_data('contract_note',array('outstanding_amount'=>0),array('status !='=>''));
                    }
                //loop from first data untill last data
                for($i=3;$i<=$totalrows;$i++)
                {
                    $so_number= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue(); 
                    $sale_amount=$objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
                    $latest_sales_amount=format_upload_amount($sale_amount);
                    $collection_amount=$objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
                    $latest_collection_amount=format_upload_amount($collection_amount);
                    
                    $utr_no=$objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
                    $collection_date=$objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
                    $outstanding_amount= $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
                   // echo $outstanding_amount;exit;
                    //Excel Column 1
                    $latest_outstanding_amount=format_upload_amount($outstanding_amount);
                    $on_date= $objWorksheet->getCellByColumnAndRow(6,$i)->getValue();
                    $month1_value=$objWorksheet->getCellByColumnAndRow(7,$i)->getValue(); 
                    $m1_amount=format_upload_amount($month1_value);
                    $month2_value=$objWorksheet->getCellByColumnAndRow(8,$i)->getValue(); 
                    $m2_amount=format_upload_amount($month2_value);
                    $month3_value=$objWorksheet->getCellByColumnAndRow(9,$i)->getValue(); 
                    $m3_amount=format_upload_amount($month3_value);
                    $m1_amount_column=is_numeric($month1_value);
                    $m2_amount_column=is_numeric($month2_value);
                    $m3_amount_column=is_numeric($month3_value);
                    $sales_amount_column=is_numeric($sale_amount);
                    $collection_amount_column=is_numeric($collection_amount);
                    $on_date_column=is_numeric($on_date);
                    $collection_date_column=is_numeric($collection_date);

                    if($on_date_column !=1 || $collection_date_column !=1)
                    {
                        $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <div class="icon"><i class="fa fa-check"></i></div>
                                                <strong>Error!</strong> Check Collection Date & Outstanding As on Date Columns in Excel!
                                             </div>');
                        redirect(SITE_URL.'outstanding_amount_upload'); exit();
                    }
                    if($m1_amount_column=='' || $m2_amount_column=='' || $m3_amount_column=='' || $sales_amount_column=='' || $collection_amount_column=='')
                    {
                        
                        $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <div class="icon"><i class="fa fa-check"></i></div>
                                                <strong>Error!</strong> Check Amount Columns in Excel!
                                             </div>');
                        redirect(SITE_URL.'outstanding_amount_upload'); exit();
                    }
                    $remarks_missing_text='';
                    //Excel Column 2
                    if($collection_date!='')
                    {
                        $collection_on_date = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($collection_date));
                    }
                    else
                    {
                        $collection_on_date=''; 
                    }
                    if($on_date!='')
                    {
                        $as_on_date = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($on_date));
                    }
                     else
                    {
                        $as_on_date=''; 
                    }
                    if($so_number==''&&$latest_outstanding_amount==''&&$as_on_date=='')
                    {
                        continue;
                    }
                    $row_count++;
                    if($as_on_date !='')
                    {
                        $date= format_date($as_on_date);
                        $as_on_date=date('Y-m-d',strtotime($date));
                    }
                    else
                    {
                        $as_on_date = '';
                    }
                    if($collection_on_date !='')
                    {
                        $date= format_date($collection_on_date);
                        $collection_on_date=date('Y-m-d',strtotime($date));
                    }
                    else
                    {
                        $collection_on_date = '';
                    }
                   
                    //echo $as_on_date; exit;
                    if($so_number!='' && $latest_outstanding_amount !='' && $as_on_date !='')
                    {   
                        $query = 'SELECT * from so_outstanding_amount WHERE so_number="'.$so_number.'"
                                        AND as_on_date="'.$as_on_date.'" ';

                        $updated_count=$this->Common_model->get_no_of_rows($query);

                        if($updated_count <= 0)
                        {
                            $qry='select * from contract_note where so_number="'.$so_number.'"';
                            $count = $this->Common_model->get_no_of_rows($qry);
                            if($count > 0)
                            {   
                                 $dat4=array(
                                                'so_number'         =>$so_number,
                                                'outstanding_amount'=>$latest_outstanding_amount,
                                                'as_on_date'        =>$as_on_date,
                                                'sale_amount'       =>$latest_sales_amount,
                                                'collection_amount' =>$latest_collection_amount,
                                                'collection_date'   =>$collection_on_date,
                                                'utr_or_chq_number' =>$utr_no,
                                                'created_by'        =>$this->session->userdata('user_id'),
                                                'created_time'      =>date('Y-m-d h:i:s'),
                                                'upload_id'         =>$upload_id
                                            );
                                 //print_r($dat4); exit;
                                 $this->Common_model->insert_data('so_outstanding_amount',$dat4);
                                 $collection_plan_arr1=array(
                                                           'so_number'         =>$so_number,
                                                           'year'              =>$year1,
                                                           'month'             =>$month_number1,
                                                           'amount'            =>$m1_amount,
                                                           'upload_id'         =>$upload_id,
                                                           'status'            =>1,
                                                           'created_by'        =>$this->session->userdata('user_id'),
                                                            'created_time'      =>date('Y-m-d h:i:s'),
                                                           );
                                 $this->Common_model->insert_data('so_collection_plan',$collection_plan_arr1);
                                 $collection_plan_arr2=array(
                                                           'so_number'         =>$so_number,
                                                           'year'              =>$year2,
                                                           'month'             =>$month_number2,
                                                           'amount'            =>$m2_amount,
                                                           'upload_id'         =>$upload_id,
                                                           'status'            =>1,
                                                           'created_by'        =>$this->session->userdata('user_id'),
                                                            'created_time'      =>date('Y-m-d h:i:s'),
                                                           );
                                 $this->Common_model->insert_data('so_collection_plan',$collection_plan_arr2);
                                 $collection_plan_arr3=array(
                                                           'so_number'         =>$so_number,
                                                           'year'              =>$year3,
                                                           'month'             =>$month_number3,
                                                           'amount'            =>$m3_amount,
                                                           'upload_id'         =>$upload_id,
                                                           'status'            =>1,
                                                           'created_by'        =>$this->session->userdata('user_id'),
                                                            'created_time'      =>date('Y-m-d h:i:s'),
                                                           );
                                 $this->Common_model->insert_data('so_collection_plan',$collection_plan_arr3);
                                 $this->Common_model->update_data('contract_note',array('outstanding_amount'=>$latest_outstanding_amount,'as_on_date'=>$as_on_date),array('so_number'=>$so_number));
                                 $inserted_files++;
                                
                            }
                            else
                            {
                                $missing++;
                                $missing_files=array(
                                    'so_number' =>$so_number,
                                    'sale_amount'=>$latest_sales_amount,
                                    'collection_amount'=>$latest_collection_amount,
                                    'collection_date'=>$collection_on_date,
                                    'utr_or_chq_number'=>$utr_no,
                                    'month_1'=>$month_number1,
                                    'month_2'=>$month_number2,
                                    'month_3'=>$month_number3,
                                    'year_1'=>$year1,
                                    'year_2'=>$year2,
                                    'year_3'=>$year3,
                                    'month1_amount'=>$m1_amount,
                                    'month2_amount'=>$m2_amount,
                                    'month3_amount'=>$m3_amount,
                                    'outstanding_amount'=>$latest_outstanding_amount,
                                    'on_date'=>$as_on_date,
                                    'upload_id'=>$upload_id,
                                    'remarks_text'=>'SO Number not existed',
                                    'created_by'=>$this->session->userdata('user_id'),
                                    'created_time'=>date('Y-m-d h:i:s')
                                    );
                                $this->Common_model->insert_data('missing_files',$missing_files);
                            }
                        }
                        else
                        {
                            $updated_files++;
                            /*$missing_files=array(
                                'so_number' =>$so_number,
                                'outstanding_amount'=>$outstanding_amount,
                                'on_date'=>$as_on_date,
                                'upload_id'=>$upload_id,
                                'remarks_text'=>'Duplicate SO Number and OnDate ',
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s')
                                );
                            $this->Common_model->insert_data('missing_files',$missing_files);*/
                           // echo $latest_outstanding_amount;exit;
                            /*$this->Common_model->update_data('contract_note',array('outstanding_amount'=>$latest_outstanding_amount,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d h:i:s')),array('so_number'=>$so_number,'as_on_date'=>$as_on_date));*/

                          
                            $update_cn = array('outstanding_amount'=> $latest_outstanding_amount,
                                               'modified_by'       => $this->session->userdata('user_id'),
                                               'modified_time'     => date('Y-m-d H:i:s'));
                            $update_cn_where = array('so_number'   => $so_number,
                                                     'as_on_date'  => $as_on_date);
                            $this->Common_model->update_data('contract_note',$update_cn,$update_cn_where);
                            $this->Common_model->update_data('so_outstanding_amount',array('outstanding_amount'=>$latest_outstanding_amount,'created_by'=>$this->session->userdata('user_id'),'created_time'=>date('Y-m-d h:i:s'),'upload_id'=>$upload_id),array('so_number'=>$so_number,'as_on_date'=>$as_on_date));
                            $this->Common_model->update_data('so_collection_plan',array('upload_id'=>$upload_id,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d H:i:s')),array('so_number'=>$so_number,'date(created_time)'=>$as_on_date));

                        }
                    }
                    else
                    {
                        $missing++;
                        if($so_number =='')
                        {
                            $remarks_missing_text.='SO Number is missing,';
                            $so_number =NULL;
                        }
                        if($latest_outstanding_amount =='')
                        {
                            $remarks_missing_text.='OutStanding Amount is missing,';
                            $outstanding_amount= NULL;
                        }
                        if($as_on_date =='')
                        {
                            $remarks_missing_text.='As On Date is missing,';
                            $as_on_date=NULL;
                        }
                        if($latest_collection_amount =='')
                        {
                            $remarks_missing_text.='Collection Amount is missing,';
                            $latest_collection_amount=NULL;
                        }
                        if($latest_sales_amount =='')
                        {
                            $remarks_missing_text.='Sale Amount is missing,';
                            $latest_sales_amount=NULL;
                        }
                        if($collection_on_date =='')
                        {
                            $remarks_missing_text.='Collection Date is missing,';
                            $collection_on_date=NULL;
                        }
                        if($utr_no =='')
                        {
                            $remarks_missing_text.='UTR No/ Chq No is missing,';
                            $utr_no=NULL;
                        }
                        if($m1_amount =='')
                        {
                            $remarks_missing_text.=$month_data1.' Amount is missing,';
                            $m1_amount=NULL;
                        }
                        if($m2_amount =='')
                        {
                            $remarks_missing_text.=$month_data2.' Amount is missing,';
                            $m2_amount=NULL;
                        }
                        if($m3_amount =='')
                        {
                            $remarks_missing_text.=$month_data3.' Amount is missing,';
                            $m3_amount=NULL;
                        }
                        $missing_files=array(
                            'so_number' =>$so_number,
                            'sale_amount'=>$latest_sales_amount,
                            'collection_amount'=>$latest_collection_amount,
                            'collection_date'=>$collection_on_date,
                            'utr_or_chq_number'=>$utr_no,
                            'month_1'=>$month_number1,
                            'month_2'=>$month_number2,
                            'month_3'=>$month_number3,
                            'year_1'=>$year1,
                            'year_2'=>$year2,
                            'year_3'=>$year3,
                            'month1_amount'=>$m1_amount,
                            'month2_amount'=>$m2_amount,
                            'month3_amount'=>$m3_amount,
                            'outstanding_amount'=>$latest_outstanding_amount,
                            'on_date'=>$as_on_date,
                            'upload_id'=>$upload_id,
                            'remarks_text'=>$remarks_missing_text,
                            'created_by'=>$this->session->userdata('user_id'),
                            'created_time'=>date('Y-m-d h:i:s')
                            );
                        $this->Common_model->insert_data('missing_files',$missing_files);
                    }
                }
              
               if($missing > 0)
                {   
                   
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response','<div class="alert alert-warning alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Warning!</strong> Out of '.$row_count.' Records '.$inserted_files.' are inserted, Missed: '.$missing.' , Updated: '.$updated_files.' .Please Check!
                                         </div>');
                   redirect(SITE_URL.'missed_outstanding_upload_records/'.icrm_encode($upload_id));
                   
                }
                else
                {  

                    if ($this->db->trans_status() === FALSE)
                    {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <div class="icon"><i class="fa fa-check"></i></div>
                                                <strong>Error!</strong> There\'s a problem occured while uploading oustanding amount!
                                             </div>');
                        redirect(SITE_URL.'outstanding_amount_upload'); exit();
                        //echo 'transaction failed';
                            
                    }
                    else
                    {
                        $this->db->trans_commit();
                        echo "here2";
                        $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <div class="icon"><i class="fa fa-check"></i></div>
                                                <strong>Success!</strong> Out of '.$row_count.' records '.$inserted_files.' are inserted, Missed: '.$missing.' , Updated: '.$updated_files.'!
                                             </div>');
                        redirect(SITE_URL.'outstanding_amount_upload'); exit();
                        //echo 'transaction success';
                    }
                }
                unlink(FCPATH.'application/uploads/excel/'.$file_name); 
            }
            else
            {
               $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <div class="icon"><i class="fa fa-check"></i></div>
                                                <strong>Error!</strong> Check Months Format in Excel!
                                             </div>');
                redirect(SITE_URL.'outstanding_amount_upload'); exit();
            }
        }
    }
    public function download_missing_so_files($encoded_id)
    {  
        $upload_id=@icrm_decode($encoded_id);
        $missing_results = $this->Common_model->get_data('missing_files',array('upload_id'=>$upload_id));
        $m1_name=get_month_name($missing_results[0]['month_1']);
        $m2_name=get_month_name($missing_results[0]['month_2']);
        $m3_name=get_month_name($missing_results[0]['month_3']);
        $year1=$missing_results[0]['year_1'];
        $year2=$missing_results[0]['year_2'];
        $year3=$missing_results[0]['year_3'];
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Missing Outstanding Bulk Upload');;
        $this->excel->getActiveSheet()->setCellValue('A1', '');
        $this->excel->getActiveSheet()->setCellValue('B1', '');
        $this->excel->getActiveSheet()->setCellValue('C1', '');
        $this->excel->getActiveSheet()->setCellValue('D1', '');
        $this->excel->getActiveSheet()->setCellValue('E1', '');
        $this->excel->getActiveSheet()->setCellValue('F1', '');
        $this->excel->getActiveSheet()->setCellValue('G1', '');
        $this->excel->getActiveSheet()->mergeCells('H1:J1')->setCellValue('H1', 'Collection Plan');
        $this->excel->getActiveSheet()->setCellValue('A2', 'SO Number');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Sales');
        $this->excel->getActiveSheet()->setCellValue('C2', 'Collections');
        $this->excel->getActiveSheet()->setCellValue('D2', 'UTR No/Chq No');
        $this->excel->getActiveSheet()->setCellValue('E2', 'Collection Date');
        $this->excel->getActiveSheet()->setCellValue('F2', 'OutStanding Amount');
        $this->excel->getActiveSheet()->setCellValue('G2', 'OutStanding as on Date');
        $this->excel->getActiveSheet()->setCellValue('H2', $m1_name.'-'.$year1);
        $this->excel->getActiveSheet()->setCellValue('I2', $m2_name.'-'.$year2);
        $this->excel->getActiveSheet()->setCellValue('J2', $m3_name.'-'.$year3);
            $exceldata="";
            if(count($missing_results)>0)
            {
                $arr=array();
                foreach ($missing_results as $row)
                {      
                        $exceldata=array();
                        $exceldata[] = @$row['so_number'];
                        $exceldata[] = @$row['sale_amount'];
                        $exceldata[] = @$row['collection_amount'];
                        $exceldata[] = @$row['utr_or_chq_number'];
                        if(@$row['collection_date']!='')
                        {
                            $exceldata[] =date('d-m-Y',strtotime(@$row['collection_date']));
                        }
                        else
                        {
                          $exceldata[]='';
                        }
                        $exceldata[] = @$row['outstanding_amount'];
                        if(@$row['on_date']!='')
                        {
                            $exceldata[] =date('d-m-Y',strtotime(@$row['on_date']));
                        }
                        else
                        {
                          $exceldata[]='';
                        }
                        $exceldata[] = @$row['month1_amount'];
                        $exceldata[] = @$row['month2_amount'];
                        $exceldata[] = @$row['month3_amount'];
                        // echo "<br>";
                        $arr[]=$exceldata;
                       
                }
                 $this->excel->getActiveSheet()->fromArray($arr, null, 'A3');
            }
            else
            {
                 $exceldata[]="No Records Found";
                 $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
                 $this->excel->getActiveSheet()->mergeCells('A3:J3');
            }
           
          
            $filename='Missed OutStanding Amount List.xlsx'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
            foreach(range('A1','H1') as $columnID) {
                $this->excel->getActiveSheet()->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            $objWriter->save('php://output');

      
    }

     public function so_amount_list()
    {     
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "SO Amount Upload List";
        $data['nestedView']['cur_page'] = 'so_amount_list';
        $data['nestedView']['parent_page'] = 'so_amount_list';

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
        $config['base_url'] = SITE_URL.'so_amount_list/'; 
        # Total Records
        $config['total_rows'] = $this->So_amount_upload_model->so_amount_rows($searchParams);
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
        $data['searchResults'] = $this->So_amount_upload_model->so_amount_results($searchParams,$config['per_page'], $current_offset);
        $this->load->view('so_bulk_upload/so_amount_list', $data);
    }

    public function download_so_bulk_upload()
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
        $searchResults = $this->So_amount_upload_model->so_upload_list($searchParams);
      
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
            $exceldata=array();
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
    public function download_so_bulk_upload_details()
    {
        $upload_id=icrm_decode($this->uri->segment(2));
        $results=$this->Common_model->get_data('so_outstanding_amount',array('upload_id'=>$upload_id));
        
        foreach($results as $key =>$value)
        {
          $month_data=$this->So_amount_upload_model->get_upload_months_data($value['so_number'],$value['upload_id']);

          $i = 1;
          foreach($month_data as $key1=>$value1)
          {
            $results[$key]["value_$i"]=$value1['amount'];
            $results[$key]["month_$i"] = $value1['month'];
            $results[$key]["year_$i"] = $value1['year'];
            $i++;
          }
        }

        $m1_name=get_month_name($results[0]['month_1']);
        $month_1=$m1_name.'-'.$results[0]['year_1'];
        $months_arr1=explode('-', $month_1);
        $month_number1=get_month_number($months_arr1[0]);
        $year1=$months_arr1[1];

        $count1 = mktime( 0, 0, 0, $month_number1, 1, $year1);
        $month_2=strftime( '%b-%Y', strtotime( '+1 month', $count1));
        $months_arr2=explode('-', $month_2);
        $month_number2=get_month_number($months_arr2[0]);
        $year2=$months_arr2[1];

        $count2=mktime( 0, 0, 0, $month_number2, 1, $year2);
        $month_3=strftime( '%b-%Y', strtotime( '+1 month', $count2));
        $data ='';
        $data = '<table border="1">';
        $data.='<thead>';
        $data.='<tr>';
        $data.='<th></th>';
        $data.='<th></th>';
        $data.='<th></th>';
        $data.='<th></th>';
        $data.='<th></th>';
        $data.='<th></th>';
        $data.='<th></th>';
        $data.='<th></th>';
        $data.='<th colspan=3>Collection Plan</th>';
        $data.='</tr>';
        $data.='<tr>';
        $data.='<th>Sno</th>';
        $data.='<th>SO Number</th>';
        $data.='<th>Sales Amount</th>';
        $data.='<th>Collection Amount</th>';
        $data.='<th>UTR No/Chq No</th>';
        $data.='<th>Collection Date</th>';
        $data.='<th>Outstanding Amount </th>';
        $data.='<th>On Date</th>';
        $data.='<th>'.$month_1.'</th>';
        $data.='<th>'.$month_2.'</th>';
        $data.='<th>'.$month_3.'</th>';
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
              $data.='<tr>';
                $data.='<td>'.$i++.'</td>';
                $data.='<td>'.@$row['so_number'].'</td>';
                $data.='<td>'.@$row['sale_amount'].'</td>';
                $data.='<td>'.@$row['collection_amount'].'</td>';
                $data.='<td>'.@$row['utr_or_chq_number'].'</td>';
                $data.= '<td>'.date('d-m-Y',strtotime($row['collection_date'])).'</td>';
                $data.='<td>'.@$row['outstanding_amount'].'</td>';
                $data.='<td>'.date('d-m-Y',strtotime($row['as_on_date'])).'</td>';
                $data.='<td>'.@$row['value_1'].'</td>';
                $data.='<td>'.@$row['value_2'].'</td>';
                $data.='<td>'.@$row['value_3'].'</td>';
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

    public function missed_outstanding_upload_records($encoded_id)
    {
        $upload_id = icrm_decode($encoded_id);
        if($upload_id=='')
        {
            redirect(SITE_URL);exit;
        }
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "SO Outstanding Amount Misssing Records";
        $data['nestedView']['cur_page'] = 'outstanding_amount_upload';
        $data['nestedView']['parent_page'] = 'outstanding_amount_upload';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage SO Outstanding Amount';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
         $data['nestedView']['breadCrumbOptions'][] = array('label' => 'SO Amount BulkUpload', 'class' => '', 'url' => SITE_URL . 'outstanding_amount_upload');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'SO Amount Bulkupload Missing files', 'class' => 'active', 'url' => '');
       

        $data['missing_results'] = $this->Common_model->get_data('missing_files',array('upload_id'=>$upload_id));
        $data['upload_id'] = $upload_id;
        $this->load->view('so_bulk_upload/missing_so_bulk_upload',$data);
    }

   
}