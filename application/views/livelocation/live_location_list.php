<?php
$this->load->view('commons/main_template', $nestedView);
echo $this->session->flashdata('response'); 
?>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
        overflow:visible;

        position: relative;
    /* overflow: hidden; */
        margin-top: 55px;
}
      }
      /* Optional: Makes the sample page fill the window. */
      .live {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
<div class="row"> 
  <div class="col-sm-12 col-md-12">
    <div class="block-flat">
        <div class="content">
          <div class="content">
            <div class="row no-gutter" >
               
              <div class="col-sm-12">
                  
                  <label class="col-sm-1 control-label">Users</label>
                  <div class="col-sm-3">
                      <select name="user_id" class="select2 user_id" id="region" required>
                      <option value="">Select User</option>
                      <?php
                      foreach($users as $row)
                      {
                        ?>
                        <option value="<?php echo $row['user_id'] ?>" "><?php echo $row['first_name'].'('.$row['employee_id'].')' ?></option> <?php
                      }
                      ?>
                      </select>
                  </div>
                  <div class="col-sm-6">
                    <label class="col-sm-3 control-label">Date Range </label>
                    <div class="col-sm-9">
                        <div class="input-group input-large date-picker input-daterange"  data-date-format="mm/dd/yyyy">
                              <input type="text" class="form-control sub_from_date" name="from_date" id="min_date" placeholder="From Date" autocomplete="off">
                              <span class="input-group-addon"> to </span>
                              <input type="text" class="form-control sub_to_date" name="to_date" id="max_date" placeholder="To Date" autocomplete="off"> 
                          </div>
                    </div>
                  </div>
                  <div class="col-sm-2">
                        <button  name="searchApprveQuote" value="1" class="btn btn-success search_location"><i class="fa fa-search"></i></button>
                       <a href="<?php echo SITE_URL.'live_location_list'?>" class="btn btn-success"><i class="fa fa-refresh"></i></a>
                  </div>
                  
              </div>
    
              <div class="live" style="height:calc(100vh)">
                <div id="map"></div>
              </div>
            </div>
        </div>
    </div>              
  </div>
</div>
<?php $this->load->view('commons/main_footer.php', $nestedView); ?>

<script>
var markers = [];
var flightPath;
var map;

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 5,
    center: {lat: 16.59737200, lng: 80.62412300}
  });
  //drawPolylines()
  map.addListener('click', function(event) {
    var center = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng() );
    map.setCenter(center);
    map.panTo(center);
    map.setZoom(8);
  });
}

function drawPolylines(res){
  var ASSET_URL = '<?php echo assets_url()?>';
  initMap();
  var data = JSON.parse(res);
  var markers = [];
    
  for(let i=0;i < data.length; i++)
  {
    console.log('00000',data[i]);
    var center = new google.maps.LatLng(data[i].latitude, data[i].longitude );
    markers.push(center);
    if(i==0){
      let marker = new google.maps.Marker({
        position: center,
        map: map,
        icon:  ASSET_URL+'images/online.svg',
        title: 'start'
      });
    }
    else if(i == data.length-1){
      let marker = new google.maps.Marker({
        position: center,
        map: map,
        icon: ASSET_URL+'images/offline.svg',
        title: 'end'
      });
    }
    else{
      let marker = new google.maps.Marker({
        position: center,
        icon: ASSET_URL+'images/punchIn.svg',
        map: map,
        title: 'visited place'
      });

    }
  }

var bounds = new google.maps.LatLngBounds();
bounds.extend(markers[0] );
bounds.extend(markers[markers.length -1] );
map.fitBounds(bounds);


  flightPath = new google.maps.Polyline({
    path: markers,
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });
  flightPath.setMap(map);





}
</script>

<script type="text/javascript">
  var now = new Date();
  var firstDayPrevMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);

  $("#min_date").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
    maxDate:now,
    minDate:firstDayPrevMonth,
    onSelect: function (date) {
      var date2 = $('#min_date').datepicker('getDate');
      $('#max_date').datepicker('option', 'minDate', date2);
    }
  });

  $("#max_date").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
    maxDate:now,
    onSelect: function (date) {
      var date2 = $('#max_date').datepicker('getDate');
      $('#min_date').datepicker('option', 'maxDate', date2);
    }
  });

  $(document).on("click",".search_location",function(e) { 
    var markers = [];
    var user_id = $('.user_id').val();
    var from_date = $('.sub_from_date').val();
    var to_date = $('.sub_to_date').val();
    if(from_date=='')
    {
      from_date = null;
    }
    if(to_date=='')
    {
      to_date = null;
    }
    
    if(user_id!='')
    {
      var data = 'user_id=' + user_id + '&from_date=' + from_date + '&to_date=' + to_date;
      $.ajax({
        type: "POST",
        url: SITE_URL + 'fetch_live_location',
        data:data,
        cache: false,
        success: function(data){
          let data1 =  JSON.parse(data)
          if(data1.length===0)
          {
            alert("No Records Found")
          }
          else
          {
            drawPolylines(data);
          }
        }
      });
      e.preventDefault();
    }
    else
    {
      alert("Please Select User");
      location.reload(true);
    }
  });
</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc&callback=initMap"></script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=initMap"></script> -->