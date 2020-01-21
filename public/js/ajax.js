$(function() {
    //フォローするを選択した際の処理
    $(document).on('click', '.follow-ajax', function() {
        $.ajax({
            url: "/followers/ajax/posts/"+$(this).val(),
            type: 'get',
            dataType: "html",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .done(function(data){ //ajaxの通信に成功した場合
            $(".follow-ajax[value='"+data+"']").remove();
            $(".followers-li[value='"+data+"']").children('.followers-status').append("<button class='btn btn-outline-dark common-btn follow-now-btn follow-now-ajax'>フォロー中</button>").val(data);
            $(".followers-li[value='"+data+"']").find('.follow-now-ajax').val(data);
        })
    });
    //フォロー中を選択した際の処理
    $(document).on('click', '.follow-now-ajax', function() {
        $.ajax({
            url: "/followers/ajax/delete/"+$(this).val(),
            type: 'get',
            dataType: "html",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .done(function(data){ //ajaxの通信に成功した場合
            $(".follow-now-ajax[value='"+data+"']").remove();
            $(".followers-li[value='"+data+"']").children('.followers-status').append("<button class='btn btn-primary common-btn follow-btn follow-ajax'>フォローする</button>");
            $(".followers-li[value='"+data+"']").find('.follow-ajax').val(data);
        })
    });
});