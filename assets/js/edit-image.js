$(document).ready(function() {
    $.each($('.image-edit'),function (index) {
        console.log('trick_images_' + index);
        const $containerImg = $('#trick_images_' + index);
        let indexImg = $containerImg.find(':input').length;
        $('#add_image').click(function(e) {
            addImage($containerImg);
            e.preventDefault();
            return false;
        });
        if (indexImg === 0) {
            addImage($containerImg);
        } else {
            $containerImg.children('div').each(function() {
                addDeleteLink($(this), index);
            });
        }
        function addImage($containerImg) {
            const template = $('<div id="trick_image_'+(index + 1)+'"><div><input type="file" id="trick_images_'+(index + 1)+'_file" name="trick[images]['+(index + 1)+'][file]"' +
                ' required="required"></div></div>');

            const $prototype = $(template);
            addDeleteLink($prototype, index);
            $containerImg.append($prototype);
            indexImg++;
        }
    });


    function addDeleteLink($prototype, index) {
        console.log(index);
        const $deleteLink = $('<a href="javascript:void(0)" class="btn btn-danger">Supprimer</a>');
        $prototype.append($deleteLink);
        console.log('.previous-img-'+index, $('.previous-img-'+index));
        $deleteLink.click(function(e) {
            $('.previous-img-'+index).remove();
            e.preventDefault();
            return false;
        });
    }
});
