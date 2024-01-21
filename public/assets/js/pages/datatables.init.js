$(document).ready(function () {
    // Initialize the DataTable
    $("#datatable").DataTable();

    // Initialize the DataTable with export buttons, excluding the last column
    $("#datatable-buttons")
        .DataTable({
            lengthChange: !1,
            buttons: [
                {
                    extend: "excel",
                    exportOptions: {
                        columns: ":not(:last-child)",
                    },
                },
                {
                    extend: "pdf",
                    exportOptions: {
                        columns: ":not(:last-child)",
                    },
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

    // Add a class to the length select element
    $(".dataTables_length select").addClass("form-select form-select-sm");
});
