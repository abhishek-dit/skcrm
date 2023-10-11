<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>users">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Role </label>
                                    <div class="col-sm-2">
                                        <select class="form-control select2" name="user_role">
                                            <option value="">Select Role</option>
                                            <?php
                                            foreach ($roles as $role) {
                                                $rselected = ($role['role_id']==$searchParams['user_role'])?'selected':'';
                                                echo '<option value = "'.$role['role_id'].'" '.$rselected.'>'.$role['name'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="user_name"  maxlength="100" placeholder="Enter Name" class="form-control" value="<?php echo @$searchParams['user_name'] ?>">
                                    </div>
                                    <label class="col-sm-2 control-label">Employee ID</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="employeeId"  maxlength="100" placeholder="Enter EMP ID" class="form-control" value="<?php echo @$searchParams['employeeId'] ?>">
                                    </div>
                                    
                                </div> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="email"  maxlength="100" placeholder="Enter Email" value="<?php echo @$searchParams['email']; ?>" class="form-control">
                                    </div>
                                    <label class="col-sm-2 control-label">Mobile</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="mobile"  maxlength="10" placeholder="Enter Mobile" value="<?php echo @$searchParams['mobile']; ?>" class="form-control">
                                    </div>

                                    <div class="col-sm-4" align="right">
                                        <button type="submit" name="searchUser" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                                        <button type="submit" name="downloadUser" title="" class="btn btn-success" formaction="<?php echo SITE_URL; ?>downloadUser" value="downloadUser"><i class="fa fa-cloud-download"></i> </button>
                                        <a href="<?php echo SITE_URL; ?>addUser" title="Add New" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
                                    <th class="text-center"><strong>Name</strong></th>
                                    <th class="text-center"><strong>Role</strong></th>
                                    <th class="text-center"><strong>Employee ID</strong></th>
                                    <th class="text-center"><strong>Email</strong></th>
                                    <th class="text-center"><strong>Mobile</strong></th>
                                    <th class="text-center"><strong>Branch</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            //@$inc = $start + 1;
                            if (count(@$userSearch) > 0) {
                                foreach ($userSearch as $row) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo @$sn++;//@$sn ?></td>
                                            <td class="text-center"><?php echo @$row['first_name'].' '.@$row['last_name']; ?></td>
                                            <td class="text-center"><?php echo @$row['role']; ?></td>
                                            <td class="text-center"><?php echo @$row['employee_id']; ?></td>
                                            <td class="text-center"><?php echo @$row['email_id']; ?></td>
                                            <td class="text-center"><?php echo getPhoneNumber(@$row['mobile_no']); ?></td>
                                            <td class="text-center"><?php echo @$row['branch']; ?></td>
                                            <td class="text-center">
                                                <a class="btn btn-primary" style="padding:3px 3px;" href="<?php echo SITE_URL;?>viewUserDetails/<?php echo @icrm_encode($row['user_id']); ?>"><i class="fa fa-eye"></i></a> 
                                                
                                                <?php
                                                if (@$row['status'] == 1) {
                                                    ?>
                                                    <a class="btn btn-default" style="padding:3px 3px;" href="<?php echo SITE_URL;?>editUser/<?php echo @icrm_encode($row['user_id']); ?>"><i class="fa fa-pencil"></i></a> 
                                                    <a class="btn btn-danger" style="padding:3px 3px;" href="<?php echo SITE_URL;?>deleteUser/<?php echo @icrm_encode($row['user_id']); ?>" onclick="return confirm('Are you sure you want to Deactivate?')"><i class="fa fa-trash-o"></i></a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="btn btn-info" title="Activate" style="padding:3px 3px;" href="<?php echo SITE_URL;?>activateUser/<?php echo @icrm_encode($row['user_id']); ?>"  onclick="return confirm('Are you sure you want to Activate?')"><i class="fa fa-check"></i></a>
                                                    <?php
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
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>
