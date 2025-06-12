<?php
include 'conexao.php';
include 'utils.php';

session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
    echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Requisição inválida.'); window.location.href='../painel_adm.php';</script>";
    exit;
}

$id_aula        = isset($_POST['id_aula']) ? (int) $_POST['id_aula'] : 0;
$id_professor   = isset($_POST['id_professor']) ? (int) $_POST['id_professor'] : 0;
$id_arena       = isset($_POST['id_arena']) ? (int) $_POST['id_arena'] : 0;
$horario_inicio = trim($_POST['horario_inicio'] ?? '');
$horario_fim    = trim($_POST['horario_fim'] ?? '');
$dias_array     = $_POST['dias_aula'] ?? [];

// Transforma o array de dias em string (ex: "Segunda, Quarta")
$dias = is_array($dias_array) ? implode(', ', $dias_array) : '';

if ($id_aula <= 0 || $id_professor <= 0 || $id_arena <= 0 || empty($dias) || $horario_inicio === '' || $horario_fim === '') {
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
        ':id_arena'       => $id_arena,
        ':id_professor'   => $id_professor,
        ':dias_aula'      => $dias,
        ':horario_inicio' => $horario_inicio,
        ':horario_fim'    => $horario_fim,
        ':id_aula'        => $id_aula,
    ]);

    echo "<script>alert('Aula particular atualizada com sucesso!'); window.location.href='../painel_adm.php';</script>";
    exit;
} catch (PDOException $e) {
    registrarErro("Erro ao atualizar aula particular: " . $e->getMessage());
    echo "<script>alert('Erro ao atualizar aula particular. Por favor, tente novamente mais tarde.'); window.history.back();</script>";
    exit;
}
