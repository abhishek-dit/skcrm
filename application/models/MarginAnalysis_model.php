<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MarginAnalysis_model extends CI_Model {

   public function getOpportunitiesForApproval($start, $len, $searchParams)
    {
        // added on 10-05-2021 for getting location id
        $this->db->select('ul.location_id');
        $this->db->from('user_location ul');
        $this->db->where('ul.user_id',$_SESSION['user_id']);
        $this->db->where('ul.status',1);
        $res_l = $this->db->get();
        $location_id = $res_l->row_array();
        // echo "<pre>";print_r($location_id);die;
        // end



        $repotees = $this->session->userdata('reportees');
        $prod = $this->session->userdata('products');
        $role_id = $this->session->userdata('role_id');
        if($prod == '') $prod = 0;
        $qry = 'SELECT * FROM ( SELECT ma.*,q.quote_id as quote_id,qr.created_by as qr_created_by,qr.created_time as quote_revision_time,o.opp_number,
                 concat(t.tag, "-", case when month(curdate()) < 4 then substr(year(curdate()) - 1, 3,4) 
                else substr(year(curdate()), 3,4) end, "-",q.quote_number) as tag,
                concat("ID - ", o.opp_number, " : ", p.name, " - ", p.description, " (Qty -", o.required_quantity, ")") 
                as opportunity, p.product_id, 
                concat( p.description, " (Qty -", o.required_quantity, ")") 
                as opp_details, t.lead_owner, o.required_quantity*qd.mrp as mrp,o.required_quantity*p.base_price as base_price,o.required_quantity*p.dp as dp, qr.advance_type, qr.advance, qr.balance_payment_days, qd.freight_insurance, qd.gst,
                r.name as approval_at_role , t.lead_id, t.lead_number, o.opportunity_id as opp_id,t.customer,t.lead_owner_name,t.owner_emp_id, t.lead_distributor_name,
                d.distributor_name, t.region,cur.code as currency_code
                FROM quote_op_margin_approval ma
                INNER JOIN opportunity o ON ma.opportunity_id = o.opportunity_id
                INNER JOIN quote_revision qr ON qr.quote_revision_id = ma.quote_revision_id AND qr.status = 3
                INNER JOIN quote q ON q.quote_id = qr.quote_id
                INNER JOIN quote_details qd ON q.quote_id = qd.quote_id AND qd.opportunity_id = o.opportunity_id
                INNER JOIN currency cur ON cur.currency_id = qd.currency_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN role r ON r.role_id = ma.approval_at
                LEFT JOIN distributor_details d ON d.user_id = qr.dealer_id
                INNER JOIN product p ON p.product_id = op.product_id  ';
                //$qry.= ' AND p.product_id IN ('.$prod.') ';
                $qry.= ' INNER JOIN (
                    SELECT q.quote_id, l.lead_id,l.lead_number,l.user_id as lead_owner, l3.tag, c.name as customer, CONCAT(u.first_name," ",u.last_name) as lead_owner_name,
                    u.employee_id as owner_emp_id , dd.distributor_name as lead_distributor_name, l4.location as region
                    FROM quote q
                    INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                    INNER JOIN quote_revision qr ON q.quote_id = qr.quote_id
                    INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                    INNER JOIN lead l ON l.lead_id = o.lead_id
                    INNER JOIN user u ON l.user_id = u.user_id
                    INNER JOIN customer c ON l.customer_id = c.customer_id
                    INNER JOIN location l1 ON l1.location_id = l.location_id
                    INNER JOIN location l2 ON l2.location_id = l1.parent_id
                    INNER JOIN location l3 ON l3.location_id = l2.parent_id
                    INNER JOIN location l4 ON l4.location_id = l3.parent_id
                    INNER JOIN customer_location cl ON cl.customer_id = l.customer_id
                    LEFT JOIN distributor_details dd ON dd.user_id = l.user2
                    INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                    INNER JOIN quote_op_margin_approval ma ON ma.quote_revision_id = qr.quote_revision_id AND ma.opportunity_id = o.opportunity_id
                    
                    AND ma.status = 1 AND ma.approval_at >= '.$role_id.'
                    AND o.company_id = '.$this->session->userdata('company').'
                    AND op.product_id IN ('.$prod.') ';
                    // WHERE cl.location_id IN ('.$this->session->userdata('locationString').')
                    
                    if($role_id == 7)
                    {
                        if($searchParams['ma_region']!='')
                        {
                            $qry .= ' AND l4.location_id = '.$searchParams['ma_region'].' ';
                        }
                        elseif($location_id['location_id'] != '')
                        {
                            $qry .= 'WHERE l4.location_id IN ('.$location_id['location_id'].')';
                        }
                        else
                        {
                            $qry .= 'WHERE cl.location_id IN ('.$this->session->userdata('locationString').')';
                        }
                    }
                    else
                    {
                        if($searchParams['ma_region']!='')
                        {
                            $qry .= ' AND l4.location_id = '.$searchParams['ma_region'].' ';
                        }
                        else
                        {
                            $qry .= 'WHERE cl.location_id IN ('.$this->session->userdata('locationString').')';
                        }
                    }
                    
                    $qry .= 'group by q.quote_id
                ) t ON t.quote_id = q.quote_id ';

           
        //$qry .= '  AND q.created_by IN ('.$repotees.',1 ) ';
         
        $qry .= "   ) table1";
        if (count($searchParams) > 0) {
            if ($searchParams['quote_id'] != '')
                $qry .=" WHERE table1.tag  like ('%" . $this->db->escape_like_str($searchParams['quote_id']) . "%') ";
            if ($searchParams['opportunity_details'] != '')
                $qry .=" WHERE table1.opportunity  like ('%" . $this->db->escape_like_str($searchParams['opportunity_details']) . "%') ";
        }
        $conditionApproval = $this->Common_model->get_data('condition_approval_mail', array('condition_approval_mail_id'=>1));		
        if($conditionApproval[0]['condition'] == 1){
            if (count($searchParams) > 0) {
                if ($searchParams['quote_id'] != '' || $searchParams['opportunity_details'] != ''){
                    $qry .= " and table1.status = 1  group by table1.quote_id ";
                }else{
                    $qry .= " WHERE table1.status = 1  group by table1.quote_id ";
                }

            }else{
                $qry .= " WHERE table1.status = 1  group by table1.quote_id ";
            }
        }
            $qry .="   order by table1.quote_revision_time desc, table1.quote_id desc, table1.status asc, table1.opportunity ";
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;

    //    echo '<pre>';print_r($qry); die();
        $res1 = $this->db->query($qry);
        $res2 = $this->db->query($num_qry);
        $res['resArray'] = $res1->result_array();
        $qry_data = $res1->result();
        // echo '<pre>'; print_r($this->db->last_query()); exit;
        $res['count'] = $res2->num_rows();
        
        
        return $res;
    }

    /*public function getOpportunitiesForApproval($start, $len, $searchParams)
    {
        $repotees = $this->session->userdata('reportees');
        $prod = $this->session->userdata('products');
        $role_id = $this->session->userdata('role_id');
        if($prod == '') $prod = 0;
        $qry = 'SELECT * FROM ( SELECT ma.*,q.quote_id as quote_id,qr.created_by as qr_created_by,qr.created_time as quote_revision_time,o.opp_number,
                 concat(t.tag, "-", case when month(curdate()) < 4 then substr(year(curdate()) - 1, 3,4) 
                else substr(year(curdate()), 3,4) end, "-",q.quote_number) as tag,
                concat("ID - ", o.opp_number, " : ", p.name, " - ", p.description, " (Qty -", o.required_quantity, ")") 
                as opportunity, p.product_id, 
                concat( p.description, " (Qty -", o.required_quantity, ")") 
                as opp_details, t.lead_owner, o.required_quantity*qd.mrp as mrp,o.required_quantity*p.base_price as base_price,o.required_quantity*p.dp as dp, qr.warranty, qr.advance_type, qr.advance, qr.balance_payment_days, qr.dealer_commission, qd.freight_insurance, qd.gst,
                r.name as approval_at_role , t.lead_id, o.opportunity_id as opp_id,t.customer,t.lead_owner_name,t.owner_emp_id, t.lead_distributor_name,
                d.distributor_name, t.region,cur.code as currency_code
                FROM quote_op_margin_approval ma
                INNER JOIN opportunity o ON ma.opportunity_id = o.opportunity_id
                INNER JOIN quote_revision qr ON qr.quote_revision_id = ma.quote_revision_id AND qr.status = 3
                INNER JOIN quote q ON q.quote_id = qr.quote_id
                INNER JOIN quote_details qd ON q.quote_id = qd.quote_id AND qd.opportunity_id = o.opportunity_id
                INNER JOIN currency cur ON cur.currency_id = qd.currency_id
                INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                INNER JOIN role r ON r.role_id = ma.approval_at
                LEFT JOIN distributor_details d ON d.user_id = qr.dealer_id
                INNER JOIN product p ON p.product_id = op.product_id  ';
                //$qry.= ' AND p.product_id IN ('.$prod.') ';
                $qry.= ' INNER JOIN (
                    SELECT q.quote_id, l.lead_id,l.user_id as lead_owner, l3.tag, c.name as customer, CONCAT(u.first_name," ",u.last_name) as lead_owner_name,
                    u.employee_id as owner_emp_id , dd.distributor_name as lead_distributor_name, l4.location as region
                    FROM quote q
                    INNER JOIN quote_details qd ON qd.quote_id = q.quote_id
                    INNER JOIN quote_revision qr ON q.quote_id = qr.quote_id
                    INNER JOIN opportunity o ON o.opportunity_id = qd.opportunity_id
                    INNER JOIN lead l ON l.lead_id = o.lead_id
                    INNER JOIN user u ON l.user_id = u.user_id
                    INNER JOIN customer c ON l.customer_id = c.customer_id
                    INNER JOIN location l1 ON l1.location_id = l.location_id
                    INNER JOIN location l2 ON l2.location_id = l1.parent_id
                    INNER JOIN location l3 ON l3.location_id = l2.parent_id
                    INNER JOIN location l4 ON l4.location_id = l3.parent_id
                    INNER JOIN customer_location cl ON cl.customer_id = l.customer_id
                    LEFT JOIN distributor_details dd ON dd.user_id = l.user2
                    INNER JOIN opportunity_product op ON op.opportunity_id = o.opportunity_id
                    INNER JOIN quote_op_margin_approval ma ON ma.quote_revision_id = qr.quote_revision_id AND ma.opportunity_id = o.opportunity_id
                    WHERE cl.location_id IN ('.$this->session->userdata('locationString').')
                    AND ma.status = 1 AND ma.approval_at >= '.$role_id.'
                    AND o.company_id = '.$this->session->userdata('company').'
                    AND op.product_id IN ('.$prod.') ';
                    if($searchParams['ma_region']!='')
                    {
                        $qry .= ' AND l4.location_id = '.$searchParams['ma_region'].' ';
                    }
                    $qry .= 'group by q.quote_id
                ) t ON t.quote_id = q.quote_id ';

           
        //$qry .= '  AND q.created_by IN ('.$repotees.',1 ) ';
         
        $qry .= "   ) table1";
        if (count($searchParams) > 0) {
            if ($searchParams['quote_id'] != '')
                $qry .=" WHERE table1.tag  like ('%" . $this->db->escape_like_str($searchParams['quote_id']) . "%') ";
            if ($searchParams['opportunity_details'] != '')
                $qry .=" WHERE table1.opportunity  like ('%" . $this->db->escape_like_str($searchParams['opportunity_details']) . "%') ";
        }
            $qry .="   order by table1.quote_revision_time desc, table1.quote_id desc, table1.status asc, table1.opportunity ";
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;

       //echo $qry; die();
        $res1 = $this->db->query($qry);
        $res2 = $this->db->query($num_qry);
        $res['resArray'] = $res1->result_array();
        $qry_data = $res1->result();
        //echo '<pre>'; print_r($res['resArray']); exit;
        $res['count'] = $res2->num_rows();
        return $res;
    }*/

    public function getTotalQuoteRows($searchParams)
    {
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');
        $this->db->from('quote q');
        $this->db->join('quote_details qd','q.quote_id = qd.quote_id','INNER');
        $this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id','INNER');
        $this->db->join('opportunity_product op','o.opportunity_id = op.opportunity_id','INNER');
        $this->db->join('product p','op.product_id = p.product_id','INNER');
        $this->db->join('lead l','l.lead_id = o.lead_id','INNER');
        $this->db->join('customer_location cl','l.customer_id = cl.customer_id','LEFT');
        $this->db->join('customer c','l.customer_id = c.customer_id','LEFT');
        if($role_id==4||$role_id==5) // IF SE
        {
            $this->db->where('l.user_id',$user_id);
        }
        $this->db->where('cl.location_id IN ('.$this->session->userdata('locationString').')');
        $this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
        if($searchParams['quote_id']!='')
            $this->db->where('q.quote_id',$searchParams['quote_id']);
        if($searchParams['opportunity_details']!='')
            $this->db->where('( p.name LIKE "%'.$searchParams['opportunity_details'].'%" OR p.description LIKE "%'.$searchParams['opportunity_details'].'%" )');
        if($searchParams['customer_name']!='')
            $this->db->like('c.name',$searchParams['customer_name']);
        $this->db->where('q.company_id',$this->session->userdata('company'));
        $this->db->group_by('q.quote_id');
        $res = $this->db->get();
        return $res->num_rows();
    }

    public function getQuoteResults($current_offset, $per_page,$searchParams)
    {
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');
        $this->db->select('q.quote_id');
        $this->db->from('quote q');
        $this->db->join('quote_details qd','q.quote_id = qd.quote_id','LEFT');
        $this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id','LEFT');
        $this->db->join('opportunity_product op','o.opportunity_id = op.opportunity_id','LEFT');
        $this->db->join('product p','op.product_id = p.product_id','INNER');
        $this->db->join('lead l','l.lead_id = o.lead_id','INNER');
        $this->db->join('customer_location cl','l.customer_id = cl.customer_id','LEFT');
        $this->db->join('customer c','l.customer_id = c.customer_id','LEFT');
        if($role_id==4||$role_id==5) // IF SE
        {
            $this->db->where('l.user_id',$user_id);
        }
        $this->db->where('cl.location_id IN ('.$this->session->userdata('locationString').')');
        $this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
        if($searchParams['quote_id']!='')
            $this->db->where('q.quote_number',$searchParams['quote_id']);
        if($searchParams['opportunity_details']!='')
            $this->db->where('( p.name LIKE "%'.$searchParams['opportunity_details'].'%" OR p.description LIKE "%'.$searchParams['opportunity_details'].'%" )');
        if($searchParams['customer_name']!='')
            $this->db->like('c.name',$searchParams['customer_name']);
        $this->db->where('q.company_id',$_SESSION['company']);
        $this->db->group_by('q.quote_id');
        $this->db->order_by('q.quote_id','DESC');
        $this->db->limit($per_page, $current_offset);
        $res = $this->db->get();
        if($res->num_rows()>0)
        {
            $rows = $res->result_array();
            $arr = array();
            foreach ($rows as $row) {
                $arr[] = $row['quote_id'];
            }
            return implode(',', $arr);
        }
        return 0;
    }

    public function getQuotesList($current_offset, $per_page,$searchParams)
    {
        //echo $current_offset.'--'.$per_page.'<br>';
        $quote_ids = $this->getQuoteResults($current_offset, $per_page,$searchParams);
        //echo $this->db->last_query().'<br>'; echo $quote_ids; exit;
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');
       /* $this->db->select('q.quote_id as quote_id,qr.quote_revision_id, qr.discount as discount, qd.mrp, q.status,
                concat(p.name, " - ", p.description, " (Qty -", o.required_quantity, ")")
                as opportunity, qr.status as quote_revision_status,
                ma.approval_at,ma.close_at,ma.status as approval_status,ma.discount as opp_discount,ma.discount_type as opp_discount_type,
                qr.created_time');
        $this->db->from('quote q');
        $this->db->join('quote_revision qr','q.quote_id = qr.quote_id AND CASE WHEN q.status=6 THEN qr.status = 3 ELSE qr.status = 1 END ','INNER');
        $this->db->join('quote_details qd','q.quote_id = qd.quote_id','INNER');
        $this->db->join('opportunity o','o.opportunity_id = qd.opportunity_id','INNER');
        $this->db->join('opportunity_product op','o.opportunity_id = op.opportunity_id','INNER');
        $this->db->join('product p','op.product_id = p.product_id','INNER');
        $this->db->join('quote_op_margin_approval ma','ma.opportunity_id = o.opportunity_id AND ma.quote_revision_id = qr.quote_revision_id','LEFT');
        $this->db->join('lead l','l.lead_id = o.lead_id','INNER');
        $this->db->join('customer_location cl','l.customer_id = cl.customer_id','INNER');
        if($role_id==4) // IF SE
        {
            $this->db->where('l.user_id',$user_id);
        }
        $this->db->where('cl.location_id IN ('.$this->session->userdata('locationString').')');
        $this->db->where('op.product_id IN ('.$this->session->userdata('products').')');
        $this->db->order_by('q.quote_id desc');*/

        $qry = 'SELECT `q`.`quote_id` as `quote_id`, `q`.`quote_number`, `qr`.`quote_revision_id`, `qr`.`discount` as `discount`,
                 `qd`.`mrp`, `q`.`status`, concat(`p`.`name`, " - ", `p`.`description`, 
                 " (Qty -", `o`.`required_quantity`, ")") as opportunity, `qr`.`status` as `quote_revision_status`, 
                 `ma`.`approval_at`, `ma`.`close_at`, `ma`.`status` as `approval_status`, `ma`.`discount` as `opp_discount`,
                 `ma`.`discount_type` as `opp_discount_type`, `qr`.`created_time` ,`l`.`lead_id`,`l`.`user_id` as `lead_user_id`,`l`.`status` as `leadStatusID`,
                 `c`.`name` as `customer_name`, `q`.`created_time`, `q`.`modified_time`
                 FROM `quote` `q` 
                 INNER JOIN `quote_revision` `qr` ON `q`.`quote_id` = `qr`.`quote_id` 
                 INNER JOIN `quote_details` `qd` ON `q`.`quote_id` = `qd`.`quote_id` 
                 INNER JOIN `opportunity` `o` ON `o`.`opportunity_id` = `qd`.`opportunity_id` 
                 INNER JOIN `opportunity_product` `op` ON `o`.`opportunity_id` = `op`.`opportunity_id` 
                 INNER JOIN `product` `p` ON `op`.`product_id` = `p`.`product_id` 
                 LEFT JOIN `quote_op_margin_approval` `ma` ON `ma`.`opportunity_id` = `o`.`opportunity_id` AND `ma`.`quote_revision_id` = `qr`.`quote_revision_id` 
                 INNER JOIN `lead` `l` ON `l`.`lead_id` = `o`.`lead_id` 
                 INNER JOIN `customer_location` `cl` ON `l`.`customer_id` = `cl`.`customer_id` 
                 INNER JOIN `customer` `c` ON `l`.`customer_id` = `c`.`customer_id` 
                 WHERE `cl`.`location_id` IN ('.$this->session->userdata('locationString').') 
                 AND `op`.`product_id` IN ('.$this->session->userdata('products').') 
                 AND `q`.`quote_id` IN ('.$quote_ids.')';
        if($role_id==4) // IF SE
        {
            $qry .= ' AND `l`.`user_id` = '.$user_id.' ';
        }
        $qry .= ' ORDER BY `q`.`quote_id` desc, qr.quote_revision_id desc ';
        //$qry .= 'limit'.$current_offset.','.$per_page;
        $res1 = $this->db->query($qry); 
        if($res1->num_rows()>0)
        {       

            return $res1->result_array();
        }
    }

   public function getPendingPoApprovalTotalRows($searchParams)
    {
        
        $role_id = $this->session->userdata('role_id');
        $this->db->from('purchase_order po');
        $this->db->join('po_products pp','po.purchase_order_id = pp.purchase_order_id','INNER');
        $this->db->join('product p','p.product_id = pp.product_id','INNER');
        $this->db->join('distributor_details d','d.user_id = po.user_id','LEFT');
        $this->db->join('user u','po.user_id=u.user_id','LEFT');
        $this->db->join('currency cur','cur.currency_id=pp.currency_id');
        $this->db->join('po_revision pr','pr.purchase_order_id = po.purchase_order_id AND pr.status = 1','INNER');
        $this->db->join('po_product_approval pa','pa.po_revision_id = pr.po_revision_id AND pa.product_id = pp.product_id','INNER');
        $this->db->join('user_location ul','u.user_id=ul.user_id');
        //$this->db->where('ul.location_id IN ('.$this->session->userdata('locationString').')');
        $this->db->where('po.user_id IN ('.$this->session->userdata('reportees').')');
        $this->db->where('p.product_id IN ('.$this->session->userdata('products').')');
        $this->db->where('ul.status',1);
        $this->db->where('pa.status',1);
        $this->db->where('pa.approval_at>=',$role_id);
        $this->db->where('po.company_id',$this->session->userdata('company'));

        if($searchParams['purchase_order_id']!='')
        $this->db->where('po.po_number',$searchParams['purchase_order_id']);
        if($searchParams['product_details']!='')
        $this->db->where('(p.name LIKE "%'.$searchParams['product_details'].'%" OR p.description LIKE "%'.$searchParams['product_details'].'%")');
        $this->db->group_by('pp.product_id');
        $res = $this->db->get();
        return $res->num_rows();
    }

    public function getPendingPoApprovalList($current_offset, $per_page, $searchParams)
    {
        $role_id = $this->session->userdata('role_id');
        $this->db->select('po.*, concat(p.name, " - ", p.description, " (Qty -", pp.qty, ")") as product_details, (pp.unit_price*pp.qty) as mrp,
            ( CASE WHEN pa.discount_type = 1 THEN (pp.qty*pp.unit_price)*(1-pa.discount/100) ELSE (pp.qty*pp.unit_price)-pa.discount END ) as order_value,
            d.distributor_name, concat(u.first_name," ",u.last_name," (",u.employee_id,")") as user,
            (pp.qty*pp.dp) as dp,(pp.qty*p.base_price) as base_price,pa.discount,pa.discount_type,pa.approval_at,pa.close_at,pa.approval_id,pp.product_id,pr.po_revision_id,pp.freight_insurance,pp.gst,cur.code as currency_code');
        $this->db->from('purchase_order po');
        $this->db->join('po_products pp','po.purchase_order_id = pp.purchase_order_id','INNER');
        $this->db->join('product p','p.product_id = pp.product_id','INNER');
        $this->db->join('distributor_details d','d.user_id = po.user_id','LEFT');
        $this->db->join('user u','po.user_id=u.user_id','LEFT');
        $this->db->join('currency cur','cur.currency_id=pp.currency_id');
        $this->db->join('po_revision pr','pr.purchase_order_id = po.purchase_order_id AND pr.status = 1','INNER');
        $this->db->join('po_product_approval pa','pa.po_revision_id = pr.po_revision_id AND pa.product_id = pp.product_id','INNER');
        $this->db->join('user_location ul','u.user_id=ul.user_id');
        //$this->db->where('ul.location_id IN ('.$this->session->userdata('locationString').')');
        $this->db->where('po.user_id IN ('.$this->session->userdata('reportees').')');
        $this->db->where('p.product_id IN ('.$this->session->userdata('products').')');
        $this->db->where('ul.status',1);
        $this->db->where('pa.status',1);
        $this->db->where('pa.approval_at>=',$role_id);
        $this->db->where('po.company_id',$this->session->userdata('company'));
        
        if($searchParams['purchase_order_id']!='')
        $this->db->where('po.po_number',$searchParams['purchase_order_id']);
        if($searchParams['product_details']!='')
        $this->db->where('(p.name LIKE "%'.$searchParams['product_details'].'%" OR p.description LIKE "%'.$searchParams['product_details'].'%")');
        $this->db->group_by('pa.approval_id');
        $this->db->order_by('po.purchase_order_id desc, p.description asc');
        $this->db->limit($per_page, $current_offset);
        $res = $this->db->get();
      //  echo $this->db->last_query();exit;
        return $res->result_array();
    }

    public function getPoDistributorDetails($purchase_order_id)
    {
        $this->db->select('d.distributor_name,u.employee_id,u.email_id,u.first_name,u.last_name,po.created_time,po.purchase_order_id,u.user_id');
        $this->db->from('purchase_order po');
        $this->db->join('user u','po.user_id = u.user_id','LEFT');
        $this->db->join('distributor_details d','d.user_id=po.user_id','LEFT');
        $this->db->where('po.purchase_order_id',$purchase_order_id);
        $res = $this->db->get();
        if($res->num_rows()>0)
            return $res->row_array();
    }

    public function getTotalPoRows($searchParams)
    {
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');
        $this->db->from('purchase_order po');
        $this->db->join('user_location ul','ul.user_id = po.user_id');
        $this->db->join('po_products pp','pp.purchase_order_id = po.purchase_order_id');
        $this->db->join('product p','pp.product_id = p.product_id');
        $this->db->join('distributor_details d','d.user_id = po.user_id');
        if($role_id==5) // IF Distributor
        {
            $this->db->where('po.user_id',$user_id);
        }
        else
        {
            //$this->db->where('ul.location_id IN ('.$this->session->userdata('locationString').')');
            $this->db->where('po.user_id IN ('.$this->session->userdata('reportees').')');
        }
        $this->db->where('pp.product_id IN ('.$this->session->userdata('products').')');
        if($searchParams['purchase_order_id']!='')
            $this->db->where('po.purchase_order_id',$searchParams['purchase_order_id']);
        if($searchParams['product_details']!='')
            $this->db->where('( p.name LIKE "%'.$searchParams['product_details'].'%" OR p.description LIKE "%'.$searchParams['product_details'].'%" )');
        if($searchParams['distributor_name']!='')
            $this->db->like('d.distributor_name',$searchParams['distributor_name']);
        if($searchParams['po_status']!='')
            $this->db->where('po.status',$searchParams['po_status']);
        $this->db->where('po.company_id',$this->session->userdata('company'));
        $this->db->group_by('po.purchase_order_id');
        $res = $this->db->get();
        return $res->num_rows();
    }

    public function getPoResults($current_offset, $per_page,$searchParams)
    {
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');
        $this->db->select('po.purchase_order_id');
        $this->db->from('purchase_order po');
        $this->db->join('user_location ul','ul.user_id = po.user_id');
        $this->db->join('po_products pp','pp.purchase_order_id = po.purchase_order_id');
        $this->db->join('product p','pp.product_id = p.product_id');
        $this->db->join('distributor_details d','d.user_id = po.user_id');
        if($role_id==5) // IF Distributor
        {
            $this->db->where('po.user_id',$user_id);
        }
        else
        {   
            //$this->db->where('ul.location_id IN ('.$this->session->userdata('locationString').')');
            $this->db->where('po.user_id IN ('.$this->session->userdata('reportees').')');
        }
        $this->db->where('pp.product_id IN ('.$this->session->userdata('products').')');
        if($searchParams['purchase_order_id']!='')
            $this->db->where('po.po_number',$searchParams['purchase_order_id']);
        if($searchParams['product_details']!='')
            $this->db->where('( p.name LIKE "%'.$searchParams['product_details'].'%" OR p.description LIKE "%'.$searchParams['product_details'].'%" )');
        if($searchParams['distributor_name']!='')
            $this->db->like('d.distributor_name',$searchParams['distributor_name']);
        if($searchParams['po_status']!='')
            $this->db->where('po.status',$searchParams['po_status']);
        $this->db->group_by('po.purchase_order_id');
        $this->db->order_by('po.purchase_order_id','DESC');
        $this->db->limit($per_page, $current_offset);
        $res = $this->db->get();
        if($res->num_rows()>0)
        {
            $rows = $res->result_array();
            $arr = array();
            foreach ($rows as $row) {
                $arr[] = $row['purchase_order_id'];
            }
            return implode(',', $arr);
        }
        return 0;
    }

    public function getPoList($current_offset, $per_page,$searchParams)
    {
        //echo $current_offset.'--'.$per_page.'<br>';
        $purchase_order_ids = $this->getPoResults($current_offset, $per_page,$searchParams);
        //echo $this->db->last_query().'<br>'; echo $purchase_order_ids; exit;
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');

        $this->db->select('po.purchase_order_id,po.po_number, CONCAT(p.name," - ",p.description," (Qty - ",pp.qty,")") as product_details, po.status as po_status,
         pa.approval_at,pa.close_at,pa.status as approval_status, pp.qty,pp.unit_price,
            (CASE WHEN pa.discount_type = 1 THEN pa.discount ELSE pa.discount*100/(pp.qty*pp.unit_price) END) discount_percentage, d.distributor_name');
        $this->db->from('purchase_order po');
        $this->db->join('po_products pp','pp.purchase_order_id = po.purchase_order_id','INNER');
        $this->db->join('product p','pp.product_id = p.product_id','INNER');
        $this->db->join('po_revision pr','pr.purchase_order_id = po.purchase_order_id AND pr.status = 1','INNER');
        $this->db->join('po_product_approval pa','pa.po_revision_id = pr.po_revision_id AND pa.product_id = pp.product_id','INNER');
        $this->db->join('distributor_details d','po.user_id = d.user_id');
        $this->db->where('po.purchase_order_id IN ('.$purchase_order_ids.')');
        $this->db->where('po.company_id',$this->session->userdata('company'));
        $this->db->order_by('po.purchase_order_id','DESC');
        $res1 = $this->db->get(); 
        if($res1->num_rows()>0)
        {       

            return $res1->result_array();
        }
    }

    public function getMarginApprovalInfo($margin_approval_id)
    {
        if($margin_approval_id!='')
        {
            // $this->db->select('ma.margin_approval_id,ma.status,ma.quote_revision_id,ma.opportunity_id,ma.approval_at,ma.close_at,l.lead_id,l.user_id as lead_owner,qr.quote_id, 
            //     CONCAT("ID - ",o.opportunity_id," : ",p.name," - ",p.description," (Qty -",o.required_quantity,")") as opportunity_details');
            $this->db->select('ma.margin_approval_id,ma.status,ma.quote_revision_id,ma.opportunity_id,o.opp_number,ma.approval_at,ma.close_at,l.lead_id,l.lead_number,l.user_id as lead_owner,qr.quote_id, 
                CONCAT(" : ",p.name," - ",p.description," (Qty -",o.required_quantity,")") as opportunity_details,(CASE WHEN l.lead_id is NOT NULL OR "" THEN c.name END) as customer_name');
            $this->db->from('quote_op_margin_approval ma');
            $this->db->join('quote_revision qr','ma.quote_revision_id = qr.quote_revision_id','INNER');
            $this->db->join('opportunity o','ma.opportunity_id = o.opportunity_id','INNER');
            $this->db->join('opportunity_product op','op.opportunity_id = o.opportunity_id','INNER');
            $this->db->join('product p','p.product_id = op.product_id','INNER');
            $this->db->join('lead l','o.lead_id = l.lead_id','INNER');
            $this->db->join('customer c','c.customer_id = l.customer_id','INNER');
            $this->db->where('ma.margin_approval_id',$margin_approval_id);
            $this->db->group_by('ma.margin_approval_id');
            $res = $this->db->get();
            if($res->num_rows()>0)
            {
                return $res->row_array();
            }
        }
    }

    public function getPoApprovalInfo($approval_id)
    {
        if($approval_id!='')
        {
            $this->db->select('ma.approval_id,ma.status,ma.po_revision_id,ma.product_id,ma.approval_at,ma.close_at,pr.purchase_order_id, 
                CONCAT(p.name," - ",p.description," (Qty -",pp.qty,")") as product_details, po.user_id as distributor_id');
            $this->db->from('po_product_approval ma');
            $this->db->join('po_revision pr','ma.po_revision_id = pr.po_revision_id','INNER');
            $this->db->join('purchase_order po','po.purchase_order_id = pr.purchase_order_id','INNER');
            $this->db->join('po_products pp','po.purchase_order_id = pp.purchase_order_id','INNER');
            $this->db->join('product p','p.product_id = pp.product_id','INNER');
            $this->db->where('ma.approval_id',$approval_id);
            $this->db->group_by('ma.approval_id');
            $res = $this->db->get();
            if($res->num_rows()>0)
            {
                return $res->row_array();
            }
        }
    }
}