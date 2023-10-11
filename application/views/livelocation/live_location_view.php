<?php
$this->load->view('commons/main_template', $nestedView);
?>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      .live {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      div.info p {
    color: white;
    font-size: 10px;
    line-height: 0px;
    font-weight: 600;
    margin-top: 4px;
    letter-spacing: 1px;
    padding-top: 10px;
    padding-bottom: 2px;
    white-space: normal;
    text-align: center;

}
#map-canvas {
    margin: 0;
    padding: 0;
    height: 400px;
    max-width: none;
}
#map-canvas img {
    max-width: none !important;
}

.gm-style .gm-style-iw-c {
  position: absolute;
    box-sizing: border-box;
    overflow: hidden;
    top: 0;
    /* font-size: 6em; */
    padding:0px!important;
    left: 0;
    transform: translate(-50%,-100%);
    background-color: #363636;
    border-radius: 1px;

    box-shadow: 0 2px 7px 1px rgba(0,0,0,0.3);
}


.gm-style-iw{
  border-radius: 16px!important;


}
.gm-style-iw gm-style-iw-c{

  padding-right: 0px!important;
    padding-bottom: 5px!important;
    max-width: 0px;
    max-height: 0px;
    border-radius: 16px!important;
}
.gm-style-iw {
    width:70px!important;
    top: 8px !important;
    left: 0px !important;
    background-color: #000;
    box-shadow: 0 1px 6px rgba(178, 178, 178, 0.6);
    border-radius: 2px 2px 10px 10px;
}

.gm-style-iw-d{
  overflow:hidden!important;
         max-width: 508px;
    max-height: 697px;

}

.gm-ui-hover-effect{
  text-transform: none;
    display: none!important;
}
/* button, select {
    text-transform: none;
    display: none!important;
} */

.gm-style .gm-style-iw-t::after {
    background: none;
    box-shadow: -2px 2px 2px 0 rgba(178,178,178,.4);
    content: "";
    height: 15px;
    left: 0;
    position: absolute;
    top: 0;
    transform: translate(-50%,-50%) rotate(-45deg);
    width: 15px;
    display:none!important;
}

</style>




<div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="block-flat">
      <div class="content">
        <div class="content">
          <div class="row no-gutter" >
            <div class="col-sm-12">
              <label class="col-sm-1 control-label">Regions</label>
              <div class="col-sm-3">
                <select style="width:100%" name="s_location" class="select2 checkLocation region">
                  <option value="0">All Over India</option>
                  <?php
                  foreach($regions as $row)
                  {
                    ?>
                    <option value="<?php echo $row['location_id'] ?>" data-latitude="<?php echo $row['latitude'] ?>" data-longitude="<?php echo $row['longitude'] ?>"><?php echo $row['location'] ?></option> <?php
                  }
                  ?>
                </select>
              </div>
              <label class="col-sm-offset-2 col-sm-1 control-label">Users</label>
              <div class="col-sm-3">
                  <select name="user_id" class="select2 user_id" id="region" required>
                  <option value="0">All Users</option>
                  <?php
                  foreach($users as $row)
                  {
                    ?>
                    <option value="<?php echo $row['user_id'] ?>" "><?php echo $row['first_name'].'('.$row['employee_id'].')' ?></option> <?php
                  }
                  ?>
                  </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="row ">
  <div class="col-sm-12 col-md-12">
    <div class="block-flat">
      <div class="content live" style="height:calc(100vh)">
      <div id="map"></div>
      </div>
    </div>
  </div>
</div>
<?php
$this->load->view('commons/main_footer.php', $nestedView);
?>

<script>
var markers = [];
var marker;
var map ;
var centers;
var SalesEngg;
var company_id = <?php echo $_SESSION['company']; ?>;
setTimeout(function(){
       location.reload();
   },600000);
//10 min
function initMap() {
   map = new google.maps.Map(document.getElementById('map'), {
    zoom: 5,
    center: {lat: 16.59737200, lng: 80.62412300},
    styles : [
    {
        "featureType": "landscape.natural",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#dde2e3"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "all",
        "stylers": [
            {
                "color": "#c6e8b3"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#c6e8b3"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#c1d1d6"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#a9b8bd"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f8fbfc"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "labels.text",
        "stylers": [
            {
                "color": "#979a9c"
            },
            {
                "visibility": "on"
            },
            {
                "weight": 0.5
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#827e7e"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#3b3c3c"
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#a6cbe3"
            },
            {
                "visibility": "on"
            }
        ]
    }
]
  });

  setMarkers(map);
}

$(document).on('change','.region',function(){
  //initMap();
var region_id = $(this).val();
if(region_id === '0'){
var center = new google.maps.LatLng(16.59737200,80.62412300);
 map.setCenter(center);
 map.panTo(center);
 map.setZoom(5);
}
else{

 var latitude = $(this).find('option:selected').data('latitude');
 var longitude = $(this).find('option:selected').data('longitude');
 var center = new google.maps.LatLng(latitude, longitude);
 map.setCenter(center);
 map.panTo(center);
 map.setZoom(8);
}
});

$(document).on('change','.user_id',function(){
  var ASSET_URL = '<?php echo assets_url()?>';
var user = $(this).val();
if(user === '0' ){
    var a = SalesEngg;
    addAllMarker(a)
}
else{
 
 var filtered = SalesEngg.filter(sales =>sales.user_id === user);
 //console.log("Sales Eng"+JSON.stringify(SalesEngg)); 
 console.log("line 385",filtered);
 if(filtered.length === 0){
     alert('you are not registred with the mobile app')
 }
 else{

 for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
  ///////////////////////////////////////////////////////////
  var name =  (filtered[0]['user_name']).split(' ')[0];
  //console.log('###################397',filtered[0]['end_time']);
      if(filtered[0]['end_time']  === null ){
        var image = {
                  url: ASSET_URL+'images/online.svg',
                  size: new google.maps.Size(20, 32),
                }
      }
      else {
        var image = {
                  url: ASSET_URL+'images/offline.svg',
                  size: new google.maps.Size(20, 32),
                };

      }

// Added on 25-11-2021
    // if(filtered[0]['user_name']  != null ){
    //     var image = {
    //               url: ASSET_URL+'images/online.svg',
    //               size: new google.maps.Size(20, 32),
    //             }
    //   }
    //   else {
    //     var image = {
    //               url: ASSET_URL+'images/offline.svg',
    //               size: new google.maps.Size(20, 32),
    //             };

    //   }
    
// Added on 25-11-2021 end 

        let latlong = new google.maps.LatLng(filtered[0]['latitude'],filtered[0]['longitude'] );
        var contentString = '<div id="content " class="info">'+
                            '<p>'+name+'</p>'+
                            '</div>';

      var infowindow = new google.maps.InfoWindow({
          content: contentString
        });

         marker = new SlidingMarker({
            map : map,
            position: latlong,
            });
            marker.set('id',filtered[0]['user_id']);
            marker.setDuration(1000);
            marker.setEasing('linear');
            marker.setIcon(image);
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
              });

            infowindow.open(map,marker);
            //markers.push(marker);
            markers.push(marker);

}    
}
 ////////////////////////////////////////////////////////

});



// function setCenter() {
//   map.setCenter({lat:21.417276,lng:70.232419});
//     console.log('center');
// }

function setMarkers(map) {
//initMap();
$.ajax({
        url: SITE_URL + 'get_user_list_api',
        data: JSON.stringify({"company_id":company_id}),
        type: 'POST'
    }).then(function(data) {
        console.log(SITE_URL);
       var res = JSON.parse(data);
       console.table('*********',res);
        SalesEngg = res['se_list'];
       addAllMarker(SalesEngg);

       console.log("Sales Eng"+JSON.stringify(SalesEngg)); 
    })
    // .catch(err=>{console.log(err)});

}

setInterval(function() {
                  window.location.reload();
                }, 500000);


function addAllMarker(SalesEngg){
    //initMap();
    var ASSET_URL = '<?php echo assets_url()?>';
  
       for (var i = 0; i < SalesEngg.length; i++) {
        // console.log('###################', SalesEngg[i].end_time,  'YYY', SalesEngg[i]);
        if(SalesEngg[i].end_time === null) {
            var image = {
                url: ASSET_URL+'images/online.svg',
                //   url: ASSET_URL+'images/punchIn.svg',
                  size: new google.maps.Size(20, 32),
                }; 
          
        } 
        else {
            var image = {
                url: ASSET_URL+'images/offline.svg',
                //   url: ASSET_URL+'images/offline.svg',
                  size: new google.maps.Size(20, 32),
                }; 
            
        }   

      // Added on 25-11-2021  
        // if(SalesEngg[i].user_name != null ){
        //     var image = {
        //         url: ASSET_URL+'images/online.svg',
        //         //   url: ASSET_URL+'images/punchIn.svg',
        //           size: new google.maps.Size(20, 32),
        //         }; 
          
        // } 
        // else {
        //     var image = {
        //         url: ASSET_URL+'images/offline.svg',
        //         //   url: ASSET_URL+'images/offline.svg',
        //           size: new google.maps.Size(20, 32),
        //         }; 
            
        // }   
        // Added on 25-11-2021 end

         var name =  (SalesEngg[i]['user_name']).split(' ')[0];
        let latlong = new google.maps.LatLng(SalesEngg[i]['latitude'],SalesEngg[i]['longitude'] );
        var contentString = '<div id="content " class="info">'+
                            '<p>'+name+'</p>'+
                            '</div>';

      var infowindow = new google.maps.InfoWindow({
          content: contentString
        });

         marker = new SlidingMarker({
            map : map,
            position: latlong,
            });
            marker.set('id',SalesEngg[i]['user_id']);
            marker.setDuration(1000);
            marker.setEasing('linear');
            marker.setIcon(image);
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
              });

            infowindow.open(map,marker);
            markers.push(marker);
  }
}






//        function addMarker(car){
//             i++;
//             let latlong1 = new google.maps.LatLng(car.coords.lat,car.coords.lng);
//             marker = new SlidingMarker({
//             map : this.map,
//             position: latlong1,

//             });
//             marker.set('id','100')
//             marker.setDuration(1000);
//             marker.setEasing('linear');
//             markers.push(marker);
//        }

       function updateMarker(message){
        //initMap();
        var ASSET_URL = '<?php echo assets_url()?>';
        var ll = (message.text).split(" ");
        if(ll.length === 3){
        if(ll[2] === 'joined' ){
          var image = {
                  url: ASSET_URL+'images/punchIn.svg', 
                  size: new google.maps.Size(20, 32),
                }; 
            markers.forEach(function(marker) {
                if(marker.id === ll[0]){
                        marker.setIcon(image);
                }
             });

        }
        if(ll[2] === 'left' ){
         
                markers.forEach(function(marker) {
                       if(marker.id === ll[0]){
                        
                         let hh =  SalesEngg.find(marker=> ll[0] === marker.user_id );
                         if(hh.end_time === null){
                            var image = {
                                url: ASSET_URL+'images/online.svg',
                                size: new google.maps.Size(20, 32),
                                };
                         }else{
                            var image = {
                                url: ASSET_URL+'images/offline.svg',
                                size: new google.maps.Size(20, 32),
                                };
                         }


   
                        marker.setIcon(image);
                       }
             });

        }

       }



      var res = (message.text).split(",");

      if(res.length === 3){
        console.log('from mover')
        console.log()
         markers.forEach(function(marker) {

        if(marker.id === res[0]){

             let latlong = new google.maps.LatLng(res[1],res[2]);
             //map.setCenter(latlong);
             //map.panTo(latlong);
             marker.setPosition(latlong);

           }
        });

         }


      }


    </script>

<script>
    var socket;
//   socket = io('http://182.156.75.105:443/');
//   socket = io('http://13.126.121.68:8080/');
socket = io('https://www.skanray-access.com:3000/');
      var name = 'nitish';

socket.on('connect',function () {
     var params = {"name": 'nitish','room':'A'};
    socket.emit('join',params,function (error){
       if(error)
       {
          alert(error);

       }
       else{
        console.log('no error');
       }
    })

})

 socket.on('disconnect',function () {
   console.log('socketttt left',socket.id);
})

socket.on('updateUserList',function (users) {

   var ASSET_URL = '<?php echo assets_url()?>';
   console.log("Changed")
    var image = {
                  url: ASSET_URL+'images/punchIn.svg',
                  size: new google.maps.Size(20, 32),
                };
                users.forEach(function(user) {
                  console.log(user);
                    markers.forEach(function(marker) {

                    if(marker.id === user){
                    marker.setIcon(image);
                    }
                    });

});


})

socket.on('newMessage',function (message){
    updateMarker(message);
});



    </script>





    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJwBZyKfho2MpWqVgwtHNHQpkK4tOq9Gc&callback=initMap"></script>
           <script  src="https://cdnjs.cloudflare.com/ajax/libs/marker-animate-unobtrusive/0.2.8/vendor/markerAnimate.js"></script>
        <script  src="https://cdnjs.cloudflare.com/ajax/libs/marker-animate-unobtrusive/0.2.8/SlidingMarker.min.js"></script>
        <script  src="https://cdnjs.cloudflare.com/ajax/libs/marker-animate-unobtrusive/0.2.8/vendor/markerAnimate.js"></script>
        <script  src="https://cdnjs.cloudflare.com/ajax/libs/marker-animate-unobtrusive/0.2.8/SlidingMarker.min.js"></script>
