$(window).on('load resize', function(){
    //windowの幅をxに代入
    var x = $(window).width();
    //windowの分岐幅をyに代入
    var y = 768;
    if (x < y) 
    {
        $('#resizeInformationUl').addClass('information-ul-xs').removeClass('information-ul');
        $('.resize-information-span1').addClass('information-xs-span1').removeClass('information-span1');
        $('.resize-information-span2').addClass('information-xs-span2').removeClass('information-span2');
    }else
    {
        $('#resizeInformationUl').addClass('information-ul').removeClass('information-ul-xs');
        $('.resize-information-span1').addClass('information-span1').removeClass('information-xs-span1');
        $('.resize-information-span2').addClass('information-span2').removeClass('information-xs-span2');
    }
});