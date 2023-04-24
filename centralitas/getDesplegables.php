<?php

$n = filter_input(INPUT_GET, 'num', FILTER_SANITIZE_STRING);
$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING);

switch ($accion) {
    case "lineas":
        for ($i = 0; $i < $n; $i++) {
            echo '
<div class="row mt-4">
    <div class="col-1">
        <label for="inputDDialplan">D</label>
        <input type="text" class="form-control" name="dDialplan[]" id="inputDDialplan" required>
    </div>
    <div class="col-2">
    <label for="inputNumTlfDialplan">Número Teléfono</label>
    <input type="number" max="999999999" class="form-control" id="inputNumTlfDialplan" name="numTlfDialplan[]" placeholder="Introduce el número">
    </div>
    <div class="col-2">
        <label for="inputExtDialplan">Nº Ext</label>
        <input type="number" class="form-control" name="extDialplan[]" id="inputExtDialplan" value="200" required>
    </div>
    <div class="col-4">
        <label for="inputNombreDialplan">Nombre</label>
        <input type="text" class="form-control" name="nombreDialplan[]" id="inputNombreDialplan" required>
    </div>
    <div class="col-2">
        <label for="inputTonoDialplan">Tono</label>
        <input type="number" class="form-control" name="tonoDialplan[]" id="inputTonoDialplan" value="1" required>
    </div>
    <div class="col-1">
        <label for="inputRetotnoDialplan">Retorno</label>
        <input type="number" class="form-control" name="retornoDialplan[]" id="inputRetotnoDialplan" value="100" required>
    </div>
</div>';
        }
        break;

    case "departamentos":
        for ($i = 0; $i < $n; $i++) {
            echo '<div class="row mt-4">
                <div class="col-1">
                    <label for="inputDDepartamento">D</label>
                    <input type="text" class="form-control" id="inputDDepartamento" value="' . ($i + 1) . '"name="dDepartamento[]" required>
                </div>
                <div class="col-2">
                    <label for="inputNumTlfDepartamento">Número Teléfono</label>
                    <input type="number" max="999999999" class="form-control" id="inputNumTlfDepartamento" name="numTlfDepartamento[]" placeholder="Introduce el número">
                </div>
                <div class="col">
                    <label for="inputNombreIDCD">Nombre Departamento</label>
                    <input type="text" class="form-control" id="inputNombreDepartamento" name="nombreDepartamento[]" placeholder="Introduce el nombre" required>
                </div>
                <div class="col">
                    <label for="inputLocucionIDCD">Locución</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="locucionDepartamento[]" id="inputLocucionIDCD">
                        <label class="custom-file-label" for="inputLocucionIDCD">Selecciona un fichero de locución</label>
                    </div>
                </div>
            </div>';
        }
        break;
}
