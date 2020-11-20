var HttpReq = null;
var dest_combo = null;

function ajaxComboBox2(url, comboBox){
    dest_combo = comboBox;
    var indice = document.getElementById('sel_fab').selectedIndex;
    var id_sel_fab = document.getElementById('sel_fab').options[indice].getAttribute('value');

    var indice1 = document.getElementById('sel_item').selectedIndex;
    var id_sel_item = document.getElementById('sel_item').options[indice1].getAttribute('value');    
    
    url = url + '?sel_fab=' + id_sel_fab + '&sel_item=' + id_sel_item;

    if (document.getElementById) { //Verifica se o Browser suporta DHTML.
        if (window.XMLHttpRequest) {
            HttpReq = new XMLHttpRequest();
            HttpReq.onreadystatechange = XMLHttpRequestChange;
            HttpReq.open("GET", url, true);
            HttpReq.send(null);
        } else if (window.ActiveXObject) {
            HttpReq = new ActiveXObject("Microsoft.XMLHTTP");
            if (HttpReq) {
                HttpReq.onreadystatechange = XMLHttpRequestChange;
                HttpReq.open("GET", url, true);
                HttpReq.send();
            }
        }
    }
}

function XMLHttpRequestChange() {
    if (HttpReq.readyState == 4 && HttpReq.status == 200){  //Verifica se o arquivo foi carregado com sucesso.
        var result = HttpReq.responseXML;
        var sel_mod = result.getElementsByTagName("nome");
        document.getElementById(dest_combo).innerHTML = "";
        for (var i = 0; i < sel_mod.length; i++) {
            new_opcao = create_opcao(sel_mod[i]);
            document.getElementById(dest_combo).appendChild(new_opcao);
        }
    }
}

function create_opcao(sel_mods) { //Cria um novo elemento OPTION.
    //return opcao.cloneNode(true);
    var new_opcao = document.createElement("option"); //Cria um OPTION.
    var texto = document.createTextNode(sel_mods.childNodes[0].data); //Cria um texto.
    new_opcao.setAttribute("value",sel_mods.getAttribute("id")); //Adiciona o atributo de valor a nova opção.
    new_opcao.appendChild(texto); //Adiciona o texto a OPTION.
    return new_opcao; // Retorna a nova OPTION.
}
