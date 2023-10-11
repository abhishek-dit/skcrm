// Custom JS for User controller
$(document).on('change', '#source', function () {
    var source_id = $(this).val();
    if (source_id == 2)
        $('.campaign').removeClass('hidden');
    else
        $('.campaign').addClass('hidden');

    if (source_id == 3)
        $('.referral').removeClass('hidden');
    else
        $('.referral').addClass('hidden');

    if (source_id == 7)
        $('.sec').removeClass('hidden');
    else
        $('.sec').addClass('hidden');
    if (source_id == 8)
        $('.colleague').removeClass('hidden');
    else
        $('.colleague').addClass('hidden');
});

$(document).on('click', '.self', function () {
    var self = $(this).attr('data-id');
    $('.self').addClass('btn-default').removeClass('btn-success');
    $(this).addClass('btn-success').removeClass('btn-default');
    if (self == 1)
    {
        $('#checkSelf').val('1');
        $('.assign').addClass('hidden');
    }
    if (self == 0)
    {
        $('#checkSelf').val('0');
        $('.assign').removeClass('hidden');
    }
});



$(document).ready(function () {
    select2Ajax('checkLocation', 'cityLocation');
    select2Ajax('checkCustomer', 'getCustomer');
    select2Ajax('checkCampaign', 'getCampaign', 0, 0);
    select2Ajax('getSEAndDistReportees', 'getReportingSEAndDistributor', 0, 0);
    select2Ajax('getReporteesWithUser', 'getReporteesWithUser', 0, 0);
    select2Ajax('getColleague', 'getColleagues', 0, 0);
    select2Ajax('checkCustomers', 'getAllCustomers');

});


$(document).on('change', ".checkCustomer", function () {
    var customer_id = $(".checkCustomer").val();
    if (customer_id != "")
    {
        var data = 'customer_id=' + customer_id;
        //alert(data);
        $.ajax({
            type: "POST",
            url: SITE_URL + 'getContact',
            data: data,
            cache: false,
            success: function (html) {
                $("#contact1").html(html);
                $("#contact2").html(html);
            }
        });
    }
    else
    {
        $('#contact1').html('<option value="">select Contact Person</option>');
        $('#contact2').html('<option value="">select Contact Person</option>');
    }
});


$(document).on('change', ".checkCustomer", function () {
    var customer_id = $(".checkCustomer").val();
    var checkRole = $("#checkRole").val();

    var check = (checkRole == 1) ? 'Sales Engineer' : 'Distributor';
    if (customer_id != "")
    {
        var data = 'customer_id=' + customer_id + '&checkRole=' + checkRole;
        //alert(data);
        $.ajax({
            type: "POST",
            url: SITE_URL + 'getSecondUser',
            data: data,
            cache: false,
            success: function (html) {
                $("#secondUser").html(html);
            }
        });
    }
    else
    {
        $('#secondUser').html('<option value="">select ' + check + '</option>');
    }
});

$(document).on('change', ".checkCustomer", function () {
    var customer_id = $(".checkCustomer").val();
    if (customer_id != "")
    {
        var data = 'customer_id=' + customer_id;
        $.ajax({
            type: "POST",
            url: SITE_URL + 'getRBH',
            data: data,
            cache: false,
            success: function (html) {
                $("#rbh").html(html);
            }
        });
    }
    else
    {
        $('#rbh').html('<option value="">Select User to Assign</option>');
    }
});


$(document).on('change', ".checkCustomer", function () {
    var customer_id = $(".checkCustomer").val();
    var role_id = $("#role").val();
    if (customer_id != "")
    {
        var data = 'customer_id=' + customer_id + '&role_id=' + role_id;
        $.ajax({
            type: "POST",
            url: SITE_URL + 'getReportees',
            data: data,
            cache: false,
            success: function (html) {
                $("#reportees").html(html);
            }
        });
    }
    else
    {
        $('#reportees').html('<option value="">Select User to Assign</option>');
    }
});

$('#submitLead').submit(function () {
    $('.color-danger').remove();
    var source_id = $('#source').val();
    var ret = true;
    if (source_id == 2)
    {
        var cam = $('#campaign').val();
        if (cam == '')
        {
            $('#campaign').after("<p class='color-danger' id='error'>This value is required</p>");
            ret = false;
        }
    }
    if (source_id == 3)
    {
        var ref = $('#ref').val();
        if (ref == '')
        {
            $('#ref').after("<p class='color-danger' id='error'>This value is required</p>");
            ret = false;
        }
    }
    if (source_id == 7)
    {
        var sec = $('#secondUser').val();
        if (sec == '')
        {
            $('#secondUser').after("<p class='color-danger' id='error'>This value is required</p>");
            ret = false;
        }
    }
    if (source_id == 8)
    {
        var colleague = $('#colleague').val();
        if (colleague == '')
        {
            $('#colleague').after("<p class='color-danger' id='error'>This value is required</p>");
            ret = false;
        }
    }

    var checkSelf = $('#checkSelf').val();
    if (checkSelf == 0)
    {
        var assignTo = $('#reportees').val();
        if (assignTo == '')
        {
            $('#reportees').after("<p class='color-danger' id='error'>This value is required</p>");
            ret = false;
        }
    }

    var contact1 = $('#contact1').val();
    var contact2 = $('#contact2').val();
   // $('#contact2').find('.color-danger').remove();
    if (contact1 == contact2)
    {
        $('#contact2').after('<span class="color-danger"> Contact Person 1 and Contact Person 2 cannot be same');
        ret = false;
    }
    return ret;
});

/// add contract note free shipping items add 
function select_all() {
    $('input[class=casez]:checkbox').each(function () {
        if ($('input[class=check_all]:checkbox:checked').length == 0) {
            $(this).prop("checked", false);
        } else {
            $(this).prop("checked", true);
        }
    });
}

var i1 = 2;

$(document).on('click', '.addline2', function () {
    var $tr = $("#table1 tr:first");
    var $lastTr = $tr.closest('#table1').find('tr:last');

    $lastTr.find('.select3').select2('destroy');

    var $clone = $lastTr.clone(true);

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
    $('#table1').append($clone);
        $lastTr.find('.select3').select2();
    $clone.find('.select3').select2();
    

});
    $('.select3').on('change', function () {
        flg=0;
        $(this).next('.select2').next('p').remove();
        new_selected=$(this).val();
        //new_index=$(this).parent('.select3').index();
        new_index=$('.select3').index($(this));
        
        $('.select3').each(function (index, value) { 
        $exist_product=$(this).val();
        
        if($exist_product == new_selected && index!=new_index && $exist_product !=""){
         flg=1;
        }
        
        
        });
        if(flg==1){
                $(this).val('');
                $(this).next('.select2').after("<p class='color-danger' id='error'>Please select Unique Product.</p>");
        }
  });
/****/

$(document).ready(function(){
$('.select3').select2();
});



$(" .delete").on('click', function () {
	cnt=$(this).parents('table').find('tr').length;
	
	if(cnt>'2'){
	    $(this).parents("tr").remove();
	}
    // $(this).parents("tr").remove();
});

$(document).on('submit','.lead_form',function(e){
    var resource_requirement = $('input[name="resource_requirement"]:checked').val();
    if(resource_requirement=='1'){
        var resource_requirement_info = $('#resource_required_details').val();
        if(resource_requirement_info==''){
            alert('Please enter resource requirement info details');
            return false;
        }
    }
});

$(document).on('change','.resource_requirement',function(){

    $('.resource_requirement').parents('.iradio_square-blue').removeClass('checked');
    $(this).parent('.iradio_square-blue').addClass('checked');
    var val = $(this).val();
    //alert(val);
    if(val==1){
        $('#resource_info_fld').removeClass('hidden');
    }
    else{
        $('#resource_info_fld').addClass('hidden');
        $('#resource_required_details').val('');
    }
});

// Free supply items value display in New contract note 
$(document).on('change','.free_item',function(){
    var unit_price = parseInt($(this).find('option:selected').data('unitprice'));
    //alert(unit_price);
    $(this).closest('.free_item_row').find('.free_item_unitprice').val(unit_price);
    var qty = $(this).closest('.free_item_row').find('.free_item_qty').val();
    $(this).closest('.free_item_row').find('.free_item_value').html(qty*unit_price);
});
$(document).on('keyup','.free_item_qty',function(){
    var qty = $(this).val();
    var unit_price = $(this).closest('.free_item_row').find('.free_item_unitprice').val();
    //alert(qty+'--'+unit_price);
    $(this).closest('.free_item_row').find('.free_item_value').html(qty*unit_price);
});

