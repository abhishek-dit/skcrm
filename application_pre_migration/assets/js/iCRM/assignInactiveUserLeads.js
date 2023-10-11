
$(document).ready(function(){
    select2Ajax('getInactiveUsersWithOpenLeads', 'getInactiveUsersWithOpenLeads', 0, 0);
    select2Ajax('getActiveUsersToAssignLeads', 'getActiveUsersToAssignLeads', 0, 0);
});

$(document).on('change','#checkall_leads',function(){
	if(this.checked){
		$('.chk_lead').prop('checked',true);
	}
	else{
		$('.chk_lead').prop('checked',false);
	}
});

$(document).on('click','#submit_assignLeads',function(){

	var assign_user = $('#assign_user').val();
	var chk_lead_len = $('.chk_lead:checked').length;
	if(assign_user==''){
		alert('Please choose a user to assign leads');
		return false;
	}
	if(chk_lead_len<=0){
		alert('Please choose atleast one lead to assign');
		return false;
	}
	
	var data = $('#assignInactiveUserLeads_frm').serialize();
		$('#validating_leadLocations').removeClass('hidden');
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'checkLocations_assignInactiveUserOpenLeads',
			data:data,
			cache:false,
			success:function(html){ 
				$('#validating_leadLocations').addClass('hidden');
				if(html!=''){
					alert(html);
					return false;
				}
				else{
					$('#assignInactiveUserLeads_frm').submit();
				}
			}
		});


});

