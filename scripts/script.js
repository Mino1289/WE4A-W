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
                var elemlike = $(`#like-${id_post}`); 
                if (elemlike.length) {
                    elemlike.text(`${result.likes} W`);
                }
                var elemdislike = $(`#dislike-${id_post}`); 
                if (elemdislike.length) {
                    elemdislike.text(`${result.dislikes} L`); 
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
                var elemlike = $(`#like-${id_post}`); 
                if (elemlike.length) {
                    elemlike.text(`${result.likes} W`);
                } 
                
                var elemdislike = $(`#dislike-${id_post}`); 
                if (elemdislike.length) {
                    elemdislike.text(`${result.dislikes} L`);
                }
            }
        }
    });
}