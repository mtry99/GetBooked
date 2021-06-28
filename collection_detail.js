
function hslToRgb(h, s, l){
    var r, g, b;

    if(s == 0){
        r = g = b = l; // achromatic
    }else{
        var hue2rgb = function hue2rgb(p, q, t){
            if(t < 0) t += 1;
            if(t > 1) t -= 1;
            if(t < 1/6) return p + (q - p) * 6 * t;
            if(t < 1/2) return q;
            if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        }

        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        var p = 2 * l - q;
        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);
    }

    return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
}

String.prototype.hashCode = function() {
    var hash = 0, i, chr;
    if (this.length === 0) return hash;
    for (i = 0; i < this.length; i++) {
      chr   = this.charCodeAt(i);
      hash  = ((hash << 5) - hash) + chr;
      hash |= 0; // Convert to 32bit integer
    }
    return hash;
  };

$(document).ready(function() {
    console.log( "ready!" );

    for (let i = 1; i <= 5; i++) {
        for (let j = 0; j < obj[i].length; j++) {
            let book_og_key = obj[i][j].original_key;
            let img = new Image();
            img.onload = function() {
                if(this.naturalWidth + this.naturalHeight === 2) {
                    let div0 = document.getElementById("cover-title-" + i + "-" + j);
                    let rbg = hslToRgb(book_og_key.hashCode()%360/360, 1, 0.7);
                    div0.style.backgroundColor = `rgba(${rbg[0]},${rbg[1]},${rbg[2]},0.5)`;
                    div0 = document.getElementById("cover-back-title-" + i + "-" + j);
                    div0.style.backgroundColor = `rgba(${rbg[0]},${rbg[1]},${rbg[2]},0.5)`;
                    div0 = document.getElementById("cover-" + i + "-" + j);
                    div0.src = "empty_cover.png";
                    div0 = document.getElementById("cover-back-" + i + "-" + j);
                    div0.src = "empty_cover.png";
                } else {
                    let div0 = document.getElementById("cover-" + i + "-" + j);
                    div0.src = this.src;
                    div0 = document.getElementById("cover-back-" + i + "-" + j);
                    div0.src = this.src;
                    div0 = document.getElementById("cover-title-" + i + "-" + j);
                    div0.style.display = "none";
                    div0 = document.getElementById("cover-back-title-" + i + "-" + j);
                    div0.style.display = "none";
                }
            };
            img.onerror = function() {
            };
            img.src = "http://covers.openlibrary.org/b/olid/" + book_og_key + "-M.jpg";
        }
    }
});
