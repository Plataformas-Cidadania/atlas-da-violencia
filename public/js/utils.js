function convertHex(hex, opacity) {
    hex = hex.replace('#', '');
    r = parseInt(hex.substring(0, 2), 16);
    g = parseInt(hex.substring(2, 4), 16);
    b = parseInt(hex.substring(4, 6), 16);

    result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')';
    return result;
}

function formatNumber(n, c, d, t) {

    let multiplo = 1;
    for (let i = 0; i < c; i++) {
        multiplo *= 10;
    }

    //console.log(multiplo);
    //console.log(parseFloat(n));
    //console.log(Math.round(n*multiplo)/multiplo);

    var n = Math.round(n * multiplo) / multiplo,
        c = isNaN(c = Math.round(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");

    //console.log(formatNumber(999999999999.165, 0, ',', '.'));
}

function downloadImage(element, btn, arquivo) {
    //$("#btn-Convert-Html2Image").on('click', function () {
    console.log(element, btn, arquivo);
    html2canvas(element, {
        onrendered: function (canvas) {
            //$("#previewImage").append(canvas);

            var imageData = canvas.toDataURL("image/png");
            var newData = imageData.replace(/^data:image\/png/, "data:application/octet-stream");

            //$("#btn-Convert-Html2Image").attr("download", "nome_arquivo.png").attr("href", newData);
            $("#" + btn).attr("download", arquivo).attr("href", newData);

            //$('#divhidden').html('<img src="'+newData+'" alt="">');
            //print('divhidden');
        }
    });
}

function printCanvas(canvasId) {
    let tela_impressao = window.open('');
    tela_impressao.document.open();
    tela_impressao.document.write("<br><img src='" + document.getElementById(canvasId).toDataURL() + "'/>");
    tela_impressao.document.close();
    tela_impressao.focus();
    tela_impressao.print();
    tela_impressao.close();
}

function downloadCanvas(linkId, canvasId, filename) {
    let link = document.getElementById(linkId);
    /*console.log(link);
    console.log(canvasId);
    console.log(filename);*/
    link.href = document.getElementById(canvasId).toDataURL();
    link.download = filename;
}

function formatPeriodicidade(statePeriodo, periodicidade) {
    let periodo = null;
    if (statePeriodo) {
        periodo = statePeriodo.toString();
        if (periodicidade === "Anual") {
            periodo = periodo.substr(0, 4);
        }
        if (periodicidade === "Semestral" || periodicidade === "Trimestral" || periodicidade === "Mensal") {
            periodo = periodo.substr(0, 7);
        }
    }
    return periodo;
}