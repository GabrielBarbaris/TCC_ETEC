<?php
include "conexao.php";

// Responde apenas com texto simples para facilitar o tratamento no JS
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "erro";
    exit;
}

$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$sobrenome = isset($_POST['sobrenome']) ? trim($_POST['sobrenome']) : '';
$telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
$senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

// Validação básica
if ($nome === '' || $sobrenome === '' || $telefone === '' || $senha === '') {
    echo "erro";
    $conn->close();
    exit;
}

// Verifica se já existe usuário com o mesmo telefone
$stmt = $conn->prepare("SELECT 1 FROM tbUsuario WHERE telefone = ? LIMIT 1");
if ($stmt) {
    $stmt->bind_param('s', $telefone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Telefone já cadastrado
        echo "erro";
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();
} else {
    echo "erro";
    $conn->close();
    exit;
}

// Realiza o cadastro
$stmt = $conn->prepare("INSERT INTO tbUsuario (nome, sobrenome, senha, tipo_usuario, telefone) VALUES (?, ?, ?, 'cliente', ?)");
if ($stmt) {
    $stmt->bind_param('ssss', $nome, $sobrenome, $senha, $telefone);
    $ok = $stmt->execute();

    if ($ok && $stmt->affected_rows > 0) {
        echo "ok";
    } else {
        echo "erro";
    }

    $stmt->close();
} else {
    echo "erro";
}

$conn->close();
