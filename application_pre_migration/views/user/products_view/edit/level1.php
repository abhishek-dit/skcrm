<?php
$all_products_roles = array(11, //global user
							10, //Sales Director
							 9, // country head
							 7, // RBH
                            12  // Stockist
							 );
if(in_array($role_id,$all_products_roles))
{
	?>
    <div class="form-group no-padding">
        <label class="col-sm-4 control-label"><h4>All Products</h4></label>
    </div>
    <?php
}
else {
	?>
    <div class="form-group no-padding">
        <label class="col-sm-4 control-label"><h4>No Need of Assigning Products</h4></label>
    </div>
    <?php
}
?>