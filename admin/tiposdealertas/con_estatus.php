<?php
  $consulta  = " UPDATE tb_tiposdealertas SET 
                        num_activo_tipa=?
                        WHERE pk_clave_tipa=? ";  
  $query = $conn->prepare($consulta);
  $query->bindParam(1, $estatus);  
  $query->bindParam(2, $id);
  $estatus=$_GET["estatus"];
  $id=$_GET["id"];
  $query->execute();   
  $redireccionar="?seccion=".$seccion."&accion=lista"; 
?>
<script>
  window.location.href = "<?php echo  $redireccionar; ?>";
</script>