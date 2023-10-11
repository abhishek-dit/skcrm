<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
<div class="row ">
	<div class="col-md-12 col-sm-12 ">
		<div class="block-flat">
			<div class ="content">
            <div class="row no-gutter " >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>get_new_outstanding_report">
                            <div class="col-sm-12">
                                <div class="col-sm-2">
                                   <select  class="form-control select2 "  placeholder="Select Region" name="region_id" >
                                        <option value=''>Select Region</option>
                                        <?php  
                                        foreach($regions as $reg)
                                        {  
                                            $selected = '';
                                            $selected=($reg['location_id']==@$searchParams['region_id'])?'selected':'';
                                            echo '<option value="'.$reg['location_id'].'"'.$selected.'>'.$reg['location'].'</option>';

                                        } ?>
                                        </select>
                                </div>
                                 <div class="col-sm-2">
                                       <select  class="form-control select2"  placeholder="Select Month" name="month_id" >
                                       <!--  <option value=''>Select Month</option> -->
                                        <?php  
                                        foreach($months as $mon)
                                        {  
                                            $selected = '';
                                            $selected=($mon['month_id']==@$searchParams['month_id'])?'selected':'';
                                            echo '<option value="'.$mon['month_id'].'"'.$selected.'>'.$mon['month'].'</option>';

                                        } ?>
                                        </select>
                                    </div>     
                                    <div class="col-sm-2">
                                        <select  class="form-control select2"  placeholder="Select Year" name="year_id" >
                                           <!--  <option value=''>Select Year</option> -->
                                        <?php  
                                        for($i=2016;$i<=date('Y');$i++)
                                        {  
                                            $selected = '';
                                            $selected=($i==@$searchParams['year_id'])?'selected':'';
                                            echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';

                                        } ?>
                                        </select>
                                    </div>
                                     <div class="col-sm-3">
                                            <button type="submit" name="search" value="1" class="btn btn-success"><i class="fa fa-search"></i></button>
                                             <button type="submit" name="download" class="btn btn-success" formaction="<?php echo SITE_URL; ?>download_new_so_report" title="Download" value="download"><i class="fa fa-cloud-download"></i> </button>
                                             <a href="<?php echo SITE_URL; ?>get_new_outstanding_report" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>
                                           <!--  <a href="<?php echo SITE_URL.'new_so_amount_upload'?>" class="btn btn-success" title="Back"><i class="fa fa-reply"></i></a> -->
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
                                    <th class="text-center" width="10%"><strong>Region</strong></th>
                                    <th class="text-center" width='15%'><strong>Outstanding Amount (L)</strong></th>
                                    <th class="text-center" width="30%"><strong>Collections Planned for the Month (L)</strong></th>
                                    <th class="text-center" width="12%"><strong>MTD Collections (L) </strong></th>
                                </tr>
                            </thead>
                             <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (!empty($outstanding_results)) {
                                    $j=1;
                                    foreach (@$outstanding_results as $row) 
                                    {
                                       ?>
                                        <tr>
                                            <td class="text-center"><?php echo $j++; ?></td>
                                            <td class="text-center"><?php echo $row['location'];?></td>
                                            <td class="text-center"><?php echo @$row['ot_amount']; ?></td>
                                            <td class="text-center" align='center'><?php echo @$row['collections_planned']; ?></td>
                                            <td class="text-center" align='center'><?php echo $row['actual_collections']; ?></td>
                                         </tr>
                                        <?php }
                                } else {
                                    ?>	<tr><td colspan="5" align="center"><span class="label label-primary">No Records</span></td></tr>
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
