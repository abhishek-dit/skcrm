//Order cart  page JS functions
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

// Remove Component from order cart	
$(".removeOrderRow").click(function(){
	var order_id = $(this).attr('data-cid'); 
	var orderRow = $(this).closest(".orderRow");
	$("#orderCartContainer").css("opacity",0.5);
	$("#loaderID").css("opacity",1);
	var data = 'order_id='+order_id;
		$.ajax({
		type:"POST",
		url:'ajax/removeOrderFromPRCart.php',
		data:data,
		cache:false,
		success:function(html){
			//alert(html);
			orderRow.remove();
			$("#orderCartContainer").css("opacity",1);
			$("#loaderID").css("opacity",0);
		}
		});

});

$('#prNum').blur(function() {
			var prNum = $(this).val();
			if(prNum!='')
			{
				var data = 'prNum='+prNum;
				$.ajax({
					type:"POST",
					url:'ajax/checkPrNumExist.php',
					data:data,
					cache:false,
					success:function(html){
						if(html==1)
						{
							$('#pr_exist_error').html('A PR Already exist with the same PR Num');
							$('#prExist').val(1);
						}
						else
						{
							$('#pr_exist_error').html('');
							$('#prExist').val('');
						}
					
					}
				});
			}
        });	
	$("#submitPrFrm").submit(function(){
		
				var prExist = $('#prExist').val();
				if(prExist==1)
				{
					alert('A PR Already exist with the same PR Num');
					$('#prNum').focus();
					return false;
				}
			
		});