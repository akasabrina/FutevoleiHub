<?php
session_start();

// Tempo máximo de inatividade (30 minutos = 1800 segundos)
$tempoMaximo = 1800;

// Verifica se houve inatividade
if (isset($_SESSION['ultimo_acesso'])) {
    $tempoInativo = time() - $_SESSION['ultimo_acesso'];

    if ($tempoInativo > $tempoMaximo) {
        session_unset();
        session_destroy();

        // Exibe alerta e redireciona
        echo "<script> alert('Você foi desconectado por inatividade.'); window.location.href = '../index.php'; </script>";
        exit();
    }
}

// Atualiza o tempo do último acesso
$_SESSION['ultimo_acesso'] = time();

// Verifica se foi um logout manual
if (isset($_GET['acao']) && $_GET['acao'] === 'sair') {
    session_unset();
    session_destroy();

    echo "<script>alert('Logout realizado com sucesso.'); window.location.href = '../index.php'; </script>";
    exit();
}

// Caso acessem esse arquivo diretamente, redireciona com segurança
echo "<script> window.location.href = '../index.php'; </script>";
exit();
