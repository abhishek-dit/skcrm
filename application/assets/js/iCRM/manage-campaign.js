// Custom JS for manage campaign



$(document).on('change','#geo5',function(){
	var locationParentId = $(this).val(); 
	//alert(locationParentId);
	
	 if(locationParentId!=""&&locationParentId!=null)
	 {
		$('.country-loading').removeClass('hidden');
		var data = 'locationParentId='+locationParentId;
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'getCountriesByGeoMulti',
			data:data,
			cache:false,
			success:function(html){ 
				//alert(html);
				$('#country5').html(html);
				$('.country-group').removeClass('hidden');
				$('.country-loading').addClass('hidden');
			}
		});
		
	 }
	 else {
		 $('.country-group').addClass('hidden');
		 $('#country5').html('<option value="">select</option>');
		 
	 }

	 /*hiding, emptying lower level territories start */
	 //region
	 $('.region-group').addClass('hidden');
	 $('#region5').html('<option value="">select</option>');
	 // state
	 $('.state-group').addClass('hidden');
	 $('#state5').html('');
	 //district
	 $('.district-group').addClass('hidden');
	 $('#district5').html('');
	 //city
	 $('.city-group').addClass('hidden');
	 $('#city5').html('');
	 /*hiding, emptying lower level territories end */
});

$(document).on('change','#country5',function(){
	var locationParentId = $(this).val(); 
	//alert(locationParentId);
	
	 if(locationParentId!=""&&locationParentId!=null)
	 {
		$('.region-loading').removeClass('hidden');
		var data = 'locationParentId='+locationParentId;
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'getRegionsByCountryMulti',
			data:data,
			cache:false,
			success:function(html){ 
				
				$('#region5').html(html);
				$('.region-group').removeClass('hidden');
				$('.region-loading').addClass('hidden');
			}
		});
		
	 }
	 else {
		 $('.region-group').addClass('hidden');
		 $('#region5').html('<option value="">select</option>');
		 
	 }

	 /*hiding, emptying lower level territories start */
	 // state
	 $('.state-group').addClass('hidden');
	 $('#state5').html('');
	 //district
	 $('.district-group').addClass('hidden');
	 $('#district5').html('');
	 //city
	 $('.city-group').addClass('hidden');
	 $('#city5').html('');
	 /*hiding, emptying lower level territories end */
	 
});

$(document).on('change','#region5',function(){
	var locationParentId = $(this).val(); 
	//alert(locationParentId);
	
	 if(locationParentId!=""&&locationParentId!=null)
	 {
		$('.state-loading').removeClass('hidden');
		var data = 'locationParentIds='+locationParentId;
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'getStatesByRegionMulti',
			data:data,
			cache:false,
			success:function(html){ 
				
				$('#state5').html(html);
				$('.state-group').removeClass('hidden');
				$('.state-loading').addClass('hidden');
			}
		});
		
	 }
	 else {
		 $('.state-group').addClass('hidden');
		 $('#state5').html('');
		 
	 }

	 /*hiding, emptying lower level territories start */
	 //district
	 $('.district-group').addClass('hidden');
	 $('#district5').html('');
	 //city
	 $('.city-group').addClass('hidden');
	 $('#city5').html('');
	 /*hiding, emptying lower level territories end */
	 
});

$(document).on('change','.state_cb',function(){
	var states = $('input:checkbox:checked.state_cb').map(function () {
	  return this.value;
	}).get();
	
	var current_districts = $('input:checkbox:checked.district_cb').map(function () {
	  return this.value;
	}).get();
	
	//alert(states);
	if(states.length>0) {
	$('.district-loading').removeClass('hidden');
	$.ajax({
	   type: "POST",
	   data: {states:states,current_districts:current_districts},
	   url: AJAX_CONTROLLER_URL+'getDistrictsByState',
	   success: function(html){
		 //alert(html);
		 $('#district5').html(html);
		 $('.district-group').removeClass('hidden');
		 $('.district-loading').addClass('hidden');
	   }
	});
	}
	else {
		$('.district-group').addClass('hidden');
		 $('#district5').html('');
	}

	/*hiding, emptying lower level territories start */
	 //city
	 var current_cities = $('input:checkbox:checked.city_cb').map(function () {
		  return this.value;
		}).get();
	 if(current_cities.length>0){

	 	var current_districts = $('input:checkbox:checked.district_cb').map(function () {
			  return this.value;
			}).get();

	 	if(current_districts.length>0){
	 		//looping current cities
	 		var isCityDistrictExist = false;
	 		$.each(current_cities,function(index,city_id){
	 			//getting city parent id
	 			var city_parent_id = $('input[name="city_parent['+city_id+']"]').val();
	 			if($.inArray(city_parent_id,current_districts)>=0){
	 				isCityDistrictExist = true;
	 				return false;
	 			}
	 		});
	 		if(!isCityDistrictExist){
	 			//hiding city
			 	$('.city-group').addClass('hidden');
			 	$('#city5').html('');
	 		}
	 		else{
	 			// run ajax to get updated cities
	 			var districts = $('input:checkbox:checked.district_cb').map(function () {
				  return this.value;
				}).get();
				
				var current_cities = $('input:checkbox:checked.city_cb').map(function () {
				  return this.value;
				}).get();
				
				var states = $('input:checkbox:checked.state_cb').map(function () {
				  return this.value;
				}).get();
				
				var role_level_id = parseInt($('#role_level_id').val());
				//alert(states);
				if(districts.length>0) {
				
					$.ajax({
					   type: "POST",
					   data: {role_level_id:role_level_id,districts:districts,current_cities:current_cities,states:states},
					   url: AJAX_CONTROLLER_URL+'getCitiesByDistrict',
					   success: function(html){
						 //alert(html);
						 $('#city5').html(html);
						 $('.city-group').removeClass('hidden');
					   }
					});
				}
				else {
					$('.city-group').addClass('hidden');
					$('#city5').html('');
				}
	 		}
	 	}
	 	else{
	 		//hiding city
		 	$('.city-group').addClass('hidden');
		 	$('#city5').html('');
	 	}
	 }
	 else{
	 	 //hiding city
		 $('.city-group').addClass('hidden');
		 $('#city5').html('');
	 }
	 
	 /*hiding, emptying lower level territories end */

});

$(document).on('change','.district_cb',function(){
		
	var districts = $('input:checkbox:checked.district_cb').map(function () {
	  return this.value;
	}).get();
	
	var current_cities = $('input:checkbox:checked.city_cb').map(function () {
	  return this.value;
	}).get();
	
	var states = $('input:checkbox:checked.state_cb').map(function () {
	  return this.value;
	}).get();
	
	
	//alert(states);
	if(districts.length>0) {
	$('.city-loading').removeClass('hidden');
	$.ajax({
	   type: "POST",
	   data: {districts:districts,current_cities:current_cities,states:states},
	   url: AJAX_CONTROLLER_URL+'getCitiesByDistrict',
	   success: function(html){
		 //alert(html);
		 $('#city5').html(html);
		 $('.city-group').removeClass('hidden');
		 $('.city-loading').addClass('hidden');
	   }
	});
	}
	else {
		$('.city-group').addClass('hidden');
		 $('#city5').html('');
	}
});

$(document).on('click','#getContacts',function(){
	var data = $('#add_campaign_form').serialize();
	$('.contacts-loading').removeClass('hidden');
	$.ajax({
	   type: "POST",
	   data: data,
	   url: AJAX_CONTROLLER_URL+'getContactsByLocationSpeciality',
	   success: function(html){
		 var ce_arr = jQuery.parseJSON(html);
		 $('#mail_to').val(ce_arr['contact_emails']);
		 $('#contactsCountDisp').html(ce_arr['count']+' Contacts has been fetched');
		 $('.contacts-loading').addClass('hidden');
	   }
	});
});

//on change of campaign type 
$(document).on('change','.campaign_type',function(){

    var val = $(this).val();
    //alert(val);
    if(val==1){
        $('.mail_fields').removeClass('hidden');
    }
    else{
        $('.mail_fields').addClass('hidden');
        $('.mail_fields textarea, .mail_fields input[type="file"]').val('');
        $('#contactsCountDisp').html('');
       
        CKEDITOR.instances['mail_content'].updateElement();
        CKEDITOR.instances['mail_content'].setData('');

    }
});

// on change of attachments
$('#attachments').change(function(){
		
	for (var i = 0; i < $(this).get(0).files.length; ++i) {
        var fname = $(this).get(0).files[i].name;
        var ext = fname.split('.').pop().toLowerCase();
		//alert(ext+$(this).val());
		if($.inArray(ext, ['jpg','png','gif','pdf','doc','docx','xls','xlsx']) == -1) {
			alert(fname+' has invalid extension! allowed jpg, png, gif, pdf, doc, docx, xls, xlsx only');
			$(this).val('');
			return false;
		}
		var fsize = $(this).get(0).files[i].size;
		//alert(fsize);
		if(fsize>(2*1024*1024)){
			alert(fname+' has exceeded max upload size of 2MB');
			$(this).val('');
			return false;
		}
    }		
	
});