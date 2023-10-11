//validate Branch Name
$('#branch_check').blur(function(){
    var branch_check = $(this).val();
    var branch_id = $('#branch_id').val();
    if(branch_check!=''){
        $("#branchNameValidating").removeClass("hidden");
        $("#emailError").addClass("hidden");
        var data = 'branch_name='+branch_check+'&branch_id='+branch_id;
        
        $.ajax({
        type:"POST",
        url:AJAX_CONTROLLER_URL+'is_branchNameExist',
        data:data,
        cache:false,
        success:function(html){ 
     
        $("#branchNameValidating").addClass("hidden");
            if(html==1)
            {
                $('#branch_check').val('');
                $('#branchCodeError').html('Branch Name <b>'+branch_check+'</b> already existed');
                $("#branchCodeError").removeClass("hidden");
                return false;
            }
            else
            {   
                $('#branchCodeError').html('');
                $("#branchCodeError").addClass("hidden");
                return false;
            }
        }
        });
    }

    
});

$(document).on('change','#geo',function(){
    var locationParentId = $(this).val(); 
    //alert(client_id);
    
     if(locationParentId!="")
     {
         
        var data = 'locationParentId='+locationParentId;
        
        $.ajax({
            type:"POST",
            url:AJAX_CONTROLLER_URL+'getbranch_CountriesByGeo',
            data:data,
            cache:false,
            success:function(html){ 
                
                $('#country,#countryblock,#edit_country').html(html);
                $('.country-group').removeClass('hidden');
                $('.edit-country-group').removeClass('hidden');
                $('.edit-region-group').addClass('hidden');
                $('.region-group').addClass('hidden');
            }
        });
        
     }
     else {
         $('.country-group, .region-group').addClass('hidden');
         $('.edit-country-group').addClass('hidden');
         
         $('#country,#region,#edit_country,#edit_region').html('<option value="">select</option>');

         
     }
     
});

$(document).on('change','#country,#edit_country',function(){
    var locationParentId = $(this).val(); 
    
     if(locationParentId!="")
     {
         
        var data = 'locationParentId='+locationParentId;
        
        $.ajax({
            type:"POST",
            url:AJAX_CONTROLLER_URL+'getbranch_RegionsByCountry',
            data:data,
            cache:false,
            success:function(html){ 
                
                $('#region,#edit_region').html(html);
                $('.region-group').removeClass('hidden');
                $('.edit-region-group').removeClass('hidden');
            }
        });
        
     }
     else {
         $('.region-group').addClass('hidden');
         $('.edit-region-group').addClass('hidden');
         $('#region,#edit_region').html('<option value="">select</option>');
         
     }
     
});

