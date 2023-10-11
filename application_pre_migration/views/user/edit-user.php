<?php $this->load->view('commons/main_template',$nestedView); ?>
<div class="row">
	<div class="block-flat">
		<div class="header">
			<h4><i class="fa fa-pencil"></i><b> Edit User</b></h4>
		</div>
		<div class="content">
			<form name="editUser" method="post" id="editUser">
				<div class="row">
					<div class="col-md-3">
						<a class="quick-button-small metro orange span2" href="<?php echo SITE_URL.'editUserDetails/'.$en_user_id; ?>">
							<i class="fa fa-user"></i>
							<p>User Details</p>
						</a>
					</div>
					<?php
					$not_allowed_roles = array(5,12);// distributor, stokist
					if(!in_array($role_id, $not_allowed_roles)){
					?>
					<div class="col-md-3">
						<a class="quick-button-small metro blueDark span2" href="<?php echo SITE_URL.'changeUserRole/'.$en_user_id; ?>">
							<i class="fa fa-gears"></i>
							<p>Change Role</p>
						</a>
					</div>
					<?php
					}
					$location_change_role_levels = array(2,3,4,5,6,7); // from role level2 to level6 
					if(in_array($role_level_id,$location_change_role_levels)){
					?>
					<div class="col-md-3">
						<a class="quick-button-small metro red span2" href="<?php echo SITE_URL.'editUserLocations/'.$en_user_id; ?>">
							<i class="fa fa-map-marker"></i>
							<p>Locations</p>
						</a>
					</div>
					<?php
					}
					$product_change_roles = array(4,5,6,8); // Sales Engineer, Distributor, RSM, NSM
					if(in_array($role_id,$product_change_roles)){
					?>
					<div class="col-md-3">
						<a class="quick-button-small metro greenDark span2" href="<?php echo SITE_URL.'editUserProducts/'.$en_user_id; ?>">
							<i class="fa fa-gavel"></i>
							<p>Products</p>
						</a>
					</div>
					</div>
					<?php
					}
					?>
				</div>
				<br>
			</form>
		</div>
	</div>
</div>
<?php $this->load->view('commons/main_footer.php',$nestedView); ?>