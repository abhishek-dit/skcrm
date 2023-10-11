var lcnt=2;
$(document).on('click',".add_new_locations",function () {  //alert("row_id");
var store_select_box=$(".store_id").html();
var location_select_box=$(".location_id").html();
	var row_id=$(this).closest('.addLocBtnRow').find('.tablerow').val(); //alert(row_id);
	var batch_id=$(this).closest('.addLocBtnRow').find('.batch_id').val();
		$('#components_tbl'+row_id+' tr').last().after('<tr id="lrowas'+lcnt+'" class="item_row"><td><input type="hidden" value="'+lcnt+'" name="tablerow'+batch_id+'[]" class="tablerow"><select class="form-control store_id" name="store_id'+batch_id+'[]" required="required">'+store_select_box+'</select> </td><td><select class="form-control location_id" name="location_id'+batch_id+'[]" required="required"><option value="">'+location_select_box+'</option></select> </td><td><input type="number" placeholder="Quantity" class="form-control eQuantity'+batch_id+' only-numbers" name="quantity'+batch_id+'[]" min="1" required="required"></td><td><a class="btn btn-danger removeComponent  btn-sm" ><span><i class="fa fa-times"></i></span></a></td></tr>');
		lcnt++;
});

 $(document).on('click',".removeComponent",function () { 
	 $(this).closest('tr').remove();
 })
 /*CALCULATION*/
 $(document).on('change',".eQuantity",function () {  
 		var eQuantity=$(this).val();
		var tran_qty=$('#mt_trans_quantity').val();
		var tran_partNumber=$('#partNumber').val();
		//alert(trans_id+'-------'+tran_qty+'--TRAPART'+tran_partNumber);
		var subtotal=0;
		$(".eQuantity"+trans_id).each(function() {
				var val=$(this).val();
				if(val!='')
				{
					subtotal+=parseInt(val);
				}
			});
 });
 
 $('#formSROR').submit(function () {// alert("SUBMITED");
	var returnSts=true;
	var eQuantity=$(this).val();
	var master_tran_qty=$('#mt_trans_quantity').val();
	var tran_partNumber=$('#partNumber').val();
$('#divSRORerror').html('');
$(".batch_id").each(function() {
		var batch_id=$(this).val();
		var tran_qty=$(this).closest('.addLocBtnRow').find('.availble_qty').val();
		//alert(batch_id+'-------'+tran_qty+'--TRAPART'+tran_partNumber);
		var subtotal=0;
			$(".eQuantity"+batch_id).each(function() { 
				var val=$(this).val(); //alert(val);
				var tablerow=$(this).closest('tr').find('.tablerow').val(); //alert(tablerow);
				var store_id=Number($(this).closest('tr').find('.store_id').val()); //alert(location_id);
				var location_id=Number($(this).closest('tr').find('.location_id').val());
				if(val!='' && location_id!='' && store_id!="")
				{
					subtotal+=parseInt(val);
				}
				else
				{
					$('#lrowas'+tablerow).remove();
				}
			});
			/*alert(subtotal); return false;*/
			if(subtotal==0)
			{
				returnSts=false;
				$('#divSRORerror').html('<span class="label label-danger"><i class="fa fa-warning"></i>  Returned Quantity / Location / Store can"t be Empty for this Batch : '+batch_id+'</span>');		return false;
			}
			
			if(subtotal!=tran_qty)
			{
				returnSts=false;
				$(this).closest('.addLocBtnRow').show();
				$('#divSRORerror').html('<span class="label label-danger"><i class="fa fa-warning"></i>  Returned Quantity and Your Entered Quantity did not match for this Batch : '+batch_id+'</span>');
				return false;
			}
			else
			{
				$(this).closest('.addLocBtnRow').hide();
			}
	});
			
	return returnSts;
});
$(document).on('change',".store_id",function () { //alert($(this).val());
var store_id=$(this).val(); 
var locSelect=$(this).closest('.item_row').find('.location_id');
//alert("asdf");
//alert(locSelect);
//alert($(this).closest('tr').find('.Quantity').val());
	var data = 'storeName_id='+store_id;
	$.ajax({
	type:"POST",
	url:'get_store_locations',
	data:data,
	cache:false,
	success:function(html){
	//alert(html);
	locSelect.html(html);
	locSelect.prop('disabled',false);
	}
	});
});



$('.icheck1').change(function()
{
	if(this.checked)
	{
		//alert("checked");
		$(this).closest(".item_row").find(".store_id").prop("disabled",false);
		$(this).closest(".item_row").find(".location_id").prop("disabled",false);
		//$(this).closest(".item_row").find(".store_id").val("1");
	}
	else
	{
		//alert("unchecked");
		$(this).closest(".item_row").find(".store_id").prop("disabled",true);
		$(this).closest(".item_row").find(".location_id").prop("disabled",true);
		//$(this).closest(".item_row").find(".store_id").val("");
	}
	var len=$('.icheck1:checked').length;
	if(len > 0)
	{
		$("button[name='submitCAL']").prop("disabled",false);
	}
	else
	{
		$("button[name='submitCAL']").prop("disabled",true);
	}
});
$('#component_chkAll').change(function(){
	
	if(this.checked)
	{
		
		$('.icheck1').prop("checked",true);
		$(".store_id").prop("disabled",false);
		$(".location_id").prop("disabled",false);
		$("button[name='submitCAL']").prop("disabled",false);
	}
	else
	{
		$('.icheck1').prop("checked",false);
		$(".store_id").prop("disabled",true);
		$(".location_id").prop("disabled",true);
		$("button[name='submitCAL']").prop("disabled",true);
	}
		
});