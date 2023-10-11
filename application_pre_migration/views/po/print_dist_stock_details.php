<!DOCTYPE html>
<html>
<head>
    <title>Icrm</title>
    <link href="<?php echo assets_url(); ?>css/print.css" media="print" rel="stylesheet" type="text/css" />
    <link href="<?php echo assets_url(); ?>css/report.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="<?php echo assets_url(); ?>images/favicon.png">

</head>
<style>
table tr td{
	height:20px !important;
}
</style>

<body>

<h3 align="center"> Distributor Name : <?php echo getUserDropDownDetails($this->session->userdata('user_id')); ?></h3>
<h4 align="center">Distributor Stock Details <?php echo 'As On '. date('d/m/Y') ?></h4> 
    <table border="1px" align="center" width="750" cellspacing="0" cellpadding="2">
       <thead style="background-color:#cccfff">
          <tr>
       		<th  width="50">Sno</th>
       		<th width="200">Product</th>
       		<th  width="250">Description</th>
       		<th width="50">Stock Available</th>
          </tr>
          
       </thead>
       <?php
        $sn=1;
        $total_qty=0;
        if(@!empty($searchResults))
        { $i=0;
        	 foreach (@$searchResults as $row) {
              $quantity=@$product_qty[$row['product_id']]['opening_stock']+@$product_qty[$row['product_id']]['po_stock']-@$product_qty[$row['product_id']]['tagged_stock'];
              if($quantity!=''|| $quantity !=0)
              { $i++;
                ?>
                <tr>
                   
                    <td ><?php echo $sn++; ?></td>
                    <td ><?php echo $row['name']; ?></td>
                    <td  align='left'><?php echo @$row['description']; ?></td>
                     <td  align='right'><?php echo @$quantity; ?></td>
                </tr>
              <?php 
            }
          }
          if($i==0)
            { ?>
                <tr><td colspan="4" align="center"><span class="label label-primary">No Records</span></td></tr>
        <?php  }
           
         
        }
        else
        { ?>
            <tr>
            <td colspan="4" align="center"><b>No Records Found </b> </td>
            </tr>
        <?php }
        ?>
    </table><br><br>
   
    <br>
    <br>
   <!--  <br><br><br><br><br> -->
    
        <!-- <table style="border:none !important" align="center" width="750">
            <tr style="border:none !important">
            <td style="border:none !important">
            
            <span style="margin-left:550px;">Authorised Signature</span>
            </td>
            </tr>
        </table> -->
   
    <div class="row" style="text-align:center">
    <button class="button print_element"  style="background-color:#3598dc" onclick="print_srn()">Print</button>
    <a class="button print_element" href="<?php echo SITE_URL.'distributor_stock_details';?>">Back</a>
    </div>
</body>
</html>
<script type="text/javascript">
function print_srn()
{
    window.print(); 
}
</script>
    