<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Product_price extends Base_controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Product_price_m');
    }
    /*Phase2 changes new enhancement 
      created by prasad on 4th aug
    */
    public function product_price_upload()
    {
    	 # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Product Price Upload";
        $data['nestedView']['cur_page'] = 'product_stock_upload';
        $data['nestedView']['parent_page'] = 'product_stock_upload';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Product Price Upload';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product Price Upload', 'class' => 'active', 'url' => '');
        $data['updated_record']=$this->Product_price_m->get_last_updated_record();
       $this->load->view('product/product_price_upload',$data);
    }

    public function download_product_price_csv()
    {  
        $this->load->library('excel');
        //Fetching products
        $product_results = $this->Common_model->get_data('product',array('status'=>1));
        

        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Product Price Upload');
        //$this->excel->getActiveSheet()->setCellValue('A1', 'Outstanding  Excel Sheet');
        $this->excel->getActiveSheet()->setCellValue('A1', 'S.No.');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Product Code');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Product Description');
        $this->excel->getActiveSheet()->setCellValue('D1', 'MRP');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Base Price');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Freight Insurance');
        $this->excel->getActiveSheet()->setCellValue('G1', 'GST');
        $this->excel->getActiveSheet()->setCellValue('H1', 'RRP');
        $this->excel->getActiveSheet()->setCellValue('I1', 'DP');
        /*$this->excel->getActiveSheet()->mergeCells('A1:C1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
           
            */            
            $exceldata="";
             if(count($product_results)>0)
            {
                $i=1;
                $arr=array();
                foreach ($product_results as $row)
                {      
                        $exceldata=array();
                        $exceldata[] = $i++;
                        $exceldata[] = @$row['name'];
                        $exceldata[] = substr(@$row['description'],0,250);
                        $exceldata[] = '';
                        $exceldata[] = '';
                        $exceldata[] = '';
                        $exceldata[] = '';
                        $exceldata[] = '';
                        $exceldata[] = '';
                       // $exceldata[] = @$row['created_time'];
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
           
            //exit('hello');
            $filename='Price_Upload_List_'.date('Ymdhis').'.xlsx'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
            $objWriter->save('php://output');
            exit;
    }

    public function insert_product_price_upload()
    {
        if($this->input->post('submit'))
        {  
            //print_r($_FILES);exit;
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

           // $objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
          //  $objReader= PHPExcel_IOFactory::createReader('Excel2007'); // For excel 2007   
            // print_r($objReader);exit;  
            //Set to read only
            $objReader->setReadDataOnly(true);          
            //Load excel file
            $objPHPExcel=$objReader->load(FCPATH.'application/uploads/excel/'.$file_name);      
            $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel         
            $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
            

             $allQrys = '';
             $missing = 0;
             $inserted_files = 0;
             $updated_files = 0;
             $row_count=0;
             
            $this->db->trans_begin();

            $upload_data = array(
                'file_name'  => $file_name,
                'created_by' => $this->session->userdata('user_id'),
                'created_time'=> date('Y-m-d h:i:s'),
                'type'=>3
                );
            $upload_id = $this->Common_model->insert_data('upload_csv',$upload_data);
            //loop from first data untill last data
            for($i=2;$i<=$totalrows;$i++)
            {
                $remarks_missing_text='';
                
                $sno= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();           
                $product_name= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 1
                $description= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 2
                $mrp_price= $objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 3
                $base_price= $objWorksheet->getCellByColumnAndRow(4,$i)->getValue(); //Excel Column 4
               // $ed= $objWorksheet->getCellByColumnAndRow(5,$i)->getValue(); //Excel Column 5
               // $vat= $objWorksheet->getCellByColumnAndRow(6,$i)->getValue(); //Excel Column 6
                $freight_insurance= $objWorksheet->getCellByColumnAndRow(5,$i)->getValue(); //Excel Column 7
                $gst= $objWorksheet->getCellByColumnAndRow(6,$i)->getValue(); //Excel Column 8
                $rrp= $objWorksheet->getCellByColumnAndRow(7,$i)->getValue(); //Excel Column 9
                $dp= $objWorksheet->getCellByColumnAndRow(8,$i)->getValue(); //Excel Column 10
               // $on_date= $objWorksheet->getCellByColumnAndRow(11,$i)->getValue(); //Excel Column 11
                /*if($on_date!='')
                {
                    $as_on_date = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($on_date));
                 }
                 else
                {
                    $as_on_date=''; 
                }*/
                

                // If all columns empty skip that row
                if($sno==''&&$product_name==''&&$description==''&&$mrp_price==''&&$base_price==''&&$freight_insurance==''&&$gst==''&&$rrp==''&&$dp=='')
                {
                    continue;
                }
                $row_count++;
                /*if($as_on_date!='')
                {
                  $date= format_date($as_on_date);
                  $as_on_date=date('Y-m-d',strtotime($date));
                }
                else
                {
                    $as_on_date = '';
                }*/
                $as_on_date=date('Y-m-d');
               if(($product_name!='') &&($mrp_price !=''||$base_price!=''|| $freight_insurance!=''||$gst!=''||$rrp!=''||$dp!='' ))
                {   
                    $products = $this->Common_model->get_data_row('product',array('name'=>$product_name));
                    $query = 'SELECT * from product_price_history WHERE product_id="'.$products['product_id'].'" AND start_date="'.$as_on_date.'" ';
                    $updated_count=$this->Common_model->get_no_of_rows($query);
                    
                    $mrp_prices=($mrp_price!='') ? $mrp_price:$products['mrp'];
                    $base_prices=($base_price!='') ? $base_price:$products['base_price'];
                    /*$eds=($ed!='') ? $ed:$products['ed'];
                    $vats=($vat!='') ? $vat:$products['vat'];*/
                    $freight_insurances=($freight_insurance!='') ? $freight_insurance:$products['freight_insurance'];
                    $gsts=($gst!='') ? $gst:$products['gst'];
                    $rrps=($rrp!='') ? $rrp:$products['rrp'];
                    $dps=($dp!='') ? $dp:$products['dp'];

                    if($updated_count <= 0)
                    {
                        $qry='select * from product where product_id="'.$products['product_id'].'"';
                        $count = $this->Common_model->get_no_of_rows($qry);
                        if($count > 0)
                        {   
                            
                            $latest_record=$this->Product_price_m->get_latest_price_record($products['product_id']);
                            if(count($latest_record)>0)
                            {
                               $this->Common_model->update_data('product_price_history',array('end_date'=>$as_on_date,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d h:i:s'),'status'=>2),array('price_history_id'=>$latest_record['price_history_id'])); 
                            }
                             $dat4=array(
                                'product_id' =>$products['product_id'],
                                'mrp_price'=>$mrp_prices,
                                'base_price'=>$base_prices,
                                'freight_insurance'=>$freight_insurances,
                                'gst'=>$gsts,
                                'rrp'=>$rrps,
                                'dp'=>$dps,
                                'start_date'=>$as_on_date,
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s'),
                                'upload_id'=>$upload_id
                            );
                             $update_data=array(
                                'mrp'=>$mrp_prices,
                                'base_price'=>$base_prices,
                                'freight_insurance'=>$freight_insurances,
                                'gst'=>$gsts,
                                'rrp'=>$rrps,
                                'dp'=>$dps,
                                'modified_by'=>$this->session->userdata('user_id'),
                                'modified_time'=>date('Y-m-d h:i:s')
                            );
                            $this->Common_model->insert_data('product_price_history',$dat4);
                            $this->Common_model->update_data('product',$update_data,array('product_id'=>$products['product_id']));
                             $inserted_files++;
                            
                        }
                        else
                        {
                            $missing++;
                            $missing_files=array(
                                'product_id' =>$product_name,
                                'mrp_price'=>$mrp_price,
                                'base_price'=>$base_price,
                                'freight_insurance'=>$freight_insurance,
                                'gst'=>$gst,
                                'rrp'=>$rrp,
                                'dp'=>$dp,
                                'as_on_date'=>$as_on_date,
                                'upload_id'=>$upload_id,
                                'remarks_text'=>'Product Code not existed',
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s'),
                                'description'=>$description
                                );
                            $this->Common_model->insert_data('missing_product_price_files',$missing_files);
                        }
                    }
                    else
                    {
                        $updated_files++;
                        $update_data=array(
                                'mrp_price'=>$mrp_prices,
                                'base_price'=>$base_prices,
                                'freight_insurance'=>$freight_insurances,
                                'gst'=>$gsts,
                                'rrp'=>$rrps,
                                'dp'=>$dps,
                                'modified_by'=>$this->session->userdata('user_id'),
                                'modified_time'=>date('Y-m-d h:i:s'),
                                'upload_id'=>$upload_id
                            );
                        $update_data1=array(
                                'mrp'=>$mrp_prices,
                                'base_price'=>$base_prices,
                                'freight_insurance'=>$freight_insurances,
                                'gst'=>$gsts,
                                'rrp'=>$rrps,
                                'dp'=>$dps,
                                'modified_by'=>$this->session->userdata('user_id'),
                                'modified_time'=>date('Y-m-d h:i:s')
                            );
                        $this->Common_model->update_data('product',$update_data1,array('product_id'=>$products['product_id']));
                        $this->Common_model->update_data('product_price_history',$update_data,array('product_id'=>$products['product_id'],'start_date'=>$as_on_date));

                    }
                }
                else
                {   
                   // echo 'hi';exit;
                    $missing++;
                    if($product_name =='')
                    {
                        $remarks_missing_text.='Product Code is missing,';
                        $product_name =NULL;
                    }
                    if($mrp_price =='' && $base_price ==''&& $freight_insurance ==''&&$gst ==''&&$rrp ==''&&$dp =='')
                    {
                        $remarks_missing_text.='No price is mentioned,';
                        $quantity =NULL;
                    }
                    if($as_on_date =='')
                    {
                        $remarks_missing_text.='As On Date is missing,';
                        $as_on_date =NULL;
                    }
                    if($description=='')
                    {
                        $description.='Description is missing,';
                        $description =NULL;
                    }
                    $missing_files=array(
                                'product_id' =>$product_name,
                                'mrp_price'=>$mrp_price,
                                'base_price'=>$base_price,
                                'freight_insurance'=>$freight_insurance,
                                'gst'=>$gst,
                                'rrp'=>$rrp,
                                'dp'=>$dp,
                                'as_on_date'=>$as_on_date,
                                'upload_id'=>$upload_id,
                                'remarks_text'=> $remarks_missing_text,
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s'),
                                'description'=>$description
                                );
                        $this->Common_model->insert_data('missing_product_price_files',$missing_files);
                       // echo $this->db->last_query();exit;
                }

            }
            if($missing > 0)
            {   
                $this->db->trans_commit();
                $this->session->set_flashdata('response','<div class="alert alert-warning alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Warning!</strong> Out Of '.$row_count.' Records ,'.$inserted_files.' are inserted, Missed: '.$missing.' , Updated: '.$updated_files.' .Please Check!
                                     </div>');
                redirect(SITE_URL.'missed_product_price_records/'.icrm_encode($upload_id));
                // echo '<pre>';print_r($data);exit;
                   
            }
            else
            {  

                if ($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Error!</strong> There\'s a problem occured while uploading Product Price!
                                         </div>');
                    redirect(SITE_URL.'product_price_upload');
                    //echo 'transaction failed';
                        
                }
                else
                {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <div class="icon"><i class="fa fa-check"></i></div>
                                            <strong>Success!</strong>Out of '.$row_count.' records '.$inserted_files.' are inserted, Missed: '.$missing.' , Updated: '.$updated_files.' !
                                         </div>');
                    redirect(SITE_URL.'product_price_upload');
                    //echo 'transaction success';
                }
            }
        }
    }
     public function missed_product_price_records($encoded_id)
    {
        $upload_id = icrm_decode($encoded_id);
        //echo $upload_id;exit;
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Product Price Misssing Records";
        $data['nestedView']['cur_page'] = 'missed_product_price_records';
        $data['nestedView']['parent_page'] = 'missed_product_price_records';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Product Stock';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
         $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product Price BulkUpload', 'class' => '', 'url' => SITE_URL . 'product_price_upload');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product price Bulkupload Missing files', 'class' => 'active', 'url' => '');
       

        $data['missing_results'] = $this->Common_model->get_data('missing_product_price_files',array('upload_id'=>$upload_id));
        $data['upload_id'] = $upload_id;
      //  print_r($data['missing_results']);exit;
        $this->load->view('product/missing_product_price_bulk_upload',$data);
    }

     public function download_missing_product_price_files($encoded_id)
    {  
        $upload_id=@icrm_decode($encoded_id);
        if($upload_id=='')
        {
            redirect(SITE_URL);exit;
        }
        $missing_results = $this->Common_model->get_data('missing_product_price_files',array('upload_id'=>$upload_id));
        $data ='';
        $data = '<table border="1">';
        $data.='<thead>';
        $data.='<tr>';
        $data.='<th>Sno</th>';
        $data.='<th>Product Code</th>';
        $data.='<th>Description </th>';
        $data.='<th>MRP </th>';
        $data.='<th>Base Price </th>';
       /* $data.='<th>ED </th>';
        $data.='<th>VAT </th>';*/
        $data.='<th>Freight Insurance </th>';
        $data.='<th>GST </th>';
        $data.='<th>RRP </th>';
        $data.='<th>DP </th>';
        $data.='<th>On Date</th>';
        $data.='<th>Remarks</th>';
        $data.='</tr>';
        $data.='</thead>';
        $data.='<tbody>';
        
        if(count($missing_results)>0)
        {
            $i=1;
            foreach($missing_results as $row)
            {
                $data.='<tr>';
                $data.='<td>'.$i++.'</td>';
                $data.='<td>'.@$row['product_code'].'</td>';
                $data.='<td>'.@$row['description'].'</td>';
                $data.='<td>'.@$row['mrp_price'].'</td>';
                $data.='<td>'.@$row['base_price'].'</td>';
                /*$data.='<td>'.@$row['ed'].'</td>';
                $data.='<td>'.@$row['vat'].'</td>';*/
                $data.='<td>'.@$row['freight_insurance'].'</td>';
                $data.='<td>'.@$row['gst'].'</td>';
                $data.='<td>'.@$row['rrp'].'</td>';
                $data.='<td>'.@$row['dp'].'</td>';
                if($row['as_on_date']!='')
                {
                    $data.='<td>'.date('d-m-Y',strtotime($row['as_on_date'])).'</td>';
                }
                else
                {
                    $data.='<td></td>';
                }
                $data.='<td>'.$row['remarks_text'].'</td>';
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
        $xlFile='ProductPriceMissingRecords_'.$time.'.xls'; 
        header("Content-type: application/x-msdownload"); 
        # replace excelfile.xls with whatever you want the filename to default to
        header("Content-Disposition: attachment; filename=".$xlFile."");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $data;
    }

     public function product_price_list()
    {     
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Product Price Upload List";
        $data['nestedView']['cur_page'] = 'product_price_list';
        $data['nestedView']['parent_page'] = 'product_price_list';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Product Price Upload List';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product Price List', 'class' => '', 'url' =>'');
        $data['pageDetails'] = 'product_stock_list';
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
        $config['base_url'] = SITE_URL.'product_price_list/'; 
        # Total Records
        $config['total_rows'] = $this->Product_price_m->product_price_rows($searchParams);
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
        $data['searchResults'] = $this->Product_price_m->product_price_results($searchParams,$config['per_page'], $current_offset);
        $this->load->view('product/product_price_list', $data);
    }

     public function download_product_price_bulk_upload()
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
        $searchResults = $this->Product_price_m->pp_upload_list($searchParams);
        $data ='';
        $data = '<table border="1">';
        $data.='<thead>';
        $data.='<tr>';
        $data.='<th>Sno</th>';
        $data.='<th>Upload Id</th>';
        $data.='<th>File </th>';
        $data.='<th>Uploaded By</th>';
        $data.='<th>Uploaded Time</th>';
        $data.='</tr>';
        $data.='</thead>';
        $data.='<tbody>';
        
        if(count($searchResults)>0)
        {
            $i=1;
            foreach($searchResults as $row)
            {
                $data.='<tr>';
                $data.='<td>'.$i++.'</td>';
                $data.='<td>'.@$row['upload_id'].'</td>';
                $data.='<td>'.@$row['file_name'].'</td>';
                $data.='<td>'.getUserName($row['created_by']).'</td>';
                $data.='<td>'.$row['created_time'].'</td>';
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
        $xlFile='ProductPriceList_'.$time.'.xls'; 
        header("Content-type: application/x-msdownload"); 
        # replace excelfile.xls with whatever you want the filename to default to
        header("Content-Disposition: attachment; filename=".$xlFile."");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $data;
    }

      public function download_pp_bulk_upload_details()
    {   
        $upload_id=icrm_decode($this->uri->segment(2));
        $upload_details=$this->Common_model->get_data_row('upload_csv',array('upload_id'=>$upload_id));
        $results=$this->Product_price_m->get_product_details($upload_id);
        $data='<p> Upload Id : '.$upload_details['upload_id'].' --- Uploaded By : '.getUserName($upload_details['created_by']).'</p>';
        $data.='';
        $data.= '<table border="1">';
        $data.='<thead>';
        $data.='<tr>';
        $data.='<th>Sno</th>';
        $data.='<th>Product</th>';
        $data.='<th>Description </th>';
        $data.='<th>MRP </th>';
        $data.='<th>Base Price </th>';
        /*$data.='<th>ED </th>';
        $data.='<th>VAT </th>';*/
        $data.='<th>Freight Insurance </th>';
        $data.='<th>GST </th>';
        $data.='<th>RRP </th>';
        $data.='<th>DP </th>';
        $data.='<th>StartDate</th>';
        $data.='<th>EndDate</th>';
        $data.='</tr>';
        $data.='</thead>';
        $data.='<tbody>';
        
        if(count($results)>0)
        {
            $i=1;
            foreach($results as $row)
            {
                $data.='<tr>';
                $data.='<td>'.$i++.'</td>';
                $data.='<td>'.@$row['name'].'</td>';
                $data.='<td>'.@$row['description'].'</td>';
                $data.='<td>'.@$row['mrp_price'].'</td>';
                $data.='<td>'.@$row['base_price'].'</td>';
                /*$data.='<td>'.@$row['ed'].'</td>';
                $data.='<td>'.@$row['vat'].'</td>';*/
                $data.='<td>'.@$row['freight_insurance'].'</td>';
                $data.='<td>'.@$row['gst'].'</td>';
                $data.='<td>'.@$row['rrp'].'</td>';
                $data.='<td>'.@$row['dp'].'</td>';
                if($row['start_date']!='')
                {
                    $data.='<td>'.date('d-m-Y',strtotime($row['start_date'])).'</td>';
                }
                else
                {
                    $data.='<td></td>';
                }
                if($row['end_date']!='')
                {
                    $data.='<td>'.date('d-m-Y',strtotime($row['end_date'])).'</td>';
                }
                else
                {
                    $data.='<td>---</td>';
                }
                $data.='</tr>';
            }
        }
        else
        {
            $data.='<tr><td colspan="4" align="center">No Records Found </td></tr>';
        }
        $data.='</tbody>';
        $data.='</table>';
        $time = date("Ymdhis");
        $xlFile='Product Price UploadList_'.$time.'.xls'; 
        header("Content-type: application/x-msdownload"); 
        # replace excelfile.xls with whatever you want the filename to default to
        header("Content-Disposition: attachment; filename=".$xlFile."");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $data;
    }
}