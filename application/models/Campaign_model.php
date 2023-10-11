<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Campaign_model extends CI_Model {

    public function get_details($start, $len, $search_fields = array()) {
        $qry = "SELECT c.*  from campaign c
                  WHERE 1 And c.company_id='".$_SESSION['company']."' ";
        // 'customerName','customerName','speciality','department','customerName','category_sub_id'

        if (count($search_fields) > 0) {

            if ($search_fields['fromDate'] != '')
                $qry .=" AND c.campaign_date  >= '" . $this->db->escape_str($search_fields['fromDate']) . "' ";
            if ($search_fields['toDate'] != '')
                $qry .=" AND c.campaign_date  <= '" . $this->db->escape_str($search_fields['toDate']) . "' ";
            if ($search_fields['campaignName'] != '')
                $qry .=" AND c.name like('" . $this->db->escape_str('%' . $search_fields['campaignName']) . "%') ";
        }
        $qry .= " order by c.campaign_id desc";
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

    public function campaignTotalRows($search_fields = array()) {
        $qry = "SELECT count(*) cnt from campaign c
                  WHERE 1 And c.company_id='".$_SESSION['company']."' ";
        // 'customerName','customerName','speciality','department','customerName','category_sub_id'

        if (count($search_fields) > 0) {

            if ($search_fields['fromDate'] != '')
                $qry .=" AND c.campaign_date  >= '" . $this->db->escape_str($search_fields['fromDate']) . "' ";
            if ($search_fields['toDate'] != '')
                $qry .=" AND c.campaign_date  <= '" . $this->db->escape_str($search_fields['toDate']) . "' ";
            if ($search_fields['campaignName'] != '')
                $qry .=" AND c.name like('" . $this->db->escape_str('%' . $search_fields['campaignName']) . "%') ";
        }
        $qry .= " order by c.campaign_id desc";
        $num_qry = $qry;
        //$qry.=" LIMIT " . $start . " , " . $len;
        //echo $qry; die();
        $res1 = $this->db->query($qry);
        $qry_data = $res1->result();

        return $qry_data[0]->cnt;
    }

    public function get_download_details($search_fields = array()) {

        $qry = "SELECT c.*  from campaign c
                  WHERE 1 And c.company_id='".$_SESSION['company']."' ";
        // 'customerName','customerName','speciality','department','customerName','category_sub_id'

        if (count($search_fields) > 0) {

            if ($search_fields['fromDate'] != '')
                $qry .=" AND c.campaign_date  >= '" . $this->db->escape_str($search_fields['fromDate']) . "' ";
            if ($search_fields['toDate'] != '')
                $qry .=" AND c.campaign_date  <= '" . $this->db->escape_str($search_fields['toDate']) . "' ";
            if ($search_fields['campaignName'] != '')
                $qry .=" AND c.name like('" . $this->db->escape_str('%' . $search_fields['campaignName']) . "%') ";
        }
        $qry .= "order by c.campaign_id desc";
        $num_qry = $qry;
        // $qry.=" LIMIT " . $start . " , " . $len;
        // echo $qry; die();
        $res1 = $this->db->query($qry);
        $res2 = $this->db->query($num_qry);
//        $res['resArray'] = $res1->result_array();
//        $qry_data = $res1->result();
//        $res['count'] = $res2->num_rows();
        return $res1->result_array();
    }

    public function getCustomerInfo() {
        $qry = "SELECT customer_id, name from customer where status = 1";
        $res = $this->db->query($qry);
        return $res->result_array();
    }

    public function getSpecialityInfo() {
        $qry = "SELECT speciality_id, name from speciality where status = 1";
        $res = $this->db->query($qry);
        return $res->result_array();
    }

    public function getCampaignData($campaign_id) {
        $qry = "SELECT c.* from campaign c where c.campaign_id = " . $campaign_id;
        $res = $this->db->query($qry);
        return $res->result_array();
    }

    public function get_emails($speciality, $location) {
        $sql = "SELECT email FROM contact c, customer_location_contact clc WHERE "
                . "c.contact_id=clc.contact_id AND "
                . " c.speciality_id=" . $this->db->escape($speciality) . " AND"
                . " clc.location_id=" . $this->db->escape($location);
        $query = $this->db->query($sql);
        return $arr = $query->result_array();
    }

    /*     * ****************************campaign documents methods ******************* */

    public function get_roles() {
        $sql = "SELECT role_id,name FROM role WHERE role_id not in (1,2,3,12,13) and status=1";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        $arr = array();
        foreach ($res as $v) {
            $arr[$v['role_id']] = $v['name'];
        }
        return $arr;
    }

    public function getCampaignDocRoles($campaign_document_id)
    {
        $ret = '';
        $q = "SELECT r.name from campaign_document_role cr 
                INNER JOIN role r ON r.role_id = cr.role_id
                WHERE campaign_document_id = '".$campaign_document_id."'";
        $r = $this->db->query($q);       
        if($r->num_rows() > 0)
        {
            $i = 0;
            foreach($r->result_array() as $row)
            {
                if($i > 0) $ret .= ', ';
                $ret .= $row['name'];
                $i++;
            }
            //return $data;
        }
        return $ret;
    }

    public function get_documents_details($start, $len, $search_fields = array()) {
        $qry = "SELECT campaign_document_id,name,description,path,status from campaign_document c 
                where 1 and c.company_id= ".$_SESSION['company'];


        if (count($search_fields) > 0) {
            if ($search_fields['campaignDocumentName'] != '')
                $qry .=" AND c.name like('" . $this->db->escape_str('%' . $search_fields['campaignDocumentName']) . "%') ";
        }
        $qry .= " order by c.campaign_document_id desc";
        $num_qry = $qry;
        $qry.=" LIMIT " . $start . " , " . $len;

        $res1 = $this->db->query($qry);
        $res2 = $this->db->query($num_qry);
        $data = $res1->result_array();
        $i = 0;
        $roles = array();
        if($res1->num_rows() > 0)
        {
            foreach($res1->result_array as $row)
            {
                $data[$i]['roles'] = $this->getCampaignDocRoles($row['campaign_document_id']);
                 
                $i++;
            }
        }
        $res['resArray'] = $data;
        $res['count'] = $res2->num_rows();
        return $res;
    }
    public function campaignDocumentTotalRows($search_fields = array()) {
        $qry = "SELECT count(*) as cnt from campaign_document c where 1 and c.company_id= ".$_SESSION['company'];


        if (count($search_fields) > 0) {
            if ($search_fields['campaignDocumentName'] != '')
                $qry .=" AND c.name like('" . $this->db->escape_str('%' . $search_fields['campaignDocumentName']) . "%') ";
        }
        $qry .= " order by c.campaign_document_id desc";

        //$qry.=" LIMIT " . $start . " , " . $len;
        //echo $qry; die();
        $res1 = $this->db->query($qry);
        $qry_data = $res1->result();

        return $qry_data[0]->cnt;
    }

    public function getCampaignDocumentsData($campaign_id) {
        $qry = "SELECT c.* from campaign_document c where c.campaign_document_id = " . $campaign_id;
        $res = $this->db->query($qry);
        return $res->result_array();
    }

    public function getCampaignDocument_roles($campaign_id) {
        $qry = "SELECT role_id from campaign_document_role c where c.campaign_document_id = " . $campaign_id;
        $res = $this->db->query($qry);
        return $res->result_array();
    }

    ///**********************FOR roles*************************************/
   public function get_documents_details_for_roles($start, $len, $search_fields = array()) {
        $qry = "SELECT c.campaign_document_id,c.name,c.description,c.path,status,c.created_by FROM"
                . " campaign_document c,campaign_document_role r "
                . "WHERE c.status = 1 AND c.campaign_document_id=r.campaign_document_id AND c.company_id=".$_SESSION['company']." and r.role_id='" . $_SESSION['role_id'] . "'";


        if (count($search_fields) > 0) {
            if ($search_fields['campaignDocumentName'] != '')
                $qry .=" AND c.name like('" . $this->db->escape_str('%' . $search_fields['campaignDocumentName']) . "%') ";
        }
        $qry .= "order by c.campaign_document_id desc";
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

   public function campaignDocumentForRolesTotalRows($search_fields = array()) {
        $qry = "SELECT count(*) as cnt FROM"
                . " campaign_document c,campaign_document_role r "
                . "WHERE c.status = 1 AND c.campaign_document_id=r.campaign_document_id AND c.company_id=".$_SESSION['company']." and r.role_id='" . $_SESSION['role_id'] . "'";

        if (count($search_fields) > 0) {
            if ($search_fields['campaignDocumentName'] != '')
                $qry .=" AND c.name like('" . $this->db->escape_str('%' . $search_fields['campaignDocumentName']) . "%') ";
        }
        $qry .= "order by c.campaign_document_id desc";

        //$qry.=" LIMIT " . $start . " , " . $len;
        //echo $qry; die();
        $res1 = $this->db->query($qry);
        $qry_data = $res1->result();

        return $qry_data[0]->cnt;
    }

}
