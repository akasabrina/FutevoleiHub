<?php
include 'conexao.php';
include 'utils.php';

session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
    echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Requisição inválida.'); history.back();</script>";
    exit;
}

$nome          = trim($_POST['nome_professor'] ?? '');
$id_arena      = isset($_POST['id_arena']) ? (int)$_POST['id_arena'] : 0;
$email         = trim($_POST['email'] ?? '');
$senha         = $_POST['senha'] ?? '';
$confirmaSenha = $_POST['confirma_senha'] ?? '';

if (empty($nome) || !$id_arena || empty($email) || empty($senha) || empty($confirmaSenha)) {
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

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $verifica = $conn->prepare("SELECT 1 FROM info_geral.professores WHERE email = :email");
    $verifica->execute([':email' => $email]);

    if ($verifica->rowCount() > 0) {
        echo "<script>alert('Este e-mail já está cadastrado.'); history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO info_geral.professores (nome_professor, id_arena, email, senha)
        VALUES (:nome, :arena, :email, :senha)
    ");
    $stmt->execute([
        ':nome'  => $nome,
        ':arena' => $id_arena,
        ':email' => $email,
        ':senha' => $senhaHash
    ]);

    echo "<script>alert('Professor cadastrado com sucesso!'); window.location.href='../painel_adm.php';</script>";
    exit;
} catch (PDOException $e) {
    registrarErro("Erro ao cadastrar professor: " . $e->getMessage());
    echo "<script>alert('Erro ao cadastrar professor. Tente novamente mais tarde.'); history.back();</script>";
    exit;
}
