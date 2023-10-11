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
     <div class="col-sm-6 multiselectbox" id="countryblock">
     <?php
     if(isset($countries))
     {
        foreach (@$countries as $country) {
        $ccheked = (array_key_exists($country['location_id'],$user_locations))?'checked':'';
        echo '<div class="radio"><label> <input type="checkbox" name="country[]" '.$ccheked.' value="'.$country['location_id'].'" class="icheck1"> '.$country['location'].'</label></div>';
        }
     }
      
     ?>
     </div>
</div>