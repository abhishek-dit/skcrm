$('.icheck').change(function()
{
	if(this.checked)
	{
		//alert("checked");
		$(this).closest(".spareRow").find(".spareQuantity").prop("disabled",false);
		$(this).closest(".spareRow").find(".spareQuantity").val("1");
	}
	else
	{
		//alert("unchecked");
		$(this).closest(".spareRow").find(".spareQuantity").prop("disabled",true);
		$(this).closest(".spareRow").find(".spareQuantity").val("");
	}
	var len=$('.icheck:checked').length;
	if(len > 0)
	{
		$("button[name='add']").prop("disabled",false);
	}
	else
	{
		$("button[name='add']").prop("disabled",true);
	}
});
/*$('.removeRow').click(function()
{
		//alert("checked");
		$(this).closest(".spareSelectedRow").remove();
});*/


$('.spareQuantity').blur(function()
{
	var max_qty = parseInt($(this).attr('max'));
	var val = parseInt($(this).val());
	//alert(max_qty + '--' + val);
	if(val > max_qty)
	{
		alert("Quantity cannot be more than "+max_qty);
		$(this).val("");
		$(this).focus();
	}

	//alert(max_qty);
});


$('.removeRow').click(function(){
	//alert(component_id);
	if(confirm('Are you sure you want to remove'))
	{
		var component_id = parseInt($(this).attr('data-cid')); 
		var componentRow = $(this).closest(".spareSelectedRow");
		//alert(component_id);
		$("#orderCartContainer").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		var data = 'component_id='+component_id;
		//alert(data);
			$.ajax({
			type:"POST",
			url:'removeComponentFromIssueCart',
			data:data,
			cache:false,
			success:function(html){
				//alert(html);
				componentRow.remove();
				$("#orderCartContainer").css("opacity",1);
				$("#loaderID").css("opacity",0);
			}
			});
	}
});


$('.removeRow1').click(function(){
	//alert(component_id);
	if(confirm('Are you sure you want to remove'))
	{
		var component_id = parseInt($(this).attr('data-cid')); 
		var location_id = parseInt($(this).attr('data-lid'));
		var componentRow = $(this).closest(".spareSelectedRow");
		//alert(component_id);
		$("#orderCartContainer").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		var data = 'component_id='+component_id;
		//alert(data);
			$.ajax({
			type:"POST",
			url:'removeComponentFromCalibrateCart?location_id='+location_id,
			data:data,
			cache:false,
			success:function(html){
				//alert(html);
				componentRow.remove();
				$("#orderCartContainer").css("opacity",1);
				$("#loaderID").css("opacity",0);
			}
			});
	}
});


var component_type = $('#componentType').val();

//var calibration_status = $('#componentType').val();
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
 
 
 $(document).on('change',".select_shop",function () { //alert($(this).val());
var select_shop=$(this).val(); 
//var locSelect=$(this).closest('.item_row').find('.equipment_shop');
//alert("asdf");
//alert(locSelect);
//alert($(this).closest('tr').find('.Quantity').val());
	var data = 'shop_id='+select_shop;
	$.ajax({
	type:"POST",
	url:'get_equipment_shop?sho_id='+select_shop,
	data:data,
	cache:false,
	success:function(html){
	//alert(html);
	$("#equipment_id").html(html);
	//locSelect.prop('disabled',false);
	}
	});
});


 $(document).on('change',".shop_i",function () { //alert($(this).val());
var select_shop=$(this).val(); 
//var locSelect=$(this).closest('.item_row').find('.equipment_shop');
//alert("asdf");
//alert(locSelect);
//alert($(this).closest('tr').find('.Quantity').val());
	var data = 'shop_id='+select_shop;
	$.ajax({
	type:"POST",
	url:'get_equipmentType_shop',
	data:data,
	cache:false,
	success:function(html){
	//alert(html);
	$("#equipment_id").html(html);
	//locSelect.prop('disabled',false);
	}
	});
});

$(".cancelScrap").click(function(){
	var t_id = $(this).attr('data-id'); 
	$("#openOrdersContainer").css("opacity",0.5);
	$("#loaderID").css("opacity",1);
	var data = 't_id='+t_id;
		$.ajax({
		type:"POST",
		url:'scrapRequestCancel',
		data:data,
		cache:false,
		success:function(html){
			//alert(html);
			$("#openOrdersContainer").css("opacity",1);
			$("#loaderID").css("opacity",0);
		}
		});
});


$('.removeScrapRow').click(function(){
	//alert(component_id);
	if(confirm('Are you sure you want to remove'))
	{
		var component_id = parseInt($(this).attr('data-cid')); 
		var location_id = parseInt($(this).attr('data-lid'));
		var scrap_type = $(this).attr('data-type');
		var componentRow = $(this).closest(".spareSelectedRow");
		//alert(component_id);
		$("#orderCartContainer").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		var data = 'scrap_type='+scrap_type+'&component_id='+component_id+'&location_id='+location_id;
		//alert(data);
			$.ajax({
			type:"POST",
			url:'removeComponentFromScrapCart',
			data:data,
			cache:false,
			success:function(html){
				//alert(html);
				componentRow.remove();
				$("#orderCartContainer").css("opacity",1);
				$("#loaderID").css("opacity",0);
			}
			});
	}
});
