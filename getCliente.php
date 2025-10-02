<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();

require_once __DIR__ . '/conexao.php';

try {
    $id = 0;
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
    } elseif (isset($_POST['id'])) {
        $id = (int) $_POST['id'];
    } elseif (isset($_SESSION['id_usuario'])) {
        $id = (int) $_SESSION['id_usuario'];
    } elseif (isset($_SESSION['id_cliente'])) {
        $id = (int) $_SESSION['id_cliente'];
    }

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['erro' => 'ID inválido']);
        exit;
    }

    $sql = "SELECT id_usuario, nome, sobrenome, telefone, endereco FROM tbUsuario WHERE id_usuario = ? LIMIT 1";
    if (!$stmt = $conn->prepare($sql)) {
        http_response_code(500);
        echo json_encode(['erro' => 'Falha ao preparar consulta']);
        exit;
    }

    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['erro' => 'Falha ao executar consulta']);
        $stmt->close();
        exit;
    }

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'id' => (int)$row['id_usuario'],
            'nome' => (string)($row['nome'] ?? ''),
            'sobrenome' => (string)($row['sobrenome'] ?? ''),
            'telefone' => (string)($row['telefone'] ?? ''),
            'endereco' => (string)($row['endereco'] ?? '')
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'Cliente não encontrado']);
    }
    $stmt->close();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Exceção no servidor']);
}
