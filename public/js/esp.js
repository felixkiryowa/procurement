//disable multiple submits
$(".form-prevent-multiple-submits").on("submit", function() {
    $(".button-prevent-multiple-submits").attr("disabled", "true");
    $(".spinner").show();
});

$(document).ready(function() {
    $('.select2').select2();

    $('#dataTable').DataTable({
        processing: true,

    });

    $('#dataTable2').DataTable({
        processing: true,

    });
});