let empty_cover_img = new Image();
empty_cover_img.src = "assets/empty_cover_small.png";

$(document).ready(function() {
    console.log("ready!");

    for (let j = 0; j < inventory.length; j++) {
        let book_og_key = inventory[j].original_key;
        let img = new Image();
        img.onload = function() {
            if (this.naturalWidth + this.naturalHeight === 2) {
                let div0 = document.getElementById("cover-title-" + j);
                let rbg = hslToRgb(book_og_key.hashCode() % 360 / 360, 1, 0.7);
                div0.style.backgroundColor = `rgba(${rbg[0]},${rbg[1]},${rbg[2]},0.5)`;
                div0 = document.getElementById("cover-back-title-" + j);
                div0.style.backgroundColor = `rgba(${rbg[0]},${rbg[1]},${rbg[2]},0.5)`;
                div0 = document.getElementById("cover-" + j);
                div0.src = "assets/empty_cover.png";
                div0 = document.getElementById("cover-back-" + j);
                div0.src = "assets/empty_cover.png";
                inventory[j].img = empty_cover_img;
                inventory[j].color = `rgba(${rbg[0]},${rbg[1]},${rbg[2]},0.5)`;
            } else {
                let div0 = document.getElementById("cover-" + j);
                div0.src = this.src;
                div0 = document.getElementById("cover-back-" + j);
                div0.src = this.src;
                div0 = document.getElementById("cover-title-" + j);
                div0.style.display = "none";
                div0 = document.getElementById("cover-back-title-" + j);
                div0.style.display = "none";
                inventory[j].img = this;
            }
        };
        img.onerror = function() {};
        img.src = "http://covers.openlibrary.org/b/olid/" + book_og_key + "-M.jpg";
    }


    for (let j = 0; j < inventory.length; j++) {
        let s = inventory[j].title;
        let result = [];
        let words = s.split(" ");
        let curLine = "";
        let lineMax = 12;
        for (word of words) {
            if (word.length + curLine.length + 1 > lineMax) {
                result.push(curLine);
                curLine = word;
            } else {
                curLine += ' ' + word;
            }
        }
        result.push(curLine);
        inventory[j].splitTitle = result;
    }
    console.log(inventory);

});