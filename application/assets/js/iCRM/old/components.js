// validate sso id exist or not on submit
$("#component_type").change(function(){
	var component_type = parseInt($('#component_type').val()); 
	$('#shop_id option').prop('selected',false);
	switch(component_type)
	{
		case 1:
			$('#divSpares').removeClass('hidden');
			$('#uploadImage').addClass('hidden');
			$('#calibrationStatus, #expType').addClass('hidden');
			$('#equip_type_id').html('<option value="">--Select Equipment Type-</option>');
		break;
		case 3:
			$('#divSpares').addClass('hidden');
			$('#uploadImage, #expType').removeClass('hidden');
			$('#calibrationStatus').removeClass('hidden');
			$('#equip_type_id').html('<option value="0">All</option>');
		break;
		default:
			$('#divSpares').addClass('hidden');
			$('#uploadImage, #expType').removeClass('hidden');
			$('#calibrationStatus').addClass('hidden');
			$('#equip_type_id').html('<option value="0">All</option>');
		break;
	}
	
});
$("#deleteComponentIMG").click(function(){
	if(confirm("Are You Sure Want to delete Component Image")){
	var txt_image = $('#txt_image').val(); //alert(txt_image);
	var component_id = $('#component_id').val();
	if(txt_image!='' && component_id!='')
	{	var data = 'txt_image='+txt_image+'&component_id='+component_id;
		$.ajax({
		type:"POST",
		url:'delete_component_img',
		data:data,
		cache:false,
		success:function(html){
		//alert(html);
		if(html==1){
			$("#divIMGFile").remove(); 
			$("#txt_image").val('');
			$("#lblIMGFile").html('<span class="label label-primary"><i class="fa fa-check-circle"></i>  Image Removed Successfully</span>');
		} else {
			$("#lblIMGFile").html('Invalid Request');
		}
		}
		});
	}
}
});
	
 $(document).on('change',"#shop_id",function () { 
	var shop_id=$("#shop_id").val();
	var component_type=$("#component_type").val();
	if(component_type==1) 
	{
		 if(shop_id=='')
		 {
			 	alert('Please Select Shop');
		 }
		 else
		 {
		 	var data = 'shop_id='+shop_id;
			$.ajax({
			type:"POST",
			url:'get_equipment_types_by_shopid',
			data:data,
			cache:false,
			success:function(html){
			//alert(html);
			$("#equip_type_id").html(html);
			}
			});
		}
	}
	else
	{
		$("#equip_type_id").html('<option value="0">All</option>');
	}
 });
 
 $('#image').change(function(){
				
				var ext = $(this).val().split('.').pop().toLowerCase();
				if($.inArray(ext, ['jpg','jpeg','png','gif','bmp']) == -1) {
					alert('invalid extension! allowed .jpg, .png, .gif, .bmp only');
					$(this).val('');
					return false;
				}
		});
		
// check part number exist or not
$('#old_part_no').blur(function() {
			var old_part_no = $(this).val();
			var component_id = $('#component_id').val();
			if(old_part_no!='')
			{
				var data = 'old_part_no='+old_part_no+'&component_id='+component_id;
				$.ajax({
					type:"POST",
					url:'ajax/checkPartNumExist.php',
					data:data,
					cache:false,
					success:function(html){
						if(html==1)
						{
							$('#part_num_error').html('A Component already exist with the same part number');
							$('#partNumExist').val(1);
						}
						else
						{
							$('#part_num_error').html('');
							$('#partNumExist').val('');
						}
					
					}
				});
			}
        });	
	$("#componentFrm").submit(function(){
		
				var partNumExist = $('#partNumExist').val();
				if(partNumExist==1)
				{
					alert('A Component already exist with the same part number');
					//$('#poNum').focus();
					return false;
				}
	});