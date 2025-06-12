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

$id_arena   = isset($_POST['id_arena']) ? (int) $_POST['id_arena'] : 0;
$nome_arena = trim($_POST['nome_arena'] ?? '');
$descricao  = trim($_POST['descricao'] ?? '');
$url_maps   = trim($_POST['url_maps'] ?? '');

if ($id_arena <= 0 || $nome_arena === '' || $descricao === '' || $url_maps === '') {
  echo "<script>alert('Por favor, preencha todos os campos obrigatórios.'); window.history.back();</script>";
  exit;
}

try {
  $sql = "UPDATE info_geral.arena
          SET nome_arena = :nome_arena, descricao = :descricao, url_maps = :url_maps
          WHERE id_arena = :id_arena";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':nome_arena' => $nome_arena,
    ':descricao'  => $descricao,
    ':url_maps'   => $url_maps,
    ':id_arena'   => $id_arena
  ]);

  echo "<script>alert('Arena atualizada com sucesso!'); window.location.href='../painel_adm.php';</script>";
  exit;

} catch (PDOException $e) {
  registrarErro("Erro ao atualizar arena: " . $e->getMessage());
  echo "<script>alert('Erro ao atualizar arena. Por favor, tente novamente mais tarde.'); window.history.back();</script>";
  exit;
}
