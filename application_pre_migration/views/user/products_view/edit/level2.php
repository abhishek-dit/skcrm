<?php
//echo '<pre>';print_r($product_groups); echo '</pre>';
//echo '<pre>';print_r($user_products); echo '</pre>';
?>
<div class="form-group">
    <label class="col-sm-3 control-label">Product Category</label>
    <div class="col-sm-9 multiselectbox">
    	<div class="radio"><label> <input type="checkbox" id="chkAllProdCat" value="1" name="chkAllProdCat" class="icheck11"> All</label></div>
    <?php
	if($all_productCategories) {
		foreach($all_productCategories as $productCategory) {
            $cchecked = (array_key_exists($productCategory['category_id'], $user_products))?'checked':'';
			echo '<div class="radio"><label> <input type="checkbox" '.$cchecked.' value="'.$productCategory['category_id'].'" name="productCategory" class="icheck11 productCategory"> '.$productCategory['name'].'</label></div>';
		}
	}
	?>
    </div>
</div>
<div class="form-group product-group-loading hidden"><label class="col-sm-3"></label><label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading product groups</label></div>
<div class="form-group product-group-box">
     <label class="col-sm-3 control-label">Product Group</label>
     
     <div class="col-sm-6 multiselectbox" id="product_group">
		<?php
            if(count($user_products)>0){
                foreach ($user_products as $category) {
                    echo '<h5><input type="checkbox" name="chkAllPG" value="1" id="prodCat'.$category['category_id'].'" checked class="icheck1 chkAllPG"> '.$category['name'].'</h5>';
                    if(count($category['childs'])>0){
                        foreach ($category['childs'] as $productGroup) {
                            $gchecked = (in_array($productGroup['group_id'], $product_groups))?'checked':'';
                            echo '<div class="radio"><label> <input type="checkbox" '.$gchecked.' name="productGroup[]" value="'.$productGroup['group_id'].'" class="icheck1 productGroup prodCat'.$category['category_id'].'"> '.$productGroup['name'].'</label></div>';
                        }
                    }
                }
            }
        ?>
     </div>
</div>
<div class="form-group product-loading hidden"><label class="col-sm-3"></label><label class="col-sm-4"><img src="<?php echo assets_url()?>images/loaders/loader1.gif"> loading products</label></div>
<div class="form-group product-box">
     <label class="col-sm-3 control-label">Products</label>
     <div class="col-sm-6 multiselectbox" id="product">
		<?php
            if(count($user_products)>0){
                foreach ($user_products as $category) {
                    echo '<h5><input type="checkbox" name="chkAllPG" value="1" id="prodCat'.$category['category_id'].'" checked class="icheck1 chkAllPG"> '.$category['name'].'</h5>';
                    if(count($category['childs'])>0){
                        foreach ($category['childs'] as $productGroup) {
                           if(in_array($productGroup['group_id'], $product_groups)){
                                echo '<h6><input type="checkbox" name="chkAllProd" value="1" id="prodGroup'.$productGroup['group_id'].'" checked class="icheck1 chkAllProd"> '.$productGroup['name'].'</h6>';
                                if(count($category['childs'][$productGroup['group_id']]['childs'])>0){
                                    foreach ($category['childs'][$productGroup['group_id']]['childs'] as $product) {
                                        $pchecked = (array_key_exists($product['product_id'], $products))?'checked':'';
                                        echo '<div class="radio"><label> <input type="checkbox" '.$pchecked.' name="product[]" value="'.$product['product_id'].'" checked class="icheck1 product pr prodGroup'.$productGroup['group_id'].' prodCat'.$category['category_id'].'"> '.$product['name'].'</label></div>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        ?>
     </div>
</div>