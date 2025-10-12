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

// Ação/Status alvo opcional, para permitir reversões explícitas
$target = '';
foreach (['acao', 'action', 'target', 'status'] as $k) {
    if (isset($_POST[$k])) { $target = strtoupper(trim((string)$_POST[$k])); break; }
}

try {
    // 1) Busca status atual
    if (!$stmt = $conn->prepare('SELECT status FROM tbPedido WHERE id_pedido = ? LIMIT 1')) {
        throw new Exception('Falha ao preparar SELECT: ' . $conn->error);
    }
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        throw new Exception('Falha ao executar SELECT: ' . $stmt->error);
    }
    $res = $stmt->get_result();
    if (!$res || $res->num_rows === 0) {
        $stmt->close();
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Pedido não encontrado']);
        exit;
    }
    $row = $res->fetch_assoc();
    $stmt->close();

    $statusAtual = strtoupper((string)($row['status'] ?? ''));

    // Funções auxiliares
    $setStatus = function(string $from, string $to) use ($conn, $id) : bool {
        $extra = '';
        if ($to === 'PRONTO') { $extra = ", notificado = 1"; }
        if ($to === 'PENDENTE') { $extra = ", notificado = 0"; }
        $sql = "UPDATE tbPedido SET status = '$to'" . $extra . " WHERE id_pedido = ? AND status = '$from'";
        if (!$stmt = $conn->prepare($sql)) { throw new Exception('Falha ao preparar UPDATE: ' . $conn->error); }
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) { $err = $stmt->error; $stmt->close(); throw new Exception('Falha ao executar UPDATE: ' . $err); }
        $ok = $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    };

    $respondOk = function(string $status, array $extra = []) use ($id) {
        echo json_encode(array_merge(['success' => true, 'id' => $id, 'status' => $status], $extra));
        exit;
    };

    // 2) Se houver alvo explícito, tenta aplicá-lo
    if ($target !== '') {
        switch ($target) {
            case 'PENDENTE':
                if ($statusAtual === 'PRONTO') {
                    if ($setStatus('PRONTO', 'PENDENTE')) $respondOk('PENDENTE');
                    echo json_encode(['success' => false, 'message' => 'Já estava PENDENTE ou pedido não encontrado.']);
                    exit;
                }
                if ($statusAtual === 'PENDENTE') $respondOk('PENDENTE', ['message' => 'Pedido já estava PENDENTE.']);
                echo json_encode(['success' => false, 'error' => 'Transição inválida para PENDENTE a partir de ' . $statusAtual]);
                exit;
            case 'PRONTO':
                if ($statusAtual === 'PENDENTE') {
                    if ($setStatus('PENDENTE', 'PRONTO')) $respondOk('PRONTO', ['notificado' => true]);
                    echo json_encode(['success' => false, 'message' => 'Já estava PRONTO ou pedido não encontrado.']);
                    exit;
                }
                if ($statusAtual === 'PRONTO') $respondOk('PRONTO', ['message' => 'Pedido já estava PRONTO.']);
                echo json_encode(['success' => false, 'error' => 'Transição inv��lida para PRONTO a partir de ' . $statusAtual]);
                exit;
            case 'ENTREGUE':
                if ($statusAtual === 'PRONTO') {
                    if ($setStatus('PRONTO', 'ENTREGUE')) $respondOk('ENTREGUE');
                    echo json_encode(['success' => false, 'message' => 'Já estava ENTREGUE ou pedido não encontrado.']);
                    exit;
                }
                if ($statusAtual === 'ENTREGUE') $respondOk('ENTREGUE', ['message' => 'Pedido já estava ENTREGUE.']);
                echo json_encode(['success' => false, 'error' => 'Transição inválida para ENTREGUE a partir de ' . $statusAtual]);
                exit;
            default:
                echo json_encode(['success' => false, 'error' => 'Alvo/ação inválido: ' . $target]);
                exit;
        }
    }

    // 3) Fluxo padrão (retrocompatibilidade):
    // PENDENTE -> PRONTO; PRONTO -> ENTREGUE; ENTREGUE -> idempotente
    if ($statusAtual === 'PENDENTE') {
        if ($setStatus('PENDENTE', 'PRONTO')) $respondOk('PRONTO', ['notificado' => true]);
        echo json_encode(['success' => false, 'message' => 'Pedido não encontrado ou já estava PRONTO.']);
        exit;
    } elseif ($statusAtual === 'PRONTO') {
        if ($setStatus('PRONTO', 'ENTREGUE')) $respondOk('ENTREGUE');
        echo json_encode(['success' => false, 'message' => 'Pedido não encontrado ou já estava ENTREGUE.']);
        exit;
    } elseif ($statusAtual === 'ENTREGUE') {
        $respondOk('ENTREGUE', ['message' => 'Pedido já estava ENTREGUE.']);
    } else {
        // Estado inesperado: tenta setar como PRONTO por segurança
        $sql = "UPDATE tbPedido SET status = 'PRONTO', notificado = 1 WHERE id_pedido = ?";
        if (!$stmt = $conn->prepare($sql)) { throw new Exception('Falha ao preparar UPDATE fallback: ' . $conn->error); }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        $respondOk('PRONTO');
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
