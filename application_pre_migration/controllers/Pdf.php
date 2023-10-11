<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pdf extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->library('pdf'); 
    $this->pdf->fontpath = 'application/assets/ffont/';
	
  }
  
 public function generate()  {
$pdf=$this->pdf;
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->SetFont('Arial', 'B', 10);
//if(isset($_REQUEST['generate_quotation']))
$x = 10;
$y = 10;
$i = 1;
$j = 0;
$row_height=7;
$line_gap=3;
$image1=assets_url()."images/skanray-logo.png";
/* * ****************** head section start ***************** */
$pdf->SetXY($x, $y);
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(96, 20, $pdf->Image($image1,$pdf->GetX(), $pdf->GetY()), $i, $j, 'C');
$pdf->SetFont('Arial', 'U', 20);
$pdf->SetFillColor(247, 247, 247);
$pdf->cell(96, 10, "Contract Note", $i, $j, 'C', 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->ln();
$pdf->SetXY($x + 96, $y + 10);
$pdf->cell(96, 10, "For Office use only", $i, $j, 'C', 1);
$pdf->SetFont('Arial', 'B', 10);

$pdf->ln();
$pdf->SetXY($x, $y + 20);
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Region/Branch", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Order No", $i, $j, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L', 1);
$pdf->ln();

$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Dealer Name", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Order Reference", $i, $j, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L', 1);
$pdf->ln();

$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Dealer code", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Engineer Name", $i, $j, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L', 1);
$pdf->ln();

$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Date of Sales Order", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Engineer PS No", $i, $j, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L', 1);
$pdf->ln();
$pdf->SetY($pdf->GetY() + $line_gap);
/* * ****************** head section END ***************** */

/* * ****************** Customer & Consignee start ***************** */

$html="Customer & Consignee( If consignee Is not same, Indicate separately in the same format below by attaching additional sheets )";
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(192, $row_height, utf8_decode($html), $i, $j, 'C');
//$pdf->WriteHTML(utf8_decode($html);
$pdf->ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36,$row_height, "Institution Name", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(100, $row_height, "", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(35, $row_height, "Institution Code", $i, $j, 'L', 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(21, $row_height, "New", $i, $j, 'L', 1);

$pdf->ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(36, 5, "Billing Name / Designation", $i, 'L',TRUE);
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY($pdf->GetX() + 36, $pdf->GetY()-10);
$pdf->cell(156, 10, "", $i, $j, 'L');

$pdf->ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Address Line 1", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(156, $row_height, "", $i, $j, 'L');

$pdf->ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Address Line 2", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(156, $row_height, "", $i, $j, 'L');
$pdf->ln();

$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "District", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "Pin Code", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(60, $row_height, "", $i, $j, 'L');
$pdf->ln();


$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, $row_height, "State", $i, $j, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(156, $row_height, "", $i, $j, 'L');
$pdf->ln();

$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(36, 5, "Landline No with STD Code", $i, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY($pdf->GetX() + 36, $pdf->GetY()-10);
$pdf->cell(50, 10, "", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(20, 10, "Mobile No", $i, $j, 'L');
$pdf->cell(36, 10, "", $i, $j, 'L');
$pdf->cell(20, 10, "PAN No", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(30, 10, "", $i, $j, 'L');
$pdf->ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(36, 10, "ORDER DETAILS",0, $j, 'L');

$pdf->ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(10, $row_height, "Sno", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(74, $row_height, "Product Description", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(30, $row_height, "Cat No", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(18, $row_height, "QTY", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(30, $row_height, "Unit Price (Rs.)", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(30, $row_height, "Total Price (Rs.)", $i, $j, 'C');

$pdf->ln();
$pdf->SetFont('Arial', '', 10);
$pdf->cell(10, $row_height, "", $i, $j, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(74, $row_height, "", $i, $j, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(30, $row_height, "", $i, $j, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(18, $row_height, "", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(30, $row_height, "", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(30, $row_height, "", $i, $j, 'C');

$pdf->ln();
$pdf->SetFont('Arial', '', 10);
$pdf->cell(162, $row_height, "", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(30, $row_height, "", $i, $j, 'C');

$pdf->ln();
$pdf->SetY($pdf->GetY() + $line_gap); 
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(10, $row_height, "Sno", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(164, $row_height, "Free Supply Items", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(18, $row_height, "QTY", $i, $j, 'C');
$pdf->ln();

$pdf->SetFont('Arial', '', 10);
$pdf->cell(10, $row_height, "", $i, $j, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(164, $row_height, "", $i, $j, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->cell(18, $row_height, "", $i, $j, 'C');
$pdf->ln();
$pdf->SetY($pdf->GetY() + $line_gap);

$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(66, 20, "Conditions of Contract", $i, $j, 'L');
$pdf->SetFont('Arial', 'B', 10);

$pdf->cell(126, 7, "Payment: Within 30 Days OF DELIVERY", $i, $j, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->ln();
$pdf->SetX($pdf->GetX() + 66);
$pdf->cell(126, 7, "Delivery: IMMEDIATE-XPS DOOR DELY", $i, $j, 'C');
$pdf->ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX($pdf->GetX() + 66);
$pdf->cell(126, 6, "Warranty: ONE YEAR", $i, $j, 'C');
$pdf->ln();
$pdf->SetY($pdf->GetY() + $line_gap);


$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(50, 10, "Additional conditions of the contract, if any", $i, 'L');
//$pdf->cell(96, 20, "Additional conditions of the contract, if any", $i, $j, 'L');
$pdf->SetXY($pdf->GetX() + 50,$pdf->GetY()-20);
//$pdf->SetX($pdf->GetX() + 66);
$pdf->SetFont('Arial', 'B', 10);

$pdf->cell(142, 20, "", $i, $j, 'C');
$pdf->ln();

$text1="The undersigned hereby orders the afore-mentioned goods from Skanray Technologies Pvt Ltd.The goods specified above to be delivered as per the conditions of sales and terms of business set out in this contract. Seller's terms of business as printed overleaf are considered to form part of contract unless expressly overruled by any of the conditions stipulated therein.";
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(192, 5, $text1, $i, 'FJ');


//$pdf->SetMargins(0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(50, 34, "Acceptance", $i, 'L');
//$pdf->cell(96, 20, "Additional conditions of the contract, if any", $i, $j, 'L');
$pdf->SetXY($pdf->GetX() + 50,$pdf->GetY()-34);
//$pdf->SetX($pdf->GetX() + 66);
$pdf->SetFont('Arial', 'B', 10);
$pdf->cell(62, 20, "", $i, $j, 'C');
$pdf->cell(80, 20, "", $i, $j, 'C');

$pdf->ln();
$pdf->SetX($pdf->GetX() + 50);
$pdf->cell(62, 7, "Customer Signature and Seal", $i, $j, 'L');
//$pdf->SetX($pdf->GetX() + 56);
$pdf->cell(80, 7, "Accepted on behalf of Skanray Technologies", $i, $j, 'L');

$pdf->ln();
$pdf->SetX($pdf->GetX() + 50);
$pdf->cell(62, 7, "Date: ", $i, $j, 'L');
//$pdf->SetX($pdf->GetX() + 56);
$pdf->cell(80, 7, "Date: ", $i, $j, 'L');




/* * ****************** Customer & Consignee END ***************** */

mysql_close();
ob_end_clean();
$pdf->Output();

 }
}
