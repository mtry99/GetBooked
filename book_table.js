
$(document).ready(function () {

    $('.cover-image').each(function(i, obj) {
        let book_og_key = obj.id.split('-')[1];
        obj.onload = function() {
            //console.log(this.naturalWidth, this.naturalHeight);
            if(this.naturalWidth + this.naturalHeight === 2) {
                obj.src = "no_cover.jpg";
            }
        };
        obj.onerror = function() {
            obj.src = "no_cover.jpg";
        };
        obj.src = "http://covers.openlibrary.org/b/olid/" + book_og_key + "-M.jpg";
    });
});

function title_clicked(id) {
    console.log("title_clicked: " + id);

    return false;
}

function author_clicked(id) {
    console.log("author_clicked: " + id);

    return false;
}

function publisher_clicked(id) {
    console.log("publisher_clicked: " + id);

    return false;
}

function genre_clicked(id) {
    console.log("genre_clicked: " + id);

    return false;
}