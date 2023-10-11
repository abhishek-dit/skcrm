<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function get_details($start, $len,$search_fields=array()) {
        $qry = "SELECT c.customer_id,name, name1,department,email,telephone, mobile,c.pan,c.tan,c.tin,c.status,l.location  FROM customer c
                INNER JOIN customer_location cl on cl.customer_id = c.customer_id
                INNER JOIN location l on cl.location_id = l.location_id
                 WHERE 1 
                and c.company_id=".$_SESSION['company']." and cl.location_id IN (".$this->session->userdata('locationString').") 
                
                ";
       // 'customerName','customerName','speciality','department','customerName','category_sub_id'
       
        if(count($search_fields)>0){
            $search_fields['category_id']=trim($search_fields['category_id']);
            $search_fields['category_sub_id']=trim($search_fields['category_sub_id']);
            
            if($search_fields['customerName']!='')
                    $qry .=" AND c.name like('%".$this->db->escape_str($search_fields['customerName'])."%') ";
            if($search_fields['s_location']!='')
                    $qry .=" AND cl.location_id = '".$this->db->escape_str($search_fields['s_location'])."' ";
            if($search_fields['department']!='')
                    $qry .=" AND department like('".$this->db->escape_str($search_fields['department'])."%') ";
            if($search_fields['category_id']!='')
                    $qry .=" AND category_id ='".$this->db->escape_str($search_fields['category_id'])."' ";
            if($search_fields['category_sub_id']!='')
                    $qry .=" AND category_sub_id ='".$this->db->escape_str($search_fields['category_sub_id'])."' ";
        }
        $qry .= " ";
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;

       // echo $qry; die();
        $res1 = $this->db->query($qry);
        //$res2 = $this->db->query($num_qry);
        $res['resArray'] = $res1->result_array();
        //$qry_data = $res1->result();
        $res['count'] = 1;
        return $res;
    }
     public function customerTotalRows($search_fields=array()) {
        $qry = "SELECT count(*) cnt FROM customer c
                INNER JOIN customer_location cl on cl.customer_id = c.customer_id WHERE 1 
                and c.company_id=".$_SESSION['company']." and cl.location_id IN (".$this->session->userdata('locationString').")";
       // 'customerName','customerName','speciality','department','customerName','category_sub_id'
       
        if(count($search_fields)>0){
            $search_fields['category_id']=trim($search_fields['category_id']);
            $search_fields['category_sub_id']=trim($search_fields['category_sub_id']);
            
            if($search_fields['customerName']!='')
                    $qry .=" AND name like('%".$this->db->escape_str($search_fields['customerName'])."%') ";
            if($search_fields['s_location']!='')
                    $qry .=" AND cl.location_id = '".$this->db->escape_str($search_fields['s_location'])."' ";
            if($search_fields['department']!='')
                    $qry .=" AND department like('".$this->db->escape_str($search_fields['department'])."%') ";
            if($search_fields['category_id']!='')
                    $qry .=" AND category_id ='".$this->db->escape_str($search_fields['category_id'])."' ";
            if($search_fields['category_sub_id']!='')
                    $qry .=" AND category_sub_id ='".$this->db->escape_str($search_fields['category_sub_id'])."' ";
        }
     //   $this->db->where('c.company_id',$this->session->userdata('company'));
        $qry .= " ";
        $num_qry = $qry;
        //$qry.=" LIMIT " . $start . " , " . $len;

        //echo $qry; die();
        $res1 = $this->db->query($qry);
        $qry_data = $res1->result();
         
        return $qry_data[0]->cnt;
        
    }
    public function get_download_details($search_fields) {
    
        $qry = "SELECT c.*,cat.name category_name,sub.name category_sub_name, l.location,c.modified_by,c.modified_time FROM customer c
                INNER JOIN customer_category cat on cat.category_id = c.category_id
                INNER JOIN customer_sub_category sub on sub.category_sub_id = c.category_sub_id
                INNER JOIN customer_location cl on cl.customer_id = c.customer_id
                INNER JOIN location l on l.location_id = cl.location_id WHERE 1 
                and c.company_id=".$_SESSION['company']." and l.location_id IN (".$this->session->userdata('locationString').")";
       
        if(count($search_fields)>0){
            $search_fields['category_id']=trim($search_fields['category_id']);
            $search_fields['category_sub_id']=trim($search_fields['category_sub_id']);
            
            if($search_fields['customerName']!='')
                    $qry .=" AND c.name like('%".$this->db->escape_str($search_fields['customerName'])."%') ";
            if($search_fields['s_location']!='')
                    $qry .=" AND cl.location_id = '".$this->db->escape_str($search_fields['s_location'])."' ";
            if($search_fields['department']!='')
                    $qry .=" AND department like('".$this->db->escape_str($search_fields['department'])."%') ";
            if($search_fields['category_id']!='')
                    $qry .=" AND cat.category_id ='".$this->db->escape_str($search_fields['category_id'])."' ";
            if($search_fields['category_sub_id']!='')
                    $qry .=" AND sub.category_sub_id ='".$this->db->escape_str($search_fields['category_sub_id'])."' ";
        }
        $qry .= "order by name";
       // $num_qry = $qry;
        $res1 = $this->db->query($qry);
       // echo $this->db->last_query();exit;
       // $res2 = $this->db->query($num_qry);
        return $res1->result_array();
    }
    public function editCompanyDetails($company_id) {
        $ret = array();
        $qry = "SELECT * from company where company_id = '$company_id'";
        $res = $this->db->query($qry);
        if ($res->num_rows() > 0) {
            $r = $res->result_array();
            $ret = $r[0];
        }
        return $ret;
    }

    public function get_category_drop_down() {
        $sql = "select category_id,name from customer_category where status=1 and company_id=".$_SESSION['company'];
        //$query=$this->db->get_where("shop");
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $category_ar = array();
        $category_ar[' '] = "Select Category";
        foreach ($res as $category) {
            $category_ar[$category['category_id']] = $category['name'];
        }

        return $category_ar;
    }

    public function get_customer_beds() {
        $sql = "select * from customer_beds";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $beds_ar = array();
        $beds_ar[' '] = "Select No. Of Beds";
        foreach ($res as $beds) {
            $beds_ar[$beds['id']] = $beds['bed_range'];
        }

        return $beds_ar;
    }

    public function get_customer_speciality() {
        $sql = "select * from customer_speciality";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $cust_speciality_ar = array();
        $cust_speciality_ar[' '] = "Select Speciality";
        foreach ($res as $speciality) {
            $cust_speciality_ar[$speciality['id']] = $speciality['name'];
        }

        return $cust_speciality_ar;
    }

   function get_sub_category_dropdown_ajax($category_id) {

        $sql = "SELECT sc.category_sub_id,sc.name FROM customer_sub_category sc,customer_category c,customer_category_details cd "
                . " WHERE c.category_id=cd.category_id "
                . " AND sc.category_sub_id=cd.category_sub_id "
                . " AND c.category_id= " . $this->db->escape($category_id)
                . " AND c.company_id= " . $_SESSION['company']
                . " AND sc.status =1 ";
        echo $sql;
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $sub_cat_options = "<option value=' '>Select Sub Category</option>";
        foreach ($res as $sub_cat) {
            $sub_cat_options.='<option value="' . $sub_cat['category_sub_id'] . '">' . $sub_cat['name'] . '</option>';
        }
        echo $sub_cat_options;
        return $sub_cat_options;
    }
     function get_sub_category_dropdown($category_id) {
       
        /*$sql = "SELECT sc.category_sub_id,sc.name FROM customer_sub_category sc,customer_category c,customer_category_details cd "
                . " WHERE c.category_id=cd.category_id "
                . " AND sc.category_sub_id=cd.category_sub_id "
                . " AND c.category_id= " . $this->db->escape($category_id)
                . " AND c.company_id= " . $_SESSION['company']
                . " AND sc.status =1 ";*/
        $sql= "SELECT sc.category_sub_id,sc.name from customer_sub_category sc inner join customer_category_details ccd on sc.category_sub_id=ccd.category_sub_id inner join customer_category cc on ccd.category_id=cc.category_id where cc.company_id='".$_SESSION['company']."' and ccd.status=1";
        if(trim($category_id)!='' )
        {
            $sql.=" AND cc.category_id = '".$category_id."'";
        }
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $sub_cat_options[' '] = "Select Sub Category";
        foreach ($res as $sub_cat) {
            $sub_cat_options[$sub_cat['category_sub_id']]=$sub_cat['name'];
        }
       
        return $sub_cat_options;
    }

    public function getLocationDetails($loc)
    {
        $data = [];
        $this->db->select('l.location_id, l.location as location, l1.location as parent');
        $this->db->from('location l');      
        $this->db->join('location l1','l1.location_id = l.parent_id','left');
        if($loc!='')
        $this->db->where('l.location_id IN ('.$loc.')');
        $this->db->where('l.status', 1);
        $this->db->limit(10, 0);
        $this->db->order_by('location_id');
        $res = $this->db->get();
        foreach($res->result_array() as $row)
        {
            $data[$row['location_id']] = $row['location'].'-'.$row['parent'];
        }
        return $data;
    }

    public function getLocation($customer_id)
    {
        //$ret = array();
        $this->db->select('l.location_id, concat(l.location, " (", l1.location, ")") as location');
        $this->db->from('location l');
         $this->db->join('location l1', 'l1.location_id = l.parent_id', 'left');      
        $this->db->join('customer_location c','c.location_id = l.location_id');
        $this->db->where('c.customer_id', $customer_id);
        $res = $this->db->get();
        if($res->num_rows() > 0)
        {
            $data = $res->result_array();
            return $data[0];
        }
        else
            return array('location_id' => '', 'location' => '--Select Location--');
    }

    public function getSearchLocation($location_id)
    {
        $this->db->select('l.location_id, concat(l.location, " (", l1.location, ")") as location');
        $this->db->from('location l');      
        $this->db->join('location l1', 'l1.location_id = l.parent_id', 'left');
        $this->db->where('l.location_id', $location_id);  
        $res = $this->db->get();
        if($res->num_rows() > 0)
        {
            $data = $res->result_array();
            return $data[0];
        }
        else
            return array('location_id' => '', 'location' => 'Select Location');
 
    }
    public function getInstallations($customer_id){
        $sql = "SELECT * from customer_installed where customer_id='".$customer_id."' ";
       
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
    }

    public function get_customer_details($start, $len,$search_fields=array()) 
    {
        $qry = "SELECT c.customer_id,c.name as `customer_name`, name1,department,email,telephone, mobile,c.pan,c.tan,c.tin,c.status,l.location,c.category_id,c.category_sub_id,c.fax,c.website,c.address1,c.address2,c.address3,c.landmark, c.pincode,c.gst,cat.name as `category_name`,s.name as `sub_category_name`,cl.location_id FROM customer c
                INNER JOIN customer_location cl on cl.customer_id = c.customer_id
                INNER JOIN location l on cl.location_id = l.location_id
                INNER JOIN customer_category cat on cat.category_id = c.category_id
                INNER JOIN customer_sub_category s on s.category_sub_id = c.category_sub_id
                 WHERE 1 
                and c.company_id=".$_SESSION['company']." and cl.location_id IN (".$this->session->userdata('locationString').") 
                
                ";
        if(count($search_fields)>0){
            if($search_fields['s_location']!='')
                    $qry .=" AND cl.location_id = '".$this->db->escape_str($search_fields['s_location'])."' ";
        }
        $qry .= " ";
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;

        $res1 = $this->db->query($qry);
        $res = $res1->result_array();
        return $res;
    }

    public function customerAppTotalRows($searchParams)
    {
        $this->db->select('c.customer_id');
        $this->db->from('customer c');
        $this->db->join('customer_location cl','cl.customer_id = c.customer_id');
        $this->db->join('location l','l.location_id = cl.location_id');
        $this->db->where('c.company_id',$this->session->userdata('company'));
        if($searchParams['customer']!='')
            $this->db->like('c.name', $searchParams['customer']);
        if($searchParams['s_location']!='')
            $this->db->like('cl.location_id', $searchParams['s_location']);
        $this->db->where('c.status', 4);
        $res = $this->db->get();
        return $res->num_rows();
    }


    public function customerAppRsults($searchParams, $per_page, $current_offset)
    {
        $this->db->select('c.*,l.location');
        $this->db->from('customer c');
        $this->db->join('customer_location cl','cl.customer_id = c.customer_id');
        $this->db->join('location l','l.location_id = cl.location_id');
        $this->db->where('c.company_id',$this->session->userdata('company'));
        if($searchParams['customer']!='')
            $this->db->like('c.name', $searchParams['customer']);
        if($searchParams['s_location']!='')
            $this->db->like('cl.location_id', $searchParams['s_location']);
        $this->db->where('c.status', 4);
        $this->db->limit($per_page, $current_offset);
        $res = $this->db->get();
        $data = $res->result_array();
        return $data;
    }
    public function getCnoteGeneratedCustomerData()
	{   
		
		$this->db->select('c.name,c.remarks2 as customer_code');
		$this->db->from('contract_note cn');
		$this->db->join('contract_note_quote_revision cnqr','cn.contract_note_id=cnqr.contract_note_id');
		$this->db->join('quote_revision qr','cnqr.quote_revision_id=qr.quote_revision_id');
		$this->db->join('quote_details qd','qr.quote_id=qd.quote_id');
		$this->db->join('opportunity o','qd.opportunity_id=o.opportunity_id');
		$this->db->join('opportunity_product op','o.opportunity_id=op.opportunity_id');
		$this->db->join('lead l','o.lead_id=l.lead_id');
		$this->db->join('customer c','l.customer_id=c.customer_id');
		$this->db->join('product p','op.product_id=p.product_id');
		/*$where=' cn.contract_note_id = (select min(cn1.contract_note_id) from contract_note cn1  inner join contract_note_quote_revision cnqr1 on cn1.contract_note_id=cnqr1.contract_note_id inner join quote_revision qr1 on cnqr1.quote_revision_id=qr1.quote_revision_id inner join quote_details qd1 on qr1.quote_id=qd1.quote_id inner join opportunity o1 on qd1.opportunity_id=o1.opportunity_id inner join lead l1 on o1.lead_id=l1.lead_id where c.customer_id=l1.customer_id)';*/
		$this->db->where('p.product_type_id',1);
		$this->db->where('cn.company_id',$this->session->userdata('company'));
        $this->db->group_by('c.customer_id');
		$res=$this->db->get();
		//echo $this->db->last_query(); exit;
		return $res->result_array();
	}
}
