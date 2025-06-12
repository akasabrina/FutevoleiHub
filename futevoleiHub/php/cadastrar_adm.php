<?php
include 'conexao.php';
include 'utils.php';

session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "<script>alert('Método inválido.'); window.location.href='../painel_adm.php';</script>";
  exit;
}

$nome          = trim($_POST['nome_adm'] ?? '');
$email         = trim($_POST['email'] ?? '');
$senha         = $_POST['senha'] ?? '';
$confirmaSenha = $_POST['confirma_senha'] ?? '';

if (empty($nome) || empty($email) || empty($senha) || empty($confirmaSenha)) {
  echo "<script>alert('Por favor, preencha todos os campos.'); history.back();</script>";
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo "<script>alert('E-mail inválido.'); history.back();</script>";
  exit;
}

if ($senha !== $confirmaSenha) {
  echo "<script>alert('As senhas não coincidem.'); history.back();</script>";
  exit;
}

try {
  // Verifica se o e-mail já está cadastrado
  $stmt = $conn->prepare("SELECT 1 FROM info_geral.adm WHERE email = :email");
  $stmt->execute([':email' => $email]);

  if ($stmt->rowCount() > 0) {
    echo "<script>alert('Este e-mail já está cadastrado.'); history.back();</script>";
    exit;
  }

  $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("
        INSERT INTO info_geral.adm (nome_adm, email, senha)
        VALUES (:nome, :email, :senha)
    ");
  $stmt->execute([
    ':nome'  => $nome,
    ':email' => $email,
    ':senha' => $senhaHash
  ]);

  echo "<script>alert('Administrador cadastrado com sucesso!'); window.location.href='../painel_adm.php';</script>";
  exit;
} catch (PDOException $e) {
  registrarErro("Erro ao cadastrar administrador ($email): " . $e->getMessage());
  echo "<script>alert('Erro ao cadastrar. Tente novamente mais tarde.'); history.back();</script>";
  exit;
}
