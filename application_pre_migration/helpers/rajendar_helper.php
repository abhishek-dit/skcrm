<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_unique_name() {
    $ci = & get_instance();

    $ci->load->helper('string');
    $date = new DateTime();
    $time = $date->getTimestamp();
    $u = $time;
    return date('YmdHis') . '_' . random_string('alnum', 10);
    ;
}

/* file end: ./application/helpers/rajendar_helper.php */

/**
 * uploading DOcument
 * @$file_name = 'userfile',
 * @$new_name = will take by default random name, 
 * @$upload_path = 'uploads/', 
 * @$types = 'gif|jpg|png|jpeg|pdf|doc|doc', 
 * @$max_size = 2048
 * 
 * returns:file name
 */
function file_upload($file_name = 'userfile', $new_name = NULL, $upload_path = 'uploads/',$display_errors=TRUE, $types = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx', $max_size = 4096) {

    $ci = & get_instance();
    $ci->load->helper('string');
    $date = new DateTime();
    $time = $date->getTimestamp();
    $u = $time * random_string('numeric', 4);
    if ($new_name == NULL) {
        $new_name = get_unique_name();
    }
    //$config['file_name']=date('YmdHis').'_'.$u."-".random_string('numeric',4);
    $config['file_name'] = $new_name;
    $config['upload_path'] = './' . $upload_path;
    $config['allowed_types'] = $types;
    $config['max_size'] = $max_size;
    //$config['max_width'] = 1024;
    //$config['max_height'] = 768;
   
    $ci->load->library('upload', $config);

    if (!$ci->upload->do_upload($file_name)) {
       
        $error = array('error' => $ci->upload->display_errors()
                );
        //$ci->load->view('upload_form', $error); 
        if($display_errors){
             return $error;
        }
    } else {
       
        $data = array('upload_data' => $ci->upload->data());
        return $data['upload_data']['file_name'];
    }
}

/**
 * Send email
 * 
 * 
 */
function entransys_send_email($from, $to,$body, $cc=NULL, $bcc=NULL, $replyto=NULL, $subject = "---",  $attachments=[]) {
    $ci = & get_instance();
    $ci->load->helper('email');
    $ci->load->library('email');
    
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user'] = 'entransys.test@gmail.com';
    $config['smtp_pass'] = 'test@2929';
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html'; // or html
    $config['validation'] = TRUE; // bool whether to validate email or not      

    $ci->email->initialize($config);
    $email_object = $ci->email;

    $email_object->from($from);
    $email_object->to($to);
    $email_object->cc($cc);
    // $email_object->cc("rajender.jakka@gmail.com");
    $email_object->subject($subject);
    $email_object->message($body);
    $email_object->bcc($bcc);
    $email_object->reply_to($replyto);
    
    if(count($attachments)>0){
        foreach($attachments as $temp_name=>$path){
            $email_object->attach($path, 'attachment',$temp_name);
        }
    }
    $status = $email_object->send();
    
    return $status;

    //echo $ci->email->print_debugger();

   
    $email_object->clear(TRUE);
}

function get_percentage($total,$per){
    return (($total*$per)/100);
}
function convert_number_to_words($number) {
//$number = 190908100.25;
   $no = round($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  return $result . "Rupees  " . $points . " ";
}

function get_salutation(){
   
    $arr=array(
        "Mr"=>"Mr",
        "Mrs"=>"Mrs",
        "Ms"=>"Ms",
        "Miss"=>"Miss",
        "Dr"=>"Dr",
    );
    return $arr;
    
}