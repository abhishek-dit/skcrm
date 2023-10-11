// validate sso id exist or not on submit
$('#deleteComponent').hide();
$(document).on('keydown',".componentName",function () {
	$('.rounded').remove();
	var supplier_id = $('#dbsupplier_id').val(); 
		$(".componentName").autocomplete({
		 source:'ajax/components_autocomplete.php?supplier_id='+supplier_id+'',
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
			$(this).closest('tr').find('.fk_component_id').val(component_id);
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

 var cnt = 2;
 $(document).on('click',"#addComponent",function () {
	 $('.rounded').remove();
		$('#products_tbl tr').last().after('<tr id="row'+cnt+'" class="item_row"><td><div class="form-group" id="row'+cnt+'"><label for="inputName" class="col-sm-3 control-label">Select Component</label><div class="col-sm-6"><input type="name" required class="form-control componentName" id="componentName" placeholder="Type Component Name" name="componentName" value=""><input id="component_id" name="component_id[]" class="fk_component_id" value="" type="hidden"></div><a class="btn btn-danger removeComponent" style="padding:3px 3px;"><span><i class="fa fa-times"></i></span></a></td></tr>');
		cnt++;
						
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
$(document).on('click','#deleteComponent',function(){
	if(confirm("Are You Sure Want to delete Component")){
	var supplier_id = $('#sup_id').val(); //
	var component_id = $('.com_id').val();
	if(supplier_id!='')//alert(component_id);
	{	
		$("#suppCompContainer").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
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
		$("#suppCompContainer").css("opacity",1);
		$("#loaderID").css("opacity",0);
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
