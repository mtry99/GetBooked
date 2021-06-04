
$(document).ready(function () {

    $('.cover-image-big').each(function(i, obj) {
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
        obj.src = "http://covers.openlibrary.org/b/olid/" + book_og_key + "-L.jpg";
    });
});