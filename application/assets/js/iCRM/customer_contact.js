$(document).on("change", ".category_id", function () {
    var old_this = $(this);
    $.ajax({
        type: "POST",
        url: SITE_URL+"getSubCategory",
        data: 'cat_id=' + $(this).val(),
        beforeSend: function () {
            //$(this).css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function (data) {
            //alert(id);
            // $(id_obj).parent('form').find(".category_sub_id").html(data);
            $(old_this).parents('form').find('.category_sub_id').html(data);
            ;
            // alert(name1);
        }
    });

});

$(document).on("change", "#customer", function () {
    var old_this = $(this);
    $.ajax({
        type: "POST",
        url: SITE_URL+"getCustomerAddress",
        data: 'id=' + $(this).val(),
        beforeSend: function () {
            //$(this).css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function (data) {
            //alert(data);
            // $(id_obj).parent('form').find(".category_sub_id").html(data);
           $(old_this).parents('form').find('#address1').val(data);
            
            // alert(name1);
        }
    });

});

$(document).ready(function(){
    select2Ajax('checkLocation', 'cityLocation');
    select2Ajax('checkCustomer', 'getCustomer');
    
});

//validate customer code
$('#customer_code').blur(function(){
    var customer_code = $(this).val();
    var customer_id = $('#customer_id').val();
    if(customer_code!=''){
        $("#customerCodeValidating").removeClass("hidden");
        $("#emailError").addClass("hidden");
        var data = 'customer_code='+customer_code+'&customer_id='+customer_id;
        
        $.ajax({
        type:"POST",
        url:AJAX_CONTROLLER_URL+'is_customerCodeExist',
        data:data,
        cache:false,
        success:function(html){ 
    //  alert(html);
        $("#customerCodeValidating").addClass("hidden");
            if(html==1)
            {
                $('#customer_code').val('');
                $('#customerCodeError').html('Customer code <b>'+customer_code+'</b> already existed');
                $("#customerCodeError").removeClass("hidden");
                return false;
            }
            else
            {   
                $('#customerCodeError').html('');
                $("#customerCodeError").addClass("hidden");
                return false;
            }
        }
        });
    }

    
});


//validate customer code
$('#speciality_check').blur(function(){
    var speciality_check = $(this).val();
    var speciality_id = $('#speciality_id').val();
    if(speciality_check!=''){
        $("#specialityNameValidating").removeClass("hidden");
        $("#emailError").addClass("hidden");
        var data = 'speciality_name='+speciality_check+'&speciality_id='+speciality_id;
        
        $.ajax({
        type:"POST",
        url:AJAX_CONTROLLER_URL+'is_specialityNameExist',
        data:data,
        cache:false,
        success:function(html){ 
     
        $("#specialityNameValidating").addClass("hidden");
            if(html==1)
            {
                $('#speciality_check').val('');
                $('#specialityCodeError').html('Speciality Name <b>'+speciality_check+'</b> already existed');
                $("#specialityCodeError").removeClass("hidden");
                return false;
            }
            else
            {   
                $('#specialityCodeError').html('');
                $("#specialityCodeError").addClass("hidden");
                return false;
            }
        }
        });
    }

    
});

var i1 = 2;

$(document).on('click', '.addCustomerCounsult', function () {
	
   var clone="<tr class='insert_table_row'>";
                            	
                                clone+='<td  align="center">';
                                clone+='<input type="text"  class="form-control" maxlength="150"  id="competitor" value=""  name="competitors[]" placeholder="Competitors" >';
                                clone+='</td>';
                                clone+='<td  align="center">';
                                clone+='<input type="text"  class="form-control" maxlength="150"  id="product_model" value=""  name="product_model[]" placeholder="Product Model" >';
                                clone+='</td>';
                                clone+='<td  align="center">';
                                clone+='<input type="text"  class="form-control only-numbers" maxlength="4"  id="quantity" value=""  name="quantity[]" placeholder="Quantity" >';
                                clone+='</td>';
                                clone+='<td  align="center">';
                                clone+='<input type="text"  class="form-control" maxlength="150"  id="make" value=""  name="make[]" placeholder="Make" >';
                                clone+='</td>';
                                clone+='<td  align="center">';
                                clone+='<input type="text"  class="form-control only-numbers" maxlength="4"  id="year_of_purchase" value=""  name="year_of_purchase[]" placeholder="Year Of Purchase" >';
                                clone+='</td>';
                                clone+='<td  align="center">';
                                clone+='<input type="text"  class="form-control only-numbers" maxlength="4"  id="replacement_year" value=""  name="replacement_year[]" placeholder="Replacement Year" >';
                                clone+='</td>';
                            clone+='</tr>';
    $('#table1').append(clone);
        
});

$(" .delete").on('click', function () {
	cnt=$('#table1').find('.insert_table_row').length;
	
	if(cnt>'1'){
	    $('#table1').find('tr:last').remove();
	}
    // $(this).parents("tr").remove();
});