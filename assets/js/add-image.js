$(document).ready(function() {
    const $containerImg = $("div#trick_images");
    let indexImg = $containerImg.find(":input").length;
    $("#add_image").click(function(e) {
        addImage($containerImg);
        e.preventDefault();
        return false;
    });
    if (indexImg === 0) {
        addImage($containerImg);
    } else {
        $containerImg.children("div").each(function() {
            addDeleteLink($(this));
        });
    }
    function addImage($containerImg) {
        const template = $containerImg.attr("data-prototype")
            .replace(/__name__label__/g, "")
            .replace(/__name__/g, indexImg);

        const $prototype = $(template);
        addDeleteLink($prototype);
        $containerImg.append($prototype);
        indexImg++;
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
