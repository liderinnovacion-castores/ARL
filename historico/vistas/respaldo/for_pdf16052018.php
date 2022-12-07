<!-- Con <page> se define una hoja con los márgenes que
   que se muestran -->
<style type="text/css">
<!--
#encabezado {padding:10px 0; border-top: 1px solid; border-bottom: 1px solid; width:100%;}
#encabezado .fila #col_1 {width: 100%; text-align:center;}
#encabezado .fila #col_2 {text-align:center; width: 100%}
#encabezado .fila #col_3 {text-align:center; width: 38%}

#encabezado .fila td img {width:150px; text-align:center;}
#encabezado .fila #col_2 #span1{font-size: 15px;}
#encabezado .fila #col_2 #span2{font-size: 12px; color: #4d9;}

#footer {padding-top:5px 0; border-top: 1px solid; width:100%;}
#footer .fila td {text-align:center; width:100%;}
#footer .fila td span {font-size: 10px; color: #f5a;}

#table {background-color: #ffffff; text-align:center; width: 100%;}
#table .tabla #col_3 {background-color: #f5f5f5; border: 1px solid #ddd; text-align:center;}


#margen tr td {width:38%;  height:25;}
#datos_header {margin:auto; width:100%; border-top:3px solid #ddd; border-left:3px solid #ddd; border-right:3px solid #ddd; border-top-left-radius:20px; border-top-right-radius:20px;}
#datos_header tr td {border:1px solid #ddd; width:38%; text-align:center; height:25;}
#datos_header .fila #col_4 {border:1px solid #ddd; background-color: #D8D8D8;}
#datos {border-bottom:3px solid #D8D8D8; border-right:3px solid #D8D8D8; border-left:3px solid #D8D8D8; margin:auto; width:100%; border-bottom-left-radius:20px; border-bottom-right-radius:20px;}
#datos .fila #col_3 {border:1px solid #ddd; background-color: #f2f2f2;}
#datos .fila #col_4 {border:1px solid #ddd; background-color: #D8D8D8;}
#datos .fila #col_5 {border:1px solid #ddd;}
#datos tr td {border:1px solid #ddd; width:38%; text-align:center; height:25;}
</style>

<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    date_default_timezone_set("America/Mexico_City");

    include("conexion.php");
    include ('../posiciones/app_referencia.php');
    include ('../funciones/distancia.php');


    //Consulta parametro de ajuste de horas con respecto al GPS

  $consulta0  = "SELECT * FROM tb_parametros WHERE txt_nombre_par ='ajustegps' ";
  $query0 = $conn->prepare($consulta0);
  $query0->execute();
  $registro0 = $query0->fetch();
  $ajustegps=$registro0["num_valor_par"];
  $query0->closeCursor();

    $id=$_GET['id'];
  $filtro=$_GET['filtro'];

  $fechainicial=date('Y-m-d H:i:s',strtotime($ajustegps.' hour',strtotime($_GET["ini"])));
  $fechafinal=date('Y-m-d H:i:s',strtotime($ajustegps.' hour',strtotime($_GET["fin"])));

  if ($filtro=="posiciones" or $filtro=="trayectoria" ){

    $consulta  = "SELECT num_serie_veh FROM tb_vehiculos WHERE txt_economico_veh =? ";
    $query = $conn->prepare($consulta);
    $query->bindParam(1, $id);
    $query->execute();
    $registro = $query->fetch();
    $nserie = $registro["num_serie_veh"];

    $consulta1  = "SELECT *,fec_ultimaposicion_pos as fecha, num_latitud_pos as latitud,             num_longitud_pos as longitud,num_ignicion_pos as ignicion
             FROM tb_posiciones WHERE num_nserie_pos = ? AND fec_ultimaposicion_pos>=?
             AND fec_ultimaposicion_pos<=? ORDER BY fec_ultimaposicion_pos ASC";
    $query1 = $conn->prepare($consulta1);
    $query1->bindParam(1, $nserie);
    $query1->bindParam(2, $fechainicial);
    $query1->bindParam(3, $fechafinal);
    $query1->execute();

    $row_array= array ();
    $r_totales = 0;
    $distancia = 0;
    $comb_consumido = 0;
    $rendimiento_calc = 0;
    $ban = 0;

    while ($registro1 = $query1->fetch())
    {
      $icono = "images/posicion.png";
      //$fecha = $registro1['fecha'];
      $fecha = date('Y-m-d H:i:s',strtotime('-'.$ajustegps.' hour',strtotime($registro1['fecha'])));
            $latitud = $registro1['latitud'];
      $longitud = $registro1['longitud'];
      $unidad_ubicacion = georeferencia($latitud,$longitud,$conn).",".georeferencia_pi($latitud,$longitud,$conn);
            $odometro = 0;
            $comb_total = 0;
            $velocidad = 0;
            $com_ocioso = 0;
            $temperatura = 0;
            $presion_aceite = 0;
            $rpm = 0;
            $tiempo_crucero = 0;
            $dtc = 0;
            $rendimiento = 0;

            if ($registro1['txt_odometro_pos'] != '') $odometro = $registro1['txt_odometro_pos'];
            if ($registro1['txt_combtot_pos'] != '') $comb_total = $registro1['txt_combtot_pos'];
            if ($registro1['txt_descolgada_pos'] != '') $velocidad = $registro1['txt_descolgada_pos'];
            if ($registro1['txt_comboci_pos'] != '') $com_ocioso = $registro1['txt_comboci_pos'];
            if ($registro1['txt_taceite_pos'] != '') $temperatura = $registro1['txt_taceite_pos'];
            if ($registro1['txt_presion_aceite_pos'] != '') $presion_aceite = $registro1['txt_presion_aceite_pos'];
            if ($registro1['txt_rpm_pos'] != '') $rpm = $registro1['txt_rpm_pos'];
            if ($registro1['txt_velcruc_pos'] != '') $tiempo_crucero = $registro1['txt_velcruc_pos'];
            if ($registro1['txt_coderr_pos'] != '') $dtc = $registro1['txt_coderr_pos'];
            if ($registro1['txt_rendimiento_pos'] != '') $rendimiento = $registro1['txt_rendimiento_pos'];

          if($ban == 0){
            $odometro_anterior = $odometro;
            $comb_anterior = $comb_total;
            $ban = 1;
          }else{
            $distancia = $odometro - $odometro_anterior;
            $comb_consumido = $comb_total - $comb_anterior;
            $rendimiento_calc = 0;
        if ($comb_consumido > 0){
          $rendimiento_calc = round($distancia / $comb_consumido,2);
        }
        $odometro_anterior = $odometro;
        $comb_anterior = $comb_total;
          }

      $fila = array ( 'latitud'=>$latitud,
                            'longitud'=>$longitud,
                            'unidad'=>$id,
                            'posicion'=>$unidad_ubicacion,
                            'uposicion'=>$fecha,
                            'ignicion'=>$registro1['ignicion'],
                            'icono'=>$icono,
              'tipo'=>'Posicion',
              'odometro' => $odometro,
              'comb_total' => $comb_total,
              'speed' => $velocidad,
              'com_ocioso' => $com_ocioso,
              'temperatura' => $temperatura,
              'presion_aceite' => $presion_aceite,
              'rpm' => $rpm,
              'tiempo_crucero' => $tiempo_crucero,
              'dtc' => $dtc,
              'rendimiento' => $rendimiento,
              'distancia_odo' => $distancia,
              'comb_consumido' => $comb_consumido,
              'rendimiento_calc' => $rendimiento_calc,
              'datos_motor' => 1
             );
          $row_array[]= $fila;
          $r_totales ++;

    }  // fin del while posiciones
    }  // fin del if filtro

       //-------------------------  extraccion de mensajes en el periodo dado ------------------------

   if ($_GET['filtro']=='eventos'){

    $consulta2  = "SELECT * FROM tb_alertas, tb_tiposdealertas WHERE txt_economico_veh =?
             AND fk_clave_tipa=pk_clave_tipa AND fec_fecha_ale<=? AND fec_fecha_ale>=?
             ORDER BY fec_fecha_ale ASC";
    $query2 = $conn->prepare($consulta2);
    $query2->bindParam(1, $id);
    $query2->bindParam(2, $fechainicial);
    $query2->bindParam(3, $fechafinal);
    $query2->execute();

    while ($registro2 = $query2->fetch())
    {
      switch ($registro2['txt_ignicion_ale']):
                    case 1:
                        $ignicion = 'Encendido';
                        break;
                    case 2:
                        $ignicion = 'Apagado';
                        break;
                    default:
                      $ignicion = 'Desconocido';
                       break;
            endswitch;

      $unidad_ubicacion = "";
      //$unidad_ubicacion = referencia_geo($latitud,$longitud,$conecta_mysql,$database_smartfleet)."<br>[".referencia_geo_pi($latitud,$longitud,$conecta_mysql,$database_smartfleet)."]";

            switch ($registro2['txt_nombre_tipa']):
                case 'Entrada Punto':
                      $icono = "images/entrada_punto.png";
                    break;
                case 'Deteccion Parada':
                      $icono = "images/parada_na.png";
                    break;
                default:
                    $icono = "images/evento.png";
                   break;
            endswitch;

      $odometro = 0;
            $comb_total = 0;
            $velocidad = 0;
            $com_ocioso = 0;
            $temperatura = 0;
            $presion_aceite = 0;
            $rpm = 0;
            $tiempo_crucero = 0;
            $dtc = 0;
            $rendimiento = 0;
          $fila = array ( 'latitud'=>$registro2['num_latitud_ale'],
                            'longitud'=>$registro2['num_longitud_ale'],
                            'unidad'=>$id,
                            'posicion'=> $unidad_ubicacion,
                            'uposicion'=>$registro2['fec_fecha_ale'],
                            'ignicion'=>$ignicion,
                            'icono'=>$icono,
              'tipo'=>$registro2['num_tipo_ale'],
              'odometro' => $odometro,
              'comb_total' => $comb_total,
              'speed' => $velocidad,
              'com_ocioso' => $com_ocioso,
              'temperatura' => $temperatura,
              'presion_aceite' => $presion_aceite,
              'rpm' => $rpm,
              'tiempo_crucero' => $tiempo_crucero,
              'dtc' => $dtc,
              'rendimiento' => $rendimiento,
              'distancia_odo' => 0,
              'comb_consumido' => 0,
              'rendimiento_calc' => 0,
              'datos_motor' => 0);
              $row_array[]= $fila;
              $r_totales ++;
      }
  }
?>
<page backtop="58mm" backbottom="10mm" backleft="4.6mm" backright="10mm" footer="page">
<page_header>
        <table id="encabezado">
            <tr class="fila">
                <td id="col_1" >
                    <img src="/var/www/html/imagenes/logo.jpg">
                    <br>
                </td>
            </tr>
            <tr class="fila">
                <td id="col_3">
                    <strong><?php echo ucwords(strtolower($_SESSION["nombre"]));?>, Histórico de posiciones, Fecha: <?php echo date('d/m/Y H:i:s', time())?></strong>
                 </td>
            </tr>
            <tr class="fila">
                <td id="col_3">
                <strong>Unidad: <?php echo $id;?>, Rango de Fecha: <?php echo $_GET['ini'];?> - <?php echo $_GET['fin'];?></strong>
                 </td>
            </tr>
        </table>
        <table id="margen">
            <tr>
                <td></td>
            </tr>
        </table>

    <table id="datos_header">
        <tr class="tabla fila">
            <td id="col_4" width="45" style="border-top-left-radius: 20px; font-size:9px;"><strong>Fecha / hora</strong></td>
            <td id="col_4" width="90"><strong style="font-size:9px;">Posición</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Tipo</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Distancia</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Comb. Cons.</strong></td>
            <td id="col_4" width="55"><strong style="font-size:9px;">Rendimiento</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Tiempo</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Vel.</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Odo.</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Comb. Tot.</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Comb. Oc.</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">Temp.</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">P. Aceite</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">RPM</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">T. Crucero</strong></td>
            <td id="col_4" width="45"><strong style="font-size:9px;">CodErr</strong></td>
            <td id="col_4" width="45" style="border-top-right-radius: 20px; font-size:9px;"><strong>Exc. Vel.</strong></td>
        </tr>
    </table>
</page_header>
    <page_footer>
        <table id="footer">
            <tr class="fila">
                <td>
                    <br>
                </td>
            </tr>
        </table>
    </page_footer>

    <table id="datos">
        <?php

        $flag_fill_color = "No";
        if (count($row_array) > 0 ){

            foreach ($row_array as $llave => $fila) {
                $uposicion[$llave]  = $fila['uposicion'];

            }

            array_multisort($uposicion, SORT_ASC,$row_array);

            $ii = 0;
                foreach ($row_array as $filas){
            ?>
            <tr class="tabla fila" >
            <?php
            switch ($flag_fill_color) {
                case "No":
            ?>

                    <td id="col_5" width="45"><strong style="font-size:8px;"><?php echo $filas['uposicion']?></strong></td>
                    <td id="col_5" width="90"><strong style="font-size:8px;"><?php echo $filas['posicion']?></strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;"><?php echo $filas['tipo']?></strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;"><?php echo $filas['distancia_odo']?> Kms.</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;"><?php echo $filas['comb_consumido']?> Lts.</strong></td>
                    <td id="col_5" width="55"><strong style="font-size:8px;"><?php echo $filas['rendimiento']?> Kms./Lt.</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;"><?php echo $filas['rendimiento_calc']?></strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;"><?php echo $filas['speed']?> Km/h</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">1|</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_5" width="45"><strong style="font-size:8px;">0</strong></td>
                <?php
                  $flag_fill_color = "Si";
                  break;
                case "Si":
                ?>
                    <td id="col_3" width="45"><strong style="font-size:8px;"><?php echo $filas['uposicion']?></strong></td>
                    <td id="col_3" width="90"><strong style="font-size:8px;"><?php echo $filas['posicion']?></strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;"><?php echo $filas['tipo']?></strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;"><?php echo $filas['distancia_odo']?> Kms.</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;"><?php echo $filas['comb_consumido']?> Lts.</strong></td>
                    <td id="col_3" width="55"><strong style="font-size:8px;"><?php echo $filas['rendimiento']?> Kms./Lt.</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;"><?php echo $filas['rendimiento_calc']?></strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;"><?php echo $filas['speed']?> Km/h</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">1|</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">0</strong></td>
                    <td id="col_3" width="45"><strong style="font-size:8px;">0</strong></td>

                <?php
                  $flag_fill_color = "No";
                  break;
              default:
                  $flag_fill_color = "No";
                  break;

            }
                ?>

        </tr>
        <?php
        $ii = $ii + 1;
        } // end of the foreach

        }else{
            ?> <p>No se encontraron registros</p><?php
            }
        ?>
    </table>
<br>
<table>
    <tr>
        <td>
             <strong>Distancia Total:</strong>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Combustible Total:</strong>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Rendimiento del Período: Kms./Lt.</strong>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Tiempo Total: Hrs.</strong>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Velocidad Promedio: Kms./Hr.</strong>
        </td>
    </tr>
</table>
</page>
