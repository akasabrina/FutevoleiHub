<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
    echo "<script>alert('Acesso não autorizado.'); window.location.href = '../index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_arena = $_POST['id_arena'] ?? null;

    if (empty($id_arena)) {
        echo "<script>alert('ID da arena não fornecido.'); history.back();</script>";
        exit;
    }

    try {
        // Verifica se a arena está vinculada a professores ou aulas
        $verificaUso = $conn->prepare("SELECT 1 FROM info_geral.professores WHERE id_arena = :id
        UNION
        SELECT 1 FROM info_geral.aulas_particulares WHERE id_arena = :id");
        $verificaUso->execute([':id' => $id_arena]);

        if ($verificaUso->rowCount() > 0) {
            echo "<script>alert('Não é possível excluir esta arena, pois ela está vinculada a alunos, professores ou aulas.'); history.back();</script>";
            exit;
        }

        // Executa o DELETE
        $stmt = $conn->prepare("DELETE FROM info_geral.arena WHERE id_arena = :id");
        $stmt->execute([':id' => $id_arena]);

        echo "<script>alert('Arena excluída com sucesso!'); window.location.href = '../painel_adm.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao excluir a arena: " . $e->getMessage() . "'); history.back();</script>";
        exit;
    }
}
