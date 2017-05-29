var HEIGHT = 300;
var WIDTH = 400;
var flipped = false; // Whether camera is flipped
var navigator;
var localStream;
var vendorUrl;

var cameraCanvas;
var cameraContext;
var cameraImg; // Image for the preview

var stickerCanvas;
var stickerContext;
var stickerImg; // Image for the preview

// hidden input used to upload by form, probably don't need this anymore
var cameraPhoto; 
var stickerPhoto;

var capturedWrapper;
var capturedCamImg;
var capturedStkrImg;

var currentSticker;

var imgDir;

var stickers = [
    {
        imgFile: "mustache-glasses.png",
        x: 125,
        y: 75,
        w: 150,
        h: 150
    },
    {
        imgFile: "patrick-gasp.png",
        x: 20,
        y: 120, 
        w: 150 * .75,
        h: 227 * .75
    },
    {
        imgFile: "doge.png",
        x: 20,
        y: 140, 
        w: 150,
        h: 150
    }
];


function createUserUploadBox(imgFile, photosDiv) {
    let img = new Image();
    let postDir = document.getElementById('post-dir').getAttribute('value');
    let boxSize = 150;
    img.src = postDir + imgFile;

    img.onload = function() {
        // console.log(this.width + " " + this.height);
        let imgClass = "";
        let offset = 0;

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
                    // capturedCamImg.setAttribute("src", dataUrl);
                    // cameraPhoto.setAttribute('value', dataUrl);

                    // dataUrl = stickerCanvas.toDataURL('image/png');
                    // capturedStkrImg.setAttribute("src", dataUrl);
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
    if (radio.value >= 0 && radio.value < stickers.length) {
        currentSticker = stickers[radio.value];
        loadStickerImage(currentSticker);
    }
}

function loadStickerImage(o, extra, callback) {
    let baseImg = new Image();
    
    baseImg.src = imgDir + o.imgFile;
    baseImg.onload = function() {
        let offsetW = 0;
        let offsetH = 0;
        let adjW = WIDTH;
        let adjH = HEIGHT;
        
        if (extra) {
            if (extra.adjW)
                adjW = extra.adjW;
            if (extra.adjH)
                adjH = extra.adjH;
            if (extra.offsetW) {
                offsetW = extra.offsetW;
            }
            if (extra.offsetH) {
                offsetH = extra.offsetH;
            }
        }

        console.log(adjW + " Width");
        console.log(adjH + " Height");

        stickerCanvas.width = adjW;
        stickerCanvas.height = adjH;
        // stickerContext = stickerCanvas.getContext('2d');
        stickerContext.clearRect(0, 0, adjW, adjH);
        // Draws the sticker at this small size onto another canvas so that we can
        // create an image out of it, which will be used to superimpose onto the
        // camera image.
        stickerContext.drawImage(baseImg, o.x + offsetW, o.y + offsetH, o.w, o.h);

        let dataUrl = stickerCanvas.toDataURL('image/png');
        stickerImg.setAttribute("src", dataUrl);
        stickerPhoto.setAttribute('value', dataUrl);
        if (callback) {
            callback();
        }
    };
}

function loadImageByUser() {

}

function drawToCanvas(img, canvas, x, y, width, height) {
    canvas.height = height;
    canvas.width = width;
    canvas.getContext('2d').drawImage(img, x, y, width, height);
}

function fileChange(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            let dataUrl = e.target.result;
            let img = new Image();

            img.src = e.target.result;
            img.onload = function() {
                console.log(this.width);
                let imgClass = "proportional";
                let style = "";
                let boxWidth = WIDTH;
                let boxHeight = HEIGHT;
                let adjustedWidth = WIDTH;
                let adjustedHeight = HEIGHT;
                let offsetW = 0;
                let offsetH = 0;

                if (flipped) {
                    cameraContext.translate(WIDTH, 0);
                    cameraContext.scale(-1, 1);
                    flipped = false;
                    console.log("flipping to reverse");
                }

                if (this.width < this.height || this.width == this.height) {
                    // imgClass = "fill-height";
                    adjustedWidth = this.width * boxHeight / this.height;
                    offsetW = -(adjustedWidth - boxWidth) / 2;
                    style = "left: " + offsetW + "px;";
                    drawToCanvas(img, cameraCanvas, 0, 0, adjustedWidth, HEIGHT);
                } else if (this.height < this.width) {
                    // imgClass = "fill-width";
                    adjustedHeight = this.height * boxWidth / this.width;
                    offsetH = -(adjustedHeight - boxHeight) / 2;
                    style = "top: " + offsetH + "px;";
                    drawToCanvas(img, cameraCanvas, 0, 0, WIDTH, adjustedHeight);
                }

                // loadStickerImage("mustache-glasses.png", 125 * this.width / boxWidth, 75 * this.height / boxHeight, 150, 150);
                // Readjust the image
                // loadStickerImage({
                //     imgFile: currentSticker.imgFile,
                //     x: 125 * adjustedWidth / boxWidth,
                //     y: 75 * adjustedHeight / boxHeight, 
                //     w: 150,
                //     h: 150,
                //     adjW: adjustedWidth,
                //     adjH: adjustedHeight,
                //     callback: function() {
                //         capture(style);
                //     }
                // });
                console.log((adjustedWidth +" "+ adjustedHeight));
                loadStickerImage(currentSticker, {
                    offsetW: -offsetW,
                    offsetH: -offsetH,
                    adjW: adjustedWidth,
                    adjH: adjustedHeight
                }, function() {
                    console.log('captured');
                    capture(style);
                });
                // dataUrl = cameraCanvas.toDataURL();
                // capturedCamImg.setAttribute("src", dataUrl);
                // // capturedCamImg.classList.add(imgClass);
                // capturedCamImg.style = style;
                // cameraPhoto.setAttribute('value', dataUrl);

                // // dataUrl = stickerCanvas.toDataURL('image/png');
                // // capturedStkrImg.setAttribute("src", dataUrl);
                // capture();
            }
            capturedWrapper.classList.remove("hidden");
        }

        reader.readAsDataURL(input.files[0]);
    }
}


function capture(style) {
    stickerImg.style = style;

    dataUrl = cameraCanvas.toDataURL('image/png');
    capturedCamImg.style = style;
    capturedCamImg.setAttribute("src", dataUrl);
    cameraPhoto.setAttribute('value', dataUrl);

    dataUrl = stickerCanvas.toDataURL('image/png');
    capturedStkrImg.style = style;
    capturedStkrImg.setAttribute("src", dataUrl);

    let camData = cameraPhoto.getAttribute("value");
    let stkerData = stickerPhoto.getAttribute("value");
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
}

function stopVideo() {
    localStream.getTracks()[0].stop();
    // camera.style = "display: none;";
    let vidWrapper = document.getElementById('video-wrapper');
    vidWrapper.classList.add('invisible');
}

function cameraCapture() {
    let capturedWrapper = document.getElementById('captured-wrapper');
    let formPost = document.getElementById('form-post');

    cameraCanvas.width = WIDTH;
    cameraCanvas.height = HEIGHT;

    // Needs to flip because camera is actually mirrored.
    // Also needs to be flipped every time the canvas width
    // and height are changed
    cameraContext.translate(WIDTH, 0);
    cameraContext.scale(-1, 1);
    flipped = true;

    // Draws the camera onto the canvas.
    cameraContext.drawImage(camera, 0, 0, WIDTH, HEIGHT);

    stickerCanvas.width = WIDTH;
    stickerCanvas.height = HEIGHT;
    loadStickerImage(currentSticker, null, function() {
        


        let dataUrl = cameraCanvas.toDataURL('image/png');
        capturedCamImg.setAttribute("src", dataUrl);
        cameraPhoto.setAttribute('value', dataUrl);

        dataUrl = stickerCanvas.toDataURL('image/png');
        capturedStkrImg.setAttribute("src", dataUrl);

        capture();
    });
}

function setupCamera() {
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
        localStream = stream; 
    }, function(error) {
        alert(error);
    });
}

function initVariables() {
    camera = document.getElementById('camera');
    cameraCanvas = document.getElementById('camera-canvas');
    stickerCanvas = document.getElementById('sticker-canvas');
    stickerContext = stickerCanvas.getContext('2d');
    cameraContext = cameraCanvas.getContext('2d');

    capturedWrapper = document.getElementById('captured-wrapper');
    capturedCamImg = document.getElementById('captured-cam-img');      // For captured
    capturedStkrImg = document.getElementById('captured-sticker-img'); // For captured
    stickerImg = document.getElementById('sticker-img');          // For view
    cameraPhoto = document.getElementById('cam-photo');              // For conversion
    stickerPhoto = document.getElementById('sticker-photo');      // For conversion

    imgDir = document.getElementById('img-dir').getAttribute('value');
}

(function() {
    initVariables();
    setupCamera();
    changeSticker({value: 0})
    document.getElementById("btn-capture").addEventListener('click', cameraCapture);
})();