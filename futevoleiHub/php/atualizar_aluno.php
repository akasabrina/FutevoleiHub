<?php
include 'conexao.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'aluno') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "<script>alert('Método inválido.'); window.location.href='../painel_aluno.php';</script>";
  exit;
}

$id_aluno    = $_SESSION['id'];
$nome_aluno  = $_POST['nome_aluno'] ?? '';
$id_arena    = $_POST['id_arena'] ?? '';
$email       = $_POST['email'] ?? '';
$senha_atual = $_POST['senha_atual'] ?? '';
$nova_senha  = $_POST['nova_senha'] ?? '';

if (empty($nome_aluno) || empty($id_arena) || empty($email)) {
  echo "<script>alert('Preencha todos os campos obrigatórios.'); window.history.back();</script>";
  exit;
}

try {
  $stmt = $conn->prepare("SELECT senha FROM info_geral.alunos WHERE id_aluno = :id");
  $stmt->execute([':id' => $id_aluno]);
  $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$aluno) {
    echo "<script>alert('Aluno não encontrado.'); window.history.back();</script>";
    exit;
  }

  if (!empty($nova_senha)) {
    if (empty($senha_atual)) {
      echo "<script>alert('Digite a senha atual para alterar.'); window.history.back();</script>";
      exit;
    }

    if (!password_verify($senha_atual, $aluno['senha'])) {
      echo "<script>alert('Senha atual incorreta.'); window.history.back();</script>";
      exit;
    }

    if (password_verify($nova_senha, $aluno['senha'])) {
      echo "<script>alert('A nova senha deve ser diferente da atual.'); window.history.back();</script>";
      exit;
    }

    $novaSenhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);
  } else {
    $novaSenhaHash = $aluno['senha'];
  }

  $stmt = $conn->prepare("UPDATE info_geral.alunos 
                          SET nome_aluno = :nome, id_arena = :arena, email = :email, senha = :senha 
                          WHERE id_aluno = :id");
  $stmt->execute([
    ':nome'   => $nome_aluno,
    ':arena'  => $id_arena,
    ':email'  => $email,
    ':senha'  => $novaSenhaHash,
    ':id'     => $id_aluno
  ]);

  // Atualiza o nome na sessão
  $_SESSION['nome'] = $nome_aluno;

  echo "<script>alert('Informações atualizadas com sucesso.'); window.location.href='../painel_aluno.php';</script>";
  exit;

} catch (PDOException $e) {
  echo "<script>alert('Erro ao atualizar: " . $e->getMessage() . "'); window.history.back();</script>";
  exit;
}

