let empty_cover_img = new Image();
empty_cover_img.src = "assets/empty_cover_small.png";

let popoverIdx = -1;

let menu = "default";
let tradeUpInventory = {};

let tradeUpIdx = 0;

function onBookClicked(i) {
    console.log(i + " clicked!");

    if (menu == "default") {
        if (popoverIdx != -1) {
            console.log(popoverIdx + " hid!");
            $(`#book-container3d-${popoverIdx}`).removeClass("book-focused");
            $(`#book-popover-${popoverIdx}`).popover('hide');
        }

        if (popoverIdx == i) {
            popoverIdx = -1;
        } else {
            popoverIdx = i;
            console.log(popoverIdx + " shown!");
            $(`#book-container3d-${popoverIdx}`).addClass("book-focused");

            $(`#book-popover-${popoverIdx}`).popover({
                html: true,
                fallbackPlacement: ['bottom'],
                boundary: "scrollParent",
                content: function() {
                    return '<div id="book-popover-content-current">Loading...</div>';
                }
            });
            $(`#book-popover-${popoverIdx}`).popover('show');

            let xhr = new XMLHttpRequest();
            xhr.open("GET", `book_detail_popover.php?id=${inventory[popoverIdx].book_id}`, true);
            xhr.onload = function(e) {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        let response = xhr.responseText;
                        console.log(response);
                        $("#book-popover-content-current").html(response);
                        $(`#book-popover-${popoverIdx}`).popover('update');
                        $(function() {
                            $(".col-md-2").tooltip('enable');
                        });
                    } else {
                        console.error(xhr.statusText);
                    }
                }
            };
            xhr.onerror = function(e) {
                console.error(xhr.statusText);
            };
            xhr.send(null);
        }
    } else if (menu == "trade-up") {
        if (inventory[i].amount > 0 && Object.keys(tradeUpInventory).length < 5) {
            inventory[i].amount--;
            if (inventory[i].amount == 1) {
                $(`#book-amount-${i}`).parent().parent().css("display", "none");
            } else if (inventory[i].amount == 0) {
                $(`#book-amount-${i}`).parent().parent().parent().css("display", "none");
            } else {
                $(`#book-amount-${i}`).html(inventory[i].amount);
            }
            tradeUpIdx++;
            tradeUpInventory[tradeUpIdx] = i;
            $(`#trade_up_container`).append(`
            <div id="book-box-trade-up-${tradeUpIdx}" class="book_box book_box_rarity_${inventory[i].rarity} book_box_rarity_trade_up">
            ${$(`#book-box-${i}`).html()
            .replace("book-amount", "book-amount-trade-up")}
            </div>`);

            $(`#book-box-trade-up-${tradeUpIdx} .amount-container`).remove();

            let j = tradeUpIdx;
            $(`#book-box-trade-up-${tradeUpIdx}`).click(function(e) {
                onTradeUpBookClicked(j);
            });
        }
    }
}

function onTradeUpBookClicked(i) {
    console.log("Trade Up " + i + " clicked!");

    $(`#book-box-trade-up-${i}`).remove();

    inventory[tradeUpInventory[i]].amount++;
    
    j = tradeUpInventory[i];
    if (inventory[j].amount == 1) {
        $(`#book-amount-${j}`).parent().parent().parent().show();
    } else if (inventory[j].amount == 2) {
        $(`#book-amount-${j}`).parent().parent().show();
        $(`#book-amount-${j}`).html(inventory[j].amount);
    } else {
        $(`#book-amount-${j}`).html(inventory[j].amount);
    }

    delete tradeUpInventory[i]; 
}

function onSortChanged() {
    let selectedVal = $("#sort-select option:selected").val()
    console.log(selectedVal);

    let maxOrder = 0;

    for (let j = 0; j < inventory.length; j++) {
        if (selectedVal == "amount") {
            maxOrder = Math.max(maxOrder, inventory[j].amount);
        } else if (selectedVal == "rarity") {
            order = Math.max(maxOrder, j);
        }
    }

    for (let j = 0; j < inventory.length; j++) {
        let order = -1;
        if (selectedVal == "amount") {
            order = maxOrder - inventory[j].amount;
        } else if (selectedVal == "rarity") {
            order = j;
        }
        $(`#book-box-${j}`).css("order", order);
    }
}

function tradeUpClicked() {
    menu = "trade-up";
    $('#trade-up-collapse').collapse('show');
    $('#default-menu').slideUp(complete = () => {
        $('#trade-up-menu').slideDown();
    });
}

function editDeckClicked() {

}

function tradeUpFill3Clicked() {
    for (let j = 0; j < inventory.length && Object.keys(tradeUpInventory).length < 5; j++) {
        if(inventory[j].rarity == 3) {
            while(inventory[j].amount > 0 && Object.keys(tradeUpInventory).length < 5) {
                onBookClicked(j);
            }
        }
    }
}

function tradeUpFill4Clicked() {
    for (let j = 0; j < inventory.length && Object.keys(tradeUpInventory).length < 5; j++) {
        if(inventory[j].rarity == 4) {
            while(inventory[j].amount > 0 && Object.keys(tradeUpInventory).length < 5) {
                onBookClicked(j);
            }
        }
    }
}

function tradeUpInitClicked() {

    let requestBody = {trade_up: []};
    
    for (const book in tradeUpInventory) {
        requestBody.trade_up.push(inventory[tradeUpInventory[book]].book_id);
    }

    redirectPost("#", requestBody);
}

function tradeUpCancelClicked() {
    menu = "default";
    $('#trade-up-collapse').collapse('hide');
    $('#trade-up-menu').slideUp(complete = () => {
        $('#default-menu').slideDown();
    });
}

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

    for (let j = 0; j < inventory.length; j++) {
        $(`#book-container3d-${j}`).click(function(e) {
            onBookClicked(j);
        });
    }
});