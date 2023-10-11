<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row ">
	<div class="col-md-12 col-sm-12 ">
		<div class="block-flat">
			<div class ="content">
            <div class="row no-gutter " >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>get_rbh_distributor_list">
                            <div class="col-sm-12">
                                 <div class="col-sm-4">
                                       <?php
                                         $attrs = '  class="select2 dist_id" id="billing_id"  ';
                                        @$users=array(''=>'Select Distributor Name')+@$users;
                                        echo form_dropdown("users_id", @$users, @$searchParams['users_id'], @$attrs);
                                        ?>
                                    </div>
                             <div class="col-sm-2">
                                    <button type="submit" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                             </div>  
                                
                            </div>
                            </div>
                        </form>
                    </div>
            <div class="row" style="margin-top: 15px"> 
                <form >
                  <div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%"><strong>Sno</strong></th>
                                    <th class="text-center" width='10%'><strong>Emp ID</strong></th>
                                    <th class="text-center" width="20%"><strong>Distributor</strong></th>
                                    <th class="text-center" width="15%"><strong>Name</strong></th>
                                    <th class="text-center" width="10%"><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($searchResults)) {
                                    $i=1;
                                    foreach (@$searchResults as $row) 
                                    {
                                        ?>
                                    <tr>
                                        <td  class="text-center"><?php echo @$i++; ?></td>
                                        <td  class="text-center"><?php echo @$row['employee_id'] ?></td>
                                        <td class="text-center"><?php echo @$row['distributor_name']; ?></td>
                                        <td class="text-center" align='cneter'><?php echo @$row['first_name'].@$row['last_name']; ?></td>
                                        
                                        <td class="text-center">
                                           <a href="<?php echo SITE_URL; ?>product_opening_stock_details/<?php echo @icrm_encode($row['user_id']); ?>" style="padding:3px 3px;" title="Enter Stock"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-edit"></i></button></a>

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
                          <div class="pull-left"><?php echo @$pagermessage ; ?></div>
                            <div class="pull-right">
                                <div class="dataTables_paginate paging_bs_normal">
                                  <?php echo @$pagination_links; ?>
                                </div>
                            </div>
                        </div>
                        </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>


<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
