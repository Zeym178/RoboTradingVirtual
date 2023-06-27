var intervalID;
var chart;
var candleSeries;
var data = {};
var markers = {};
var activeIndex = 'index1';
var fbot = false;
var botdir = false;
var botini = 0;
var botaccion = 0.0;

$(document).ready(function() {
    data.index1 = generateInitialData(1000);
    data.index2 = generateInitialData(1000);
    data.index3 = generateInitialData(1000);
    data.index4 = generateInitialData(1000);
    data.index5 = generateInitialData(1000);


    createCandlestickChart('chart', data.index1);
    updateIndicators(data.index1[data.index1.length - 1]);
    getuserdata();

    intervalID = setInterval(function() {
        var newData = generateRealtimeData();
        updateCandlestickChart(newData);
        updateIndicators(newData[activeIndex]);
    }, 1000);
});

$('#botSwitch').change(function() {
    fbot = $(this).prop('checked');
    if (fbot) {
        botini = data[activeIndex][data[activeIndex].length - 1].close;
        console.log(botini);
        botdir = false;
    } else {
        fbot = false;
        botdir = false;
        botini = 0;
        botaccion = 0.0;
    }
});

$('#cantidadcompra').keyup('input', function() {
    var cantidad = parseFloat($(this).val());
    var precioCompra = parseFloat($('.preciocompra').text());
    var estimadoCompra;
    if ($('#buyselect').val() === 'buyaccion') {
        estimadoCompra = (cantidad * precioCompra).toFixed(4);
    } else {
        estimadoCompra = (cantidad / precioCompra).toFixed(4);
    }
    $('#estimadocompra').text(estimadoCompra);
});

$('#cantidadventa').keyup('input', function() {
    var cantidad = parseFloat($(this).val());
    var precioCompra = parseFloat($('.preciocompra').text());
    var estimadoVenta;
    if ($('#sellselect').val() === 'sellaccion') {
        estimadoVenta = (cantidad * precioCompra).toFixed(4);
    } else {
        estimadoVenta = (cantidad / precioCompra).toFixed(4);
    }
    $('#estimadoventa').text(estimadoVenta);
});

$('#buyForm').submit(function(event) {
    event.preventDefault();
    var compra = parseFloat($('#cantidadcompra').val());
    var cantidad = parseFloat($('#estimadocompra').text());
    var indexname = $('option[value="' + activeIndex + '"]').text();
    if ($('#buyselect').val() === 'buyaccion') {
        var aux = cantidad;
        cantidad = compra;
        compra = aux;
    }
    realizarAccion(compra * -1, cantidad, indexname);
});

$('#sellForm').submit(function(event) {
    event.preventDefault();
    var compra = parseFloat($('#cantidadventa').val());
    var cantidad = parseFloat($('#estimadoventa').text());
    var indexname = $('option[value="' + activeIndex + '"]').text();
    if ($('#sellselect').val() === 'sellaccion') {
        var aux = cantidad;
        cantidad = compra;
        compra = aux;
    }
    realizarAccion(compra, cantidad * -1, indexname);
});

function realizarAccion(comprap, cantidadp, indexname) {
    var tipo = 'Compra';
    var dinerocuenta = parseFloat($('#dinerocuenta').text());
    var valacciones = parseFloat($('#valacciones').text());
    if (comprap > 0) {
        tipo = 'Venta';
        if (valacciones + cantidadp < 0) return;
    }
    if (dinerocuenta + comprap < 0) return;
    var precio = parseFloat($('.preciocompra').text());
    //var fechaHora = new Date().toISOString();

    var fechaHora = new Date();

    var fecha = fechaHora.getFullYear() + '-' + (fechaHora.getMonth() + 1).toString().padStart(2, '0') + '-' + fechaHora.getDate().toString().padStart(2, '0');
    var hora = fechaHora.getHours().toString().padStart(2, '0') + ':' + fechaHora.getMinutes().toString().padStart(2, '0') + ':' + fechaHora.getSeconds().toString().padStart(2, '0');

    var fechaHora = fecha + 'T' + hora;

    //console.log(fechaHoraFormateada);

    newmarker = {
        time: data[activeIndex][data[activeIndex].length - 1].time,
        position: 'aboveBar',
        color: tipo === 'Compra' ? '#008000' : '#ff0000',
        shape: 'circle',
        text: tipo
    };
    if (!(markers[activeIndex])) markers[activeIndex] = [];
    markers[activeIndex].push(newmarker);
    candleSeries.setMarkers(markers[activeIndex]);

    $.ajax({
        url: './acc/registrar_transaccion.php',
        method: 'POST',
        data: {
            tipo: tipo,
            cantidad: cantidadp,
            total: comprap,
            precio: precio,
            fechaHora: fechaHora,
            indexname: indexname
        },
        success: function(response) {
            console.log('Transacción registrada: ' + response);
            getuserdata();
            //$('#dinerocuenta').text(dinerotot);
            //$('#valacciones').text(accionestot);
            $('#buyForm')[0].reset();
            $('#buyModal').modal('hide');
            $('#sellForm')[0].reset();
            $('#sellModal').modal('hide');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al registrar la transacción:', errorThrown);
        }
    });
}

function getuserdata() {
    var indexname = $('option[value="' + activeIndex + '"]').text();
    $.ajax({
        url: './acc/get-userdata.php',
        method: 'POST',
        data: {
            indexname: indexname
        },
        dataType: 'json',
        success: function(response) {
            $('#dinerocuenta').text(response.dinero);
            $('#valacciones').text(response.acciones);
        },
        error: function(errorThrown) {
            console.error('Error al traer los datos del usuario:', errorThrown);
        }
    });
}

function stopInterval() {
    clearInterval(intervalID);
}
var dir = 1;

function generateInitialData(count) {
    var startDate = new Date();
    startDate.setDate(startDate.getDate() - count);

    var firstopen = getRandomPrice(10000, 11000);

    var data = [];

    for (var i = 1; i <= count; i++) {
        var date = new Date(startDate);
        date.setDate(date.getDate() + i);

        var open = firstopen;
        var high, low, close;
        if (i % 5 === 0) {
            dir = getRandomTrend();
        }
        if (dir === 1) {
            high = getRandomPrice(open, open + 10);
            low = getRandomPrice(open - 5, open);
            close = getRandomPrice(low, high);
        } else {
            high = getRandomPrice(open, open + 5);
            low = getRandomPrice(open - 10, open);
            close = getRandomPrice(low, high);
        }
        firstopen = close;

        data.push({ time: date.getTime(), open: open, high: high, low: low, close: close });
    }

    return data;
}

function generateRealtimeData() {
    var newData = {};

    for (var index in data) {
        var lastData = data[index][data[index].length - 1];
        var date = new Date();

        var open = lastData.close;
        var high, low, close;
        var currentIndex = data[index].length;


        if (currentIndex % 5 === 0) {
            dir = getRandomTrend();
        }
        if (dir === 1) {
            high = getRandomPrice(open, open + 10);
            low = getRandomPrice(open - 5, open);
            close = getRandomPrice(low, high);
        } else {
            high = getRandomPrice(open, open + 5);
            low = getRandomPrice(open - 10, open);
            close = getRandomPrice(low, high);
        }

        newData[index] = { time: date.getTime(), open: open, high: high, low: low, close: close };
    }
    var firstData = data.index1[0];
    var firstDataTime = new Date(newData.index1.time);
    console.log(firstDataTime);

    return newData;
}

function getRandomTrend() {
    return Math.random() >= 0.5 ? 1 : -1;
}

function getRandomPrice(min, max) {
    return parseFloat((Math.random() * (max - min) + min).toFixed(2));
}

function updateCandlestickChart(newData) {

    for (var index in newData) {
        data[index].push(newData[index]);

        if (index === activeIndex) {
            candleSeries.setData(data[index]);

        }
    }

    if (fbot === true) {
        var botprev = data[activeIndex][data[activeIndex].length - 2].close;
        if (botdir) {
            if (newData[activeIndex].close < botprev && newData[activeIndex].close - botini > 0) {
                botini = newData[activeIndex].close;
                botdir = false;
                var precioCompra = parseFloat($('.preciocompra').text());
                var cantidad = (botaccion * precioCompra).toFixed(4);
                var indexname = $('option[value="' + activeIndex + '"]').text();
                realizarAccion(cantidad, botaccion * -1, indexname);

                console.log('venta');
            }
        } else {
            if (newData[activeIndex].close > botprev && botini - newData[activeIndex].close > 0) {
                var dinerocuenta = parseFloat($('#dinerocuenta').text());
                var compra = parseFloat($('#botInput').val());
                if (($('#botInput').val()).trim() === '' || dinerocuenta - compra < 0) return;

                botini = newData[activeIndex].close;
                botdir = true;
                var precioCompra = parseFloat($('.preciocompra').text());
                var cantidad = (compra / precioCompra).toFixed(4);
                var indexname = $('option[value="' + activeIndex + '"]').text();
                realizarAccion(compra * -1, cantidad, indexname);
                botaccion = cantidad;

                console.log('compra');
            }
        }
    }

}

function createCandlestickChart(containerId, data) {
    const chartProperties = {
        height: 400,
        timeScale: {
            timeVisible: true,
            secondsVisible: false,
            tickMarkFormatter: (time, tickMarkType, locale) => {
                var date = new Date(time);
                var currentDate = new Date();
                if (Math.abs(date - currentDate) < 86400000) {
                    return date.toLocaleString(locale, { day: 'numeric', month: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' });
                } else {
                    return date.toLocaleString(locale, { day: 'numeric', month: 'numeric' });
                }
            }
        }
    };

    const domElement = document.getElementById(containerId);
    chart = LightweightCharts.createChart(domElement, chartProperties);
    candleSeries = chart.addCandlestickSeries();
    candleSeries.setData(data);
}

$('#index-select').change(function() {
    $('#botSwitch').prop('checked', false);
    fbot = false;
    botdir = false;
    botini = 0;
    botaccion = 0.0;

    activeIndex = $(this).val();
    candleSeries.setData(data[activeIndex]);
    if (!(markers[activeIndex])) markers[activeIndex] = [];
    candleSeries.setMarkers(markers[activeIndex]);
    getuserdata();
    updateIndicators(data[activeIndex][data[activeIndex].length - 1]);
});

function updateIndicators(dataf) {
    var lastDate = new Date(dataf.time);
    var day = lastDate.getDate();
    var month = lastDate.getMonth();
    var year = lastDate.getYear();

    var maxDay = -Infinity;
    var minDay = Infinity;
    var maxMonth = -Infinity;
    var minMonth = Infinity;
    var todayopen = null;

    data[activeIndex].filter(function(datum) {
        var date = new Date(datum.time);
        return date.getDate() === day && date.getMonth() === month && date.getYear() === year;
    }).forEach(function(datum) {
        maxDay = Math.max(maxDay, datum.high);
        minDay = Math.min(minDay, datum.low);
        if (todayopen === null) todayopen = datum.open;
    });

    data[activeIndex].filter(function(datum) {
        var date = new Date(datum.time);
        return date.getMonth() === month && date.getYear() === year;
    }).forEach(function(datum) {
        maxMonth = Math.max(maxMonth, datum.high);
        minMonth = Math.min(minMonth, datum.low);
    });

    var f = new Date(data.time);
    $('.preciocompra').text(dataf.close);
    $('#indicator-open').text(todayopen);
    $('#indicator-high').text(maxDay);
    $('#indicator-low').text(minDay);
    $('#indicator-monthly-high').text(maxMonth);
    $('#indicator-monthly-low').text(minMonth);

    var acciones = parseFloat($('#valacciones').text());
    $('#valmercado').text((dataf.close * acciones).toFixed(2));
}