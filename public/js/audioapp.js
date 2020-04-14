$(function () {
    let baseurl_detail = "/music/";
    let baseurl_search_artist = "/music/search?artist=";

    function set_info(tag) {
        $("a#audio_detail").attr("href", baseurl_detail + tag.attr("id") ?? "");
        $("a#audio_detail").attr("music_id", tag.attr("id") ?? "");
        $("#audio_artist").attr(
            "href",
            baseurl_search_artist +
                encodeURIComponent(tag.attr("audio_artist")) ?? ""
        );
        $("#audio_artist").text(tag.attr("audio_artist"));
        $("#audio_title").text(tag.attr("audio_title"));
    }

    // Setup the player to autoplay the next track
    var a = audiojs.createAll({
        trackEnded: function () {
            var next = $("ol li.playing").next();
            if (!next.length) next = $("ol li").first();
            next.addClass("playing").siblings().removeClass("playing");
            audio.load($("a", next).attr("data-src"));
            audio.play();
            set_info($("a", next));
        },
    });

    // Load in the first track
    var audio = a[0];
    var first = $("ol a.musicitem").attr("data-src");
    $("ol li").first().addClass("playing");
    audio.load(first);
    set_info($("ol a"));

    // Load in a track on click
    $("ol li a.musicitem").click(function (e) {
        e.preventDefault();
        $(this).parent().addClass("playing").siblings().removeClass("playing");
        audio.load($(this).attr("data-src"));
        audio.play();
        set_info($(this));
    });
    // Keyboard shortcuts
    $(document).keydown(function (e) {
        var unicode = e.charCode ? e.charCode : e.keyCode;
        // right arrow
        if (unicode == 39) {
            var next = $("li.playing").next();
            if (!next.length) next = $("ol li").first();
            next.find("a.musicitem").click();
            // back arrow
        } else if (unicode == 37) {
            var prev = $("li.playing").prev();
            if (!prev.length) prev = $("ol li").last();
            prev.find("a.musicitem").click();
            // spacebar
        } else if (unicode == 32) {
            audio.playPause();
        }
    });

    $("button.queue").click(function (e) {
        add_to_queue(
            $(this).parent().find("a.musicitem").attr("id"),
            $(this).parent().html()
        );
    });
});

function add_to_queue(music_id, datasrc) {
    key = "queue";

    datalist = JSON.parse(localStorage.getItem(key));
    console.log(datalist);
    if (datalist === null) {
        datalist = {};
    }
    datalist[music_id] = datasrc;
    localStorage.setItem(key, JSON.stringify(datalist));

    return true;
}

function remove_from_queue(music_id) {
    key = "queue";

    datalist = JSON.parse(localStorage.getItem(key));
    console.log(datalist);
    delete datalist[music_id];
    localStorage.setItem(key, JSON.stringify(datalist));

    return true;
}
