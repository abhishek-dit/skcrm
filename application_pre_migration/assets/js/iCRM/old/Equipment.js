 $(document).on('change',"#shop_id",function () { 
	var shop_id=$("#shop_id").val();
		 //alert("111");
		 if(shop_id=='')
		 {
			 	//alert('Please Select Shop');
				$("#equip_type_id").html('<option value="">-Select Equipment Type-</option');
				$(this).focus();
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
	
 });
 
 // on change of search shop
  $(document).on('change',"#shop",function () { 
	var shop_id=$(this).val();
		//alert("222");
		 if(shop_id=='')
		 {
			 	//alert('Please Select Shop');
				$("#equipmentType").html('<option value="">-Select Equipment Type-</option');
				$(this).focus();
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
			$("#equipmentType").html(html);
			}
			});
		}
	
 });