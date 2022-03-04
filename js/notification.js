$(function(){
    $('#notification').animate({
        bottom: '10px'
    }, 500,function(){
        setTimeout(function(){
            $('#notification').animate({
                bottom: '-140px'
            }, 500);
        }, 5000);
    });
    $('#notification').mouseleave(function(){
        $(this).animate({
            bottom: '-140px'
        }, 500);
    });

});