<?php 
/**
 * Post page. User post their images. This is a test page for now. Probably won't use.
 *
 * PHP version 5.5.38
 *
 * @category  Page
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

session_start();
require_once '../config/paths.php';

// Maybe use this.
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // …
// }

if (!(isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "")) {
    header("Location: ../index.php");
}

require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <h2>Post</h2>
    <?php  
    if (isset($_SESSION["err_msg"]) && $_SESSION["err_msg"] != "") {
        echo "Error: " . $_SESSION["err_msg"];
        $_SESSION["err_msg"] = "";
    }
    ?>
    
    <form action="<?php echo ACTIONS_DIR ?>post.php" method="post" enctype="multipart/form-data">
        <div id="booth">
            <video id="camera" width="400" height="300"></video>
            <a href="#" id="btn-capture">Take Photo</a>
            <canvas id="canvas" width="400" height="300"></canvas>
            <img src="" id="photo">
        </div>
        <input type="email" name="email" value="<?php echo $_SESSION["user_email"]; ?>">
        <input type="text" name="title" placeholder="title">
        <input type="text" name="description" placeholder="description">
        <input type="file" name="file" id="file">
        <input type="hidden" name="base64img" value="" id="hidden-img">
        <button>Post</button>
    </form>
</div>

<script>
    (function() {
        var camera = document.getElementById('camera'),
        canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        photo = document.getElementById('photo'),
        hiddenImg = document.getElementById('hidden-img'),
        // To generate src from video
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

        document.getElementById("btn-capture").addEventListener('click', function() {
            context.drawImage(camera, 0, 0, 400, 300);

            // Manipulate canvas here

            let dataUrl = canvas.toDataURL('image/png');
            photo.setAttribute("src", dataUrl);
            hiddenImg.setAttribute('value', dataUrl);
            // console.log(document.getElementById('hidden-img'));
        });
    })();
</script>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>