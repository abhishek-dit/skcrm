<?php

//echo '<pre>';print_r($cur_states);echo '</pre>';
//print_r($cur_districts);
/*print_r($cur_geo);*/

$district_hidden_cls = ($level_depth>=5)?'':'hidden';
$city_hidden_cls = ($level_depth>=6)?'':'hidden';
?>
<div class="form-group">
     <label class="col-sm-3 control-label">GEO</label>
     <div class="col-sm-6">
         <select class="form-control load_iCheck" name="geo" id="geo5">
             <option value="">select</option>
             <?php
                 if($geos) {
                     foreach($geos as $geo) {
                         $gselected = ($geo['location_id']==$cur_geo['location_id'])?'selected':'';
                         echo '<option value="'.$geo['location_id'].'" '.$gselected.'>'.$geo['location'].'</option>';
                     }
                 }
                 ?>
         </select>
     </div>
</div>
<div class="form-group country-group">
     <label class="col-sm-3 control-label">Country</label>
     <div class="col-sm-6">
		<select name="country" class="form-control" id="country5">
        	<option value="">Select</option>
            <?php
            foreach ($countries as $country) {
                $cselected = ($country['location_id']==$cur_country['location_id'])?'selected':'';
                echo '<option value="'.$country['location_id'].'" '.$cselected.'>'.$country['location'].'</option>';
            }
            ?>
        </select>
     </div>
</div>
<div class="form-group region-group">
     <label class="col-sm-3 control-label">Region</label>
     <div class="col-sm-6">
		<select name="region" class="form-control" id="region5">
        	<option value="">Select</option>
            <?php
            foreach ($regions as $region) {
                $rselected = ($region['location_id']==$cur_region['location_id'])?'selected':'';
                echo '<option value="'.$region['location_id'].'" '.$rselected.'>'.$region['location'].'</option>';
            }
            ?>
        </select>
     </div>
</div>
<div class="form-group state-group">
     <label class="col-sm-3 control-label">State</label>
     <div class="col-sm-6 multiselectbox" id="state5">
		<?php
        if(count($states)>0){
            foreach ($states as $state) {
                $schecked = (array_key_exists($state['location_id'],$cur_states))?'checked':'';
                echo '<div class="radio"><label> <input type="checkbox" name="state[]" '.$schecked.' value="'.$state['location_id'].'" class="icheck1 state_cb"> '.$state['location'].'</label></div>';
            }
        }
        ?>
     </div>
</div>
<div class="form-group district-group <?php echo $district_hidden_cls;?>">
     <label class="col-sm-3 control-label">District</label>
     <div class="col-sm-6 multiselectbox" id="district5">
		<?php
            if($level_depth>=5){
                if(count($cur_states)>0){
                    foreach ($cur_states as $state) {
                        echo '<h5>'.$state['location_name'].'</h5>';
                        $districts = getLocationsByParent($state['location_id']);
                        if(count($districts)>0){
                            foreach ($districts as $district) {
                                    
                                $dchecked = (array_key_exists($district['location_id'],$state['childs'])||array_key_exists($district['location_id'],$cur_districts))?'checked':'';
                                echo '<div class="radio"><label> <input type="checkbox" name="district[]" '.$dchecked.' value="'.$district['location_id'].'" class="icheck1 district_cb"> '.$district['location'].'</label></div>';
                                echo '<input type="hidden" value="'.$state['location_id'].'" name="district_parent['.$district['location_id'].']">';
                            }
                        }
                    }
                }
            }
        ?>
     </div>
</div>
<div class="form-group city-group <?php echo $city_hidden_cls;?>">
     <label class="col-sm-3 control-label">City</label>
     <div class="col-sm-6 multiselectbox" id="city5">
		<?php
            if($level_depth>=6){
                if(count($cur_states)>0){
                    foreach ($cur_states as $state) {
                        if(count($state['childs'])>0){
                            echo '<h5>'.$state['location_name'].'</h5>';
                            $districts = getLocationsByParent($state['location_id']);
                            if(count($districts)>0){
                                foreach ($districts as $district) {
                                    if(array_key_exists($district['location_id'], $state['childs'])||array_key_exists($district['location_id'],$cur_districts)) {
                                    echo '<h6>'.$district['location'].'</h6>';
                                    $cities = getLocationsByParent($district['location_id']);
                                    if(count($cities)>0){
                                        foreach ($cities as $city) {
                                            if(is_array(@$state['childs'][$district['location_id']]['childs']))
                                            $cchecked = (array_key_exists($city['location_id'],@$state['childs'][$district['location_id']]['childs']))?'checked':'';
                                            else
                                                $cchecked = '';
                                            echo '<div class="radio"><label> <input type="checkbox" name="city[]" '.$cchecked.' value="'.$city['location_id'].'" class="icheck1 city_cb"> '.$city['location'].'</label></div>';
                                            echo '<input type="hidden" value="'.$district['location_id'].'" name="city_parent['.$city['location_id'].']">';
                                        }
                                    }
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