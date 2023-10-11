<div class="form-group">
     <label class="col-sm-3 control-label">GEO</label>
     <div class="col-sm-6">
         <select class="form-control load_iCheck" name="geo" id="geo">
             <option value="">select</option>
             <?php
                 if($geos) {
                     foreach($geos as $geo) {
                         
                         echo '<option value="'.$geo['location_id'].'">'.$geo['location'].'</option>';
                     }
                 }
                 ?>
         </select>
     </div>
</div>
<div class="form-group country-group hidden">
     <label class="col-sm-3 control-label">Country</label>
     <div class="col-sm-6">
		<select name="country" class="form-control" id="country">
        	<option value="">Select</option>
        </select>
     </div>
</div>
<div class="form-group region-group hidden">
     <label class="col-sm-3 control-label">Region</label>
     <div class="col-sm-6">
		<select name="region" class="form-control" id="region">
        	<option value="">Select</option>
        </select>
     </div>
</div>