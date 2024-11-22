<?php 
$titulo_pagina = 'Editar Arquivo';
include_once(__DIR__ . '/../partials/header.php');

require_once(__DIR__ . '/../../controller/arquivos/ArquivosController.php');
$arquivosController = new ArquivosController();
?>

<section class="content">
    <fieldset class="border p-2">
    <form action="index.php?modulo=arquivos&acao=atualizar" method="post">
            <input type="hidden" name="caminho_relativo" value="<?php echo $_GET['caminho_relativo']; ?>">

            <div class="mb-3">
                <label for="nome_arquivo" class="form-label">Nome</label>
                <input type="text" id="nome_arquivo" name="nome_arquivo" class="form-control" value="<?php echo basename($_GET['caminho_relativo']); ?>" placeholder="Nome do Arquivo" required>
            </div>

            <?php if(file_exists($_GET['caminho_relativo']) && is_file($_GET['caminho_relativo'])) { ?>
                <div class="mb-3">
                    <label for="conteudo" class="form-label">Conteúdo</label>
                    <?php $conteudoArquivo = $arquivosController->obterConteudoArquivo($_GET['caminho_relativo']); ?>
                    <textarea <?php echo (!$conteudoArquivo['ok'] ? "disabled" : ""); ?> class="form-control" name="conteudo" id="conteudo" rows="5"><?php echo $conteudoArquivo['msg']; ?></textarea>
                </div>
            <?php } ?>

            <button type="submit" class="btn btn-primary">Editar</button>
        </form>
    </fieldset>
</section>
    
<?php
include_once __DIR__ . '/../partials/footer.php';
?>