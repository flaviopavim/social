$(function(){
    $('#new-post').click(function(){
        $('#modal').fadeIn();
    });
    $('.close-modal').click(function(){
        $('#modal').fadeOut();
    });


})
$(window).on('click', function(e) {
    if (e.target == modal) {
        $('#modal').fadeOut();
    }
}).on('keyup', function(e) {
    if (e.keyCode == 27) {
        $('#modal').fadeOut();
    }
});