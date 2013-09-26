<!DOCTYPE html>
<html>

<head>
    <title>Backup-Maschine</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="container">
    <div id="controls" class="col-md-4 col-md-offset-4">
        <h1 class="text-center">Backup-Maschine</h1>
        <h4 class="text-center">Status Backup</h4>

        <p class="lead" id="crashplan-status">
            <span class="label label-danger"><span class="glyphicon glyphicon-warning-sign"></span></span>  Crashplan läuft<strong class="not">nicht</strong>.
        </p>

        <p class="lead" id="hdd-status">
            <span class="label label-danger"><span class="glyphicon glyphicon-warning-sign"></span></span>  Festplatte<strong class="not"> nicht</strong> angeschlossen.
        </p>
        <h4 id="actions" class="text-center">Aktionen</h4>
        <button id="remove-hdd" class="btn btn-default btn-block">Festplatte auswerfen</button>
        <button data-toggle="modal" data-target="#modal" data-remote="modals/rebootWarning.html" class="btn btn-warning btn-block">Neustart</button>
        <button data-toggle="modal" data-target="#modal" data-remote="modals/shutdownWarning.html" class="btn btn-danger btn-block">Herunterfahren</button>
        <?php if (isset($_GET['debug'])) { ?>
            <h4 class="debug text-center">Debug</h4>
            <button id="update" class="btn btn-default btn-block">Update</button>
            <pre id="debug" style="text-align: left;"></pre>
        <?php } ?>
    </div>
</div>

<!-- Modals-->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"></div>
<div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"></div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/backup.js"></script>
</body>

</html>