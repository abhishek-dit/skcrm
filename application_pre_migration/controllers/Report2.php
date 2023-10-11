<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Report2 extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Report2_model");
		$this->load->helper("report2_helper");
		$this->load->helper("report_helper");
		$this->load->model("Report_model");
	}

	public function margin_analysis_report()
	{	
		/*echo "<pre>";
		print_r($_POST); exit();*/
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Margin Analysis Report";
		$data['nestedView']['cur_page'] = 'margin_analysis_report';
		$data['nestedView']['parent_page'] = 'margin_analysis_report';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/iCRM/customer_contact.js"></script>';

		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Margin Analysis Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Margin Analysis Report','class'=>'active','url'=>'');

		# Authorization check
		$logged_in_role = $this->session->userdata('role_id');
        if(!in_array($logged_in_role,marginAnalysisReportAllowedRoles()))
        {
        	$this->load->view('report/not_authorized', $data);
        	
        }
        else
        {
			$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
			$user_id=$this->session->userdata('user_id');
			$reportees=$this->session->userdata('reportees');
			$user_reportees= $reportees.','.$user_id;
			//print_r($user_reportees);exit;
			$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	
			//$data['role_id']	 = $_SESSION['role_id'];  
			$data['role_id'] = 8;

			$search = $this->input->post('searchMarginData',TRUE);
			if($search!='')
			{
				$searchParams = array(
				'mr_region' => $this->input->post('mr_region',TRUE),
				'mr_user' => $this->input->post('mr_user',TRUE),
				'mr_fromDate' => $this->input->post('mr_fromDate',TRUE),
				'mr_toDate' => $this->input->post('mr_toDate',TRUE),
				'mr_segment' => $this->input->post('mr_segment',TRUE),
				'mr_customer' => $this->input->post('mr_customer',TRUE),
				'mr_customer_id' => $this->input->post('mr_customer_id',TRUE),
				'mr_dealer' => $this->input->post('mr_dealer',TRUE),
				'mr_dealer_id' => $this->input->post('mr_dealer_id',TRUE),
				'mr_product' => $this->input->post('mr_product',TRUE));
			}
			else
			{
				$searchParams = array(
				'mr_region' => '',
				'mr_user' => '',
				'mr_fromDate' => '',
				'mr_toDate' => '',
				'mr_segment' => '',
				'mr_customer' => '',
				'mr_customer_id' => '',
				'mr_dealer' => '',
				'mr_dealer_id' => '',
				'mr_product' => '');
			}


			#additional Data
			$products = array();
			if($searchParams['mr_segment']!='')
			{
				$products = getProductsBySegment($searchParams['mr_segment']);
			}
			$data['products'] = $products;
			$data['searchParams'] = $searchParams;
			$data['product_segments'] = get_user_product_segments();
			$data['table_data'] = get_margin_data($searchParams);
			//$data['customer_list'] = getCustomerListInLoggedInUserLocations(@$searchParams['mr_region']);
			/*echo $this->db->last_query();
			echo '<pre>'; print_r($data['table_data']); exit;*/

			$this->load->view('report/margin_report', $data);
		}
	}

	public function download_margin_analysis_report()
	{
		$search = $this->input->post('downloadProductMarginData',TRUE);
		if($search!='')
		{
			$searchParams = array(
								'mr_region' => $this->input->post('mr_region',TRUE),
								'mr_user' => $this->input->post('mr_user',TRUE),
								'mr_fromDate' => $this->input->post('mr_fromDate',TRUE),
								'mr_toDate' => $this->input->post('mr_toDate',TRUE),
								'mr_segment' => $this->input->post('mr_segment',TRUE),
								'mr_customer' => $this->input->post('mr_customer',TRUE),
								'mr_customer_id' => $this->input->post('mr_customer_id',TRUE),
								'mr_dealer' => $this->input->post('mr_dealer',TRUE),
								'mr_dealer_id' => $this->input->post('mr_dealer_id',TRUE),
								'mr_product' => $this->input->post('mr_product',TRUE)
							);
			//$segment_margin_data = $this->Report2_model->get_segmentMarginData($searchParams);
			$product_results = $this->Report2_model->get_productMarginData($searchParams);
			$product_margin_data = array(); $segment_margin_data = array();
			if($product_results)
			{
				foreach ($product_results as $pdata) {
					if($pdata['order_value']>0)
					{
						$product_margin_data[$pdata['group_id']][] = $pdata;
						if(array_key_exists($pdata['group_id'], $segment_margin_data))
						{
							$order_value = 	$segment_margin_data[$pdata['group_id']]['order_value']+$pdata['order_value'];
							$basic_price = 	$segment_margin_data[$pdata['group_id']]['basic_price']+$pdata['basic_price'];
							$segment_total_qty = 	$segment_margin_data[$pdata['group_id']]['segment_total_qty']+$pdata['product_total_qty'];
							$segment_margin_data[$pdata['group_id']] = array('group_id'=>$pdata['group_id'],'segment'=>$pdata['segment'],
								'order_value'=>$order_value,'basic_price'=>$basic_price,'segment_total_qty'=>$segment_total_qty);
						}
						else
						{
							$segment_margin_data[$pdata['group_id']] = array('group_id'=>$pdata['group_id'],'segment'=>$pdata['segment'],
								'order_value'=>$pdata['order_value'],'basic_price'=>$pdata['basic_price'],'segment_total_qty'=>$pdata['product_total_qty']);
						}
					}
				}
			}
			//echo '<pre>'; print_r($segment_margin_data); exit;
			$this->load->library('excel');
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Margin Analysis Report');
            $this->excel->getActiveSheet()->setCellValue('A1', 'Segment');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Product Code');
            $this->excel->getActiveSheet()->setCellValue('C1', 'Description');
            $this->excel->getActiveSheet()->setCellValue('D1', 'Revenue');
            $this->excel->getActiveSheet()->setCellValue('E1', 'Gross Margin %');
            $this->excel->getActiveSheet()->setCellValue('F1', 'Qty');
            $this->excel->getActiveSheet()->setCellValue('G1', 'ASP');
            $this->excel->getActiveSheet()->setCellValue('H1', 'Unit DP');
            $this->excel->getActiveSheet()->setCellValue('I1', 'Var %');
            $exceldata="";
            if(count($segment_margin_data)>0)
            {
                $arr=array();
                $total_nsp = 0; $total_gross_margin = 0;
                foreach ($segment_margin_data as $row)
                {   
                	if(count(@$product_margin_data[$row['group_id']])>0)
                	{
                		$order_value = $row['order_value'];
                        $basic_price = $row['basic_price'];
                        $nsp = round(get_nsp($order_value));
                        $gross_margin = $nsp - $basic_price;
                        $gross_margin_percentage = round(($gross_margin*100/$nsp),2);

                        $total_nsp += $nsp;
                        $total_gross_margin += $gross_margin;
                		foreach(@$product_margin_data[$row['group_id']] as $product)
                		{
                			$product_order_value = $product['order_value'];
                            $product_basic_price = $product['basic_price'];
                            $product_nsp = round(get_nsp($product_order_value));
                            $product_gross_margin = $product_nsp - $product_basic_price;
                            $product_gross_margin_percentage = round(($product_gross_margin*100/$product_nsp),2);
                            $product_qty = $product['product_total_qty'];
                            $product_asp = round($product_nsp/$product_qty);
                            $product_unit_dp = $product['unit_dp'];
                            if($product_unit_dp>0)
                            	$variance_percentage = round(((($product_order_value/$product_qty)-$product_unit_dp)/$product_unit_dp)*100,2);
                            else $variance_percentage = 0;

		                    $exceldata=array();
		                    $exceldata[] = @$product['segment'];
		                    $exceldata[] = @$product['name'];
		                    $exceldata[] = @$product['description'];
		                    $exceldata[] = @$product_nsp;
		                    $exceldata[] = @$product_gross_margin_percentage;
		                    $exceldata[] = @$product_qty;
		                    $exceldata[] = @$product_asp;
		                    $exceldata[] = @$product_unit_dp;
		                    $exceldata[] = @$variance_percentage;
		                    $arr[]=$exceldata;
	                	}

	                	/*$exceldata=array();
	                    $exceldata[] = '';
	                    $exceldata[] = '';
	                    $exceldata[] = $row['segment'].' Total';
	                    $exceldata[] = @$nsp;
	                    $exceldata[] = @$gross_margin_percentage;
	                    $exceldata[] = '';
	                    $exceldata[] = '';
	                    $exceldata[] = '';
	                    $exceldata[] = '';
	                    $arr[]=$exceldata;*/
                	}

                	
                }
                /*$total_gross_margin_percentage = round($total_gross_margin*100/$total_nsp,2);
            	$exceldata=array();
                $exceldata[] = '';
                $exceldata[] = '';
                $exceldata[] = 'Grand Total';
                $exceldata[] = @$total_nsp;
                $exceldata[] = @$total_gross_margin_percentage;
                $exceldata[] = '';
                $exceldata[] = '';
                $exceldata[] = '';
	            $exceldata[] = '';
                $arr[]=$exceldata;*/
                $this->excel->getActiveSheet()->fromArray($arr, null, 'A2');
            }
            else
            {
                $exceldata[]="No Records Found";
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A2');
                $this->excel->getActiveSheet()->mergeCells('A1:I1');
            }

            $filename='Margin_Analysis_Report_'.date('Y-m-d h:i:s').'.xlsx'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
            foreach(range('A1','I1') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
            }
            $objWriter->save('php://output');
            exit;   
		}
	}

	public function cnote_margin_analysis()
	{	
		/*echo "<pre>";
		print_r($_POST); exit();*/
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "C-Note Margin Analysis Report";
		$data['nestedView']['cur_page'] = 'cnote_margin_analysis';
		$data['nestedView']['parent_page'] = 'cnote_margin_analysis';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();

		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'C-Note Margin Analysis Report';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'C-Note Margin Analysis Report','class'=>'active','url'=>'');

		# Authorization check
		$logged_in_role = $this->session->userdata('role_id');
        if(!in_array($logged_in_role,marginAnalysisReportAllowedRoles()))
        {
        	$this->load->view('report/not_authorized', $data);
        	
        }
        else
        {
			$data['regions'] = $this->Common_model->get_data('location',array('territory_level_id'=>4,'status'=>1));
			$user_id=$this->session->userdata('user_id');
			$reportees=$this->session->userdata('reportees');
			$user_reportees= $reportees.','.$user_id;
			//print_r($user_reportees);exit;
			$data['users'] = $this->Report_model->get_user_reportees($user_reportees);	
			//$data['role_id']	 = $_SESSION['role_id'];  
			$data['role_id'] = 8;

			# Search Functionality
			$psearch=$this->input->post('searchMarginData', TRUE);
			if($psearch!='') {
				$searchParams = array(
				'mr_region' => $this->input->post('mr_region',TRUE),
				'mr_user' => $this->input->post('mr_user',TRUE),
				'mr_fromDate' => $this->input->post('mr_fromDate',TRUE),
				'mr_toDate' => $this->input->post('mr_toDate',TRUE),
				'mr_segment' => $this->input->post('mr_segment',TRUE),
				'mr_product' => $this->input->post('mr_product',TRUE));
			$this->session->set_userdata($searchParams);
			} else {
				
				if($this->uri->segment(2)!='')
				{
				$searchParams=array(
						  'categoryName'=>$this->session->userdata('categoryName'),
								  );
				}
				else {
					$searchParams = array(
											'mr_region' => '',
											'mr_user' => '',
											'mr_fromDate' => '',
											'mr_toDate' => '',
											'mr_segment' => '',
											'mr_product' => '');
					$this->session->unset_userdata(array_keys($searchParams));
				}
				
			}
			$data['searchParams'] = $searchParams;
			
			/* pagination start */
			$config = get_paginationConfig();
			$config['base_url'] = SITE_URL.'cnote_margin_analysis/'; 
			# Total Records
			$config['per_page'] = $this->global_functions->getDefaultPerPageRecords();
			$current_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			$cnote_results = get_cnote_margin_data($searchParams,$current_offset,$config['per_page']);
			$data['cnote_results'] = $cnote_results['resultArray'];
		    $config['total_rows'] = $cnote_results['count'];
			$data['total_rows'] = $config['total_rows'];
	        $this->pagination->initialize($config);
			$data['pagination_links'] = $this->pagination->create_links(); 
			
			if($data['pagination_links']!= '') {
				$data['last']=$this->pagination->cur_page*$config['per_page'];
				if($data['last']>$data['total_rows']){
					$data['last']=$data['total_rows'];
				}
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$config['per_page'])+1).' to '.($data['last']).' of '.$data['total_rows'];
	         } 
			 $data['sn'] = $current_offset + 1;
			/* pagination end */
			
			#additional Data
			$products = array();
			if($searchParams['mr_segment']!='')
			{
				$products = getProductsBySegment($searchParams['mr_segment']);
			}
			$data['products'] = $products;
			$data['searchParams'] = $searchParams;

			$data['product_segments'] = get_user_product_segments();
			
			/*echo $this->db->last_query();
			echo '<pre>'; print_r($data['table_data']); exit;*/

			$this->load->view('report/cnote_margin_report', $data);
		}
	}

	public function download_cnote_margin_report()
	{
		$search = $this->input->post('downloadCNoteMarginData',TRUE);
		if($search!='')
		{
			$searchParams = array(
								'mr_region' => $this->input->post('mr_region',TRUE),
								'mr_user' => $this->input->post('mr_user',TRUE),
								'mr_fromDate' => $this->input->post('mr_fromDate',TRUE),
								'mr_toDate' => $this->input->post('mr_toDate',TRUE),
								'mr_segment' => $this->input->post('mr_segment',TRUE),
								'mr_product' => $this->input->post('mr_product',TRUE)
							);
			//$segment_margin_data = $this->Report2_model->get_segmentMarginData($searchParams);
			$cnote_results = $this->Report2_model->get_regularCNoteMarginData($searchParams);
			
			//echo '<pre>'; print_r($segment_margin_data); exit;
			$this->load->library('excel');
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('CNote Margin Report');
            $this->excel->getActiveSheet()->setCellValue('A1', 'C-Note ID');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Type');
            $this->excel->getActiveSheet()->setCellValue('C1', 'Opportunity ID');
            $this->excel->getActiveSheet()->setCellValue('D1', 'Product Code');
            $this->excel->getActiveSheet()->setCellValue('E1', 'Description');
            $this->excel->getActiveSheet()->setCellValue('F1', 'Qty');
            $this->excel->getActiveSheet()->setCellValue('G1', 'Warranty (Months)');
            $this->excel->getActiveSheet()->setCellValue('H1', 'Advance (%)');
            $this->excel->getActiveSheet()->setCellValue('I1', 'Balance Payment Days');
            $this->excel->getActiveSheet()->setCellValue('J1', 'Dealer Commission (%)');
            $this->excel->getActiveSheet()->setCellValue('K1', 'Dealer');
            $this->excel->getActiveSheet()->setCellValue('L1', 'Highest Approver');
            $this->excel->getActiveSheet()->setCellValue('M1', 'Order Value');
            $this->excel->getActiveSheet()->setCellValue('N1', 'Net Selling Price');
            $this->excel->getActiveSheet()->setCellValue('O1', 'Basic Price');
            $this->excel->getActiveSheet()->setCellValue('P1', 'Discount %');
            $this->excel->getActiveSheet()->setCellValue('Q1', 'Cost of Warranty');
            $this->excel->getActiveSheet()->setCellValue('R1', 'Cost of Finance');
            $this->excel->getActiveSheet()->setCellValue('S1', 'Commission to Dealer in Rs');
            $this->excel->getActiveSheet()->setCellValue('T1', 'Cost of Free Supply');
            $this->excel->getActiveSheet()->setCellValue('U1', 'Gross Margin %');
            $this->excel->getActiveSheet()->setCellValue('V1', 'Gross Margin in Rs');
            $this->excel->getActiveSheet()->setCellValue('W1', 'Net Margin %');
            $this->excel->getActiveSheet()->setCellValue('X1', 'Net Margin in Rs');
            $this->excel->getActiveSheet()->setCellValue('Y1', 'Customer');
            $this->excel->getActiveSheet()->setCellValue('Z1', 'SO Number');
            $this->excel->getActiveSheet()->setCellValue('AA1', 'Region');
            $this->excel->getActiveSheet()->setCellValue('AB1', 'CNote Created By');
            $this->excel->getActiveSheet()->setCellValue('AC1', 'CNote Created User Role');
            $this->excel->getActiveSheet()->setCellValue('AD1', 'Order Status');
            $this->excel->getActiveSheet()->setCellValue('AE1', 'Invoice Cleared By');
            $this->excel->getActiveSheet()->setCellValue('AE1', 'CNote Created Time');
            $exceldata="";
            if(@$cnote_results['count']>0)
            {
                $arr=array();
                $total_nsp = 0; $total_gross_margin = 0;
                foreach ($cnote_results['resultArray'] as $row)
                {   
                	$data = array();
                	$data['order_value'] = $row['order_value'];
                	$data['net_selling_price'] = get_nsp($row['order_value']);
                    $data['dp'] = $row['dp'];
                    $data['cost_of_free_supply'] = $row['free_value'];
                    $data['basic_price'] = $row['basic_price'];
                    $data['total_warranty_in_years'] = $row['warranty_in_years'];
                    $data['exclude_extra_warranty_in_nm'] = ($row['cnote_type']==2)?1:'';
                    $data['free_supply'] = '';
                    $data['balance_payment_days'] = ($row['balance_payment_days']>0)?$row['balance_payment_days']:0;
                    $advance = $row['advance'];
                    if($row['advance_type']==2) // Advance in Rs
                    {
                    	$advance = round($row['advance']*100/$row['order_value'],2);
                    }
                    $data['advance'] = $advance;
                    $data['discount'] = round(($row['mrp_value']-$row['order_value'])*100/$row['mrp_value'],2);
                    $data['dealer_commission'] = $row['dealer_commission'];

                    $ma_data = marginAnalysis($data);
                    //if($row['cnote_id']==1303){ echo '<pre>'; print_r($ma_data); exit;}
                    $cnote_type = ($row['cnote_type']==1)?'Regular':'Purchase Order';
                    $product_details = $row['product_info_str'];
                    $products_arr = array_filter(explode('|',$product_details));
                    $k=0;
                    foreach ($products_arr as $pstr) {
                    
                    	$parr = array_filter(explode('@@',$pstr));
                    	$opportunity_id = @$parr[0];
                    	$product_code = @$parr[1];
                    	$product_description = @$parr[2];
                    	$quantity = @$parr[3];
	                    $exceldata=array();
	                    $exceldata[] = @$row['cnote_id'];
	                    if($k==0)
	                    {
	                    	
	                    	$exceldata[] = @$cnote_type;
	                	}
	                	else 
	                	{
	                    	$exceldata[] = '';
	                	}
	                    $exceldata[] = @$opportunity_id;
	                    $exceldata[] = @$product_code;
	                    $exceldata[] = @$product_description;
	                    $exceldata[] = @$quantity;
	                    $highest_approver = '';
	                    if($row['cnote_type']==1)
	                    {
	                    	$cnote_date =  format_date($row['cnote_created_time']);
	                    	$quote_format_type   =   quote_format_type($cnote_date);
	                    	if($quote_format_type==2)
	                    	{
	                    		$highest_approver = getRegularCNoteHighestApprover($row['cnote_id']);
	                    	}
	                    }
	                    else
	                    {
	                    	$highest_approver = getPurchaseOrderCNoteHighestApprover($row['cnote_id']);
	                    }
	                    if($k==0)
	                    {
	                    	$exceldata[] = @$row['warranty'];
		                    $exceldata[] = @$advance;
		                    $exceldata[] = @$row['balance_payment_days'];
		                    $exceldata[] = (@$row['dealer_commission']>0)?@$row['dealer_commission']:'';
		                    $exceldata[] = @$row['distributor_name'];
		                    $exceldata[] = @$highest_approver;
		                    $exceldata[] = @$row['order_value'];
		                    $exceldata[] = @$ma_data['net_selling_price'];
		                    $exceldata[] = @$ma_data['basic_price'];
		                    $exceldata[] = @$ma_data['discount'];
		                    $exceldata[] = @$ma_data['cost_of_warranty'];
		                    $exceldata[] = @$ma_data['cost_of_finance'];
		                    $exceldata[] = @$ma_data['cost_of_commission'];
		                    $exceldata[] = @$ma_data['cost_of_free_supply'];
		                    $exceldata[] = @$ma_data['gross_margin_percentage'];
		                    $exceldata[] = @$ma_data['gross_margin'];
		                    $exceldata[] = @$ma_data['net_margin_percentage'];
		                    $exceldata[] = @$ma_data['net_margin'];
		                    $exceldata[] = @$row['customer'];
		                    $exceldata[] = @$row['SO_number'];
		                    $exceldata[] = @$row['region'];
		                    $exceldata[] = ($row['cnote_type']==1)?@$row['sales_engineer']:$row['customer'];
		                     $exceldata[] = @$row['cnote_created_user_role'];
		                    $exceldata[] = getOrderStatusLabel(@$row['contract_note_status']);
		                    $exceldata[] = @$row['invoice_cleared_by'];
		                    $exceldata[] = @$row['cnote_created_time'];
	                	}
	                	else 
	                	{
	                		/*for($i=1;$i<=24;$i++)
	                		{
	                			$exceldata[] = '';	
	                		}*/
	                	}
	                    $k++;
	                    $arr[]=$exceldata;
						
                    }
                	
                }
                
                $this->excel->getActiveSheet()->fromArray($arr, null, 'A2');
                $this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setWrapText(true);
            }
            else
            {
                $exceldata[]="No Records Found";
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A2');
                $this->excel->getActiveSheet()->mergeCells('A1:AD1');
            }

            $filename='CNote_Margin_Report_'.date('Y-m-d h:i:s').'.xlsx'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
            foreach(range('A1','AD1') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
            }
            $objWriter->save('php://output');
            exit;   
		}
	}

	public function getCustomersAutoCompleteList()
	{
		$term = @$_REQUEST['term'];
		$region = @$_REQUEST['region'];
		$customers = $this->Report2_model->getCustomersAutoComplete($region,$term);
		$json=array();
		if($customers)
		{
			foreach($customers as $crow)
			{
				$json[]=array(
                        'value'=> $crow['name'],
                        'label'=>$crow['name'],
                        'customer_id' => $crow['customer_id']
                            );
			}
		}
		echo json_encode($json);
	}

	public function getDealersAutoCompleteList()
	{
		$term = @$_REQUEST['term'];
		$region = @$_REQUEST['region'];
		$customers = $this->Report2_model->getDealersAutoComplete($region,$term);
		$json=array();
		if($customers)
		{
			foreach($customers as $crow)
			{
				$json[]=array(
                        'value'=> $crow['distributor_name'],
                        'label'=>$crow['distributor_name'],
                        'dealer_id' => $crow['user_id']
                            );
			}
		}
		echo json_encode($json);
	}

}