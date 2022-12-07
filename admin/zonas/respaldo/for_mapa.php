
<?php 

  $id=$_GET["id"];
  $consulta  = " SELECT * FROM tb_zonas, tb_detallezonas
                 WHERE pk_clave_zon=? AND pk_clave_zon=fk_clave_zon ORDER BY pk_clave_det ASC ";  
  $query = $conn->prepare($consulta);
  $query->bindParam(1, $id);
  $query->execute();

  $consulta1  = " SELECT COUNT(*) as total FROM tb_zonas, tb_detallezonas
                 WHERE pk_clave_zon=? AND pk_clave_zon=fk_clave_zon ";                   
  $query1 = $conn->prepare($consulta1);
  $query1->bindParam(1, $id);
  $query1->execute();
  $registro1 = $query1->fetch();
  $total=$registro1["total"];

  $consulta2  = " SELECT * FROM tb_zonas, tb_detallezonas
                 WHERE pk_clave_zon=? AND pk_clave_zon=fk_clave_zon ORDER BY pk_clave_det ASC";                   
  $query2 = $conn->prepare($consulta2);
  $query2->bindParam(1, $id);
  $query2->execute();
  $registro2 = $query2->fetch();

?>

<form action="?seccion=zonas&amp;accion=actualizapuntos&amp;id=<?php echo $id ?>" id="form1" method="post">
<div class="container">
  <div class="row">    
    <div class="col-md-6">
      <h1>DIBUJA ZONA <?php echo $registro2["txt_nombre_zon"]?></h1>      
    </div>
    <div class="col-md-3">
      <button type="submit" id="agrega"  class="btn btn-primary">GUARDAR CAMBIOS</button>
    </div>
    <div class="col-md-3">
      <a href="#" onclick="history.go(-1); return false;"><button type="button"  class="btn btn-warning">REGRESAR</button></a>   
    </div>  
  </div>
</div>
    <div class="lngLat"><span class="one"></span><span class="two"></span></div>
 <div id="info"></div>
</form>
<div id="map" ></div>


<script>

  function initMap() {
    <?php if($total>0) { $zoom=17; ?>
      var myLatLng = new google.maps.LatLng(<?php echo $registro2["num_latitud_zon"]?>, <?php echo $registro2["num_longitud_zon"]?>);
    <?php } else {  $zoom=5;?>
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

  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $gmk ?>&callback=initMap"></script>
