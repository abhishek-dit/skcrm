// User master page JS functions
// on check/uncheck of order checkbox
$('.order_chk').change(function()
{
	var len=$('.order_chk:checked').length;
	if(len > 0)
	{
		$("button[name='addPR']").prop("disabled",false);
	}
	else
	{
		$("button[name='addPR']").prop("disabled",true);
	}
});

// on check/uncheck of select all checkbox
$('#checkAllOrders').change(function()
{
	if(this.checked)
	{
		$('.order_chk').prop('checked',true);
		$("button[name='addPR']").prop("disabled",false);
	}
	else
	{
		$('.order_chk').prop('checked',false);
		$("button[name='addPR']").prop("disabled",true);
	}
});
var order_type = $('#orderType').val();
// autocomplete part numbers
$("#partNumber").autocomplete({
	source:'ajax/ac_getComponentsByPartNumbers.php?order_type='+order_type,
	minLength:1,
	width: 402,
	select: function( event, ui ) {
		var label = ui.item.label;
		
	} 
 });
 
 // autocomplete designations
$("#part_description").autocomplete({
	source:'ajax/ac_getComponentsByPartDesc.php?order_type='+order_type,
	minLength:1,
	width: 402,
	select: function( event, ui ) {
		var label = ui.item.label;
		
	} 
 });