function commentBox(author, id, comment, time) {
    let box         = document.createElement('div');
    let p1          = document.createElement('p');
    let p2          = document.createElement('p');
    let cAuthor     = document.createElement('span');
    let authorLink  = document.createElement('a');
    let cTime       = document.createElement('span');
    let cComment    = document.createElement('span');
    let btnDelete   = document.createElement('i');

    box.className = 'comment-box';
    box.id = 'comment-box-' + id;

    cAuthor.className = 'comment-author';
    authorLink.className = 'author-link';
    authorLink.innerHTML = author;

    cTime.className = 'comment-time';
    cTime.innerHTML = time;

    cComment.className = 'comment';
    cComment.innerHTML = comment;

    btnDelete.className = 'btn-delete fa fa-trash-o';
    btnDelete.setAttribute('onclick', 'deleteComment(this)');
    btnDelete.setAttribute('comment-id', id);

    box.append(p1);
    box.append(p2);
    box.append(btnDelete);

    p1.append(cAuthor);
    p1.append(cTime);
    p2.append(cComment);

    cAuthor.append(authorLink);

    

    // <i class="btn-delete fa fa-trash-o" onclick="deleteComment(this)" comment-id="2"></i>
    return box;
}

function commentDate(date) {
    let monthNames = [
        "January", "February", "March",
        "April", "May", "June", "July",
        "August", "September", "October",
        "November", "December"
    ];

    date = new Date(date);
    let day = date.getDate();
    let month = monthNames[date.getMonth()];
    let year = date.getFullYear();
    let mins = date.getMinutes();
    let hrs = date.getHours() % 12;
    let isPM = date.getHours() >= 12 ? 1 : 0;
    let time = hrs + ":" + mins + (isPM ? " pm" : " am");
    
    return month + " " + day + ", " + year + " " + time;

}

function postComment() {
    let comment = document.getElementById('comment-area').value;

    if (comment.trim() == "")
        return;
    
    let post_id = document.getElementById('comment-area').getAttribute('post-id');

    ajax({
        method: 'post',
        url: document.getElementById('comment-action').value,
        data: {
            comment: comment,
            post_id: post_id
        },
        success: function(cObj) {
            try {
                if (cObj) {
                    cObj = JSON.parse(cObj);
                    if (cObj) {
                        let author = cObj.author_login;
                        let box = commentBox(author, cObj.id, cObj.comment, commentDate(cObj.creation_date));
                        let firstComment = document.querySelector('.comment-box');
                        
                        insertAfter(box, firstComment);
                        document.getElementById('comment-area').value = "";
                    } else {
                        alert("Couldn't add comment.");
                    }
                } else {
                    alert("Couldn't add comment.");
                }
            } catch (err) {
                console.log(err);
            }
        },
        error: function(err) {
            alert(err);
        }
    });
}

function deleteComment(comment) {
    let yes = confirm("Are you sure you want to delete this?");
    if (!yes) {
        return;
    }
    let comment_id = comment.getAttribute('comment-id');
    console.log(comment_id);
    ajax({
        method: 'post',
        url: document.getElementById('delete-comment-action').value,
        data: {
            comment_id: comment_id
        },
        success: function(res) {
            if (res) {
                let cBox = document.getElementById('comment-box-' + comment_id);
                cBox.parentNode.removeChild(cBox);
            } else {
                alert("Couldn't delete comment.");
            }
        },
        error: function(err) {
            alert(err);
        }
    });
    
}

(function() {
    var text = document.getElementById('comment-area');

    // Adds an event listener to the element
    function observe(element, event, handler) {
        element.addEventListener(event, handler, false);
    };

    function resize () {
        text.style.height = 'auto';
        text.style.height = text.scrollHeight+'px';
    }

    function delayedResize () {
        window.setTimeout(resize, 0);
    }
    observe(text, 'change',  resize);
    observe(text, 'cut',     delayedResize);
    observe(text, 'paste',   delayedResize);
    observe(text, 'drop',    delayedResize);
    observe(text, 'keydown', delayedResize);

    text.focus();
    text.select();
    resize();
})();