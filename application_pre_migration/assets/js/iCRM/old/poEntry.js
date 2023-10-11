
$('#poNumber').blur(function() {
			var poNum = $(this).val();
			if(poNum!='')
			{
				var data = 'poNum='+poNum;
				$.ajax({
					type:"POST",
					url:'ajax/checkPoNumExist.php',
					data:data,
					cache:false,
					success:function(html){
						if(html==1)
						{
							$('#po_exist_error').html('A PO Already exist with the same PO Num');
							$('#poExist').val(1);
						}
						else
						{
							$('#po_exist_error').html('');
							$('#poExist').val('');
						}
					
					}
				});
			}
        });	
	$("#poEntryFrm").submit(function(){
		
				var poExist = $('#poExist').val();
				if(poExist==1)
				{
					alert('A PO Already exist with the same PO Num');
					$('#poNum').focus();
					return false;
				}
			
		});