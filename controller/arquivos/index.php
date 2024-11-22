<?php

if(isset($_GET['acao'])) {
    switch ($_GET['acao']) {
        case 'listar':
            require_once(__DIR__ . '/../../view/arquivos/listar.php');
            break;
        case 'cadastrar':
            require_once(__DIR__ . '/../../view/arquivos/cadastrar.php');
            break;
        case 'editar':
            require_once(__DIR__ . '/../../view/arquivos/editar.php');
            break;
        case 'criar':
            require_once(__DIR__ . '/ArquivosController.php');
            $arquivosController = new ArquivosController();
            $arquivosController->criar();
            break;
        case 'download':
            require_once(__DIR__ . '/ArquivosController.php');
            $arquivosController = new ArquivosController();
            Funcoes::download($_GET['caminho_relativo']);
            break;
        case 'atualizar':
            require_once(__DIR__ . '/ArquivosController.php');
            $arquivosController = new ArquivosController();
            $arquivosController->atualizar();
            break;
        case 'excluir':
            require_once(__DIR__ . '/ArquivosController.php');
            $arquivosController = new ArquivosController();
            $arquivosController->excluir();
    }
}