<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
	public $ref,$edate;
	
	public function setRef($ref){
        $this->ref = $ref;
    }
	public function setDate($edate){
        $this->edate = $edate;
    }
    public function setRoleCheck($roleCheck){
    	$this->roleCheck = $roleCheck;
    }
    
   // $pdf->Image('@' . $img, 55, 19, '', '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
  //Page header
	  public function Header() {
	  		/*$hurl = ''.assets_url() . "images/skanray_new.png".'';
		    $file = file_get_contents($hurl);*/
		    $header_img = FCPATH.'application/assets/quote_header_img.txt';
			$file = @file_get_contents($header_img);
			
            //var_dump($file); exit;
			 $head_content = '<br><br>
			 					<div style="font-size:8px;">
			 					<table>
			 						<tr width="500">
			 							<td width="170">Ref #: '.$this->ref.'</td>
			 							<td>';
			if($this->roleCheck == 2)
			{
				$head_content .= 'SKANRAY AUTHORIZED DISTRIBUTOR';
			}			 							
			 $head_content .='</td> 
			 						<td width="170">'. $this->Image('@' .$file, 180, 4, '19', '21', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false).' </td>
			 						</tr>
			 						<tr>
			 							<td>Date - '.$this->edate.'</td>
			 							<td></td>
			 						</tr>
			 						<tr>
			 							<td>Form No 3F5004</td>
			 							<td></td>
			 						</tr>
			 					</table>	
								</div>';               
           $this->writeHTML( $head_content, 0, 0, 0, true, 'L', true); 
	  }

	// Page footer
	  public function Footer() {
		   //$file = file_get_contents(''.assets_url() . "images/pdf_footer.png".'');
		   $this->writeHtmlCell(102,200,25,281,'<p style="font-size:8px;">Page '.$this->getAliasNumPage().' of  '.' '.$this->getAliasNbPages().'</p>','',1,0,false,'R');
		   //$footer_text = '<img src="'.$file.'" >';               
          // $this->writeHTMLCell(600, 300, '', '', $footer_text, 0, 0, 0, true, 'C', true); 
		   //$this->Image('@' . $file, -1, 286, '212', '12', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		   // $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		    //$files = file_get_contents(''.assets_url() . "images/pdf_footer.png".'');
		   // $this->Image('@' . $file,'10', 6, '60', '15', 'PNG', '', 'L', false, 300, '', false, false, 0, false, false, false);
		    $footer_img = FCPATH.'application/assets/quote_footer_img.txt';
			$files = @file_get_contents($footer_img);
		    $this->Image('@'.$files,4, 286, '206', '9', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	  }
}