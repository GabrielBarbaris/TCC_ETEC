<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

$id = 0;
if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
} elseif (isset($_POST['id_pedido'])) {
    $id = (int) $_POST['id_pedido'];
}

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Parâmetro id inválido']);
    exit;
}

try {
    $sql = "UPDATE tbPedido SET status = 'PRONTO', notificado = 1 WHERE id_pedido = ? AND status = 'PENDENTE'";
    if (!$stmt = $conn->prepare($sql)) {
        throw new Exception('Falha ao preparar a query: ' . $conn->error);
    }

    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        throw new Exception('Falha ao executar a query: ' . $stmt->error);
    }

    $updated = $stmt->affected_rows;
    $stmt->close();

    if ($updated > 0) {
        echo json_encode([
            'success' => true,
            'id' => $id,
            'status' => 'PRONTO',
            'notificado' => true
        ]);
    } else {
        // Já atualizado ou não encontrado
        echo json_encode([
            'success' => false,
            'message' => 'Pedido não encontrado ou já estava PRONTO.'
        ]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
