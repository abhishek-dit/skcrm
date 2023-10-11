<?php $this->load->view('commons/main_template', $nestedView); ?>

<?php
if (@$displayList == 1) {
    ?>

    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <table class="table table-bordered"></table>
                <div class="content">
                    <div class="row no-gutter" >
                        <form role="form" class="form-horizontal" method="post" action="<?php echo SITE_URL; ?>viewCampaignDocuments">
                            <div class="col-sm-12">
                                
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-2">
                                    <input type="text" name="campaignDocumentName" placeholder="Document Name" maxlength="100"  value="<?php echo @$searchParams['campaignDocumentName']; ?>" id="companyName" class="form-control">
                                </div>
                             <div class="col-sm-2">
                                    <button type="submit" name="searchCampaignDocument" title="Search" value="1" class="btn btn-success"><i class="fa fa-search"></i> </button>
                             </div>  
                                
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
                                    <th class="text-center"><strong>Document Name</strong></th>
                                    <th class="text-center"><strong>Description </strong></th>
                                    <th class="text-center"><strong>Attachment</strong></th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //@$inc = $start + 1;
                                if (@$count > 0) {
                                    foreach ($campaignDocumentSearch as $row) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo @$sn++; //@$sn      ?></td>
                                            <td class="text-center"><?php echo @$row['name']; ?></td>
                                            <td class="text-center"><?php echo @$row['description']; ?></td>
                                            <td class="text-center"> <a target="_blank"  href="<?php echo SITE_URL1; ?>uploads/campaign_documents/<?php echo @$row['path'];?>"  style="padding:3px 3px;" class="btn btn-default"><i class="fa fa-paperclip" aria-hidden="true"></i>
</a></td>
                                            

                                           
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>  <tr><td colspan="8" align="center"><span class="label label-primary">No Records</span></td></tr>
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
    <?php } ?>
    <?php $this->load->view('commons/main_footer.php', $nestedView); ?>
    <script>
        $(document).on("change", ".category_id", function () {
            var old_this = $(this);
            $.ajax({
                type: "POST",
                url: "<?php echo SITE_URL; ?>getSubCategory",
                data: 'cat_id=' + $(this).val(),
                beforeSend: function () {
                    //$(this).css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    //alert(id);
                    // $(id_obj).parent('form').find(".category_sub_id").html(data);
                    $(old_this).parents('form').find('.category_sub_id').html(data);
                    ;
                    // alert(name1);
                }
            });

        });
    </script>
    <style>
        .row.no-gutter {
            margin-left: 5px;
            margin-right: 5px;
        }

        .row.no-gutter [class*='col-']:not(:first-child),
        .row.no-gutter [class*='col-']:not(:last-child) {
            padding-right: 5px;
            padding-left: 5px;
        }
        .row.no-gutter lable{}
    </style>
    <script>
    $("#campaign_date").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
           
        }); 
    </script>
    <?php
       // echo '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/ckeditor.js"></script>';
    //echo '<script type="text/javascript" src="' . assets_url() . 'js/ckeditor/adapters/jquery.js"></script>';
        ?>