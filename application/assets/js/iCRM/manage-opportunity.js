//opportunity js

$(document).on('submit','.opportunity-form',function(){
    // checking if decision makers are duplicate
    var cur_this = $(this);
    var dm1 = cur_this.find('.decision_maker1').val();
    var dms = []; var isError = false;
    var i=1;var pos;
    for(;i<=5;i++){
        var val = cur_this.find('.decision_maker'+i).val();
        if(val!=''&&i>1){
            pos = $.inArray(val,dms);
            if(pos>=0){
                //alert('same value exist at '+(pos)+' and '+i);
                //alert('Decision maker'+pos+' and Decision maker'+i+' should not be same');
                cur_this.find('.opp_error').html('Decision maker'+pos+' and Decision maker'+i+' should not be same');
                isError = true;
                return false;
            }
        }
        dms[i] = val;

    }
    var val = cur_this.find('.op_status').val();
    var remarks2 = cur_this.find('.remarks2').val();
    var opp_lost_reason = cur_this.find('.opp_lost_reason').val();
    var opp_lost_compititor = cur_this.find('.opp_lost_competitor').val();
    var comp_remarks2 = cur_this.find('.comp_remarks2').val();
    if((val=='8')&&remarks2=='')
    {
        remarks_text = 'Drop Reason';
        alert('Please enter '+remarks_text);
        isError = true;
    }
    if(val=='7' && opp_lost_reason == '')
    {
        alert('Please Select Lost Reason');
        isError = true;
    }
    if(opp_lost_reason=='15' && remarks2 == '')
    {
        alert('Please enter Lost Reason');
        isError = true;
    }
    if(val=='7'&& opp_lost_compititor == '')
    {
        alert('Please Select Lost Compititor');
        isError = true;
    }
    if(opp_lost_compititor=='29' && comp_remarks2 == '')
    {
        alert('Please enter competitor name');
        isError = true;
    }
    // changed for distributor role on 16-06-2021
    if((val=='10')&&remarks2=='')
    {
        remarks_text = 'Order Collected by Dealer Reason Reason';
        alert('Please enter '+remarks_text);
        isError = true;
    }
    // changed for distributor role on 16-06-2021 end
    if(isError){
        return false;
    }
    //alert(isError);
    //return false;
});

$(document).on('submit','.opportunity-form1',function(){
    // checking if decision makers are duplicate
    var cur_this = $(this);
    var dm1 = $('#decision_maker1').val();
    var dms = []; var isError = false;
    var i=1;var pos;
    for(;i<=5;i++){
        var val = $('#decision_maker'+i).val();
        if(val!=''&&i>1){
            pos = $.inArray(val,dms);
            if(pos>=0){
                //alert('same value exist at '+(pos)+' and '+i);
                //alert('Decision maker'+pos+' and Decision maker'+i+' should not be same');
                cur_this.find('.opp_error').html('Decision maker'+pos+' and Decision maker'+i+' should not be same');
                isError = true;
                return false;
            }
        }
        dms[i] = val;

    }
    if(isError){
        return false;
    }
    //return false;
});

  $(document).on("change",".category",function() {    
        var old_this = $(this);
        $(old_this).parents('form').find('.product').html('<option value="">Select Product</option>');
        $.ajax({
            type: "POST",
            url: SITE_URL+"getProductGroup",
            data:'category_id='+$(this).val(),
            beforeSend: function()
            {
            },
        success: function(data){
            $(old_this).parents('form').find('.group').html(data);
        }
        });
       

    });

    $(document).on("change",".group",function() {   
        var old_this = $(this);
        $.ajax({
            type: "POST",
            url: SITE_URL+"getProduct",
            data:'group_id='+$(this).val(),
            beforeSend: function()
            {
            },
        success: function(data){
            $(old_this).parents('form').find('.product').html(data);
        }
        });
         $.ajax({
            type: "POST",
            url: AJAX_CONTROLLER_URL+"getCompetitorsByProductCategory",
            data:'category_id='+$(this).val(),
            beforeSend: function()
            {
            },
            success: function(data){
                $(old_this).parents('form').find('.opportunity_competitors').html(data);
            }
        });

    });

//on change of status
$(document).on('change','.op_status',function(){
    var val = $(this).val();
    var remarks_text = 'Reason';
    //alert('hi');
    if(val=='8')
     {   
        remarks_text = 'Drop Reason <span class="req-fld">*</span>';
        $(this).closest('.formContentBlock').find('.remarks_text').html(remarks_text);
        $(this).closest('.formContentBlock').find('.remarks2').attr('required','required');
        $(this).closest('.formContentBlock').find('.remarks_fld_blk').removeClass('hidden');
    }
    else if(val=='7')
    {
        $(this).closest('.formContentBlock').find('.opp_lost_reason_div').removeClass('hidden');
        $(this).closest('.formContentBlock').find('.opp_lost_reason').attr('required','required');
        //hiding remarks block
        $(this).closest('.formContentBlock').find('.remarks_fld_blk').addClass('hidden');
        $(this).closest('.formContentBlock').find('.remarks2').removeAttr('required');
        $(this).closest('.formContentBlock').find('.remarks2').val('');
        //opening lost competitor division
        $('.opp_lost_comp_div').removeClass('hidden');
        $('.opp_lost_competitor').attr('required','required');
        //hiding competitor remarks block
        $('.comp_remarks_fld_blk').addClass('hidden');
        $('.comp_remarks2').removeAttr('required');
        $('.comp_remarks2').val('');

        $('.model_fld_blk').removeClass('hidden');

    }
    // changed for distributor role on 16-06-2021
    else if(val=='10')
    {   
       remarks_text = 'Order Collected by Dealer Reason <span class="req-fld">*</span>';
       $(this).closest('.formContentBlock').find('.remarks_text').html(remarks_text);
       $(this).closest('.formContentBlock').find('.remarks2').attr('required','required');
       $(this).closest('.formContentBlock').find('.remarks_fld_blk').removeClass('hidden');
   }
    // end
    else
    {
        $(this).closest('.formContentBlock').find('.opp_lost_reason_div').addClass('hidden');
        $(this).closest('.formContentBlock').find('.opp_lost_reason').removeAttr('required');
        $(this).closest('.formContentBlock').find('.opp_lost_reason').val('');
        //hiding remarks block
        $(this).closest('.formContentBlock').find('.remarks_fld_blk').addClass('hidden');
        $(this).closest('.formContentBlock').find('.remarks2').removeAttr('required');
        $(this).closest('.formContentBlock').find('.remarks2').val('');
        //hiding lost competitor division
        $('.opp_lost_comp_div').addClass('hidden');
        $('.opp_lost_competitor').removeAttr('required');
        $('.opp_lost_competitor').val('');
        //hiding competitor remarks block
        $('.comp_remarks_fld_blk').addClass('hidden');
        $('.comp_remarks2').removeAttr('required');
        $('.comp_remarks2').val('');

         $('.model_fld_blk').addClass('hidden');
        $('.model').val('');
    }
    
});
$(document).on('change','.opp_lost_reason',function(){
 var reason_id = $(this).val();
  var remarks_text = 'Reason';
 if(reason_id == '15')
    {   
        remarks_text = 'Lost Reason <span class="req-fld">*</span>';
        $(this).closest('.formContentBlock').find('.remarks_text').html(remarks_text);
        $(this).closest('.formContentBlock').find('.remarks2').attr('required','required');
        $(this).closest('.formContentBlock').find('.remarks_fld_blk').removeClass('hidden');
    }
    else{
        $(this).closest('.formContentBlock').find('.remarks_text').html(remarks_text);
        $(this).closest('.formContentBlock').find('.remarks_fld_blk').addClass('hidden');
        $(this).closest('.formContentBlock').find('.remarks2').removeAttr('required');
        $(this).closest('.formContentBlock').find('.remarks2').val('');
    }
});
$(document).on('change','.opp_lost_competitor',function(){
 var competitor_id = $(this).val();
  var remarks_text = 'Reason';
 if(competitor_id == '29')
    {   
        remarks_text = 'Lost Competitor Name <span class="req-fld">*</span>';
        $('.comp_remarks_text').html(remarks_text);
        $('.comp_remarks2').attr('required','required');
        $('.comp_remarks_fld_blk').removeClass('hidden');
    }
    else{
        $('.comp_remarks_text').html(remarks_text);
        $('.comp_remarks_fld_blk').addClass('hidden');
        $('.comp_remarks2').removeAttr('required');
        $('.comp_remarks2').val('');
    }
});

$(document).on('click','.cancel',function(){
        $('.opp_lost_reason_div').addClass('hidden');
        $('.opp_lost_reason').removeAttr('required');
        $('.opp_lost_reason').val('');
        //hiding remarks block
        $('.remarks_fld_blk').addClass('hidden');
        $('.remarks2').removeAttr('required');
        $('.remarks2').val('');
        //hiding lost competitor division
        $('.opp_lost_comp_div').addClass('hidden');
        $('.opp_lost_competitor').removeAttr('required');
        $('.opp_lost_competitor').val('');
        //hiding competitor remarks block
        $('.comp_remarks_fld_blk').addClass('hidden');
        $('.comp_remarks2').removeAttr('required');
        $('.comp_remarks2').val('');

         $('.model_fld_blk').addClass('hidden');
        $('.model').val('');
  
});