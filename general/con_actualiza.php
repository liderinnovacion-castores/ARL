<?php

  $consulta  = " UPDATE ".$Tabla." SET ".$campoMostrar."=? WHERE ".$campoId."=?";  
  $query = $conn->prepare($consulta);
  $query->bindParam(1, $elemento);
  $query->bindParam(2, $id);
  $elemento=$_POST["elemento"];
  $id=$_POST["id"];
  $query->execute();    
  $redireccionar="?seccion=".$seccion."&accion=lista";
  
  if(isset($_POST["rxp"]))
  	$redireccionar.="&rxp=".$_POST["rxp"];
  if(isset($_POST["orden"]))
  	$redireccionar.="&orden=".$_POST["orden"];
  if(isset($_POST["busca"]))
  	$redireccionar.="&busca=".$_POST["busca"];  
  if(isset($_POST["inicia"]))
    $redireccionar.="&inicia=".$_POST["inicia"]; 
  $query->closeCursor();  
?>
<script>
  window.location.href = "<?php echo  $redireccionar; ?>";
</script>