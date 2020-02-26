$(document).ready(function() {
    const $container = $("div#trick_videos");
    let index = $container.find(":input").length;
    $("#add_video").click(function(e) {
        addVideo($container);
        e.preventDefault();
        return false;
    });
    if (index === 0) {
        addVideo($container);
    } else {
        $container.children("div").each(function() {
            addDeleteLink($(this));
        });
    }
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
});
