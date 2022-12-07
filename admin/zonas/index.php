<?php
//Módulo 5 = Zonas
$consulta = "select count(*) as total from monitoreo.tb_usuarios u join monitoreo.tb_modulosxrol r on u.fk_clave_rol = r.fk_clave_rol where r.fk_clave_mod = 5 and pk_clave_usu = ".$_SESSION['id'];
$query = $conn->prepare($consulta);
$query->execute();
$registro = $query->fetch();
$permiso = $registro['total'];
if($permiso>0) { 
    include_once("admin/zonas/def_variables.php");
    $accion = (isset($_GET['accion']) && $_GET['accion'] != '') ? $_GET['accion'] : 'lista';
    switch ($accion) {
        case "lista":
            include_once("general/def_sesiones.php");
            include_once("general/for_controles.php");
            include_once("admin/zonas/con_elemento.php");
            include_once("general/def_orden.php");
            include_once("admin/zonas/for_elemento.php");
            include_once("general/imp_navegacion.php");
            break;
        case "agrega":
            include ("admin/zonas/con_agrega.php");
            break;
        case "cambia":
            include ("admin/zonas/for_cambia.php");
            break;
        case "actualiza":
            include ("admin/zonas/con_actualiza.php");
            break;
        case "actualizapuntos":
            include ("admin/zonas/con_actualizapuntos.php");
            break;
        case "borra":
            include ("general/con_borra.php");
            break;
        case "eliminaseleccionados":
            include ("general/app_eliminaseleccionados.php");
            break;
        case "mapa":
            include ("admin/zonas/for_mapa.php");
            break;
        case "mapa1":
            include ("admin/zonas/for_mapa1.php");
            break;
    }
} else {
    ?>
    <div class="container">
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Su usuario no tiene acceso a este módulo</strong>.
        </div>
    </div>
    <?php
}
?>