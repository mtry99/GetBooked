function collection_clicked(id) {
    console.log("collection_clicked: " + id);

    url = "/collection_detail.php?id=" + id;

    console.log(url);

    window.location.href = url;

    return false;
}