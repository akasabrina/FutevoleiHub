<?php
include 'conexao.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
    echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Requisição inválida.'); window.location.href='../painel_adm.php';</script>";
    exit;
}

$id_aula         = $_POST['id_aula'] ?? '';
$id_professor    = $_POST['id_professor'] ?? '';
$id_arena        = $_POST['id_arena'] ?? '';
$horario_inicio  = $_POST['horario_inicio'] ?? '';
$horario_fim     = $_POST['horario_fim'] ?? '';
$dias_array      = $_POST['dias_aula'] ?? [];

// Transforma o array de dias em string (ex: "Segunda, Quarta")
$dias = is_array($dias_array) ? implode(', ', $dias_array) : '';

if (empty($id_aula)|| empty($id_professor) || empty($id_arena) || empty($dias) || empty($horario_inicio) || empty($horario_fim)) {
    echo "<script>alert('Preencha todos os campos obrigatórios.'); window.history.back();</script>";
    exit;
}

try {
    $sql = "UPDATE info_geral.aulas_particulares 
            SET id_arena = :id_arena, 
                id_professor = :id_professor, 
                dias_aula = :dias_aula, 
                horario_inicio = :horario_inicio, 
                horario_fim = :horario_fim
            WHERE id_aula = :id_aula";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_arena'        => $id_arena,
        ':id_professor'    => $id_professor,
        ':dias_aula'       => $dias,
        ':horario_inicio'  => $horario_inicio,
        ':horario_fim'     => $horario_fim,
        ':id_aula'         => $id_aula,
    ]);

    echo "<script>alert('Aula particular atualizada com sucesso!'); window.location.href='../painel_adm.php';</script>";
    exit;
} catch (PDOException $e) {
    echo "<script>alert('Erro ao atualizar: " . $e->getMessage() . "'); window.history.back();</script>";
    exit;
}
