// User master page JS functions
// on check/uncheck of component checkbox
$('.component_chk').change(function()
{
	if(this.checked)
	{
		//alert("checked");
		$(this).closest(".componentRow").find(".componentQuantity").prop("disabled",false);
	}
	else
	{
		//alert("unchecked");
		$(this).closest(".componentRow").find(".componentQuantity").prop("disabled",true);
		$(this).closest(".componentRow").find(".componentQuantity").val("");
	}
	var len=$('.component_chk:checked').length;
	if(len > 0)
	{
		$("button[name='add']").prop("disabled",false);
	}
	else
	{
		$("button[name='add']").prop("disabled",true);
	}
});

// on check/uncheck of select all checkbox
$('#checkAllComponents').change(function()
{
	if(this.checked)
	{
		$('.component_chk').prop('checked',true);
		$(".componentQuantity").prop("disabled",false);
		$("button[name='add']").prop("disabled",false);
	}
	else
	{
		$('.component_chk').prop('checked',false);
		$(".componentQuantity").prop("disabled",true);
		$(".componentQuantity").val("");
		$("button[name='add']").prop("disabled",true);
	}
});
var component_type = $('#componentType').val();
// autocomplete part numbers
$("#part_number").autocomplete({
	source:'ac_getComponentsByPartNumbers?component_type='+component_type,
	minLength:1,
	width: 402,
	select: function( event, ui ) {
		var label = ui.item.label;
		
	} 
 });
 
 // autocomplete designations
$("#part_description").autocomplete({
	source:'ac_getComponentsByPartDesc?component_type='+component_type,
	minLength:1,
	width: 402,
	select: function( event, ui ) {
		var label = ui.item.label;
		
	} 
 });
 
  
 $(document).on('change',"#shop_id",function () { //alert($(this).val());
	var select_shop=$(this).val(); 
//var locSelect=$(this).closest('.item_row').find('.equipment_shop');
//alert("asdf");
//alert(locSelect);
//alert($(this).closest('tr').find('.Quantity').val());
	var data = 'shop_id='+select_shop;
	$.ajax({
	type:"POST",
	url:'get_equipmentType_shop_Order',
	data:data,
	cache:false,
	success:function(html){
	//alert(html);
	$("#equipment_id").html(html);
	//locSelect.prop('disabled',false);
	}
	});
});