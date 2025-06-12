<?php
include 'conexao.php';
include 'utils.php';

session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "<script>alert('Requisição inválida.'); window.location.href='../painel_professor.php';</script>";
  exit;
}

$id_professor_session = $_SESSION['id'];

// Sanitização
$id_professor_form = isset($_POST['id_professor']) ? (int)$_POST['id_professor'] : 0;
$nome              = trim($_POST['nome_professor'] ?? '');
$email             = trim($_POST['email'] ?? '');
$senha_atual       = $_POST['senha_atual'] ?? '';
$nova_senha        = $_POST['nova_senha'] ?? '';

// Verifica se o professor está tentando editar seus próprios dados
if ($id_professor_form !== $id_professor_session) {
  echo "<script>alert('Você só pode editar suas próprias informações.'); window.location.href='../painel_professor.php';</script>";
  exit;
}

// Validação de campos obrigatórios
if (empty($nome) || empty($email)) {
  echo "<script>alert('Nome e e-mail são obrigatórios.'); window.history.back();</script>";
  exit;
}

try {
  // Atualiza nome e e-mail
  $stmtUpdate = $conn->prepare("UPDATE info_geral.professores 
                                  SET nome_professor = :nome, email = :email 
                                  WHERE id_professor = :id");
  $stmtUpdate->execute([
    ':nome' => $nome,
    ':email' => $email,
    ':id' => $id_professor_session
  ]);

  // Atualiza senha, se necessário
  if (!empty($senha_atual) && !empty($nova_senha)) {
    $stmtSenha = $conn->prepare("SELECT senha FROM info_geral.professores WHERE id_professor = :id");
    $stmtSenha->execute([':id' => $id_professor_session]);
    $senha_hash = $stmtSenha->fetchColumn();

    if (!password_verify($senha_atual, $senha_hash)) {
      echo "<script>alert('Senha atual incorreta.'); window.history.back();</script>";
      exit;
    }

    if ($senha_atual === $nova_senha) {
      echo "<script>alert('A nova senha não pode ser igual à senha atual.'); window.history.back();</script>";
      exit;
    }

    $nova_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    $stmtUpdateSenha = $conn->prepare("UPDATE info_geral.professores 
                                           SET senha = :nova 
                                           WHERE id_professor = :id");
    $stmtUpdateSenha->execute([
      ':nova' => $nova_hash,
      ':id' => $id_professor_session
    ]);
  }

  echo "<script>alert('Informações atualizadas com sucesso!'); window.location.href='../painel_professor.php';</script>";
  exit;
} catch (PDOException $e) {
  registrarErro("Erro ao atualizar os dados: " . $e->getMessage());
  echo "<script>alert('Erro ao atualizar. Tente novamente mais tarde.'); window.history.back();</script>";
  exit;
}
