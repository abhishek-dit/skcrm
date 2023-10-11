<?php
$this->load->view('commons/main_template', $nestedView);
?>

<div class="container-fluid" id="pcont">
    <?php
    $this->load->view('commons/breadCrumb', $nestedView);
    ?>
    <div class="cl-mcont">
        <?php
        if (isset($_REQUEST['flg'])) {
            $flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
            switch ($flg) {
                case 1:
                    $formHeading = 'Add New Customer';
                    if (!empty($_REQUEST['val'])) {
                        $formHeading = 'Edit Company Details';
                    }
                    ?>
                    <div class="row"> 
                        <div class="col-sm-12 col-md-12">
                            <div class="block-flat">
                                <div class="header">							
                                    <h4><?php echo $formHeading; ?></h4>
                                </div>
                                <div class="content">
                                    <form class="form-horizontal" role="form" action="<?php echo SITE_URL;?>customerAdd"  parsley-validate novalidate method="post">
                                        <input type="hidden" name="customer_id" value="<?php echo @$this->global_functions->encode_icrm(@$customer_data[0]['customer_id']); ?>">
                                        

                                       
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Customer Name<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required class="form-control" id="Name" value="<?php echo @$customer_data[0]['name']; ?>"  name="name"  placeholder="Hospital Name" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Speciality <span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required class="form-control" id="name1" value="<?php echo @$customer_data[0]['name1']; ?>"  name="name1"  placeholder="Speciality" >
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="inputName" class="col-sm-3 control-label">Salutation <span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required class="form-control" id="name" placeholder="Salutation" name="salutation" value="<?php echo @$customer_data[0]['salutation']; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Department<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required class="form-control" id="department" value="<?php echo @$customer_data[0]['department']; ?>"  name="department"  placeholder="Department" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Category<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <?php 
                                                    //$shops=array(" "=>"Select Shop")+$shops_data;
                                                    if(isset($customer_data[0]['category_id'])){
                                                        $category_id=$customer_data[0]['category_id'];
                                                    }else{
                                                        $category_id="";
                                                    }
                                                    $attrs=' required class="form-control category_id" id="category_id"  ';
                                                    echo form_dropdown("category_id",$categories,$category_id,$attrs);
                                                ?>
                                                <!-- <input type="text" required class="form-control" id="category_id" value="<?php //echo @$customer_data[0]['category_id']; ?>"  name="category_id"  placeholder="Category" >-->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Sub Category<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                               
                                                 <?php 
                                                    //$shops=array(" "=>"Select Shop")+$shops_data;
                                                 
                                                 if(!isset($sub_categories)){
                                                     $sub_categories=array();
                                                     $category_sub_id="";
                                                 }else{
                                                    $category_sub_id= $customer_data[0]['category_sub_id'];
                                                 }
                                                    $attrs=' required class="form-control category_sub_id" id="category_sub_id" ';
                                                    echo form_dropdown("category_sub_id",$sub_categories,$category_sub_id,$attrs);
                                                ?>
<!--                                                <input type="text" required class="form-control" id="category_sub_id" value="<?php echo @$customer_data[0]['category_sub_id']; ?>"  name="category_sub_id"  placeholder="Sub Category" >-->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Email<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required parsley-type="email" class="form-control" id="email" value="<?php echo @$customer_data[0]['email']; ?>"  name="email" placeholder="Email" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Telephone<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required  class="form-control" name="telephone" id="telephone" value="<?php echo @$customer_data[0]['telephone']; ?>" placeholder="Telephone" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Fax<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required class="form-control" id="country" value="<?php echo @$customer_data[0]['fax']; ?>"  name="fax" placeholder="Fax" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Mobile<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required   class="form-control" id="mobile" value="<?php echo @$customer_data[0]['mobile']; ?>"  name="mobile" placeholder="Mobile" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Website<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text"  parsley-type="url"  required class="form-control" id="website" value="<?php echo @$customer_data[0]['website']; ?>"  name="website" placeholder="Website">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Address1<span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <textarea required class="form-control" id="address1" name="address1"><?php echo @$customer_data[0]['address1']; ?></textarea>
<!--                                                <input type="text"  parsley-type="url"  required class="form-control" id="address1" value="<?php echo @$customer_data[0]['address1']; ?>"  name="address1" placeholder="Address1">-->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Address2 <span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <textarea required class="form-control" id="address2" name="address2"><?php echo @$customer_data[0]['address2']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Address3 <span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <textarea required class="form-control" id="address3" name="address3"><?php echo @$customer_data[0]['address3']; ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Landmark <span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required class="form-control" id="mobile" value="<?php echo @$customer_data[0]['landmark']; ?>"  name="landmark" placeholder="Landmark" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Pincode <span class="req-fld">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required class="form-control" parsley-type="number"  id="pincode" value="<?php echo @$customer_data[0]['pincode']; ?>"  name="pincode" placeholder="Pincode" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-10">
                                                <button class="btn btn-primary" type="submit" name="submitCustomer" value="button"><i class="fa fa-check"></i> Submit</button>
                                                <a class="btn btn-danger" href="<?php echo SITE_URL; ?>customer"><i class="fa fa-times"></i> Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>				
                        </div>
                    </div><br>

                    <?php
                    break;
            }
        }
        echo $this->session->flashdata('response');
        ?>


        <div class="row"> 
            <div class="col-sm-12 col-md-12">
                <div class="block-flat">
                    <table class="table table-bordered"></table>
                    <div class="content">
                        <div class="row no-gutter" >
                            <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>customer">
                                <div class="col-sm-12">
                                    <label class="col-sm-2 control-label">Customer Name</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="companyName" value="<?php echo @$companyName; ?>" id="companyName" class="form-control">
                                    </div>
                                    <label class="col-sm-2 control-label">Speciality</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="speciality" value="<?php echo @$companyName; ?>" id="companyName" class="form-control">
                                    </div>
                                    <label class="col-sm-2 control-label">Department</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="department" value="<?php echo @$companyName; ?>" id="companyName" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label class="col-sm-2 control-label">Category</label>
                                    <div class="col-sm-2">
                                        <?php 
                                                    //$shops=array(" "=>"Select Shop")+$shops_data;
                                                    if(isset($customer_data[0]['category_id'])){
                                                        $category_id=$customer_data[0]['category_id'];
                                                    }else{
                                                        $category_id="";
                                                    }
                                                    $attrs=' required class="form-control category_id" id="category_id"  ';
                                                    echo form_dropdown("s_category_id",$categories,$category_id,$attrs);
                                                ?>
                                        
                                    </div>
                                    <label class="col-sm-2 control-label">Sub Category</label>
                                    <div class="col-sm-2">
                                         <?php 
                                                    //$shops=array(" "=>"Select Shop")+$shops_data;
                                                 
                                                 if(!isset($sub_categories) ){
                                                    
                                                     $sub_categories=array();
                                                     $category_sub_id="";
                                                 }else{
                                                     
                                                   // $category_sub_id= $customer_data[0]['category_sub_id'];
                                                 }
                                                    $attrs=' required class="form-control category_sub_id" id="category_sub_id" ';
                                                    echo form_dropdown("category_sub_id",$sub_categories,$category_sub_id,$attrs);
                                                ?>
                                    </div>

                                    <div class="col-sm-1">
                                        <button type="submit" name="searchCompany" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                                    </div>
                                    <div class="col-md-1">							
                                        <a href="<?php echo SITE_URL; ?>customer?flg=<?php echo $this->global_functions->encode_icrm(1); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Add New</a>
                                    </div>
                                    <div class="col-sm-2" align="center">
                                        <a class="btn btn-success" href="<?php echo @$download_path; ?>"><i class="fa fa-cloud-download"></i> Download</a>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                        <div class="header"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered hover">
                                <thead>
                                    <tr>
                                        <th class="text-center"><strong>S.NO</strong></th>
                                        <th class="text-center"><strong>Hospital Name</strong></th>
                                         <th class="text-center"><strong>Speciality </strong></th>
                                          
                                           <th class="text-center"><strong>Department</strong></th>
                                        <th class="text-center"><strong>Email</strong></th>
                                        <th class="text-center"><strong>Mobile</strong></th>
                                         <th class="text-center"><strong>Actions</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    @$inc = $start + 1;
                                    if (@$count > 0) {
                                        foreach ($companySearch as $row) {
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo @$inc++; ?></td>
                                                <td class="text-center"><?php echo @$row['name']; ?></td>
                                                <td class="text-center"><?php echo @$row['name1']; ?></td>
                                                <td class="text-center"><?php echo @$row['department']; ?></td>
                                                <td class="text-center"><?php echo @$row['email']; ?></td>
                                                <td class="text-center"><?php echo @$row['mobile']; ?></td>
                                                <td class="text-center">
                                                    <a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>customer?flg=<?php echo $this->global_functions->encode_icrm(1); ?>&val=<?php echo @$this->global_functions->encode_icrm($row['customer_id']); ?>"><i class="fa fa-pencil"></i></a> 
                                                    <?php
                                                    if (@$row['status'] == 1) {
                                                        ?>
                                                        <a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>customer?del=<?php echo @$this->global_functions->encode_icrm($row['customer_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>customer/?activate=<?php echo @$this->global_functions->encode_icrm($row['customer_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>	<tr><td colspan="7" align="center"><span class="label label-primary">No Records</span></td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php $this->global_functions->draw_pagination($start, $count, $page, $length); ?>  
                    </div>
                </div>				
            </div>
        </div>

        <?php
        $this->load->view('commons/main_footer.php', $nestedView);
        ?>
<script>
     $(document).on("change",".category_id",function() {		
        var old_this = $(this);
        $.ajax({
            type: "POST",
            url: "<?php echo SITE_URL;?>customer/get_sub_category",
            data:'cat_id='+$(this).val(),
            beforeSend: function(){
            //$(this).css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
            },
        success: function(data){	  
                    //alert(id);
           // $(id_obj).parent('form').find(".category_sub_id").html(data);
                    $(old_this).parents('form').find('.category_sub_id').html(data);;
                   // alert(name1);
        }
        });

    });
</script>
<style>
.row.no-gutter {
  margin-left: 5px;
  margin-right: 5px;
}

.row.no-gutter [class*='col-']:not(:first-child),
.row.no-gutter [class*='col-']:not(:last-child) {
  padding-right: 5px;
  padding-left: 5px;
}
.row.no-gutter lable{}
</style>