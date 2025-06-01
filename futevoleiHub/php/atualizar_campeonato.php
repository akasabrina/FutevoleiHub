<?php
include 'conexao.php';
session_start();

// Verifica se o aluno está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

// Coleta os dados do formulário
$id        = $_POST["id_camp"];
$nome_camp = $_POST["nome_camp"];
$descricao = $_POST["descricao"];
$categoria = $_POST["categoria"];
$data_camp = $_POST["data_camp"];
$fim       = $_POST["data_fim_inscricao"];
$arena     = $_POST["id_arena"];

// Validações básicas
if (empty($nome_camp) || empty($descricao) || empty($categoria) || empty($data_camp) || empty($fim) || empty($arena)) {
  echo "<script>alert('Preencha todos os campos obrigatórios.'); window.history.back();</script>";
  exit;
}

try {
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "UPDATE info_geral.campeonatos 
          SET nome_camp = :nome, descricao = :descricao, categoria = :categoria, data_camp = :data_camp, data_fim_inscricao = :fim, id_arena = :arena 
          WHERE id_camp = :id";

    if ($data_camp < $fim) {
      echo "<script>alert('O fim das inscrições tem que ser antes do dia do campeonato'); history.back();</script>";
      exit;
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
      ':nome'      => $nome_camp,
      ':descricao' => $descricao,
      ':categoria' => $categoria,
      ':data_camp' => $data_camp,
      ':fim'       => $fim,
      ':arena'     => $arena,
      ':id'        => $id
    ]);

    echo "<script>alert('Informações do campeonato atualizadas com sucesso.'); window.location.href='../painel_adm.php';</script>";
    exit;
  }
} catch (PDOException $e) {
  echo "<script>alert('Erro ao atualizar: " . $e->getMessage() . "'); window.history.back();</script>";
  exit;
}
