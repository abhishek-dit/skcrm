<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>

<div class="row"> 
	<div class="col-sm-12 col-md-12">
		<div class="block-flat">
			<div class="content">
				<div class="row no-gutter">
					<form class="form-horizontal" role="form" action="<?php echo SITE_URL; ?>submit_settings" method="post">
            <div class="row">
              <?php 
              foreach($preference_list as $row)
              {
                ?>
                <div class=" col-sm-6">
                  <h3><?php echo $row['name'];?></h3>
                </div>
                <?php
                foreach($row['preference_list'] as $preference_data)
                {
                  ?>
                  <div class="form-group">
                    <div class="col-md-offset-1 col-sm-12">
                      <label class="col-sm-3 control-label"><?php echo $preference_data['lable'];?></label>
                      <div class="col-sm-2">
                        <?php 
                        if($preference_data['type']==1)
                        {
                          ?>
                          <input type="number" name="preference_name[<?php echo $preference_data['preference_id']?>]" value="<?php echo $preference_data['value'];?>" class="form-control"><?php
                        }
                        if($preference_data['type']==2)
                        {
                          if($preference_data['value']==1)
                          {
                                $checked='checked';
                                $status='Enabled';
                          }
                          else
                          {
                                $checked='';
                                $status='Disabled';
                          }
                          ?>
                          <input type="checkbox" name="preference_checkbox[<?php echo $preference_data['preference_id']?>]" value="1" <?php echo $checked;?> class="icheck1" style="margin-top: 15px;"><small> <?php echo $status;?></small><?php  
                        }
                        if($preference_data['type']==3)
                        {
                          ?>
                          <input type="text" name="preference_name[<?php echo $preference_data['preference_id']?>]" value="<?php echo $preference_data['value'];?>" class="form-control"><?php
                        }
                        ?>
                      </div> 
                    </div>
                  </div> <?php
                }
              }?>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-4 col-sm-8">
                <button class="btn btn-primary" type="submit"  value="button"><i class="fa fa-check"></i> Save Changes</button>
                <a class="btn btn-danger" href="<?php echo SITE_URL;?>"><i class="fa fa-times"></i> Cancel</a>
              </div>
            </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>