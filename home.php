<?php
$titulo_pagina = 'Gerenciador de Arquivos';
$ocultarFaixaCabecalho = true;
include_once __DIR__ . '/view/partials/header.php';
?>

<section class="content">
    <div class="p-4 p-lg-5 rounded-3 text-center">
        <div class="m-4 m-lg-5">
            <h1 class="display-5 fw-bold">Gerenciador de Arquivos</h1>
            <p class="fs-4">Gerencie seus arquivos e diretórios de forma leve e descomplicada.</p>
            <a class="btn btn-primary btn-lg" href="index.php?modulo=arquivos&acao=listar">Iniciar</a>
        </div>
    </div>
</section>

<?php
include_once __DIR__ . '/view/partials/footer.php';
?>