<!DOCTYPE html>
<html>
  <head>
    <title>Backup-Maschine</title>
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
  <div class="container" style="text-align:center;">
      <div id="controls" class="col-md-4 col-md-offset-4">
      <h1>Backup-Maschine</h1>
      <h4>Status Backup</h4>
      <pre id="status"></pre>
      <h4 id="actions" >Aktionen</h4>
      <a data-toggle="modal" data-target="#modal"  href="modals/rebootWarning.html" class="btn btn-warning btn-block">Neustart</a>
      <a data-toggle="modal" data-target="#modal"  href="modals/shutdownWarning.html" class="btn btn-danger btn-block">Herunterfahren</a>
      <?php if(isset($_GET['debug'])){ ?>
      <h4 class="debug" >Debug</h4>
      <button id="update" class="btn btn-default btn-block">Update</button>
      <pre id="debug"></pre>
      <?php } ?>
</div>
  </div>
 <!-- Modal for Shutdown-->
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">  </div><!-- /.modal -->
  <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">  </div><!-- /.modal -->
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script>

    $(document).ready(function(){
        $.ajax({
        url: 'scripts.php?call=status',
        dataType: 'json',
        success: function(data){
            console.log(data);
            $('#status').html(data.return);
            if($('#debug').length>0){
            $('#debug').html(data.debug);
            }
        }
        });
    $('#update').click(function(){
        $.ajax({
        url: 'scripts.php?call=pull',
        dataType: 'json',
        success: function(data){
            $(".alert-update").alert('close')
            $('h4.debug').after('<div style="text-align:left;" class="alert alert-dismissable alert-success alert-update">' + 
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + 
                            data.return + '</div>');
            }
        });
    });
$(document.body).on('hidden.bs.modal', function () {
    $('#modal').removeData('bs.modal')
});
    });
    </script>
  </body>
</html>