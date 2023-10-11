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
	source:'ajax/ac_getComponentsByPartNumbers.php?component_type='+component_type,
	minLength:1,
	width: 402,
	select: function( event, ui ) {
		var label = ui.item.label;
		
	} 
 });
 
 // autocomplete designations
$("#part_description").autocomplete({
	source:'ajax/ac_getComponentsByPartDesc.php?component_type='+component_type,
	minLength:1,
	width: 402,
	select: function( event, ui ) {
		var label = ui.item.label;
		
	} 
 });