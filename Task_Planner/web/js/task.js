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

    $('form input[type=submit]').on('click',function(event){
        var text = $('form input[type=text]');
        if(text.length < 6 || text > 250){
            event.preventDefault();
            $('.error').html('Comment is too short or too long. Must has 6 - 250 characters.');
        }
    });
});