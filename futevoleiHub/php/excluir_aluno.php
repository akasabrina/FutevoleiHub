<?php
include 'conexao.php';
session_start();

// Verifica se o usuário está logado e tem permissão
if (!isset($_SESSION['id']) || !in_array($_SESSION['tipo'], ['administrador', 'aluno'])) {
    echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
    exit;
}

// Só prossegue se for POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Requisição inválida.'); history.back();</script>";
    exit;
}

// Identifica quem está tentando excluir
$tipo = $_SESSION['tipo'];
if ($tipo === 'administrador') {
    // Administrador recebe o ID do formulário
    if (empty($_POST['id_aluno'])) {
        echo "<script>alert('ID do aluno não informado.'); history.back();</script>";
        exit;
    }
    $id_aluno = $_POST['id_aluno'];
} else {
    // Aluno só pode excluir a si mesmo
    $id_aluno = $_SESSION['id'];
}

try {
    // Executa o DELETE
    $stmt = $conn->prepare("DELETE FROM info_geral.alunos WHERE id_aluno = :id");
    $stmt->execute([':id' => $id_aluno]);

    if ($tipo === 'administrador') {
        echo "<script>alert('Aluno excluído com sucesso.'); window.location.href = '../painel_adm.php';</script>";
    } else {
        // encerra sessão do aluno e redireciona para a página pública
        session_destroy();
        echo "<script>alert('Sua conta foi excluída.'); window.location.href = '../index.php';</script>";
    }
    exit;

} catch (PDOException $e) {
    echo "<script>alert('Erro ao excluir aluno: " . $e->getMessage() . "'); history.back();</script>";
    exit;
}
?>
