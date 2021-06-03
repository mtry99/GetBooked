
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('active');
    });

    $("#pages-range-slider").slider({
        range: true,
        min: 0,
        max: 1500,
        values: [book_filter["page_min"], book_filter["page_max"]],
        slide: function (event, ui) {
            $("#pages-amount1").val("" + ui.values[0]);
            $("#pages-amount2").val("" + ui.values[1]);
        }
    });
    $("#pages-amount1").val($("#pages-range-slider").slider("values", 0));
    $("#pages-amount2").val($("#pages-range-slider").slider("values", 1));
    $("#pages-amount1").change(function() {
        $("#pages-range-slider").slider("values", 0, $("#pages-amount1").val());
    });
    $("#pages-amount2").change(function() {
        $("#pages-range-slider").slider("values", 1, $("#pages-amount2").val());
    });
    if(book_filter["page_on"]) {
        $("#switch-pages").attr('checked','checked');
        $("#collapse-pages").collapse('show');
    }

    $("#year-range-slider").slider({
        range: true,
        min: 1500,
        max: 2021,
        values: [book_filter["year_min"], book_filter["year_max"]],
        slide: function (event, ui) {
            $("#year-amount1").val("" + ui.values[0]);
            $("#year-amount2").val("" + ui.values[1]);
        }
    });
    $("#year-amount1").val($("#year-range-slider").slider("values", 0));
    $("#year-amount2").val($("#year-range-slider").slider("values", 1));
    $("#year-amount1").change(function() {
        $("#year-range-slider").slider("values", 0, $("#year-amount1").val());
    });
    $("#year-amount2").change(function() {
        $("#year-range-slider").slider("values", 1, $("#year-amount2").val());
    });
    if(book_filter["year_on"]) {
        $("#switch-year").attr('checked','checked');
        $("#collapse-year").collapse('show');
    }

    if(book_filter["in_stock"]) {
        $("#in-stock-check").attr('checked','checked');
    }

    $("#input-title").val(book_filter["title"]);
    $("#input-author").val(book_filter["author"]);
    $("#input-publisher").val(book_filter["publisher"]);
    $("#input-genre").val(book_filter["genre"]);

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

function apply_filter() {

    let filter_obj = {
        page_on: $("#switch-pages").is(":checked"),
    
        year_on: $("#switch-year").is(":checked"),

        title: $("#input-title").val(),
        author: $("#input-author").val(),
        publisher: $("#input-publisher").val(),

        genre: $("#input-genre").val()
        .split(",").map((x) => {
            return x.trim().toLowerCase();
        }).filter((x) => {
            return x !== "";
        }).join(","),

        in_stock: $("#in-stock-check").is(":checked"),
    };

    filter_obj.page_min = $("#pages-amount1").val();
    filter_obj.page_max = $("#pages-amount2").val();
    
    filter_obj.year_min = $("#year-amount1").val();
    filter_obj.year_max = $("#year-amount2").val();

    //$("#input-genre").val(filter_obj.genre.join(", "));

    console.log(filter_obj);

    let url = window.location.origin + "/book.php?";

    if(filter_obj.title !== "") {
        url += "title=" + encodeURIComponent(filter_obj.title) + "&";
    }
    if(filter_obj.author !== "") {
        url += "author=" + encodeURIComponent(filter_obj.author) + "&";
    }
    if(filter_obj.publisher !== "") {
        url += "publisher=" + encodeURIComponent(filter_obj.publisher) + "&";
    }
    if(filter_obj.genre !== "") {
        url += "genre=" + encodeURIComponent(filter_obj.genre) + "&";
    }
    if(filter_obj.in_stock) {
        url += "in_stock=" + encodeURIComponent(filter_obj.in_stock) + "&";
    }
    if(filter_obj.page_on) {
        url += "page_on=" + encodeURIComponent(filter_obj.page_on) + "&";
        url += "page_min=" + encodeURIComponent(filter_obj.page_min) + "&";
        url += "page_max=" + encodeURIComponent(filter_obj.page_max) + "&";
    }
    if(filter_obj.year_on) {
        url += "year_on=" + encodeURIComponent(filter_obj.year_on) + "&";
        url += "year_min=" + encodeURIComponent(filter_obj.year_min) + "&";
        url += "year_max=" + encodeURIComponent(filter_obj.year_max) + "&";
    }

    url = url.replace(/&\s*$/, "");

    console.log(url);

    // sad,,as,df,asd,,a3,5,35,as,35a,s,5a ,,asa, d< B#%,  <#$^<,W$^<
    // http://localhost/book.php?title=df&author=sdfsd&publisher=sdfs&genre=sad%2Cas%2Cdf%2Casd%2Ca3%2C5%2C35%2Cas%2C35a%2Cs%2C5a%2Casa%2Cd%3C%20b%23%25%2C%3C%23%24%5E%3C%2Cw%24%5E%3C&in_stock=true&page_on=true&page_min=224&page_max=814&year_on=true&year_min=1753&year_max=1925

    window.location.href = url;

    return false;
}

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