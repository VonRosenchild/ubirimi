$(document).ready(function () {
    $('#slides').slides({
        preload:true,
        preloadImage:'img/loading.gif',
        play:5000,
        pause:2500,
        hoverPause:true,
        animationStart:function (current) {
            $('.caption').animate({
                bottom:-35
            }, 100);
            if (window.console && console.log) {
                // example return of current slide number
                console.log('animationStart on slide: ', current);
            }

        },
        animationComplete:function (current) {
            $('.caption').animate({
                bottom:0
            }, 200);
            if (window.console && console.log) {
                // example return of current slide number
                console.log('animationComplete on slide: ', current);
            }
        },
        slidesLoaded:function () {
            $('.caption').animate({
                bottom:0
            }, 200);
        }
    });
    for (var i = 1; i <= 6; i++) {
        $("#single_" + i).fancybox({
            helpers:{
                title:{
                    type:'float'
                }
            }
        });
    }
});