<?php 
  $color="#BF1F10";
  $colorsobre="#99170D";
?>
<style type="text/css">

html, body {
  height: 100%;
  margin: 0px;
  padding: 0px;
}
body {	
  background-color: transparent;
  font-family: 'Open Sans', sans-serif;
  font-size: 12px;
}

#map {
  height: 100%;
  margin: 0px;
  padding: 0px;
  max-height: 460px;
}

.row {
  margin:0px;
  padding: 0px;
}

a, a:active, a:visited {
  color: <?php echo $color?>;
  text-decoration: none;
}

a:hover {
  color: <?php echo $colorsobre?>;
  text-decoration: none;
}

h1 {
  font-size: 1.5em;
  margin: 0px;
  padding: 0px;
}

h2  {
  font-size: 1.5em;
  font-weight: bold;
  margin: 0px;
  padding: 0px;
}

h3 {
  font-size: 1em;
  font-weight: bold;
  margin: 0px;
  padding: 0px;
}

p {
  text-align: justify;
}

ul {

}
img {
}
.foto {
    max-height: 100%;  
    max-width: 100%; 
    width: auto;
    height: auto;
    position: absolute;  
    top: 0;  
    bottom: 0;  
    left: 0;  
    right: 0;  
    margin: auto;
}

.negrita {
  font-weight: bold;
}

.centrado {
  text-align: center;
}

.img-center{
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    position: relative;
}
.izquierda {
  text-align: left;
}

.derecha {
  text-align: right;
}

.blanco {
  color: #FFFFFF;
}

.container-fluid{
	padding: 0px;
}

.sinmargen {
  padding: 0px;
  margin: 0px; 
}

.form-control {
}

#logo {
  display:block;
  margin:0 auto;
  margin-top: -10px;
  margin-bottom: -5px;
  max-width: 150px;
}

/* Propiedades utlizadas en acceso/ingresa.php */
.contenedoracceso{
  position: absolute;
  top: 30%;
  width: 100%;
}

.contenedoracceso h1 {
  margin-bottom:-5px
}

.lineanegra{
  background-color:#000;
  height: 10px;
}

.fondoblanco{
  background-color: #FFF;
  height: 250px; 
  padding-top:50px
}

#vehiculo {
  margin-top:-120px;
  width: 100%;  
}

.fondo { 
  background: url(imagenes/fondo.jpg) no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  background-color: none !important;
  min-height: 700px;
}


div[class^="col-"] {
    padding-top: 5px;
    padding-bottom: 5px;
}

/* MENU PRINCIPAL */
#menuprincipal {
  z-index: 1; 
  position: fixed; 
  right: 0px; 
  top: 50px; 
  width:230px; 
  padding: 10px; 
  background-color:#FFF; 
  border:1px solid #CCC;
  display: none;
}

#menuprincipal ul {
  margin: 0px;
  padding: 0px;
  list-style-type: none;
}

#menuprincipal ul li {
  padding: 3px;  
}

#menuprincipal ul li:hover {
  color: #FFFFFF; 
  background-color: <?php echo $color?>;
}

.opcionmenu { 
}

#botonmenu {
  padding:8px;
  background-color: #EFEFEF;
  border: 1px solid #CCCCCC;
  border-radius: 6px;
}

.barramenu {
  margin:3px;
  display:block; 
  background-color:<?php echo $color;?>; 
  width:22px; 
  height: 2px
}

/* Variaciones para ajuste en tabletas e ipads */
@media only screen and (max-width: 996px) {

  .contenedoracceso{
    top: 20%;
  }    

  .fondoblanco{
    height: 320px; 
    padding-top:0px
  }

  #vehiculo {
    width: 70%;
    display: block;
    margin: 0 auto;
  }
}

/* Variaciones para ajuste en dispositivos móviles smartphone */
@media only screen and (max-width: 840px) {
  h1 {
    font-size: 14px;
  }
  h2 {
    font-size: 14px;
  }
  h3 {
    font-size: 14px;
  }

  .contenedoracceso{
    top: 5%;
  }  

  .fondoblanco{
    height: 290px; 
    padding-top:5px
  }

  #vehiculo {
    width: 100%;
  }

  #botonmenu {
    position: fixed;
    top: 10px;
    right: 10px;
  }

  #menuprincipal {
    z-index: 1; 
    position: relative; 
    right: 0px; 
    top: 20px; 
    width:100%; 
  }
}

.fondocolor {
  background-color: <?php echo $color?>;
}

/* Utilizados en el Monitor de Alertas*/
.circulo {
  border-radius: 50%;
  width: 30px;
  height: 30px; 
  margin: 0 auto;
  font-size: 10px;
  line-height: 28px;
}

.fondorojo {
  background-color: #CC0000;
}
.fondoverde {
  background-color: #009900;
}
.fondoamarillo {
  background-color: #FFCC00;
}

.rojo {
  color: #CC0000;
}
.verde {
  color: #009900;
}
.amarillo {
  color: #FFCC00;
}

.glyphicon {
  font-size: 9px;
}
</style>