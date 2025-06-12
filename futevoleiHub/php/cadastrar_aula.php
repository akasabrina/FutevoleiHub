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

$id_aluno     = isset($_POST['id_aluno']) ? (int)$_POST['id_aluno'] : 0;
$id_professor = isset($_POST['id_professor']) ? (int)$_POST['id_professor'] : 0;
$id_arena     = isset($_POST['id_arena']) ? (int)$_POST['id_arena'] : 0;
$dias_aula    = isset($_POST['dias_aula']) ? implode(', ', array_map('trim', $_POST['dias_aula'])) : '';
$horario_ini  = trim($_POST['horario_inicio'] ?? '');
$horario_fim  = trim($_POST['horario_fim'] ?? '');

if (!$id_aluno || !$id_professor || !$id_arena || empty($dias_aula) || empty($horario_ini) || empty($horario_fim)) {
    echo "<script>alert('Por favor, preencha todos os campos obrigatórios.'); history.back();</script>";
    exit;
}

if (strtotime($horario_fim) <= strtotime($horario_ini)) {
    echo "<script>alert('O horário de término deve ser após o horário de início.'); history.back();</script>";
    exit;
}

try {
    $sql = "INSERT INTO info_geral.aulas_particulares 
            (id_aluno, id_professor, id_arena, dias_aula, horario_inicio, horario_fim)
            VALUES (:id_aluno, :id_professor, :id_arena, :dias_aula, :horario_inicio, :horario_fim)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_aluno'       => $id_aluno,
        ':id_professor'   => $id_professor,
        ':id_arena'       => $id_arena,
        ':dias_aula'      => $dias_aula,
        ':horario_inicio' => $horario_ini,
        ':horario_fim'    => $horario_fim,
    ]);

    echo "<script>alert('Aula particular cadastrada com sucesso!'); window.location.href='../painel_adm.php';</script>";
    exit;
} catch (PDOException $e) {
    registrarErro("Erro ao cadastrar aula: " . $e->getMessage());
    echo "<script>alert('Erro ao cadastrar aula. Verifique os dados.'); history.back();</script>";
    exit;
}
