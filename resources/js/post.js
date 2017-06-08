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
var previewWrapper;

var currentSticker;
var currentMode;

var imgDir;
var postDir;
var actionDir;

var happyTriggerStopperOn = false;
var changingMode = false;
var style = "";
var offsetExtra = null;

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

function userUploadBox(id, imgClass="", style="", src="") {
    let box = document.createElement('div');
    let crop = document.createElement('div');
    let photo = document.createElement('img');
    let icon = document.createElement('i');

    box.className = 'user-upload-box';
    box.id = 'upload-box-' + id;
    crop.className = 'crop';
    photo.className = imgClass;
    photo.style = style;
    photo.src = src;
    icon.className = "btn-delete fa fa-trash-o";
    icon.setAttribute('onclick', 'deletePost(' + id + ')');

    crop.appendChild(photo);
    crop.appendChild(icon);
    box.appendChild(crop);
    return box;
}

/**
 * Creates and adds a photo box to the side photo list.
 * @param {object}      postObj   Has imgFile and postId
 * @param {DOM Element} photosDiv Div we're adding to.
 * @return {void}
 */
function createUserUploadBox(postObj, photosDiv, pre=true) {
    try {
        let img = new Image();
        let boxSize = 150;

        img.src = postDir + postObj.imgFile;

        img.onerror = function() {
            let errorImg = imgDir + "image-not-available.png";
            let box = userUploadBox(postObj.postId, "", "", errorImg);
            if (pre) {
                photosDiv.prepend(box);
            } else {
                photosDiv.appendChild(box);
            }
        }

        img.onload = function() {
            let imgClass = "";
            let offset = 0;
            let boxStyle = "";

            // Makes sure to fill up the box when image sizes are not
            // a 1:1 ratio.
            if (this.height < this.width) {
                imgClass = "short-height";
                offset = -(this.width * boxSize / this.height - boxSize) / 2;
                boxStyle = "left: " + offset + "px;";
            } else if (this.width < this.height) {
                imgClass = "short-width";
                offset =  -(this.height * boxSize / this.width - boxSize) / 2;
                boxStyle = "top: " + offset + "px;";
            } else {
                imgClass = "perfect-box";
            }
            let box = userUploadBox(postObj.postId, imgClass, boxStyle, img.src);
            if (pre) {
                photosDiv.prepend(box);
            } else {
                photosDiv.appendChild(box);
            }
        };
    }
    catch(error) {
        console.log(error);
    }
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
        loadStickerImage(currentSticker, offsetExtra);
    }
}

function changeMode(mode) {
    if (changingMode) {
        mode.checked = false;
        if (mode.value == "camera")
            document.querySelectorAll('#modes input[type=radio]')[1].checked = true;
        else
            document.querySelectorAll('#modes input[type=radio]')[0].checked = true;
    }
    else if (mode.value == "camera") {
        changingMode = true;
        offsetExtra = null;
        document.getElementById('btn-capture').classList.add('disabled');
        document.querySelectorAll(".mode-radio")[1].setAttribute('disabled', true);
        document.querySelectorAll(".mode-radio")[0].classList.remove('inactive');
        
        cameraImg.classList.add('invisible');
        stickerImg.style = "";
        cameraImg.style = "";
        style = "";

        stickerCanvas.width = WIDTH;
        stickerCanvas.height = HEIGHT;

        loadStickerImage(currentSticker, offsetExtra);

        camera.onloadeddata = function() {
            camera.classList.remove('invisible');
            changingMode = false;
            document.getElementById('btn-capture').classList.remove('disabled');
            document.querySelectorAll(".mode-radio")[1].removeAttribute('disabled');
            document.querySelectorAll(".mode-radio")[1].classList.add('inactive');
        }
        setTimeout(() => {
            setupCamera();
        }, 1000);

        document.getElementById("file-wrapper").classList.remove("active");
        document.getElementById("file").classList.remove("active");
        document.getElementById("file-label").innerHTML = "";

        cameraCanvas.width = WIDTH;
        cameraCanvas.height = HEIGHT;

        // Needs to flip because camera is actually mirrored.
        // Also needs to be flipped every time the canvas width
        // and height are changed
        cameraContext.translate(WIDTH, 0);
        cameraContext.scale(-1, 1);
    } else if (mode.value == "upload") {
        changingMode = true;
        offsetExtra = null;
        document.getElementById('btn-capture').classList.add('disabled');
        document.querySelectorAll(".mode-radio")[0].setAttribute('disabled', true);
        document.querySelectorAll(".mode-radio")[1].classList.remove('inactive');

        cameraImg.classList.add('invisible');
        camera.classList.add('invisible');
        cameraImg.src = "";
        style = "";

        document.querySelector('#file').value = '';
        setTimeout(() => {
            stopVideo();
            changingMode = false;
            document.querySelectorAll(".mode-radio")[0].classList.add('inactive');
            document.getElementById('btn-capture').classList.remove('disabled');
            document.querySelectorAll(".mode-radio")[0].removeAttribute('disabled');
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

function getFileExtension(file) {
    return file.substr((~-file.lastIndexOf(".") >>> 0) + 2);
}

function getFilenameFromInput(input) {
    let str = input.value;
    return str.split(/(\\|\/)/g).pop();
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
                let boxWidth = WIDTH;
                let boxHeight = HEIGHT;
                let adjustedWidth = WIDTH;
                let adjustedHeight = HEIGHT;
                let offsetW = 0;
                let offsetH = 0;

                // Fills the image into a 400x300 div and centers it.
                if (this.width < this.height || this.width == this.height) {
                    adjustedWidth = this.width * boxHeight / this.height;
                    offsetW = -(adjustedWidth - boxWidth) / 2;
                    style = "left: " + offsetW + "px;";
                    drawToCanvas(img, cameraCanvas, 0, 0, adjustedWidth, HEIGHT);
                } else if (this.height < this.width) {
                    adjustedHeight = this.height * boxWidth / this.width;
                    offsetH = -(adjustedHeight - boxHeight) / 2;
                    style = "top: " + offsetH + "px;";
                    drawToCanvas(img, cameraCanvas, 0, 0, WIDTH, adjustedHeight);
                }
                let camData = cameraCanvas.toDataURL('image/png');
                offsetExtra = {
                    offsetW: -offsetW,
                    offsetH: -offsetH,
                    adjW: adjustedWidth,
                    adjH: adjustedHeight
                };
                // Reload sticker with offsets.
                loadStickerImage(currentSticker, offsetExtra, function() {
                    // Adjusts images styles on the offset
                    stickerImg.style = style;
                    cameraImg.style = style;

                    cameraImg.setAttribute("src", camData);
                    cameraImg.classList.remove('invisible');
                });
            }
        }
        reader.readAsDataURL(input.files[0]);
        let filename = getFilenameFromInput(input);
        if (filename.length > 26) {
            let ext = getFileExtension(filename);
            console.log(ext);
            let start = filename.length - ext.length - 3 - (10 - ext.length);
            start = Math.abs(start);
            fileEnd = filename.substr(start, filename.length - start);
            filename = filename.substr(0, 13);
            filename += '...' + fileEnd;
        }
        document.getElementById('file-label').innerHTML = "File: " + filename;
    }
}

/**
 * Saves the image created
 * @param {string} style Style to set the images to.
 * @return {void}
 */
function captureImage() {
    let camData = capturedCamImg.getAttribute('src');
    let stkerData = capturedStkrImg.getAttribute("src");

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
        url: actionDir + "post.php",
        data: {
            title: title,
            description: description,
            camImg: camData,
            stickerImg: stkerData
        },
        method: "post",
        success: function(res) {
            if (res.indexOf("Error") < 0) {
                res = JSON.parse(res);
                // console.log(res);
                createUserUploadBox(res, document.getElementById('photos'));
            }
        },
        error: function(err) {
            if (err.indexOf("Trigger happy") >= 0) {
                document.getElementById('btn-capture').innerHTML = "Hold your horses! Trigger happy much?";
                setTimeout(function() {
                    document.getElementById('btn-capture').innerHTML = "Capture";
                }, 1000);
            } else if (err.indexOf("No image") >= 0){
                // console.log(err);
                alert("Please select an file first.");
            }
        }
    });
}

/**
 * Turns off the camera.
 * @return {void}
 */
function stopVideo() {
    localStream.getTracks()[0].stop();
}

/**
 * Captures the picture in camera mode
 * @return {void}
 */
function cameraCapture() {
    if (happyTriggerStopperOn) {
        document.getElementById('btn-capture').innerHTML = "Hold your horses! Trigger happy much?";
        return;
    }
    happyTriggerStopperOn = true;
    setTimeout(function() {
        happyTriggerStopperOn = false;
        document.getElementById('btn-capture').innerHTML = "Capture";
    }, 1000);
    capturedCamImg.style = "";
    capturedStkrImg.style = "";

    // Draws the camera onto the canvas.
    cameraContext.drawImage(camera, 0, 0, WIDTH, HEIGHT);

    let dataUrl = cameraCanvas.toDataURL('image/png');
    capturedCamImg.setAttribute("src", dataUrl);

    dataUrl = stickerCanvas.toDataURL('image/png');
    capturedStkrImg.setAttribute("src", dataUrl);

    captureImage();
}

/**
 * Captures the picture in camera mode
 * @return {void}
 */
function fileCapture() {
    capturedCamImg.style = style;
    capturedStkrImg.style = style;

    let dataUrl = cameraImg.getAttribute('src');
    capturedCamImg.setAttribute("src", dataUrl);

    dataUrl = stickerCanvas.toDataURL('image/png');
    capturedStkrImg.setAttribute("src", dataUrl);

    captureImage();
}

function capture() {
    if (changingMode)
        return;

    let isCameraMode = document.querySelectorAll('#modes input[type=radio]')[0].checked;
    
    if (isCameraMode) {
        cameraCapture();
    } else {
        fileCapture();
    }
}

/**
 * Deletes a post
 * @return {void}
 */
function appendLastPost() {
    let numUploads = document.querySelectorAll('.user-upload-box').length;
    if (numUploads < 10) {
        // console.log(numUploads);
        ajax({
            method: 'post',
            url: actionDir + 'get_nth_post.php',
            data: {
                nth: numUploads + 1
            },
            success: function(res) {
                res = JSON.parse(res);

                if (res) {
                    // Have to parsed to get the variables right
                    let parsed = {
                        imgFile: res.img_file,
                        postId: res.post_id
                    };
                    createUserUploadBox(parsed, document.getElementById('photos'), false);
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
}

/**
 * Deletes a post
 * @param {number} id Id of the post
 * @return {void}
 */
function deletePost(id) {
    let yes = confirm("Are you sure?");
    if (yes) {
        ajax({
            method: 'post',
            url: actionDir + 'delete_post.php',
            data: {
                post_id: id
            },
            success: function(res) {
                let post = document.getElementById('upload-box-' + id);
                
                post.style.opacity = '0';
                setTimeout(function() {
                    post.parentNode.removeChild(post);
                    appendLastPost();
                }, 400);
            },
            error: function(err) {
                alert(err);
            }
        });
    }
}

/**
 * Sets up the webcam.
 * @return {void}
 */
function setupCamera(callback) {
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
        if (callback) {
            callback();
        }
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
    stickerImg      = document.getElementById('sticker-img');
    cameraImg       = document.getElementById('camera-img');        // For view
    vidWrapper      = document.getElementById('video-wrapper');
    previewWrapper  = document.getElementById('preview-wrapper');
    imgDir          = document.getElementById('img-dir').getAttribute('value');
    actionDir       = document.getElementById('action-dir').getAttribute('value');
    postDir         = document.getElementById('post-dir').getAttribute('value');
}

(function() {
    initVariables();
    changeSticker(document.querySelector('#form-sticker input[type=radio]'));
    changeMode(document.querySelector('#modes input[type=radio]'));
    document.getElementById("btn-capture").addEventListener('click', capture);
})();