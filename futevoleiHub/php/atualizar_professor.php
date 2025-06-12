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

$id_professor   = isset($_POST['id_professor']) ? (int)$_POST['id_professor'] : 0;
$nome_professor = trim($_POST['nome_professor'] ?? '');
$arena          = isset($_POST['id_arena']) ? (int)$_POST['id_arena'] : 0;
$senha_atual    = $_POST['senha_atual'] ?? '';
$nova_senha     = $_POST['nova_senha'] ?? '';

if (empty($id_professor) || empty($nome_professor) || empty($arena)) {
  echo "<script>alert('Preencha todos os campos obrigatórios.'); window.history.back();</script>";
  exit;
}

try {
  $stmt = $conn->prepare("SELECT senha FROM info_geral.professores WHERE id_professor = :id");
  $stmt->execute([':id' => $id_professor]);
  $dados = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$dados) {
    echo "<script>alert('Professor não encontrado.'); window.history.back();</script>";
    exit;
  }

  // Se for alterar a senha, validar senha atual
  if (!empty($nova_senha)) {
    if (empty($senha_atual)) {
      echo "<script>alert('Para alterar a senha, informe a senha atual.'); window.history.back();</script>";
      exit;
    }

    if ($nova_senha === $senha_atual) {
      echo "<script>alert('A nova senha não pode ser igual à senha atual.'); window.history.back();</script>";
      exit;
    }

    if (!password_verify($senha_atual, $dados['senha'])) {
      echo "<script>alert('Senha atual incorreta.'); window.history.back();</script>";
      exit;
    }

    $senha_final = password_hash($nova_senha, PASSWORD_DEFAULT);
  } else {
    // Se não for alterar, mantém a senha atual
    $senha_final = $dados['senha'];
  }

  // Atualiza os dados no banco
  $sql = "
        UPDATE info_geral.professores
        SET nome_professor = :nome, id_arena = :arena, senha = :senha
        WHERE id_professor = :id
    ";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':nome'  => $nome_professor,
    ':arena' => $arena,
    ':senha' => $senha_final,
    ':id'    => $id_professor
  ]);

  echo "<script>alert('Informações do professor atualizadas com sucesso.'); window.location.href='../painel_adm.php';</script>";
  exit;
} catch (PDOException $e) {
  registrarErro("Erro ao atualizar professor (ID $id_professor): " . $e->getMessage());
  echo "<script>alert('Erro ao atualizar. Tente novamente mais tarde.'); window.history.back();</script>";
  exit;
}
