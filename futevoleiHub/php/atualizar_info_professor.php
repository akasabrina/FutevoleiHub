<?php
include 'conexao.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
    exit;
}

$id_professor_session = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_professor_form = $_POST['id_professor'];
    $nome = $_POST['nome_professor'];
    $email = $_POST['email'];
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';

    // Impede que alguém tente editar outro professor manualmente pela URL
    if ($id_professor_form != $id_professor_session) {
        echo "<script>alert('Você só pode editar suas próprias informações.'); window.location.href='../painel_professor.php';</script>";
        exit;
    }

    try {
        $stmtUpdate = $conn->prepare("UPDATE info_geral.professores SET nome_professor = :nome, email = :email WHERE id_professor = :id");
        $stmtUpdate->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':id' => $id_professor_session
        ]);

        // Verifica se a senha deve ser alterada
        if (!empty($senha_atual) && !empty($nova_senha)) {
            $stmtSenha = $conn->prepare("SELECT senha FROM info_geral.professores WHERE id_professor = :id");
            $stmtSenha->execute([':id' => $id_professor_session]);
            $senha_hash = $stmtSenha->fetchColumn();

            if (password_verify($senha_atual, $senha_hash)) {
                if ($senha_atual === $nova_senha) {
                    echo "<script>alert('A nova senha não pode ser igual à senha atual.'); history.back(); </script>";
                    exit;
                }

                $nova_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmtUpdateSenha = $conn->prepare("UPDATE info_geral.professores SET senha = :nova WHERE id_professor = :id");
                $stmtUpdateSenha->execute([
                    ':nova' => $nova_hash,
                    ':id' => $id_professor_session
                ]);
            } else {
                echo "<script>alert('Senha atual incorreta.'); history.back();</script>";
                exit;
            }
        }

        echo "<script>alert('Informações atualizadas com sucesso!'); window.location.href='../painel_professor.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao atualizar: " . $e->getMessage() . "'); history.back();</script>";
    }
}
