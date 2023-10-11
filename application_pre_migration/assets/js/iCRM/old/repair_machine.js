// validate sso id exist or not on submit
$(document).on('keydown',".componentName",function () { 
		$(".componentName").autocomplete({
		 source:'ajax/repair_components_autocomplete.php',
		 minLength:1,
		 width: 402,
		 select: function( event, ui ) {
		 	var label = ui.item.label;
			var component_id = ui.item.id;
			var value = ui.item.value;
			$(this).closest('tr').find('.component_id').val(component_id);
		 }
		});
	 });

 var cnt = 2;
$(document).on('click',"#add_new_repair_machine",function () {
	var count=$('#components_count').val();//alert(count);
	var components_html=$('#component_id').html();//alert(components_html);
	if(cnt<=count) {
	$('#components_tbl tr').last().after('<tr id="row'+cnt+'" class="item_row"><td><select name="component_id[]" id="component_id" class="form-control">'+components_html+'</select></td><td> <input type="text" placeholder="Enter Quantity" name="quantity[]" class="form-control only_numbers"></td><td><button type="button" class="btn btn-danger removeComponent"><i class="fa fa-times"></i></button></td></tr>');
		cnt++;
	}
	else
	{
		$('#lblcomponentcount').html('No Components Found / This Issue have only "'+count+'" Components');
	}
						
		});
$(document).on('click',"#add_store_repairs",function () {
	$('.rounded').remove();
	$('#components_tbl tr').last().after('<tr id="row'+cnt+'" class="item_row"><td><input type="hidden" class="component_id" id="component_id" name="component_id[]" ><input type="text" placeholder="Select Component" name="componentName[]" class="form-control componentName"></td><td> <input type="text" placeholder="Enter Quantity" name="quantity[]" class="form-control only_numbers"></td> <td><input type="text" placeholder="Select Location" name="locationName" class="form-control"><input type="hidden"  name="location_id[]" class="form-control location_id"></td><td><button type="button" class="btn btn-danger removeComponent"><i class="fa fa-times"></i></button></td></tr>');

		cnt++;
						
		});
 $(document).on('click',".removeComponent",function () { 
	 $(this).closest('tr').find('.componentName').val('');
	 $(this).closest('tr').find('.fk_component_id').val('');
	 $(this).closest('tr').remove();
 })
 
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
