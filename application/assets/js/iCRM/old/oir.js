// validate sso id exist or not on submit
$(document).on('keydown',"#transactionName",function () { //alert("HIHASI");
		$("#transactionName").autocomplete({
		 source:'ajax/openIssueAutocomplete.php',
		 minLength:1,
		 width: 402,
		 select: function( event, ui ) {
		 	var label = ui.item.label;
			var po_id = ui.item.id;
			var value = ui.item.value;
			$('#transaction_id').val(po_id);
		 }
		});
	 });

 var cnt = 2;
$(document).on('click',"#add_new_components",function () {
	$('.rounded').remove();
	var store_select_box=$(".storedropdown_id").html();
	$('#components_tbl tr').last().after('<tr id="row'+cnt+'" class="item_row"><td><input type="hidden" class="component_row_id" id="component_id" name="component_id[]" value="'+cnt+'"><input type="text" placeholder="Select Component" name="componentName['+cnt+']" class="form-control"></td><td><div data-date-format="yyyy-mm-dd" data-min-view="2" class="input-group date datetime"><input type="text" readonly=""placeholder="Expiration Date" name="exp_date['+cnt+']"size="16" class="form-control"><span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span></div> </td><td><input type="text" placeholder="Price" class="form-control col-sm-6 productPrice" name="productPrice['+cnt+']"></td><td><button type="button" class="btn btn-warning add_new_locations" id="add_new_locations" data-toggle="modal" data-target="#locaBox'+cnt+'"><i class="fa fa-globe"></i>Add Locations</button><button type="button" class="btn btn-danger removeComponent"><i class="fa fa-times"></i></button></td></tr>');
		
		$('#locationMOdelBoxes').append('<div aria-hidden="true" style="display: none;" class="modal fade" id="locaBox'+cnt+'" tabindex="-1" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div> <div class="modal-body"><div class="suppliersBlock"><h4>Add Location List</h4><table class="stat-table responsive table table-stats table-striped table-sortable table-bordered"><tbody id="locations_tbl'+cnt+'"><tr><th>Store</th> <th> Location</th>  <th> Quantity</th><th></th></tr><tr id="lrow'+cnt+'" class="loc_row"><td><select name="storeName'+cnt+'[]" id="storeNameId'+cnt+'" class="form-control storedropdown_id">'+store_select_box+'</select><input type="hidden" name="storeSelectedLabel'+cnt+'[]" class="storeSelectedLabel"> </td><td><select id="location_id'+cnt+'" name="location_id'+cnt+'[]" class="form-control locationName_id" disabled><option value="">Select Location</option></select><input type="hidden" name="locationSelectedLabel'+cnt+'[]"class="locationSelectedLabel">  </td><td><input type="text" placeholder="Quantity" class="form-control col-sm-6 Quantity" id="quantity'+cnt+'" name="quantity'+cnt+'[]"></td><td></td></tr></tbody><tbody><tr class="addLocBtnRow"><td><input type="hidden" class="tablerow" name="tablerow[]" value="'+cnt+'"> </td><td> </td><td><button type="button" class="btn btn-info add_new_locations" id="add_new_locations"><i class="fa fa-plus"></i>Add Locations</button></td><td></td></tr></tbody></table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-success" data-dismiss="modal">Submit</button></div></div></div></div>');
		cnt++;
						
		});
		var lcnt=2;
$(document).on('click',".add_new_locations",function () { 
var store_select_box=$(".batchStoredropdown_id").html();
	var row_id=$(this).closest('.addLocBtnRow').find('.tablerow').val(); //alert(row_id);
	var component_row_id=$(this).closest('.component_row_id').val();
		$('#locations_tbl'+row_id+' tr').last().after('<tr id="lrowas'+lcnt+'" class="loc_row"><td><select name="batchStore'+row_id+'[]"  class="form-control batchStoredropdown_id">'+store_select_box+'</select><input type="hidden" name="batchStoreSelectedLabel'+row_id+'[]" class="batchStoreSelectedLabel">  </td><td><select name="location_id'+row_id+'[]" class="form-control locationName_id" disabled><option value="">Select Location</option></select><input type="hidden" name="locationSelectedLabel'+row_id+'[]" class="locationSelectedLabel">  </td><td><input type="text" placeholder="Quantity" class="form-control col-sm-6 only-numbers"name="quantity'+row_id+'[]"></td><td><a class="label label-danger removeComponent" ><span><i class="fa fa-times"></i></span></a></td></tr>');
		lcnt++;
});
 $(document).on('click',".removeComponent",function () { 
	 $(this).closest('tr').find('.componentName').val('');
	 $(this).closest('tr').find('.fk_component_id').val('');
	 $(this).closest('tr').remove();
 })
 // validate sso id exist or not on submit		
 $("#chckComponent").click(function(){
	$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
 });
 
  $(document).on('click',".chckComponentQty",function () {  //alert("HIHI");
  var txte=$(this).closest('tr').find('.eQuantity');
  txte.prop("disabled", !$(this).is(':checked'));
  $(this).closest('tr').find(".eQuantity").val("");
 
  });
$("#deleteComponent").click(function(){
	if(confirm("Are You Sure Want to delete Component")){
	var supplier_id = $('#sup_id').val(); //
	var component_id = $('.com_id').val();
	if(supplier_id!='')//alert(component_id);
	{	
		$(".form_container_full").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		var data = $("form").serialize();
		$.ajax({
		type:"POST",
		url:'ajax/delete_assigned_components.php',
		data:data,
		cache:false,
		success:function(html){
		//alert(html);
		if(html!=''){
			$("#divPDFFile").remove(); 
			$(".form_container_full").css("opacity",1);
			$("#tblData").html(html);
			$('.chckCbutton').hide();
			$('#chckComponent').prop("checked", false);
			$("#lblComponent").html('<span class="label label-primary"><i class="fa fa-check-circle"></i>  Components Removed Successfully</span>');$("#loaderID").css("opacity",0);
		} else {
			$("#lblComponent").html('Invalid Request');
		}
		}
		});
	}
	}
});
$(document).on('change',".batchStoredropdown_id",function () { //alert($(this).val());
var batchStoreId=$(this).val(); 
 var storeSelectedOption = $('option:selected', $(this)).text();
var locSelect=$(this).closest('.loc_row').find('.locationName_id');
var storeSelectedLabel=$(this).closest('.loc_row').find('.batchStoreSelectedLabel');
storeSelectedLabel.val(storeSelectedOption);
//alert($(this).closest('tr').find('.Quantity').val());
	var data = 'storeName_id='+batchStoreId;
	$.ajax({
	type:"POST",
	url:'ajax/get_store_locations.php',
	data:data,
	cache:false,
	success:function(html){
	//alert(html);
	locSelect.html(html);
	locSelect.prop('disabled',false);
	}
	});
});
$(document).on('change',".locationName_id",function () {
var locationSelectedLabel=$('option:selected',$(this)).text();
var loca_Label=$(this).closest('.loc_row').find('.locationSelectedLabel');
loca_Label.val(locationSelectedLabel);
});


/***TABLE COLLAPSE ON 12-Nov-2015***/
$('.details').hide();
$(document).on('click',".toggle-details",function () { //alert("html");
		var row=$(this).closest('tr');
		var next=row.next();
		$('.details').not(next).hide();
		$('.toggle-details').not(this).attr('src','application/assets/images/plus.png');
		next.toggle();
		if (next.is(':hidden')) {
    		$(this).attr('src','application/assets/images/plus.png');
		} else {
    		$(this).attr('src','application/assets/images/minus.png');
		}
});
/*Validate the Quantity as Per Transaction Quantity*/
//$('#locBtn').hide();
/*$(document).on('blur',".eQuantity",function () {
	//var enteredQty=Number($(this).val());
	var id=$(this).attr("id");
	var mtQty=Number($(this).closest('td').find('.mt_trans_quantity').val());
	//var mtQty=$(this).closest('.item_row').find('.mt_trans_quantity').val();
	var subtotal=0;
	$(".subQty"+id).each(function() {
		var val=$(this).val();
		if(val!='')
		{
    		subtotal+=parseInt(val);
		}
	});
	//alert(subtotal);
	if(subtotal>mtQty)
	{
		alert("Component Quantity and Entered Quantity Should be Equal");
		$(this).val('');
	}
	if(subtotal==mtQty)
	{
		$('#locBtn').show();
	}
	else
	{
		$('#locBtn').hide();
	}
	//alert(mtQty);
	
});
*/

$('.cancelTrans').click(function(){
	var trans_id = parseInt($(this).attr('data-id')); 
	var r = confirm("Are you sure you want to cancel the transaction")
	if(r == true)
	{
		//alert(trans_id);
		$("#orderCartContainer").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		var data = 'trans_id='+trans_id;
		//alert(data);
			$.ajax({
			type:"POST",
			url:'cancelTransaction',
			data:data,
			cache:false,
			success:function(html){
				$("#orderCartContainer").css("opacity",1);
				$("#loaderID").css("opacity",0);
			}
			});
	}
});

$('.cancelMasterTrans').click(function(){
	var trans_id = parseInt($(this).attr('data-id')); 
	var r = confirm("Are you sure you want to cancel the master transaction")
	if(r == true)
	{
		//alert(trans_id);
		$("#orderCartContainer").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		var data = 'trans_id='+trans_id;
		//alert(data);
			$.ajax({
			type:"POST",
			url:'ajax/cancelMasterTransaction.php',
			data:data,
			cache:false,
			success:function(html){
				$("#orderCartContainer").css("opacity",1);
				$("#loaderID").css("opacity",0);
			}
			});
	}
});

  $(document).on('click',".checkReturn",function () {  
  //alert("HIHI");
  var txte=$(this).closest('tr').find('.returnQuantity');
  txte.prop("disabled", !$(this).is(':checked'));
  $(this).closest('tr').find(".returnQuantity").val("");
  
	var len=$('.checkReturn:checked').length;
	if(len > 0)
	{
		$("button[name='submitReturn']").prop("disabled",false);
	}
	else
	{
		$("button[name='submitReturn']").prop("disabled",true);
	}
 
  });
$('#formOIR').submit(function () {
	var returnSts=true;
$(".mt_trans_pk").each(function() {
		var trans_id=$(this).val();
		var tran_qty=$(this).closest('.item_row').find('.mt_trans_quantity').val();
		var tran_partNumber=$(this).closest('.item_row').find('.partNumber').val();
		//alert(trans_id+'-------'+tran_qty+'--TRAPART'+tran_partNumber);
		var subtotal=0;
			$(".subQty"+trans_id).each(function() {
				var val=$(this).val();
				if(val!='')
				{
					subtotal+=parseInt(val);
				}
			});
			/*alert(subtotal+'---'+tran_qty);
			return false;*/
			if(subtotal==0)
			{
				returnSts=false;
				$('#lblOIRerror').html('<span class="label label-danger"><i class="fa fa-times"></i>    Issued Quantity cant be Empty for '+tran_partNumber+'</span>');		return false;
			}
			if(subtotal!=tran_qty)
			{
				returnSts=false;
				$('#lblOIRerror').html('<span class="label label-danger"><i class="fa fa-times"></i>  Request Quantity and Issued Quantity did not match for '+tran_partNumber+'</span>');
				return false;
			}

	});
	return returnSts;
});

$(".cancelReturn").click(function(){
	var t_id = $(this).attr('data-rid'); 
	//alert(t_id);
	$("#openOrdersContainer").css("opacity",0.5);
	$("#loaderID").css("opacity",1);
	var data = 't_id='+t_id;
		$.ajax({
		type:"POST",
		url:'requestCancelReturn',
		data:data,
		cache:false,
		success:function(html){
			//alert(html);
			$("#openOrdersContainer").css("opacity",1);
			$("#loaderID").css("opacity",0);
		}
		});
});