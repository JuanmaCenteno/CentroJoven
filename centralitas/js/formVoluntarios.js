$(document).ready(function () {
    var plataforma = $('#inputPlataforma').val();
    var id = $('#inputRevendedor').val();
    $('#selectNumLineas').on('change', function (e) {
        loadLineas(this.value);
        $('#lineas').fadeIn();
        
        
    });
    $('#selectNumDepartamentos').on('change', function (e) {
        loadDepartamentos(this.value);
        $('#departamentos').fadeIn();
    });
    // POST
    $('#formData').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: 'infoForm.php',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function () {
                finForm();
            },
            error: function (e) {
                console.log("There was an error with your request...");
                console.log("error: " + JSON.stringify(e));
            }
        });
        return false;
    });
    $('#selectCodigoCliente').on('change', function (e) {
        loadCif(this.value, plataforma);
    });
    loadComboClientes(id, plataforma);
    loadComboLineas();
    loadCombos(plataforma);
});

var loadComboClientes = function (id, plataforma) {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: "listaEmpresas",
            id: id,
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            cargarComboClientes(myJsonData);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
};

var loadCif = function (id, plataforma) {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: "loadCif",
            id: id,
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            document.getElementById("inputCif").value = myJsonData[0].cif;
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
};

var loadComboLineas = function () {
    $.ajax({
        type: 'GET',
        url: 'resources/1a100.json',
        contentType: "text/plain",
        dataType: 'json',
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            cargarCombo(myJsonData);
            cargarComboDepartamentos(myJsonData);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
};

var cargarCombo = function (datos) {
    var combo = document.getElementById("selectNumLineas");
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        var el = document.createElement("option");
        el.textContent = opt.text;
        el.value = opt.value;
        combo.appendChild(el);
    }
    $('#selectNumLineas').selectpicker('refresh');
};

var cargarComboDepartamentos = function (datos) {
    var combo2 = document.getElementById("selectNumDepartamentos");
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        var el = document.createElement("option");
        el.textContent = opt.text;
        el.value = opt.value;
        combo2.appendChild(el);
    }
    $('#selectNumDepartamentos').selectpicker('refresh');
};


var cargarComboClientes = function (datos) {
    var combo2 = document.getElementById("selectCodigoCliente");
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        var el = document.createElement("option");
        el.textContent = opt.apellidos;
        el.value = opt.id;
        combo2.appendChild(el);
    }
    $('#selectCodigoCliente').selectpicker('refresh');
};


var loadCombos = function (plataforma) {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: "tiposConexion",
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            cargarComboConexion(myJsonData, "selectTipoConexion");
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });

    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: "tiposElectronica",
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            cargarComboConexion(myJsonData, "selectTipoElectronica");
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });

};

var cargarComboConexion = function (datos, elem) {
    var combo = document.getElementById(elem);
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        var el = document.createElement("option");
        el.textContent = opt.nombre;
        el.value = opt.id;
        combo.appendChild(el);
    }
    $('#' + elem).selectpicker('refresh');
};


var loadLineas = function (num, plataforma) {
    $.ajax({
        type: 'GET',
        url: 'getDesplegables.php',
        contentType: "text/plain",
        data: {
            accion: "lineas",
            num: num
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            document.getElementById("lineas").innerHTML = myJsonData;
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

var loadDepartamentos = function (num) {
    $.ajax({
        type: 'GET',
        url: 'getDesplegables.php',
        contentType: "text/plain",
        data: {
            accion: "departamentos",
            num: num
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            document.getElementById("departamentos").innerHTML = myJsonData;
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

var finForm = function () {
    alert('Formulario enviado');
    $('#formData')[0].reset();
    $('#selectCodigoCliente').selectpicker('val', '');
    $('#selectTipoConexion').selectpicker('val', '');
    $('#selectTipoElectronica').selectpicker('val', '');
    $('#selectNumLineas').selectpicker('val', '-');
    $('#selectNumDepartamentos').selectpicker('val', '-');
    $('#lineas').fadeOut();
    $('#departamentos').fadeOut();
}
