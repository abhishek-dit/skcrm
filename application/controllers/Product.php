<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Product extends Base_controller {

	public function __construct() 
	{
        parent::__construct();
		$this->load->model("Product_model");
	}

	public function category()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Product Category";
		$data['nestedView']['cur_page'] = 'productCategory';
		$data['nestedView']['parent_page'] = 'productCategory';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Product Categories';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Product Category','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchCategory', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'categoryName'=>$this->input->post('categoryName', TRUE),
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'categoryName'=>$this->session->userdata('categoryName'),
							  );
			}
			else {
				$searchParams=array(
					  'categoryName'=>'',
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'productCategory/'; 
		# Total Records
	    $config['total_rows'] = $this->Product_model->categoryTotalRows($searchParams);
		
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
	   	$data['categorySearch'] = $this->Product_model->categoryResults($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('product/categoryView', $data);

	}

	public function addCategory()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Product Category";
		$data['nestedView']['cur_page'] = 'productCategory';
		$data['nestedView']['parent_page'] = 'productCategory';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Product Category';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Product Category','class'=>'active','url'=>SITE_URL.'productCategory');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Product Category','class'=>'active','url'=>'');

 		
		$data['companies'] = $this->Common_model->get_dropdown('company', 'company_id', 'name', []);
		$data['competitors'] = $this->Common_model->get_dropdown('competitor', 'competitor_id', 'name', array('status'=>1));
		$data['subCategory'] = $this->Common_model->get_dropdown('sub_category', 'sub_category_id', 'name', array('status'=>1));
		$data['competitorSelected'] = array();
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('product/categoryView', $data);

	}

	public function editCategory($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Product Category";
		$data['nestedView']['cur_page'] = 'productCategory';
		$data['nestedView']['parent_page'] = 'productCategory';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Product Categories';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Product Category','class'=>'active','url'=>SITE_URL.'productCategory');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Product Category','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			
			$value = @icrm_decode($encoded_id);
			$where = array('category_id' => $value);
			$data['categoryEdit'] = $this->Common_model->get_data('product_category', $where);
			$data['competitorSelected'] = array_column($this->Common_model->get_data('product_category_competitor', array('category_id'=>$value),array('competitor_id'), '1'), 'competitor_id');
			$data['subCategorySelected'] = array_column($this->Common_model->get_data('category_sub_category', array('category_id'=>$value),array('sub_category_id'), '1'), 'sub_category_id');
		}
		$this->validateEditUrl(@$data['categoryEdit'],'productCategory');
		$data['companies'] = $this->Common_model->get_dropdown('company', 'company_id', 'name', []);
		$data['competitors'] = $this->Common_model->get_dropdown('competitor', 'competitor_id', 'name', array('status'=>1));
		$data['subCategory'] = $this->Common_model->get_dropdown('sub_category', 'sub_category_id', 'name', array('status'=>1));
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('product/categoryView', $data);
	}

	public function deleteCategory($encoded_id)
	{
		$category_id=@icrm_decode($encoded_id);
		$where = array('category_id' => $category_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('product_category',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Product category has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL.'productCategory');
	}

	public function activateCategory($encoded_id)
	{
		$category_id=@icrm_decode($encoded_id);
		$where = array('category_id' => $category_id);
		$dataArr = array('status' => 1);
		$this->Common_model->update_data('product_category',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Product category has been Activated successfully!
							 </div>');
		redirect(SITE_URL.'productCategory');

	}

	public function categoryAdd()
	{
		if($this->input->post('submitCategory') != "")
		{
			//print_r($_POST);
			$category_id = $this->input->post('category_id');
			/*$competitors = $this->input->post('competitors');
			$subCategories = $this->input->post('subCategory');*/

			$dataArr = array(
					'name' => $this->input->post('name'),
					'company_id' => $this->session->userdata('company'),
					'description' => $this->input->post('description'));
			//$dataArr = $_POST[];
			if($category_id == "")
			{
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				//Insert
				$category_id = $this->Common_model->insert_data('product_category',$dataArr);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Product category has been added successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('category_id' => $category_id);

				//Update
				$this->Common_model->update_data('product_category',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Product category has been updated successfully!
									 </div>');

				//$this->db->delete('product_category_competitor', array('category_id' => $category_id));
				//$this->db->delete('category_sub_category', array('category_id' => $category_id));
				
			}

			/*foreach ($competitors as $key => $value) 
			{
				$competitorDataArray = array('category_id'=>$category_id,'competitor_id'=>$value);
				$this->Common_model->insert_data('product_category_competitor',$competitorDataArray);
			}

			foreach ($subCategories as $key => $value) 
			{
				$subCategoryDataArray = array('category_id'=>$category_id,'sub_category_id'=>$value);
				$this->Common_model->insert_data('category_sub_category',$subCategoryDataArray);
			}*/

			redirect(SITE_URL.'productCategory');
		}
	}

	public function downloadCategory()
	{
		if($this->input->post('downloadCategory')!='') {
			
			$searchParams=array( 'categoryName'=>$this->input->post('categoryName', TRUE),
								'company_id'=>$this->input->post('company', TRUE));
			$categories = $this->Product_model->categoryDetails($searchParams);
			$header = '';
			$data ='';
			$titles = array('S.NO','Product Category','Description','Created Time','Modified By','Modified Time');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($categories)>0)
			{
				
				foreach($categories as $category)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$category['name'].'</td>';
					$data.='<td>'.$category['description'].'</td>';
					$data.='<td>'.DateFormatAM($category['created_time']).'</td>';
					$data.='<td>'.getUserName($category['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($category['modified_time']).'</td>';
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
			$xlFile='category_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function subCategory()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Sub Systems";
		$data['nestedView']['cur_page'] = 'productSubCategory';
		$data['nestedView']['parent_page'] = 'productSubCategory';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Sub Systems';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Sub System','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchSubCategory', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'subCategoryName'=>$this->input->post('subCategoryName', TRUE),
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'subCategoryName'=>$this->session->userdata('subCategoryName'),
							  );
			}
			else {
				$searchParams=array(
					  'subCategoryName'=>'',
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'productSubCategory/'; 
		# Total Records
	    $config['total_rows'] = $this->Product_model->subCategoryTotalRows($searchParams);
		
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
	   	$data['subCategorySearch'] = $this->Product_model->subCategoryResults($searchParams,$config['per_page'], $current_offset);
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$this->load->view('product/subCategoryView', $data);

	}

	public function addSubCategory()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Sub System";
		$data['nestedView']['cur_page'] = 'productSubCategory';
		$data['nestedView']['parent_page'] = 'productSubCategory';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Sub System';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Sub System','class'=>'active','url'=>SITE_URL.'productSubCategory');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add new Sub System','class'=>'active','url'=>'');

		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('product/subCategoryView', $data);

	}

	public function editSubCategory($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Sub Systems";
		$data['nestedView']['cur_page'] = 'productSubCategory';
		$data['nestedView']['parent_page'] = 'productSubCategory';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Sub Systems';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Sub System','class'=>'active','url'=>SITE_URL.'productSubCategory');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Sub System','class'=>'active','url'=>'');
		//echo $encoded_id;echo '<br>'.@icrm_decode($encoded_id); exit;
		if(@icrm_decode($encoded_id)!='')
		{
			$value = @icrm_decode($encoded_id);
			$where = array('sub_category_id' => $value);
			$data['subCategoryEdit'] = $this->Common_model->get_data('sub_category', $where);
		}
		$this->validateEditUrl(@$data['subCategoryEdit'],'productSubCategory');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('product/subCategoryView', $data);
	}

	public function deleteSubCategory($encoded_id)
	{
		$sub_category_id=@icrm_decode($encoded_id);
		$where = array('sub_category_id' => $sub_category_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('sub_category',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Sub system has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL.'productSubCategory');
	}

	public function activateSubCategory($encoded_id)
	{
		$sub_category_id=@icrm_decode($encoded_id);
		$where = array('sub_category_id' => $sub_category_id);
		$dataArr = array('status' => 1);
		$this->Common_model->update_data('sub_category',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Sub system has been Activated successfully!
							 </div>');
		redirect(SITE_URL.'productSubCategory');

	}

	public function subCategoryAdd()
	{
		if($this->input->post('submitSubCategory') != "")
		{
			//print_r($_POST);
			$sub_category_id = $this->input->post('sub_category_id');

			$dataArr = array('name' => $this->input->post('name'));
			//$dataArr = $_POST[];
			if($sub_category_id == "")
			{
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['company_id'] = $this->session->userdata('company');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				//Insert
				$sub_category_id = $this->Common_model->insert_data('sub_category',$dataArr);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Product sub system has been added successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('sub_category_id' => $sub_category_id);

				//Update
				$this->Common_model->update_data('sub_category',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Product sub system has been updated successfully!
									 </div>');
			}
			redirect(SITE_URL.'productSubCategory');
		}
	}

	public function downloadSubCategory()
	{
		if($this->input->post('downloadSubCategory')!='') {
			
			$searchParams=array('subCategoryName'=>$this->input->post('subCategoryName', TRUE));
			$subCategories = $this->Product_model->subCategoryDetails($searchParams);
			//echo $this->db->last_query();exit;
			$header = '';
			$data ='';
			$titles = array('S.NO','Sub System','Created Time','Modified By','Modified Time');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($subCategories)>0)
			{
				
				foreach($subCategories as $subCategory)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$subCategory['name'].'</td>';
					$data.='<td>'.DateFormatAM($subCategory['created_time']).'</td>';
					$data.='<td>'.getUserName($subCategory['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($subCategory['modified_time']).'</td>';
					$data.='</tr>';
					$j++;
				}
			}
			else
			{
				$data.='<tr><td colspan="'.(count($titles)).'" align="center">No Results Found</td></tr>';
			}
			$data.='</tbody>';
			$data.='</table>';
			$time = date("Ymdhis");
			$xlFile='subsystem_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function group()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Segment";
		$data['nestedView']['cur_page'] = 'materialGroup';
		$data['nestedView']['parent_page'] = 'materialGroup';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Segment';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Segment','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchGroup', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'groupName'=>$this->input->post('groupName', TRUE),
					  'category_id'=>$this->input->post('category', TRUE),
					  'groupDescription'=>$this->input->post('groupDescription', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'groupName'=>$this->session->userdata('groupName'),
					  'category_id'=>$this->session->userdata('category_id'),
					  'groupDescription'=>$this->session->userdata('groupDescription')
							  );
			}
			else {
				$searchParams=array(
					  'groupName'=>'',
					  'category_id'=>'',
					  'groupDescription'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'materialGroup/'; 
		# Total Records
	    $config['total_rows'] = $this->Product_model->groupTotalRows($searchParams);
		
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
	   	$data['groupSearch'] = $this->Product_model->groupResults($searchParams,$config['per_page'], $current_offset);
		$data['displayList'] = 1;
		$data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
		$this->load->view('product/groupView', $data);

	}

	public function addGroup()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Segment";
		$data['nestedView']['cur_page'] = 'materialGroup';
		$data['nestedView']['parent_page'] = 'materialGroup';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Material Group';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Material Group','class'=>'active','url'=>SITE_URL.'materialGroup');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Material Group','class'=>'active','url'=>'');

 		
		$data['categories'] =  $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
		$data['competitors'] = $this->Common_model->get_dropdown('competitor', 'competitor_id', 'name', array('status'=>1,'company_id'=>$this->session->userdata('company')));
		$data['competitorSelected']=array();
		$data['company_id'] = $this->session->userdata('company');
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('product/groupView', $data);

	}

	public function editGroup($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Segment";
		$data['nestedView']['cur_page'] = 'materialGroup';
		$data['nestedView']['parent_page'] = 'materialGroup';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Segment';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Segment','class'=>'active','url'=>SITE_URL.'materialGroup');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Segment','class'=>'active','url'=>'');

		if(@icrm_decode($encoded_id)!='')
		{
                    
			
			$value = @icrm_decode($encoded_id);
			$where = array('group_id' => $value);

			$data['competitorSelected'] = array_column($this->Common_model->get_data('product_category_competitor', array('category_id'=>$value),array('competitor_id'), '1'), 'competitor_id');
			$data['competitors'] = $this->Common_model->get_dropdown('competitor', 'competitor_id', 'name', array('status'=>1,'company_id'=>$this->session->userdata('company')));
			$data['groupEdit'] = $this->Common_model->get_data('product_group', $where);
			//$data['companyEdit'] = $this->AdminModel->editCompanyDetails($value);
            $data['products'] =  $this->Common_model->get_dropdown('product', 'product_id', "name", array('group_id'=>$value,'company_id'=>$this->session->userdata('company')), 'concat(name, "( ", description, ")") name');
            $data['target_product'] =  $this->Common_model->get_value("product" ,array('group_id'=>$value,'target'=>1),"product_id" );
            $data['company_id'] = $this->session->userdata('company');
		}
 		$this->validateEditUrl(@$data['groupEdit'],'materialGroup');
		$data['categories'] =  $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
                
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('product/groupView', $data);

	}

	public function deleteGroup($encoded_id)
	{
		$group_id=@icrm_decode($encoded_id);
		$where = array('group_id' => $group_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('product_group',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Material group has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL.'materialGroup');
	}

	public function activateGroup($encoded_id)
	{
		$group_id=@icrm_decode($encoded_id);
		$where = array('group_id' => $group_id);
		$dataArr = array('status' => 1);
		$this->Common_model->update_data('product_group',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Material group has been Activated successfully!
							 </div>');
		redirect(SITE_URL.'materialGroup');
	}

	public function downloadGroup()
	{
		if($this->input->post('downloadGroup')!='') {
			
			$searchParams=array( 'groupName'=>$this->input->post('groupName', TRUE),
									'category_id'=>$this->input->post('category', TRUE),
									'groupDescription' => $this->input->post('groupDescription'), TRUE);
			$groups = $this->Product_model->groupDetails($searchParams);
			$header = '';
			$data ='';
			$titles = array('S.NO','Product Category','Competitors', 'Segment','Description','Created Time','Modified By','Modified Time');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($groups)>0)
			{
				
				foreach($groups as $group)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$group['CategoryName'].'</td>';
					$data.='<td>'.$group['competitors'].'</td>';
					$data.='<td>'.$group['name'].'</td>';
					$data.='<td>'.$group['description'].'</td>';
					$data.='<td>'.DateFormatAM($group['created_time']).'</td>';
					$data.='<td>'.getUserName($group['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($group['modified_time']).'</td>';
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
			$xlFile='group_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}
	
	public function groupAdd()
	{
		if($this->input->post('submitGroup') != "")
		{
			//print_r($_POST); exit();
			$group_id = $this->input->post('group_id');
			$competitors=$this->input->post('competitors');
			$dataArr = array(
					'name' => $this->input->post('name'),
					'category_id' => $this->input->post('category'),
					'description' => $this->input->post('description'));
			//$dataArr = $_POST[];
			if($group_id == "")
			{
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				//Insert
				$group_id1=$this->Common_model->insert_data('product_group',$dataArr);
				foreach ($competitors as $key => $value) 
				{
					$competitorDataArray = array('category_id'=>$group_id1,'competitor_id'=>$value);
					$this->Common_model->insert_data('product_category_competitor',$competitorDataArray);
				}
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Material group has been added successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('group_id' => $group_id);

				//Update
				$this->Common_model->update_data('product_group',$dataArr, $where);
                                if($this->input->post('product_id')!=0){
                                   $product_id=$this->input->post('product_id');
                                    $this->Common_model->update_data('product',array('target'=>2), array('group_id'=>$group_id, 'target' => 1));
                                    $this->Common_model->update_data('product',array('target'=>1), array('product_id'=>$product_id));
                                    
                                }
                $competitorwhere=array('category_id'=>$group_id);
                $this->db->delete('product_category_competitor', $competitorwhere);
                foreach ($competitors as $key => $value) 
				{
					$competitorDataArray = array('category_id'=>$group_id,'competitor_id'=>$value);
					$this->Common_model->insert_data('product_category_competitor',$competitorDataArray);
				}
                                
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Material group has been updated successfully!
									 </div>');
			}
			redirect(SITE_URL.'materialGroup');
		}
	}

	public function competitor()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Competitor";
		$data['nestedView']['cur_page'] = 'competitor';
		$data['nestedView']['parent_page'] = 'competitor';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Competitor';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Competitor','class'=>'active','url'=>'');
		
		# Search Functionality
		$psearch=$this->input->post('searchCompetitor', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'competitorName'=>$this->input->post('competitorName', TRUE)
					 		  );
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'competitorName'=>$this->session->userdata('competitorName')
							  );
			}
			else {
				$searchParams=array(
					  'competitorName'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'competitor/'; 
		# Total Records
	    $config['total_rows'] = $this->Product_model->competitorTotalRows($searchParams);
		
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
	   	$data['competitorSearch'] = $this->Product_model->competitorResults($searchParams,$config['per_page'], $current_offset);
		$data['displayList'] = 1;
		$this->load->view('product/competitorView', $data);

	}

	public function addCompetitor()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Competitor";
		$data['nestedView']['cur_page'] = 'competitor';
		$data['nestedView']['parent_page'] = 'competitor';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Competitor';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Competitor','class'=>'active','url'=>SITE_URL.'competitor');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Competitor','class'=>'active','url'=>'');

		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('product/competitorView', $data);


	}

	public function editCompetitor($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Competitor";
		$data['nestedView']['cur_page'] = 'competitor';
		$data['nestedView']['parent_page'] = 'competitor';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Competitor';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Competitor','class'=>'active','url'=>SITE_URL.'competitor');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Competitor','class'=>'active','url'=>'');

		if(@icrm_decode($encoded_id)!='')
		{
			
			$value = @icrm_decode($encoded_id);
			$where = array('competitor_id' => $value);
			$data['competitorEdit'] = $this->Common_model->get_data('competitor', $where);
			//$data['companyEdit'] = $this->AdminModel->editCompanyDetails($value);
		}
		$this->validateEditUrl(@$data['competitorEdit'],'competitor');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('product/competitorView', $data);
	}

	public function deleteCompetitor($encoded_id)
	{
		$competitor_id=@icrm_decode($encoded_id);
		$where = array('competitor_id' => $competitor_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('competitor',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Competitor has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL.'competitor');
	}

	public function activateCompetitor($encoded_id)
	{
		$competitor_id=@icrm_decode($encoded_id);
		$where = array('competitor_id' => $competitor_id);
		$dataArr = array('status' => 1);
		$this->Common_model->update_data('competitor',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Competitor has been Activated successfully!
							 </div>');
		redirect(SITE_URL.'competitor');
	}

	public function competitorAdd()
	{
		if($this->input->post('submitCompetitor') != "")
		{
			//print_r($_POST);
			$competitor_id = $this->input->post('competitor_id');
			$dataArr = array(
					'name' 		=> $this->input->post('name'),
					'rating' 	=> $this->input->post('rating'),
					'company_id'=> $this->session->userdata('company'));
			//$dataArr = $_POST[];
			if($competitor_id == "")
			{
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				//Insert
				$this->Common_model->insert_data('competitor',$dataArr);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Competitor has been added successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('competitor_id' => $competitor_id);

				//Update
				$this->Common_model->update_data('competitor',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Competitor has been updated successfully!
									 </div>');
			}
			redirect(SITE_URL.'competitor');
		}
	}

	public function downloadCompetitor()
	{
		if($this->input->post('downloadCompetitor')!='') {
			
			$searchParams=array( 'competitorName'=>$this->input->post('competitorName', TRUE));
			$competitors = $this->Product_model->competitorDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Competitor','Rating','Created Time', 'Modified By','Modified Time');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($competitors)>0)
			{
				
				foreach($competitors as $competitor)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$competitor['name'].'</td>';
					$data.='<td>'.$competitor['rating'].'</td>';
					$data.='<td>'.DateFormatAM($competitor['created_time']).'</td>';
					$data.='<td>'.getUserName($competitor['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($competitor['modified_time']).'</td>';
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
			$xlFile='competitor_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	/*Phase2 update: Prasad 09-08-2017*/
	public function product()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Product";
		$data['nestedView']['cur_page'] = 'product';
		$data['nestedView']['parent_page'] = 'product';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Product';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Product','class'=>'active','url'=>'');		

		# Search Functionality
		$psearch=$this->input->post('searchProduct', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'productName'=>$this->input->post('productName', TRUE),
					  'category_id'=>$this->input->post('category', TRUE),
					  'group_id'=>$this->input->post('group', TRUE),
					  'product_type_id'=>$this->input->post('product_types_id', TRUE),
					  'productDescription'=>$this->input->post('productDescription', TRUE)
					  		);
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'productName'=>$this->session->userdata('productName'),
					  'category_id'=>$this->session->userdata('category_id'),
					  'group_id'=>$this->session->userdata('group_id'),
					  'product_type_id'=>$this->session->userdata('product_type_id'),
					  'productDescription'=>$this->session->userdata('productDescription')
							  );
			}
			else {
				$searchParams=array(
					  'productName'=>'',
					  'category_id'=>'',
					  'group_id'=>'',
					  'product_type_id'=>'',
					  'productDescription'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		//print_r($data['searchParams']);die();
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'product/'; 
		# Total Records
	    $config['total_rows'] = $this->Product_model->productTotalRows($searchParams);
		
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
	   	$data['productSearch'] = $this->Product_model->productResults($searchParams,$config['per_page'], $current_offset);
		$data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
	   	//$data['groups'] =  array(''=>'Select Segment') + $this->Common_model->get_dropdown('product_group', 'group_id', 'name', array('category_id'=>@$searchParams['category_id']), 'name');
	   	$data['groups'] = array(''=>'Select Segment') + $this->Product_model->GetDemoProductGroup($searchParams);
	   	$data['product_type']=array(''=>'Select Product Type') + $this->Common_model->get_dropdown('product_type','product_type_id','name');
		
		$data['displayList'] = 1;
		# Load page with all shop details
		$this->load->view('product/productView', $data);
	}
	
	/*Phase2 update: Prasad 9-08-2017 */
	public function addProduct()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Product";
		$data['nestedView']['cur_page'] = 'product';
		$data['nestedView']['parent_page'] = 'product';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Product';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Product','class'=>'active','url'=>SITE_URL.'product');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Product','class'=>'active','url'=>'');

		$data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
		$data['product_type']=array(''=>'Select Product Type') + $this->Common_model->get_dropdown('product_type','product_type_id','name');
		$data['groups'] = array(''=>'Select Segment');
		$data['sub_system']=array(''=>'Select Sub System') + $this->Common_model->get_dropdown('sub_category','sub_category_id','name',array('status'=>1,'company_id'=>$this->session->userdata('company')));
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('product/productView', $data);
	}
	
	/*Phase2 update: Prasad 09-08-2017*/
	public function editProduct($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Product";
		$data['nestedView']['cur_page'] = 'product';
		$data['nestedView']['parent_page'] = 'product';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.icheck/icheck.min.js"></script>';
		$data['nestedView']['css_includes'] = array();
		$data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="'.assets_url().'js/jquery.icheck/skins/square/blue.css" />';
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Product';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Product','class'=>'active','url'=>SITE_URL.'product');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Product','class'=>'active','url'=>'');

		if(@icrm_decode($encoded_id)!='')
		{
			
			$value = @icrm_decode($encoded_id);
			$where = array('product_id' => $value);
			$data['productEdit'] = $this->Common_model->get_data('product', $where);
			$data['productEdit'][0]['category_id'] = $this->Common_model->get_value('product_group', array('group_id'=>$data['productEdit'][0]['group_id']), 'category_id');
			//$data['companyEdit'] = $this->AdminModel->editCompanyDetails($value);
		}
		$this->validateEditUrl(@$data['productEdit'],'product');
		$data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
		$data['groups'] = array(''=>'Select Group') + $this->Common_model->get_dropdown('product_group', 'group_id', 'name', ['category_id'=>@$data['productEdit'][0]['category_id']], 'name');
		$data['product_type']=array(''=>'Select Product Type') + $this->Common_model->get_dropdown('product_type','product_type_id','name');
		$data['sub_system']=array(''=>'Select Sub System') + $this->Common_model->get_dropdown('sub_category','sub_category_id','name',array('status'=>1,'company_id'=>$this->session->userdata('company')));
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('product/productView', $data);
	}

	public function deleteProduct($encoded_id)
	{
		$product_id=@icrm_decode($encoded_id);
		$where = array('product_id' => $product_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('product',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Product has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL.'product');
	}

	public function activateProduct($encoded_id)
	{
		$product_id=@icrm_decode($encoded_id);
		$where = array('product_id' => $product_id);
		$dataArr = array('status' => 1);
		$this->Common_model->update_data('product',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Product has been Activated successfully!
							 </div>');
		redirect(SITE_URL.'product');
	}

	/*Phase2 update: Prasad 09-08-2017*/
	public function productAdd()
	{
		//print_r($_POST);exit;
		//$name = $this->input->post('name');
		//echo base64_decode($name); exit;
		/*if($this->input->post('submitProduct') != "")
		{*/
		  //	print_r($_POST);exit;

			$product_id = $this->input->post('product_id');
			$dataArr = array(
					'name' => base64_decode($this->input->post('name')),
					'name2'=> base64_decode($this->input->post('product_name')),
					'group_id' => $this->input->post('group'),
					'description' => base64_decode($this->input->post('description')),
					'mrp' => base64_decode($this->input->post('mrp')),
					'base_price' => base64_decode($this->input->post('basePrice')),
					'sub_category_id'=>$this->input->post('sub_category'),
					/*'ed' => $this->input->post('ed'),
					'vat' => $this->input->post('vat'),*/
					'gst' => base64_decode($this->input->post('gst')),
					'freight_insurance' => base64_decode($this->input->post('freightInsurance')),
					'rrp' => base64_decode($this->input->post('rrp')),
					'dp' => base64_decode($this->input->post('dp')),
					//  Added on 18-06-2021 for adding new field for warranty based on product -->
					'warranty' => base64_decode($this->input->post('warranty')),
					// Added on 18-06-2021 for adding new field for warranty based on product end -->
					'features' => base64_decode($this->input->post('features')),
					'scope' => base64_decode($this->input->post('scope')),
					'product_type_id'=>$this->input->post('product_type_id'),
					'availability'	 =>$this->input->post('availability'),
					'target' => $this->input->post('target'),
					'company_id' => $this->session->userdata('company'));
            //Added by Naveen 22-10-2016 15:00 
			if($dataArr['target'] == '') $dataArr['target'] = 2;
			if($dataArr['base_price'] == '') $dataArr['base_price'] = 0;


			//$dataArr = $_POST[];
			if($product_id == "")
			{
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				//Insert Updated By Naveen on 24-10-2016 11:30
				$product_id = $this->Common_model->insert_data('product',$dataArr);
				if($product_id != '')
				{
					$category_id = ($this->input->post('category') == '')? 0 : $this->input->post('category');
					$users = $this->Product_model->getUsersByProductCategory($category_id);
					if($users)
					{
						foreach($users as $user)
						{
							$upDataArr = array('user_id' => $user['user_id'], 'product_id' => $product_id, 'status' => 1);
							$this->Common_model->insert_data('user_product',$upDataArr);
						}
					}
					else
					{
						$userInfo = $this->Product_model->getNewPCUsers();
						$userID = $this->session->userdata('user_id');
						$upDataArr = array('user_id' => $userID, 'product_id' => $product_id, 'status' => 1);
						$this->Common_model->insert_data('user_product',$upDataArr);
						// foreach($userInfo as $user)
						// {
						// 	$upDataArr = array('user_id' => $user['user_id'], 'product_id' => $product_id, 'status' => 1);
						// 	$this->Common_model->insert_data('user_product',$upDataArr);
						// }
					}

				}
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Product has been added successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('product_id' => $product_id);

				//Update
				$this->Common_model->update_data('product',$dataArr, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Product has been updated successfully!
									 </div>');
			}
			redirect(SITE_URL.'product');
		//}
	}

	/*Phase2 update: Prasad 09-08-2017*/
	public function downloadProduct()
	{
		if($this->input->post('downloadProduct')!='') {
			
			$searchParams=array('productName'=>$this->input->post('productName', TRUE),
									'category_id'=>$this->input->post('category', TRUE),
					  				'group_id'=>$this->input->post('group', TRUE),
					  				'product_type_id'=>$this->input->post('product_types_id',TRUE),
					  				'productDescription' => $this->input->post('productDescription', TRUE));
			$products = $this->Product_model->productDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Product Category','Segment','Segment Description','Product', 'Product Description','Product Type','Specifications', 'Scope', 'MRP','Base Price', 'RRP', 'DP','Created Time','Modified By','Modified Time');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			 $j=1;
			if(count($products)>0)
			{
				
				foreach($products as $product)
				{
					$data.='<tr>';
					$data.='<td align="center">'.$j.'</td>';
					$data.='<td>'.$product['CategoryName'].'</td>';
					$data.='<td>'.$product['GroupName'].'</td>';
					$data.='<td>'.$product['groupDescription'].'</td>';
					$data.='<td>'.$product['name'].'</td>';
					$data.='<td>'.$product['description'].'</td>';
					$data.='<td>'.$product['pt_name'].'</td>';
					$data.='<td>'.$product['features'].'</td>';
					$data.='<td>'.$product['scope'].'</td>';
					$data.='<td>'.$product['mrp'].'</td>';
					$data.='<td>'.$product['base_price'].'</td>';
					$data.='<td>'.$product['rrp'].'</td>';
					$data.='<td>'.$product['dp'].'</td>';
					$data.='<td>'.DateFormatAM($product['created_time']).'</td>';
					$data.='<td>'.getUserName($product['modified_by']).'</td>';
					$data.='<td>'.DateFormatAM($product['modified_time']).'</td>';
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
			$xlFile='products_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function demoProduct()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo Product";
		$data['nestedView']['cur_page'] = 'demoProduct';
		$data['nestedView']['parent_page'] = 'demoProduct';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo Product';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Demo Product','class'=>'active','url'=>'');		

		# Search Functionality
		$psearch=$this->input->post('searchDemoProduct', TRUE);
		if($psearch!='') {
		$searchParams=array(
					  'location'=>$this->input->post('location', TRUE),
					  'serialNumber'=>$this->input->post('serialNumber', TRUE),
					  'category_id'=>$this->input->post('category', TRUE),
					  'group_id'=>$this->input->post('group', TRUE),
					  'product_id'=>$this->input->post('product', TRUE),
					  'region_id'=>$this->input->post('region', TRUE)
					  		);
		$this->session->set_userdata($searchParams);
		} else {
			
			if($this->uri->segment(2)!='')
			{
			$searchParams=array(
					  'location'=>$this->session->userdata('location'),
					  'serialNumber'=>$this->session->userdata('serialNumber'),
					  'category_id'=>$this->session->userdata('category_id'),
					  'group_id'=>$this->session->userdata('group_id'),
					  'product_id'=>$this->session->userdata('product_id'),
					  'region_id'=>$this->session->userdata('region_id')
							  );
			}
			else {
				$searchParams=array(
					  'location'=>'',
					  'serialNumber'=>'',
					  'category_id'=>'',
					  'group_id'=>'',
					  'product_id'=>'',
					  'region_id'=>''
					  			  );
				$this->session->unset_userdata(array_keys($searchParams));
			}
			
		}
		$data['searchParams'] = $searchParams;
		//print_r($data['searchParams']);die();
		
		/* pagination start */
		$config = get_paginationConfig();
		$config['base_url'] = SITE_URL.'demoProduct/'; 
		# Total Records
	    $config['total_rows'] = $this->Product_model->demoProductTotalRows($searchParams);
		
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
	   	$data['demoProductSearch'] = $this->Product_model->demoProductResults($searchParams,$config['per_page'], $current_offset);
		$data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
	   //	$data['groups'] =  array(''=>'Select Segment') + $this->Common_model->get_dropdown('product_group', 'group_id', 'name', array('category_id'=>@$searchParams['category_id']), 'name');
		//modified by prasad
	   	$data['groups']   = array(''=>'Select Segment') + $this->Product_model->GetDemoProductGroup($searchParams);
	   	$searchArray=[];
	   	if($searchParams['group_id']!='')
	   	{
	   		$searchArray['group_id']=$searchParams['group_id'];
	   	}
	   	$searchArray['company_id']=$this->session->userdata('company');
	   	$data['products'] =  array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name',$searchArray, 'concat(name, "( ", description, ")") name');
	   	$data['regions'] =  array(''=>'Select Region') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('territory_level_id'=>4));
		$data['displayList'] = 1;
		# Load page with all shop details
		$this->load->view('product/demoProductView', $data);
	}

	public function addDemoProduct()
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo Product";
		$data['nestedView']['cur_page'] = 'demoProduct';
		$data['nestedView']['parent_page'] = 'demoProduct';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo Product';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Demo Product','class'=>'active','url'=>SITE_URL.'demoProduct');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Add Demo Product','class'=>'active','url'=>'');

		$data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
		$data['groups'] = array(''=>'Select Segment');
		$data['products'] = array(''=>'Select Product');
		$data['regions'] = array('Select Region') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('territory_level_id'=>4));
		$data['branch'] = array('Select Branch');
		$data['city'] = array('Select City');
		$data['flg'] = 1;
		$data['val'] = 0;
		# Load page with all shop details
		$this->load->view('product/demoProductView', $data);
	}

	public function editDemoProduct($encoded_id)
	{
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Demo Product";
		$data['nestedView']['cur_page'] = 'demoProduct';
		$data['nestedView']['parent_page'] = 'demoProduct';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Demo Product';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Demo Product','class'=>'active','url'=>SITE_URL.'demoProduct');
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Edit Demo Product','class'=>'active','url'=>'');

		if(@icrm_decode($encoded_id)!='')
		{
			
			$value = @icrm_decode($encoded_id);
			$where = array('demo_product_id' => $value);
			$data['demoProductEdit'] = $this->Common_model->get_data('demo_product_details', $where);
			$data['demoProductEdit'][0]['region_id'] = $this->Common_model->get_value('branch', array('branch_id'=>$data['demoProductEdit'][0]['branch_id']), 'region_id');
			$data['demoProductEdit'][0]['product_id'] = $this->Common_model->get_value('demo_product', array('demo_product_id'=>$value), 'product_id');
			$data['demoProductEdit'][0]['group_id'] = $this->Common_model->get_value('product', array('product_id'=>$data['demoProductEdit'][0]['product_id']), 'group_id');
			$data['demoProductEdit'][0]['category_id'] = $this->Common_model->get_value('product_group', array('group_id'=>$data['demoProductEdit'][0]['group_id']), 'category_id');
			$data['demoProductEdit'][0]['cityName'] = getLocationName($data['demoProductEdit'][0]['city_id']);
			//$data['companyEdit'] = $this->AdminModel->editCompanyDetails($value);
		}
		$this->validateEditUrl(@$data['demoProductEdit'],'demoProduct');
		$data['regions'] = array('Select Region') + $this->Common_model->get_dropdown('location', 'location_id', 'location', array('territory_level_id'=>4));
		$data['branch'] = array('Select Branch') + $this->Common_model->get_dropdown('branch', 'branch_id', 'name', array('region_id'=>$data['demoProductEdit'][0]['region_id'],'company_id'=>$this->session->userdata('company')));
		
		$data['categories'] =  array(''=>'Select Category') + $this->Common_model->get_dropdown('product_category', 'category_id', 'name', array('company_id'=>$this->session->userdata('company')));
		//$data['groups'] = array(''=>'Select Segment') + $this->Common_model->get_dropdown('product_group', 'group_id', 'name', ['category_id'=>@$data['demoProductEdit'][0]['category_id']], 'name');
		$searchParams['category_id']=$data['demoProductEdit'][0]['category_id'];
		$data['groups']   = array(''=>'Select Segment') + $this->Product_model->GetDemoProductGroup($searchParams);
		$searchArray=[];
	   	if($data['demoProductEdit'][0]['group_id']!='')
	   	{
	   		$searchArray['group_id']=$data['demoProductEdit'][0]['group_id'];
	   	}
	   	$searchArray['company_id']=$this->session->userdata('company');
		$data['products'] = array(''=>'Select Product') + $this->Common_model->get_dropdown('product', 'product_id', 'name', $searchArray, 'concat(name, "( ", description, ")") name');
		$data['city'] = array('Select City');
		$data['flg'] = 1;
		$data['val'] = 1;
		# Load page with all shop details
		$this->load->view('product/demoProductView', $data);
	}

	public function deleteDemoProduct($encoded_id)
	{
		$demo_product_id=@icrm_decode($encoded_id);
		$where = array('demo_product_id' => $demo_product_id);
		$dataArr = array('status' => 2);
		$this->Common_model->update_data('demo_product_details',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Demo product has been De-Activated successfully!
							 </div>');
		redirect(SITE_URL.'demoProduct');
	}

	public function activateDemoProduct($encoded_id)
	{
		$demo_product_id=@icrm_decode($encoded_id);
		$where = array('demo_product_id' => $demo_product_id);
		$dataArr = array('status' => 1);
		$this->Common_model->update_data('demo_product_details',$dataArr, $where);
		
		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Demo product has been Activated successfully!
							 </div>');
		redirect(SITE_URL.'demoProduct');
	}

	public function demoProductAdd()
	{
		if($this->input->post('submitDemoProduct') != "")
		{
			//print_r($_POST);
			$demo_product_id = $this->input->post('demo_product_id');
			$dataArr = array('location' => $this->input->post('location'),
								'serial_number'=>$this->input->post('serialNumber'),
								'branch_id' => $this->input->post('branch_id'),
								'city_id' => $this->input->post('city_id'));
			//$dataArr = $_POST[];
			if($demo_product_id == "")
			{
				$dataArr['created_by'] = $this->session->userdata('user_id');
				$dataArr['created_time'] = date('Y-m-d H:i:s');
				//Insert
				$demo_product_id = $this->Common_model->insert_data('demo_product_details',$dataArr);
				
				$dataArr1 = array('product_id' => $this->input->post('product'),
								'demo_product_id' => $demo_product_id);
				$this->Common_model->insert_data('demo_product',$dataArr1);

				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Demo product has been added successfully!
									 </div>');
			}
			else
			{	
				$dataArr['modified_by'] = $this->session->userdata('user_id');
				$dataArr['modified_time'] = date('Y-m-d H:i:s');
				$where = array('demo_product_id' => $demo_product_id);

				//Update
				$this->Common_model->update_data('demo_product_details',$dataArr, $where);

				$dataArr1 = array('product_id' => $this->input->post('product'),
								'demo_product_id' => $demo_product_id);
				$this->Common_model->update_data('demo_product',$dataArr1, $where);
				
				$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
										<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										<div class="icon"><i class="fa fa-check"></i></div>
										<strong>Success!</strong> Demo product has been updated successfully!
									 </div>');
			}
			redirect(SITE_URL.'demoProduct');
		}
	}

	public function downloadDemoProduct()
	{
		if($this->input->post('downloadDemoProduct')!='') {
			
			$searchParams=array('location'=>$this->input->post('location', TRUE),
									'serialNumber'=>$this->input->post('serialNumber', TRUE),
									'category_id'=>$this->input->post('category', TRUE),
					  				'group_id'=>$this->input->post('group', TRUE),
					  				'product_id'=>$this->input->post('product', TRUE),
					  				'region_id'=>$this->input->post('region', TRUE));
			$demoProducts = $this->Product_model->demoProductDetails($searchParams);
			
			$header = '';
			$data ='';
			$titles = array('S.NO','Product Category','Segment', 'Segment Description', 'Product', 'Product Description', 
					'RRP (Rs)','Serial Number', 'City', 'Location Name','Region', 'Sales Branch Office', 'Responsible Person (RBH of Region)', 'Created Time','Modified By','Modified Time');
			$data = '<table border="1">';
			$data.='<thead>';
			$data.='<tr>';
			foreach ( $titles as $title)
			{
				$data.= '<th>'.$title.'</th>';
			}
			$data.='</tr>';
			$data.='</thead>';
			$data.='<tbody>';
			$j=1;
			if(count($demoProducts)>0)
			{
				
				foreach($demoProducts as $demoProduct)
				{
					$data.='<tr>';
					$data.='<td valign="top" align="center">'.$j.'</td>';
					$data.='<td valign="top">'.$demoProduct['CategoryName'].'</td>';
					$data.='<td valign="top">'.$demoProduct['GroupName'].'</td>';
					$data.='<td valign="top">'.$demoProduct['GroupDescription'].'</td>';
					$data.='<td valign="top">'.$demoProduct['ProductName'].'</td>';
					$data.='<td valign="top">'.$demoProduct['ProductDescription'].'</td>';
					$data.='<td valign="top">'.$demoProduct['rrp'].'</td>';
					$data.='<td valign="top">'.$demoProduct['serial_number'].'</td>';
					$data.='<td valign="top">'.$demoProduct['city'].'</td>';
					$data.='<td valign="top">'.$demoProduct['location'].'</td>';
					$data.='<td valign="top">'.$demoProduct['region'].'</td>';
					$data.='<td valign="top">'.$demoProduct['branch'].'</td>';
					$data.='<td valign="top">'.getRBHforRegion($demoProduct['region_id']).'</td>';
					$data.='<td valign="top">'.DateFormatAM($demoProduct['created_time']).'</td>';
					$data.='<td valign="top">'.getUserName($demoProduct['modified_by']).'</td>';
					$data.='<td valign="top">'.DateFormatAM($demoProduct['modified_time']).'</td>';
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
			$xlFile='demoProducts_'.$time.'.xls'; 
			header("Content-type: application/x-msdownload"); 
			# replace excelfile.xls with whatever you want the filename to default to
			header("Content-Disposition: attachment; filename=".$xlFile."");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $data;
			
		}
	}

	public function getProductGroup()
	{
		$category_id=$this->input->post('category_id');
        //$this->Common_model->get_dropdown('product_group', 'group_id', 'name', array('category_id'=>$category_id));
	    $results = $this->Product_model->getLoggedInUserProductGroupsDropdown($category_id);
	    //$results = $this->Product_model->getProductGroupsDropdown($category_id);
       // Phase2 update: Mahesh 24-12-2017 filtered by assinged products only
        $groups='<option value="">Select Group</option>';
        foreach ($results as $key=>$value) 
        {
            $groups.='<option value="' . $key . '">' . $value . '</option>';
        }
       
       echo $groups;
	}

	public function getProductGroup_for_products()
	{
		$category_id=$this->input->post('category_id');
        //$this->Common_model->get_dropdown('product_group', 'group_id', 'name', array('category_id'=>$category_id));
	  //  $results = $this->Product_model->getLoggedInUserProductGroupsDropdown($category_id);
	     $results = $this->Product_model->getProductGroupsDropdown($category_id);
       // Phase2 update: Mahesh 24-12-2017 filtered by assinged products only
        $groups='<option value="">Select Segment</option>';
        foreach ($results as $key=>$value) 
        {
            $groups.='<option value="' . $key . '">' . $value . '</option>';
        }
       
       echo $groups;
	}

	public function getProduct()
	{
		$group_id=$this->input->post('group_id');
        //$this->Common_model->get_dropdown('product_group', 'group_id', 'name', array('category_id'=>$category_id));
        $results = $this->Product_model->getLoggedInUserProductsDropdown($group_id);// Phase2 update: Mahesh 24-12-2017 filtered by assinged products only
        //echo $this->db->last_query(); exit;	
        $products='<option value="">Select Product</option>';
        foreach ($results as $key=>$value) 
        {
            $products.='<option value="' . $key . '">' . $value . '</option>';
        }
       
       echo $products;
	}

	public function getDemoProduct()
	{
		$product_id=@$_REQUEST['product_id'];
        $results = $this->Product_model->getDemoProduct($product_id);
        $demoProducts='<option value="">Select Demo Product</option>';
        foreach ($results as $key=>$value) 
        {
            $demoProducts.='<option value="' . $key . '">' . $value . '</option>';
        }
       
       echo $demoProducts;
	}
	
	public function getBranch()
	{
		$region_id=$this->input->post('region_id');
        //$this->Common_model->get_dropdown('product_group', 'group_id', 'name', array('category_id'=>$category_id));
        $results = $this->Common_model->get_dropdown('branch', 'branch_id', 'name', array('region_id'=>$region_id,'company_id'=>$this->session->userdata('company')));
        $branch='<option value="">Select Branch</option>';
        foreach ($results as $key=>$value) 
        {
            $branch.='<option value="' . $key . '">' . $value . '</option>';
        }
       
       echo $branch;
	}

	public function checkCategoryAvailability()
	{
		$name = @$_REQUEST['name'];
		$category_id = @$_REQUEST['category_id'];
		$data = array('name'=>$name,'category_id'=>$category_id);
		$result = $this->Product_model->checkCategoryAvailability($data);
		echo $result;
	}

	public function checksubcategoryAvailability()
	{
		$name = @$_REQUEST['name'];
		$sub_category_id = @$_REQUEST['category_id'];
		$data = array('name'=>$name,'sub_category_id'=>$sub_category_id);
		$result = $this->Product_model->checksubCategoryAvailability($data);
		echo $result;
	}

	public function checkGroupAvailability()
	{
		$name = @$_REQUEST['name'];
		$group_id = @$_REQUEST['group_id'];
		$data = array('name'=>$name,'group_id'=>$group_id);
		$result = $this->Product_model->checkGroupAvailability($data);
		echo $result;
	}

	public function checkCompetitorAvailability()
	{
		$name = @$_REQUEST['name'];
		$competitor_id = @$_REQUEST['competitor_id'];
		$data = array('name'=>$name,'competitor_id'=>$competitor_id);
		$result = $this->Product_model->checkCompetitorAvailability($data);
		echo $result;
	}

	public function checkProductAvailability()
	{
		$name = @$_REQUEST['name'];
		$product_id = @$_REQUEST['product_id'];
		$data = array('name'=>$name,'product_id'=>$product_id);
		$result = $this->Product_model->checkProductAvailability($data);
		echo $result;
	}

	public function checkDemoProductSerialNumberAvailability()
	{
		$serial_number = @$_REQUEST['serial_number'];
		$demo_product_id = @$_REQUEST['demo_product_id'];
		$data = array('serial_number'=>$serial_number,'demo_product_id'=>$demo_product_id);
		$result = $this->Product_model->checkDemoProductSerialNumberAvailability($data);
		echo $result;
	}

	public function getProductsDropdownBySegment()
	{
		$segment = $this->input->post('segment');
		$data = '<option value="">Select Product</option>';
		$results = getAllProductsBySegment($segment);
		if($results)
		{
			foreach ($results as $row) {
				$data .= '<option value="'.$row['product_id'].'">'.$row['description'].'('.$row['name'].')</option>';
			}
		}
		if($data)
			echo $data;
	}

	public function manageFreeSupplyItems(){
		# Data Array to carry the require fields to View and Model
		$data['nestedView']['heading'] = "Manage Free Supply Item %";
		$data['nestedView']['cur_page'] = 'manageFreeSupplyItems';
		$data['nestedView']['parent_page'] = 'manageFreeSupplyItems';
		
		# Load JS and CSS Files
		$data['nestedView']['js_includes'] = array();
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/jquery.parsley/parsley.js"></script>';
		$data['nestedView']['js_includes'][] = '<script type="text/javascript" src="'.assets_url().'js/iCRM/product.js"></script>';
		$data['nestedView']['css_includes'] = array();
		
		# Breadcrumbs
		$data['nestedView']['breadCrumbTite'] = 'Manage Free Supply Item %';
		$data['nestedView']['breadCrumbOptions'] = array( array('label'=>'Home','class'=>'','url'=>SITE_URL.'home'));
		$data['nestedView']['breadCrumbOptions'][] = array('label'=>'Manage Free Supply Item %','class'=>'active','url'=>'');
		
		
		
		 
		
		/* pagination end */
		
		# Search Results
		//print_r($data['categorySearch']);die();
		$data['displayList'] = 1;
		$percentage=$this->input->post('percentage', TRUE);
		$data['freeSupplyItems'] = $this->Common_model->get_data('free_supply_item_percentage','item_id = 1');
		$this->load->view('product/freeSupplyItemPercentage', $data);
	}

	public function updatePercentage(){
		$percentage=$this->input->post('percentage', TRUE);
		// echo $percentage;die;
		$dataArr = array('percentage' => $percentage);
		$this->Common_model->update_data('free_supply_item_percentage',$dataArr, 'item_id = 1');

		$this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<div class="icon"><i class="fa fa-check"></i></div>
								<strong>Success!</strong> Percentage has been Updated successfully!
							 </div>');
		redirect(SITE_URL.'manageFreeSupplyItems');

	}
}