<?php
include 'conexao.php';
session_start();

if (!isset($_SESSION['id']) || !in_array($_SESSION['tipo'], ['professor', 'aluno'])) {
    echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_campeonato'])) {
  $id_usuario = $_SESSION['id'];
  $tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'professor'
  $id_campeonato = $_POST['id_campeonato'];

  try {
    // Verifica se o usuário já está inscrito neste campeonato
    if ($tipo_usuario === 'aluno') {
      $verifica = $conn->prepare("SELECT 1 FROM info_geral.inscricao_campeonato WHERE id_aluno = :id AND id_camp = :id_campeonato");
    } else {
      $verifica = $conn->prepare("SELECT 1 FROM info_geral.inscricao_campeonato WHERE id_professor = :id AND id_camp = :id_campeonato");
    }

    $verifica->execute([
      ':id' => $id_usuario,
      ':id_campeonato' => $id_campeonato
    ]);

    if ($verifica->rowCount() > 0) {
      echo "<script>alert('Você já está inscrito neste campeonato.'); history.back();</script>";
      exit;
    }

    // Inserção da inscrição
    if ($tipo_usuario === 'aluno') {
      $stmt = $conn->prepare("INSERT INTO info_geral.inscricao_campeonato (id_camp, id_aluno) VALUES (:id_campeonato, :id_usuario)");
    } else {
      $stmt = $conn->prepare("INSERT INTO info_geral.inscricao_campeonato (id_camp, id_professor) VALUES (:id_campeonato, :id_usuario)");
    }

    $stmt->execute([
      ':id_campeonato' => $id_campeonato,
      ':id_usuario' => $id_usuario
    ]);

    echo "<script>alert('Inscrição realizada com sucesso!'); window.location.href='../painel_{$tipo_usuario}.php';</script>";

  } catch (PDOException $e) {
    echo "<script>alert('Erro ao realizar inscrição: " . $e->getMessage() . "'); history.back();</script>";
  }
} else {
  echo "<script>alert('Requisição inválida.'); history.back();</script>";
}
