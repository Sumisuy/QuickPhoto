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
        var img_address = JSON.parse(JSON.parse(response).details).image_path;
        var container = $('#image-list ul');
        var img_container = document.createElement("div");
        var img = document.createElement("img");
        img_container.className = 'image-list-item-container';
        img.className = 'image-list-item';
        img.setAttribute('src', img_address);
        img.setAttribute('width', '100px');
        img_container.append(img);
        container.append(img_container);
    });

    $('.fs-upload-input').attr('name', 'images[]');

});