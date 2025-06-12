<?php
include 'conexao.php';
include 'utils.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "<script>alert('Método inválido.'); window.location.href='../index.php';</script>";
  exit;
}

$nome           = trim($_POST["nome"] ?? '');
$id_arena       = $_POST["id_arena"] ?? '';
$email          = trim($_POST["email"] ?? '');
$senha          = $_POST["senha"] ?? '';
$confirmaSenha  = $_POST["confirma_senha"] ?? '';

if (empty($nome) || empty($id_arena) || empty($email) || empty($senha) || empty($confirmaSenha)) {
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
  $stmt = $conn->prepare("SELECT 1 FROM info_geral.alunos WHERE email = :email");
  $stmt->execute([':email' => $email]);

  if ($stmt->rowCount() > 0) {
    echo "<script>alert('Este e-mail já está cadastrado.'); history.back();</script>";
    exit;
  }

  $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("
        INSERT INTO info_geral.alunos (nome_aluno, id_arena, email, senha)
        VALUES (:nome, :id_arena, :email, :senha)
    ");
  $stmt->execute([
    ':nome'     => $nome,
    ':id_arena' => $id_arena,
    ':email'    => $email,
    ':senha'    => $senhaHash
  ]);

  echo "<script>alert('Aluno cadastrado com sucesso!'); window.location.href='../index.php';</script>";
  exit;
} catch (PDOException $e) {
  registrarErro("Erro ao cadastrar aluno ($email): " . $e->getMessage());
  echo "<script>alert('Erro ao cadastrar. Tente novamente mais tarde.'); history.back();</script>";
  exit;
}
