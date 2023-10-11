<?php $this->load->view('commons/main_template', $nestedView); ?>
<?php
echo $this->session->flashdata('response');
?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>punchinlogs">
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
                                    <label class="col-sm-2 control-label">From Date</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="from_Date" id="fromDate" readonly=""  placeholder="YYYY-MM-DD" value="<?php echo @$searchParams['from_Date']; ?>" class="form-control">
                                    </div>
                                    <label class="col-sm-2 control-label">To Date</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="to_Date" id="toDate" readonly="" placeholder="YYYY-MM-DD" value="<?php echo @$searchParams['to_Date']; ?>" class="form-control">
                                    </div>

                                    <div class="col-sm-4" align="right">
                                        <button type="submit" name="searchUserLogs" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                                        <a href="<?php echo SITE_URL;?>punchinlogs"><button type="button" class="btn btn-primary" ><i class="fa fa-refresh"></i></button></a>
                                        <button type="submit" name="downloadUserLogs" title="" class="btn btn-success" formaction="<?php echo SITE_URL; ?>download_punch_in_logs" value="downloadUser"><i class="fa fa-cloud-download"></i> </button>

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
                                    <th class="text-center"><strong>Role</strong></th>
                                    <th class="text-center"><strong>Employee ID</strong></th>
                                    <th class="text-center"><strong>Branch</strong></th>
                                    <th class="text-center"><strong>Punch In Time</strong></th>
                                    <th class="text-center"><strong>Punch Out Time</strong></th>
                                    <th class="text-center"><strong>Battery Status</strong></th>
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
                                            <td class="text-center"><?php echo @$row['branch']; ?></td>
                                            <td class="text-center"><?php echo DateFormatAM(@$row['start_time']); ?></td>
                                            <td class="text-center"><?php echo DateFormatAM(@$row['end_time']); ?></td>
                                            <td class="text-center"><?php echo @$row['battery_percentage'].'%'; ?></td>
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
