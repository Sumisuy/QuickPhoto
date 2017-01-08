var CSRF_TOKEN;

$(document).ready(function() {

    CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $('#file-uploader-dropbox').upload({
        action: "/upload-file",
        autoUpload: true,
        postData: {
            _token: CSRF_TOKEN
        }
    }).on("filecomplete.upload", function(e, file, response) {
        console.log(response);
    });

    $('.fs-upload-input').attr('name', 'images[]');

});