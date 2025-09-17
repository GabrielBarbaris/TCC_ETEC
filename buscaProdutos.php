<?php
header('Content-Type: application/json; charset=UTF-8');

try {
    require_once 'conexao.php';

    $sql = "SELECT id_produto, nome_produto, tipo_quantidade FROM tbProduto ORDER BY nome_produto";
    $result = $conn->query($sql);

    $dados = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $dados[] = [
                'id' => (int)$row['id_produto'],
                'nome' => $row['nome_produto'],
                'tipo' => strtoupper($row['tipo_quantidade'] ?? 'UNIDADE')
            ];
        }
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['erro' => 'Falha na consulta']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Exceção no servidor']);
}
