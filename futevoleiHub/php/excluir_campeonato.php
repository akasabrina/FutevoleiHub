<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_camp'])) {
    $id = $_POST['id_camp'];

    try {
        // Excluir o campeonato
        $stmt = $conn->prepare("DELETE FROM info_geral.campeonatos WHERE id_camp = :id");
        $stmt->execute([':id' => $id]);

        echo "<script>alert('Campeonato excluído com sucesso.'); window.location.href = '../painel_adm.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao excluir campeonato: " . $e->getMessage() . "'); history.back();</script>";
    }
} else {
    echo "<script>alert('Requisição inválida.'); history.back();</script>";
}
?>