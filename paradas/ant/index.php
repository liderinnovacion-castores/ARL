<?php 
    if(isset($_SESSION["paradas"])) 
    {
	 	$accion = (isset($_GET['accion']) && $_GET['accion']!='') ? $_GET['accion'] : 'captura';
		switch ($accion)
		{
      case "captura":       
          include ("paradas/for_captura.php");
        break;  
      case "lista":       
          include ("paradas/for_captura.php");
          include ("paradas/con_elemento.php");   
          include ("paradas/app_lista.php");
        break;   										
	 	}
	}
	else
	{
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
