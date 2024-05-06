function validate(input) {
    document.getElementsByName(input)[0].classList.add("error");
}

function formatDate(difference) {
    // difference is date1 - date2
    // https://stackoverflow.com/questions/15493521/how-do-i-calculate-a-duration-time
    //Arrange the difference of date in days, hours, minutes, and seconds format
    let days = Math.floor(difference / (1000 * 60 * 60 * 24));
    let hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((difference % (1000 * 60)) / 1000);
    return { days, hours, minutes, seconds };
}

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
    var title = prompt("Please provide a title:", "Warning");
    var message = prompt("Please provide a reason:", "User / Post has been warned.");
    $.ajax({
        type: "POST",
        url: "components/warn.php",
        data: {
            id: id,
            type: type,
            title: title,
            content: message
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                if (type == "post") {
                    var post = $(`#post-${id}`);
                    var btn = $(`#btn-warn-post-${id}`);
                    if (post.hasClass("sensible")) {
                        post.removeClass("sensible");
                        btn.text("Mark post sensible");
                    } else {
                        post.addClass("sensible");
                        btn.text("Unmark post sensible");
                    }
                }
                if (type == "user") {
                    var btn = $(`#btn-warn-user-${id}`);
                    if (btn.text() == "Warn user") {
                        btn.text("Unwarn user");
                    } else {
                        btn.text("Warn user");
                    }
                }
            }
        }
    });
}

function delet(id, type) {
    var title = prompt("Please provide a title:", "Delete post");
    var message = prompt("Please provide a reason:", "One of your posts has been deleted.");
    $.ajax({
        type: "POST",
        url: "components/delete.php",
        data: {
            id: id,
            type: type,
            title: title,
            content: message
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

if (parseInt($('#notif-nbr').text()) > 0) {
    $('#notif-nbr').show();
} else {
    $('#notif-nbr').hide();
}

var notifID = [];
setInterval(function () {
    $.ajax({
        type: "POST",
        url: "components/refresh.php",
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                if (result.n <= 0)
                    $('#notif-nbr').hide();
                else {
                    $('#notif-nbr').show().text(result.n);
                    if (notifID.indexOf(result.notifications[0].ID) == -1 && result.notifications[0].isDisplayed == 0) {
                        var lastnotif = result.notifications[0];
                        var date = formatDate(Date.now() - new Date(lastnotif.date));
                        var datestring = "";
                        if (date.days > 0)
                            datestring += `${date.days} days `;
                        else {
                            if (date.hours > 0)
                                datestring += `${date.hours} hours `;
                            if (date.minutes > 0)
                                datestring += `${date.minutes} minutes `;
                            if (date.seconds > 0)
                                datestring += `${date.seconds} seconds `;
                        }

                        $(`#notif-${lastnotif.id}`).remove();
                        var html = `<div id="notif-${lastnotif.ID}" class="toast-container position-fixed bottom-0 end-0 p-3"> \
                        <div id="liveToast-${lastnotif.ID}" class="toast" role="alert" aria-live="assertive" aria-atomic="true"> \
                        <div class="toast-header"> \
                            <strong class="me-auto">${lastnotif.title}</strong> \
                            <small>${datestring}</small> \
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button> \
                        </div> \
                        <div class="toast-body"> \
                            ${lastnotif.content}
                            <div> \
                            <button id="btn-mr-notif-${lastnotif.ID}" type="button" class="btn btn-primary" onclick="exeNotif(${lastnotif.ID}, 'read')"><i class="fa fa-fw fa-solid fa-envelope-circle-check"></i></button> \
                            <button type="button" class="btn btn-danger" onclick="exeNotif(${lastnotif.ID}, 'delete')"><i class="fa fa-fw fa-solid fa-trash-can"></i></button> \
                            </div> \
                        </div> \
                        </div>`;
                        $('body').append(html)

                        const toastLiveExample = $(`#liveToast-${lastnotif.ID}`)
                        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
                        toastBootstrap.show()
                        notifID.push(lastnotif.ID);

                        exeNotif(lastnotif.ID, "displayed")
                    }
                }
            }
        }
    });
}, 10 * 1000); // 10 seconds

function exeNotif(id, type) {
    if (type == "delete") {
        if (!confirm("Voulez-vous vraiment supprimer cette notification ?"))
            return;
    } else if (type == "deleteAll") {
        if (!confirm("Voulez-vous vraiment supprimer toutes les notifications ?"))
            return;
    }

    $.ajax({
        type: "POST",
        url: "components/notifs.php",
        data: {
            id: id,
            type: type // "displayed", "read", "delete", "readAll", "deleteAll"
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                if (type == "read") {
                    $(`#notif-${id}`).removeClass('table-primary');
                    $(`#btn-mr-notif-${id}`).remove();
                    $('#notif-nbr').text(parseInt($('#notif-nbr').text()) - 1);
                }
                if (type == "delete") {
                    $(`#notif-${id}`).remove();
                    $('#notif-nbr').text(parseInt($('#notif-nbr').text()) - 1);
                }
                if (type == "readAll") {
                    $('#notif-nbr').text("0").hide();
                    $('.notif').removeClass('table-primary');
                    $('.btn-mr-notif').remove();
                }
                if (type == "deleteAll") {
                    $('#notif-nbr').text("0").hide();
                    $('#table-body-notif').remove();
                }
            }
        }
    });
}

function follow(id) { // for user profile page
    if ($('#follow-btn').text() == "Unfollow") {
        if (!confirm("Voulez-vous vraiment unfollow cet utilisateur ?"))
            return;
    }

    $.ajax({
        type: "POST",
        url: "components/follow.php",
        data: {
            ID_user: id
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                if (result.following) {
                    $('#follow-btn').text("Unfollow").removeClass("btn-success").addClass("btn-danger");
                } else {
                    $('#follow-btn').text("Follow").removeClass("btn-danger").addClass("btn-success");
                }
            }
        }
    });
}

function unfollow(id) { // for suivi page
    if (!confirm("Voulez-vous vraiment unfollow cet utilisateur ?"))
        return;
    $.ajax({
        type: "POST",
        url: "components/follow.php",
        data: {
            ID_follow: id
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                $(`#follow-${id}`).remove();
            }
        }
    });
}

postsList = [];
function loadPosts(type) {
    var start = $('#posts').children().length;
    var id = parseInt($('#ID_user').text());
    $.ajax({
        type: "POST",
        url: "components/display.php",
        data: {
            type: type,
            start: start,
            ID_user: id
        },
        datatype: "json",
        success: function (result) {
            result = JSON.parse(result);
            if (result.success) {
                for (let post of result.posts) {
                    var id = parseInt(post.match(/post-[0-9]+/)[0].slice(5));
                    if (postsList.indexOf(id) != -1) {
                        continue;
                    }
                    $('#posts').append(post);
                    postsList.push(id);
                }
            }
        }
    });
}

