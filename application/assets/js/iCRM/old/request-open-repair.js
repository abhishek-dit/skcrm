 $(document).on('change',".store_id",function () { 
 	var store=$(this).val();
	var ltype_id=$("#ltype_id").val();
	var closestLoc=$(this).closest("tr").find(".location_id");
	if(store!='')//alert(component_id);
	{	
		var data='store_id='+store+'&ltype_id='+ltype_id;
		$.ajax({
		type:"POST",
		url:'request-open-repair_locations',
		data:data,
		cache:false,
			success:function(html){ //alert(closestLoc);
				closestLoc.html(html);
				closestLoc.prop('disabled',false);
				//alert(html);
			}
		});
	}
	else
	{
		closestLoc.html('<option value="">--Select Store Location--</option>');
		closestLoc.prop('disabled',true);
	}
 });
  var cnt = 2;
 $(document).on('click',"#add_store_repairs",function () { 
	 var stores_select_box=$("#store_id").html();
	 var mt_trans_quantity=$("#mt_trans_quantity").val();
	$('#repairs_tbl tr').last().after('<tr id="row'+cnt+'" class="item_row"><td><input type="hidden" value="'+cnt+'" class="tablerow"><select name="store_id[]" class="form-control store_id">'+stores_select_box+'</select></td><td><select name="location_id[]"  class="form-control location_id" disabled><option value="">--Select Store Location--</option></select></td> <td> <input type="number" placeholder="Enter Quantity" name="quantity[]" class="form-control only-numbers Quantity" max='+mt_trans_quantity+' min="0"></td><td><button type="button" class="btn btn-danger removeComponent"><i class="fa fa-times"></i></button></td></tr>');
		cnt++;
});
 $(document).on('click',".removeComponent",function () { 
	 $(this).closest('tr').remove();
 })
  $(document).on('change',".Quantity",function () {  
  var tran_qty=$("#mt_trans_quantity").val();
  var tran_partNumber=$("#tran_partNumber").val();
  var subtotal=0;
  			$(".Quantity").each(function() {
				var val=$(this).val();
				var tablerow=$(this).closest('tr').find('.tablerow').val(); //alert(tablerow);
				var store_id=Number($(this).closest('tr').find('.store_id').val()); 
				var location_id=Number($(this).closest('tr').find('.location_id').val());//alert(location_id);
				if(val!='' && location_id!='' && store_id!="")
				{
					subtotal+=parseInt(val);
				}
				else
				{
					
					subtotal=0;
				}
			});
			/*alert(subtotal+'---'+mt_trans_quantity);
			return false;*/
				if(subtotal==0)
				{
					
					$('#lblRORerror').html('<span class="label label-danger"><i class="fa fa-times"></i> Return Quantity / Location / Store can"t  be Empty for Requested Quantityfor '+tran_partNumber+'</span>');		return false;
				}
				else
				{
					$('#lblRORerror').html('');
				}
			if(subtotal!=tran_qty)
			{
				//$(this).val('');
				$('#lblRORerror').html('<span class="label label-danger"><i class="fa fa-times"></i>  Return Quantity and Requested Quantity did not match for '+tran_partNumber+'</span>');
				return false;
			}
			else
			{
				$('#lblRORerror').html('');
			}
  });
  
  
 $(document).on('click',"#submitROR",function () {  
  var tran_qty=$("#mt_trans_quantity").val();
  var tran_partNumber=$("#tran_partNumber").val();
  var subtotal=0;
  			$(".Quantity").each(function() {
				var val=$(this).val();
				var tablerow=$(this).closest('tr').find('.tablerow').val(); //alert(tablerow);
				var store_id=Number($(this).closest('tr').find('.store_id').val()); 
				var location_id=Number($(this).closest('tr').find('.location_id').val());//alert(location_id);
				if(val!='' && location_id!='' && store_id!="")
				{
					subtotal+=parseInt(val);
				}
				else
				{
					$('#row'+tablerow).remove();
					subtotal=0;
				}
			});
			/*alert(subtotal+'---'+mt_trans_quantity);
			return false;*/
			if(subtotal==0)
			{
				$(this).val('');
				$('#lblRORerror').html('<span class="label label-danger"><i class="fa fa-times"></i> Return Quantity / Location / Store can"t be Empty for Requested Quantity for '+tran_partNumber+'</span>');	
				return false;
			}
			if(subtotal!=tran_qty)
			{
				//$(this).val('');
				$('#lblRORerror').html('<span class="label label-danger"><i class="fa fa-times"></i>  Return Quantity and Requested Quantity did not match for '+tran_partNumber+'</span>');
				return false;
			}
			else
			{
				$(this).closest('.addLocBtnRow').hide();
			}
  });
  
  
$(document).on('change',".location1",function () { 
var location=$(this).val();

if(location==0)//alert(component_id);
{	
	$('.comment').prop("disabled",false);
}
else
{
	$('.comment').prop("disabled",true);
}
});