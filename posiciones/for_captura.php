<!-- Include Date Range Picker -->
<script src="./librerias/datetimepicker/moment.min.js"></script>
<script type="text/javascript" src="./librerias/datetimepicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="./librerias/datetimepicker/daterangepicker.css" />

<div class="container-fluid">
    <form id="form1" class="ingresa" action="?seccion=posiciones&amp;accion=lista" method="post">
        <div class="row">

            <div class="col-md-1">
                Número ecónomico:
                <input type="text" name="vehiculo" class="validate[required] form-control"
                    value="<?php if(isset($_POST["vehiculo"])) echo $_POST["vehiculo"]; ?>" />
            </div>
            <div class="col-md-3">
                Rango de fecha-hora:
                <input type="text" name="daterange" id="daterange" class="form-control" size=40>
            </div>
            <div class="col-md-2">
                Rango min. entre registros PDF:
                <select id="txr" class="form-control">
                    <?php $txr = 5  ?>
                    <option value="5" <?php if ($txr == 5) { ?> selected="selected" <?php } ?>>5</option>
                    <option value="10" <?php if ($txr == 10) { ?> selected="selected" <?php } ?>>10</option>
                    <option value="20" <?php if ($txr == 20) { ?> selected="selected" <?php } ?>>20</option>
                    <option value="30" <?php if ($txr == 30) { ?> selected="selected" <?php } ?>>30</option>
                </select>
            </div>
            <div class="col-md-1">
                <input type="hidden" name="from" id="from" value="">
                <input type="hidden" name="to" id="to" value="">
                <br /><button type="submit" id="buscar" class="btn btn-primary">BUSCAR</button>
            </div>

            <div class="col-md-1">
            </div>
            <?php if(isset($_POST['vehiculo'])) { ?>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-success btn-xs" id="imprime">IMPRIMIR</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success btn-xs" id="pdf" hidden>PDF</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success btn-xs" id="excel" hidden>EXCEL</button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </form>
</div>

<script>
$('#daterange').daterangepicker();


$('#daterange').daterangepicker({
    "timePicker": true,
    "timePicker24Hour": true,
    "startDate": "<?php if(isset($_POST['from'])) echo $_POST['from'];  else echo date('m/d/Y')?>",
    "endDate": "<?php if(isset($_POST['to'])) echo $_POST['to']; else echo date('m/d/Y')?>",

    "locale": {
        "format": "MM/DD/YYYY HH:mm",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Inicio",
        "toLabel": "Fin",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Dom",
            "Lun",
            "Mar",
            "Mie",
            "Jue",
            "Vie",
            "Sab"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
        "firstDay": 1
    }

});



$("#buscar").click(function() {

    fecharango = $('#daterange').val();
    fechas = fecharango.split("-");
    vini = fechas[0].trim();
    vfin = fechas[1].trim();
    $('#from').val(vini);
    $('#to').val(vfin);

});


$('#imprime').click(function() {
    var divToPrint = document.getElementById("info");
    newWin = window.open("");
    newWin.document.write(
        '<html><head><title>Histórico de posiciones</title><link rel="stylesheet" href="/librerias/bootstrap/css/bootstrap.min.css"></head><body>'
        );
    newWin.document.write(
        '<p><strong>Reporte Histórico de posiciones, Fecha: <?php echo date('d/m/Y H:i:s',time())?></strong></p>'
        );
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
});
</script>



<div id="editor"></div>

<script>
function descargapdf() {

    var pdf = new jsPDF('l', 'pt', 'a4');
    source = $('#infodata')[0];
    pdf.cellInitialize();
    pdf.setFontSize(10);
    specialElementHandlers = {
        '#editor': function(element, renderer) {
            // true = "handled elsewhere, bypass text extraction"
            return true
        }
    };
    margins = {
        top: 20,
        bottom: 20,
        left: 20
    };

    pdf.fromHTML(
        source, // HTML string or DOM elem ref.
        margins.left, // x coord
        margins.top, { // y coord
            'elementHandlers': specialElementHandlers
        },


        function(dispose) {
            pdf.save('archivo.pdf');
        }, margins);


}
</script>

<script src="scripts/jspdf.debug.js"></script>


<script type="text/javascript">
$("#pdf").click(function() {

    fecharango = $('#daterange').val();
    fechas = fecharango.split("-");
    vini = fechas[0].trim();
    vfin = fechas[1].trim();
    var vtxr = document.getElementById("txr").value;

    var url = "posiciones/for_pdf.php?pdf=1";
    url = url + "&vehiculo=<?php if(isset($_POST["vehiculo"])) echo $_POST["vehiculo"]; ?>";
    url = url + "&txr=" + vtxr;
    url = url + "&from=" + vini;
    url = url + "&to=" + vfin;
    window.open(url);

});

$("#excel").click(function() {

    fecharango = $('#daterange').val();
    fechas = fecharango.split("-");
    vini = fechas[0].trim();
    vfin = fechas[1].trim();

    var url = "posiciones/for_excel.php?excel=1";
    url = url + "&vehiculo=<?php if(isset($_POST["vehiculo"])) echo $_POST["vehiculo"]; ?>";
    url = url + "&from=" + vini;
    url = url + "&to=" + vfin;
    window.open(url);

});
</script>