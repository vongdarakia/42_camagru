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

function postComment() {
    let comment = document.getElementById('comment-area').value;

    if (comment == "")
        return;

    
    let post_id = document.getElementById('comment-area').getAttribute('post-id');

    ajax({
        method: 'post',
        url: document.getElementById('comment-action').value,
        data: {
            comment: comment,
            post_id: post_id
        },
        success: function(res) {
            console.log(res);
            if (res) {
                let author = document.getElementById('user-login').value;
                let box = commentBox(author, post_id, comment, 'jan 15');
                let firstComment = document.querySelector('.comment-box');
                insertAfter(box, firstComment);

                document.getElementById('comment-area').value = "";
            } else {
                alert("Couldn't add comment.");
            }
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function deleteComment(comment) {
    let id = comment.getAttribute('comment-id');
    let cBox = document.getElementById('comment-box-' + id);

    cBox.parentNode.removeChild(cBox);
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