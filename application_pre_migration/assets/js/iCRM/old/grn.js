// validate sso id exist or not on submit
$('#deleteComponent').hide();
$(document).on('keydown',"#poName",function () { 
		$("#poName").autocomplete({
		 source:'ajax/po_autocomplete.php',
		 minLength:1,
		 width: 402,
		 select: function( event, ui ) {
		 	var label = ui.item.label;
			var po_id = ui.item.id;
			var value = ui.item.value;
			$('#po_id').val(po_id);
			if(po_id>0)
			{
				//alert('hello');
				$("#grnContainer").css("opacity",0.5);
				$("#loaderID").css("opacity",1);
				var data = 'po_id='+po_id;
				$.ajax({
					type:"POST",
					url:'ajax/grn_getPoOrders.php',
					data:data,
					cache:false,
					success:function(html){
						$("#grnContainer").css("opacity",1);
						$("#loaderID").css("opacity",0);
						$('#components_tbl').html(html);
						$('#chkAllComp').prop('disabled',false);
						$('#components_tbl input[type="text"], #components_tbl button').prop('disabled',true);
					}
				});
			}
		 }
		});
	 });

$(document).on('change','.componentRow',function(){
	if(this.checked)
	{
		$(this).closest('.item_row').find('input[type="text"],button').prop('disabled',false);
		$('#submitStore').prop('disabled',false);
	}
	else
	{
		$(this).closest('.item_row').find('input[type="text"],button').prop('disabled',true);
		var len=$('.componentRow:checked').length;
		if(len==0){$('#submitStore').prop('disabled',true);}
	}
});
// check all components
$('#chkAllComp').change(function(){
	if(this.checked)
	{
		$('.componentRow').prop('checked',true);
		$('#components_tbl input[type="text"],#components_tbl button,#submitStore').prop('disabled',false);
	}
	else
	{
		$('.componentRow').prop('checked',false);
		$('#components_tbl input[type="text"],#components_tbl button,#submitStore').prop('disabled',true);
	}
});
// on blur of location quantity 
$(document).on('blur',".locationQty",function () {
	var rowid = $(this).closest('.item_row').find('.componentRow').val();
	//alert(rowid);
	var locTot = calTotLocQty(rowid);
	var ordQty = parseInt($(this).closest('.item_row').find('.orderedQty').val());
	var receivedTotQty = parseInt($(this).closest('.item_row').find('.receivedTotQty').val());
	var balQty = ordQty - receivedTotQty;
	//alert(locTot);
	if(locTot>balQty)
	{
		alert('GRN quantity exceeding balance quantity');
		$(this).val('');
		//$(this).focus();
	}
	else
	{
		$(this).closest('.item_row').find('.grnQty').val(locTot);
		$(this).closest('.item_row').find('.grnQtyLabel').html(locTot);
	}
});

function calTotLocQty(rowid)
{
	var tot = 0;
	$('.locQty'+rowid).each(function() {
        var val = parseInt($(this).val());
		
		if(val>0)
		{
			tot+=val;
		}
    });
	return tot;
}
 var cnt = 2;
$(document).on('click',"#add_new_components",function () {
	$('.rounded').remove();
	var store_select_box=$(".storedropdown_id").html();
	$('#components_tbl tr').last().after('<tr id="row'+cnt+'" class="item_row"><td><input type="hidden" class="componentId" name="componentId['+cnt+']"><input type="text" placeholder="Select Component" name="componentName['+cnt+']" class="form-control componentName"></td><td></td><td><div data-date-format="yyyy-mm-dd" data-min-view="2" class="input-group date datetime"><input type="text" readonly placeholder="Expiration Date" name="exp_date['+cnt+']"size="16" class="form-control"><span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span></div> </td><td><input type="text" placeholder="Price" class="form-control col-sm-6 productPrice" name="productPrice['+cnt+']"></td><td><button type="button" class="btn btn-warning add_new_locations" id="add_new_locations" data-toggle="modal" data-target="#locaBox'+cnt+'"><i class="fa fa-globe"></i>Add Locations</button><a href="javascript:void(0)" class="btn btn-danger btn-xs removeComponent" data-rowId = "'+cnt+'"><i class="fa fa-times"></i></a></td></tr>');
		
		$('#locationMOdelBoxes').append('<div aria-hidden="true" style="display: none;" class="modal fade" id="locaBox'+cnt+'" tabindex="-1" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div> <div class="modal-body"><div class="suppliersBlock"><div class="header"><h4>Add Location List</h4></div><table class="stat-table responsive table table-stats table-striped table-sortable table-bordered"><tbody id="locations_tbl'+cnt+'"><tr><th>Store</th> <th> Location</th>  <th> Quantity</th><th></th></tr><tr id="lrow'+cnt+'" class="loc_row"><td><select name="storeName'+cnt+'[]" id="storeNameId'+cnt+'" class="form-control storedropdown_id">'+store_select_box+'</select><input type="hidden" name="storeSelectedLabel'+cnt+'[]" class="storeSelectedLabel"> </td><td><select id="location_id'+cnt+'" name="location_id'+cnt+'[]" class="form-control locationName_id" disabled><option value="">Select Location</option></select><input type="hidden" name="locationSelectedLabel'+cnt+'[]"class="locationSelectedLabel">  </td><td><input type="text" placeholder="Quantity" class="form-control col-sm-6 only-numbers" id="quantity'+cnt+'" name="quantity'+cnt+'[]"></td><td></td></tr></tbody><tbody><tr class="addLocBtnRow"><td><input type="hidden" class="tablerow" name="tablerow[]" value="'+cnt+'"> </td><td> </td><td><button type="button" class="btn btn-info add_new_locations" id="add_new_locations"><i class="fa fa-plus"></i>Add Locations</button></td><td></td></tr></tbody></table></div></div><div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button><button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-check"></i> Submit</button></div></div></div></div>');
		cnt++;
						
		});
		var lcnt=2;
$(document).on('click',".add_new_locations",function () { 
var store_select_box=$(".storedropdown_id").html();
	var row_id=$(this).closest('.addLocBtnRow').find('.tablerow').val(); //alert(row_id);
	var data = '<tr id="lrowas'+lcnt+'" class="loc_row"><td><select name="storeName'+row_id+'[]"  class="form-control storedropdown_id storeName'+row_id+'">'+store_select_box+'</select><input type="hidden" name="storeSelectedLabel'+row_id+'[]" class="storeSelectedLabel storeSelectedLabel'+row_id+'">  </td><td><select name="location_id'+row_id+'[]" class="form-control locationName_id location_id'+row_id+'" disabled><option value="">Select Location</option></select><input type="hidden" name="locationSelectedLabel'+row_id+'[]" class="locationSelectedLabel locationSelectedLabel'+row_id+'">  </td><td><input type="text" placeholder="Quantity" class="form-control col-sm-6 only-numbers locationQty locQty'+row_id+'"name="quantity'+row_id+'[]"></td><td><a class="label label-danger removeLocation" ><span><i class="fa fa-times"></i></span></a></td></tr>';
	//alert(data);
		$('#locations_tbl'+row_id).append(data);
		lcnt++;
});
 $(document).on('click',".removeComponent",function () { 
     var rowId = $(this).attr('data-rowId');
	 $('#locaBox'+rowId).remove();
	 $(this).closest('tr').remove();
 })
  $(document).on('click',".removeLocation",function () { 
	 
	 $(this).closest('tr').remove();
 })
 
$(document).on('change',".storedropdown_id",function () {
var storeName_id=$(this).val();
 var storeSelectedOption = $('option:selected', $(this)).text();
var locSelect=$(this).closest('.loc_row').find('.locationName_id');
var storeSelectedLabel=$(this).closest('.loc_row').find('.storeSelectedLabel');
storeSelectedLabel.val(storeSelectedOption);
//alert($(this).closest('tr').find('.Quantity').val());
	var data = 'storeName_id='+storeName_id;
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

// auto complete components by part number
/*$(".componentName").autocomplete({
	source:'ajax/ac_getComponentsByPartNumbers.php',
	minLength:1,
	width: 402,
	select: function( event, ui ) {
		var label = ui.item.label;
		
	} 
 });*/
 $(document).on('keydown',".componentName",function () {
	
		$(".componentName").autocomplete({
		 source:'ajax/ac_getComponentsByPartNumbers.php',
		 minLength:1,
		 width: 402,
		 select: function( event, ui ) {
		 	var label = ui.item.label;
			var component_id = ui.item.id;
			var value = ui.item.value;
			var component_type = ui.item.component_type;
			if(component_id=='' || value==''){
				$(this).closest('tr').find('.componentName').val(id);
				$(this).closest('tr').find('.fk_component_id').val('');

			}
			$(this).closest('tr').find('.componentName').val(value);
			$(this).closest('tr').find('.componentId').val(component_id);
			//$('#component_id').val(component_id);
		 }
		 ,open: function( event, ui ) { alert(component_type);
			 $(".ui-autocomplete li.ui-menu-item").addClass("red");
			 if(component_type==1) {
          		$(".ui-autocomplete li.ui-menu-item").addClass("error");
			 } else if(component_type==2) {
				 $(".ui-autocomplete li.ui-menu-item").addClass("green");
			 } else {
				 $(".ui-autocomplete li.ui-menu-item").addClass("blue");
			 }
        }
	 	 });
		 });
		 
// check is GRN entry exist alredy 	 
$('#grn').blur(function() {
			var grn = $(this).val();
			if(grn!='')
			{
				var data = 'grn='+grn;
				$.ajax({
					type:"POST",
					url:'ajax/checkGrnNumExist.php',
					data:data,
					cache:false,
					success:function(html){
						if(html==1)
						{
							$('#grn_exist_error').html('A GRN Already exist with the same GRN Num');
							$('#grnExist').val(1);
						}
						else
						{
							$('#grn_exist_error').html('');
							$('#grnExist').val('');
						}
					
					}
				});
			}
        });	
	$("#grnFrm").submit(function(){
		
				var grnExist = $('#grnExist').val();
				if(grnExist==1)
				{
					alert('A GRN Already exist with the same GRN Num');
					//$('#grn').focus();
					return false;
				}
				var hasError = false; errMsg= '';
				var intime = $('#grn_date').val();
				if(intime=='')
				{
					errMsg = '<span class="label label-danger"><i class="fa fa-warning"></i>  Please add GRN In Time  </span>';
					hasError = true;
					$('#grnErrMsg').html(errMsg);
					return false;
					
				}
				$('.componentRow:checked').each(function() {
                    var rowid = $(this).val();
					var partNum = $(this).closest('.item_row').find('.componentName').val();
					var expDate = $(this).closest('.item_row').find('.expDate').val();
					//alert(expDate);
					if(expDate=='')
					{
						errMsg = '<span class="label label-danger"><i class="fa fa-warning"></i>  Expiration date empty for the component : '+partNum+'</span>';
						hasError = true;
						return false;
					}
					var productPrice = $(this).closest('.item_row').find('.productPrice').val();
					if(productPrice==''||productPrice==0)
					{
						errMsg = '<span class="label label-danger"><i class="fa fa-warning"></i> Please add Unit Cost for the component : '+partNum+'</span>';
						hasError = true;
						return false;
					}
					var orderQty = parseInt($(this).closest('.item_row').find('.orderedQty').val());
					var receivedTotQty = parseInt($(this).closest('.item_row').find('.receivedTotQty').val());
					var balQty = orderQty - receivedTotQty;
					var grnQty=0; hasLocErr = false;
					$('.storeName'+rowid).each(function() {
						var storeid = $(this).val();
						if(storeid=='')
						{
							errMsg = '<span class="label label-danger"><i class="fa fa-warning"></i>  Please choose Store and location for the component : '+partNum+'</span>';
							hasLocErr = true;
							return false;
						}
						var storeNameLable = $(this).closest('.loc_row').find('.storeSelectedLabel').val();
						var location_id = $(this).closest('.loc_row').find('.locationName_id').val();
						if(location_id=='')
						{
							
							errMsg = '<span class="label label-danger"><i class="fa fa-warning"></i>  Please choose  location for the store '+storeNameLable+'  for the component : '+partNum+'</span>';
							hasLocErr = true;
							return false;
						}
						var locQty = $(this).closest('.loc_row').find('.locationQty').val();
						//alert(locQty);
						var locationSelectedLabel = $(this).closest('.loc_row').find('.locationSelectedLabel').val();
						if(locQty==''||locQty==0)
						{
							errMsg = '<span class="label label-danger"><i class="fa fa-warning"></i>  Please enter Quantity for the location '+locationSelectedLabel+'  for the component : '+partNum+'</span>';
							hasLocErr = true;
							return false;
						}
						grnQty+=parseInt(locQty);
					});
					
					if(hasLocErr)
					{
						hasError = true;
						return false;
					}
					if(grnQty==0)
					{
						errMsg = '<span class="label label-danger"><i class="fa fa-warning"></i>  please enter Grn Qty in locations for the component : '+partNum+'</span>';
						hasError = true;
						return false;
					}
					if(grnQty>balQty)
					{
						errMsg = '<span class="label label-danger"><i class="fa fa-warning"></i>  Grn Quantity  greater than the balance quantity  for the component : '+partNum+'</span>';
						hasError = true;
						return false;
					}
                });
				
				
				if(hasError)
				{
					//alert('error');
					$('#grnErrMsg').html(errMsg);
					return false;
				}
				
		});