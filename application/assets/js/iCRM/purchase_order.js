$(document).ready(function(){
$('.select3').select2();
});

$(document).on('change','.segment',function(){
  var segment_id = $(this).val();
  var product_ele = $(this).closest('.product_row').find('.product_list');
  $.ajax({
            type: "POST",
            url: SITE_URL+"getUserProductsBySegment",
            data: 'segment_id=' +segment_id,
            beforeSend: function () {
                //$(this).css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
            },
            success: function (data) {
                product_ele.html(data);                
            }
        });
});

$(document).on('change','.product_list',function(){
  var unit_price = $(this).find(':selected').attr('data-unitPrice');
  var unit_currency = $(this).find(':selected').attr('data-currency');
  $(this).closest('.product_row').find('.unit_price_display').html(unit_price);
  $(this).closest('.product_row').find('.unit_price').val(unit_price);
  $(this).closest('.product_row').find('.unit_currency').val(unit_currency);
});

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
    $clone.find('.segment').prop('selected',false);
    $clone.find('.unit_price').val('');
    $clone.find('.unit_price_display').html('');
    $clone.find('.product_qty').val('');
    $clone.find('.discount_type').prop('selected',false);
    $clone.find('.discount').val('');
    $clone.find('.discounted_value').val('');
    $clone.find('.discounted_value_display').html('');
});

$(" .delete").on('click', function () {
    cnt=$(this).parents('table').find('tr').length;
    
    if(cnt>'2'){
        $(this).parents("tr").remove();
        calculateTotal();
        calculate_extra_warranty();
    }
});

$(document).on('keyup change', '.product_qty', function () {
    var ele = $(this).closest('.product_row');
    setDiscountUpperLimit(ele);
    calculateDiscountedValue(ele); 
    calculate_extra_warranty();
});

$(document).on('keyup change', '.discount', function () {
  var ele = $(this).closest('.product_row');
  calculateDiscountedValue(ele);
  calculate_extra_warranty();
  setDiscountUpperLimit(ele);
    
});

$(document).on('change', '.discount_type', function () {
  var ele = $(this).closest('.product_row');
  setDiscountUpperLimit(ele);
  calculateDiscountedValue(ele);
 // calculate_extra_warranty();
});

function calculateDiscountedValue(product_row_element)
{
    var discount = product_row_element.find('.discount').val();
    var discount_type = product_row_element.find('.discount_type').val();
    var unit_price = product_row_element.find('.unit_price').val();
    var qty = parseInt(product_row_element.find('.product_qty').val());
    var total_value = parseInt(unit_price)*qty;
    
    if(isNaN(discount))
      discount=0;
    total_value = (!isNaN(total_value))?parseInt(total_value) : 0;
    var discount_amt = 0;
    //alert(unit_price+'-->'+qty+'-->'+total_value+'-->'+discount_type+'-->'+discount);
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
    product_row_element.find('.discounted_value_display').html(discounted_value);
    product_row_element.find('.discounted_value').val(discounted_value);
    var total_val = calculateTotal();
    
    $('#total_value_display').html(total_val);
    $('#total_value').val(total_val);
    calculate_extra_warranty()
    
}

function calculateTotal()
{
  var total_val = 0;
  $('.discounted_value').each(function(){
      var discounted_value = $(this).val();
      discounted_value = (!isNaN(discounted_value))?parseInt(discounted_value) : 0;
      total_val += discounted_value;
  });
  var advance_type = $('#advance_type').val();
  if(advance_type=='2'){ $('#advance_collected').attr('max',total_val);}
  return total_val;
}

function setDiscountUpperLimit(ele)
{
  var unit_price = ele.find('.unit_price').val();
  var qty = ele.find('.product_qty').val();
  var discount_type = ele.find('.discount_type').val();
  if(isNaN(qty))
    qty = 0;
  if(isNaN(unit_price))
    unit_price = 0;
  var total_value = parseInt(unit_price)*qty;
  var percentage=(total_value*10)/100;
  var discount_type = parseInt(discount_type);
  var discount_percentage=ele.find('.preference_value').val();
  var max=0;
  switch(discount_type)
  {
    case 1:
      max = discount_percentage;
    break;
    case 2:
      max = percentage;
    break;
  }
  ele.find('.discount').attr('max',max);
}


$('#advance_collected').blur(function(){
    hideBalancePaymentDays();
});

$('#advance_type').change(function(){
    var total_value = $('#total_value').val();
    total_value = (total_value!='')?parseInt(total_value):0;
    var advance_type = $('#advance_type').val();
    var max = 0;
    switch(advance_type)
    {
      case '1':
        max = 100;
      break;
      case '2':
        max = total_value;
      break;
    }
    $('#advance_collected').attr('max',max);
    hideBalancePaymentDays();
});

function hideBalancePaymentDays()
{
    var advance_type = $('#advance_type').val();
    var advance = parseInt($('#advance_collected').val());
    var total_value = $('#total_value').val();
    total_value = (total_value!='')?parseInt(total_value):0;
    //alert(quote_value);
    if((advance_type=='1'&&advance=='100')||(advance_type=='2'&&advance==total_value))
    {
        $('.bal_payment_block').addClass('hidden');
        $('#balance_payment_days').val(0);
         $('#balance_payment_days').attr('min',0);
    }
    else
    {
        $('.bal_payment_block').removeClass('hidden');
         $('#balance_payment_days').attr('min',1);
        $('#balance_payment_days').val('');
    }
}
function calculate_extra_warranty()
{
    var dp = 0;
   $('.unit_price').each(function(){
      var unit_price = $(this).val();
      var qty=$(this).closest('.product_row').find('.product_qty').val();
     // alert(unit_price+'cc'+qty);
      unit_price = (!isNaN(unit_price))?parseInt(unit_price) : 0;
      up=unit_price*parseInt(qty);
      dp += up;
  });
    var warranty=parseInt($('.warranty').val());
    if(warranty > default_warranty)
    {
      var f=warranty/12; //= warranty_in_years
      var k=cost_of_warranty;
     var total_val=$('#total_value').val();
     if(total_val=='')
     {
      total_val=0;
     }
       var war_dis_value= dp*Math.pow((1+k/100),(f-1))-dp;
       var grand_total=parseInt(total_val)+parseInt(war_dis_value);
       $('.discount_div').removeClass('hidden');
       $('#warranty_cost').html(Math.round(war_dis_value,2));
       $('#grand_total').html(Math.round(grand_total,2));
       //alert('hi');
    }
    else
    {
       $('.discount_div').addClass('hidden');
       $('#warranty_cost').html('');
        $('#grand_total').html('');
    }
}
$(document).on('change','.warranty',function(){
  var total_val=$('#total_value').val();
  if(total_val >0)
  {
      calculate_extra_warranty();
  }
});
var warranty=parseInt($('.warranty').val());
//alert(default_warranty);
if(warranty > default_warranty)
{
  var total_val=$('#total_value').val();
  if(total_val >0)
  {
      calculate_extra_warranty();
  }
}
$(document).on('click','.submit_btn',function(){
  //alert('hello');
  var count1=0;
  var count2=0;
  var count3=0;
  $('.col1').each(function(){
    var col1=$(this).val();
    if(col1=='')
    {
      count1++;
    }
  });
  $('.col2').each(function(){
    var col2=$(this).val();
    if(col2=='')
    {
      count2++;
    }
  });
  $('.col3').each(function(){
    var col3=$(this).val();
    if(col3=='')
    {
      count3++;
    }
  });
  if(count1>0)
  {
    alert("Please fill Product Segment");
    return false;
  }
  if(count2>0)
  {
    alert("Please fill Product");
    return false;
  }
  if(count3>0)
  {
    alert("Please fill Quantity");
    return false;
  }
});
