// validate sso id exist or not on submit		
$("#deleteSupplierPDF").click(function(){
	var txt_quote_pdf = $('#txt_quote_pdf').val(); //alert(txt_quote_pdf);
	var supplier_id = $('#supplier_id').val();
	if(txt_quote_pdf!='' && supplier_id!='')
	{	
		$("#supContainer").css("opacity",0.5);
		$("#loaderID").css("opacity",1);
		var data = 'txt_quote_pdf='+txt_quote_pdf+'&supplier_id='+supplier_id;
		$.ajax({
		type:"POST",
		url:'ajax/deleteSupplierPDF.php',
		data:data,
		cache:false,
		success:function(html){
		//alert(html);
		$("#supContainer").css("opacity",1);
		$("#loaderID").css("opacity",0);
		if(html==1){
			$("#divPDFFile").remove(); 
			$("#txt_quote_pdf").val(''); 
			$("#lblPDFFile").html('Quote Removed Successfully');
		} else {
			$("#lblPDFFile").html('Invalid Request');
		}
		}
		});
	}

});

// validate quote upload
		/*$('#quote_pdf').change(function(){
				
				var ext = $('#quote_pdf').val().split('.').pop().toLowerCase();
				if($.inArray(ext, ['pdf']) == -1) {
					alert('invalid extension! allowed pdf only');
					$('#quote_pdf').val('');
					return false;
				}
				var size = this.files[0].size; var max_kb = 300;
				var size_kb = Math.ceil(size/1024);
				if(size_kb>max_kb)
				{
					alert('File size limit exceeds! allowed less than 300 KB');
					$('#resume').val('');
					return false;
				}

		});*/
