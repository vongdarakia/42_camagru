
function createUserUploadBox(imgFile, photosDiv) {
    let img = new Image();
    let postDir = document.getElementById('post-dir').getAttribute('value');
    let boxSize = 150;
    img.src = postDir + imgFile;

    img.onload = function() {
        // console.log(this.width + " " + this.height);
        let imgClass = "";

        // Makes sure to fill up the box when image sizes are not
        // a 1:1 ratio.
        if (this.height < this.width) {
            imgClass = "short-height";
            offset = -(this.width * boxSize / this.height - boxSize) / 2;
            style = "left: " + offset + "px;";
        } else if (this.width < this.height) {
            imgClass = "short-width";
            offset =  -(this.height * boxSize / this.width - boxSize) / 2;
            style = "top: " + offset + "px;";
        } else {
            imgClass = "perfect-box";
        }

        let box = document.createElement('div');
        let crop = document.createElement('div');
        let photo = document.createElement('img');

        box.className = 'user-upload-box';
        crop.className = 'crop';
        photo.className = imgClass;
        photo.style = style;
        photo.src = img.src;

        crop.appendChild(photo);
        box.appendChild(crop);

        photosDiv.prepend(box);
    };
}

function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
    }));
}
// http://viralpatel.net/blogs/ajax-style-file-uploading-using-hidden-iframe/
function fileUpload(form, action_url) {
    var file = document.querySelector('#files > input[type="file"]').files[0];
    getBase64(file);
    // console.log(form);
    // Create the iframe...
    var iframe = document.createElement("iframe");
    iframe.setAttribute("id", "upload_iframe");
    iframe.setAttribute("name", "upload_iframe");
    iframe.setAttribute("width", "0");
    iframe.setAttribute("height", "0");
    iframe.setAttribute("border", "0");
    iframe.setAttribute("style", "width: 0; height: 0; border: none;");

    // Add to document...
    // form.parentNode.appendChild(iframe);
    document.getElementById('upload').appendChild(iframe);
    window.frames['upload_iframe'].name = "upload_iframe";

    iframeId = document.getElementById("upload_iframe");

    // Add event...
    var eventHandler = function () {

            if (iframeId.detachEvent) iframeId.detachEvent("onload", eventHandler);
            else iframeId.removeEventListener("load", eventHandler, false);

            // Message from server...
            if (iframeId.contentDocument) {
                content = iframeId.contentDocument.body.innerHTML;
            } else if (iframeId.contentWindow) {
                content = iframeId.contentWindow.document.body.innerHTML;
            } else if (iframeId.document) {
                content = iframeId.document.body.innerHTML;
            }

            try {
                if (content.indexOf("Error") >= 0) {
                    console.log(content);
                } else {
                    document.getElementById('photo-frame').contentWindow.location.reload(true);
                    // let cameraCanvas = document.getElementById('camera-canvas');
                    // let dataUrl = cameraCanvas.toDataURL('image/png');
                    // prevCamImg.setAttribute("src", dataUrl);
                    // camPhoto.setAttribute('value', dataUrl);

                    // dataUrl = stickerCanvas.toDataURL('image/png');
                    // prevStkrImg.setAttribute("src", dataUrl);
                    // // console.log(document.getElementById('hidden-img'));

                    // let camData = document.getElementById('cam-photo').getAttribute("value");
                    // let stkerData = document.getElementById('sticker-photo').getAttribute("value");
                }
            } catch(err) {
                console.log(err);
            }
            
            // document.getElementById('upload').innerHTML = content;

            // Del the iframe...
            setTimeout('iframeId.parentNode.removeChild(iframeId)', 250);
        }

    if (iframeId.addEventListener) iframeId.addEventListener("load", eventHandler, true);
    if (iframeId.attachEvent) iframeId.attachEvent("onload", eventHandler);

    // Set properties of form...
    form.setAttribute("target", "upload_iframe");
    form.setAttribute("action", action_url);
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");

    // Submit the form...
    form.submit();
    

    // document.getElementById('upload').innerHTML = "Uploading...";
}

function changeSticker(radio) {
    if (radio.value == "Patrick Gasp") {
        loadStickerImage("patrick-gasp.png", 20, 120, 150 * .75, 227 * .75);
    } else if (radio.value == "Mustache and Sunglasses") {
        loadStickerImage("mustache-glasses.png", 125, 75, 150, 150);
    } else if (radio.value == "Doge") {
        loadStickerImage("doge.png", 20, 140, 150, 150);
    }
}

function loadStickerImage(imgFile, x, y, w, h) {
    let stickerCanvas = document.getElementById('sticker-canvas');
    let stickerContext = stickerCanvas.getContext('2d');
    let stickerImg = document.getElementById('sticker-img');
    let stickerPhoto = document.getElementById('sticker-photo');
    let baseImg = new Image();
    let imgDir = document.getElementById('img-dir').getAttribute('value');
    
    baseImg.src = imgDir + imgFile;
    baseImg.onload = function() {
        // console.log(this.width + " " + this.height);
        stickerContext.clearRect(0, 0, 400, 300);
        // Draws the sticker at this small size onto another canvas so that we can
        // create an image out of it, which will be used to superimpose onto the
        // camera image.
        stickerContext.drawImage(baseImg, x, y, w, h);

        let dataUrl = stickerCanvas.toDataURL('image/png');
        stickerImg.setAttribute("src", dataUrl);
        stickerPhoto.setAttribute('value', dataUrl);
    };
}

(function() {
    let camera = document.getElementById('camera'),
    cameraCanvas = document.getElementById('camera-canvas'),
    stickerCanvas = document.getElementById('sticker-canvas'),
    cameraContext = cameraCanvas.getContext('2d'),
    prevCamImg = document.getElementById('preview-cam-img'),      // For preview
    prevStkrImg = document.getElementById('preview-sticker-img'), // For preview
    stickerImg = document.getElementById('sticker-img'),          // For view
    camPhoto = document.getElementById('cam-photo'),              // For conversion
    stickerPhoto = document.getElementById('sticker-photo'),      // For conversion

    vendorUrl = window.URL || window.webkitURL;

    navigator.getMedia = navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia;
    navigator.getMedia({
        video: true,
        audio: false
    }, function(stream) {
        camera.src = vendorUrl.createObjectURL(stream);
        camera.play();
    }, function(error) {
        alert(error);
    });

    // Flips the canvas, because originally, the camera is reversed.
    cameraContext.translate(400, 0);
    cameraContext.scale(-1, 1);

    loadStickerImage("mustache-glasses.png", 125, 75, 150, 150);

    document.getElementById("btn-capture").addEventListener('click', function() {
        let previewWrapper = document.getElementById('preview-wrapper');
        let formPost = document.getElementById('form-post');

        previewWrapper.classList.remove("hidden");
        
        // Draws the camera onto the canvas.
        cameraContext.drawImage(camera, 0, 0, 400, 300);

        // Manipulate canvas here

        let dataUrl = cameraCanvas.toDataURL('image/png');
        prevCamImg.setAttribute("src", dataUrl);
        camPhoto.setAttribute('value', dataUrl);

        dataUrl = stickerCanvas.toDataURL('image/png');
        prevStkrImg.setAttribute("src", dataUrl);
        // fileUpload(formPost, '../actions/post.php');

        let camData = document.getElementById('cam-photo').getAttribute("value");
        let stkerData = document.getElementById('sticker-photo').getAttribute("value");
        camData = camData;
        stkerData = stkerData;

        let description = document.getElementById('description').getAttribute("value");
        if (description == null) {
            description = "";
        }

        let title = document.getElementById('title').getAttribute("value");
        if (title == null) {
            title = "";
        }
        ajax({
            url: "/camagru/actions/post.php",
            data: {
                email: document.getElementById('email').getAttribute("value"),
                title: title,
                description: description,
                camImg: camData,
                stickerImg: stkerData
            },
            method: "post",
            success: function(res) {
                console.log(res);
                // console.log(stkerData);
                console.log("SUCCESS");
                createUserUploadBox(res, document.getElementById('photos'));
            },
            error: function(err) {
                // alert(err);
                console.log("ERROR");
            }
        });
//         var file = document.querySelector('#files > input[type="file"]').files[0];
// getBase64(file);
        // console.log(document.getElementById('hidden-img'));

        // let camData = document.getElementById('cam-photo').getAttribute("value");
        // let stkerData = document.getElementById('sticker-photo').getAttribute("value");
        // camData = b64EncodeUnicode(camData);
        // stkerData = b64EncodeUnicode(stkerData);
        // ajax({
        //     url: "/camagru/actions/post.php",
        //     data: {
        //         email: document.getElementById('email').getAttribute("value"),
        //         title: document.getElementById('title').getAttribute("value"),
        //         description: document.getElementById('description').getAttribute("value"),
        //         camImg: camData,
        //         stickerImg: stkerData
        //     },
        //     success: function(res) {
        //         // console.log(res);
        //         console.log("SUCCESS");
        //     },
        //     error: function(err) {
        //         // alert(err);
        //         console.log("ERROR");
        //     }
        // });
    });
})();