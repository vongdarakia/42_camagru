/**
 * Inserts an element after another element.
 * @param {Node Element} newNode Node to insert.
 * @param {Node Element} referenceNode Reference of insert.
 * @return {void}
 */
function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

/**
 * AJAX call, much like how jQuery AJAX works.
 * @param {object} o Holds information
 * @return {void}
 */
function ajax(o){
    let xmlhttp;
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onload = function() {
        if (o.success && xmlhttp.responseText.indexOf('Error') == -1) {
            o.success(xmlhttp.responseText);
        } else if (o.error) {
            o.error(xmlhttp.responseText);
        }
    }

    let url = "";
    if (o.data) {
        let i = 0;
        for (var k in o.data){
            if (o.data.hasOwnProperty(k)) {
                if (i > 0) url += "&";
                url += k + '=' + encodeURIComponent(o.data[k]);
                i++;
            }
        }
    }

    let method = "GET";
    if (o.method) {
        method = o.method.toUpperCase();
    }
    if (method == "GET" || method == "POST") {
        try {
            xmlhttp.open(method, o.url, true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(encodeURI(url));
        } catch(err) {
            console.log(err);
        }
    }
}

/**
 * UI modifications for liking a post
 * @param {DOM Element} heart The heart icon
 * @return {void}
 */
function applyLike(heart) {
    heart.classList.remove('btn-like');
    heart.classList.remove('fa-heart-o');
    heart.classList.add('btn-liked');
    heart.classList.add('fa-heart');
}

/**
 * UI modifications for unliking a post
 * @param {DOM Element} heart The heart icon
 * @return {void}
 */
function applyUnlike(heart) {
    heart.classList.remove('btn-liked');
    heart.classList.remove('fa-heart');
    heart.classList.add('btn-like');
    heart.classList.add('fa-heart-o');
}

/**
 * Likes or unlikes a post
 * @param {DOM Element} heart The heart icon
 * @return {void}
 */
function like(heart) {
    let url = document.getElementById("like-action").value;
    let isLiking = true;
    let postId = heart.getAttribute('post-id');
    
    if (heart.classList.contains('btn-liked')) {
        applyUnlike(heart);
        isLiking = false;
    } else {
        applyLike(heart);
    }

    ajax({
        method: 'post',
        url: url,
        data: {
            post_id: postId,
            is_liking: isLiking
        },
        success: function(res) {
            if (res >= 1) {
                let spanNumLikes = document.getElementById('num-likes-' + postId);
                let numLikes = parseInt(spanNumLikes.innerHTML);
                numLikes += isLiking ? 1 : -1;
                spanNumLikes.innerHTML = numLikes;
            } else {
                if (heart.classList.contains('btn-liked')) {
                    applyUnlike(heart);
                } else {
                    applyLike(heart);
                }
                alert("Please log in to like this post.");
            }
        },
        error: function(err) {
            console.log(err);
        }
    });
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
                if (!post)
                    post = document.getElementById('post-box-' + id);
                
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

var imgDir;
var actionDir;
var postDir;

(function() {
    imgDir          = document.getElementById('img-dir').getAttribute('value');
    actionDir       = document.getElementById('action-dir').getAttribute('value');
    postDir         = document.getElementById('post-dir').getAttribute('value');
}());