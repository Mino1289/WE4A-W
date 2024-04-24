function validate(input) { document.getElementsByName(input)[0].classList.add("error"); }

function like(id_post) {
    $.ajax({
        type: "POST",
        url: "components/processlike.php",
        data: {
            id_post: id_post,
            type: 'like'
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                var like = $(`#like-${id_post}`);
                var dislike = $(`#dislike-${id_post}`);
                like.text(`${result.likes} W`);                
                dislike.text(`${result.dislikes} L`);

                if (like.hasClass("btn-outline-success")) {
                    like.removeClass("btn-outline-success");
                    like.addClass("btn-success");
                } else {
                    like.removeClass("btn-success");
                    like.addClass("btn-outline-success");
                }

                if (dislike.hasClass("btn-outline-danger")) {
                    dislike.removeClass("btn-outline-danger");
                    dislike.addClass("btn-danger");
                } 
                //else {
                //     dislike.removeClass("btn-danger");
                //     dislike.addClass("btn-outline-danger");
                // }
            } 
        } 
    }); 
}

function dislike(id_post) {
    $.ajax({
        type: "POST",
        url: "components/processlike.php",
        data: {
            id_post: id_post,
            type: 'dislike'
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                var like = $(`#like-${id_post}`);
                var dislike = $(`#dislike-${id_post}`);
                like.text(`${result.likes} W`);                
                dislike.text(`${result.dislikes} L`);
                if (like.hasClass("btn-outline-success")) {
                    like.removeClass("btn-outline-success");
                    like.addClass("btn-success");
                } 
                // else {
                //     like.removeClass("btn-success");
                //     like.addClass("btn-outline-success");
                // }

                if (dislike.hasClass("btn-outline-danger")) {
                    dislike.removeClass("btn-outline-danger");
                    dislike.addClass("btn-danger");
                } else {
                    dislike.removeClass("btn-danger");
                    dislike.addClass("btn-outline-danger");
                }
            }
        }
    });
}

function warn(id, type) {
    $.ajax({
        type: "POST",
        url: "components/warn.php",
        data: {
            id: id,
            type: type
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                if (type == "post") {
                    var post = $(`#post-${id}`);
                    var btn = $(`#btn-warn-post-${id}`);
                    console.log(btn);
                    if (post.hasClass("sensible")) {
                        post.removeClass("sensible");
                        btn.text("Mark post sensible");
                    } else {
                        post.addClass("sensible");
                        btn.text("Unmark post sensible");
                    }
                }
            }
        }
    });
}

function delet(id, type) {
    $.ajax({
        type: "POST",
        url: "components/delete.php",
        data: {
            id: id,
            type: type
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                if (type == "post") {
                    $(`#post-${id}`).remove();
                }
            }
        }
    });
}