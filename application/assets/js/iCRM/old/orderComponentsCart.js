//Order cart  page JS functions
// on check/uncheck of select all checkbox
$('#checkAllComponents').change(function()
{
	if(this.checked)
	{
		$('.component_chk').prop('checked',true);
		$(".componentQuantity").prop("disabled",false);
		$("button[name='add']").prop("disabled",false);
	}
	else
	{
		$('.component_chk').prop('checked',false);
		$(".componentQuantity").prop("disabled",true);
		$(".componentQuantity").val("");
		$("button[name='add']").prop("disabled",true);
	}
});

// Remove Component from order cart	
$(".removecomponentRow").click(function(e){
	e.preventDefault();
	if(confirm('Are you sure you want to remove'))
	{
	var component_id = $(this).attr('data-cid'); 
	var componentRow = $(this).closest(".componentRow");
	$("#orderCartContainer").css("opacity",0.5);
	$("#loaderID").css("opacity",1);
	var data = 'component_id='+component_id;
		$.ajax({
		type:"POST",
		url:'removeComponentFromOrderCart',
		data:data,
		cache:false,
		success:function(html){
			//alert(html);
			componentRow.remove();
			$("#orderCartContainer").css("opacity",1);
			$("#loaderID").css("opacity",0);
			var comp_count = $('.componentIds').length;
			//alert(comp_count);
			if(comp_count==0)
			{
				$('#submitOrder').prop('disabled',true);
			}
			var grandTotal = getGrandTotal();
			$('#GrandTotal').html(grandTotal);
		}
		});
		
	}

});

$('.addSuppliers').click(function(e) {
    e.preventDefault();
	var component_id = $(this).attr('data-cid');
	var data1 = $("#orderComponentsCartFrm").serialize();
	var data = 'cid='+component_id+'&'+data1;
	$("#loading"+component_id).css("opacity",1);
	//alert(data);
		$.ajax({
		type:"POST",
		url:'addSuppliersToComponentOrder',
		data:data,
		cache:false,
		success:function(html){
			//alert(html);
			$("#loading"+component_id).css("opacity",0);
			$("#subBox"+component_id).modal("hide");
			$("#supList"+component_id).html(html);
		}
		});
	/*alert(component_id);
	alert(fields);*/
});

//on click upload Qutoe
$('.uploadQuote').click(function(e) {
    e.preventDefault();
	$(this).closest('.uploadQuoteBlock').find('.uploadBtn').removeClass('hidden');
	$(this).addClass('hidden');
});

var files;
//on change browse button
//$('.supQuote').change(function() {
	$('.supQuote').on('change', function(event){
		
    /*var ext = $(this).val().split('.').pop().toLowerCase();
	if($.inArray(ext, ['pdf']) == -1) {
		alert('invalid extension! allowed pdf only');
		$(this).val('');
		return false;
	}*/
	$(this).closest('.uploadQuoteBlock').find('.uploadBtn').addClass('hidden');
	$(this).closest('.uploadQuoteBlock').find('.loadingProc').removeClass('hidden');
	
	var sid = $(this).closest('.uploadQuoteBlock').find('.suppId').val();
	var compId = $(this).closest('.componentRow').find('.componentIds').val();
	//alert(sid);
	// Variable to store your files
	var data = 'sid='+sid+'&compId='+compId;
	var loadingProc = $(this).closest('.uploadQuoteBlock').find('.loadingProc');	
	var uploadSuccMsg = $(this).closest('.uploadQuoteBlock').find('.uploadSuccMsg');	
	files = event.target.files;
	uploadFiles(event,sid,compId,loadingProc,uploadSuccMsg);
	$(this).val('');
	
	
	
});

// Catch the form submit and upload the files
	function uploadFiles(event,sid,compId,loadingProc,uploadSuccMsg)
	{
		event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        // START A LOADING SPINNER HERE

        // Create a formdata object and add the files
		var data = new FormData();
		$.each(files, function(key, value)
		{
			data.append(key, value);
			//alert(key);
			//alert(JSON.stringify(value));
		});
        
        $.ajax({
            url: 'uploadSupplierQuote?sid='+sid+'&compId='+compId,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR)
            {
            	if(typeof data.error === 'undefined')
            	{
            		// Success so call function to process the form
					//alert('success'+data.url);
					//viewQuote.html('<a href="'+data.url+'" target="_blank">View Quote</a>');
            		//submitForm(event, data);
					
					loadingProc.addClass('hidden');
					uploadSuccMsg.removeClass('hidden');
            	}
            	else
            	{
            		// Handle errors here
            		console.log('ERRORS1: ' + data.error);
            	}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	// Handle errors here
            	console.log('ERRORS2: ' + textStatus);
            	// STOP LOADING SPINNER
            }
        });
    }
	
$('.componentQuantity').blur(function(){

	var qty = parseInt($(this).val());
	var unitCost = parseInt($(this).closest('.componentRow').find('.comp_unit_cost').val());
	var tot = qty*unitCost;
	//alert(qty+'-->'+unitCost+tot);
	$(this).closest('.componentRow').find('.compTotQty').html(tot);	
	var grandTotal = getGrandTotal();
	$('#GrandTotal').html(grandTotal);
});

function getGrandTotal()
{
	var grandTotal = 0;
	$('.componentQuantity').each(function() {
        var qty = parseInt($(this).val());
		var unitCost = parseInt($(this).closest('.componentRow').find('.comp_unit_cost').val());
		grandTotal+=(qty*unitCost);
    });
	return grandTotal;
}