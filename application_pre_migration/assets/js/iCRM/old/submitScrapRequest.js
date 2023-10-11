// Remove Spare from Repair cart	
$(".removecomponentRow").click(function(){
	var component_id = $(this).attr('data-cid'); 
	var componentRow = $(this).closest(".componentRow");
	$("#orderCartContainer").css("opacity",0.5);
	$("#loaderID").css("opacity",1);
	var data = 'component_id='+component_id;
		$.ajax({
		type:"POST",
		url:'ajax/removeComponentsFromScrapCart.php',
		data:data,
		cache:false,
		success:function(html){
			//alert(html);
			componentRow.remove();
			$("#orderCartContainer").css("opacity",1);
			$("#loaderID").css("opacity",0);
			var comp_count = $('.componentIds').length;
			//alert(comp_count);
			if(comp_count==0)
			{
				$('#submitScrap').prop('disabled',true);
			}
		}
		});
		
	

});



