<?php $this->load->view('commons/main_template',$nestedView); ?>
<div class="cl-mcont">
	<div class="row">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>updateUserLocations"  parsley-validate novalidate method="post">
					<input type="hidden" name="role_id" id="role_id" value="<?php echo $role_id;?>">
					<input type="hidden" name="role_level_id" id="role_level_id" value="<?php echo $role_level_id;?>">
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
					<div class="form-group no-padding">
							<div class="col-sm-7">
								<h3 class="hthin">Assigned locations</h3>
							</div>
						</div>
                        <?php
						switch($role_level_id) {
							case 2:
								include_once('location_view/edit/level2.php');
							break;
							case 3: case 4:
								include_once('location_view/edit/level3.php');
							break;
							case 5: case 6:
								include_once('location_view/edit/level4.php');
							break;
							case 7:
								include_once('location_view/edit/level5.php');
							break;
						}
						?>
					<br>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<a class="btn btn-default" href="<?php echo SITE_URL.'editUser/'.$encoded_id;?>">Cancel</a>
							<button type="submit" class="btn btn-primary submit">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('commons/main_footer.php',$nestedView); ?>
<script>
$(document).on('click','.submit',function(){
		var city_parent_str = $('.city_parent').map(function () {
	     return this.value;
	    }).get();
		var res= city_parent_str.join('p');
		$('.city_parent_hidd').val(res);
		var district_parent_str = $('.district_parent').map(function () {
	     return this.value;
	    }).get();
		var res1= district_parent_str.join('p');
		console.log(res1);
		$('.district_parent_hidd').val(res1);
	});
</script>