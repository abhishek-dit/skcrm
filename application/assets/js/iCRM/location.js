// Custom JS for Location controller
$(document).ready(function(){
    $( document ).on( 'focus', ':input', function(){
        $( this ).attr( 'autocomplete', 'off' );
    });
});

function getLocationChild(parent, child, child1, child2)
{
  if(child1  === undefined) child1=0;
  if(child2  === undefined) child2=0;
  $(document).on('change',"#"+parent,function () { 
  var location_id=$("#"+parent).val();
  //alert(customer_id);
         if(location_id != "")
         {
     var data = 'location_id='+location_id+'&territory='+child;
      //alert(data);
      $.ajax({
                      type:"POST",
                      url:SITE_URL+'getChilds',
                      data:data,
                      cache:false,
                      success:function(html){
                                      $("#"+child).html(html);
                      }
      });
         }
          else
         {
          $('#'+child).html('<option value="">Select '+child+'</option>');
         }
      $('#'+child1).html('<option value="">Select '+child1+'</option>');
      $('#'+child2).html('<option value="">Select '+child2+'</option>');
     });
}

getLocationChild('Country', 'Region', 'State', 'District');
getLocationChild('Region','State', 'District');
getLocationChild('State', 'District');

function kritika(parent_location, location)
{
  var loc = $("#loc").val();
  var par = $("#par").val();
  //alert(loc);
  if(loc == 0 && par==undefined)
    $("#"+location).attr('readonly',true);

  $("."+parent_location).change(function () {
    $("#"+location).val("");
    $("#location_error").remove();

    var parent_location_id = $(this).val();
    
    if(parent_location_id != "")
    {
      $("#"+location).attr('readonly',false);
      $("#location_error").remove();
    }
  });

}

function checkLocationAvailability(parent_location, location)
{
  $("#"+location).on('keyup keypress blur change',function () {
      $("#location_error").remove();
      var location_name = $(this).val();
      var location_id = $("#loc").val();
      var parent_location_id = $("."+parent_location).val();
      var data = 'parent_location='+parent_location_id+'&location='+location_name+'&location_id='+location_id;
      
      $.ajax({
        type:"POST",
        url:SITE_URL+'checkLocationAvailability',
        data:data,
        cache:false,
        success:function(response){
          $("#location_error").remove();
          if(response>0)
          {
            //$("#"+location).val("");
            $("#"+location).after("<p class='color-danger' id='location_error'>This location already exists</p>");
          }
          else
          {
            $("#location_error").remove();
          }
        }
      });
    });
}

$("form").submit(function( e ) {
  if ($('#location_error').length) {
    e.preventDefault();
    return false;
  }
});

kritika('territoryEntry','name');
checkLocationAvailability('territoryEntry','name');
