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
                $rselected = (array_key_exists($region['location_id'],$user_locations))?'selected':'';
                echo '<option value="'.$region['location_id'].'" '.$rselected.'>'.$region['location'].'</option>';
            }
            ?>
        </select>
     </div>
</div>