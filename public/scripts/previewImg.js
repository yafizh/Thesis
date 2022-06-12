function previewImage(input) {
    const imgPlaceholder = document.querySelector(".image-placeholder");
    const imgPreview = imgPlaceholder.nextElementSibling;
    imgPlaceholder.classList.add("d-none");
    imgPreview.classList.remove("d-none");

    const oFReader = new FileReader();
    oFReader.readAsDataURL(input.files[0]);

    oFReader.onload = function (oFREvent) {
        imgPreview.src = oFREvent.target.result;
    };
}
