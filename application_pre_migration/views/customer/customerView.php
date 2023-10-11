<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
$role_id=$this->session->userdata('role_id');
if (@$flg != '') {
    //$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
    if ($flg == 1) {
        $disabledText = '';
        $disabledSelect = '';
        if ($editCheck == 1) {
            $disabledText = 'disabled';
            $disabledSelect = 'disabled = disabled';
        }
        if ($val == 1) {
            $formHeading = 'Edit Customer Details';
        } else {
            $formHeading = 'Add New Customer';
        }
        echo $this->session->flashdata('response');
        ?>
<?php  if ($editCheck != 0) {
            $form_cation="customerInstallationAdd";
        }else{
            $form_cation="customerAdd";
        }
?>

        <form class="form-horizontal" role="form" action="<?php echo SITE_URL.$form_cation; ?>"  parsley-validate novalidate method="post">
        <div class="row"> 
            <div class="col-sm-12 col-md-12">
                <div class="block-flat">
                    <div class="header">							 <!--data-modal="form-primary -->
                        <h4><?php echo $formHeading; ?>
                            <button type="button" class="btn btn-primary btn-flat md-trigger  align-right" title="Installation Based On Customer" style="float:right; padding:6px;" id="customer_installation" data-toggle="modal" data-target="#customerinstallation_model"><i class="fa fa-book fa-fw"></i> Installed Base</button></h4>
                    </div>
                    <div class="content">
                        
                            <input type="hidden" name="customer_id" id="customer_id" value="<?php echo @icrm_encode(@$customer_data[0]['customer_id']); ?>">
                            <input type="hidden" name="parent" value="<?php echo @$parent ?>">


                            <div class="form-group">
                                <label class="col-sm-3 control-label">Customer Name<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?> required class="form-control" maxlength="100" id="Name" value="<?php echo @$customer_data[0]['name']; ?>"  name="name"  placeholder="Hospital Name" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Customer Code</label>
                                <div class="col-sm-6">
                                    <?php 
                                    $customer_code_disable = $disabledText;
                                    if(@$customer_data[0]['remarks2']!=''){
                                            $customer_code_disable = 'disabled';
                                        }?>
                                    <input type="text" <?php echo $customer_code_disable; ?> class="form-control" maxlength="10" id="customer_code" value="<?php echo @$customer_data[0]['remarks2']; ?>"  name="customer_code"  placeholder="Customer Code" >
                                    <p id="customerCodeValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
                                <p id="customerCodeError" class="error hidden"></p>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="inputName" class="col-sm-3 control-label">Salutation </label>
                                <div class="col-sm-6">
                                    <?php
                                    $salutation=array('0'=>"Select Salutation")+get_salutation();
                                    $attrs = $disabledSelect . '  class="select2 salutation" id="salutation"  ';
                                    echo form_dropdown("salutation", @$salutation,@$customer_data[0]['salutation'] , @$attrs);
                                    ?>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Department</label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?> class="form-control" maxlength="100"  id="department" value="<?php echo @$customer_data[0]['department']; ?>"  name="department"  placeholder="Department" >
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Category<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    //$shops=array(" "=>"Select Shop")+$shops_data;
                                    if (isset($customer_data[0]['category_id'])) {
                                        $category_id = $customer_data[0]['category_id'];
                                    } else {
                                        $category_id = "";
                                    }
                                    $attrs = $disabledSelect . ' required class="form-control category_id" id="category_id"  ';
                                    echo form_dropdown("category_id", $categories, $category_id, $attrs);
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Sub Category<span class="req-fld">*</span></label>
                                <div class="col-sm-6">

                                    <?php
                                    if (!isset($sub_categories)) {
                                        $sub_categories = array(' ' => 'Select Sub Category');
                                        $category_sub_id = "";
                                    } else {
                                        $category_sub_id = $customer_data[0]['category_sub_id'];
                                    }
                                    $attrs = $disabledSelect . ' required class="form-control category_sub_id" id="category_sub_id" ';
                                    echo form_dropdown("category_sub_id", @$sub_categories, @$category_sub_id, @$attrs);
                                    ?>
                                            <!--<input type="text" required class="form-control" id="category_sub_id" value="<?php echo @$customer_data[0]['category_sub_id']; ?>"  name="category_sub_id"  placeholder="Sub Category" >-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?> parsley-type="email" maxlength="100"  class="form-control" id="email" value="<?php echo @$customer_data[0]['email']; ?>"  name="email" placeholder="Email" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Telephone</label>
                                <div class="col-sm-1">
                                    <?php
                                    $telephone[0] = "91";
                                    if (@$customer_data[0]['telephone'] != NULL) {
                                        $telephone = explode("-", @$customer_data[0]['telephone']);
                                    }
                                    echo form_dropdown('isd1', @$isd, @$telephone[0], 'class="select2"');
                                    ?>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" <?php echo $disabledText; ?>  maxlength="10"  class="form-control only-numbers" id="telephone" value="<?php echo @$telephone[1]; ?>"  name="telephone" placeholder="Telephone" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Mobile </label>
                                <div class="col-sm-1">
                                    <?php
                                    $mobile[0] = "91";
                                    if (@$customer_data[0]['mobile'] != NULL) {
                                        $mobile = explode("-", @$customer_data[0]['mobile']);
                                    }
                                    echo form_dropdown('isd2', @$isd, $mobile[0], 'class="select2"');
                                    ?>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" size="10" maxlength="10" class="form-control only-numbers"  name="mobile" value="<?php echo @$mobile[1]; ?>" placeholder="Mobile Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Fax </label>
                                <div class="col-sm-1">
                                    <?php
                                    $fax[0] = "91";
                                    if (@$customer_data[0]['fax'] != NULL) {
                                        $fax = explode("-", @$customer_data[0]['fax']);
                                    }
                                    echo form_dropdown('isd3', @$isd, $fax[0], 'class="select2"');
                                    ?>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" <?php echo $disabledText; ?> class="form-control only-numbers" maxlength="10"  id="country" value="<?php echo @$fax[1]; ?>"  name="fax" placeholder="Fax" >
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Website</label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?>  parsley-type="url" class="form-control"  maxlength="225"  id="website" value="<?php echo @$customer_data[0]['website']; ?>"  name="website" placeholder="Website">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">City<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if ($val == 1) {
                                        $attrs = ' disabled=disabled';
                                    } else
                                        $attrs = '';
                                    ?>    
                                    <select required style="width:100%" class="checkLocation" name="city_id" <?php echo $attrs; ?>>
                                    <?php
                                    if ($val == 1) {
                                        ?>
                                            <option value="<?php echo $city['location_id']; ?>"><?php echo $city['location']; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="">--Select Location--</option>
                                            <?php }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address1<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <textarea required <?php echo $disabledText; ?> class="form-control"   maxlength="255"  id="address1" name="address1"><?php echo @$customer_data[0]['address1']; ?></textarea>
                                    <!-- <input type="text"  parsley-type="url"  required class="form-control" id="address1" value="<?php echo @$customer_data[0]['address1']; ?>"  name="address1" placeholder="Address1">-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address2 </label>
                                <div class="col-sm-6">
                                    <textarea <?php echo $disabledText; ?> class="form-control"  maxlength="255"  id="address2" name="address2"><?php echo @$customer_data[0]['address2']; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address3 </label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" <?php echo $disabledText; ?> maxlength="255"  id="address3" name="address3"><?php echo @$customer_data[0]['address3']; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Landmark </label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?> class="form-control" maxlength="225"  id="mobile" value="<?php echo @$customer_data[0]['landmark']; ?>"  name="landmark" placeholder="Landmark" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Pincode </label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?>  class="form-control" maxlength="10"  parsley-type="number"  id="pincode" value="<?php echo @$customer_data[0]['pincode']; ?>"  name="pincode" placeholder="Pincode" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PAN </label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?>  class="form-control" maxlength="10"   id="pan" value="<?php echo @$customer_data[0]['pan']; ?>"  name="pan" placeholder="PAN" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">TAN </label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?>  class="form-control" maxlength="20"  id="tan" value="<?php echo @$customer_data[0]['tan']; ?>"  name="tan" placeholder="TAN" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">TIN </label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?>  class="form-control" maxlength="20" id="tin" value="<?php echo @$customer_data[0]['tin']; ?>"  name="tin" placeholder="TIN" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">GST Number </label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?>  class="form-control" maxlength="30" id="gst" value="<?php echo @$customer_data[0]['gst']; ?>"  name="gst" placeholder="GST Number" >
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                <?php
                                if ($editCheck == 0) {
                                    $redirect = ($parent == 1) ? 'newLead' : ($parent == 2) ? 'assignLeads' : 'customer';
                                    ?>
                                        <button class="btn btn-primary" type="submit" name="submitCustomer" value="button"><i class="fa fa-check"></i> Submit</button>
                                        <a class="btn btn-danger" href="<?php echo SITE_URL . $redirect; ?>"><i class="fa fa-times"></i> Cancel</a>
                                        <?php
                                    }
                                    ?>    
                                </div>
                            </div>
                        <!--</form>-->
                    </div>
                </div>				
            </div>
        </div><br>

        <?php
        // break;
    }
}

?>
<?php
if (@$displayList == 1) {
    echo $this->session->flashdata('response');
    ?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>customer">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Customer </label>
                                    <div class="col-sm-2">
                                        <input type="text" name="s_customerName" maxlength="100" placeholder="Name" value="<?php echo @$search_data['customerName']; ?>" id="companyName" class="form-control">
                                    </div>

                                    <label class="col-sm-2 control-label">Category</label>
                                    <div class="col-sm-2">
    <?php
    $attrs = '  class="select2 category_id" id="category_id"  ';
    echo form_dropdown("s_category_id", @$categories, @$search_data['category_id'], @$attrs);
    ?>

                                    </div>
                                    

                                </div> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                <label class="col-sm-2 control-label">Sub Category</label>
                                    <div class="col-sm-2">
                                        <?php
                                        if (!isset($s_sub_categories)) {

                                            $s_sub_categories = array(' ' => 'Select Sub Category');
                                            $s_category_sub_id = "";
                                        } else {

                                            $category_sub_id = $search_data['category_sub_id'];
                                        }
                                        $attrs = '  class="select2 category_sub_id" id="category_sub_id" ';
                                        echo form_dropdown("s_category_sub_id", @$s_sub_categories, @$search_data['category_sub_id'], @$attrs);
                                        ?>
                                    </div>
                                <label class="col-sm-2 control-label">Location</label>
                                    <div class="col-sm-2">
                                        <select style="width:100%" name="s_location" class="checkLocation">
                                            <option value="<?php echo $s_loc['location_id']; ?>"><?php echo $s_loc['location']; ?></option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4" align="left">
                                        <button type="submit" name="searchCustomer" value="1" class="btn btn-success" title="Search"><i class="fa fa-search"></i> </button>
                                        <?php if(in_array($role_id, allowed_download_roles()))
                                        { ?>
                                        <button type="submit" name="downloadCustomer" class="btn btn-success" formaction="<?php echo SITE_URL; ?>downloadCustomer" title="Download" value="downloadCustomer"><i class="fa fa-cloud-download"></i> </button>
                                        <?php } ?>
                                        <a href="<?php echo SITE_URL; ?>addCustomer" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                <div class="form-group">
                                </div>
                            </div>
                        </div>
                        -->
                    </form>

                    <div class="header"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center"><strong>S.NO</strong></th>
                                    <th class="text-center"><strong>Customer Name</strong></th>
                                     <th class="text-center"><strong>Telephone</strong></th>
                                      <th class="text-center"><strong>Mobile</strong></th>
                                    <th class="text-center"><strong>Location</strong></th>
                                  
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    //@$inc = $start + 1;
    if (@count($customerSearch) > 0) {
        foreach ($customerSearch as $row) {
            ?>
                                        <tr>
                                            <td class="text-center" style="width:7%"><?php echo @$sn++; //@$sn ?></td>
                                            <td style="width:35%"><?php echo @$row['name']; ?></td>
                                            <td class="text-center"  style="width:15%"><?php echo @$row['telephone']; ?></td>
                                            <td class="text-center"  style="width:15%"><?php echo getPhoneNumber(@$row['mobile']); ?></td>
                                            <td class="text-center"  style="width:15%"><?php echo @$row['location'];  ?></td>
                                            <td class="text-center"  style="width:13%">
            <?php
            if ($editCheck == 1) {
                ?>
                                                    <a class="btn btn-primary" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editCustomer/<?php echo @icrm_encode($row['customer_id']); ?>"><i class="fa fa-info"></i></a> 
                <?php
            } else {
                ?>
                                                    <a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editCustomer/<?php echo @icrm_encode($row['customer_id']); ?>"><i class="fa fa-pencil"></i></a> 
                                                    <?php
                                                    if (@$row['status'] == 1) {
                                                        ?>
                                                        <a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>deleteCustomer/<?php echo @icrm_encode($row['customer_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>activateCustomer/<?php echo @icrm_encode($row['customer_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
                                                        <?php
                                                    }
                                                }
                                                ?>    

                                            </td>
                                        </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>	<tr><td colspan="6" align="center"><span class="label label-primary">No Records</span></td></tr>
                                        <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-left"><?php echo @$pagermessage; ?></div>
                            <div class="pull-right">
                                <div class="dataTables_paginate paging_bs_normal">
                                <?php echo @$pagination_links; ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>				
            </div>
        </div>
<?php } ?>
<!-- Nifty Modal -->
            
                <div class="modal fade colored-header in" id="customerinstallation_model"  style="width:70%; margin: auto;">
                    <?php  /*if ($editCheck != 0) {?>
                    <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>customerInstallationAdd"  parsley-validate novalidate method="post">
                    <?php } */?>
                    <div class="md-content">
                    
                      <div class="modal-header">
                        <h3>Customer Installation Base</h3>
                        <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      </div>
                      <div class="modal-body form">
                      
                     <table class="table no-border hover" id="table1">
                        <thead>
                            <tr>

                                
                                <th class="text-center" width="20%"><strong>Competitors</strong></th>
                                <th class="text-center" width="35%"><strong>Product Model</strong></th>
                                <th class="text-center"  width="5%"><strong>Quantity</strong></th>
                                <th class="text-center" width="10%"><strong>Make</strong></th>
                                <th class="text-center" width="15%"><strong>Year Of Purchase</strong></th>
                                <th class="text-center" width="15%"><strong>Replacement Year</strong></th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
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
                            } ?>
							<tr class="insert_table_row">
                            	
                                <td  align="center">
                                <input type="text"  class="form-control" maxlength="150"  id="competitor" value=""  name="competitors[]"  maxlength="150" placeholder="Competitors" >
                                </td>
                                <td  align="center">
                                <input type="text"  class="form-control" maxlength="150"  id="product_model" value=""  name="product_model[]" placeholder="Product Model" >
                                </td>
                                <td  align="center">
                                <input type="text"  class="form-control only-numbers"   maxlength="5"  id="quantity" value=""  name="quantity[]" placeholder="Quantity" >
                                </td>
                                <td  align="center">
                                <input type="text"  class="form-control" maxlength="150"  id="make" value=""  name="make[]" placeholder="Make" >
                                </td>
                                <td  align="center">
                                <input type="text"  class="form-control only-numbers" maxlength="4"  id="year_of_purchase" value=""  name="year_of_purchase[]" placeholder="Year Of Purchase" >
                                </td>
                                <td  align="center">
                                <input type="text"  class="form-control only-numbers" maxlength="4"  id="replacement_year" value=""  name="replacement_year[]" placeholder="Replacement Year" >
                                </td>
                            </tr>                            
                            </tbody>
                    </table>
                    <div>
                    <button type="button" class="btn btn-danger delete" title="Customer Installation" style="float:right; padding:4px;" ><i class="fa fa-minus"></i></button>
                    <button  type="button" class="btn btn-success addCustomerCounsult" title="Customer Installation" style="float:right; padding:4px; margin-right:5px;" id="customer_installation"><i class="fa fa-plus"></i></button>
                    &nbsp;
                    
                    </div>

                      </div>
                      <div class="modal-footer">
                      <?php // print_r($customer_data);
                       
                       if ($editCheck != 0) {?>
                          <input type="hidden" name="id" value="<?php echo @icrm_encode(@$customer_data[0]['customer_id']); ?>" >
                          <button type="submit" class="btn btn-primary btn-flat" name="customer_install" value="Customer Installation">Submit</button>
                          <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
                         <?php }else{?>
                        <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Ok</button>
                         <?php }?>
                      </div>
                     
                    </div>
                </div>
<div class="md-overlay"></div>  </form>               
<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>