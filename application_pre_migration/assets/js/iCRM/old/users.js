// User master page JS functions
$('#role').change(function() {
    
	var role = parseInt($(this).val());
	switch(role)
	{
		case 1: case 2: case 3: case 8: case 9: case 10:
			$('#shopGroup').removeClass('hidden');
			$('.shop').prop('checked',false);
			$('.shop0').prop('checked',false);
		break;
		/*case 6:
			$('#shopGroup').addClass('hidden');
			$('.shop').prop('checked',true);
		break;*/
		default:
			$('#shopGroup').addClass('hidden');
			//$('.shop').prop('checked',true);
			$('.shop0').prop('checked',true);
		break;
	}
});
// check if user already exist with sso id
$('#sso_id').blur(function() {
			var sso_id = $(this).val();
			var pk_sso_id = $('#pk_sso_id').val();
			if(sso_id!=''&&pk_sso_id=='')
			{
				var data = 'sso_id='+sso_id;
				$.ajax({
					type:"POST",
					url:'checkUserSsoIdExists',
					data:data,
					cache:false,
					success:function(html){
						if(html==1)
						{
							$('#ssoId_exist_error').html('A user already exists with this SSO ID');
							$('#ssoIdExist').val(1);
						}
						else
						{
							$('#ssoId_exist_error').html('');
							$('#ssoIdExist').val('');
						}
					
					}
				});
			}
        });

// validate sso id exist or not on submit		
$("#userForm").submit(function(){
	var pk_sso_id = $('#pk_sso_id').val();
	if(pk_sso_id=='')
	{	
		var ssoIdExist = $('#ssoIdExist').val();
		if(ssoIdExist==1)
		{
			alert('A user already exists with this SSO ID');
			return false;
		}
	}
	
	var role = parseInt($('#role').val());
	switch(role)
	{
		case 1: case 2: case 3: case 8: case 9: case 10:
			var num = $('.shop:checked').length;
			if(num<=0)
			{
				alert('Please choose atleast one shop');
				return false;
			}
		break;
	}

});
