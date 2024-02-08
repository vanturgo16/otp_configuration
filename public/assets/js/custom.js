$(document).on("shown.bs.modal", ".modal", function () {
    $(".js-example-basic-single").select2({
        dropdownParent: this,
    });
});
