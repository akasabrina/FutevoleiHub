<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_professor'])) {
    $id = $_POST['id_professor'];

    try {
        // Excluir o professor
        $stmt = $conn->prepare("DELETE FROM info_geral.professores WHERE id_professor = :id");
        $stmt->execute([':id' => $id]);

        echo "<script>alert('Professor excluído com sucesso.'); window.location.href = '../painel_adm.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao excluir professor: " . $e->getMessage() . "'); history.back();</script>";
    }
} else {
    echo "<script>alert('Requisição inválida.'); history.back();</script>";
}
?>