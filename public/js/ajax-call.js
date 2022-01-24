function meGusta(commentId) {
    let Ruta = Routing.generate('Likes');
    $.ajax({
        type: 'POST',
        url: Ruta,
        data: ({commentId: commentId}),
        async: true,
        dataType: 'json',
        success: function (data) {
            if(!data['exito']) {
                ShowAlert('Duplicidad', 'Ya le dio me gusta a este comentario', 'danger', commentId);
            } else {
                $("#error-"+commentId).hide();
                $("#likes-"+commentId).html(data['likes']);
                $("#dislikes-"+commentId).html(data['dislikes']);
            }
        }
    });
}

function ShowAlert(msg_title, msg_body, msg_type, commentId) {
    var AlertMsg = $("#error-"+commentId);
    $(AlertMsg).find('strong').html(msg_title);
    $(AlertMsg).find('p').html(msg_body);
    $(AlertMsg).removeAttr('class');
    $(AlertMsg).addClass('alert alert-' + msg_type);
    $(AlertMsg).show();
}

function dislike(commentId) {
    let Ruta = Routing.generate('Dislike');
    $.ajax({
        type: 'POST',
        url: Ruta,
        data: ({commentId: commentId}),
        async: true,
        dataType: 'json',
        success: function(data) {
            if(!data['exito']) {
                ShowAlert('Duplicidad', 'Ya le dio no me gusta a este comentario', 'danger', commentId);
            } else {
                $("#error-"+commentId).hide();
                $("#dislikes-"+commentId).html(data['dislikes']);
                $("#likes-"+commentId).html(data['likes']);
            }
        }
    })
}