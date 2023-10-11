<?php $this->load->view('commons/main_template', $nestedView);

?>
<div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="content">
                <form method="post" action="<?php echo SITE_URL;?>stock_in_hand_table" class="submit_frm">
                    <div class="">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-2">
                                        <select class="form-control category select2" style="width:100%" name="category">
                                            <option value="">Select Category</option>
                                            <?php
                                            foreach ($category_list as $row) {
                                                $selected='';
                                                if($row['category_id']==$searchParams['category'])
                                                {
                                                    $selected='selected';
                                                }
                                                else
                                                {
                                                    $selected='';
                                                }
                                                echo '<option value="'.$row['category_id'].'"'.$selected.'>'.$row['name'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="select2 segment" style="width:100%" name="segment">
                                            <option value="">Select Segment</option>
                                            <?php
                                            if(count($segments)>0)
                                            {
                                                foreach ($segments as $srow) {
                                                    $selected = ($srow['group_id']==$searchParams['segment'])?'selected':'';
                                                    echo '<option value="'.$srow['group_id'].'" '.$selected.'>'.$srow['name'].'</option>';
                                                }
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="select2 product" style="width:100%" name="product">
                                            <option value="">Select Product</option>
                                            <?php
                                            if(count($products)>0)
                                            {
                                                foreach ($products as $srow) {
                                                    $selected = ($srow['product_id']==$searchParams['product'])?'selected':'';
                                                    echo '<option value="'.$srow['product_id'].'" '.$selected.'>'.$srow['description'].'</option>';
                                                }
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                        <a  class="btn btn-success" href="<?php echo SITE_URL.'stock_in_hand_table';?>"><i class="fa fa-refresh"></i></a>
                                        <button type="submit" style="margin-left: 84px;margin-top: -30px;" value="1" name="download" formaction="<?php echo SITE_URL.'download_stock_in_hand_xl'?>" class="btn btn-primary" title="Download Product Excel"><i class="fa fa-cloud-download"></i></button>
                                    </div>
                                    <div class="col-sm-3">
                                        <?php
                                        if($as_on_date!='')
                                        {
                                            ?>
                                            <span style="margin-left: 18px;"><strong style="color: red;">Last Updated On: </strong><?php echo date('d-m-Y',strtotime($as_on_date));?></span> <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><br>
                </form>
                <div class="con">
                   <!--  <span><?php echo $as_on_date;?></span> -->
                    <div class="">
                        <div class="table-responsive " style="margin-top: -18px;">
                            <table class="table table-bordered hover" style="background-color: '#cfac6b' !important;">
                                <thead>
                                    <tr>
                                        <th class="text-center"  width="6%"><strong></strong></th>
                                        <th class="text-center"  width="47%"><strong>Category</strong></th>
                                        <th class="text-center"  width="47%"><strong>Quantity</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(count($product_list)>0)
                                    {
                                        foreach($product_list as $key1 =>$value1)
                                        {
                                            ?>
                                            <tr>
                                                <td class="text-center"><img src="<?php echo assets_url(); ?>images/plus.png" class="toggle-details"></td>
                                                <td align="left"><?php echo $value1['category_name'];?></td>

                                                <?php 
                                                $qty_sum_1 = 0;
                                                foreach($value1['segment'] as $key2 => $value2)
                                                {
                                                    foreach ($value2['products'] as $key4 => $value4) 
                                                    {
                                                        $qty_sum_1+=$value4['quantity'];
                                                    }
                                                }
                                                ?>
                                                <td align="right"><?php echo $qty_sum_1;?></td>
                                            </tr> 
                                            <?php
                                            if(count($value1['segment'])>0)
                                            {
                                                $slno1 = 1;?>
                                                <tr class="details">
                                                    <td  colspan="3">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th width="10%"></th>
                                                                    <th class="text-center" width="10%"><strong>Sno</strong></th>
                                                                    <th class="text-center" width="50%"><strong>Segment</strong></th>
                                                                    <th class="text-center"  width="30%"><strong>Quantity</strong></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach($value1['segment'] as $key2 => $value2)
                                                                {
                                                                    ?>
                                                                    <tr class="asset_row">
                                                                        <td class="text-center"><img src="<?php echo assets_url(); ?>images/plus.png" class="toggle-details2"></td>
                                                                        <td class="text-center"><?php echo $slno1++;?></td>
                                                                        <td  align="left"><?php echo $value2['group_name'];?></td>

                                                                        <?php 
                                                                        $qty_sum_2 = 0;
                                                                        foreach ($value2['products'] as $key4 => $value4) 
                                                                        {
                                                                            $qty_sum_2+=$value4['quantity'];
                                                                        }
                                                                        ?>
                                                                         <td align="right"><?php echo $qty_sum_2; ?></td>
                                                                    </tr> <?php

                                                                    if(count($value2['products'])>0)
                                                                    {
                                                                        $slno = 1;?>
                                                                        <tr class="details2">
                                                                            <td  colspan="5">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="text-center"><strong>Sno</strong></th>
                                                                                            <th class="text-center"><strong>Product</strong></th>
                                                                                            <th class="text-center"><strong>Product Code</strong></th>
                                                                                            <th class="text-center"><strong>Quantity</strong></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        foreach($value2['products'] as $key3 => $value3)
                                                                                        {
                                                                                            
                                                                                            ?>
                                                                                            <tr class="asset_row">
                                                                                                <td class="text-center"><?php echo $slno++;?></td>
                                                                                                <td align="left"><?php echo $value3['description'];?></td>
                                                                                                <td align="left"><?php echo $value3['product_code'];?></td>
                                                                                                <td align="right"><?php if($value3['quantity']==NULL){echo 0;}else {echo $value3['quantity'];}?></td>
                                                                                            </tr><?php
                                                                                        }?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr> <?php
                                                                    }
                                                                    else
                                                                    {
                                                                        ?>
                                                                        <tr><td colspan="4" class="text-center"><span class="label label-primary">- No Results Found -</span></td></tr> <?php
                                                                    }
                                                                }?>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr> <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <tr><td colspan="4" class="text-center"><span class="label label-primary">- No Results Found -</span></td></tr> <?php
                                            }
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <tr><td colspan="3" class="text-center"><span class="label label-primary">- No Results Found -</span></td></tr> <?php
                                    }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
		</div>				
	</div>
</div>

<style type="text/css">
    .radio-inline{ padding-left: 10px !important;}
    .radio-inline input[type="radio"]{ margin: 0px 4px 0px 0px;}
</style>

<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>

<script type="text/javascript">

Highcharts.setOptions({ colors: [ '#42A5F5','#A1887F','#FFD54F','#3F51B5','#FF9800','#F44336', '#4CAF50', '#9C27B0', '#795548', '#FFEB3B', '#CDDC39']});

//var icrm = $.noConflict();
var ASSET_URL = "<?php echo assets_url();?>";

$(document).on('change','.segment',function(){
//alert($(this).val()); 
var segment = $(this).val();
if(segment!='')
{
    var data='segment='+segment;
    $.ajax({
        url: SITE_URL+'getProductsDropdownforstock',
        type :"POST",
        data:data,
        success:function(data){
          $('.product').html(data);
        }
    });
}
else
{
    $('.product').html('<option value="">Select Product</option>');
}
});

$(document).on('change','.category',function(){
//alert($(this).val()); 
var category = $(this).val();
 $('.product').html('<option value="">Select Product</option>');
if(category!='')
{
    var data='category='+category;
    $.ajax({
        url: SITE_URL+'getsegmentDropdownforstock',
        type :"POST",
        data:data,
        success:function(data){
          $('.segment').html(data);
        }
    });
}
else
{
    $('.segment').html('<option value="">Select Segment</option>');
    $('.product').html('<option value="">Select Product</option>');
}
});

$('.details, .details2').hide();
$(document).on('click',".toggle-details",function () { 
    var row=$(this).closest('tr');
    var next=row.next();
    $('.details').not(next).hide();
    $('.toggle-details').not(this).attr('src',ASSET_URL+'images/plus.png');
    next.toggle();
    if (next.is(':hidden')) {
        $(this).attr('src',ASSET_URL+'images/plus.png');
    } else {
        $(this).attr('src',ASSET_URL+'images/minus.png');
    }
});

$(document).on('click',".toggle-details2",function () { 
    var row=$(this).closest('tr');
    var next=row.next();
    $('.details2').not(next).hide();
    $('.toggle-details2').not(this).attr('src',ASSET_URL+'images/plus.png');
    next.toggle();
    if (next.is(':hidden')) {
        $(this).attr('src',ASSET_URL+'images/plus.png');
    } else {
        $(this).attr('src',ASSET_URL+'images/minus.png');
    }
});

</script>