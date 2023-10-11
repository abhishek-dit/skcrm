<div class="modal fade colored-header" id="customerDetails1" tabindex="-1" role="dialog">
    <div class="modal-dialog">
	<form method="post" novalidate parsley-validate="" class="form-horizontal">
		<div class="md-content">
			<div class="modal-header">
				<span style="font-size:18px">Customer Details</span>
				<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
<?php 
    $disabledText = '';
    $disabledSelect = '';
    $editCheck = 1;
    $val = 1;
    if($editCheck == 1)
    {
        $disabledText = 'disabled';
        $disabledSelect = 'disabled = disabled';
    }
?>

			<div class="modal-body form">
				<div class="col-sm-6 col-md-6">
					<div class="formContentBlock">

                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" <?php echo $disabledText; ?> required class="form-control" maxlength="100" id="Name" value="<?php echo @$customer_data[0]['name']; ?>"  name="name"  placeholder="Hospital Name" >
                            </div>
                            <div class="form-group">
                                <label>Salutation </label>
                               
                                     <input type="text" <?php echo $disabledText; ?> required class="form-control" maxlength="100" id="Name" value="<?php echo @$customer_data[0]['salutation']; ?>"  name="name"  placeholder="salutation" >
                      </div>
                            <div class="form-group">
                                <label>Category</label>
                                <?php
                                //$shops=array(" "=>"Select Shop")+$shops_data;
                                if (isset($customer_data[0]['category_id'])) {
                                    $category_id = $customer_data[0]['category_id'];
                                } else {
                                    $category_id = "";
                                }
                                $attrs = $disabledSelect.' required class="form-control category_id" id="category_id"  ';
                                echo form_dropdown("category_id", $categories, $category_id, $attrs);
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" <?php echo $disabledText; ?> parsley-type="email" maxlength="100"  class="form-control" id="email" value="<?php echo @$customer_data[0]['email']; ?>"  name="email" placeholder="Email" >
                            </div>
                            <div class="form-group">
                                <label>Fax</label>
                                <input type="text" <?php echo $disabledText; ?> class="form-control" maxlength="20"  id="country" value="<?php echo @$customer_data[0]['fax']; ?>"  name="fax" placeholder="Fax" >
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <?php
                                    if($val == 1)
                                    {
                                        $attrs = ' disabled=disabled';
                                    }
                                    else $attrs = '';
                                ?>    
                                <select required style="width:100%" class="checkLocation" name="city_id" <?php echo $attrs; ?>>
                                    <?php if($val == 1) 
                                    {
                                        ?>
                                        <option value="<?php echo $city['location_id']; ?>"><?php echo $city['location']; ?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <option value="">--Select Location--</option>
                                        <?php
                                    } ?>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                    <input type="text" <?php echo $disabledText; ?> required class="form-control" maxlength="10"  parsley-type="number"  id="pincode" value="<?php echo @$customer_data[0]['pincode']; ?>"  name="pincode" placeholder="Pincode" >
                            </div>
                            <div class="form-group">
                                <label>TAN</label>
                                    <input type="text" <?php echo $disabledText; ?> required class="form-control" maxlength="30"    id="tan" value="<?php echo @$customer_data[0]['tan']; ?>"  name="tan" placeholder="TAN" >
                            </div>
                      <div class="form-group">
                                <label>Address1</label>
                                    <textarea required <?php echo $disabledText; ?> class="form-control"   maxlength="255"  id="address1" name="address1"><?php echo @$customer_data[0]['address1']; ?></textarea>
                                    <!-- <input type="text"  parsley-type="url"  required class="form-control" id="address1" value="<?php echo @$customer_data[0]['address1']; ?>"  name="address1" placeholder="Address1">-->
                            </div>
                            <div class="form-group">
                                <label>Address3 </label>
                                    <textarea class="form-control" <?php echo $disabledText; ?> maxlength="255"  id="address3" name="address3"><?php echo @$customer_data[0]['address3']; ?></textarea>
                            </div>

                            
                            

					</div>
				</div>
				<div class="col-sm-6 col-md-6">
					<div class="formContentBlock">
                            <div class="form-group">
                                <label>Customer Type</label>
                                <input type="text" <?php echo $disabledText; ?> class="form-control" maxlength="100"  id="name1" value="<?php echo @$customer_data[0]['name1']; ?>"  name="name1"  placeholder="Speciality" >
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <input type="text" <?php echo $disabledText; ?> class="form-control" maxlength="100"  id="department" value="<?php echo @$customer_data[0]['department']; ?>"  name="department"  placeholder="Department" >
                            </div>
                            <div class="form-group">
                                <label>Sub Category</label>
                                <?php
                                if (!isset($sub_categories)) {
                                    $sub_categories = array(' '=>'Select Sub Category');
                                    $category_sub_id = "";
                                } else {
                                    $category_sub_id = $customer_data[0]['category_sub_id'];
                                }
                                $attrs = $disabledSelect.' required class="form-control category_sub_id" id="category_sub_id" ';
                                echo form_dropdown("category_sub_id", @$sub_categories, @$category_sub_id, @$attrs);
                                ?>
                                <!--<input type="text" required class="form-control" id="category_sub_id" value="<?php echo @$customer_data[0]['category_sub_id']; ?>"  name="category_sub_id"  placeholder="Sub Category" >-->
                            </div>
                            <div class="form-group">
                                <label>Telephone</label>
                                <input type="text" <?php echo $disabledText; ?>  class="form-control" maxlength="15"  name="telephone" id="telephone" value="<?php echo @$customer_data[0]['telephone']; ?>" placeholder="Telephone" >
                            </div>
                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" <?php echo $disabledText; ?>  class="form-control" maxlength="15"  id="mobile" value="<?php echo @$customer_data[0]['mobile']; ?>"  name="mobile" placeholder="Mobile" >
                            </div>
                            <div class="form-group">
                                <label>Website</label>
                                <input type="text" <?php echo $disabledText; ?>  parsley-type="url" class="form-control"  maxlength="225"  id="website" value="<?php echo @$customer_data[0]['website']; ?>"  name="website" placeholder="Website">
                            </div>
                            <div class="form-group">
                                <label>PAN</label>
                                    <input type="text" <?php echo $disabledText; ?> required class="form-control" maxlength="30"    id="tin" value="<?php echo @$customer_data[0]['pan']; ?>"  name="pan" placeholder="PAN" >
                            </div>
                            <div class="form-group">
                                <label>TIN</label>
                                    <input type="text" <?php echo $disabledText; ?> required class="form-control" maxlength="30"    id="tin" value="<?php echo @$customer_data[0]['tin']; ?>"  name="tan" placeholder="TIN" >
                            </div>
                            
                      <div class="form-group">
                                <label>Address2 </label>
                                    <textarea required <?php echo $disabledText; ?> class="form-control"  maxlength="255"  id="address2" name="address2"><?php echo @$customer_data[0]['address2']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Landmark </label>
                                    <input type="text" <?php echo $disabledText; ?> class="form-control" maxlength="225"  id="mobile" value="<?php echo @$customer_data[0]['landmark']; ?>"  name="landmark" placeholder="Landmark" >
                            </div>
                            
                            

					</div>
				</div>
				<!--<div class="form-group">
				<label>Your name</label> <input type="name" class="form-control" placeholder="John Doe">
				</div>-->

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Close</button>
			</div>
		</div>
	</form>
</div>
</div>
<div class="md-overlay"></div>


<div class="modal fade colored-header" id="installationBase" tabindex="-1" role="dialog">
    <div class="modal-dialog">
    <form method="post" novalidate parsley-validate="" class="form-horizontal">
        <div class="md-content">
            <div class="modal-header">
                <span style="font-size:18px">Customers Installation Base</span>
                <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body form">
                 <table class="table table-bordered hover" id="table1">
                    <thead>
                        <tr>

                            
                            <th class="text-center" width="20%"><strong>Manufacturer</strong></th>
                            <th class="text-center" width="35%"><strong>ProducManufacturert Model</strong></th>
                            <th class="text-center"  width="5%"><strong>Quantity</strong></th>
                            <th class="text-center" width="10%"><strong>Make</strong></th>
                            <th class="text-center" width="15%"><strong>Year Of Purchase</strong></th>
                            <th class="text-center" width="15%"><strong>Replacement Year</strong></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $customer_installation = $this->customer_model->getInstallations($customer_data[0]['customer_id']);
                        // print_r($opportunities);
                        if (count(@$customer_installation) > 0) {
                            foreach ($customer_installation as $v) {
                                ?>

                                <tr>
                                    <td class="text-center"><?php echo @$v['competitors']; ?></td>
                                    <td class="text-center"><?php echo @$v['product_model']; ?></td>
                                    <td class="text-center"><?php echo @$v['quantity']; ?></td>
                                    <td class="text-center"> <?php echo @$v['make']; ?></td>
                                    <td class="text-center"> <?php echo @$v['year_of_purchase']; ?></td>
                                    <td class="text-center"> <?php echo @$v['replacement_year']; ?></td>
                                </tr>


                            
                        <?php }
                        }
                        else {
                            ?>
                            <tr><td colspan="6" align="center">No records</td></tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>    

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
</div>
</div>
<div class="md-overlay"></div>


