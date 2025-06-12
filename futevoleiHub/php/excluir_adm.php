<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_adm'])) {
    $id = $_POST['id_adm'];

    try {
        // Verifica quantos administradores existem
        $stmtCount = $conn->prepare("SELECT COUNT(*) as total FROM info_geral.adm");
        $stmtCount->execute();
        $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

        if ($total <= 1) {
            echo "<script>alert('Não é possível excluir o único administrador do sistema.'); history.back();</script>";
            exit;
        }

        // Excluir o administrador
        $stmtDelete = $conn->prepare("DELETE FROM info_geral.adm WHERE id_adm = :id");
        $stmtDelete->execute([':id' => $id]);

        echo "<script>alert('Administrador excluído com sucesso.'); window.location.href = '../painel_adm.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao excluir administrador: " . $e->getMessage() . "'); history.back();</script>";
    }
} else {
    echo "<script>alert('Requisição inválida.'); history.back();</script>";
}
?>
