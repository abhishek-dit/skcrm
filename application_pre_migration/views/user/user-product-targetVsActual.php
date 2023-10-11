<?php
	$this->load->view('commons/main_template',$nestedView); 
?>
	<div class="row"> 
		<div class="col-sm-12 col-md-12">
      		<div class="block-flat">
       			<div class="content">
       				<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>user_productTargetVsActual"  parsley-validate novalidate method="post">
	       				<input type="hidden" name="action" value="submit">
	       				<div class="row">
	       					<div class="col-sm-4">
	       						<select class="getUserProductReporteesWithUser" style="width:100%" name="user_id" onchange="this.form.submit()">
	       							<option value="<?php echo $user_id; ?>">
                                        <?php echo getUserDropDownDetails($user_id); ?>
                                    </option>
	       						</select>
	       					</div>
	       					<div class="col-sm-2">
	       						<select class="form-control"  name="month_year" onchange="this.form.submit()">
	       							<option value="0_<?php echo $yr1; ?>">Financial Year</option>
	       							<?php
	       								foreach ($months as $month) {
	       									$m_val = $month['month_id'].'_'.$month['year'];
	       									$m_label = $month['month'].' '.$month['year'];
	       									$selected = ($month['month_id']==$cur_month&&$month['year']==$cur_year)?'selected':'';
                                            if(($month['month_id']==$cur_month&&$month['year']==$cur_year)) $sel_month = $m_label;
	       									echo '<option value="'.$m_val.'" '.$selected.'>'.$m_label.'</option>';
	       								}
	       							?>
	       						</select>
	       					</div>
                            <div class="col-sm-6">
                                    <table class="table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Financial Year/Month</th>
                                                <th>Target Revenue (Rs)</th>
                                                <th>Reached - YTD/MTD (Rs)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $yr1; ?></td>
                                                <td align="center"><?php echo getTargetValue(userYearTargetRevenue($yr1, $user_id)); ?></td>
                                                <td align="center"><?php echo getTargetValueBar(userYearRevenueGenerated($yr1, $user_id, $role_id, $locationString), userYearTargetRevenue($yr1, $user_id)); ?></td>
                                            </tr>
                                            <?php if($cur_month != 0)
                                            {
                                                ?>
                                            <tr>
                                                <td><?php echo $sel_month; ?></td>   
                                                <td align="center"><?php echo getTargetValue(userTargetRevenue($cur_year, $cur_month, $user_id)); ?></td>
                                                <td align="center"><?php echo getTargetValue(userRevenueGenerated($cur_year, $cur_month, $user_id, $role_id, $locationString)); ?></td>
                                            </tr>
                                                <?php
                                            } 
                                            ?>
                                        </tbody>
                                    </table>
                            </div>
	          			</div>
	          			
	          			<div class="row">
                            <div class="col-sm-8">
                            </div>
                        </div>
          			</form>
          			<div class="row">
          				<div class="col-sm-12">
          					<div class="table-responsive">
                        <table class="table table-bordered hover">
                            <thead>
                                <tr>
                                    <th class="text-center"><strong>S.NO</strong></th>
                                    <th class="text-center"><strong>Product</strong></th>
                                    <th class="text-center"><strong>Target</strong></th>
                                    <th class="text-center"><strong>Completed</strong></th>
                                    <th class="text-center"><strong>Target Progress Bar</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sn=1;
                            if (count(@$user_products) > 0) {
                                foreach ($user_products as $product) {
                                    ?>
                                        <tr>
                                            <td class="text-center"  style="width:10%"><?php echo @$sn++; ?></td>
                                            <td style="width:40%">
                                            <?php echo @$product['name']; ?> - 
                                            (<?php echo substr(@$product['description'],0,250);?>)
                                            </td>
                                            <td class="text-center" style="width:10%">
                                            <?php
                                           $target_qty='Not Assigned';
                                                if(@$user_product_targerts[$product['product_id']]>0)
                                                $target_qty = @$user_product_targerts[$product['product_id']];
                                                echo $target_qty;
                                            ?>
                                            </td>
                                            <td class="text-center" style="width:10%">
                                            	<?php
                                            	$comleted_qty = userProductTarget($cur_year, $cur_month, $user_id, $role_id , $product['product_id'], $locationString);
                                            	echo $comleted_qty;
                                            	?>
                                            </td>
                                            <td>
                                            	<?php echo getTargetBar($target_qty,$comleted_qty);?>
                                            </td>
                                        </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>	<tr><td colspan="14" align="center"><span class="label label-primary">No Records</span></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
          				</div>
          			</div>

          			<br>
        		</div>
     	 	</div>
    	</div>
	</div>

<?php
	$this->load->view('commons/main_footer.php',$nestedView); 
?>
<script type="text/javascript">


$(document).ready(function(){
    select2Ajax('getUserProductReporteesWithUser', 'getUserProductReporteesWithUser', 0, 0)
});

</script>