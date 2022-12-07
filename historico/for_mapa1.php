<script src="../librerias/jquery.min.js"></script>
<script src="key.js"></script>

<style type="text/css">

body{
margin:0px;
padding:0px;
font-size:12px;
}

h1 {
font-size: 1em;
font-weight: bold;
}

#map{
width:100%;
height:640px;
margin:0;
padding:0px;
}

#loadstatus{
position:absolute;
bottom:0;
left:0;
z-index:1000;
width:100%;
line-height:30px;
height:30px;
border:1px solid #ccc;
text-align:left;
background-color:#fff;
}

#hideStats{
display:none;
}

#stats{
position:absolute;
top:130px;
left:0;
margin:0;
padding:0;
z-index:2000;
background-color:#fff;
border:1px solid #fff;
border-bottom:1px solid #ccc;
width:99%;
height:450px;
overflow:auto;
display:none;
}

#stats p{
margin:0 0 15px 0;
}

#information{
position:absolute;
bottom:0;
z-index:1500;
text-align:center;
width:100%;
}

#progress{
height:100%;
width:0%;
background-color:#DDEFA4;
}

#firstHalf{
width:45%;
margin:5px 0 15px 3px;
border-bottom:3px solid blue;
float:left;
}
#secondHalf{
width:45%;
margin:5px 3px 15px 0;
border-bottom:3px solid red;
float:right;
text-align:right;
}

</style>

﻿<?php 
session_start();
include ('../conexion/conexion.php');
include("api_Maps.php"); 

  $id=$_GET["id"];
  $consulta  = " SELECT * FROM tb_zonas, tb_detallezonas WHERE pk_clave_zon=? AND pk_clave_zon=fk_clave_zon ORDER BY pk_clave_det ASC ";  
  $query = $conn->prepare($consulta);
  $query->bindParam(1, $id);
  $query->execute();
  $consulta1  = " SELECT COUNT(*) as total FROM tb_zonas, tb_detallezonas WHERE pk_clave_zon=? AND pk_clave_zon=fk_clave_zon ";                   
  $query1 = $conn->prepare($consulta1);
  $query1->bindParam(1, $id);
  $query1->execute();
  $registro1 = $query1->fetch();
  $total=$registro1["total"];
  $consulta2  = " SELECT * FROM tb_zonas, tb_detallezonas WHERE pk_clave_zon=? AND pk_clave_zon=fk_clave_zon ORDER BY pk_clave_det ASC";                   
  $query2 = $conn->prepare($consulta2);
  $query2->bindParam(1, $id);
  $query2->execute();
  $registro2 = $query2->fetch();
?>

<form action="?seccion=zonas&amp;accion=actualizapuntos&amp;id=<?php echo $id ?>" id="form1" method="post">
<div class="container">
  <div class="row">    
    <div class="col-md-6">
        
       <h1> <center> ZONA DE RIESGO </center> </h1>   
       
      <h1>NOMBRE <?php echo $registro2["txt_nombre_zon"]?></h1>      
    </div>
  </div>
	<div class="row">
		<div class="col-md-3">
			 Latitud:<input type="text" name="latitud" id="latitud" value="" class="validate[required] text-input text form-control" size="30" maxlength="30"   />
		</div>
		<div class="col-md-3">
			 Longitud:<input type="text" name="longitud" id="longitud" value="" class="validate[required] text-input text form-control" size="30" maxlength="30"   />
		</div>
	</div>
</div>
    <div class="lngLat"><span class="one"></span><span class="two"></span></div>
 <div id="info"></div>
</form>
  
<div id="map" ></div>


<script>

  function initMap() {  
    <?php if($total>0) { $zoom=12; ?>
      var myLatLng = new google.maps.LatLng(<?php echo $registro2["num_latitud_zon"]?>, <?php echo $registro2["num_longitud_zon"]?>);
    <?php } else {  $zoom=8;?>
      var myLatLng = new google.maps.LatLng(<?php echo $latitudcentro?>, <?php echo $longitudcentro?>);
    <?php } ?>

    var mapOptions = {
      zoom: <?php echo $zoom; ?>,
      center: myLatLng,
      mapTypeId: google.maps.MapTypeId.RoadMap
    };

    var map = new google.maps.Map(document.getElementById('map'),mapOptions);

    var coordenadas = [
      <?php
        if($total>0)
        { $cuenta=1; 
          while($registro = $query->fetch()) 
          { ?>
            new google.maps.LatLng(<?php echo $registro["num_latitud_zon"] ?>, <?php echo $registro["num_longitud_zon"] ?>)<?php if($cuenta<$total) echo ",";?>
        <?php 
            $cuenta++; 
          } 
        } else {
        ?>
          new google.maps.LatLng(22.233759, -103.144604),
          new google.maps.LatLng(22.335417, -99.420239),
          new google.maps.LatLng(20.004915, -99.206006),
          new google.maps.LatLng(19.886151, -103.144604)         
      <?php
        }
      ?>
    ];
    myPolygon = new google.maps.Polygon({
      paths: coordenadas,
      draggable: true, // turn off if it gets annoying
      editable: true,
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35
    });

    myPolygon.setMap(map);
  google.maps.event.addListener(myPolygon, "dragend", getPolygonCoords);
  google.maps.event.addListener(myPolygon.getPath(), "insert_at", getPolygonCoords);
  google.maps.event.addListener(myPolygon.getPath(), "remove_at", getPolygonCoords);
  google.maps.event.addListener(myPolygon.getPath(), "set_at", getPolygonCoords);

    // Enable a Secure Point marker and make it draggable in the map.

function openInfoWindow(marker) {
    var markerLatLng = marker.getPosition();
    infoWindow.setContent([
        '<b>La posición del punto seguro es:</b><br/>',
        markerLatLng.lat(),
        ', ',
        markerLatLng.lng(),
        '<br/><br/><b>Arrastre el marcador para actualizar la posición.</b>'
    ].join(''));
    infoWindow.open(map, marker);
	document.getElementById('latitud').value = markerLatLng.lat();
	document.getElementById('longitud').value = markerLatLng.lng();
}
	infoWindow = new google.maps.InfoWindow();
	<?php if ($registro2["num_latitudcen_zon"] and $registro2["num_longitudcen_zon"]){
	?>
		var pointPosition = new google.maps.LatLng(<?php echo $registro2["num_latitudcen_zon"]?>, <?php echo $registro2["num_longitudcen_zon"]?>);
	<?php
		}else{
	?>
		var pointPosition = myLatLng;
	<?php
		}
	?>
	var marker = new google.maps.Marker({
		position: pointPosition,
		draggable: true,
		map: map,
	});
	// mouseup allows to update the position of the marker when
	// is dragged and dropped in the map.
	google.maps.event.addListener(marker, 'mouseup', function(){
		openInfoWindow(marker);
	});
	//infowindow.open(map,marker);
	openInfoWindow(marker);
  }

  //Display Coordinates below map
  function getPolygonCoords() {
    var len = myPolygon.getPath().getLength();
    var htmlStr = "";
    for (var i = 0; i < len; i++) {
      htmlStr += "<input type='hidden' name='latlong"+(i+1)+"' value='"+myPolygon.getPath().getAt(i).toUrlValue(5)+"' />";
      //Use this one instead if you want to get rid of the wrap > new google.maps.LatLng(),
      //htmlStr += "" + myPolygon.getPath().getAt(i).toUrlValue(5);
    }
    htmlStr += "<input type='hidden' name='puntos' value='"+i+"' />";
    document.getElementById('info').innerHTML = htmlStr;
  }
</script>
<!--<script src="https://maps.googleapis.com/maps/api/js?key=<<!--?php echo $gmk ?>&callback=initMap"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXNIR-oeOZjAnQX8XgQnE1dhJXLVjWFUM&callback=initMap"></script>
