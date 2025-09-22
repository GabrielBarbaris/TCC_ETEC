<?php
header('Content-Type: application/json; charset=UTF-8');

try {
    require_once 'conexao.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['erro' => 'Parâmetro id inválido']);
        exit;
    }

    // Produto
    $stmt = $conn->prepare('SELECT id_produto, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade FROM tbProduto WHERE id_produto = ? LIMIT 1');
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['erro' => 'Falha ao preparar consulta de produto']);
        exit;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $produto = $res->fetch_assoc();
    $stmt->close();

    if (!$produto) {
        http_response_code(404);
        echo json_encode(['erro' => 'Produto não encontrado']);
        exit;
    }

    // Cortes associados
    $cortes = [];
    $stmt2 = $conn->prepare('SELECT c.id_corte, c.nome_corte FROM tbQuantidadeCorte qc JOIN tbcorte c ON c.id_corte = qc.cod_corte WHERE qc.cod_produto = ? ORDER BY c.nome_corte');
    if ($stmt2) {
        $stmt2->bind_param('i', $id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        while ($row = $res2->fetch_assoc()) {
            $cortes[] = [
                'id' => (int)$row['id_corte'],
                'nome' => $row['nome_corte']
            ];
        }
        $stmt2->close();
    }

    echo json_encode([
        'produto' => [
            'id' => (int)$produto['id_produto'],
            'nome' => $produto['nome_produto'],
            'preco' => (float)$produto['preco'],
            'peso_minimo' => (float)$produto['peso_minimo'],
            'intervalo_peso' => (float)$produto['intervalo_peso'],
            'descricao' => $produto['descricao'],
            'imagem_url' => $produto['imagem_url'],
            'tipo_quantidade' => strtoupper($produto['tipo_quantidade'])
        ],
        'cortes' => $cortes
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Exceção no servidor']);
}