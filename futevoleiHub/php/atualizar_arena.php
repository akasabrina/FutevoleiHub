<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_arena   = $_POST['id_arena'] ?? '';
  $nome_arena = $_POST['nome_arena'] ?? '';
  $descricao  = $_POST['descricao'] ?? '';
  $url_maps   = $_POST['url_maps'] ?? '';

  // Verifica se todos os campos foram preenchidos
  if (empty($id_arena) || empty($nome_arena) || empty($descricao) || empty($url_maps)) {
    echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
    exit;
  }

  try {
    // Atualiza os dados da arena
    $sql = "UPDATE info_geral.arena
            SET nome_arena = :nome_arena,
                descricao = :descricao,
                url_maps = :url_maps
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
    echo "<script>alert('Erro ao atualizar: " . $e->getMessage() . "'); history.back();</script>";
    exit;
  }
}
?>
