<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
    echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $nome = $_POST['nome_professor'] ?? '';
    $arena = $_POST['id_arena'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
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
        $verificaEmail = $conn->prepare("SELECT 1 FROM info_geral.professores WHERE email = :email");
        $verificaEmail->execute([':email' => $email]);

        if ($verificaEmail->rowCount() > 0) {
            echo "<script>alert('Este e-mail já está cadastrado.'); history.back();</script>";
            exit;
        }

        // Insere o novo professor no banco
        $query = "
            INSERT INTO info_geral.professores (nome_professor, id_arena, email, senha)
            VALUES (:nome, :arena, :email, :senha)
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':nome'  => $nome,
            ':arena' => $arena,
            ':email' => $email,
            ':senha' => $senhaHash
        ]);

        echo "<script>alert('Professor cadastrado com sucesso!'); window.location.href='../painel_adm.php';</script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "'); history.back();</script>";
        exit;
    }
}
?>