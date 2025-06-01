<?php
include 'conexao.php';
session_start();


// Processa o formulário se for um envio via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $nome  = $_POST["nome"];
    $arena = $_POST["id_arena"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $confirmaSenha = $_POST['confirma_senha'] ?? '';

    // Validação dos campos
    if (empty($nome) || empty($arena) || empty($email) || empty($senha) || empty($confirmaSenha)) {
        echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('E-mail inválido!'); history.back();</script>";
        exit;
    }

    if ($senha !== $confirmaSenha) {
        echo "<script>alert('As senhas não coincidem.'); history.back();</script>";
        exit;
    }

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        // Verifica se o e-mail já está cadastrado
        $verificaEmail = $conn->prepare("SELECT 1 FROM info_geral.alunos WHERE email = :email");
        $verificaEmail->execute([':email' => $email]);

        if ($verificaEmail->rowCount() > 0) {
            echo "<script>alert('Este e-mail já está cadastrado.'); history.back();</script>";
            exit;
        }

        // Insere o novo aluno no banco
        $query = "
            INSERT INTO info_geral.alunos (nome_aluno, id_arena, email, senha)
            VALUES (:nome, :arena, :email, :senha)
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':nome'  => $nome,
            ':arena' => $arena,
            ':email' => $email,
            ':senha' => $senhaHash
        ]);

        echo "<script>alert('Aluno cadastrado com sucesso!'); window.location.href='../index.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "'); history.back();</script>";
        exit;
    }
}
