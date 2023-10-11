<?php $this->load->view('commons/main_template',$nestedView); ?>
<div class="cl-mcont">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="block-flat" style="margin-bottom:40px;">
				<div class="content">
					<div class="row">
						<div class="col-md-6">
							<div class="header">
								<h3>User Details</h3>
							</div>
							<table class="no-border">
								<tbody class="no-border-x no-border-y">
									<tr>
										<td class="data-lable">First Name</td>
										<td class="data-item"><?php echo @$user['first_name'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Last Name</td>
										<td class="data-item"><?php echo @$user['last_name'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Employee ID</td>
										<td class="data-item"><?php echo @$user['employee_id'];?></td>
									</tr>
									<tr>
										<td class="data-lable">E-Mail</td>
										<td class="data-item"><?php echo @$user['email_id'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Mobile</td>
										<td class="data-item"><?php echo getPhoneNumber(@$user['mobile_no']);?></td>
									</tr>
									<tr>
										<td class="data-lable">Alternate Number</td>
										<td class="data-item"><?php echo getPhoneNumber(@$user['alternate_number']);?></td>
									</tr>
									<tr>
										<td class="data-lable">Address1</td>
										<td class="data-item"><?php echo @$user['address1'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Address2</td>
										<td class="data-item"><?php echo @$user['address2'];?></td>
									</tr>
									<tr>
										<td class="data-lable">City</td>
										<td class="data-item"><?php echo @$user['city'];?></td>
									</tr>
									<tr>
										<td class="data-lable">User Role</td>
										<td class="data-item"><?php echo @$userRole;?></td>
									</tr>
									<tr>
										<td class="data-lable">Branch</td>
										<td class="data-item"><?php echo @$userBranch;?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php
						if($role_id==5||$role_id==12) { // if distributor, stockist
							if($role_id==5) {$heading = 'Distributor details'; $name='Distributor Name';}
							if($role_id==12) {$heading = 'Stockist details';  $name='Stockist Name';}
						?>
						<div class="col-md-6">
							<div class="header">
								<h3><?php echo @$heading;?></h3>
							</div>
							<table class="no-border">
								<tbody class="no-border-x no-border-y">
									<tr>
										<td class="data-lable"><?php echo @$name;?></td>
										<td class="data-item"><?php echo @$distributor['distributor_name'];?></td>
									</tr>
									<tr>
										<td class="data-lable">PAN Number</td>
										<td class="data-item"><?php echo @$distributor['PAN_number'];?></td>
									</tr>
									<tr>
										<td class="data-lable">TIN Number</td>
										<td class="data-item"><?php echo @$distributor['TIN_number'];?></td>
									</tr>
									<tr>
										<td class="data-lable">TAN Number</td>
										<td class="data-item"><?php echo @$distributor['TAN_number'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Sales Tax Number</td>
										<td class="data-item"><?php echo @$distributor['service_tax_number'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Service Tax Number</td>
										<td class="data-item"><?php echo @$distributor['sales_tax_number'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Excise Number</td>
										<td class="data-item"><?php echo @$distributor['excise_number'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Bank</td>
										<td class="data-item"><?php echo @$distributor['bank_name'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Bank Branch</td>
										<td class="data-item"><?php echo @$distributor['branch'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Account Name</td>
										<td class="data-item"><?php echo @$distributor['ac_name'];?></td>
									</tr>
									<tr>
										<td class="data-lable">Account Number</td>
										<td class="data-item"><?php echo @$distributor['ac_no'];?></td>
									</tr>
									<tr>
										<td class="data-lable">IFSC</td>
										<td class="data-item"><?php echo @$distributor['ifsc'];?></td>
									</tr>
									<tr>
										<td class="data-lable">&nbsp;</td>
										<td class="data-item">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php
						}
						?>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="header">
								<h3>Location Details</h3>
							</div>
							<div>
							<ul class="nav nav-list treeview collapse">
							<?php
							if($parent_hirarchy_str!='')
								echo '<li><label>'.$parent_hirarchy_str.'</label></li>';
							switch($role_level_id) {
								case 1: case 8:
									?>
									<?php
									if($role_level_id==1||$role_id==12)
									{
										?>
									     <li><label class="nav-header"><i class="fa fa-check-square-o"></i> All GEOS</label></li>
									    <?php
									}
									else {
										?>
									     <li><label class="nav-header"><i class="fa fa-square-o"></i> No Locations</label></li>
									    <?php
									}
									?>
									<?php
								break;
								default:
									echo map_locations($user_locations);
								break;

							}
							?>
							</ul>
								<!--<ul class="nav nav-list treeview collapse">
									<li><label><i class="fa fa-angle-right"></i> Asia Pacific <i class="fa fa-angle-right"></i> India <i class="fa fa-angle-right"></i> South</label></li>
						            <li><label class="tree-toggler nav-header"><i class="fa fa-plus-square-o"></i> Andhra Pradesh</label>
						                <ul class="nav nav-list tree">
						                    <li><a href="#">Vishakapatnam</a></li>
						                    <li><a href="#">Guntur</a></li>
						                    <li><label class="tree-toggler nav-header"><i class="fa fa-plus-square-o"></i> Krishna</label>
						                        <ul class="nav nav-list tree">
						                            <li><a href="#">Vijayawada</a></li>
						                            <li><a href="#">Machilipatnam</a></li>
						                            <li><a href="#">Gudivada</a></li>
						                            <li><a href="#">Nuziveedu</a></li>
						                        </ul>
						                    </li>
						                </ul>
						            </li>
						            <li><label class="tree-toggler nav-header"><i class="fa fa-plus-square-o"></i> Telangana</label>
						                <ul class="nav nav-list tree">
						                    <li><a href="#">Hyderabad</a></li>
						                    <li><a href="#">Ranga Reddy</a></li>
						                    <li><a href="#">Warangal</a></li>
						                </ul>
						            </li>
						            <li><label class="nav-header"><i class="fa fa-square-o"></i> Karnataka</label></li>
						         </ul>-->
							</div>
						</div>
						<div class="col-md-6">
							<div class="header">
								<h3>Products Details</h3>

							</div>
							<div>
								<ul class="nav nav-list treeview collapse">
									<?php
									switch($role_id) {
										case 4: // sales engineer
										case 5: // distributor
										case 6: // RSM
										case 8: // NSM
											echo map_products($user_products);
										break;
										default:
											
											$all_products_roles = array(11, //global user
																		10, //Sales Director
																		 9, // country head
																		 7, // RBH
																		12, // Stockist
																		 );
											if(in_array($role_id,$all_products_roles))
											{
												?>
											     <li><label class="nav-header"><i class="fa fa-check-square-o"></i> All Products</label></li>
											    <?php
											}
											else {
												?>
											     <li><label class="nav-header"><i class="fa fa-square-o"></i> No Products</label></li>
											    <?php
											}
											?>
											<?php
										break;

									}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('commons/main_footer.php',$nestedView); ?>