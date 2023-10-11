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
		 }
		});
	 });

 var cnt = 2;
$(document).on('click',"#add_new_components",function () {
	$('.rounded').remove();
	var store_select_box=$(".storedropdown_id").html();
	$('#components_tbl tr').last().after('<tr id="row'+cnt+'" class="item_row"><td><input type="hidden" class="component_row_id" id="component_id" name="component_id[]" value="'+cnt+'"><input type="text" placeholder="Select Component" name="componentName['+cnt+']" class="form-control"></td><td><div data-date-format="yyyy-mm-dd" data-min-view="2" class="input-group date datetime"><input type="text" readonly=""placeholder="Expiration Date" name="exp_date['+cnt+']"size="16" class="form-control"><span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span></div> </td><td><input type="text" placeholder="Price" class="form-control col-sm-6 productPrice" name="productPrice['+cnt+']"></td><td><button type="button" class="btn btn-warning add_new_locations" id="add_new_locations" data-toggle="modal" data-target="#locaBox'+cnt+'"><i class="fa fa-globe"></i>Add Locations</button><button type="button" class="btn btn-danger removeComponent"><i class="fa fa-times"></i></button></td></tr>');

		cnt++;
						
		});
		var lcnt=2;
$(document).on('click',".add_new_locations",function () { 
var store_select_box=$(".storedropdown_id").html();
	var row_id=$(this).closest('.addLocBtnRow').find('.tablerow').val(); alert(row_id);
	var component_row_id=$(this).closest('.component_row_id').val();
		$('#locations_tbl'+row_id+' tr').last().after('<tr id="lrowas'+lcnt+'" class="loc_row"><td><select name="batchName1[]" class="form-control batchdropdown_id" id="bacthNameId1"><option value="">--Select Batch--</option></select><input type="hidden" name="batchSelectedLabel'+row_id+'[]" class="batchSelectedLabel"></td><td><select name="storeName'+row_id+'[]"  class="form-control storedropdown_id">'+store_select_box+'</select><input type="hidden" name="storeSelectedLabel'+row_id+'[]" class="storeSelectedLabel">  </td><td><select name="location_id'+row_id+'[]" class="form-control locationName_id" disabled><option value="">Select Location</option></select><input type="hidden" name="locationSelectedLabel'+row_id+'[]" class="locationSelectedLabel">  </td><td><input type="text" placeholder="Available Quantity" class="form-control col-sm-6 only-numbers" name="availquantity'+row_id+'[]"></td><td><input type="text" placeholder="Quantity" class="form-control col-sm-6 only-numbers"name="quantity'+row_id+'[]"></td><td><a class="btn btn-danger removeComponent" ><span><i class="fa fa-times"></i></span></a></td></tr>');
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
 
  $(document).on('click',".chckComponent",function () { 
  	//$('#deleteComponent').toggle();
	$('.rounded').remove();
	 $('.chckCbutton').toggle($('.chckComponent:checked').length > 0);
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
$(document).on('change',".storedropdown_id",function () { //alert($(this).val());
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


/***TABLE COLLAPSE ON 12-Nov-2015***/
$('.details').hide();
$(document).on('click',".toggle-details",function () { //alert("html");
		var row=$(this).closest('tr');
		var next=row.next();
		next.toggle();
		if (next.is(':hidden')) {
    		$(this).attr('src','assets/images/plus.png');
		} else {
    		$(this).attr('src','assets/images/minus.png');
		}
});
