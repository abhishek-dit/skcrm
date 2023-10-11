// validate sso id exist or not on submit
$('#deleteSupplier').hide();
$(document).on('keydown',".supplierName",function () {
	$('.rounded').remove();
	var component_id = $('#dbcomponent_id').val(); 
		$(".supplierName").autocomplete({
		 source:'suppliersAutocomplete?component_id='+component_id+'',
		 minLength:1,
		 width: 402,
		 select: function( event, ui ) {
		 	var label = ui.item.label;
			var supplier_id = ui.item.id;
			var value = ui.item.value;
			if(supplier_id=='' || value==''){
				$(this).closest('tr').find('.supplierName').val(id);
				$(this).closest('tr').find('.fk_supplier_id').val('');

			}
			//alert(supplier_id);
			$(this).closest('tr').find('.supplierName').val(value);
			$(this).closest('tr').find('.fk_supplier_id').val(supplier_id);
		 }
		 
	 	 });
		 });

 var cnt = 2;
 $(document).on('click',"#addSupplier",function () {
	 $('.rounded').remove();
		$('#products_tbl tr').last().after('<tr id="row'+cnt+'" class="item_row"><td><div class="form-group" id="row'+cnt+'"><label for="inputName" class="col-sm-3 control-label">Select Supplier</label><div class="col-sm-6"><input type="name" required class="form-control supplierName" id="supplierName" placeholder="Type Supplier Name" name="supplierName" value=""><input id="supplier_id" name="supplier_id[]" class="fk_supplier_id" value="" type="hidden"></div><a class="btn btn-danger removeSupplier" style="padding:3px 3px;"><span><i class="fa fa-times"></i></span></a></td></tr>');
		cnt++;
						
		});
 $(document).on('click',".removeSupplier",function () { 
	 $(this).closest('tr').find('.supplierName').val('');
	 $(this).closest('tr').find('.fk_supplier_id').val('');
	 $(this).closest('tr').remove();
 })
 // validate sso id exist or not on submit		
 $(document).on('click',"#chckComponent",function(){
	$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
 });
 
  $(document).on('click',".chckComponent",function () { 
  	//$('#deleteSupplier').toggle();
	$('.rounded').remove();
	 $('.chckCbutton').toggle($('.chckComponent:checked').length > 0);
  });
$(document).on('click','#deleteSupplier',function(){
	if(confirm("Are You Sure Want to delete Supplier")){
	var supplier_id = $('#sup_id').val(); //
	var supplier_id = $('.com_id').val();
	if(supplier_id!='')//alert(supplier_id);
	{	
		$("#suppCompContainer").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		$(".form_container_full").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		var data = $("form").serialize();
		$.ajax({
		type:"POST",
		url:'deleteAssignedSuppliers',
		data:data,
		cache:false,
		success:function(html){
		//alert(html);
		$("#suppCompContainer").css("opacity",1);
		$("#loaderID").css("opacity",0);
		if(html!=''){
			$("#divPDFFile").remove(); 
			$(".form_container_full").css("opacity",1);
			$("#tblData").html(html);
			$('.chckCbutton').hide();
			$('#chckComponent').prop("checked", false);
			$("#lblComponent").html('<span class="label label-primary"><i class="fa fa-check-circle"></i>  Suppliers Removed Successfully</span>');$("#loaderID").css("opacity",0);
		} else {
			$("#lblComponent").html('Invalid Request');
		}
		}
		});
	}
	}
});
