//$(document).on("click", "#add_quote", function () {
//    var old_this = $(this);
//    //alert('wwwww');
//    //$(".modal-body").html("wokring");
//   
//    $.ajax({
//        type: "POST",
//        url: SITE_URL+"addQuote",
//        data: 'id=' + $(this).data('id'),
//        beforeSend: function () {
//            //$(this).css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
//        },
//        success: function (data) {
//            //alert(id);
//            // $(id_obj).parent('form').find(".category_sub_id").html(data);
//            $('.modal-body').html(data);
//            // alert(name1);
//        }
//    });
//   
//});
$(document).on("change", "#billing", function () {
    var old_this1 = $(this);
    //alert('wwwww');
    //$(".modal-body").html("wokring");
   stokist_id=$(this).val();
   if(stokist_id =='3'){
        $.ajax({
            type: "POST",
            url: SITE_URL+"getQuoteStokist",
            data: 'id=' +stokist_id,
            beforeSend: function () {
                //$(this).css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
            },
            success: function (data) {
                $("#stokist_div").css('display','block');
                $("#stokist_id").html(data);
                
            }
        });
    }else{
         $("#stokist_div").css('display','none');
    }
});


$('#quotation_frm').submit(function () {
	var source_id = $('#source').val();
	var ret = true;
        
        var op_check=$("input.op:checked").length;
        if(op_check==0){
             $('input.op').parents('table').next('p').remove();
                $('input.op').parents('table').after("<p class='color-danger' id='error'>Please Select Atleast One Opportunity</p>");
                ret = false;
        }else{
             $('input.op').parents('table').next('p').remove();
        }
        var billing = $('#billing').val();
        if(billing == 3)
        {
            var stockist = $('#stokist_id').val();
            if(stockist == 0)
            {
                $('#stokist_id').next('span').next('p').remove();
                $('#stokist_id').next('span').after("<p class='color-danger' id='error'>Please select stockist</p>");
                ret = false;
            }else{
                
                $('#stokist_id').next('span').next('p').remove();
            }
        }
//        var discount=$('#discount').val();
//        if(discount == '')
//            {
//                $('#discount').val('0');
//               
//            }
/*        var discount=$('#discount').val();    
        if(!$.isNumeric(discount))
            {
                $('#discount').siblings('p').remove();
                $('#discount').after("<p class='color-danger' id='error'>Please Enter Nubers</p>");
                
                ret = false;
            }else{
                $('#discount').siblings('p').remove();
            }    
*/                
                
        /*****************************************************/        
	
	return ret;
});

//FOR Add New Revision to Quote
$(document).on("change", ".billing", function () {
    var old_this1 = $(this);
   stokist_id=$(this).val();
   if(stokist_id =='3'){
        $.ajax({
            type: "POST",
            url: SITE_URL+"getQuoteStokist",
            data: 'id=' +stokist_id,
            beforeSend: function () {
                //$(this).css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
            },
            success: function (data) {
                $(".stokist_div").css('display','block');
                $(".stokist_id").html(data);
                
            }
        });
    }else{
         $(".stokist_div").css('display','none');
    }
});
$('#quote_revision_frm').submit(function () {
	
        ret=true;
        
        var billing = $('#billing_rev').val();
        
        if(billing == 3)
        {
            var stockist = $('#stokist_id_rev').val();
           // alert(stockist);
            if(stockist == 0)
            {
                $('#stokist_id_rev').next('span').next('p').remove();
                $('#stokist_id_rev').next('span').after("<p class='color-danger' id='error'>Please select stockist</p>");
            
            ret = false;
            }else{
                
                $('#stokist_id_rev').next('span').next('p').remove();
            }
        }
        /*var discount=$('#discount_rev').val();    
       
        if(!$.isNumeric(discount))
            {
                $('#discount_rev').siblings('p').remove();
                $('#discount_rev').after("<p class='color-danger' id='error'>Please Enter Nubers</p>");
                
                ret = false;
            }else{
                $('#discount_rev').siblings('p').remove();
            }    */
                
                
        /*****************************************************/        
	
	return ret;
});


$('.op').change(function()
{
    if(this.checked)
    {
        //alert("checked");
        $(this).closest(".opprow").find(".subcat").prop("disabled",false);

    }
    else
    {
        //alert("unchecked");
        $(this).closest(".opprow").find(".subcat").prop("disabled",true);
        $(this).closest(".opprow").find(".subcat option:eq(0)").prop("selected",true);
    }
    hideBalancePaymentDays();
});


$('#quoteDiscount1').submit(function () {
    var rbh = $('#rbh').val();
    var nsm = $('#nsm').val();
    var ch = $('#ch').val();
    
    var ret = true;
    if(nsm != ch)
    {
        $('#nsm').after("<p class='color-danger' id='error'>Min of CH and Max of NSM should match</p>");
        ret = false;
    }
    if(rbh > nsm || rbh > ch)
    {
        $('#rbh').after("<p class='color-danger' id='error'>Max of RBH cannot be more than max of NSM/CH</p>");
        ret = false;
    }
    return ret;
});


$(document).on('change','.quote_op', function(){
    if(this.checked)
    {
        $(this).closest('.quote_opprow').find('.op_discount').prop('readonly',false);
    }
    else
    {
        $(this).closest('.quote_opprow').find('.op_discount,.discount_type').prop('readonly',true);
    }
});

$(document).on('change','.free_check', function(){
    if(this.checked)
    {
        $(this).closest('.free_row').find('.free_supplys').removeClass('hidden');
    }
    else
    {
        cnt=$(this).closest('.free_row').find('.free_table tr').length;
        if(cnt>'2')
        {
            $(this).closest('.free_row').find('.free_table tr:gt(1)').remove();
        }
        $(this).closest('.free_row').find('.select3').val('').change();
        $(this).closest('.free_row').find('.free_item_qty').val('');
        $(this).closest('.free_row').find('.free_supplys').addClass('hidden');
    }
});

$(document).on('click', '.add_free_item', function () {
    //alert('hello');
    var $tr = $(this).closest('.free_row').find(".free_table tr:first");
    var $lastTr = $(this).closest('.free_row').find('.free_table tr:last');

    $lastTr.find('.select3').select2('destroy');


    var $clone = $lastTr.clone(true);
    $clone.find('.free_item_qty').val('');
    $clone.find('td').each(function() {
        var el = $(this).find(':first-child');
        var id = el.attr('id') || null;
        if (id) {
            var i = id.substr(id.length - 1);
            var prefix = id.substr(0, (id.length - 1));
            el.attr('id', prefix + (+i + 1));
            //el.attr('name', prefix + (+i + 1));
        }
    });
    $(this).closest('.free_row').find('.free_table').append($clone);
        $lastTr.find('.select3').select2();
    $clone.find('.select3').select2();
    

});

$(document).on('click', '.delete_free_item', function () {
    cnt=$(this).parents('.free_table').find('tr.free_product_row').length;
     if(cnt>'1'){
        $(this).parents('tr.free_product_row').remove();
    }
    else
    {
        $(this).closest('.free_product_row').find('.select3').val('').change();
        $(this).closest('.free_product_row').find('.free_item_qty').val('');
    }
    
});

$(document).on('keyup', '.op_discount', function () {
    //alert('hello');
    var discount = $(this).closest('.quote_opprow').find('.op_discount').val();
    var discount_type = $(this).closest('.quote_opprow').find('.discount_type').val();
    var total_value = parseInt($(this).closest('.quote_opprow').find('.opp_value').val());
    var disc_val_ele = $(this).closest('.quote_opprow').find('.discounted_value');
    var opp_val_ele = $(this).closest('.quote_opprow').find('.order_val');
    calculateDiscountedValue(discount,discount_type,total_value,disc_val_ele,opp_val_ele);
    hideBalancePaymentDays();
    
});
$(document).on('change', '.discount_type', function () {
    
    var discount = $(this).closest('.quote_opprow').find('.op_discount').val();
    var discount_type = $(this).closest('.quote_opprow').find('.discount_type').val();
    var total_value = parseInt($(this).closest('.quote_opprow').find('.opp_value').val());
    switch(discount_type)
    {
        case '1':
            $(this).closest('.quote_opprow').find('.op_discount').attr('max',100);
        break;
        case '2':
            $(this).closest('.quote_opprow').find('.op_discount').attr('max',total_value);
        break;
    }
    
    var disc_val_ele = $(this).closest('.quote_opprow').find('.discounted_value');
    var opp_val_ele = $(this).closest('.quote_opprow').find('.order_val');
    calculateDiscountedValue(discount,discount_type,total_value,disc_val_ele,opp_val_ele);
    hideBalancePaymentDays();
});
function calculateDiscountedValue(discount,discount_type,total_value,disc_val_ele,opp_val_ele)
{
    discount = (!isNaN(discount))?parseFloat(discount) : 0;
    total_value = (!isNaN(total_value))?parseInt(total_value) : 0;
    var discount_amt = 0;
    switch(discount_type)
    {
        case '1':
            discount_amt = Math.round((total_value * discount) / 100 );
        break;
        case '2':
            discount_amt = Math.round(discount);
        break;
    }
    var discounted_value = total_value - discount_amt;
    disc_val_ele.html(discounted_value);
    opp_val_ele.val(discounted_value);
}

$(document).on('change', '.billing', function () {
    
    var billing = $(this).val();
    if(billing=='2')
    {
        $('#dealer_commission_row,#dealer_row').addClass('hidden');
        $('#dealer').val(null).trigger("change");  
        $('#dealer_commission').val('');
    }
    else
    {
        $('#dealer_commission_row').removeClass('hidden');
    }
});


// In Quote Add   start
$('#advance_collected').blur(function(){
    hideBalancePaymentDays();
});

$(document).on('change','#advance_type',function(){
    hideBalancePaymentDays();
});


function hideBalancePaymentDays()
{
    var advance_type = $('#advance_type').val();
    var advance = parseInt($('#advance_collected').val());
    var quote_value = getQuoteValue();
    if(advance_type=='1')
    {
        $('#advance_collected').attr('max',100);
    }
    else
    {
        $('#advance_collected').attr('max',quote_value);
    }

    if((advance_type=='1'&&advance=='100')||(advance_type=='2'&&advance==quote_value))
    {
        $('.bal_payment_block').addClass('hidden');
        $('#balance_payment_days').val('');
        $('#balance_payment_days').attr('min',0);
        $('#balance_payment_days').prop('required',false);
    }
    else
    {
        $('#balance_payment_days').attr('min',1);
        $('.bal_payment_block').removeClass('hidden');
        $('#balance_payment_days').prop('required',true);
    }
}

function getQuoteValue()
{
    var quote_value = 0;
    $('.op:checked').each(function(){
        var opp_value = $(this).closest('.op_row').find('.order_val').val();
        quote_value += parseInt(opp_value);
    });
    return quote_value;
}



// $(document).on('keyup','#dealer_commission',function(){
//     var dealer_commission = $(this).val();
//     if(dealer_commission>0)
//     {
//         $('#dealer_row').removeClass('hidden');
//     }
//     else
//     {
//         $('#dealer_row').addClass('hidden'); 
//         $('#dealer').val(null).trigger("change");   
//     }
// });

$(document).on('change','.rev_quote_op',function(){
    var opp_id = $(this).val();
    if(this.checked)
    {
        $('#free_'+opp_id).removeClass('hidden');
        $(this).closest('.quote_opprow').find('.op_discount,.discount_type').prop('disabled',false);
    }
    else
    {
        $('#free_'+opp_id).addClass('hidden');
        var prev_disc_type = $(this).closest('.quote_opprow').find('.prev_disc_type').val();
        var prev_disc = $(this).closest('.quote_opprow').find('.prev_disc').val();
        var prev_disc_val = $(this).closest('.quote_opprow').find('.prev_disc_val').val();
        $(this).closest('.quote_opprow').find('.discount_type').val(prev_disc_type);
        $(this).closest('.quote_opprow').find('.op_discount').val(prev_disc);
        $(this).closest('.quote_opprow').find('.discounted_value').html(prev_disc_val);
        $(this).closest('.quote_opprow').find('.op_discount,.discount_type').prop('disabled',true);

    }
});
// In Quote Add   end