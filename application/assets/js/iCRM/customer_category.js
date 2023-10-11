//validate customer category
$('#category_check').blur(function(){
    var category_check = $(this).val();
    var category_id = $('#category_id').val();
    if(category_check!=''){
        $("#categoryNameValidating").removeClass("hidden");
        $("#emailError").addClass("hidden");
        var data = 'category_name='+category_check+'&category_id='+category_id;
        
        $.ajax({
        type:"POST",
        url:AJAX_CONTROLLER_URL+'is_categoryNameExist',
        data:data,
        cache:false,
        success:function(html){ 
     
        $("#categoryNameValidating").addClass("hidden");
            if(html==1)
            {
                $('#category_check').val('');
                $('#categoryCodeError').html('Category Name <b>'+category_check+'</b> already existed');
                $("#categoryCodeError").removeClass("hidden");
                return false;
            }
            else
            {   
                $('#categoryCodeError').html('');
                $("#categoryCodeError").addClass("hidden");
                return false;
            }
        }
        });
    }

    
});



$('#sub_category_check').blur(function(){
    var sub_category_check = $(this).val();
    var sub_category_id = $('#sub_category_id').val();
    var category_id = $('#category').val();
    
    if(sub_category_check!=''){
        $("#subcategoryNameValidating").removeClass("hidden");
        $("#emailError").addClass("hidden");
        var data = 'category_name='+sub_category_check+'&sub_category_id='+sub_category_id+'&category_id='+category_id;
        
        $.ajax({
        type:"POST",
        url:AJAX_CONTROLLER_URL+'is_subcategoryNameExist',
        data:data,
        cache:false,
        success:function(html){ 
     
        $("#subcategoryNameValidating").addClass("hidden");
            if(html==1)
            {
                $('#sub_category_check').val('');
                $('#subcategoryCodeError').html('Sub Category Name <b>'+sub_category_check+'</b> already existed');
                $("#subcategoryCodeError").removeClass("hidden");
                return false;
            }
            else
            {   
                $('#subcategoryCodeError').html('');
                $("#subcategoryCodeError").addClass("hidden");
                return false;
            }
        }
        });
    }

    
});