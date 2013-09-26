/**
 * Created with JetBrains PhpStorm.
 * User: lukas
 * Date: 26.09.13
 * Time: 17:40
 * To change this template use File | Settings | File Templates.
 */

function updateStatus(){
    $.ajax({
        url: 'scripts.php?call=status',
        dataType: 'json',
        success: function (data) {
            console.log(data);
            if (data.crashplan) {
                $('#crashplan-status .label').removeClass('label-danger').addClass('label-success');
                $('#crashplan-status .glyphicon').removeClass('glyphicon-warning-sign').addClass('glyphicon-check');
                $('#crashplan-status .not').hide();
            } else {
                $('#crashplan-status .label').addClass('label-danger').removeClass('label-success');
                $('#crashplan-status .glyphicon').addClass('glyphicon-warning-sign').removeClass('glyphicon-check');
                $('#crashplan-status .not').show();
            }
            if (data.hdd) {
                $('#hdd-status .label').removeClass('label-danger').addClass('label-success');
                $('#hdd-status .glyphicon').removeClass('glyphicon-warning-sign').addClass('glyphicon-check');
                $('#hdd-status .not').hide();
            } else {
                $('#hdd-status .label').addClass('label-danger').removeClass('label-success');
                $('#hdd-status .glyphicon').addClass('glyphicon-warning-sign').removeClass('glyphicon-check');
                $('#hdd-status .not').show();
            }
            if ($('#debug').length > 0) {
                $('#debug').text(data.debug);
            }
        }
    });
}

$(document).ready(function () {

    $('#update').click(function () {
        $.ajax({
            url: 'scripts.php?call=pull',
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $(".alert-update").alert('close')
                $('h4.debug').after('<div style="text-align:left;" class="alert alert-dismissable alert-' + data.status + ' alert-update">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    data.return + '</div>');
                if (data.status == 'success') {
                    $('.btn').prop('disabled', true);
                    setTimeout(function () {
                        window.location.reload()
                    }, 5000);
                }else{
                    updateStatus();
                }
            }
        });
    });
    $('#remove-hdd').click(function () {
        $.ajax({
            url: 'scripts.php?call=removeHDD',
            dataType: 'json',
            success: function (data) {
                console.log(data);
                updateStatus();
                $(".alert-hdd").alert('close')
                $('h4#actions').after('<div style="text-align:left;" class="alert alert-dismissable alert-' + data.status + ' alert-hdd">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    data.return + '</div>');
            }
        });
    });
    $(document.body).on('hidden.bs.modal', function () {
        $('#modal').removeData('bs.modal')
    });
});