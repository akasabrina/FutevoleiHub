<?php
session_start();
include 'php/conexao.php';

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';


// Verifica se os campos email e senha estão preenchidos
if (empty($email) || empty($senha)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos.']);
    exit;
}

try {
    // teste 
    //if ($email == 'admin@gmail.com' && $senha == 'admin'){
    //    $_SESSION['id'] = 1;
    //    $_SESSION['nome'] = 'sabrina';
    //    $_SESSION['tipo'] = 'administrador';
    //    echo json_encode(['status' => 'administrador']);
    //    exit;
    //}

    // 1. Tenta encontrar o administrador
    $stmt = $conn->prepare("SELECT id_adm AS id, nome_adm AS nome, senha FROM info_geral.adm WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $adm = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adm && password_verify($senha, $adm['senha'])) {  
        $_SESSION['id'] = $adm['id'];
        $_SESSION['nome'] = $adm['nome'];
        $_SESSION['tipo'] = 'administrador';
        echo json_encode(['status' => 'administrador']);
        exit;
    }

    // 2. Tenta encontrar o aluno
    $stmt = $conn->prepare("SELECT id_aluno AS id, nome_aluno AS nome, senha FROM info_geral.alunos WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($aluno && password_verify($senha, $aluno['senha'])) {
        $_SESSION['id'] = $aluno['id'];
        $_SESSION['nome'] = $aluno['nome'];
        $_SESSION['tipo'] = 'aluno';
        echo json_encode(['status' => 'aluno']);
        exit;
    }

    // 3. Tenta encontrar o professor
    $stmt = $conn->prepare("SELECT id_professor AS id, nome_professor AS nome, senha FROM info_geral.professores WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $prof = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prof && password_verify($senha, $prof['senha'])) { 
        $_SESSION['id'] = $prof['id'];
        $_SESSION['nome'] = $prof['nome'];
        $_SESSION['tipo'] = 'professor';
        echo json_encode(['status' => 'professor']);
        exit;
    }

    // Se não achou em nenhum dos dois
    echo json_encode(['status' => 'erro', 'mensagem' => 'Email ou senha inválidos.']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no banco: ' . $e->getMessage()]);
}
?>