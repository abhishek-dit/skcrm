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
                                

                                    $r = getReporteeRoles(@$leadDetails['role_id']);
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
<div class="md-overlay"></div>