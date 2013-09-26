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
        <p class="lead" id="crashplan-status" ><span class="label label-danger"><span class="glyphicon glyphicon-warning-sign"></span></span>  Crashplan läuft<strong class="not"> nicht</strong>.</p>
        <p  class="lead" id="hdd-status"><span class="label label-danger"><span class="glyphicon glyphicon-warning-sign"></span></span>  Festplatte<strong class="not"> nicht</strong> angeschlossen.</p>
      <h4 id="actions"  class="text-center">Aktionen</h4>
      <button id="remove-hdd" class="btn btn-default btn-block">RemoveHDD</button>
      <a data-toggle="modal" data-target="#modal"  href="modals/rebootWarning.html" class="btn btn-warning btn-block">Neustart</a>
      <a data-toggle="modal" data-target="#modal"  href="modals/shutdownWarning.html" class="btn btn-danger btn-block">Herunterfahren</a>
      <?php if(isset($_GET['debug'])){ ?>
      <h4 class="debug text-center" >Debug</h4>
      <button id="update" class="btn btn-default btn-block">Update</button>
      <pre id="debug" style="text-align: left;"></pre>
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
            if(data.crashplan){
                $('#crashplan-status .label').removeClass('label-danger').addClass('label-success');
                $('#crashplan-status .glyphicon').removeClass('glyphicon-warning-sign').addClass('glyphicon-check');
                $('#crashplan-status .not').hide();
            }else{
                $('#crashplan-status .label').addClass('label-danger').removeClass('label-success');
                $('#crashplan-status .glyphicon').addClass('glyphicon-warning-sign').removeClass('glyphicon-check');
                $('#crashplan-status .not').show();
            }
            if(data.hdd){
                $('#hdd-status .label').removeClass('label-danger').addClass('label-success');
                $('#hdd-status .glyphicon').removeClass('glyphicon-warning-sign').addClass('glyphicon-check');
                $('#hdd-status .not').hide();
            }else{
                $('#hdd-status .label').addClass('label-danger').removeClass('label-success');
                $('#hdd-status .glyphicon').addClass('glyphicon-warning-sign').removeClass('glyphicon-check');
                $('#hdd-status .not').show();
            }
            if($('#debug').length>0){
            $('#debug').text(data.debug);
            }
        }
        });
    $('#update').click(function(){
        $.ajax({
        url: 'scripts.php?call=pull',
        dataType: 'json',
        success: function(data){
            console.log(data);
            $(".alert-update").alert('close')
            $('h4.debug').after('<div style="text-align:left;" class="alert alert-dismissable alert-'+ data.status + ' alert-update">' + 
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + 
                            data.return + '</div>');
            if(data.status == 'success'){
                $('.btn').prop('disabled', true);
                setTimeout(window.location.reload(),5000);
            }
            }
        });
    });
    $('#remove-hdd').click(function(){
        $.ajax({
        url: 'scripts.php?call=removeHDD',
        dataType: 'json',
        success: function(data){
            console.log(data);
            $(".alert-hdd").alert('close')
            $('h4#actions').after('<div style="text-align:left;" class="alert alert-dismissable alert-'+ data.status + ' alert-hdd">' + 
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