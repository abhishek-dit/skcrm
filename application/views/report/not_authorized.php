<?php $this->load->view('commons/main_template', $nestedView); ?>
<div class="cl-mcont">
    <div id="loaderID" style="position:fixed; top:50%; left:58%; z-index:2; opacity:0"><img src="<?php echo assets_url(); ?>images/ajax-loading-img.gif" /></div>
    <div class="row"> 
        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <div class="content"> 
                   <h1 align="center">You are not authorized to access this page</h1>
                </div>
            </div>
        </div>              
    </div>
</div>
<!-- <b style="color:red;">▼ -20</b> -->
<!-- <b style="color:green;">▲ 20</b> -->

<?php $this->load->view('commons/main_footer.php', $nestedView); ?>