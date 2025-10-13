<?php
session_start();
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

if (!isset($_SESSION['id_usuario']) || !$_SESSION['id_usuario']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];
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
    // 1) Verifica se o pedido pertence ao usuário e está PENDENTE
    if (!$stmt = $conn->prepare("SELECT status FROM tbPedido WHERE id_pedido = ? AND cod_usuario = ? LIMIT 1")) {
        throw new Exception('Falha ao preparar consulta de verificação: ' . $conn->error);
    }
    $stmt->bind_param('ii', $id, $idUsuario);
    if (!$stmt->execute()) {
        throw new Exception('Falha ao executar consulta de verificação: ' . $stmt->error);
    }
    $res = $stmt->get_result();
    if (!$res || $res->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Pedido não encontrado ou não pertence a este usuário']);
        $stmt->close();
        exit;
    }
    $row = $res->fetch_assoc();
    $stmt->close();

    if (strtoupper((string)($row['status'] ?? '')) !== 'PENDENTE') {
        echo json_encode([
            'success' => false,
            'message' => 'Pedido não pode ser cancelado (já finalizado/cancelado)'
        ]);
        exit;
    }

    // 2) Exclui o pedido e seus itens em transação
    $conn->begin_transaction();

    // Exclui itens do pedido (se houver)
    if (!$stmt = $conn->prepare("DELETE FROM tbPedidoProduto WHERE cod_pedido = ?")) {
        throw new Exception('Falha ao preparar exclusão dos itens: ' . $conn->error);
    }
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        throw new Exception('Falha ao excluir itens do pedido: ' . $stmt->error);
    }
    $stmt->close();

    // Exclui o pedido (garante que pertence ao usuário)
    if (!$stmt = $conn->prepare("DELETE FROM tbPedido WHERE id_pedido = ? AND cod_usuario = ?")) {
        throw new Exception('Falha ao preparar exclusão do pedido: ' . $conn->error);
    }
    $stmt->bind_param('ii', $id, $idUsuario);
    if (!$stmt->execute()) {
        throw new Exception('Falha ao excluir pedido: ' . $stmt->error);
    }
    $deleted = $stmt->affected_rows;
    $stmt->close();

    if ($deleted <= 0) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Pedido não pôde ser excluído (inexistente ou não pertence a você)'
        ]);
        exit;
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'id' => $id,
        'deleted' => true
    ]);
} catch (Throwable $e) {
    try { $conn->rollback(); } catch (Throwable $ignore) {}
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
