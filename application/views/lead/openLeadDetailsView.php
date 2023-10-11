<?php $this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
$encode_lead_id = @icrm_encode($lead_id);

$user_id = @$leadDetails['user_id'];
$leadStatus = @$leadDetails['status'];
//$checkUser = (($user_id == $this->session->userdata('user_id')) && $leadStatus != 19)?1:0;
$logged_in_role = $this->session->userdata('role_id');
// If logged user is RBH OR Super User or lead owner
$checkUser = (($logged_in_role==7) || ($this->session->userdata('s_role_id')==13) || ($user_id == $this->session->userdata('user_id')) )?1:0;
$roleUser = @$leadDetails['role_id'];
if($roleUser == '') $roleUser = 4;
$checkRole = ($roleUser == 5)? 1: 0;
$secondUser = ($checkRole == 1)?'Sales Engineer':'Distributor';
$hide = ($checkRole == 1)?'':'hidden';
$req = ($checkRole == 1)?'required':'';
$chide = ($leadDetails['source_id'] != 2)?'hidden':'';
$rhide = ($leadDetails['source_id'] != 3)?'hidden':'';
$colhide = ($leadDetails['source_id'] != 8)?'hidden':'';
$reporting = 0;
$edit = ($leadDetails['user_id']==$this->session->userdata('user_id')&& $leadDetails['status']!=19)?1:0;
$formAction = ($checkPage == 1)?'updateLead':'';


?>

<div class="row wizard-row">
	<div class="col-md-12 fuelux">
		<div class="block-wizard">
			<div id="wizard1" class="wizard wizard-ux">
				<?php include_once('train.php'); ?>
			</div>
			<div class="step-content">
				<form class="form-horizontal" method="post" action="<?php echo SITE_URL?>planVisit">
					<div class="form-group no-padding">
						<div class="col-sm-5">
							<span style="font-size:18px">Lead Details</span>
						</div>
							<div class="col-sm-7" align="right">
								<button type="button" class="btn btn-primary btn-flat md-trigger" title="Order of Events" data-toggle="modal" data-target="#events"><i class="fa fa-info"></i> Details</button>
						<?php if($checkUser && $checkPage) { ?>
								<input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>">
							<?php 

                            if($checkUser && $logged_in_role != 4 && $logged_in_role != 5)
                             { ?>
								<button type="button" class="btn btn-primary btn-flat md-trigger" title="Re-route Lead" data-toggle="modal" data-target="#reroute"><i class="fa fa-mail-forward (alias)"></i> Re-route</button>
								<?php } ?>
							<?php if(@$leadDetails['visit_requirement'] == 1 && $leadStatus != 1) { ?>
								<button class="btn btn-primary" type="submit" name="visit" value="1"><i class="fa  fa-calendar"></i> Block Visit</button>
							<?php } ?>
							<?php if($leadStatus == 1 || $leadStatus == 2 || $leadStatus == 4) { ?>
								<button class="btn btn-danger" type="submit"  formaction="<?php echo SITE_URL; ?>dropLead" name="dropLead" value="1"><i class="fa  fa-trash-o"></i> Drop Lead</button>
							<?php } ?>
							<?php if($leadStatus == 5 || $leadStatus == 10) { ?>
								<button class="btn btn-danger" type="submit"  formaction="<?php echo SITE_URL; ?>closeLead" name="closeLead" value="1" onclick="return confirm('Are you sure you want to close the Lead ?')"><i class="fa  fa-times"></i> Close Lead</button>
							<?php } ?>
						<?php } ?>	
						</div>
					</div>
				</form>	
				<form class="form-horizontal lead_form" id="leadDetailsForm" method="post" action="<?php echo SITE_URL.$formAction?>" data-parsley-validate novalidate>

					<?php include_once('leadDetails.php'); ?>
					<?php if($edit==1) { ?>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button class="btn btn-primary" type="submit" name="updateLead" value="1"><i class="fa fa-check"></i> Update </button>
							<a href="<?php echo SITE_URL;?>openLeads"><button type="button" class="btn btn-danger" ><i class="fa fa-times"></i> Cancel</button></a>
						</div>
					</div>
					<?php } ?>
				</form>	
			</div>
		</div>
	</div>
</div>				

<?php include_once('leadCustomerContact.php'); ?>

<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id; ?>">-->
<div class="modal fade colored-header" id="reroute" role="dialog">
    <form action="<?php echo SITE_URL; ?>re_route_user" method="post" novalidate="" parsley-validate="" class="form-horizontal" id='quote_revision_frm'>
    <div class="modal-dialog">
        <div class="md-content">
            <div class="modal-header">
                <span style="font-size:18px">Lead Re-route</span>
                <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body form">
                
                <br><br><br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Reroute to</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="reroute_lead" value="<?php echo @$leadDetails['lead_id']; ?>">
                                    <input type="hidden" name="lead_user_id" value="<?php echo @$leadDetails['user_id']; ?>">
                                    <?php 
                                
                                    $r = getReporteeRoles(@$this->session->userdata('role_id'));
                                    //$r = getReporteeRoles(@$leadDetails['role_id']);
                                    //$this->ajax_model->getReportees(@$leadDetails['location_id'], $r);
                                    ?>
                                    <select required class="select2" style="width:100%" name="re_route_to">
                                        <?php $this->ajax_model->getReportees(@$leadDetails['location_id'], $r); ?>
                                    </select>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>	
            <div class="modal-footer">
                <span class="opp_error error" style="color:red;float:left;font-weight:bold;"></span>
                <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
                <button type="submit" name="submitReRoute" value="1" class="btn btn-primary btn-flat">Submit</button>
            </div>
        </div>	
    </div>	
        </form>
</div>

<!-- Modal -->
							  <div class="modal fade" id="mod-warning" tabindex="-1" role="dialog">
								<div class="modal-dialog" style="width:650px;">
								  <div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									</div>
									<div class="modal-body" style="height:auto;overflow-y:auto;">
										<div class="text-center">
											<div class="i-circle warning"><i class="fa fa-warning"></i></div>
											<h4>Warning!</h4>

											<?php
											if(@$lead_row['site_readiness_id']==NULL && @$lead_row['relationship_id']==NULL){

												echo '<p>Please update Site Readiness, Rapport with the customer fields to create new opportunity</p>';
											}
											else if(@$lead_row['site_readiness_id']==NULL){
												echo '<p>Please update Site Readiness field to create new opportunity</p>';
											}
											else if(@$lead_row['relationship_id']==NULL){
												echo '<p>Please update Rapport with the customer field to create new opportunity</p>';
											}
											?>
											
										</div>
									</div>
									<div class="modal-footer">
							
									  <button type="button" class="btn btn-warning" data-dismiss="modal">OK</button>
									</div>
								  </div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							  </div><!-- /.modal -->
<div class="md-overlay"></div>




<!-- Modal for Chronological sequence of events in the Lead -->

<!--<div class="md-modal colored-header custom-width md-effect-9" id="<?php //echo @$pop_id; ?>">-->
<div class="modal fade colored-header" id="events" role="dialog">
    <div class="modal-dialog">
        <div class="md-content">
            <div class="modal-header">
                <span style="font-size:18px">Chronological Sequence of Events</span>
                <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body form">
                
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <div class="form-group">
                            	<table class="table-bordered table">
                            		<thead>
                            			<tr>
                            				<th>S No</th>
                            				<th>Event</th>
                            				<th>Date</th>
                            				<th>End Date</th>
                            				<th>User</th>
                            				<th>Remarks</th>
                            			</tr>
                            		</thead>
                            		<tbody>
                            			<?php
                            				$i = 1; 
                            				$res = getLeadEvents($lead_id);
                            				foreach($res as $row){ ?>
                            				<tr>
                            					<td><?php echo $i++; ?></td>
                            					<td><?php echo getLeadStatus1($row['status']); ?></td>
                            					<td><?php echo $row['created_date']; ?></td>
                            					<td><?php echo $row['end_date']; ?></td>
                            					<td><?php echo $row['created_user']; ?></td>
                            					<td><?php echo $row['remarks']; ?><?php ?></td>
                            				</tr>
                            			<?php } ?>
                            		</tbody>
                            	</table>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>	
            <div class="modal-footer">
                <span class="opp_error error" style="color:red;float:left;font-weight:bold;"></span>
                <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Ok</button>
            </div>
        </div>	
    </div>	
        </form>
</div>

<!-- Modal -->



<?php 
$this->load->view('commons/main_footer.php', $nestedView); ?>
