<?php
$this->load->view('commons/header2', $nestedView);

?>

<div class="row"> 
    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
            <div class="content">
                <?php echo $this->session->flashdata('response'); ?>
            </div>
        </div>              
    </div>
</div>
<?php $this->load->view('commons/footer2.php', $nestedView); ?>