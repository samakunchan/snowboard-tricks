$(document).ready(function() {
    let count = [];
    $.each($(".image-edit"),function (index) {
        const trick_images_loop = $("#trick_images_" + index);

        let indexImg = trick_images_loop.find(":input").length;
        if (indexImg === 0) {
            addImagePrevious($("#trick_images_0"), index);
        } else {
            trick_images_loop.children("div").each(function() {
                addDeletePreviousLink($(this), index);
            });
        }
        count.push($(".image-edit"));
    });



    $("#add_image").click(function(e) {
        addNewImage($(".form-image"));
        addNewDeleteLink($(".image-edit"));
        e.preventDefault();
        return false;
    });

    function addNewImage(container) {
        const template = $(""+"<div class=\"previous-img-"+count.length+"\"></div>");
        const subTemplate = $("<div id=\"trick_image_"+count.length+"\" class=\"image-edit\">" +
            "<div>" +
            "<input type=\"file\" id=\"trick_images_"+count.length+"_file\"" + " name=\"trick[images]["+count.length+"][file]\"" + " required=\"required\">" +
            "</div>" +
            "</div>");
        template.append(subTemplate);
        container.append(template);
        count.push(subTemplate);
    }

    function addNewDeleteLink($prototype) {
        const $deleteLink = $("<a href=\"javascript:void(0)\" class=\"btn btn-danger\">Supprimer</a>");
        $prototype[count.length-1].append($deleteLink[0]);
        $deleteLink.click(function(e) {
            $(".previous-img-"+ (count.length-1)).remove();
            e.preventDefault();
            const start = count.length-1;
            count.splice(start, 1);
            return false;
        });
    }

    function addImagePrevious($containerImg, index) {
        const template = $("<div id=\"trick_image_"+(index + 1)+"\"><div><input type=\"file\" id=\"trick_images_"+(index + 1)+"_file\" name=\"trick[images]["+(index + 1)+"][file]\"" + " required=\"required\"></div></div>");

        const $prototype = $(template);
        addDeletePreviousLink($prototype, index);
        $containerImg.append($prototype);
    }
    function addDeletePreviousLink($prototype, index) {
        const $deleteLink = $("<a href=\"javascript:void(0)\" class=\"btn btn-danger\">Supprimer</a>");
        $prototype.append($deleteLink);
        $deleteLink.click(function(e) {
            $(".previous-img-"+index).remove();
            e.preventDefault();
            const start = count.length-1;
            count.splice(start, 1);
            return false;
        });
    }
});
