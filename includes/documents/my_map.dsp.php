<?php
  $fb =& CFotobox::getInstance();
  $t  =& CTag::getInstance();
  
  $geo = array();
  if(isset($tags))
  {
    $tagUrl = 'tags-' . $tags . '/';
    $filterTags = (array)explode(',', $tags);
    $siblings = $t->getSiblings($user_id, $tags);
    $siblingTags = array();
    foreach($siblings as $sibling)
      $siblingTags[] = $sibling['uts_sibling'];
    $siblingTags = implode(',', $siblingTags);
    $geo = $t->geoForTags($user_id, $siblingTags);
  }

  if(count($geo) > 0)
  {
    echo '<div id="fotomap" style="width:100%; height:450px; margin: 0 0 10px 10px;"></div>
      <script>
        google.load("maps", "2.x");
// Call this function when the page has been loaded
function initialize() {
  var map = new google.maps.Map2(document.getElementById("fotomap"));
  var bounds = new GLatLngBounds(new GLatLng(0,0), new GLatLng(0,0));
';
    $lats = $lons = array();
    foreach($geo as $coords)
    {
      if($coords['utg_latitude'] < $minLat)
        $minLat = $coords['utg_latitude'];
      if($coords['utg_latitude'] > $maxLat)
        $maxLat = $coords['utg_latitude'];

      if($coords['utg_longitude'] < $minLon)
        $minLon = $coords['utg_longitude'];
      if($coords['utg_longitude'] > $maxLon)
        $maxLon = $coords['utg_longitude'];

      $lats[] = $coords['utg_latitude'];
      $lons[] = $coords['utg_longitude'];
      echo '
        var latlng = new GLatLng('.$coords['utg_latitude'].', '.$coords['utg_longitude'].');
        map.addOverlay(new GMarker(latlng));
        bounds.extend(latlng);
        ';

    }
    $latAvg = array_sum($lats) / count($lats);
    $lonAvg = array_sum($lons) / count($lons);;
    $latDelta = $maxLat - $minLat;
    $lonDelta = $maxLon - $minLon;
    echo '
  var center = new GLatLng( '.floatval($latAvg).', '.floatval($lonAvg).' ); 
  var delta = new GSize( '.floatval($lonDelta).', '.floatval($latDelta).'); 
  //var minZoom = map.spec.getLowestZoomLevel(center, delta, map.viewSize); 
  //map.centerAndZoom(center, 10); 
  map.setCenter(center, map.getBoundsZoomLevel(bounds));
  map.addControl(new GSmallZoomControl());
}
google.setOnLoadCallback(initialize);
      </script>
      ';
  }
