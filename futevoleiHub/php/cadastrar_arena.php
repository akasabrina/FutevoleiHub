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

$nome_arena = trim($_POST['nome_arena'] ?? '');
$descricao  = trim($_POST['descricao'] ?? '');
$url_iframe = trim($_POST['url_maps_iframe'] ?? '');
$url_direto = trim($_POST['url_maps_direto'] ?? '');

if (empty($nome_arena) || empty($descricao) || empty($url_iframe) || empty($url_direto)) {
  echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
  exit;
}

try {
  $stmt = $conn->prepare("SELECT 1 FROM info_geral.arena WHERE nome_arena = :nome");
  $stmt->execute([':nome' => $nome_arena]);

  if ($stmt->rowCount() > 0) {
    echo "<script>alert('Já existe uma arena com esse nome.'); history.back();</script>";
    exit;
  }

  $stmt = $conn->prepare("
        INSERT INTO info_geral.arena (nome_arena, descricao, url_maps_iframe, url_maps_direto)
        VALUES (:nome_arena, :descricao, :url_iframe, :url_direto)
    ");
  $stmt->execute([
    ':nome_arena' => $nome_arena,
    ':descricao'  => $descricao,
    ':url_iframe' => $url_iframe,
    ':url_direto' => $url_direto
  ]);

  echo "<script>alert('Arena cadastrada com sucesso!'); window.location.href='../painel_adm.php';</script>";
  exit;
} catch (PDOException $e) {
  registrarErro("Erro ao cadastrar arena ({$nome_arena}): " . $e->getMessage());
  echo "<script>alert('Erro ao cadastrar. Tente novamente mais tarde.'); history.back();</script>";
  exit;
}
