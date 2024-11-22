<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $titulo_pagina = isset($titulo_pagina) ? $titulo_pagina : "Manipulação de Arquivos"; ?>
    </title>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/46e02bda27.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>

<body>
<div class="container-fluid p-5 m-0">
    <?php if(isset($_SESSION[FLASH])) {
        display_flash_message();
    } ?>

    <?php if(!isset($ocultarFaixaCabecalho)) { ?>
        <section id="section_voltar">
            <button id="btn_icone_voltar" class="m-0 border-0 p-2" onclick="history.back()">
                <i class="fa-regular fa-circle-left fa-2xl"></i>
                <span class="text-muted"><strong>Voltar</strong></span>
            </button>
        </section>
        
        <section class="text-bg-primary p-2">
            <?php $titulo_componente_header = isset($titulo_componente_header) ? $titulo_componente_header : $titulo_pagina; ?>
            <h3 class="m-0"><?php echo $titulo_componente_header; ?></h3>
        </section>
    <?php } ?>
