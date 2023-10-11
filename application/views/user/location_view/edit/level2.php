<div class="form-group">
    <label class="col-sm-3 control-label">GEO</label>
    <div class="col-sm-9">
    <?php
	if($geos) {
		foreach($geos as $geo) {
			 $gcheked = (array_key_exists($geo['location_id'],$user_locations))?'checked':'';
			echo '<label class="checkbox-inline"> <input type="checkbox" '.$gcheked.' value="'.$geo['location_id'].'" name="geo[]" class="icheck1"> '.$geo['location'].'</label>';
		}
	}
	?>
    </div>
</div>