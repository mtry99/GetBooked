
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

function drawStar(cx,cy,spikes,outerRadius,innerRadius){
    var rot=Math.PI/2*3;
    var x=cx;
    var y=cy;
    var step=Math.PI/spikes;

    ctx.beginPath();
    ctx.moveTo(cx,cy-outerRadius)
    for(i=0;i<spikes;i++){
        x=cx+Math.cos(rot)*outerRadius;
        y=cy+Math.sin(rot)*outerRadius;
        ctx.lineTo(x,y)
        rot+=step

        x=cx+Math.cos(rot)*innerRadius;
        y=cy+Math.sin(rot)*innerRadius;
        ctx.lineTo(x,y)
        rot+=step
    }
    ctx.lineTo(cx,cy-outerRadius);
    ctx.closePath();
}

let canvas = document.getElementById("canvas");
const { width, height } = canvas.getBoundingClientRect();
canvas.width = Math.ceil(width);
canvas.height = Math.ceil(height);
let ctx = canvas.getContext("2d");

let wheel = [];
let wheelPos = 0;
let t = 0;

let coverHeight = 300;
let coverWidth = 200;
let coverGap = 50;

let coverEdgePoints = [];

let fireFrameImgs = [[],[],[],[],[],[]];
let fireFrameImgsOg = [];
let fireFrameImgsBw = [];

let rarityGlows = {
    3: {gs: 30, c1: 220},
    4: {gs: 40, c1: 280},
    5: {gs: 60, c1: 40}
};

for(let i = 0; i <= 20; i++) {
    coverEdgePoints.push({x: i / 20 * coverWidth, y: 0, r: Math.random()});
    coverEdgePoints.push({x: i / 20 * coverWidth, y: coverHeight, r: Math.random()});
}
for(let i = 1; i < 30; i++) {
    coverEdgePoints.push({x: 0, y: i / 30 * coverHeight, r: Math.random()});
    coverEdgePoints.push({x: coverWidth, y: i / 30 * coverHeight, r: Math.random()});
}

let metal_arrow_img = new Image();
metal_arrow_img.src = "metal_arrow.png";

let c = 7;
let d = 30 * (coverWidth + coverGap) - width/2 + coverWidth/2 + 0.7 * coverWidth * (Math.random()-0.5);
let x = 0;

let startSpin = false;
let spinned = false;

function onFrame() {
    ctx.fillStyle = "rgba(0,0,0, 0.2)";
    ctx.fillRect(0, 0, width, height);
    
    let wheelSize = (wheel.length * (coverWidth + coverGap));

    t += 1/60;

    if(startSpin) x += 1/60;
    wheelPos = - d / (c * c) * Math.pow(x - c, 2) + d;
    if(x > c && startSpin) {
        wheelPos = d;
        startSpin = false;

        $('#bookModal').modal('show');
    }

    let effectiveWheelPos = - (wheelPos) % wheelSize;

    for(let i = 0; i < wheel.length * 2; i++) {
        let idx = i < wheel.length ? i : i - wheel.length;
        let book = obj[wheel[idx].rarity][wheel[idx].idx];
        let rarity = wheel[idx].rarity;

        let bookPos = {
            x: effectiveWheelPos + i * (coverWidth + coverGap),
            y: height / 2 - coverHeight / 2,
            w: coverWidth, 
            h: coverHeight
        }

        if(bookPos.x + bookPos.w * 2 < 0 || bookPos.x - 1 * bookPos.w > width) {
            continue;
        }

        ctx.save();

        function gradientRadial(dir, clr) {
            var grad = ctx.createRadialGradient(dir[0], dir[1], 0, dir[0], dir[1], dir[2]);
            grad.addColorStop(1.0, `rgba(${clr},0)`);
            grad.addColorStop(0.2, `rgba(${clr},0.5)`);
            grad.addColorStop(0.0, `rgba(${clr},1)`);
            return grad;
        }

        ctx.translate(bookPos.x, bookPos.y);

        for(p of coverEdgePoints) {
            let glowSize = rarityGlows[rarity].gs * (0.3 * Math.cos(t * 5 + 2 * Math.PI * p.r + 2 * Math.PI * wheel[idx].r) + 1);
            let glowHue = rarityGlows[rarity].c1 + 30 * Math.cos(t * 3 + 3 * Math.PI * p.r + 3 * Math.PI * wheel[idx].r);
            glowHue = glowHue % 360;
            let glowClr = hslToRgb(glowHue / 360, 1, 0.5);
            ctx.fillStyle = gradientRadial([p.x, p.y, glowSize], `${glowClr[0]},${glowClr[1]},${glowClr[2]}`);
            ctx.fillRect(p.x-glowSize, p.y-glowSize, 2*glowSize, 2*glowSize);
        }

        ctx.restore();
    }
    
    for(let i = 0; i < wheel.length * 2; i++) {
        let idx = i < wheel.length ? i : i - wheel.length;
        let book = obj[wheel[idx].rarity][wheel[idx].idx];
        let rarity = wheel[idx].rarity;

        let bookPos = {
            x: effectiveWheelPos + i * (coverWidth + coverGap),
            y: height / 2 - coverHeight / 2,
            w: coverWidth, 
            h: coverHeight
        }

        if(bookPos.x + bookPos.w * 2 < 0 || bookPos.x - 1 * bookPos.w > width) {
            continue;
        }

        ctx.save();

        if(book.img) {
            ctx.drawImage(book.img, bookPos.x, bookPos.y, bookPos.w, bookPos.h);
        } else {
            ctx.drawImage(empty_cover_img, bookPos.x, bookPos.y, bookPos.w, bookPos.h);
        }

        if(book.color) {
            ctx.fillStyle = book.color;
            ctx.fillRect(bookPos.x, bookPos.y, bookPos.w, bookPos.h);
            
            ctx.font = "32px Old Standard TT";
            ctx.textAlign = "center";
            if(book.splitTitle) {
                for(let j = 0; j < book.splitTitle.length; j++) {
                    let depth = 0.5;
                    ctx.fillStyle = "rgb(150,150,150)";
                    ctx.fillText(book.splitTitle[j], bookPos.x + bookPos.w / 2 - depth, bookPos.y + 50 + 35 * j + depth);
                    ctx.fillStyle = "rgb(100,100,100)";
                    ctx.fillText(book.splitTitle[j], bookPos.x + bookPos.w / 2 + depth, bookPos.y + 50 + 35 * j - depth);
                    ctx.fillStyle = "rgb(255,255,255)";
                    ctx.fillText(book.splitTitle[j], bookPos.x + bookPos.w / 2, bookPos.y + 50 + 35 * j);
                }
            }
        }

        ctx.restore();

        ctx.save();

        ctx.translate(bookPos.x, bookPos.y);

        let curFireFrameIdx = Math.round(t * 30 + wheel[idx].r * fireFrameImgs[rarity].length)%fireFrameImgs[rarity].length;

        let curFireFrame = fireFrameImgs[rarity][curFireFrameIdx];

        if(curFireFrame) {
            let xOff = 22;
            let yOff = 33;
            ctx.drawImage(curFireFrame, -xOff, -yOff, bookPos.w+2*xOff, bookPos.h+2*yOff);
        }
        
        ctx.restore();

        ctx.save();

        let starSize = 16;

        ctx.lineWidth = 2;
        ctx.fillStyle = 'rgb(255,255,200)';
        ctx.strokeStyle = 'rgb(255,200,0)';
        ctx.shadowBlur = 10;
        ctx.shadowColor = "rgb(255,200,0)";

        for(let j = 0; j < rarity; j++) {
            let x = bookPos.x + coverWidth/2 - (rarity - 1)/2*(2*starSize+5) + j*(2*starSize+5);
            drawStar(x, bookPos.y + coverHeight + 30, 5, starSize, starSize/2);
            ctx.stroke();
            ctx.fill();
        }

        ctx.restore();
    }

    ctx.drawImage(metal_arrow_img, width/2 - metal_arrow_img.width/4, -20, metal_arrow_img.width/2, metal_arrow_img.height/2);
}

function onDismissModal() {
    $('#buttonOpen').show();
}

function onOpenClick() {
    console.log("OPEN!");
    if(spinned) {
        resetWheel();
    }
    spinned = true;
    startSpin = true;
    
    $('#buttonOpen').fadeOut();
}

function resetWheel() {
    wheel = [];
    generateWheel(30);
    wheel.push({rarity: 5, idx: 0, r: Math.random()});
    generateWheel(5);

    x = 0;
    startSpin = false;
}

function generateWheel(num) {
    let chance = [0,256,128,64,8,1];
    let total = 0;
    for(let r = 1; r <= 5; r++) {
        if(obj[r].length != 0) {
            total += chance[r];
            chance[r] = total - chance[r];
        }
    }
    function dice() {
        let rng = Math.random() * total;
        for(let r = 5; r >= 1; r--) {
            if(rng > chance[r]) {
                return r;
            }
        }
        return 1;
    }
    for(let i = 0; i < num; i++) {
        let rarity = dice();
        wheel.push({rarity: rarity, idx: Math.floor(Math.random() * obj[rarity].length), r: Math.random()});
    }
    //console.log(wheel);
}

let empty_cover_img = new Image();
empty_cover_img.src = "empty_cover_small.png";

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
                    obj[i][j].img = empty_cover_img;
                    obj[i][j].color = `rgba(${rbg[0]},${rbg[1]},${rbg[2]},0.5)`;
                } else {
                    let div0 = document.getElementById("cover-" + i + "-" + j);
                    div0.src = this.src;
                    div0 = document.getElementById("cover-back-" + i + "-" + j);
                    div0.src = this.src;
                    div0 = document.getElementById("cover-title-" + i + "-" + j);
                    div0.style.display = "none";
                    div0 = document.getElementById("cover-back-title-" + i + "-" + j);
                    div0.style.display = "none";
                    obj[i][j].img = this;
                }
            };
            img.onerror = function() {
            };
            img.src = "http://covers.openlibrary.org/b/olid/" + book_og_key + "-M.jpg";
        }
    }

    for(let i = 0; i <= 20; i++) {
        for(let j = 3; j <= 5; j++) {
            fireFrameImgs[j].push(new Image());
            fireFrameImgs[j][i].src = `fire_frame_${j}/frame_${String(i).padStart(2, '0')}_delay-0.03s.png`;
        }
        
    }

    console.log(fireFrameImgs);

    resetWheel();

    for(let i = 1; i <= 5; i++) {
        for(let j = 0; j < obj[i].length; j++) {
            let s = obj[i][j].title;
            let result = [];
            let words = s.split(" ");
            let curLine = "";
            let lineMax = 12;
            for(word of words) {
                if(word.length + curLine.length + 1 > lineMax) {
                    result.push(curLine);
                    curLine = word;
                } else {
                    curLine += ' ' + word;
                }
            }
            result.push(curLine);
            obj[i][j].splitTitle = result;
        }
    }
    console.log(obj);

    $('#bookModal').modal('show');

    setInterval(onFrame, 1000 / 60);
});
