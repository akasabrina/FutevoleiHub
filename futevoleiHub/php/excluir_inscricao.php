<?php
include 'conexao.php';
session_start();

// Verifica se o usuário está logado e tem permissão
if (!isset($_SESSION['id']) || !in_array($_SESSION['tipo'], ['administrador', 'professor', 'aluno'])) {
    echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
    exit;
}

$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_inscricao'])) {
    $id_inscricao = $_POST['id_inscricao'];

    try {
        if ($tipo_usuario !== 'administrador') {
            if ($tipo_usuario === 'aluno') {
                $query = "SELECT id_inscricao FROM info_geral.inscricao_campeonato WHERE id_inscricao = :id AND id_aluno = :id_usuario";
            } else { 
                $query = "SELECT id_inscricao FROM info_geral.inscricao_campeonato WHERE id_inscricao = :id AND id_professor = :id_usuario";
            }

            $verifica = $conn->prepare($query);
            $verifica->execute([
                ':id' => $id_inscricao,
                ':id_usuario' => $id_usuario
            ]);

            if ($verifica->rowCount() === 0) {
                echo "<script>alert('Você não tem permissão para excluir esta inscrição.'); history.back();</script>";
                exit;
            }
        }

        // Excluir a inscrição
        $stmt = $conn->prepare("DELETE FROM info_geral.inscricao_campeonato WHERE id_inscricao = :id");
        $stmt->execute([':id' => $id_inscricao]);

        // Redirecionamento de acordo com o tipo
        if ($tipo_usuario === 'administrador') {
            echo "<script>alert('Inscrição excluída com sucesso.'); window.location.href='../painel_adm.php';</script>";
        } elseif ($tipo_usuario === 'aluno') {
            echo "<script>alert('Inscrição excluída com sucesso.'); window.location.href='../painel_aluno.php';</script>";
        } else { // professor
            echo "<script>alert('Inscrição excluída com sucesso.'); window.location.href='../painel_professor.php';</script>";
        }

    } catch (PDOException $e) {
        echo "<script>alert('Erro ao excluir inscrição: " . $e->getMessage() . "'); history.back();</script>";
    }

} else {
    echo "<script>alert('Requisição inválida.'); history.back();</script>";
}
