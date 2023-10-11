<div class="form-group">
     <label class="col-sm-3 control-label">GEO</label>
     <div class="col-sm-6">
         <select class="form-control load_iCheck" name="geo" id="geo5">
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
		<select name="country" class="form-control" id="country5">
        	<option value="">Select</option>
        </select>
     </div>
</div>
<div class="form-group region-group hidden">
     <label class="col-sm-3 control-label">Region</label>
     <div class="col-sm-6">
		<select name="region" class="form-control" id="region5">
        	<option value="">Select</option>
        </select>
     </div>
</div>
<div class="form-group state-group hidden">
     <label class="col-sm-3 control-label">State</label>
     <div class="col-sm-6 multiselectbox" id="state5">
		
     </div>
</div>
<div class="form-group district-group hidden">
     <label class="col-sm-3 control-label">District</label>
     <div class="col-sm-6 multiselectbox" id="district5">
		
     </div>
</div>
<div class="form-group city-group hidden">
     <label class="col-sm-3 control-label">City</label>
     <div class="col-sm-6 multiselectbox" id="city5">
		
     </div>
</div>