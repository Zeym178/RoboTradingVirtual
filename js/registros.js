$(document).ready(function() {
    var currentPage = 1;
    var loading = false;

    fetchResults("", currentPage);

    $('#fecha, #accion').change(function() {
        $("tbody").empty();
        currentPage = 1;
        var fecha = $("#fecha").val();
        var accion = $("#accion").val();

        fetchResults(fecha, currentPage, accion);
    });

    function fetchResults(fecha, page, accion) {
        $.ajax({
            url: "./acc/obtener_registros.php",
            method: "POST",
            data: { fecha: fecha, page: page, accion: accion },
            dataType: "json",
            beforeSend: function() {
                $("#loading-indicator").show();
                loading = true;
            },
            success: function(data) {
                $("#loading-indicator").hide();

                $.each(data, function(index, record) {
                    var row = "<tr>" +
                        "<td>" + record.Accion + "</td>" +
                        "<td>" + record.Tipo + "</td>" +
                        "<td>" + record.FechaHora + "</td>" +
                        "<td>" + record.Cantidad + "</td>" +
                        "<td>" + record.Precio + "</td>" +
                        "<td>" + record.MontoTotal + "</td>" +
                        "</tr>";
                    $("tbody").append(row);
                });

                loading = false;
                checkShowMoreButton(data);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }

    function checkShowMoreButton(data) {
        var resultCount = data.length;
        var resultsPerPage = 5;

        if (resultCount >= resultsPerPage) {
            $("#show-more-btn").show();
        } else {
            $("#show-more-btn").hide();
        }
    }

    $("#show-more-btn").click(function() {
        currentPage++;
        var fecha = $("#fecha").val();
        var accion = $("#accion").val();

        fetchResults(fecha, currentPage, accion);
    });
});