<?php
class ArquivosController {
    private $diretorioPrincipal;

    public function __construct() {
        $this->diretorioPrincipal = 'lista_arquivos';
    }

    /**
     * Método para obter descrição do arquivo 
     * @param string $tipoArquivo
     * @return void
     */
    private function obterDescricaoTipoArquivo($tipoArquivo) {
        return ($tipoArquivo == 'diretorio' ? 'Diretório' : 'Arquivo');
    }

    /**
     * Método para obter lista de arquivos
     * @return array
     */
    public function obterListaArquivos() {
        $dadosRequisicao = $_REQUEST;

        $retorno = array();
        try {
            $caminhoRelativo =  $this->diretorioPrincipal;
            if(isset($dadosRequisicao['caminho_relativo']) && !empty($dadosRequisicao['caminho_relativo'])) {
                $caminhoRelativo = $dadosRequisicao['caminho_relativo'];
            }

            if(empty($caminhoRelativo)) {
                throw new Exception("Necessário informar o diretório.");
            }

            // Tratamento para Impedir Directory Traversal
            if(strpos(realpath($caminhoRelativo), realpath($this->diretorioPrincipal)) === false || strpos($caminhoRelativo, ".") !== false || strpos($caminhoRelativo, "..") !== false) {
                throw new Exception("Diretório inválido.");
            }

            $listaCaminhoArquivos = glob($caminhoRelativo . '/*');
            if($listaCaminhoArquivos === false) {
                throw new Exception("Não foi possível verificar o diretório $caminhoRelativo.");
            }

            $retorno['diretorioAtual'] = $caminhoRelativo;
            foreach ($listaCaminhoArquivos as $caminhoArquivo) {
                $retorno['dados'][] = array(
                    'caminhoRelativo' => $caminhoArquivo,
                    'iconeHtml' => Funcoes::obterIconeTipoArquivo($caminhoArquivo),
                    'nome' => basename($caminhoArquivo),
                    'tamanho' => is_dir($caminhoArquivo) ? 'Pasta' : Funcoes::obterTamanhoArquivo($caminhoArquivo),
                    'dataModificacao' => date("d/m/Y H:i:s", filemtime($caminhoArquivo)),
                );
            }
            return $retorno;
        } catch (Exception $e) {
            Funcoes::exibe_mensagem($e->getMessage(), FLASH_ERROR);
        }
    }

    /**
     * Método para validar nome do arquivo
     * @param string $nomeArquivo
     * @param string $descricaoTipoArquivo
     * @return void
     */
    private function validarNomeArquivo($nomeArquivo, $descricaoTipoArquivo) {
        if(empty($nomeArquivo)) {
            throw new Exception("Necessário informar o nome do $descricaoTipoArquivo.");
        }

        // Permite nomes de arquivo que contêm apenas letras, números, underscores, hífens, pontos e espaços
        if (!preg_match('/^[\w\-. ]+$/', $nomeArquivo)) {
            throw new Exception("$descricaoTipoArquivo inválido. Permitido apenas letras, números, underscores, hífens, pontos e espaços.");
        }
    }

    /**
     * Método para validar se a extensão do arquivo é válida
     * @param string $caminhoRelativo
     * @return void
     */
    private function validarExtensaoArquivo($caminhoRelativo) {
        $extensaoArquivosPermitidos = array('txt');

        if(!in_array(pathinfo($caminhoRelativo, PATHINFO_EXTENSION), $extensaoArquivosPermitidos)) {
            throw new Exception("A extensão do arquivo não é permitida. O arquivo precisa estar no formato " . "<b>".implode(", ", $extensaoArquivosPermitidos)."</b>");
        }
    }

    /**
     * Método para adicionar arquivo/pasta
     * @return void
     */
    public function criar() {
        $dadosRequisicao = $_REQUEST;
        $caminhoDestino =  $dadosRequisicao['caminho_relativo'] . "/" . $dadosRequisicao['nome_arquivo'];
        $descricaoTipoArquivo = $this->obterDescricaoTipoArquivo($dadosRequisicao['tipo_arquivo']);
        try {
            if(empty($dadosRequisicao['tipo_arquivo'])) {
                throw new Exception("Necessário informar o tipo de arquivo.");
            }
            
            $this->validarNomeArquivo($dadosRequisicao['nome_arquivo'], $descricaoTipoArquivo);

            if($dadosRequisicao['tipo_arquivo'] == 'diretorio') {
                if(!mkdir($caminhoDestino, 0777, true)) {
                    throw new Exception("Não foi possível criar o diretório.");
                }
            }

            if($dadosRequisicao['tipo_arquivo'] == 'arquivo') {
                $this->validarExtensaoArquivo($caminhoDestino);

                if(fopen($caminhoDestino, 'w') === false) {
                    throw new Exception("Não foi possível criar o arquivo.");
                }
            }

            Funcoes::exibe_mensagem($descricaoTipoArquivo . " criado com sucesso.", FLASH_SUCCESS);
            Funcoes::redireciona_url("index.php?modulo=arquivos&acao=listar&caminho_relativo=" . $dadosRequisicao['caminho_relativo']);
        } catch (Exception $e) {
            Funcoes::exibe_mensagem($e->getMessage(), FLASH_ERROR);
            Funcoes::redireciona_url("index.php?modulo=arquivos&acao=cadastrar&caminho_relativo=" . $dadosRequisicao['caminho_relativo']);
        }
    }

    /**
     * Método para atualizar arquivo/diretório
     * @return void
     */
    public function atualizar() {
        $dadosRequisicao = $_REQUEST;
        $caminhoRelativo = $dadosRequisicao['caminho_relativo'];
        $nomeArquivo = $dadosRequisicao['nome_arquivo'];
        $nomeArquivoAntigo = basename($dadosRequisicao['caminho_relativo']);
        $tipoArquivo = is_dir($caminhoRelativo) ? 'diretorio' : 'arquivo';

        $descricaoTipoArquivo = $this->obterDescricaoTipoArquivo($tipoArquivo);

        try {
            $this->validarNomeArquivo($nomeArquivo, $tipoArquivo);

            // Alterar nome do arquivo
            $caminhoRelativoNovo = str_replace($nomeArquivoAntigo, $nomeArquivo, $dadosRequisicao['caminho_relativo']);
            if($tipoArquivo == 'arquivo') {
                $this->validarExtensaoArquivo($caminhoRelativoNovo);
            }

            if(!rename($caminhoRelativo, $caminhoRelativoNovo)) {
                throw new Exception("Não foi possível renomear o arquivo.");
            }

            // Alterar conteúdo do arquivo
            if($tipoArquivo == 'arquivo') {
                $conteudoArquivo = $dadosRequisicao['conteudo'];
                $arquivo = fopen($caminhoRelativoNovo, "w");
                if(!$arquivo) {
                    throw new Exception("Não foi possível abrir o arquivo.");
                }
                $conteudoArquivo = fwrite($arquivo, $conteudoArquivo);
                fclose($arquivo);
            }

            Funcoes::exibe_mensagem("$descricaoTipoArquivo alterado com sucesso.", FLASH_SUCCESS);
            Funcoes::redireciona_url("index.php?modulo=arquivos&acao=listar&caminho_relativo=" . str_replace("/" . basename($caminhoRelativoNovo), "", $caminhoRelativoNovo));   
        } catch (Exception $e) {
            Funcoes::exibe_mensagem($e->getMessage(), FLASH_ERROR);
            Funcoes::redireciona_url("index.php?modulo=arquivos&acao=editar&caminho_relativo=" . $caminhoRelativo);
        }
    }

    /**
     * Método para excluir arquivo
     * @param string $caminhoRelativo
     * @param string $nomeArquivo
     * @return void
     */
    private function excluirArquivo($caminhoRelativo, $nomeArquivo) {
        if(!file_exists($caminhoRelativo)) {
            throw new Exception("Arquivo {$nomeArquivo} não encontrado.");
        }

        if(!unlink($caminhoRelativo)) {
            throw new Exception("Não foi possível excluir o arquivo: " . $nomeArquivo);
        }
    }

    /**
     * Método para excluir diretório
     * @param string $caminhoRelativo
     * @param string $nomeArquivo
     * @return void
     */
    private function excluirDiretorio($caminhoRelativo, $nomeArquivo) {
        Funcoes::removeDir($caminhoRelativo);
    }

    /**
     * Método para excluir arquivo/diretório
     * @return void
     */
    public function excluir() {
        $dadosRequisicao = $_REQUEST;
        $caminhoRelativo = $dadosRequisicao['caminho_relativo'];
        $nomeArquivo = basename($caminhoRelativo);
        $tipoArquivo = is_dir($caminhoRelativo) ? 'diretorio' : 'arquivo';

        $descricaoTipoArquivo = $this->obterDescricaoTipoArquivo($tipoArquivo);
        try {
            $this->validarNomeArquivo($nomeArquivo, $tipoArquivo);

            if(is_dir($caminhoRelativo)) {
                $this->excluirDiretorio($caminhoRelativo, $nomeArquivo);                
            } else {
                $this->excluirArquivo($caminhoRelativo, $nomeArquivo);                
            }
            Funcoes::exibe_mensagem("$descricaoTipoArquivo excluído com sucesso.", FLASH_SUCCESS);
            Funcoes::redireciona_url("index.php?modulo=arquivos&acao=listar&caminho_relativo=" . str_replace("/" . basename($caminhoRelativo), "", $caminhoRelativo));
        } catch (Exception $e) {
            Funcoes::exibe_mensagem($e->getMessage(), FLASH_ERROR);
            Funcoes::redireciona_url("index.php?modulo=arquivos&acao=listar&caminho_relativo=" . $caminhoRelativo);
        }
    }
    
    /**
     * Método para obter conteúdo do arquivo
     * @param string $caminho_relativo
     * @return void
     */
    public function obterConteudoArquivo($caminho_relativo) {
        $retorno = array(
            "ok" => false,
            "msg" => "Não foi possível obter o conteúdo do arquivo."
        );

        if(!file_exists($caminho_relativo)) {
            return $retorno;
        }

        $conteudoArquivo = "";
        if(!empty(filesize($caminho_relativo))) {
            $arquivo = fopen($caminho_relativo, "r");
            $conteudoArquivo = fread($arquivo, filesize($caminho_relativo));
            fclose($arquivo);
        }

        $retorno["ok"] = true;
        $retorno["msg"] = $conteudoArquivo;

        return $retorno;
    }
}