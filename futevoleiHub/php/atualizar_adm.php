<?php
include 'conexao.php';
include 'utils.php';

session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "<script>alert('Método inválido.'); window.location.href='../painel_adm.php';</script>";
  exit;
}

$id_adm      = isset($_POST['id_adm']) ? (int) $_POST['id_adm'] : 0;
$nome_adm    = trim($_POST['nome_adm'] ?? '');
$senha_atual = $_POST['senha_atual'] ?? '';
$nova_senha  = $_POST['nova_senha'] ?? '';

if ($id_adm <= 0 || $nome_adm === '') {
  echo "<script>alert('Preencha todos os campos obrigatórios.'); window.history.back();</script>";
  exit;
}

try {
  $stmt = $conn->prepare("SELECT senha FROM info_geral.adm WHERE id_adm = :id");
  $stmt->execute([':id' => $id_adm]);
  $adm = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$adm) {
    echo "<script>alert('Administrador não encontrado.'); window.history.back();</script>";
    exit;
  }

  // Se for alterar a senha, validar senha atual
  if (!empty($nova_senha)) {
    if (empty($senha_atual)) {
      echo "<script>alert('Para alterar a senha, informe a senha atual.'); window.history.back();</script>";
      exit;
    }

    if (!password_verify($senha_atual, $adm['senha'])) {
      echo "<script>alert('Senha atual incorreta.'); window.history.back();</script>";
      exit;
    }

    if (password_verify($nova_senha, $adm['senha'])) {
      echo "<script>alert('A nova senha deve ser diferente da atual.'); window.history.back();</script>";
      exit;
    }

    $senha_final = password_hash($nova_senha, PASSWORD_DEFAULT);
  } else {
    // Se não for alterar, mantém a senha atual
    $senha_final = $adm['senha'];
  }

  $stmt = $conn->prepare("
        UPDATE info_geral.adm 
        SET nome_adm = :nome, senha = :senha 
        WHERE id_adm = :id
    ");

  $stmt->execute([
    ':nome'  => $nome_adm,
    ':senha' => $senha_final,
    ':id'    => $id_adm
  ]);

  echo "<script>alert('Informações do administrador atualizadas com sucesso.'); window.location.href='../painel_adm.php';</script>";
  exit;
} catch (PDOException $e) {
  registrarErro("Erro ao atualizar administrador: " . $e->getMessage());
  echo "<script>alert('Erro ao atualizar administrador. Por favor, tente novamente mais tarde.'); window.history.back();</script>";
  exit;
}
