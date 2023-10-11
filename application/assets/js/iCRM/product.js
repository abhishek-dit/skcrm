function checkCategoryAvailability()
{
  $("#name").on('keyup keypress blur change',function () {
      $("#error").remove();
      var name = $(this).val();
      var category_id = $("#category_id").val();
      var data = 'name='+name+'&category_id='+category_id;
      
      $.ajax({
        type:"POST",
        url:SITE_URL+'checkCategoryAvailability',
        data:data,
        cache:false,
        success:function(response){
          $("#error").remove();
          if(response>0)
          {
            //$("#name").val("");
            $("#name").after("<p class='color-danger' id='error'>This category already exists</p>");
          }
          else
          {
            $("#error").remove();
          }
        }
      });
    });
}

function checkGroupAvailability()
{
  $("#name").on('keyup keypress blur change',function () {
      $("#error").remove();
      var name = $(this).val();
      var group_id = $("#group_id").val();
      var data = 'name='+name+'&group_id='+group_id;
      
      $.ajax({
        type:"POST",
        url:SITE_URL+'checkGroupAvailability',
        data:data,
        cache:false,
        success:function(response){
          $("#error").remove();
          if(response>0)
          {
            //$("#name").val("");
            $("#name").after("<p class='color-danger' id='error'>This group already exists</p>");
          }
          else
          {
            $("#error").remove();
          }
        }
      });
    });
}


function checksubcategoryAvailability()
{
  $("#sub_system").on('keyup keypress blur change',function () {
      $("#error").remove();
      var name = $(this).val();
      var sub_category_id = $("#sub_category_id").val();
      var data = 'name='+name+'&sub_category_id='+sub_category_id;
      
      $.ajax({
        type:"POST",
        url:SITE_URL+'checksubcategoryAvailability',
        data:data,
        cache:false,
        success:function(response){
          $("#error").remove();
          if(response>0)
          {
            //$("#name").val("");
            $("#sub_system").after("<p class='color-danger' id='error'>This Sub System already exists</p>");
          }
          else
          {
            $("#error").remove();
          }
        }
      });
    });
}

function checkCompetitorAvailability()
{
  $("#name").on('keyup keypress blur change',function () {
      $("#error").remove();
      var name = $(this).val();
      var competitor_id = $("#competitor_id").val();
      var data = 'name='+name+'&competitor_id='+competitor_id;
      
      $.ajax({
        type:"POST",
        url:SITE_URL+'checkCompetitorAvailability',
        data:data,
        cache:false,
        success:function(response){
          $("#error").remove();
          if(response>0)
          {
            //$("#name").val("");
            $("#name").after("<p class='color-danger' id='error'>This competitor already exists</p>");
          }
          else
          {
            $("#error").remove();
          }
        }
      });
    });
}

function checkProductAvailability()
{
  $("#name").on('keyup keypress blur change',function () {
      $("#error").remove();
      var name = $(this).val();
      var product_id = $("#product_id").val();
      var data = 'name='+name+'&product_id='+product_id;
      
      $.ajax({
        type:"POST",
        url:SITE_URL+'checkProductAvailability',
        data:data,
        cache:false,
        success:function(response){
          $("#error").remove();
          if(response>0)
          {
            //$("#name").val("");
            $("#name").after("<p class='color-danger' id='error'>This product already exists</p>");
          }
          else
          {
            $("#error").remove();
          }
        }
      });
    });
}

function checkDemoProductSerialNumberAvailability()
{
  $("#serialNumber").on('keyup keypress blur change',function () {
      $("#error").remove();
      var serial_number = $(this).val();
      var demo_product_id = $("#demo_product_id").val();
      var flg= $("#flg").val();
      var sno= $('#sno').val();
      
      var data = 'serial_number='+serial_number+'&demo_product_id='+demo_product_id;
      if(sno!=serial_number)
      {
          $.ajax({
            type:"POST",
            url:SITE_URL+'checkDemoProductSerialNumberAvailability',
            data:data,
            cache:false,
            success:function(response)
            {
                $("#error").remove();
                if(response>0)
                {
                  //$("#serialNumber").val("");
                  $("#serialNumber").after("<p class='color-danger' id='error'>This serial number already exists</p>");
                }
                else
                {
                  $("#error").remove();
                }
            }
          });
      }
    });
}

$("form").submit(function( e ) {
  if ($('#error').length) {
    e.preventDefault();
    return false;
  }
});