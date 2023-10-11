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
     <div class="col-sm-6 multiselectbox" id="countryblock">
<!--        		<div class="radio"> 
                    <label> <input type="checkbox" checked="" name="check1" class="icheck"> Option 1</label> 
                  </div>
                  <div class="radio"> 
                    <label> <input type="checkbox" name="check2" class="icheck"> Option 2</label> 
                  </div>
                  <div class="radio"> 
                    <label> <input type="checkbox" name="check3" class="icheck"> Option 3</label> 
                  </div>-->
     </div>
</div>