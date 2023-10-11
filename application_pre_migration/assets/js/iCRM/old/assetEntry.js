
$('#asset_no').blur(function() {
			var asset_no = $(this).val();
			if(asset_no!='')
			{
				var data = 'asset_no='+asset_no;
				$.ajax({
					type:"POST",
					url:'ajax/checkAssetNumExist.php',
					data:data,
					cache:false,
					success:function(html){
						if(html==1)
						{
							$('#asset_exist_error').html('A Grn Asset Already exist with the same Asset Number');
							$('#assetExist').val(1);
						}
						else
						{
							$('#asset_exist_error').html('');
							$('#assetExist').val('');
						}
					
					}
				});
			}
        });	
	$("#assetEntryFrm").submit(function(){
		
				var assetExist = $('#assetExist').val();
				if(assetExist==1)
				{
					alert('A Grn Asset Already exist with the same Asset Number');
					//$('#poNum').focus();
					return false;
				}
			
		});