<?php
$this->load->view('commons/main_template', $nestedView);
?>

<?php
if (@$flg != '') {
    //$flg = @$this->global_functions->decode_icrm($_REQUEST['flg']);
    if ($flg == 1) {
        if ($val == 1) {
            $formHeading = 'Edit Sub Category';
        } else {
            $formHeading = 'Add Sub Category';
        }
        ?>
        <div class="row"> 
            <div class="col-sm-12 col-md-12">
                <div class="block-flat">
                    <div class="header">                            
                        <h4><?php echo $formHeading; ?></h4>
                    </div>
                    <div class="content">
                    <?php echo validation_errors(); ?>
                        <form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>sub_categoryAdd"  parsley-validate novalidate method="post">
                            <input type="hidden" id="sub_category_id" name="sub_category_id" value="<?php echo @icrm_encode($categoryEdit[0]['category_sub_id']); ?>">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 control-label">Customer Category <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <select class="form-control load_iCheck" name="category_id" id="category">
                                        <option value="">select</option>
                                        <?php
                                            if($customer_category) {
                                                foreach($customer_category as $row) {
                                                    $selected=(@$category_id == $row['category_id'])?'selected':'';
                                                    echo '<option value="'.$row['category_id'].'" '.$selected.'>'.$row['name'].'</option>';
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 control-label">Sub Category Name <span class="req-fld">*</span></label>
                                <div class="col-sm-6">
                                    <input type="name" required class="form-control" id="sub_category_check" placeholder="Name" name="name" value="<?php echo @$categoryEdit[0]['name']; ?>"  maxlength="100">
                                    <p id="subcategoryNameValidating" class="hidden"><i class="fa fa-spinner fa-spin"></i> Checking...</p>
                                <p id="subcategoryCodeError" class="error hidden"></p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button class="btn btn-primary" type="submit" name="submitcategory" value="button"><i class="fa fa-check"></i> Submit</button>
                                    <a class="btn btn-danger" href="<?php echo SITE_URL; ?>sub_category"><i class="fa fa-times"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>              
            </div>
        </div><br>

        <?php
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
                    <div class="row">
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>sub_category">
                            <div class="col-sm-12">

                                <label class="col-sm-2 control-label">Category</label>
                                <div class="col-sm-2">
                                    <input type="text" autocomplete="off" name="category" value="<?php echo @$searchParams['category']; ?>" class="form-control" placeholder="Category Name"  maxlength="100">
                                </div> 
                                <label class="col-sm-2 control-label">Sub Category</label>
                                <div class="col-sm-2">
                                    <input type="text" autocomplete="off" name="sub_category" value="<?php echo @$searchParams['sub_category']; ?>" class="form-control" placeholder="Sub category Name"  maxlength="100">
                                </div> 
                                <div class="col-sm-4">
                                    <button type="submit" title="Search" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                                    <button type="submit" name="downloadcategory" value="1" title="Download" formaction="<?php echo SITE_URL; ?>downloadsub_category" class="btn btn-success"><i class="fa fa-cloud-download"></i> </button>
                                    <a href="<?php echo SITE_URL; ?>addsub_category" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i> </a>
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
                                    <th class="text-center"><strong>Category</strong></th>
                                    <th class="text-center"><strong>Name</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (count($categories) > 0) {
                                foreach ($categories as $row) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo @$sn;$sn++; ?></td>
                                            <td class="text-center"><?php echo @$row['customer']; ?></td>
                                            <td class="text-center"><?php echo @$row['name']; ?></td>
                                            <td class="text-center">
                                                <a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>editsub_category/<?php echo @icrm_encode($row['category_sub_id']); ?>"><i class="fa fa-pencil"></i></a> 
                                        <?php
                                        if (@$row['ccd_status'] == 1) {
                                            ?>
                                                    <a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>deletesub_category/<?php echo @icrm_encode($row['category_sub_id']); ?>" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-trash-o"></i></a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL; ?>activatesub_category/<?php echo @icrm_encode($row['category_sub_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>  <tr><td colspan="6" align="center"><span class="label label-primary">No Records</span></td></tr>
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
    </div>

    <?php
}
$this->load->view('commons/main_footer.php', $nestedView);
?>

