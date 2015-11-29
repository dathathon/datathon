jQuery(document).ready(function(){
  $('.selectpicker').selectpicker('deselectAll');
  $('.selectpicker').selectpicker();

  $(document.body).on('click','.dropdown-menu.inner.selectpicker li', function () {

  });

  // for donut chart
  Morris.Donut({
    element: 'donutChart',
    data: [
      {value: 10, label: 'Categoty', formatted: 'at least 70%' },
      {value: 15, label: 'Gender', formatted: 'approx. 15%' },
      {value: 10, label: 'Country', formatted: 'approx. 10%' },
      {value: 5, label: 'A really really long label', formatted: 'at most 5%' }
    ],
    formatter: function (x, data) { return data.formatted; }
  });

  //for filter and get the value
  $('.filterCss').on('click', '.filterval', function() {
    var element = $(this);
    var value = element.data("val");
    var type = element.data("type");
    var text = element.text();
    $('.'+type+' .glyphicon-ok').hide();
    element.empty().html('<span class="glyphicon glyphicon-ok"></span> '+text);

  });

  $(".toggelPlace").on('click',function(){
      var element = $(this);
      var place = element.attr("place");
      initMap(place);
  });

});

/* comman selector for multiple selector */
function commaSelector(selectorField)
{
    var selectorArray = [];

    $('#'+selectorField+' :checked').each(function (i, selected) {
        selectorArray[i] = $(selected).val();
    });

    $("input[name='"+selectorField+"']").val(selectorArray.join(","));

    // get graph
    $.getJSON("http://192.168.2.248/index.php?filter=1", function( json ) {

    $.each(json, function(i, item) {
        $( "#"+type ).append('<option value="'+item+'">'+item+'</option>');
      });
    }, "jsonp");

}

getingFilters('appsiteids');

// function for getting the filters value
function getingFilters(type='') {

  $.getJSON("http://192.168.2.248/index.php?filter=1", function( json ) {

    $.each(json, function(i, item) {
      $( "#"+type ).append('<option value="'+item+'">'+item+'</option>');
    });
  }, "jsonp");

  $("#"+type).selectpicker('refresh');

}

// for google heat map
  var map, heatmap;

  function initMap(place='') {

    var latlng = new google.maps.LatLng(37.09024, -95.712891);
    map = new google.maps.Map(document.getElementById('heatmap'), {
      zoom: 3,
      center :latlng,
      streetViewControl: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    infowindow = new google.maps.InfoWindow();
    if(place == ''){
      place = 'airport';
    }

    var service = new google.maps.places.PlacesService(map);

    service.nearbySearch({
        location: {lat: 37.30, lng: -121.90},
        radius: 15000,
        types: [place]
      }, callback);

    function callback(results, status) {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
          createMarker(results[i]);
        }
      }
    }

    function createMarker(place) {
      var placeLoc = place.geometry.location;
      //console.log(place.types[0]);

      var pinIcon = new google.maps.MarkerImage(place.icon,
          null, /* size is determined at runtime */
          null, /* origin is 0,0 */
          null, /* anchor is bottom center of the scaled image */
          new google.maps.Size(24,24)
      );

      var str = 'International Airport';
      var txt = place.name;
      if (/International Airport/i.test(txt) || place.types[0] == 'shopping_mall' || place.types[0] == 'school' || place.types[0] == 'train_station'){
       var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location,
        icon : pinIcon,
        visible: true,
      });

       marker.addListener('click', function() {
        map.setZoom(10);
        map.setCenter(marker.getPosition());
      });

      google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(place.name);
        infowindow.open(map, this);
      });

      }
    }


    heatmap = new google.maps.visualization.HeatmapLayer({
      data: getPoints(),
      map: map
    });


  }

  /*function togglePlaces(val){

    var place = $(this).attr('class');
    console.log($(this).find('option:selected').attr("class"));
    initMap(place);
  }*/

  function toggleHeatmap() {
    heatmap.setMap(heatmap.getMap() ? null : map);
  }

  function changeGradient() {
    var gradient = [
      'rgba(0, 255, 255, 0)',
      'rgba(0, 255, 255, 1)',
      'rgba(0, 191, 255, 1)',
      'rgba(0, 127, 255, 1)',
      'rgba(0, 63, 255, 1)',
      'rgba(0, 0, 255, 1)',
      'rgba(0, 0, 223, 1)',
      'rgba(0, 0, 191, 1)',
      'rgba(0, 0, 159, 1)',
      'rgba(0, 0, 127, 1)',
      'rgba(63, 0, 91, 1)',
      'rgba(127, 0, 63, 1)',
      'rgba(191, 0, 31, 1)',
      'rgba(255, 0, 0, 1)'
    ]
    heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
  }

  function changeRadius() {
    heatmap.set('radius', heatmap.get('radius') ? null : 20);
  }

  function changeOpacity() {
    heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
  }

  // Heatmap data: 500 Points
  function getPoints() {

    return [
      new google.maps.LatLng(33.10,-97.10),
      new google.maps.LatLng(25.80,-80.20),
      new google.maps.LatLng(39.20,-84.60),
      new google.maps.LatLng(39.70,-105.00),
      new google.maps.LatLng(43.50,-89.50),
      new google.maps.LatLng(41.50,-81.80),
      new google.maps.LatLng(39.00,-94.70),
      new google.maps.LatLng(26.30,-80.30),
      new google.maps.LatLng(37.30,-121.90),
      new google.maps.LatLng(43.40,-84.00)
    ];
  }