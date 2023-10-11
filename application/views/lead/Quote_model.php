<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Quote_model extends CI_Model {

    public function get_opportunities($lead_id){
        
        //$sql="select o.opportunity_id,concat(u.first_name,' ',last_name,'( ',group_concat(' ',p.name,' '),' )') product_name from opportunity o
        $sql="SELECT o.opportunity_id,o.opp_number,p.product_id ,pg.category_id, p.name as product_name,p.description,o.required_quantity,op.mrp,p.dp,p.sub_category_id  from opportunity o    
                JOIN opportunity_product op ON o.opportunity_id=op.opportunity_id 
                JOIN product p ON p.product_id=op.product_id 
                JOIN product_group pg ON pg.group_id = p.group_id
                JOIN lead l ON l.lead_id=o.lead_id 
                JOIN user u ON u.user_id =l.user_id        
                where o.status NOT IN (6, 7, 8) AND o.lead_id=".$this->db->escape($lead_id)."
                group by o.opportunity_id";
        
        $res1 = $this->db->query($sql);
        return $res['op_res'] = $res1->result_array();
    }

    public function getProductSubCategory($product_id)
    {
        $ret = array('' => 'Select Sub-Category') ;
        if($product_id != '')
        {
            $q = 'SELECT s.sub_category_id, s.name from sub_category s
                    INNER JOIN category_sub_category cs ON cs.sub_category_id = s.sub_category_id
                    INNER JOIN product_group pg ON pg.group_id = cs.category_id
                    INNER JOIN product p ON p.group_id = pg.group_id
                    WHERE p.product_id = "'.$product_id.'"';
            $r = $this->db->query($q);
            foreach($r->result_array() as $row)
            {
                $ret[$row['sub_category_id']] = $row['name'];
            }
        }
        return $ret;
    }

    public function getQuoteDetails($lead_id)
    {
        $qry = 'SELECT q.quote_id as quote_id, b.name as billing, qr.discount as discount, 
                group_concat(concat("ID - ", o.opportunity_id, " : ", p.name, " - ", p.description, " (Qty -", o.required_quantity, ")") separator "<br>") 
                as opportunity, q.status, q.warranty, q.advance_type, q.advance, q.balance_payment_days, q.dealer_commission from quote q
                INNER JOIN quote_revision qr ON qr.quote_id = q.quote_id
                INNER JOIN billing b ON qr.billing_info_id = b.billing_info_id
                INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN product p ON p.product_id = op.product_id
                WHERE qr.status = 1 AND o.lead_id = "'.$lead_id.'"
                group by q.quote_id
                order by qr.quote_revision_id desc';
        $res1 = $this->db->query($qry);        
        $res['resArray'] = $res1->result_array();
        $res['count'] = $res1->num_rows();
        return $res;
    }

    // Phase2 Update: @Mahesh 16-08-2017 fetching warranty, advance, balance payment days, dealer_commission
    function get_quote_details1($cid = NULL)
    {
        if($cid != NULL)
        {

            $q2 = 'SELECT * from quote_revision where quote_revision_id = '.$this->db->escape($cid).'';
            $r2 = $this->db->query($q2);
            if($r2->num_rows() > 0)
            {
                $qDataA = $r2->result_array();
                $qData = $qDataA[0];
                $qid = $qData['quote_id'];
                $data['quotation_id'] = $qid;
                $data['created_time'] = $qData['created_time'];
                $data['quote_creator'] = getNameAndRole($qData['created_by']);
                $billing_to = $qData['billing_info_id'];
                $data['discount'] = $qData['discount'];
                $lead_id = getLeadFromQuote($qid);
                $customer_id = getLeadCustomerID($lead_id);

                $leadOwner = array('user_id' => '', 'user2' => '');
                $q3 = 'SELECT user_id, user2 from lead where lead_id = "'.$lead_id.'"';
                $r3 = $this->db->query($q3);
                if($r3->num_rows() > 0)
                {
                    $d3 = $r3->result_array();
                    $leadOwner = $d3[0];
                }
                $role_id = getUserRole($qData['created_by']);
                $data['roleCheck'] = 1;

                //$data['bank_details'] = array('name' => '', 'branch' => '', 'ac_name' => '', 'ac_no' => '', 'ifsc' => '');
               $channels = get_channel_partner_details($qid);
                if($channels['type']==2)
                { 
                    switch($billing_to)
                    {
                        case 1:
                            if($role_id == 5)
                                $data['lead_owner'] = getNameAndRole($leadOwner['user2']);
                            else $data['lead_owner'] = getNameAndRole($leadOwner['user_id']);
                            $data['bank_details'] = getBankDetails(0,$qid);
                            break;
                        case 2:
                            $data['lead_owner'] = getNameAndRole($leadOwner['user_id']);
                            $distrib = $leadOwner['user2'];
                            if($role_id == 5) 
                            {
                                $distrib = $leadOwner['user_id'];
                                $data['roleCheck'] = 2;
                                $data['lead_owner']['distributor'] = getDistributorName($leadOwner['user_id']);
                            }
                            $data['bank_details'] = getBankDetails($distrib,$qid);
                            break;
                        case 3:
                            if($role_id == 5)
                                $data['lead_owner'] = getNameAndRole($leadOwner['user2']);
                            else $data['lead_owner'] = getNameAndRole($leadOwner['user_id']);
                            $data['bank_details'] = getBankDetails($qData['stockist_id'],$qid);
                            break;
                    }
                }
                else
                {
                    if($role_id == 5)
                    {
                        $data['lead_owner'] = getNameAndRole($leadOwner['user2']);
                    }
                    else
                    {
                        $data['lead_owner'] = getNameAndRole($leadOwner['user_id']);
                    }
                    $data['bank_details'] = getBankDetails(0,$qid);
                }
                /*
                if($data['lead_owner']['role'] == 'Stockist') 
                {
                    $data['lead_owner'] = $data['quote_creator'];
                }
                //$data['quote_creator'] = $data['lead_owner'];
                */
                //$data['lead_owner'] = $data['quote_creator'];
                $data['quote_creator'] = $data['lead_owner'];                
                $data['quote_creator']['role'] = ($data['quote_creator']['role'] == 'Super User')?'Sales Engineer':$data['quote_creator']['role'];

                $q = "SELECT group_concat(name separator ' And ') as name from (SELECT sc.* from quote q
                        INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                        INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                        INNER JOIN sub_category sc ON sc.sub_category_id = qd.sub_category_id
                        WHERE q.quote_id = ".$this->db->escape($qid)."
                        group by sc.sub_category_id) t";
                $r = $this->db->query($q);
                $data['product_category_desc'] = '';
                if($r->num_rows() > 0)
                {
                    $d = $r->result_array();
                    $data['product_category_desc'] = $d[0]['name'];
                }
                $data['customer_details'] = array('name' => '', 'address' => '', 'email' => '', 'mobile' => 0);
                $q1 = 'SELECT concat(c.name, " ", c.name1) as name, concat(c.address1, " ", c.address2, " - ", l.location) as address,
                        c.address1 as address1, c.address2 as address2, c.pincode as pincode, c.pan as pan, 
                        c.email as email, c.mobile as mobile, c.telephone as landline from customer c
                        INNER JOIN customer_location cl ON cl.customer_id = c.customer_id
                        INNER JOIN location l ON cl.location_id = l.location_id
                        where c.customer_id = "'.$customer_id.'"';
                $r1 = $this->db->query($q1);
                if($r1->num_rows() > 0)
                {
                    $d1 = $r1->result_array();
                    $data['customer_details'] = $d1[0];
                    $data['customer_details']['mobile'] = getPhoneNumber($data['customer_details']['mobile']);
                }
                /* To fetch the data from product table directly
                $q4 = 'SELECT p.product_id, p.name, p.description, p.features, p.scope, p.mrp, p.rrp, p.dp, p.base_price, 
                        p.ed, p.vat, p.freight_insurance,p.gst, sum(o.required_quantity) as qty from quote q
                        INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                        INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                        INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                        INNER JOIN product p ON p.product_id = op.product_id
                        WHERE q.quote_id = '.$this->db->escape($qid).'
                        group by p.product_id';
                */
                //To fetch the data from quote_details table
                $q4 = 'SELECT p.product_id, p.name, p.description, p.features, p.scope, qd.mrp, p.rrp, p.dp, p.base_price, 
                        qd.ed, qd.vat, qd.freight_insurance,qd.gst, o.required_quantity as qty,o.opportunity_id from quote q
                        INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                        INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                        INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                        INNER JOIN product p ON p.product_id = op.product_id
                        WHERE q.quote_id = '.$this->db->escape($qid).'
                        '; // Removed group by p.product_id

                $r4 = $this->db->query($q4);
                $data['product_details'] = $r4->result_array(); 
                //echo "<pre>";
                //print_r($data); die();

                return $data;       
            }
            else return false;

        }
        else
        {
            return FALSE;
        }
    }
    
    
    function get_quote_details($qid = NULL) {
        if ($qid != NULL) 
        {
            $sql = "SELECT q.*,q.created_time as 'quote_date',b.name as 'billing_name',o.*,l.*
                FROM quote q 
                JOIN billing b ON b.billing_info_id=q.billing_info_id
                JOIN quote_details qd on q.quote_id =qd.quote_id
                JOIN opportunity o on o.opportunity_id=qd.opportunity_id
                JOIN lead l ON o.lead_id=l.lead_id 
                where q.quote_id=" . $this->db->escape($qid) . "";

            $query = $this->db->query($sql);
            $res = $query->result_array();
            foreach ($res as $v);
            $data['quote_details'] = $v; //Quotation Details
            
            //user details
            $sql1 = "select u.* ,"
                    . " c.name as 'company_name',c.PAN_number,c.TIN_number,c.CIN_number ,c.TAN_number,c.service_tax_number,c.sales_tax_number,c.excise_number,c.address1,c.address2,c.state,c.city,c.country "
                    . "from user u "
                    . " JOIN company c ON c.company_id=u.company_id "
                    . " where u.user_id='" . $data['quote_details']['user_id'] . "'";
            $query = $this->db->query($sql1);
            $res1 = $query->result_array();
            foreach ($res1 as $v1);
            $data['user_details'] = $v1;
            
            //Distributor
            if ($data['user_details']['role_id'] == "5") {
                $sql = "select * from distributor_details where user_id='" . $data['quote_details']['user_id'] . "'";
                $query = $this->db->query($sql1);
                $res1 = $query->result_array();
                foreach ($res1 as $v1);
                $data['distributor_details'] = $v1; // distributor details
            }
            
            //product details
            $producnt_detils=array();
            foreach ($res as $v){
            $product_query = "SELECT p.product_id,pg.group_id,pc.category_id,"
                                . " p.name,p.description,p.mrp,p.base_price,p.ed,p.vat,p.freight_insurance,p.gst,p.mrp,p.dp,"
                                . " pg.name as product_group, "
                                . " pc.category_id,pc.company_id,pc.name as 'company_name' "
                                . " FROM product p"
                                . " JOIN opportunity_product op ON op.product_id=p.product_id "
                                . " JOIN product_group pg ON p.group_id=pg.group_id "
                                . " JOIN product_category pc ON pc.category_id=pg.category_id"
                                . " where op.opportunity_id='" . $v['opportunity_id'] . "' ";
            $query = $this->db->query($product_query);
            $res1 = $query->result_array();

            //foreach ($res1 as $v1);
            $res1[0]['qty']=$v['required_quantity'];
             $producnt_detils[]= $res1[0]; //prodct and there company detials
            }
            $data['product_details']=$producnt_detils;
                    
            return $data;
            
        }
        else
        {
            return FALSE;
        }
    }
    
    function get_products($quote_ids=[]){
        $products=array();
        if(count($quote_ids)>0){
            foreach($quote_ids as $v){
                
                $sql="SELECT CONCAT('ID-',p.product_id,'  ',p.name, ' - ',p.description,' ( QNT - ',o.required_quantity,' )') as name
                from quote q 
                JOIN quote_details qd ON q.quote_id=qd.quote_id
                JOIN opportunity o ON o.opportunity_id=qd.opportunity_id
                JOIN opportunity_product op ON o.opportunity_id=op.opportunity_id 
                JOIN product p ON p.product_id=op.product_id 
                WHERE q.quote_id='".$v."'
                ";
                $query=$this->db->query($sql);
                //die();
                $res=$query->result_array();
                $products[$v]=$res;
            }
            
        }
        return $products;
    }
    
    //get details for approval
    public function get_details_for_approval($start, $len,$search_fields=array()) {
        //print_r($search_fields);
        if($_SESSION['reportees'] == NULL){
            $repotees="0";
        }else{
            $repotees=$_SESSION['reportees'];
        }
        $discount_con=" ";
        if($_SESSION['role_id'] == '7'){ //RBH
             $discount_con=" AND q.discount< 30 ";
        }elseif($_SESSION['role_id'] == '8'){ // NSM
            $discount_con=" AND q.discount >= 30 AND q.discount<= 35 ";
        }elseif($_SESSION['role_id'] == '9'){ //CH
            $discount_con=" AND q.discount > 35 ";
        }
        
        $qry = "SELECT concat(c.first_name,' ',c.last_name) contact_name,q.quote_id,q.discount,q.status quote_status,b.name billing_name,concat(c.first_name,' ',c.last_name) contact_name,c.mobile_no contact_mobile 
                from quote q 
                JOIN quote_details qd ON q.quote_id=qd.quote_id
                JOIN opportunity o ON o.opportunity_id=qd.opportunity_id
                JOIN opportunity_product op ON o.opportunity_id=op.opportunity_id 
                JOIN product p ON p.product_id=op.product_id 
                JOIN billing b ON b.billing_info_id=q.billing_info_id
                JOIN lead l ON l.lead_id=o.lead_id 
                JOIN user u ON u.user_id =l.user_id
                JOIN contact c ON c.contact_id =l.contact_id
                WHERE 1 AND u.user_id in (".$repotees.") $discount_con 
                        AND q.status=1 
                ";
                
       
        if(count($search_fields)>0)
        {
            if($search_fields['billingName']!='')
                    $qry .=" AND b.name  like( '%".$this->db->escape_str($search_fields['billingName'])."%') ";
        }
        $qry .= "  GROUP BY q.quote_id order by q.quote_id desc";
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;

       //echo $qry; die();
        $res1 = $this->db->query($qry);
        $res2 = $this->db->query($num_qry);
        $res['resArray'] = $res1->result_array();
        $qry_data = $res1->result();
        $res['count'] = $res2->num_rows();
        return $res;
    }

    public function getQuotesForApproval($start, $len, $searchParams)
    {

        $quoteApp[7] = array('min' => 0, 'max' => 30);
        $quoteApp[8] = array('min' => 30, 'max' => 35);
        $quoteApp[9] = array('min' => 35, 'max' => 100);
        $q = 'SELECT * from quote_approval';
        $r = $this->db->query($q);
        foreach($r->result_array() as $row)
        {
            $quoteApp[$row['role_id']] = array('min' => $row['min'], 'max' => $row['max']);
        }

        $repotees = $this->session->userdata('reportees');
        $discount_con=" ";
        if($_SESSION['role_id'] == '7')
        { //RBH
             $discount_con=" AND qr.discount< ".$quoteApp[7]['max'];
        }
        elseif($_SESSION['role_id'] == '8')
        { // NSM
            $discount_con=" AND qr.discount >= ".$quoteApp[8]['min']." AND qr.discount< ".$quoteApp[8]['max'];
        }
        elseif($_SESSION['role_id'] == '9')
        { //CH
            $discount_con=" AND qr.discount >= ".$quoteApp[9]['min'];
        }

        // new update: 24-05-2018, added qr.discount > 0 in quote_revision table join condition to avoid new quote revisions not listed in old quote approvals
        $prod = $this->session->userdata('products');
        if($prod == '') $prod = 0;
        $qry = 'SELECT * FROM (SELECT q.quote_id as quote_id, b.name as billing, qr.discount as discount, qr.created_by,
                q.status, concat(t.tag, "-", case when month(curdate() < 4) then substr(year(curdate()) - 1, 3,4) 
                else substr(year(curdate()), 3,4) end, "-",q.quote_id) as tag,
                group_concat(concat("ID - ", o.opportunity_id, " : ", p.name, " - ", p.description, " (Qty -", o.required_quantity, ")") separator "<br>") 
                as opportunity from quote q
                INNER JOIN quote_revision qr ON qr.quote_id = q.quote_id AND case when q.status = 1 then qr.status = 1 else 
                CASE when q.status = 6 then qr.status = 3 end end  AND qr.discount > 0
                INNER JOIN billing b ON qr.billing_info_id = b.billing_info_id
                INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN product p ON p.product_id = op.product_id AND p.product_id IN ('.$prod.')
                INNER JOIN (
                SELECT q.quote_id, l.lead_id, l3.tag from quote q
                INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                INNER JOIN lead l ON l.lead_id = o.lead_id
                INNER JOIN location l1 ON l1.location_id = l.location_id
                INNER JOIN location l2 ON l2.location_id = l1.parent_id
                INNER JOIN location l3 ON l3.location_id = l2.parent_id
                group by q.quote_id
                ) t ON t.quote_id = q.quote_id

            WHERE q.created_by IN ('.$repotees.',1 ) '.$discount_con;
         if (count($searchParams) > 0) {

            if ($searchParams['billing_id'] != '')
                $qry .=" AND b.billing_info_id  = '" . $this->db->escape_str($searchParams['billing_id']) . "' ";
                      
        }
        $qry .= "  GROUP BY q.quote_id ) table1";
        if (count($searchParams) > 0) {
            if ($searchParams['quote_id'] != '')
                $qry .=" WHERE table1.tag  like ('%" . $this->db->escape_like_str($searchParams['quote_id']) . "%') ";
        }
            $qry .="   order by table1.quote_id desc";
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;

       //echo $qry; die();
        $res1 = $this->db->query($qry);
        $res2 = $this->db->query($num_qry);
        $res['resArray'] = $res1->result_array();
        $qry_data = $res1->result();
        $res['count'] = $res2->num_rows();
        return $res;
    }

    public function quoteTotalApprovalRows()
    {
        return 12;
    }

    public function getProductCategories($lead_id)
    {
        $ret = array('' => 'Select Product Category');
        if($lead_id != '')
        {
            $q = 'SELECT pc.category_id, pc.name from lead l
                INNER JOIN opportunity o ON o.lead_id = l.lead_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN product p ON p.product_id = op.product_id
                INNER JOIN product_group pg ON pg.group_id = p.group_id
                INNER JOIN product_category pc ON pc.category_id = pg.category_id
                WHERE o.status IN (1,2,3,4,5) AND l.lead_id = "'.$lead_id.'"
                group by pc.category_id';
            $r = $this->db->query($q);
            foreach($r->result_array() as $row)
            {
                $ret[$row['category_id']] = $row['name'];
            }    
        }
        return $ret;
    }

    public function getContractPDFDetails($contract_note_id)
    {
        $data=array();
        $data['quotation'] = array();
        $contract_note=$this->Common_model->get_data('contract_note' , array('contract_note_id'=>$contract_note_id) );
        $data['contract_note']= $contract_note[0];
        $data['freeProducts'] = getContractFreeProducts($contract_note_id);
        $data['engineer'] = getNameAndID($data['contract_note']['created_by']);
        $data['engineer']['branch'] = getBranchforUser($data['contract_note']['created_by']);
        if($this->session->userdata('role_id') == 5)
        {
            $data['distributor'] = $data['engineer'];
        }
        $quotes = getQuoteIDByContractNote($contract_note_id);
		$quote_date = getQuoteCreatedDateByCnote($contract_note_id);
        $quote_id = $quotes[0];
        $quotesVal = implode(",",$quotes);
		$data['quote_date'] = implode(",", $quote_date);
        $data['quote_val'] = $quotesVal;
		$lead_id = getLeadFromQuote($quote_id);
        $data['lead_id_val'] = $lead_id;
        $customer_id = getLeadCustomerID($lead_id);

        $q3 = 'SELECT user_id, user2 from lead where lead_id = "'.$lead_id.'"';
        $r3 = $this->db->query($q3);
        if($r3->num_rows() > 0)
        {
            $d3 = $r3->result_array();
            $leadOwner = $d3[0];
        }
        if($this->session->userdata('role_id') != 5) $data['distributor'] = getNameAndID($leadOwner['user2']);
        else
        {
            $data['engineer'] = getNameAndID($leadOwner['user2']);
            //$data['engineer']['branch'] = getBranchforUser($leadOwner['user2']); // If Branch to be displayed as company users
            $data['engineer']['branch'] = getBranchforUser($data['contract_note']['created_by']); // If Branch to be displayed is users
        } 

        $q1 = 'SELECT concat(c.name) as name, concat(c.address1, " ", c.address2, " - ", l.location) as address,
                c.address1 as address1, case when (c.address2 != "") then concat(c.address2, ", ", l.location)
                else l.location end as address2, c.pincode as pincode, c.pan as pan, 
                c.email as email, c.mobile as mobile, c.telephone as landline,c.gst, l1.location as district,
                l2.location as state from customer c
                INNER JOIN customer_location cl ON cl.customer_id = c.customer_id
                INNER JOIN location l ON cl.location_id = l.location_id
                LEFT JOIN location l1 ON l.parent_id = l1.location_id
                LEFT JOIN location l2 ON l1.parent_id = l2.location_id
                where c.customer_id = "'.$customer_id.'"';
        $r1 = $this->db->query($q1);
        if($r1->num_rows() > 0)
        {
            $d1 = $r1->result_array();
            $data['customer_details'] = $d1[0];
            $data['customer_details']['mobile'] = getPhoneNumber($data['customer_details']['mobile']);
            $data['customer_details']['landline'] = getPhoneNumber($data['customer_details']['landline']);
        }
        /* To fetch the data from product table directly
        $q = "SELECT p.product_id, p.name, p.description, sum(qty) as qty,
                round(sum(mrp*qty*(1-(discount/100))*(1+(freight_insurance/100)))) total  from
                (SELECT p.*, o.opportunity_id, o.required_quantity as qty, max(qr.discount) discount from contract_note cn
                INNER JOIN contract_note_quote_revision cr ON cr.contract_note_id = cn.contract_note_id
                INNER JOIN quote_revision qr ON qr.quote_revision_id = cr.quote_revision_id
                INNER JOIN quote q ON q.quote_id = qr.quote_id
                INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN product p ON op.product_id = p.product_id
                WHERE cn.contract_note_id = '".$contract_note_id."'
                group by opportunity_id) as p
                group by p.product_id";
        */
        // TO fetch the data from opportunity_product table
        $q = "SELECT p.product_id, p.name, p.description, sum(qty) as qty,
                round(sum(q_mrp*qty*(1-(discount1/100))*(1+(freight_insurance/100)))) total1,
                round(sum((q_mrp/(1+(freight_insurance/100))/(1+(gst/100)))*qty*(1-(discount1/100))*(1+(freight_insurance/100))*(1+(gst/100)))) total  from
                (SELECT p.name, p.description, op.*, o.required_quantity as qty, max(qr.discount) discount1,qd.mrp as q_mrp from contract_note cn
                INNER JOIN contract_note_quote_revision cr ON cr.contract_note_id = cn.contract_note_id
                INNER JOIN quote_revision qr ON qr.quote_revision_id = cr.quote_revision_id
                INNER JOIN quote q ON q.quote_id = qr.quote_id
                INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN product p ON op.product_id = p.product_id
                WHERE cn.contract_note_id = '".$contract_note_id."'
                group by opportunity_id) as p
                group by p.product_id";
        $r = $this->db->query($q);
        $data['product_details'] = $r->result_array();

        return $data;

    }

    /* Phase2 update: start*/
    function getCNoteProductDetails($contract_note_id)
    {
        $q = "SELECT qd.mrp,p.product_id,o.opp_number, p.name, p.description, (o.required_quantity) as qty, ma.discount, o.opportunity_id,
                round(CASE WHEN ma.discount_type=1 THEN (qd.mrp*o.required_quantity)*(1-ma.discount/100) ELSE (qd.mrp*o.required_quantity)-ma.discount END) total from contract_note cn
                INNER JOIN contract_note_quote_revision cr ON cr.contract_note_id = cn.contract_note_id
                INNER JOIN quote_revision qr ON qr.quote_revision_id = cr.quote_revision_id 
                INNER JOIN quote q ON q.quote_id = qr.quote_id
                INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN product p ON op.product_id = p.product_id
                LEFT JOIN quote_op_margin_approval ma ON ma.quote_revision_id = cr.quote_revision_id AND op.opportunity_id = ma.opportunity_id
                WHERE cn.contract_note_id = '".$contract_note_id."'";
        $r = $this->db->query($q);
        return  $r->result_array();
    }

    function getCNoteProductDetailsOfOldQuotes($contract_note_id)
    {
        $q = "SELECT p.product_id, p.name, p.description, (o.required_quantity) as qty, qr.discount, o.opportunity_id,
                round(((qd.mrp/(1+qd.freight_insurance/100)/(1+qd.gst/100))*o.required_quantity*(1- qr.discount /100)*(1+(qd.freight_insurance/100))*(1+(qd.gst/100)))) total from contract_note cn
                INNER JOIN contract_note_quote_revision cr ON cr.contract_note_id = cn.contract_note_id
                INNER JOIN quote_revision qr ON qr.quote_revision_id = cr.quote_revision_id 
                INNER JOIN quote q ON q.quote_id = qr.quote_id
                INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN product p ON op.product_id = p.product_id
                LEFT JOIN quote_op_margin_approval ma ON ma.quote_revision_id = cr.quote_revision_id AND op.opportunity_id = ma.opportunity_id
                WHERE cn.contract_note_id = '".$contract_note_id."'";
        $r = $this->db->query($q);
        return  $r->result_array();
    }

    function getCNoteQuoteApprovalDetails($contract_note_id)
    {
        $this->db->select('ma.*,CONCAT(u.first_name," ",u.last_name) as se,qr.quote_id');
        $this->db->from('quote_op_margin_approval ma');
        $this->db->join('contract_note_quote_revision cnqr','ma.quote_revision_id = cnqr.quote_revision_id');
        $this->db->join('quote_revision qr','qr.quote_revision_id = cnqr.quote_revision_id');
        $this->db->join('user u','qr.created_by = u.user_id','left');
        $this->db->where('cnqr.contract_note_id',$contract_note_id);
        $this->db->order_by('ma.quote_revision_id asc,ma.opportunity_id asc');
        $res = $this->db->get();
        if($res->num_rows()>0)
        {
            return $res->result_array();
        }
    }

    function getCNoteQuoteApprovalHistory($contract_note_id)
    {
        $this->db->select('mah.*,CONCAT(u.first_name," ",u.last_name) as approval_by,qr.quote_id,ma.opportunity_id');
        $this->db->from('quote_op_margin_approval ma');
        $this->db->join('quote_op_margin_approval_history mah','ma.margin_approval_id = mah.margin_approval_id','LEFT');
        $this->db->join('contract_note_quote_revision cnqr','ma.quote_revision_id = cnqr.quote_revision_id');
        $this->db->join('quote_revision qr','qr.quote_revision_id = cnqr.quote_revision_id');
        $this->db->join('user u','mah.created_by = u.user_id','left');
        $this->db->where('cnqr.contract_note_id',$contract_note_id);
        $this->db->order_by('ma.quote_revision_id asc,ma.opportunity_id asc');
        $res = $this->db->get();
        if($res->num_rows()>0)
        {
            return $res->result_array();
        }
    }
    /* Phase2 update: end*/

    /*
    ** Get Quote Free Supply items
    ** Phase2 Update
    ** Mahesh created: 16-08-2017 updated: 
    ** START
    */
    public function getQuoteRevisionFreeSupplyItems($quote_revision_id)
    {
        
        if($quote_revision_id != '')
        {
            $q = 'SELECT p.product_id,p.name,p.description,q.quantity,q.unit_price from quote_opp_free_supply q
                INNER JOIN product p ON p.product_id = q.product_id
                WHERE q.quote_revision_id = "'.$quote_revision_id.'"';
            $r = $this->db->query($q);
            return $r->result_array();  
        }
    }

    /*
    ** Get Lead owner details
    ** Phase2 Update
    ** Mahesh created: 16-08-2017 updated: 
    */
    public function getLeadOwnerDetails($lead_id)
    {
        
        if($lead_id != '')
        {
            $this->db->select('l.*,u.*');
            $this->db->from('lead l');
            $this->db->join('user u','l.user_id = u.user_id','INNER');
            $this->db->where('l.lead_id',$lead_id);
            $r = $this->db->get();
            return $r->result_array();  
        }
    }

    /*
    ** Get Quotes information by CNote ID
    ** Phase2 Update
    ** Mahesh created: 16-08-2017 updated: 
    */
    public function getQuotesByCNoteID($contract_note_id)
    {
        
        if($contract_note_id != '')
        {
            $this->db->select('q.*,qr.created_time as quote_revision_time,qr.quote_revision_id');
            $this->db->from('contract_note c');
            $this->db->join('contract_note_quote_revision cq','c.contract_note_id = cq.contract_note_id','INNER');
            $this->db->join('quote_revision qr','qr.quote_revision_id = cq.quote_revision_id','INNER');
            $this->db->join('quote q','q.quote_id = qr.quote_id','INNER');
            $this->db->where('c.contract_note_id',$contract_note_id);
            $r = $this->db->get();
            return $r->result_array();  
        }
    }


    

    public function getQuoteDetailsByLead($lead_id)
    {
        $this->db->select('q.quote_id as quote_id,qr.quote_revision_id, qr.discount as discount, qd.mrp,p.dp, o.required_quantity , q.status,
                concat(p.name, " - ", p.description, " (Qty -", o.required_quantity, ")")
                as opportunity, qr.status as quote_revision_status, q.warranty, q.advance_type, q.advance, q.balance_payment_days, q.dealer_commission,
                ma.approval_at,ma.close_at,ma.modified_by,ma.status as status,ma.discount as opp_discount,ma.discount_type as opp_discount_type,
                qr.created_time,q.quote_number,o.opportunity_id,CONCAT(u.first_name," ",u.last_name) as user,mah.approved_by,mah.status as app_status');
        $this->db->from('quote q');
        $this->db->join('quote_revision qr','q.quote_id = qr.quote_id','INNER');
        $this->db->join('quote_details qd','q.quote_id = qd.quote_id','INNER');
        $this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id','INNER');
        $this->db->join('opportunity_product op','o.opportunity_id = op.opportunity_id','INNER');
        $this->db->join('product p','op.product_id = p.product_id','INNER');
        $this->db->join('quote_op_margin_approval ma','ma.opportunity_id = o.opportunity_id AND ma.quote_revision_id = qr.quote_revision_id','LEFT');
        $this->db->join('quote_op_margin_approval_history mah','mah.margin_approval_id = ma.margin_approval_id','LEFT');
        $this->db->join('user u','u.user_id = mah.created_by','LEFT');
        //$this->db->join('quote_op_margin_approval_history mah','mah.margin_approval_id = ma.margin_approval_id','INNER');
        $this->db->where('o.lead_id',$lead_id);
        $this->db->where('qr.status in (1,2,3)');
        $this->db->order_by('q.quote_id desc, qr.quote_revision_id desc, qr.status desc');
        $res1 = $this->db->get();
        if($res1->num_rows()>0)
        {       

            return $res1->result_array();
        }
    }

    public function getDistributorCNoteDetails($contract_note_id)
    {
        if($contract_note_id!='')
        {
            $this->db->select('cn.*,d.distributor_name,u.employee_id, concat(u.first_name," ",u.last_name) as person_name,d.PAN_number as pan,
                u.address1,u.address2,u.city,u.state,u.email_id,u.mobile_no, b.name as branch,
                po.advance_type, po.advance,po.warranty,po.balance_payment_days,po.purchase_order_id,po.created_time as purchase_order_date');
            $this->db->from('contract_note cn');
            $this->db->join('contract_note_po_revision cnpr','cn.contract_note_id = cnpr.contract_note_id','INNER');
            $this->db->join('po_revision pr','pr.po_revision_id = cnpr.po_revision_id','INNER');
            $this->db->join('purchase_order po','pr.purchase_order_id = po.purchase_order_id','INNER');
            $this->db->join('distributor_details d','d.user_id = po.user_id','LEFT');
            $this->db->join('user u','u.user_id = po.user_id','LEFT');
            $this->db->join('branch b','b.branch_id = u.branch_id');
            $this->db->where('cn.contract_note_id',$contract_note_id);
            $res = $this->db->get();
            if($res->num_rows()>0)
            {
                $row = $res->row_array();
                return $row;
            }
        }
    }

    function getDistributorCNoteProductDetails($contract_note_id)
    {
        $q = "SELECT p.product_id, p.name, p.description, pp.qty, pa.discount, 
                round(((pp.unit_price/(1+pp.freight_insurance/100)/(1+pp.gst/100))*pp.qty*(1-(CASE WHEN pa.discount_type=1 THEN pa.discount ELSE round(pa.discount*100/(pp.qty*(pp.unit_price/(1+pp.freight_insurance/100)/(1+pp.gst/100))),2) END ) /100)*(1+(pp.freight_insurance/100))*(1+(pp.gst/100)))) as total, po.warranty, po.default_warranty, p.dp*pp.qty as dp
                from contract_note cn
                INNER JOIN contract_note_po_revision cr ON cr.contract_note_id = cn.contract_note_id
                INNER JOIN po_revision pr ON pr.po_revision_id = cr.po_revision_id 
                INNER JOIN purchase_order po ON po.purchase_order_id = pr.purchase_order_id
                INNER JOIN po_products pp ON pp.purchase_order_id = po.purchase_order_id
                INNER JOIN po_product_approval pa ON pa.po_revision_id = pr.po_revision_id  AND pa.product_id = pp.product_id
                INNER JOIN product p ON pp.product_id = p.product_id
                WHERE cn.contract_note_id = '".$contract_note_id."'";
        $r = $this->db->query($q);
        return  $r->result_array();
    }

    function getCNotePoApprovalDetails($contract_note_id)
    {
        $this->db->select('pa.*,p.description as product');
        $this->db->from('po_product_approval pa');
        $this->db->join('contract_note_po_revision cnqr','pa.po_revision_id = cnqr.po_revision_id');
        $this->db->join('po_revision pr','pr.po_revision_id = cnqr.po_revision_id');
        $this->db->join('product p','p.product_id = pa.product_id','left');
        $this->db->where('cnqr.contract_note_id',$contract_note_id);
        $this->db->order_by('pa.po_revision_id asc,pa.product_id asc');
        $res = $this->db->get();
        if($res->num_rows()>0)
        {
            return $res->result_array();
        }
    }

    function getCNotePoApprovalHistory($contract_note_id)
    {
        $this->db->select('mah.*,CONCAT(u.first_name," ",u.last_name) as approval_by,ma.product_id');
        $this->db->from('po_product_approval_history mah');
        $this->db->join('po_product_approval ma','ma.approval_id = mah.approval_id');
        $this->db->join('contract_note_po_revision cnqr','ma.po_revision_id = cnqr.po_revision_id');
        $this->db->join('po_revision qr','qr.po_revision_id = cnqr.po_revision_id');
        $this->db->join('user u','mah.created_by = u.user_id','left');
        $this->db->where('cnqr.contract_note_id',$contract_note_id);
        $this->db->order_by('ma.po_revision_id asc,ma.product_id asc');
        $res = $this->db->get();
        if($res->num_rows()>0)
        {
            return $res->result_array();
        }
    }

    function getDistributors()
    {
        $this->db->select('d.distributor_name,u.user_id,u.employee_id,CONCAT(u.first_name," ",u.first_name) as user_name');
        $this->db->from('user u');
        $this->db->join('distributor_details d','d.user_id = u.user_id');
        $this->db->join('user_location ul','ul.user_id = u.user_id AND ul.status = 1');
        $this->db->join('location l1','l1.location_id = ul.location_id');
        $this->db->join('location l2','l2.parent_id = l1.location_id','left');
        $this->db->join('location l3','l3.parent_id = l2.location_id','left');
        $this->db->join('location l4','l4.parent_id = l3.location_id','left');
        $this->db->where('u.company_id',$this->session->userdata('company'));
        $this->db->where('((l1.location_id IN ('.$this->session->userdata('locationString').')) OR (l2.location_id IN ('.$this->session->userdata('locationString').')) OR (l3.location_id IN ('.$this->session->userdata('locationString').')) OR (l4.location_id IN ('.$this->session->userdata('locationString').')))');
        $this->db->group_by('u.user_id');
        $res = $this->db->get();
        if($res->num_rows()>0)
        {
            return $res->result_array();
        }
    }

    function getLatestRevisionDetails($quote_id)
    {
        $this->db->from('quote_revision');
        $this->db->where('quote_id',$quote_id);
        $this->db->order_by('quote_revision_id','desc');
        $this->db->limit(1);
        $res = $this->db->get();
        if($res->num_rows()>0)
        {
            return $res->row_array();
        }
    }

    function getPreviousRevisionOpportunityDetails($quote_revision_id)
    {
        $this->db->select('*');
        $this->db->from('quote_op_margin_approval');
        $this->db->where('quote_revision_id',$quote_revision_id);

    }
    /* Phase2 Update
    ** Mahesh created: 16-08-2017 updated: 
    ** END
    */
    //get company string 
    public function get_company_lable_details($quotation_id)
    {
        $this->db->select('case when cp.type=1 then concat("M/s ",cp.name,"., ",cp.city) else concat("M/s ",c.name,"., ",c.city) end as company_label  ');
        $this->db->from('quote q');
        $this->db->join('channel_partner cp','q.channel_partner_id=cp.channel_partner_id');
        $this->db->join('company c','cp.company_id=c.company_id');
        $this->db->where('q.quote_id',$quotation_id);
        $res = $this->db->get();
        return $res->row_array();
    }
    public function check_opportunity_currency($opportunities)
    {
        $this->db->select('currency_id');
        $this->db->from('opportunity_product');
        $this->db->where('opportunity_id IN ('.$opportunities.') ');
        $res=$this->db->get();
        $results = $res->result_array();
        $opp=array();
        foreach ($results as $row) {
           $opp[]=$row['currency_id'];
        }
        $unique_type = array_unique($opp);
        if(count($unique_type)==1)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    public function currency_availablity_check($opportunities)
    {   
        $default_currency = $this->Common_model->get_value('company',array('company_id'=>$this->session->userdata('company'),'status'=>1),'currency_id');
        $this->db->select('currency_id');
        $this->db->from('opportunity_product');
        $this->db->where('opportunity_id IN ('.$opportunities.') ');
        $res=$this->db->get();
        $results = $res->result_array();
        $opp=array();
        foreach ($results as $row) {
           $opp[]=$row['currency_id'];
        }
        $unique_type = array_unique($opp);
        if(count($unique_type)==1)
        {
            if($unique_type[0]==$default_currency)
            {
                return 1;
            }
            else
            {
                $factor=$this->Common_model->get_value('currency_transaction',array('from_currency_id'=>$unique_type[0],'to_currency_id'=>$default_currency,'status'=>1),'value');
                if($factor=='')
                {
                    return 0;
                }
                else
                {
                   
                    return 1;
                }   
            }
        }
        else
        {
            return 0;
        }
    }
    public function getQuoteDetailsByLead_api($lead_id,$quote_id,$quote_revision_id)
    {
        $this->db->select('q.quote_id as quote_id,qr.quote_revision_id, qr.discount as discount, qd.mrp, o.required_quantity , q.status,
                concat(p.name, " - ", p.description, " (Qty -", o.required_quantity, ")")
                as opportunity, qr.status as quote_revision_status, q.warranty, q.advance_type, q.advance, q.balance_payment_days, q.dealer_commission,
                ma.approval_at,ma.close_at,ma.status as approval_status,ma.discount as opp_discount,ma.discount_type as opp_discount_type,
                qr.created_time,q.quote_number,o.opportunity_id');
        $this->db->from('quote q');
        $this->db->join('quote_revision qr','q.quote_id = qr.quote_id','INNER');
        $this->db->join('quote_details qd','q.quote_id = qd.quote_id','INNER');
        $this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id','INNER');
        $this->db->join('opportunity_product op','o.opportunity_id = op.opportunity_id','INNER');
        $this->db->join('product p','op.product_id = p.product_id','INNER');
        $this->db->join('quote_op_margin_approval ma','ma.opportunity_id = o.opportunity_id AND ma.quote_revision_id = qr.quote_revision_id','LEFT');
        $this->db->where('o.lead_id',$lead_id);
        $this->db->where('q.quote_id',$quote_id);
        $this->db->where('qr.quote_revision_id',$quote_revision_id);
        $this->db->where('qr.status in (1,3)');
        $this->db->order_by('q.quote_id desc, qr.quote_revision_id desc, qr.status desc');
        $res1 = $this->db->get(); 
        if($res1->num_rows()>0)
        {       

            return $res1->result_array();
        }
    }

}