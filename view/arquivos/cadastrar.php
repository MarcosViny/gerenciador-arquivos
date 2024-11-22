<?php 
$titulo_pagina = 'Cadastrar Arquivo';
include_once __DIR__ . '/../partials/header.php';
?>

<section class="content">
    <fieldset class="border p-2">
        <form action="index.php?modulo=arquivos&acao=criar" method="post">
            <input type="hidden" name="caminho_relativo" value="<?php echo $_GET['caminho_relativo']; ?>">

            <div class="mb-3">
                <label for="tipo_arquivo" class="form-label">Tipo</label>
                <select name="tipo_arquivo" id="tipo_arquivo" class="form-control">
                    <option value="diretorio">Diretório</option>
                    <option value="arquivo">Arquivo</option>
                </select>
            </div>
    
            <div class="mb-3">
                <label for="nome_arquivo" class="form-label">Nome</label>
                <input type="text" id="nome_arquivo" name="nome_arquivo" class="form-control" maxlength="100" required>
            </div>
    
            <button type="submit" class="btn btn-primary">Criar</button>
        </form>
    </fieldset>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        
        setarPlaceholderCampoNome($('#tipo_arquivo').val());
        $('#tipo_arquivo').change(function() {
            setarPlaceholderCampoNome($('#tipo_arquivo').val());
        });
    });

    function setarPlaceholderCampoNome(tipoArquivo) {
        if(tipoArquivo == 'diretorio') {
            $('#nome_arquivo').attr('placeholder', "MinhaPasta")
        } else {
            $('#nome_arquivo').attr('placeholder', "MeuArquivo.txt")
        }
    }
</script>

<?php
include_once __DIR__ . '/../partials/footer.php';
?>