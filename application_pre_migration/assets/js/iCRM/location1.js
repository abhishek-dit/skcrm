// Custom JS for Location controller

function getLocationChild(parent, child, child1 = 0, child2 = 0)
{
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

