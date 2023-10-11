<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Product_bulk_upload extends Base_controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Product_bulk_upload_m');
        $this->load->model('Product_price_m');
    }
    /*Phase2 changes new enhancement 
      created by prasad on 4th aug
    */
    public function downloadProduct_Upload()
    {
        if($this->input->post('downloadProduct')!='') 
        {
            $this->load->library('excel');
            $products = $this->Product_bulk_upload_m->productDetails();
            $note='Active / Inactive';
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Products List');
            $this->excel->getActiveSheet()->setCellValue('A1', 'Product Code');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Product Name');
            $this->excel->getActiveSheet()->setCellValue('C1', 'Segment');
            $this->excel->getActiveSheet()->setCellValue('D1', 'Sub System');
            $this->excel->getActiveSheet()->setCellValue('E1', 'Product Description');
            /*$this->excel->getActiveSheet()->setCellValue('E1', 'Specifications');
            $this->excel->getActiveSheet()->setCellValue('F1', 'Scope');*/
            $this->excel->getActiveSheet()->setCellValue('F1', 'MRP');
            $this->excel->getActiveSheet()->setCellValue('G1', 'Base Price');
            $this->excel->getActiveSheet()->setCellValue('H1', 'Freight Insurance');
            $this->excel->getActiveSheet()->setCellValue('I1', 'GST');
            $this->excel->getActiveSheet()->setCellValue('J1', 'RRP');
            $this->excel->getActiveSheet()->setCellValue('K1', 'DP');
            $this->excel->getActiveSheet()->setCellValue('L1', 'Product Type');
            $this->excel->getActiveSheet()->setCellValue('M1', 'Target');
            $this->excel->getActiveSheet()->setCellValue('N1', 'Availability ('.$note.')');
            $exceldata="";
            if(count($products)>0)
            {
                $arr=array();

                foreach ($products as $product)
                {      /*strip_tags(html_entity_decode())*/
                    if($product['target']!='' || $product['target']>0)
                    {
                        if($product['target']==1)
                        {
                            $target = 'Yes';
                        }
                        else if($product['target'] == 2)
                        {
                            $target = 'No';
                        }
                    }
                    else
                    {
                        $target = '';
                    }
                    if($product['availability']!='' || $product['availability']>0)
                    {
                        if($product['availability']==1)
                        {
                            $availability = 'Active';
                        }
                        else if($product['availability'] == 2)
                        {
                            $availability = 'Inactive';
                        }
                    }
                    else
                    {
                        $target = '';
                    }

                    $exceldata=array();
                    $exceldata[] = @$product['name'];
                    $exceldata[] = @$product['name2'];
                    $exceldata[] = @$product['GroupName'];
                    $exceldata[] = @$product['category_name'];
                    $exceldata[] = @$product['description'];
                    /*$exceldata[] = @$product['features'];
                    $exceldata[] = @$product['scope'];*/
                    $exceldata[] = @$product['mrp'];
                    $exceldata[] = @$product['base_price'];
                    $exceldata[] = @$product['freight_insurance'];
                    $exceldata[] = @$product['gst'];
                    $exceldata[] = @$product['rrp'];
                    $exceldata[] = @$product['dp'];
                    $exceldata[] = @$product['pt_name'];
                    $exceldata[] = @$target;
                    $exceldata[] = @$availability;
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

            $filename='Products_List_'.date('Y-m-d h:i:s').'.xlsx'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
            foreach(range('A1','N1') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
            }
            $objWriter->save('php://output');
            exit;   
        }
    }

    public function product_bulk_upload()
    {
         # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Product Upload";
        $data['nestedView']['cur_page'] = 'product_bulk_upload';
        $data['nestedView']['parent_page'] = 'product_bulk_upload';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Product Upload';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product Upload', 'class' => 'active', 'url' => '');

       $this->load->view('product/product_upload',$data);
    }

     public function download_product_csv()
    {  
        $this->load->library('excel');
       
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Product List Upload');
        //$this->excel->getActiveSheet()->setCellValue('A1', 'Outstanding  Excel Sheet');
        $this->excel->getActiveSheet()->setCellValue('A1', 'Product Code');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Name');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Segment');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Description');
        /*$this->excel->getActiveSheet()->setCellValue('E1', 'Features');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Scope');*/
        $this->excel->getActiveSheet()->setCellValue('E1', 'MRP');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Base Price');
        $this->excel->getActiveSheet()->setCellValue('G1', 'Freight Insurance');
        $this->excel->getActiveSheet()->setCellValue('H1', 'GST');
        $this->excel->getActiveSheet()->setCellValue('I1', 'RRP');
        $this->excel->getActiveSheet()->setCellValue('J1', 'DP');
        $this->excel->getActiveSheet()->setCellValue('K1', 'Product Type');
        $this->excel->getActiveSheet()->setCellValue('L1', 'Target');
        /*$this->excel->getActiveSheet()->mergeCells('A1:C1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
           
            */            
           
           
          
            $filename='Product Upload List.xlsx'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
            $objWriter->save('php://output');
    }

    public function insert_product_list_upload()
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
             $check_product_type_arr=$this->Product_bulk_upload_m->get_product_type();
             foreach($check_product_type_arr as $row)
             {
                $product_type_arr[$row['name']]=$row['product_type_id'];
             }
            // echo $row_count; exit;
            $this->db->trans_begin();

            $upload_data = array(
                'file_name'  => $file_name,
                'created_by' => $this->session->userdata('user_id'),
                'created_time'=> date('Y-m-d h:i:s'),
                'type'=>4
                );
            $upload_id = $this->Common_model->insert_data('upload_csv',$upload_data);
            //loop from first data untill last data
            for($i=2;$i<=$totalrows;$i++)
            {
                $remarks_missing_text='';
                
                $product_code= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();//Excel Column 1            
                $product_name= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();//Excel Column 2
                $segment= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 3
                $sub_system= $objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 4
                $description= $objWorksheet->getCellByColumnAndRow(4,$i)->getValue(); //Excel Column 4
                //$features= $objWorksheet->getCellByColumnAndRow(4,$i)->getValue(); //Excel Column 5
                //$scope= $objWorksheet->getCellByColumnAndRow(5,$i)->getValue(); //Excel Column 6
                $latest_mrp_price= $objWorksheet->getCellByColumnAndRow(5,$i)->getValue(); //Excel Column 7
                $mrp_price=format_upload_amount($latest_mrp_price);
                $latest_base_price= $objWorksheet->getCellByColumnAndRow(6,$i)->getValue(); //Excel Column 8
                $base_price=format_upload_amount($latest_base_price);
                $freight_insurance= $objWorksheet->getCellByColumnAndRow(7,$i)->getValue(); //Excel Column 9
                $gst= $objWorksheet->getCellByColumnAndRow(8,$i)->getValue(); //Excel Column 10
                $latest_rrp= $objWorksheet->getCellByColumnAndRow(9,$i)->getValue(); //Excel Column 11
                $rrp=format_upload_amount($latest_rrp);
                $latest_dp= $objWorksheet->getCellByColumnAndRow(10,$i)->getValue(); //Excel Column 12
                $dp=format_upload_amount($latest_dp);
                $product_type= $objWorksheet->getCellByColumnAndRow(11,$i)->getValue(); //Excel Column 13
                $target= $objWorksheet->getCellByColumnAndRow(12,$i)->getValue(); //Excel Column 14
                $availability= $objWorksheet->getCellByColumnAndRow(13,$i)->getValue(); //Excel Column 15
                
                // If all columns empty skip that row
                if($product_code==''&&$product_name==''&&$segment==''&&$description==''&&$mrp_price===''&&$base_price===''&&$freight_insurance===''&&$gst===''&&$rrp===''&&$dp===''&&$product_type==''&&$target=='')
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
               if($product_code!=''&&$segment!=''&&$product_type!='' &&$mrp_price !==''&&$freight_insurance!==''&&$gst!==''&&$rrp!==''&&$dp!==''&& (strtoupper($availability)=='ACTIVE' || strtoupper($availability)=='INACTIVE') )
                {   
                    $products = $this->Common_model->get_data_row('product',array('name'=>$product_code));
                    /*$query = 'SELECT * from product_price_history WHERE product_id="'.$products['product_id'].'" AND start_date="'.$as_on_date.'" ';
                    $updated_count=$this->Common_model->get_no_of_rows($query);*/
                    
                    $mrp_prices=($mrp_price!=='') ? $mrp_price:0;
                    $base_prices=($base_price!=='') ? $base_price:0;
                    /*$eds=($ed!='') ? $ed:$products['ed'];
                    $vats=($vat!='') ? $vat:$products['vat'];*/
                    $freight_insurances=($freight_insurance!=='') ? $freight_insurance:0;
                    $gsts=($gst!=='') ? $gst:0;
                    $rrps=($rrp!=='') ? $rrp:0;
                    $dps=($dp!=='') ? $dp:0;

                    if(count($products) <= 0)
                    {
                       /* $qry='select * from product  p 
                              inner join product_group pg on p.group_id=pg.group_id
                              where product_id="'.$products['product_id'].'"
                              and strtolower(pg.name)="'.strtolower($segment).'"';
                        $count = $this->Common_model->get_no_of_rows($qry);*/
                        $check=$this->Product_bulk_upload_m->check_group($segment);
                        $sub_category_check=$this->Product_bulk_upload_m->check_sub_system($sub_system);
                        if(array_key_exists($product_type, $product_type_arr))
                        {
                            $check_product_type=1;
                        }
                        else
                        {
                            $check_product_type=0;
                        }


                        /*$check_product_type=$this->Product_bulk_upload_m->check_product_type($product_type);*/
                        if(count($check) > 0 && count($sub_category_check)>0 && $check_product_type>0 &&  (strtoupper($target)=='YES' || strtoupper($target)=='NO'))
                        {   
                            $insert_data=array(
                                'name'=>$product_code,
                                'description'=>$description,
                                'name2'=>$product_name,
                                'group_id'=>$check['group_id'],
                                'mrp'=>$mrp_prices,
                                'base_price'=>$base_prices,
                                'freight_insurance'=>$freight_insurances,
                                'gst'=>$gsts,
                                'rrp'=>$rrps,
                                'dp'=>$dps,
                                'as_on_date'=>$as_on_date,
                                'sub_category_id'=>$sub_category_check['sub_category_id'],
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s'),
                                'status'=>1
                            );
                            if(strtoupper($target)=='YES')
                            {
                                $insert_data['target']=1;
                            }
                            elseif(strtoupper($target)=='NO')
                            {
                                $insert_data['target']=2;
                            }
                            /*if(strtoupper($product_type)=='Accessory')
                            {
                                $insert_data['product_type_id']=2;
                            }
                            elseif(strtoupper($product_type)=='Main Unit')
                            {
                                $insert_data['product_type_id']=1;
                            }
                            elseif(strtoupper($product_type)=='Consumable')
                            {
                                $insert_data['product_type_id']=3;
                            }
                            elseif(strtoupper($product_type)=='Spare')
                            {
                                $insert_data['product_type_id']=4;
                            }
                            elseif(strtoupper($product_type)=='CMC')
                            {
                                $insert_data['product_type_id']=5;
                            }
                            elseif(strtoupper($product_type)=='NCMC')
                            {
                                $insert_data['product_type_id']=6;
                            }*/
                            if(strtoupper($product_type)=='ACCESSORY')
                            {
                                $insert_data['product_type_id']=2;
                            }
                            elseif(strtoupper($product_type)=='MAIN UNIT')
                            {
                                $insert_data['product_type_id']=1;
                            }
                            elseif(strtoupper($product_type)=='CONSUMABLE')
                            {
                                $insert_data['product_type_id']=3;
                            }
                            elseif(strtoupper($product_type)=='SPARE')
                            {
                                $insert_data['product_type_id']=4;
                            }
                            elseif(strtoupper($product_type)=='CMC')
                            {
                                $insert_data['product_type_id']=5;
                            }
                            elseif(strtoupper($product_type)=='NCMC')
                            {
                                $insert_data['product_type_id']=6;
                            }
                            if(strtoupper($availability)=='ACTIVE')
                            {
                                $insert_data['availability']=1;
                            }
                            else if(strtoupper($availability)=='INACTIVE')
                            {
                                $insert_data['availability']=2;
                            }
                            $p_id=$this->Common_model->insert_data('product',$insert_data);
                            $dat4=array(
                                'product_id' =>$p_id,
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
                             $this->Common_model->insert_data('product_price_history',$dat4);
                             $inserted_files++;
                            
                        }
                        else
                        {
                            $missing++;
                            $missing_files=array(
                                'name' =>$product_code,
                                'name2'=>$product_name,
                                'mrp'=>$mrp_price,
                                'base_price'=>$base_price,
                                'freight_insurance'=>$freight_insurance,
                                'gst'=>$gst,
                                'rrp'=>$rrp,
                                'dp'=>$dp,
                                'upload_id'=>$upload_id,
                                'remarks_text'=>'Segment or sub system does not existed',
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s'),
                                'description'=>$description,
                                'product_type'=>$product_type,
                                'target'=>$target,
                                'group'=>$segment,
                                'sub_category'=>$sub_system,
                                'availability'=>$availability
                                );
                            $this->Common_model->insert_data('missing_product_files',$missing_files);
                        }
                    }
                    else
                    {   
                        $check=$this->Product_bulk_upload_m->check_group($segment);
                        $sub_category_check=$this->Product_bulk_upload_m->check_sub_system($sub_system);
                        if(array_key_exists($product_type, $product_type_arr))
                        {
                            $check_product_type=1;
                        }
                        else
                        {
                            $check_product_type=0;
                        }
                        /*$check_product_type=$this->Product_bulk_upload_m->check_product_type($product_type);*/
                        if(count($check) <= 0 || count($sub_category_check)<=0 || $check_product_type<=0 || (strtoupper($target)!='YES' && strtoupper($target)!='NO'))
                        {  
                            $missing++;
                            $missing_files=array(
                                'name' =>$product_code,
                                'name2'=>$product_name,
                                'mrp'=>$mrp_price,
                                'base_price'=>$base_price,
                                'freight_insurance'=>$freight_insurance,
                                'gst'=>$gst,
                                'rrp'=>$rrp,
                                'dp'=>$dp,
                                'upload_id'=>$upload_id,
                                'remarks_text'=>'Segment or sub system or Product Type does not existed',
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s'),
                                'description'=>$description,
                                'product_type'=>$product_type,
                                'target'=>$target,
                                'group'=>$segment,
                                'sub_category'=>$sub_system,
                                'availability'=>$availability
                                );
                            $this->Common_model->insert_data('missing_product_files',$missing_files);
                        } 
                        else
                        {
                            $updated_files++;
                            $query = 'SELECT * from product_price_history WHERE product_id="'.$products['product_id'].'" AND start_date="'.$as_on_date.'" ';
                            $updated_count=$this->Common_model->get_no_of_rows($query);
                            if($updated_count > 0)
                            {
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
                                 $this->Common_model->update_data('product_price_history',$update_data,array('product_id'=>$products['product_id'],'start_date'=>$as_on_date));
                            }
                            else
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
                                 $this->Common_model->insert_data('product_price_history',$dat4);
                            }

                            $update_data1=array(
                                'name'=>$product_code,
                                'description'=>$description,
                                'name2'=>$product_name,
                                'group_id'=>$check['group_id'],
                                'mrp'=>$mrp_prices,
                                'base_price'=>$base_prices,
                                'freight_insurance'=>$freight_insurances,
                                'gst'=>$gsts,
                                'rrp'=>$rrps,
                                'dp'=>$dps,
                                'as_on_date'=>$as_on_date,
                                'sub_category_id'=>$sub_category_check['sub_category_id'],
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s'),
                                'status'=>1
                            );
                            if(strtoupper($target)=='YES')
                            {
                                $update_data1['target']=1;
                            }
                            elseif(strtoupper($target)=='NO')
                            {
                                $update_data1['target']=2;
                            }
                            if(strtoupper($availability)=='ACTIVE')
                            {
                                $update_data1['availability']=1;
                            }
                            elseif(strtoupper($availability)=='INACTIVE')
                            {
                                $update_data1['availability']=2;
                            }
                            if(strtoupper($product_type)=='ACCESSORY')
                            {
                                $update_data1['product_type_id']=2;
                            }
                            elseif(strtoupper($product_type)=='MAIN UNIT')
                            {
                                $update_data1['product_type_id']=1;
                            }
                            elseif(strtoupper($product_type)=='CONSUMABLE')
                            {
                                $update_data1['product_type_id']=3;
                            }
                            elseif(strtoupper($product_type)=='SPARE')
                            {
                                $update_data1['product_type_id']=4;
                            }
                            elseif(strtoupper($product_type)=='CMC')
                            {
                                $update_data1['product_type_id']=5;
                            }
                            elseif(strtoupper($product_type)=='NCMC')
                            {
                                $update_data1['product_type_id']=6;
                            }
                            /*$update_data1=array(
                                    'mrp'=>$mrp_prices,
                                    'base_price'=>$base_prices,
                                    'freight_insurance'=>$freight_insurances,
                                    'gst'=>$gsts,
                                    'rrp'=>$rrps,
                                    'dp'=>$dps,
                                    'modified_by'=>$this->session->userdata('user_id'),
                                    'modified_time'=>date('Y-m-d h:i:s')
                                );*/
                            $this->Common_model->update_data('product',$update_data1,array('product_id'=>$products['product_id']));
                        }
                    }
                }
                else
                {   
                   // echo 'hi';exit;
                    $missing++;
                    if($product_code =='')
                    {
                        $remarks_missing_text.='Product Code is missing,';
                    }
                    if($segment =='')
                    {
                        $remarks_missing_text.='Segment is missing,';
                    }
                    if($product_type =='')
                    {
                        $remarks_missing_text.='Product Type is missing,';
                    }
                    if($mrp_price =='')  
                    {
                        $remarks_missing_text.='MRP is not mentioned,';
                    }
                    if($freight_insurance =='')
                    {
                        $remarks_missing_text.='Freight Insurance is not mentioned,';
                    }
                    if($gst =='')
                    {
                        $remarks_missing_text.='GST is not mentioned,';
                    }
                    if($rrp=='')
                    {
                        $remarks_missing_text.='RRP is not mentioned,';
                    }
                    if($dp =='')
                    {
                        $remarks_missing_text.='DP is missing,';
                    }
                    if(strtoupper($availability) !='ACTIVE' && strtoupper($availability) !='INACTIVE')
                    {
                        $remarks_missing_text.='Availability Should be Active or Inactive,';
                    }
                    $missing_files=array(
                                'name' =>$product_code,
                                'name2'=>$product_name,
                                'group'=>$segment,
                                'mrp'=>$mrp_price,
                                'base_price'=>$base_price,
                                'freight_insurance'=>$freight_insurance,
                                'gst'=>$gst,
                                'rrp'=>$rrp,
                                'dp'=>$dp,
                                'upload_id'=>$upload_id,
                                'remarks_text'=> $remarks_missing_text,
                                'created_by'=>$this->session->userdata('user_id'),
                                'created_time'=>date('Y-m-d h:i:s'),
                                'description'=>$description,
                                'target'=>$target,
                                'availability'=>$availability,
                                'sub_category'=>$sub_system,
                                'product_type'=>$product_type
                                );
                        $this->Common_model->insert_data('missing_product_files',$missing_files);
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
                redirect(SITE_URL.'missed_product_list_records/'.icrm_encode($upload_id));
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
                                            <strong>Error!</strong> There\'s a problem occured while uploading Product List!
                                         </div>');
                    redirect(SITE_URL.'product_bulk_upload');
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
                    redirect(SITE_URL.'product_bulk_upload');
                    //echo 'transaction success';
                }
            }
        }
    }
     public function missed_product_list_records($encoded_id)
    {
        $upload_id = icrm_decode($encoded_id);
        //echo $upload_id;exit;
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Product List Misssing Records";
        $data['nestedView']['cur_page'] = 'missed_product_list_records';
        $data['nestedView']['parent_page'] = 'missed_product_list_records';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/custom/manage-user.js"></script>';
        $data['nestedView']['css_includes'] = array();

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Manage Product List';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
         $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product List BulkUpload', 'class' => '', 'url' => SITE_URL . 'product_bulk_upload');
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product List Bulkupload Missing files', 'class' => 'active', 'url' => '');
       

        $data['missing_results'] = $this->Common_model->get_data('missing_product_files',array('upload_id'=>$upload_id));
        $data['upload_id'] = $upload_id;
      //  print_r($data['missing_results']);exit;
        $this->load->view('product/missing_product_list_bulk_upload',$data);
    }
    public function download_missing_product_list_files($encoded_id)
    {  
        $upload_id=@icrm_decode($encoded_id); 
        if($upload_id=='')
        {
            redirect(SITE_URL);exit;
        }
        $missing_results = $this->Common_model->get_data('missing_product_files',array('upload_id'=>$upload_id));
        $note='Active / Inactive';
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Missed Products List');
        $this->excel->getActiveSheet()->setCellValue('A1', 'Product Code');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Product Name');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Segment');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Sub System');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Product Description');
        $this->excel->getActiveSheet()->setCellValue('F1', 'MRP');
        $this->excel->getActiveSheet()->setCellValue('G1', 'Base Price');
        $this->excel->getActiveSheet()->setCellValue('H1', 'Freight Insurance');
        $this->excel->getActiveSheet()->setCellValue('I1', 'GST');
        $this->excel->getActiveSheet()->setCellValue('J1', 'RRP');
        $this->excel->getActiveSheet()->setCellValue('K1', 'DP');
        $this->excel->getActiveSheet()->setCellValue('L1', 'Product Type');
        $this->excel->getActiveSheet()->setCellValue('M1', 'Target');
        $this->excel->getActiveSheet()->setCellValue('N1', 'Availability ('.$note.')');
        $this->excel->getActiveSheet()->setCellValue('O1', 'Remarks');
        $exceldata="";
        if(count($missing_results)>0)
        {
            $arr=array();
            $i=1;
            foreach($missing_results as $product)
            {   
                $exceldata=array();
                $exceldata[] = @$product['name'];
                $exceldata[] = @$product['name2'];
                $exceldata[] = @$product['group'];
                $exceldata[] = @$product['sub_category'];
                $exceldata[] = @$product['description'];
                /*$exceldata[] = @$product['features'];
                $exceldata[] = @$product['scope'];*/
                $exceldata[] = @$product['mrp'];
                $exceldata[] = @$product['base_price'];
                $exceldata[] = @$product['freight_insurance'];
                $exceldata[] = @$product['gst'];
                $exceldata[] = @$product['rrp'];
                $exceldata[] = @$product['dp'];
                $exceldata[] = @$product['product_type'];
                $exceldata[] = @$product['target'];
                $exceldata[] = @$product['availability'];
                $exceldata[] = @$product['remarks_text'];
                $arr[]=$exceldata;
            }
            $this->excel->getActiveSheet()->fromArray($arr, null, 'A2');
        }
        $filename='Missed_Products_List_'.date('Y-m-d h:i:s').'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
        foreach(range('A1','O1') as $columnID) {
        $this->excel->getActiveSheet()->getColumnDimension($columnID)
            ->setAutoSize(true);
        }
        $objWriter->save('php://output');
    }
     public function product_list()
    {     
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "Product  Upload List";
        $data['nestedView']['cur_page'] = 'product_list';
        $data['nestedView']['parent_page'] = 'product_list';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'Product  Upload List';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'Product List', 'class' => '', 'url' =>'');
        $data['pageDetails'] = 'product_list';
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
        $config['base_url'] = SITE_URL.'product_list/'; 
        # Total Records
        $config['total_rows'] = $this->Product_bulk_upload_m->product_list_rows($searchParams);
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
        $data['searchResults'] = $this->Product_bulk_upload_m->product_list_results($searchParams,$config['per_page'], $current_offset);
        $this->load->view('product/product_list', $data);
    }
    public function download_product_list_bulk_upload()
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
        $searchResults = $this->Product_bulk_upload_m->product_upload_list($searchParams);
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
        $xlFile='ProductList_'.$time.'.xls'; 
        header("Content-type: application/x-msdownload"); 
        # replace excelfile.xls with whatever you want the filename to default to
        header("Content-Disposition: attachment; filename=".$xlFile."");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $data;
    }

     public function download_product_bulk_upload_details()
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
        /*$data.='<th>Features </th>';
        $data.='<th>Scope </th>';*/
        $data.='<th>MRP </th>';
        $data.='<th>Base Price </th>';
        /*$data.='<th>ED </th>';
        $data.='<th>VAT </th>';*/
        $data.='<th>Freight Insurance </th>';
        $data.='<th>GST </th>';
        $data.='<th>RRP </th>';
        $data.='<th>DP </th>';
        $data.='<th>Product Type </th>';
        $data.='<th>Target </th>';
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
               /* $data.='<td>'.@$row['features'].'</td>';
                $data.='<td>'.@$row['scope'].'</td>';*/
                $data.='<td>'.@$row['mrp_price'].'</td>';
                $data.='<td>'.@$row['base_price'].'</td>';
                /*$data.='<td>'.@$row['ed'].'</td>';
                $data.='<td>'.@$row['vat'].'</td>';*/
                $data.='<td>'.@$row['freight_insurance'].'</td>';
                $data.='<td>'.@$row['gst'].'</td>';
                $data.='<td>'.@$row['rrp'].'</td>';
                $data.='<td>'.@$row['dp'].'</td>';
                $data.='<td>'.@$row['product_type'].'</td>';
                 if($row['target']==1)
                {
                    $data.='<td>'.'Yes'.'</td>';
                }
                else
                {
                   $data.='<td>'.'No'.'</td>'; 
                }
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
        $xlFile='Product UploadList_'.$time.'.xls'; 
        header("Content-type: application/x-msdownload"); 
        # replace excelfile.xls with whatever you want the filename to default to
        header("Content-Disposition: attachment; filename=".$xlFile."");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $data;
    }
}