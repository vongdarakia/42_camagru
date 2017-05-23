(function() {
    let camera = document.getElementById('camera'),
    cameraCanvas = document.getElementById('camera-canvas'),
    stickerCanvas = document.getElementById('sticker-canvas'),
    cameraContext = cameraCanvas.getContext('2d'),
    stickerContext = stickerCanvas.getContext('2d'),         
    camImg = document.getElementById('cam-img'),             // For view
    stickerImg = document.getElementById('sticker-img'),     // For view
    camPhoto = document.getElementById('cam-photo'),         // For posting
    stickerPhoto = document.getElementById('sticker-photo'), // For posting

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

    let baseImg = new Image();
    let imgDir = document.getElementById('img-dir').getAttribute('value');
    
    baseImg.src = imgDir + "mustache-glasses.png";
    // console.log(baseImg.src);
    baseImg.onload = function() {
        // console.log(this.width + ' ' + this.height);
        // Draws the sticker at this small size onto another canvas so that we can
        // create an image out of it, which will be used to superimpose onto the
        // camera image.
        stickerContext.drawImage(baseImg, 125, 75, 150, 150);

        let dataUrl = stickerCanvas.toDataURL('image/png');
        stickerImg.setAttribute("src", dataUrl);
        stickerPhoto.setAttribute('value', dataUrl);
    };

    document.getElementById("btn-capture").addEventListener('click', function() {
        
        // Draws the camera onto the canvas.
        cameraContext.drawImage(camera, 0, 0, 400, 300);

        // Manipulate canvas here

        let dataUrl = cameraCanvas.toDataURL('image/png');
        camImg.setAttribute("src", dataUrl);
        camPhoto.setAttribute('value', dataUrl);
        // console.log(document.getElementById('hidden-img'));
    });

})();