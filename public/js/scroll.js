$(document).ready(function() {
    $('ul.pagination').hide();
    $('.scroll').jscroll({
        autoTrigger: true,
        loadingHtml: '<div class="col-xs-12 text-center"><i class="zmdi zmdi-spinner zmdi-hc-spin zmdi-hc-3x"></i></div>',
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: '.scroll',
        callback: function() {
            $('ul.pagination').remove();
        }
    });
});