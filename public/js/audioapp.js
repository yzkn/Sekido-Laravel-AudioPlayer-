$(function() {
    let baseurl_detail = "/music/";
    let baseurl_search_artist = "/music/search?artist=";
    // Setup the player to autoplay the next track
    var a = audiojs.createAll({
        trackEnded: function() {
            var next = $("ol li.playing").next();
            if (!next.length) next = $("ol li").first();
            next.addClass("playing")
                .siblings()
                .removeClass("playing");
            audio.load($("a", next).attr("data-src"));
            audio.play();
            $("a#audio_detail").attr(
                "href",
                baseurl_detail + $("a", next).attr("id")
            );
            $("#audio_artist").attr(
                "href",
                baseurl_search_artist +
                    encodeURIComponent($("a", next).attr("audio_artist"))
            );
            $("#audio_artist").text($("a", next).attr("audio_artist"));
            $("#audio_title").text($("a", next).attr("audio_title"));
        }
    });

    // Load in the first track
    var audio = a[0];
    var first = $("ol a").attr("data-src");
    $("ol li")
        .first()
        .addClass("playing");
    audio.load(first);
    $("a#audio_detail").attr("href", baseurl_detail + $("ol a").attr("id"));
    $("#audio_artist").attr(
        "href",
        baseurl_search_artist +
            encodeURIComponent($("ol a").attr("audio_artist"))
    );
    $("#audio_artist").text($("ol a").attr("audio_artist"));
    $("#audio_title").text($("ol a").attr("audio_title"));

    // Load in a track on click
    $("ol li").click(function(e) {
        e.preventDefault();
        $(this)
            .addClass("playing")
            .siblings()
            .removeClass("playing");
        audio.load($("a", this).attr("data-src"));
        audio.play();
        $("a#audio_detail").attr(
            "href",
            baseurl_detail + $("a", this).attr("id")
        );
        $("#audio_artist").attr(
            "href",
            baseurl_search_artist +
                encodeURIComponent($("a", this).attr("audio_artist"))
        );
        $("#audio_artist").text($("a", this).attr("audio_artist"));
        $("#audio_title").text($("a", this).attr("audio_title"));
    });
    // Keyboard shortcuts
    $(document).keydown(function(e) {
        var unicode = e.charCode ? e.charCode : e.keyCode;
        // right arrow
        if (unicode == 39) {
            var next = $("li.playing").next();
            if (!next.length) next = $("ol li").first();
            next.click();
            // back arrow
        } else if (unicode == 37) {
            var prev = $("li.playing").prev();
            if (!prev.length) prev = $("ol li").last();
            prev.click();
            // spacebar
        } else if (unicode == 32) {
            audio.playPause();
        }
    });
});
