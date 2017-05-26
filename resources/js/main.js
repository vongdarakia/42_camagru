function ajax(o){
    let xmlhttp;
    xmlhttp = new XMLHttpRequest();

    // xmlhttp.onreadystatechange = function() {
    //     if (xmlhttp.readyState == XMLHttpRequest.DONE && xmlhttp.status == 200) {
    //         if (o.success) {
    //             o.success(xmlhttp.responseText);
    //         }
    //     } else if (o.error) {
    //         o.error(xmlhttp.responseText);
    //     }
    // }
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

function getBase64(file) {
   var reader = new FileReader();
   reader.readAsDataURL(file);
   reader.onload = function () {
     console.log(reader.result);
   };
   reader.onerror = function (error) {
     console.log('Error: ', error);
   };
}

function applyLike(heart) {
    heart.classList.remove('btn-like');
    heart.classList.remove('fa-heart-o');
    heart.classList.add('btn-liked');
    heart.classList.add('fa-heart');
}

function applyUnlike(heart) {
    heart.classList.remove('btn-liked');
    heart.classList.remove('fa-heart');
    heart.classList.add('btn-like');
    heart.classList.add('fa-heart-o');
}

function like(heart) {
    let url = document.getElementById("like-action").value;
    let email = document.getElementById("user-email").value;
    let isLiking = true;
    
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
            post_id: heart.id,
            is_liking: isLiking
        },
        success: function(res) {
            console.log(res);
        },
        error: function(err) {
            console.log(err);
        }
    });
}