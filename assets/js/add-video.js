$(document).ready(function() {
    function addVideo($container) {
        const template = $container.attr("data-prototype")
            .replace(/__name__label__/g, "")
            .replace(/__name__/g, index);
        const $prototype = $(template);
        addDeleteLink($prototype);
        $container.append($prototype);
        index++;
    }
    function addDeleteLink($prototype) {
        const $deleteLink = $("<a href=\"javascript:void(0)\" class=\"btn btn-danger\">Supprimer</a>");
        $prototype.append($deleteLink);
        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault();
            return false;
        });
    }
    const trickVideo = $("div#trick_videos");
    let index = trickVideo.find(":input").length;
    $("#add_video").click(function(e) {
        addVideo(trickVideo);
        e.preventDefault();
        return false;
    });
    if (index === 0) {
        addVideo(trickVideo);
    } else {
        trickVideo.children("div").each(function() {
            addDeleteLink($(this));
        });
    }

});
