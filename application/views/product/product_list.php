<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row ">
	<div class="col-md-12 col-sm-12 ">
		<div class="block-flat">
			<div class ="content">
            <div class="row no-gutter " >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>product_list">
                            <div class="col-sm-12">
                                
                                <label class="col-sm-2 control-label">Upload ID </label>
                                <div class="col-sm-2">
                                    <input type="text" name="upload_id" placeholder="Upload ID" maxlength="20"  value="<?php echo @$searchParams['upload_id']; ?>" id="upload_id" class="form-control">
                                </div>
                                 <div class="col-sm-3">
                                        <input type="text" required class="form-control" id="start_date" placeholder="Start Date" name="start_date" readonly  value="<?php echo @$searchParams['start_date']; ?>">
                                    </div>     
                                    <div class="col-sm-3">
                                            <input type="text" required class="form-control" id="end_date" placeholder="End Date" name="end_date" readonly  value="<?php echo @$searchParams['end_date']; ?>">
                                    </div>
                                     <div class="col-sm-2">
                                            <button type="submit" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                             <button type="submit" name="download" title="" class="btn btn-success" formaction="<?php echo SITE_URL; ?>download_product_list_bulk_upload" value="download"><i class="fa fa-cloud-download"></i> </button>
                                     </div>  
                             </div>
                        </form>
                    </div>
            <!-- <div class="row"> 
                --> <form >
                  <div class="table-responsive" style="padding-top:20px";>
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%"><strong>Sno</strong></th>
                                    <th class="text-center" width="10%"><strong>Upload ID</strong></th>
                                    <th class="text-center" width='15%'><strong>File</strong></th>
                                    <th class="text-center" width="30%"><strong>Uploaded By</strong></th>
                                    <th class="text-center" width="12%"><strong>UploadedTime </strong></th>
                                   <!--  <th class="text-center" width="12%"><strong>Actions</strong></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($searchResults)) {
                                    $j=1;
                                    foreach (@$searchResults as $row) 
                                    {
                                       ?>
                                        <tr>
                                            <td class="text-center"><?php echo $j++; ?></td>
                                            <td class="text-center"><?php echo $row['upload_id'];?></td>
                                            <td class="text-center"><?php echo @$row['file_name']; ?></td>
                                            <td class="text-center" align='center'><?php echo getUserName(@$row['created_by']); ?></td>
                                            <td class="text-center" align='center'><?php echo $row['created_time']; ?></td>
                                            <!-- <td  class="text-center">
                                               <a href="<?php echo SITE_URL; ?>download_product_bulk_upload_details/<?php echo @icrm_encode($row['upload_id']); ?>" style="padding:3px 3px;" title="Download"><button type='button' class="btn btn-primary" style="padding: 3px;" ><i class="fa fa-cloud-download"></i></button></a>

                                            </td> -->
                                            
                                        </tr>
                                        <?php }
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
            <!-- </div> -->
            </div>
        </div>
    </div>
</div>


<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
