<div class="form-group">
    <label class="col-sm-3 control-label">Product Category</label>
    <div class="col-sm-9 multiselectbox">
    	<div class="radio"><label> <input type="checkbox" id="chkAllProdCat" value="1" name="chkAllProdCat" class="icheck1"> All</label></div>
    <?php
	if($productCategories) {
		foreach($productCategories as $productCategory) {
			echo '<div class="radio"><label> <input type="checkbox" value="'.$productCategory['category_id'].'" name="productCategory" class="icheck1 productCategory"> '.$productCategory['name'].'</label></div>';
		}
	}
	?>
    </div>
</div>
<div class="form-group product-group-loading hidden"><label class="col-sm-3"></label><label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading product groups</label></div>
<div class="form-group product-group-box hidden">
     <label class="col-sm-3 control-label">Product Group</label>
     
     <div class="col-sm-6 multiselectbox" id="product_group">
		
     </div>
</div>
<div class="form-group product-loading hidden"><label class="col-sm-3"></label><label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading products</label></div>
<div class="form-group product-box hidden">
<input type="hidden" name="product" class="prod_hidd">
     <label class="col-sm-3 control-label">Products</label>
     <div class="col-sm-6 multiselectbox" id="product">
		
     </div>
</div>