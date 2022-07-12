function convertHex(hex,opacity){
    hex = hex.replace('#','');
    r = parseInt(hex.substring(0,2), 16);
    g = parseInt(hex.substring(2,4), 16);
    b = parseInt(hex.substring(4,6), 16);

    result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
    return result;
}


function formatNumber(n, c, d, t){

    let multiplo = 1;
    for(let i=0; i<c; i++){
        multiplo*=10;
    }

    //console.log(multiplo);
    //console.log(parseFloat(n));
    //console.log(Math.round(n*multiplo)/multiplo);

    var n = Math.round(n*multiplo)/multiplo,
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
            $("#"+btn).attr("download", arquivo).attr("href", newData);

            //$('#divhidden').html('<img src="'+newData+'" alt="">');
            //print('divhidden');

        }
    });
}


function printCanvas(canvasId){
    let tela_impressao = window.open('');
    tela_impressao.document.open();
    tela_impressao.document.write("<br><img src='"+document.getElementById(canvasId).toDataURL()+"'/>");
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

function formatPeriodicidade(statePeriodo, periodicidade){
    let periodo = null;
    if(statePeriodo){
        periodo = statePeriodo.toString();
        if(periodicidade==="Anual"){
            periodo = periodo.substr(0, 4);
        }
        if(periodicidade==="Semestral" || periodicidade==="Trimestral" || periodicidade==="Mensal"){
            periodo = periodo.substr(0, 7);
        }
    }
    return periodo;
}


function gerarIntervalos2(valores){
    let intervalos = [];
    let max = parseInt(valores[valores.length-1]);
    let maxUtil = parseInt(max - max * 10 / 100);
    let qtdIntervalos = 10;
    let intervalo = parseInt(maxUtil / qtdIntervalos);

    let rounder =  intervalo % 1000 > 100 ? 100 : intervalo % 100 > 10 ? 10 : 1;
    intervalo = Math.ceil(intervalo/rounder) * rounder;
    //console.log(intervalo);
    intervalos[0] = 0;
    intervalos[9] = maxUtil;
    for(let i=1;i<qtdIntervalos;i++){
        //intervalos[i] = intervalos[i-1] + intervalo;
        intervalos[i] = intervalos[i-1] + intervalo/qtdIntervalos*i;//intervalo/qtdIntervalos*i irá gerar um intervalo gradativo
    }
    return intervalos;
}

function gerarIntervalos(min,max, qtdIntervalos){
    let intervalos = [];

    min = parseInt(min);
    let minUtil = parseInt(min + min * 10 / 100);

    //console.log('min', min);
    //console.log('minUtil', minUtil);

    max = parseInt(max);
    let maxUtil = parseInt(max - max * 10 / 100);

    //console.log('max', max);
    //console.log('maxUtil', maxUtil);

    //let qtdIntervalos = 10;
    let intervalo = maxUtil >= 10 ? parseInt(maxUtil / qtdIntervalos) : (maxUtil / qtdIntervalos);


    //console.log('maxUtil', intervalo);
    //console.log(intervalo);
    //console.log('resto', intervalo % 100);
    let rounder =  intervalo % 1000 > 100 ? 100 : intervalo % 100 > 10 ? 10 : 1;
    //console.log('intervalo antes', intervalo);
    //console.log('rounder', rounder);
    intervalo = Math.ceil(intervalo/rounder) * rounder;
    //console.log('intervalo depois', intervalo);
    intervalos[0] = min;
    intervalos[1] = minUtil;
    intervalos[qtdIntervalos-1] = maxUtil;
    //console.log('intervalor[9]', intervalos[9]);
    for(let i=2;i<qtdIntervalos-1;i++){
        //intervalos[i] = intervalos[i-1] + intervalo;
        intervalos[i] = intervalos[i-1] + intervalo/qtdIntervalos*i;//intervalo/qtdIntervalos*i irá gerar um intervalo gradativo
    }
    return intervalos;
}

/*function gerarIntervalos(valores){
    let intervalos = [];

    let min = parseInt(valores[0]);
    let minUtil = parseInt(min + min * 10 / 100);

    let max = parseInt(valores[valores.length-1]);
    console.log(max+'-'+max+' * 10 / 100');
    let maxUtil = parseInt(max - max * 10 / 100);
    let qtdIntervalos = 10;
    let intervalo = maxUtil >= 10 ? parseInt(maxUtil / qtdIntervalos) : (maxUtil / qtdIntervalos);

    console.log('maxUtil', intervalo);
    //console.log(intervalo);
    //console.log('resto', intervalo % 100);
    let rounder =  intervalo % 1000 > 100 ? 100 : intervalo % 100 > 10 ? 10 : 1;
    console.log('intervalo antes', intervalo);
    console.log('rounder', rounder);
    intervalo = Math.ceil(intervalo/rounder) * rounder;
    console.log('intervalo depois', intervalo);
    intervalos[0] = min;
    intervalos[1] = minUtil;
    intervalos[9] = maxUtil;
    for(let i=2;i<qtdIntervalos;i++){
        //intervalos[i] = intervalos[i-1] + intervalo;
        intervalos[i] = intervalos[i-1] + intervalo/qtdIntervalos*i;//intervalo/qtdIntervalos*i irá gerar um intervalo gradativo
    }
    return intervalos;
}*/

function getColor(d, intervalos) {

    var colors = ['#4285F4', '#689DF6', '#8EB6F8', '#B3CEFB', '#F6D473', '#F1B567', '#ED965B', '#E87850',  '#E45A45',  '#E0433C'];

    var qtdIntervalos = intervalos.length;
    for(var i=qtdIntervalos-1; i>=0; i--){
        if(d > intervalos[i]){
            return colors[i];
        }
    }

    return colors[0];

}

function downloadTextToFile(filename, text) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}

function removeHTML(str){
    str = str.replaceAll('</p>', '\n\n');
    var tmp = document.createElement("DIV");
    tmp.innerHTML = str;
    return tmp.textContent || tmp.innerText || "";
}



