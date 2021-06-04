

function title_clicked(id) {
    console.log("title_clicked: " + id);

    url = "/book_detail.php?id=" + id;

    console.log(url);

    window.location.href = url;

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