// Custom JS for User controller
$(document).on('change','#geo',function(){
	var locationParentId = $(this).val(); 
	//alert(client_id);
	var role_level_id = parseInt($('#role_level_id').val());
	 if(locationParentId!="")
	 {
		 
		var data = 'locationParentId='+locationParentId+'&role_level_id='+role_level_id;
		
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'getCountriesByGeo',
			data:data,
			cache:false,
			success:function(html){ 
				//alert(html);
				$('#country,#countryblock').html(html);
				$('.country-group').removeClass('hidden');
			}
		});
		
	 }
	 else {
		 $('.country-group, .region-group').addClass('hidden');
		 if(role_level_id==3||role_level_id==4)
		 $('#countryblock').html('');
		 else
		 $('#country,#region').html('<option value="">select</option>');
		 
	 }
	 
});

$(document).on('change','#country',function(){
	var locationParentId = $(this).val(); 
	//alert(client_id);
	var role_level_id = parseInt($('#role_level_id').val());
	 if(locationParentId!="")
	 {
		 
		var data = 'locationParentId='+locationParentId+'&role_level_id='+role_level_id;
		
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'getRegionsByCountry',
			data:data,
			cache:false,
			success:function(html){ 
				
				$('#region').html(html);
				$('.region-group').removeClass('hidden');
			}
		});
		
	 }
	 else {
		 $('.region-group').addClass('hidden');
		 $('#region').html('<option value="">select</option>');
		 
	 }
	 
});

// level5 locations functions
$(document).on('change','#geo5',function(){
	var locationParentId = $(this).val(); 
	//alert(client_id);
	var role_level_id = parseInt($('#role_level_id').val());
	 if(locationParentId!="")
	 {
		 
		var data = 'locationParentId='+locationParentId+'&role_level_id='+role_level_id;
		
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'getCountriesByGeo',
			data:data,
			cache:false,
			success:function(html){ 
				
				$('#country5,#countryblock').html(html);
				$('.country-group').removeClass('hidden');
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
	//alert(client_id);
	var role_level_id = parseInt($('#role_level_id').val());
	 if(locationParentId!="")
	 {
		 
		var data = 'locationParentId='+locationParentId+'&role_level_id='+role_level_id;
		
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'getRegionsByCountry',
			data:data,
			cache:false,
			success:function(html){ 
				
				$('#region5').html(html);
				$('.region-group').removeClass('hidden');
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
	//alert(client_id);
	var role_level_id = parseInt($('#role_level_id').val());
	 if(locationParentId!="")
	 {
		 
		var data = 'locationParentId='+locationParentId+'&role_level_id='+role_level_id;
		
		$.ajax({
			type:"POST",
			url:AJAX_CONTROLLER_URL+'getStatesByRegion',
			data:data,
			cache:false,
			success:function(html){ 
				
				$('#state5').html(html);
				$('.state-group').removeClass('hidden');
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
	var role_level_id = parseInt($('#role_level_id').val());
	//alert(states);
	if(states.length>0) {
	
	$.ajax({
	   type: "POST",
	   data: {states:states,role_level_id:role_level_id,current_districts:current_districts},
	   url: AJAX_CONTROLLER_URL+'getDistrictsByState',
	   success: function(html){
		 //alert(html);
		 $('#district5').html(html);
		 $('.district-group').removeClass('hidden');
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
});

/*Assign products js functions ------------strat--*/
$(document).on('change','#chkAllProdCat',function(){
	if(this.checked) {
		$('.productCategory').prop('checked',true);
	}
	else $('.productCategory').prop('checked',false);
	load_product_groups();
});

$(document).on('change','.productCategory',function(){
	load_product_groups();
});

function load_product_groups() {
	
	var productCategories = $('input:checkbox:checked.productCategory').map(function () {
	  return this.value;
	}).get();
	//alert(productCategories);
	if(productCategories.length>0) {
	var role_level_id = parseInt($('#role_level_id').val());
	var role_id = parseInt($('#role_id').val());
	$('.product-group-loading').removeClass('hidden');
	$('.product-group-box,.product-box').addClass('hidden');
	$.ajax({
	   type: "POST",
	   data: {role_level_id:role_level_id,productCategories:productCategories,role_id:role_id},
	   url: AJAX_CONTROLLER_URL+'getPorductGroupsByCategories',
	   success: function(html){
		 //alert(html);
		 $('#product_group').html(html);
		 $('.product-group-loading').addClass('hidden');
		 $('.product-group-box,.product-box').removeClass('hidden');
		 load_products();
	   }
	});
	}
	else {
		$('.product-group-box').addClass('hidden');
		$('#product_group').html('');
		load_products();
	}
	
	
}

$(document).on('change','.chkAllPG',function(){
	var chk_id = $(this).attr('id');
	if(this.checked) {
		$('.'+chk_id).prop('checked',true);
	}
	else $('.'+chk_id).prop('checked',false);
	load_products();
});

$(document).on('change','.productGroup',function(){
	load_products();
});

function load_products() {
	
	var productCategories = $('input:checkbox:checked.productCategory').map(function () {
	  return this.value;
	}).get();

	var productGroups = $('input:checkbox:checked.productGroup').map(function () {
	  return this.value;
	}).get();
	
	if(productGroups.length>0) {
	var role_level_id = parseInt($('#role_level_id').val());
	var role_id = parseInt($('#role_id').val());
	$('.product-loading').removeClass('hidden');
	$('.product-box').addClass('hidden');
	$.ajax({
	   type: "POST",
	   data: {role_level_id:role_level_id,productCategories:productCategories,role_id:role_id,productGroups:productGroups},
	   url: AJAX_CONTROLLER_URL+'getPorductsByGroup',
	   success: function(html){
		 //alert(html);
		 $('#product').html(html);
		 $('.product-loading').addClass('hidden');
		 $('.product-box').removeClass('hidden');
	   }
	});
	}
	else {
		$('.product-box').addClass('hidden');
		$('#product').html('');
	}
}

$(document).on('change','.chkAllProd',function(){
	var chk_id = $(this).attr('id');
	if(this.checked) {
		$('.'+chk_id).prop('checked',true);
	}
	else $('.'+chk_id).prop('checked',false);
});

/*Assign products js functions ------------end--*/

$('#userAddForm').submit(function(){
	if ($('.parsley-error').length>0) { alert('Please fill all required information in User Details');};
});

//validate employee id unique
$('#employee_id').blur(function(){
	var employee_id = $(this).val();
	var user_id = $('#user_id').val();
	if(employee_id!=''){
		$("#empIdValidating").removeClass("hidden");
		$("#empIdError").addClass("hidden");
		var data = 'employee_id='+employee_id+'&user_id='+user_id;
		
		$.ajax({
		type:"POST",
		url:AJAX_CONTROLLER_URL+'is_employeeIdExist',
		data:data,
		cache:false,
		success:function(html){ 
	//	alert(html);
		$("#empIdValidating").addClass("hidden");
			if(html==1)
			{
				$('#employee_id').val('');
				$('#empIdError').html('Employee ID <b>'+employee_id+'</b> already existed');
				$("#empIdError").removeClass("hidden");
				return false;
			}
			else
			{	
				$('#empIdError').html('');
				$("#empIdError").addClass("hidden");
				return false;
			}
		}
		});
	}
});

//validate employee email unique
$('#email').blur(function(){
	var email = $(this).val();
	var user_id = $('#user_id').val();
	if(email!=''){
		$("#emailValidating").removeClass("hidden");
		$("#emailError").addClass("hidden");
		var data = 'email='+email+'&user_id='+user_id;
		
		$.ajax({
		type:"POST",
		url:AJAX_CONTROLLER_URL+'is_employeeEmailExist',
		data:data,
		cache:false,
		success:function(html){ 
	//	alert(html);
		$("#emailValidating").addClass("hidden");
			if(html==1)
			{
				$('#email').val('');
				$('#emailError').html('Email <b>'+email+'</b> already existed');
				$("#emailError").removeClass("hidden");
				return false;
			}
			else
			{	
				$('#emailError').html('');
				$("#emailError").addClass("hidden");
				return false;
			}
		}
		});
	}

	
});

$(document).on('change','#new_role_id',function(){
		//alert('hello');
		var new_role = $(this).val();
		if(new_role==3||new_role==11||new_role==13){
			$('#chr_next_btn').addClass('hidden');
			$('#chr_submit_btn').removeClass('hidden');
		}
		else{
			$('#chr_next_btn').removeClass('hidden');
			$('#chr_submit_btn').addClass('hidden');
		}
	});

$(document).on('change','.month_apply_all_cb',function(){
	//alert('hello');
	var month_id=$(this).val();
	if(this.checked){
		var first_val = $('.month'+month_id+':first').val();
		if(first_val!='')
		$('.month'+month_id).val(first_val);
	}
	else{
		$('.month'+month_id).val('');
	}
	
});

$(document).on('keypress','only-numbers',function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
});

$(document).on('change','.product_apply_all_cb',function(){
	//alert('hello');
	var product_id=$(this).val();
	if(this.checked){
		var first_val = $('.product'+product_id+':first').val();
		if(first_val!='')
		$('.product'+product_id).val(first_val);
	}
	else{
		$('.product'+product_id).val('');
	}
	
});

// validate bulk upload
$('#uploadCsv').change(function(){
				
	var ext = $(this).val().split('.').pop().toLowerCase();
	if($.inArray(ext, ['csv']) == -1) {
		alert('invalid extension! allowed .csv only');
		$(this).val('');
		return false;
	}
});
		
$('#bulkUploadFrm').submit(function(){
	var csvFile = $('#uploadCsv').val();
	if(csvFile=='')
	{
		alert('Please upload CSV file');
		return false;
	}
});
$('#uploadXlsx').change(function(){
				
	var ext = $(this).val().split('.').pop().toLowerCase();
	if($.inArray(ext, ['xlsx']) == -1 && $.inArray(ext, ['xls'])== -1 ) {
		alert('invalid extension! allowed .xlsx ,.xls only');
		$(this).val('');
		return false;
	}
});
$('#bulkUploadFrm1').submit(function(){
	var xlxsFile = $('#uploadXlsx').val();
	if(xlxsFile=='')
	{
		alert('Please upload XLSX or XLS file');
		return false;
	}
});
