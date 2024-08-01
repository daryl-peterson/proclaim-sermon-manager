var $j = jQuery.noConflict();

// $j is now an alias to the jQuery function; creating the new alias is optional.
jQuery(document).ready(function ($) {

    function addFileUpload(selector, title, mime) {
        var field = selector.replace('_button', '') + "_ul";
        $(selector).click(function (e) {
            e.preventDefault();

            var file = wp.media({
                title: title,
                // mutiple: true if you want to upload multiple files at once
                multiple: true,

                library: {
                    //  I don't know why but the following two were already
                    // there even when I wasn't passing anything.
                    orderby: "date",
                    query: true,
                    post_mime_type: [mime] // pass all mimes in array
                },

            }).open()
                .on('select', function (e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    // org var uploaded_image = image.state().get('selection').first();

                    // var uploaded_file = file.state().get('selection');

                    var attachments = file.state().get('selection').map(
                        function (attachment) {

                            attachment.toJSON();
                            return attachment;

                        }
                    );

                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    console.log('HERE');
                    console.log(attachments);

                    var i;

                    for (i = 0; i < attachments.length; ++i) {
                        console.log(attachments[i]);
                        /*
                        var ctrl =
                        $hml= "<input id=\"$field_id\" name="$field_name" type="hidden" value="$id">
                        <li id="$li" name="$li" class="file-status ui-sortable-handle">
                            <span>File: <strong>$attachment->file</strong></span>
                            <a href="$attachment->url" target="_blank" rel="external">Download</a> /
                            <a href="#" data-id="$id" data-field="$field_id" data-li="$li" class="file-remove">Remove</a>
                        </li>
                        */
                    }

                });
        });
    }

    //$j('#drpsermon_date').inputmask({ mask: '99/99/9999', placeholder: 'mm/dd/yyyy', alias: 'datetime' });  //static mask
    //$j("#drpsermon_date").datepicker();

    addFileUpload('#drpsermon_notes_button', 'Sermon PDF Upload');

    /*
    $('.remove-file').on('click', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        console.log("ID : " + id);

    });
    */

    $(".file-remove").on("click", function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        var field = $(this).data('field');
        var ctrl = '#' + field;
        $(ctrl).val('');

        field = $(this).data('li');
        ctrl = '#' + field;
        $(ctrl).hide();

    });

});