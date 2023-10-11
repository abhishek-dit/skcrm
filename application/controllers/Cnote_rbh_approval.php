<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Base_controller.php';


class Cnote_rbh_approval extends Base_controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Cnote_rbh_approval_m');
        $this->load->model('Contract_model');
        $this->load->model("quote_model");
        $this->load->library('Pdf');
        $this->load->library('numbertowords');
        $this->load->library('user_agent');
        
    }

    /**
    * Fetching Cnote list waiting for approval
    * return: $cnote_array(array)
    **/
    public function contract_note_approval_list()
    {     
        # Data Array to carry the require fields to View and Model
        $data['nestedView']['heading'] = "C Note Approval List";
        $data['nestedView']['cur_page'] = 'contract_note_approval_list';
        $data['nestedView']['parent_page'] = 'contract_note_approval_list';

        # Load JS and CSS Files
        $data['nestedView']['js_includes'] = array();
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.parsley/parsley.js"></script>';
        
        $data['nestedView']['js_includes'][] = '<script type="text/javascript" src="' . assets_url() . 'js/jquery.icheck/icheck.min.js"></script>';

        $data['nestedView']['css_includes'] = array();
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/fuelux/css/fuelux-responsive.min.css" />';
        $data['nestedView']['css_includes'][] = '<link rel="stylesheet" type="text/css" href="' . assets_url() . 'js/jquery.icheck/skins/square/blue.css" />';

        # Breadcrumbs
        $data['nestedView']['breadCrumbTite'] = 'C Note Approval';
        $data['nestedView']['breadCrumbOptions'] = array(array('label' => 'Home', 'class' => '', 'url' => SITE_URL . 'home'));
        $data['nestedView']['breadCrumbOptions'][] = array('label' => 'C Note Approval', 'class' => '', 'url' =>'');
        $data['pageDetails'] = 'contract_note_approval_list';
        //$data['lead_id'] = $lead_id;
        $user_id = $this->session->userdata('user_id');
        //retreving user locations
        $locations =  getUserLocations($user_id);

        # Search Functionality
        $psearch=$this->input->post('search', TRUE);
        if($psearch!='') {
        $searchParams=array(
                      'contract_note_id'=>$this->input->post('contract_note_id'),
                      'cnote_type'=>$this->input->post('cnote_type'),
                      'billing_party'=>$this->input->post('billing_party')
                      );
        $this->session->set_userdata($searchParams);
        } else {
            
            if($this->uri->segment(2)!='')
            {
            $searchParams=array(
                      'contract_note_id'=>$this->session->userdata('contract_note_id'),
                      'cnote_type'=>$this->session->userdata('cnote_type'),
                      'billing_party'=>$this->session->userdata('billing_party')
                      );
            }
            else {
                $searchParams=array(
                      'contract_note_id'=>'',
                      'cnote_type'=>'',
                      'billing_party'=>'',
                       );
                $this->session->unset_userdata(array_keys($searchParams));
            }
            
        }
        $data['searchParams'] = $searchParams;
       // print_r($searchParams);exit;
        /* pagination start */
        $config = get_paginationConfig();
        $config['base_url'] = SITE_URL.'contract_note_approval_list/'; 
        # Total Records
        $config['total_rows'] = $this->Cnote_rbh_approval_m->contract_note_total_rows($searchParams,$locations);
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
        $data['searchResults'] = $this->Cnote_rbh_approval_m->contract_note_results($searchParams,$config['per_page'], $current_offset,$locations);
        $data['billing_name'] = $this->Common_model->get_dropdown("billing", "billing_info_id", "name");
       $this->load->view('lead/open_rbh_cnote_approvals', $data);
    }
    #Cnote Approval process
    public function cNote_approval($id)
    {   
        $ids = icrm_decode($id);
        $concat_ids=explode("_",$ids);
        $cnote_type = $concat_ids[1];
        $contract_note_id =  $concat_ids[0];
        #Fetching Lead id by passing cnote_id
        $lead_id = $this->Cnote_rbh_approval_m->getLeadIdByCNote($contract_note_id);
        if($this->input->post('approve'))
        {  
             $this->db->trans_begin();
            //updating contract note status to 1 
            $where = array('contract_note_id' => $contract_note_id);
            $updateData = array('status' => 1,
                                'approved_by' => $this->session->userdata('user_id'),
                                'approved_time' => date('Y-m-d H:i:s'));
            $this->Common_model->update_data('contract_note', $updateData, $where);
            // C-Note Status history
            addCnoteStatusHistory($contract_note_id,1);
            switch ($cnote_type) {
                case 1:
                    
                    //pdf file generation
                    $data = $this->quote_model->getContractPDFDetails($contract_note_id);
                    $data['cnote_date'] =  format_date($data['contract_note']['created_time']);
                    //$data['cnote_data'];die;
                    $data['tax_type']   =   tax_type($data['cnote_date']);
                    // Get Quote Details
                    $data['quotes'] = $this->quote_model->getQuotesByCNoteID($contract_note_id);
                    $data['quote_format_type']   =   quote_format_type($data['cnote_date']);

                    $lead_id = $data['lead_id_val'];
                    $customerSAPCode = getCustomerSAPCode($lead_id);
                    /*$quote_content = $this->load->view('quote/contractPDF', $data, true);
                    $pdf = new Pdf('P', 'px', 'A4', true, 'UTF-8', false);
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetFont('dejavusans', '', 8);
                    $pdf->AddPage();
                    $image1 = assets_url() . "images/skanray-logo.png";
                    $pdf->writeHTML($quote_content, true, false, true, false, '0');
                    $pdf_name=$customerSAPCode.$contract_note_id.date('MdYhis').".pdf";
                   // $path = SITE_URL1.'downloads/';
                    $pdf_save_path=FCPATH."downloads/";
                    $pdf_file_path=$pdf_save_path.$pdf_name;
                    $pdf->Output($pdf_file_path, 'F');*/

                
                    $leads = $this->Common_model->get_data_row('lead',array('lead_id'=>$lead_id));
                    //retreving otr emails
                    $region_id =  getRegionFromCity($leads['location_id']);
                    $otr_mails = $this->Cnote_rbh_approval_m->get_mails_otr($region_id);
                    /*echo $this->db->last_query();
                    echo '<pre>'; print_r($otr_mails); exit;*/
                    $mails = array();
                    $cc = array();
                    foreach ($otr_mails as $emails) {
                        $cc[]=$emails['email_id'];
                    }

                   //retreving email of lead owner
                    $user=$this->Common_model->get_data_row('user',array('user_id'=>$leads['user_id']));
                    $mails=$user['email_id'];
                    $to=mail_to($mails);
                    $message='Hi '.$user['first_name'].' '.$user['last_name'].',';
                    $message .= '<p>Your contract note : '.$contract_note_id.' has been cleared for invoice. </p>';
                    $message .= '<p>Regards,<br>iCRM,<br>SkanRay</p>';
                    //$message .= 'SE mail: '.implode(',',$mails).'<br>OTR mails:'.implode(',',$cc);

                    $subject="Contract Note : ".$contract_note_id.' has been cleared for invoice';
                    $from = "noreply@skanray-access.com";
                    $cc = mail_to($cc);
                    //print_r($cc); exit;
                    /*$docs=array();
                    $docs['contract_note'.$contract_note_id.'.pdf']=$pdf_file_path;*/
                    
                   send_email($to,$subject,$message,$cc);
                break;
                case 2: // Distributor Purchase Order
                    $purchase_order_id = $this->Cnote_rbh_approval_m->getPoIdByCNote($contract_note_id);
                    // Update Purchase order status
                    $po_data = array('status'=>4,'modified_by'=>$this->session->userdata('user_id'),'modified_time'=>date('Y-m-d H:i:s'));
                    $po_where = array('purchase_order_id'=>$purchase_order_id);
                    $this->Common_model->update_data('purchase_order',$po_data,$po_where);
                    // Add PO Status History
                    addPoStatusHistory($purchase_order_id,4);
                break;
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while approving Contact Note!
                                     </div>');
            }
            else
            {
                $this->db->trans_commit();
                /*$res = send_email( $to,$subject, $body, $cc, $from,$from_name='Skanray ICRM', $bcc, NULL,  $docs); 
                unlink($pdf_file_path);*/
                //echo ($res)?'success1':'fail'; exit; 
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> Contract Note has been Approved successfully!
                                     </div>');
            }
            
        }
        if($this->input->post('reject'))
        {
             $this->db->trans_begin();
              $where = array('contract_note_id' => $contract_note_id);
            $updateData = array('status' => 4,
                                'approved_by' => $this->session->userdata('user_id'),
                                'approved_time' => date('Y-m-d H:i:s'));
           $this->Common_model->update_data('contract_note', $updateData, $where);
           if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('response','<div class="alert alert-danger alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Error!</strong> There\'s a problem occured while rejecting Contact Note!
                                     </div>');
           
                    
            }
            else
            {
                $this->db->trans_commit();
                $this->session->set_flashdata('response','<div class="alert alert-success alert-white rounded">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <div class="icon"><i class="fa fa-check"></i></div>
                                        <strong>Success!</strong> Contract Note has been rejected successfully!
                                     </div>');
           
            }
        }
         //echo "his ";exit;
          redirect(SITE_URL.'contract_note_approval_list');
    }
    #PDF Generation for Contract Note
     function view_contract_note_pdf($qid=0) 
    {
        $contract_note_id = icrm_decode($qid);
        $data = $this->quote_model->getContractPDFDetails($contract_note_id);
        $data['cnote_date'] =  format_date($data['contract_note']['created_time']);
        $data['tax_type']   =   tax_type($data['cnote_date']);
        
        $lead_id = $data['lead_id_val'];
        $customerSAPCode = getCustomerSAPCode($lead_id);
        
        $this->load->view('quote/view_contract_pdf', $data);


    }
}
