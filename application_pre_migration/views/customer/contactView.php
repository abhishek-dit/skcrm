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
            $formHeading = 'Edit Contact Details';
        } else {
            $formHeading = 'Add New Contact';
        }
        ?>
        <div class="row"> 
            <div class="col-sm-12 col-md-12">
                <div class="block-flat">
                    <div class="header">							
                        <h4><?php echo $formHeading; ?></h4>
                    </div>
                    <div class="content">
                        <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>contactAdd"  parsley-validate novalidate method="post">
                            <input type="hidden" name="contact_id" value="<?php echo @$this->global_functions->encode_icrm(@$contact_data[0]['contact_id']); ?>">
                            <input type="hidden" name="parent" value="<?php echo @$parent ?>">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salutation<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    $salutation=get_salutation();
                                    $attrs = $disabledSelect . ' required class="select2 salutation" id="salutation"  ';
                                    echo form_dropdown("salutation", @$salutation,@$contact_data[0]['salutation'] , @$attrs);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">First name<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?> required  maxlength="80"  class="form-control" id="first_name" value="<?php echo @$contact_data[0]['first_name']; ?>"  name="first_name" placeholder="First Name" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Last name</label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?>  maxlength="80"  class="form-control" id="last_name" value="<?php echo @$contact_data[0]['last_name']; ?>"  name="last_name" placeholder="Last Name" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Speciality<span class="req-fld">*</span></label>
                                <div class="col-sm-6">

                                    <?php
                                    $speciality = array(" " => "Select Speciality") + $speciality;
                                    if (isset($contact_data[0]['speciality_id'])) {
                                        $speciality_id = $contact_data[0]['speciality_id'];
                                    } else {
                                        $speciality_id = "";
                                    }
                                    $attrs = $disabledSelect . ' required class="select2 speciality_id" id="speciality_id"  ';
                                    echo form_dropdown("speciality_id", @$speciality, @$speciality_id, @$attrs);
                                    ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Customer<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if ($val == 1)
                                        $dis = 'disabled=disabled';
                                    else
                                        $dis = '';
                                    ?>
                                    <select style="width:100%" required class="checkCustomer" name="customer_id" <?php echo $dis; ?>  id="customer">
                                        <?php
                                        if ($val == 1) {
                                            ?>
                                            <option value="<?php echo $customerDetails['customer_id'] ?>"><?php echo $customerDetails['customer']; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="">Select Customer</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Telephone</label>
                                <div class="col-sm-1">
                                    <?php
                                    $telephone[0] = "91";
                                    if (@$contact_data[0]['telephone'] != NULL) {
                                        $telephone = explode("-", @$contact_data[0]['telephone']);
                                    }
                                    echo form_dropdown('isd1', @$isd, @$telephone[0], 'class="select2"');
                                    ?>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" <?php echo $disabledText; ?>  maxlength="10"  class="form-control only-numbers" id="telephone" value="<?php echo @$telephone[1]; ?>"  name="telephone" placeholder="Telephone" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Mobile <span class="req-fld">*</span> </label>
                                <div class="col-sm-1">
                                    <?php
                                    $mobile[0] = "91";
                                    if (@$contact_data[0]['mobile_no'] != NULL) {
                                        $mobile = explode("-", @$contact_data[0]['mobile_no']);
                                    }
                                    echo form_dropdown('isd2', @$isd, $mobile[0], 'class="select2"');
                                    ?>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" size="10" maxlength="10" class="form-control only-numbers" required name="mobile_no" value="<?php echo @$mobile[1]; ?>" placeholder="Mobile Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Fax </label>
                                <div class="col-sm-1">
                                    <?php
                                    $fax[0] = "91";
                                    if (@$contact_data[0]['fax'] != NULL) {
                                        $fax = explode("-", @$contact_data[0]['fax']);
                                    }
                                    echo form_dropdown('isd3', @$isd, $fax[0], 'class="select2"');
                                    ?>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" size="10" maxlength="10" class="form-control only-numbers" value="<?php echo @$fax[1]; ?>" name="fax" placeholder="Fax">
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?> required parsley-type="email" maxlength="80"  class="form-control" id="email" value="<?php echo @$contact_data[0]['email']; ?>"  name="email" placeholder="Email" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address Line 1<span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?> maxlength="225"  class="form-control" id="address1" required value="<?php echo @$contact_data[0]['address1']; ?>"  name="address1" placeholder="Address1" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address Line 2</label>
                                <div class="col-sm-6">
                                    <input type="text" <?php echo $disabledText; ?> maxlength="225"  class="form-control" id="address2" value="<?php echo @$contact_data[0]['address2']; ?>"  name="address2" placeholder="Address2" >
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">

                                            <?php
                                            if ($editCheck == 0) {
                                                $redirect = ($parent == 1) ? 'newLead' : ($parent == 2) ? 'assignLeads' : 'contact';
                                                ?>
                                        <button class="btn btn-primary" type="submit" name="submitContact" value="button"><i class="fa fa-check"></i> Submit</button>
                                        <a class="btn btn-danger" href="<?php echo SITE_URL . $redirect; ?>"><i class="fa fa-times"></i> Cancel</a>
                                        <?php
                                    }
                                    ?>    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>				
            </div>
        </div><br>

        <?php
        // break;
    }
}
echo $this->session->flashdata('response');
?>
<?php
if (@$displayList == 1) {
    ?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>contact">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-1 control-label">Customer</label>
                                    <div class="col-sm-3">
                                        <select style="width:100%" class="checkCustomer" name="customer">
                                            <option value="<?php echo $s_cus['customer_id']; ?>"><?php echo $s_cus['customer']; ?></option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label">Speciality</label>
                                    <div class="col-sm-2">
    <?php echo form_dropdown('c_speciality', $SpecialityInfo, @$searchParams['c_speciality'], 'class="select2"'); ?>
                                    </div>
                                    <label class="col-sm-1 control-label">Contact</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="contactName" placeholder="Name" maxlength="100"  value="<?php echo @$searchParams['contactName']; ?>" id="companyName" class="form-control">
                                    </div>
                               
                           
                                <div class='col-sm-2'>
                                    <button type="submit" title="Search" name="searchContact" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                    <?php if(in_array($role_id, allowed_download_roles()))
                                    { ?>
                                    <button type="submit" name="downloadContact"  title="Download"  class="btn btn-success" formaction="<?php echo SITE_URL; ?>downloadContact" value="downloadCustomer"><i class="fa fa-cloud-download"></i> </button>
                                    <?php } ?>
                                    <a href="<?php echo SITE_URL; ?>addContact" class="btn btn-success" title="Add New"><i class="fa fa-plus"></i> </a>
                                </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="header"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center"><strong>S.NO</strong></th>
                                    <th class="text-center"><strong>Name</strong></th>
                                    <th class="text-center"><strong>Customer</strong></th>
                                    <th class="text-center"><strong>Speciality</strong></th>
                                    <th class="text-center"><strong>Email</strong></th>
                                    <th class="text-center"><strong>Mobile</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    //@$inc = $start + 1;
    if (@count($contactSearch) > 0) {
        foreach ($contactSearch as $row) {
            ?>
                                        <tr>
                                            <td class="text-center"><?php echo @$sn++; //@$sn   ?></td>
                                            <td class="text-center"><?php echo @$row['first_name']; ?></td>
                                            <td class="text-center"><?php echo @$row['customer']; ?></td>
                                            <td class="text-center"><?php echo @$row['speciality']; ?></td>
                                            <td class="text-center"><?php echo @$row['email']; ?></td>
                                            <td class="text-center"><?php echo getPhoneNumber(@$row['mobile_no']); ?></td>
                                            <td class="text-center">
            <?php
            if ($editCheck == 1) {
                ?>
                                                    <?php if(@$row['status'] == 1)
                                                    {
                                                        ?>
                                                    <a class="btn btn-primary" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editContact/<?php echo @icrm_encode($row['contact_id']); ?>"><i class="fa fa-info"></i></a> 
                                                        <?php
                                                    } 
                                                    else 
                                                    {
                                                        ?>
                                                        Disabled
                                                        <?php
                                                    }
                                                    ?>                       
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editContact/<?php echo @icrm_encode($row['contact_id']); ?>"><i class="fa fa-pencil"></i></a> 
                                                    <?php
                                                    if (@$row['status'] == 1) {
                                                        ?>
                                                        <a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>deleteContact/<?php echo @icrm_encode($row['contact_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>activateContact/<?php echo @icrm_encode($row['contact_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </td>
                                        </tr>
            <?php
        }
    } else {
        ?>	<tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
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
    <?php
}
//print_r($this->session->userdata);
//echo $this->session->userdata('check');
?>
    <?php $this->load->view('commons/main_footer.php', $nestedView); ?>
