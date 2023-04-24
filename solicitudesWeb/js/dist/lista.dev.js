"use strict";

$(document).ready(function () {
  definirDataTable();
  loadData('%');
});

var definirDataTable = function definirDataTable() {
  $('#solicitudes').removeAttr('width').DataTable({
    dom: 'lBfrtip',
    buttons: [{
      extend: 'copyHtml5',
      text: '<i class="fa fa-copy"/> Copiar',
      titleAttr: 'Copiar'
    }, {
      extend: 'excelHtml5',
      text: '<i class="fa fa-file-excel-o"/> Exportar a Excel',
      titleAttr: 'Copiar'
    }, {
      extend: 'csvHtml5',
      text: '<i class="fa fa-file-text-o"/> Exportar a CSV',
      titleAttr: 'Copiar'
    }, {
      extend: 'pdfHtml5',
      text: '<i class="fa fa-file-pdf-o"/> Exportar a PDF',
      titleAttr: 'Copiar'
    }, {
      text: 'Todos los Estados',
      action: function action() {
        loadData('%');
      }
    }, {
      text: 'En Proceso',
      action: function action() {
        loadData('En Proceso');
      }
    }, {
      text: 'Realizadas',
      action: function action() {
        loadData('Realizada');
      }
    }, {
      text: 'Canceladas',
      action: function action() {
        loadData('Cancelada');
      }
    }],
    language: {
      "decimal": "",
      "emptyTable": "No hay información",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
      "infoFiltered": "(Filtrado de _MAX_ total entradas)",
      "infoPostFix": "",
      "thousands": ",",
      "lengthMenu": "Mostrar _MENU_ Entradas",
      "loadingRecords": "Cargando...",
      "processing": "Procesando...",
      "search": "Buscar:",
      "zeroRecords": "Sin resultados encontrados",
      "paginate": {
        "first": "Primero",
        "last": "Ultimo",
        "next": "Siguiente",
        "previous": "Anterior"
      }
    },
    scrollY: "300px",
    scrollX: true,
    "autoWidth": false,
    scrollCollapse: true,
    "fixedHeader": {
      "header": false,
      "footer": false
    },
    "columnDefs": [{
      "width": "10px",
      "targets": 0
    }, {
      "width": "40px",
      "targets": 1
    }, {
      "width": "100px",
      "targets": 2
    }, {
      "width": "70px",
      "targets": 3
    }, {
      "width": "70px",
      "targets": 4
    }, {
      "width": "70px",
      "targets": 5
    }, {
      "width": "10px",
      "targets": 6
    }, {
      "width": "40px",
      "targets": 7
    }, {
      "width": "100px",
      "targets": 8
    }, {
      "width": "70px",
      "targets": 9
    }, {
      "width": "70px",
      "targets": 10
    }, {
      "width": "70px",
      "targets": 11
    }, {
      "width": "10px",
      "targets": 12
    }, {
      "width": "40px",
      "targets": 13
    }, {
      "width": "100px",
      "targets": 14
    }, {
      "width": "70px",
      "targets": 15
    }, {
      "width": "70px",
      "targets": 16
    }, {
      "width": "70px",
      "targets": 17
    }, {
      "width": "10px",
      "targets": 18
    }],
    order: [[1, "desc"]]
  });
};

var loadData = function loadData(estado) {
  var plataforma = $("#plataforma").val();
  $.ajax({
    type: 'GET',
    url: 'api_solicitudes.php',
    contentType: "text/plain",
    dataType: 'json',
    data: {
      accion: 'listaSolicitudes',
      estado: estado,
      plataforma: plataforma
    },
    success: function success(data) {
      myJsonData = data; //console.log(myJsonData);

      $("#solicitudes").dataTable().fnDestroy();
      definirDataTable();
      populateDataTable(myJsonData);
    },
    error: function error(e) {
      console.log("There was an error with your request...");
      console.log("error: " + JSON.stringify(e));
    }
  });
};

var populateDataTable = function populateDataTable(data) {
  console.log("populating data table..."); // clear the table before populating it with more data

  $("#solicitudes").DataTable().clear();
  var length = Object.keys(data).length;

  for (var i = 0; i < length; i++) {
    var res = data[i]; // Se puede cargar la info desde la inicializacion de la tabla

    $('#solicitudes').dataTable().fnAddData([res.ID, res.ESTADO, res.ESTADO_SIM, res.ID_TARIFA, res.NOMBRE_TARIFA, res.PRECIO_TARIFA, res.NUM_DOCUMENTO, res.RAZON_SOCIAL, res.NOMBRE_TITULAR, res.APELLIDOS_TITULAR, res.FECHA_NACIMIENTO, res.NUM_TLF_TITULAR, res.EMAIL, res.ALTA, res.NUM_TLF_PORTABILIDAD, res.NUM_ICC_PORTABILIDAD, res.TITULAR_CUENTA_BANCARIA, res.IBAN, res.DIRECCION_ENVIO, res.DIRECCION_FACTURACION, "<input id='btnEnviarSim' type='button' class='btn btn-success' value='Enviar SIM' onclick='muestraModal()'>"]);
  }
};

var muestraModal = function muestraModal() {
  $('#modalIcc').modal('show');
};