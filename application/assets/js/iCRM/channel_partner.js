$('#channel_partner_check').blur(function(){
    var channel_partner_check = $(this).val();
    var channel_partner_id = $('#channel_partner_id').val();
    if(channel_partner_check!=''){
        $("#channel_partnerNameValidating").removeClass("hidden");
        $("#emailError").addClass("hidden");
        var data = 'channel_partner_name='+channel_partner_check+'&channel_partner_id='+channel_partner_id;
        
        $.ajax({
        type:"POST",
        url:AJAX_CONTROLLER_URL+'is_channel_partnerNameExist',
        data:data,
        cache:false,
        success:function(html){ 
     
        $("#channel_partnerNameValidating").addClass("hidden");
            if(html==1)
            {
                $('#channel_partner_check').val('');
                $('#channel_partnerCodeError').html('Channel Partner Name <b>'+channel_partner_check+'</b> already existed');
                $("#channel_partnerCodeError").removeClass("hidden");
                return false;
            }
            else
            {   
                $('#channel_partnerCodeError').html('');
                $("#channel_partnerCodeError").addClass("hidden");
                return false;
            }
        }
        });
    }

    
});