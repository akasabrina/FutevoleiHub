<?php

function registrarErro(string $mensagem): void
{
    $caminhoLog = __DIR__ . "/../logs/erros.log";

    // Garante que o diretório exista
    if (!is_dir(dirname($caminhoLog))) {
        mkdir(dirname($caminhoLog), 0777, true);
    }

    // Monta a mensagem com data e hora
    $mensagemLog = "[" . date("Y-m-d H:i:s") . "] " . $mensagem . PHP_EOL;

    // Registra no arquivo
    error_log($mensagemLog, 3, $caminhoLog);
}
