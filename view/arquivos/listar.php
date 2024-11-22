<?php
$titulo_pagina = 'Lista de Arquivos';
include_once __DIR__ . '/../partials/header.php';

require_once(__DIR__ . '/../../controller/arquivos/ArquivosController.php');
$arquivosController = new ArquivosController();
$listaArquivos = $arquivosController->obterListaArquivos();
$arquivos = !empty($listaArquivos['dados']) ? $listaArquivos['dados'] : array();
$diretorioAtual = $listaArquivos['diretorioAtual'];
?>
    
<section class="content">
    <div class="d-flex justify-content-between align-items-center bg-light p-2 border">
        <span class="text-muted">
            <span class="fw-bold">Diretório: </span><?php echo $diretorioAtual; ?>
        </span>

        <span class="d-inline-block bg-primary rounded-circle span_icone_adicionar_arquivo">
            <a class="icone_adicionar_arquivo" href="index.php?modulo=arquivos&acao=cadastrar&caminho_relativo=<?php echo $diretorioAtual; ?>" title="Adicionar Arquivo">
                <i class="fa-solid fa-plus"></i>
            </a>
        </span>
    </div>

    <?php if(!empty($arquivos)) { ?>
        <table class="table table-responsive table-striped table-bordered tabelaGerenciarArquivos">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Tamanho</th>
                        <th scope="col">Data de Modificação</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arquivos as $arquivo) { ?>
                        <tr>
                            <td>
                                <?php echo $arquivo['iconeHtml']; ?>
                                <!-- Navegar -->
                                <?php if(is_dir($arquivo['caminhoRelativo'])) { ?>
                                    <a class="text-decoration-none link-dark" href="index.php?modulo=arquivos&acao=listar&caminho_relativo=<?php echo $arquivo['caminhoRelativo']; ?>"><?php echo $arquivo['nome']; ?></a>
                                <?php } else { ?>
                                    <?php echo $arquivo['nome']; ?>
                                <?php } ?>
                            </td>
                            <td><?php echo $arquivo['tamanho']; ?></td>
                            <td><?php echo $arquivo['dataModificacao']; ?></td>
                            <td class="acoes">
                                <a id="btnEditar" class="btn btn-primary" title="Editar" href="index.php?modulo=arquivos&acao=editar&caminho_relativo=<?php echo $arquivo['caminhoRelativo']; ?>"><i class="fa-solid fa-pen fa-xs"></i></a>

                                <a class="btn btn-danger" title="Excluir Arquivo" href="index.php?modulo=arquivos&acao=excluir&caminho_relativo=<?php echo $arquivo['caminhoRelativo']; ?>" onclick="return confirm('Tem certeza que deseja excluir <?php echo basename($arquivo['caminhoRelativo']); ?>?');"><i class="fa-solid fa-trash fa-xs"></i></a>
                                
                                <?php if(!is_dir($arquivo['caminhoRelativo'])) {?>
                                    <a class="btn btn-success" title="Baixar Arquivo" href="index.php?modulo=arquivos&acao=download&caminho_relativo=<?php echo $arquivo['caminhoRelativo']; ?>"><i class="fa-solid fa-download fa-xs"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
        </table>
    <?php } else {?>
        <div class="border p-2 text-center"><b>Nenhum Arquivo/Diretório encontrado.</b></div>
    <?php } ?>
</section>

<?php
// Footer
include_once __DIR__ . '/../partials/footer.php';
?>