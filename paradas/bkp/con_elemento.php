<?php 

  $vehiculo=$_POST['vehiculo'];
  $from=$_POST['from']; 
  $to=$_POST['to'];
  $distancia=$_POST["distancia"];


  $consulta1  = " SELECT * FROM tb_vehiculos
                 WHERE txt_economico_veh=?";  
  $query1 = $conn->prepare($consulta1);
  $query1->bindParam(1, $vehiculo);  
  $query1->execute();
  $nserie=0;  
  while($registro1 = $query1->fetch())
  	 {
      $eco    = $registro1['txt_economico_veh'];
      $nserie   = trim($registro1['num_serie_veh']);
      $fecha_actual = date("Y/m/d H:i:s",time());
      $ubicacion_actual = $registro1['txt_posicion_veh'];
      $lat_actual = $registro1['num_latitud_veh'];
      $lon_actual = $registro1['num_longitud_veh'];
     }
  if($nserie==0)
  {
  	echo "<p>No se encontró el vehículo.</p>";
  	exit();
  }

   $consulta22  = " SELECT * FROM avl_secundario WHERE sec_primario=?"; 
  $query22 = $conn->prepare($consulta22);
  $query22->bindParam(1, $nserie); 
  $query22->execute();
  $nseriesec=0;
  while($registro22 = $query22->fetch())
     {
      $nseriesec = $registro22['sec_secundario'];
     }
  if($nseriesec==0)
  {
            $strSQL  = " SELECT * FROM tb_posiciones WHERE num_nserie_pos='".$nserie."'
               AND DATE(fec_ultimaposicion_pos) >= '".date("Y/m/d", strtotime($from))."'
               AND DATE(fec_ultimaposicion_pos) <= '".date("Y/m/d", strtotime($to))."'
               ORDER BY fec_ultimaposicion_pos ";
  }
  else 
{
	$strSQL  = "  SELECT * FROM (
               SELECT * FROM tb_posiciones WHERE num_nserie_pos='".$nserie."' 
               AND DATE(fec_ultimaposicion_pos) >= '".date("Y/m/d", strtotime($from))."'
               AND DATE(fec_ultimaposicion_pos) <= '".date("Y/m/d", strtotime($to))."'
              UNION
               SELECT * FROM tb_posiciones WHERE num_nserie_pos='".$nseriesec."'
               AND DATE(fec_ultimaposicion_pos) >= '".date("Y/m/d", strtotime($from))."'
               AND DATE(fec_ultimaposicion_pos) <= '".date("Y/m/d", strtotime($to))."'
             ) posiciones
               ORDER BY fec_ultimaposicion_pos ";
 }
?>
