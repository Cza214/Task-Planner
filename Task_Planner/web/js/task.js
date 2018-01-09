function loadComments(){
    var id = window.location.pathname.split('/')[3];
    $.ajax({
        url: "/comment/show/"+id
    }).done(function(result){
        $('.comments').html(result);
    })
}

$(function () {
    loadComments();
});