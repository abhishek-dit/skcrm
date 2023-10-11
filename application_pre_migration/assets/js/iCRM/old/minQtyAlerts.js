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

$('#component_chkAll').change(function(){
	
	if(this.checked)
	{
		
		$('.component_chk').prop("checked",true);
		$("button[name='add']").prop("disabled",false);
	}
	else
	{
		$('.component_chk').prop("checked",false);
		$("button[name='add']").prop("disabled",true);
	}
		
});

