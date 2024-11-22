<?php

// Flash Messages
require_once(__DIR__ . '/../view/partials/flash_messages.php');

class Funcoes {
    /**
     * Método para obter o tamanho do arquivo
     * @param string $caminhoArquivo
     * @param integer $precisao
     * @return float
     */
    public static function obterTamanhoArquivo($caminhoArquivo, $precisao = 2) {
        $tamanhoArquivo = filesize($caminhoArquivo);

        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB']; 
        $bytes = max($tamanhoArquivo, 0); 
        $pow_value = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow_value = min($pow_value, count($units) - 1); 
        $bytes /= pow(1024, $pow_value);

        return round($bytes, $precisao) . ' ' . $units[$pow_value]; 
    }

    /**
     * Método para determinar o icone do tipo de arquivo
     * @param string $caminhoArquivo
     * @return string
     */
    public static function obterIconeTipoArquivo($caminhoArquivo) {
        // Icone Diretório
        if (is_dir($caminhoArquivo)) {
            return '<i class="fa-solid fa-folder"></i>';
        } 
        
        // Obter icone de acordo com Regex
        $tipoArquivo = mime_content_type($caminhoArquivo);
        $arrIcones = array(
           '/image\/*/' => '<i class="fa-solid fa-file-image"></i>',
           '/video\/*/' => '<i class="fa-solid fa-file-video"></i>',
           '/audio\/*/' => '<i class="fa-solid fa-file-audio"></i>'
        );
        foreach($arrIcones as $mimeTypeRegex => $iconeHtml) {
            if(preg_match($mimeTypeRegex, $tipoArquivo)) {
                return $iconeHtml;
            }
        };

        // Icone Padrão caso não seja nenhum dos anteriores
        return '<i class="fa-solid fa-file"></i>';
    }

    /**
     * Método para realizar download do arquivo
     * @param string $caminhoRelativo
     */
    public static function download($caminhoRelativo) {
        header('Content-Description: File Transfer'); 
        header('Content-Type: application/octet-stream'); 
        header('Content-Disposition: attachment; filename="' . basename($caminhoRelativo) . '"'); 
        readfile($caminhoRelativo);
        exit; 
    }

    /**
     * Método para remover diretório recursivamente
     * @param string $dirname
     * @return void
     */
    public static function removeDir($dirname) {
        if (is_dir($dirname)) {
            $dir = new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST) as $object) {
                if ($object->isFile()) {
                    unlink($object);
                } elseif($object->isDir()) {
                    rmdir($object);
                } else {
                    throw new Exception('Tipo de objeto desconhecido: '. $object->getFileName());
                }
            }
            rmdir($dirname);
        } else {
            throw new Exception('Diretório não encontrado.');
        }
    }

    /**
     * Setar a mensagem que será exibida em tela
     * @param string $mensagem
     * @param string $tipoMensagem
     * @return void
     */
    public static function exibe_mensagem($mensagem, $tipoMensagem) {
        flash($mensagem, $tipoMensagem);
    }

    /**
     * Redirecionar a url
     * @param string $caminhoRelativo
     * @example $caminhoRelativo -> modulo=arquivos&acao=listar
     * @return void
     */
    public static function redireciona_url($caminhoRelativo) {
        $url = BASE_URL . $caminhoRelativo;
        header("Location: $url");
        exit;
    }
}
