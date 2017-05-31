var HEIGHT = 300;
var WIDTH = 400;

var navigator;
var vendorUrl;
var localStream;

var cameraCanvas;
var cameraContext;


var stickerCanvas;
var stickerContext;

// Image for the preview
var stickerImg;
var cameraImg;

var capturedWrapper;
var capturedCamImg;
var capturedStkrImg;

var vidWrapper;

var currentSticker;

var imgDir;

var stickers = [
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
    },
    {
        imgFile: "mustache-glasses.png",
        x: 125,
        y: 75,
        w: 150,
        h: 150
    }
];

/**
 * Creates and adds a photo box to the side photo list.
 * @param {string}      imgFile   Image we're going to display.
 * @param {DOM Element} photosDiv Div we're adding to.
 * @return {void}
 */
function createUserUploadBox(imgFile, photosDiv) {
    let img = new Image();
    let postDir = document.getElementById('post-dir').getAttribute('value');
    let boxSize = 150;
    img.src = postDir + imgFile;

    img.onload = function() {
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

/**
 * Changes the sticker to the user's choice
 * @param {DOM Element} radio Radio input element with value we're changing sticker to.
 * @return {void}
 */
function changeSticker(radio) {

    if (radio.value >= 0 && radio.value < stickers.length) {
        let radios = document.querySelectorAll('input[type=radio]');
        for (var i = 0; i < radios.length; i++) {
            radios[i].classList.remove('active');
        }
        radio.classList.add('active');
        currentSticker = stickers[radio.value];
        loadStickerImage(currentSticker);
    }
}

function changeMode(mode) {
    if (mode.value == "camera") {
        setTimeout(() => {
            setupCamera();
        }, 1000);
        document.getElementById("file-wrapper").classList.remove("active");
        document.getElementById("file").classList.remove("active");
        document.getElementById("file-label").innerHTML = "";
    } else if (mode.value == "upload") {
        
        setTimeout(() => {
            stopVideo();
        }, 1000);
        document.getElementById("file-wrapper").classList.add("active");
        document.getElementById("file").classList.add("active");
        document.getElementById("file-label").innerHTML = "Choose a file";
    }
}

/**
 * Loads a given sticker.
 * @param {object}   o        Info on sticker we're drawing.
 * @param {object}   extra    Info on adjusted w/h as well as their offsets.
 * @param {function} callback Function to call after we're done.
 * @return {void}
 */
function loadStickerImage(s, extra, callback) {
    let baseImg = new Image();
    
    baseImg.src = imgDir + s.imgFile;
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

        stickerCanvas.width = adjW;
        stickerCanvas.height = adjH;

        // Draws the sticker at this small size onto another canvas so that we can
        // create an image out of it, which will be used to superimpose onto the
        // camera image.
        stickerContext.clearRect(0, 0, adjW, adjH);
        stickerContext.drawImage(baseImg, s.x + offsetW, s.y + offsetH, s.w, s.h);

        let dataUrl = stickerCanvas.toDataURL('image/png');
        stickerImg.setAttribute("src", dataUrl);
        // stickerPhoto.setAttribute('value', dataUrl);
        if (callback) {
            callback();
        }
    };
}

/**
 * Draws image to a canvas.
 * @param {Image}          img    Image we're drawing on to canvas.
 * @param {Canvas Element} canvas Canvas we're drawing to.
 * @param {number}         x      Horizontal position.
 * @param {number}         y      Vertical position.
 * @param {number}         w      Width of image.
 * @param {number}         h      Height of image.
 * @return {void}
 */
function drawToCanvas(img, canvas, x, y, w, h) {
    canvas.height = h;
    canvas.width = w;
    canvas.getContext('2d').drawImage(img, x, y, w, h);
}

/**
 * When file changes, creates a preview of what the image will look like.
 * @param {DOM Element} input File input element.
 * @return {void}
 */
function fileChange(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            let dataUrl = e.target.result;
            let img = new Image();

            img.src = e.target.result;
            img.onload = function() {
                let imgClass = "proportional";
                let style = "";
                let boxWidth = WIDTH;
                let boxHeight = HEIGHT;
                let adjustedWidth = WIDTH;
                let adjustedHeight = HEIGHT;
                let offsetW = 0;
                let offsetH = 0;

                // Fills the image into a 400x300 div and centers it.
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

                // Reload sticker with offsets.
                loadStickerImage(currentSticker, {
                    offsetW: -offsetW,
                    offsetH: -offsetH,
                    adjW: adjustedWidth,
                    adjH: adjustedHeight
                }, function() {
                    // capture(style);
                });
            }
            capturedWrapper.classList.remove("hidden");
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Saves the image created
 * @param {string} style Style to set the images to.
 * @return {void}
 */
function capture(style) {
    // Adjusts images styles on the offset
    stickerImg.style = style;
    capturedCamImg.style = style;
    capturedStkrImg.style = style;

    let camData = cameraCanvas.toDataURL('image/png');
    capturedCamImg.setAttribute("src", camData);
    // cameraPhoto.setAttribute('value', camData);

    let stkerData = stickerImg.getAttribute("src");
    // dataUrl = stickerCanvas.toDataURL('image/png');
    capturedStkrImg.setAttribute("src", stkerData);

    // let camData = cameraPhoto.getAttribute("value");
    
    camData = camData;
    stkerData = stkerData;

    let description = document.getElementById('description');
    if (description) {
        description = description.getAttribute("value");
    } else {
        description = "";
    }

    let title = document.getElementById('title');
    if (title) {
        title = title.getAttribute("value");
    } else {
        title = "";
    }

    ajax({
        url: "/camagru/actions/post.php",
        data: {
            title: title,
            description: description,
            camImg: camData,
            stickerImg: stkerData
        },
        method: "post",
        success: function(res) {
            createUserUploadBox(res, document.getElementById('photos'));
        },
        error: function(err) {
            console.log(err);
        }
    });
}

/**
 * Turns off the camera.
 * @return {void}
 */
function stopVideo() {
    localStream.getTracks()[0].stop();
    vidWrapper.classList.add('invisible');
}

/**
 * Captures the picture in camera mode
 * @return {void}
 */
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

    // Draws the camera onto the canvas.
    cameraContext.drawImage(camera, 0, 0, WIDTH, HEIGHT);

    stickerCanvas.width = WIDTH;
    stickerCanvas.height = HEIGHT;
    loadStickerImage(currentSticker, null, function() {
        let dataUrl = cameraCanvas.toDataURL('image/png');
        capturedCamImg.setAttribute("src", dataUrl);

        dataUrl = stickerCanvas.toDataURL('image/png');
        capturedStkrImg.setAttribute("src", dataUrl);

        capture();
    });
}

/**
 * Sets up the webcam.
 * @return {void}
 */
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
        vidWrapper.classList.remove('invisible');
    }, function(error) {
        console.log(error);
    });
}

/**
 * Initializes global variables.
 * @return {void}
 */
function initVariables() {
    camera          = document.getElementById('camera');
    cameraCanvas    = document.getElementById('camera-canvas');
    stickerCanvas   = document.getElementById('sticker-canvas');
    stickerContext  = stickerCanvas.getContext('2d');
    cameraContext   = cameraCanvas.getContext('2d');
    capturedWrapper = document.getElementById('captured-wrapper');
    capturedCamImg  = document.getElementById('captured-cam-img');      // For captured
    capturedStkrImg = document.getElementById('captured-sticker-img'); // For captured
    stickerImg      = document.getElementById('sticker-img');          // For view
    vidWrapper      = document.getElementById('video-wrapper');

    imgDir = document.getElementById('img-dir').getAttribute('value');
}

(function() {
    initVariables();
    setupCamera();
    changeSticker(document.querySelector('input[type=radio]'))
    document.getElementById("btn-capture").addEventListener('click', cameraCapture);
})();