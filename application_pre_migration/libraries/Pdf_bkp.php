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
  //Page header
	  public function Header() {
		    //$file = file_get_contents(''.assets_url() . "images/pdf_header.png".'');
			
            //$this->Image('@' . $file, 180, 5, '19', '21', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
			 						</tr>
			 						<tr>
			 							<td>Date - '.$this->edate.'</td>
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
	  }
}