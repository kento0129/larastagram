$(function() {
    var jqxhr;
    //フォローするを選択した際の処理
    $(document).on('click', '.follow-ajax', function() {
        if (jqxhr) {
            return;
        }
        jqxhr = $.ajax({
            url: "/followers/ajax/posts/"+$(this).val(),
            type: 'get',
            dataType: "html",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data){ //ajaxの通信に成功した場合
                $(".follow-ajax[value='"+data+"']").remove();
                $(".followers-li[value='"+data+"']").children('.followers-status').append("<button class='btn btn-outline-dark common-btn follow-now-btn follow-now-ajax'>フォロー中</button>").val(data);
                $(".followers-li[value='"+data+"']").find('.follow-now-ajax').val(data);
                jqxhr = '';
            }
        })
    });
    //フォロー中を選択した際の処理
    $(document).on('click', '.follow-now-ajax', function() {
        if (jqxhr) {
            return;
        }
        jqxhr = $.ajax({
            url: "/followers/ajax/delete/"+$(this).val(),
            type: 'get',
            dataType: "html",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data){ //ajaxの通信に成功した場合
                $(".follow-now-ajax[value='"+data+"']").remove();
                $(".followers-li[value='"+data+"']").children('.followers-status').append("<button class='btn btn-primary common-btn follow-btn follow-ajax'>フォローする</button>");
                $(".followers-li[value='"+data+"']").find('.follow-ajax').val(data);   
                jqxhr = '';
            }
        })
    });
    //いいね処理
    $(document).on('click', '.love', function() {
        if (jqxhr) {
            return;
        }
        jqxhr = $.ajax({
            url: "/likes/posts/"+$(this).data('value'),
            type: 'get',
            dataType: "html",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data){ //ajaxの通信に成功した場合
                var post = JSON.parse( data );
                var like_icon = $("#like-icon-post-"+post.post_id);
                var like_text = $("#like-text-post-"+post.post_id);
                like_icon.children('.love').remove();
                like_icon.append("<div class='loved hide-text' data-remote='true' rel='nofollow' data-value='" + post.like_id + "'>いいねを取り消す</div>");
                like_text.children().remove();
                like_text.append(post.text);
                jqxhr = '';
            }
        })
    });
    //いいね取消処理
    $(document).on('click', '.loved', function() {
        if (jqxhr) {
            return;
        }
        jqxhr = $.ajax({
            url: "/likes/delete/"+$(this).data('value'),
            type: 'get',
            dataType: "html",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data){ //ajaxの通信に成功した場合
                var post = JSON.parse( data );
                var like_icon = $("#like-icon-post-"+post.post_id);
                var like_text = $("#like-text-post-"+post.post_id);
                like_icon.children('.loved').remove();
                like_icon.append("<div class='love hide-text' data-remote='true' rel='nofollow' data-value='" + post.post_id + "'>いいね</div>");
                like_text.children().remove();
                like_text.append(post.text);  
                jqxhr = '';
            }
        })
    });
});