<?php $this->load->view('commons/main_template',$nestedView); ?>
<div class="cl-mcont">
	<div class="row fuelux">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>updateUserProducts"  parsley-validate novalidate method="post">
					<input type="hidden" name="role_id" id="role_id" value="<?php echo $role_id;?>">
					<input type="hidden" name="role_level_id" id="role_level_id" value="<?php echo $role_level_id;?>">
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
					<div class="form-group no-padding">
							<div class="col-sm-7">
								<h3 class="hthin">Assigned Products</h3>
							</div>
						</div>
                        <?php
						/*echo $sess_company;
						print_r($productCategories);*/
						switch($role_id) {
							case 4: // sales engineer
							case 5: // distributor
							case 6: // RSM
							case 8: // NSM
								include_once('products_view/edit/level2.php');
							break;
							default:
								include_once('products_view/edit/level1.php');
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
		var products = $('input:checkbox:checked.product').map(function () {
	     return this.value;
	    }).get();
		var res= products.join('p');
		$('.update_prod_hidd').val(res);
	});
</script>