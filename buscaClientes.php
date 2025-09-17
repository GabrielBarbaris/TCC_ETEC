<?php
header('Content-Type: application/json; charset=UTF-8');

try {
    require_once 'conexao.php';

    $sql = "SELECT id_usuario, nome, sobrenome FROM tbUsuario WHERE tipo_usuario = 'cliente' ORDER BY nome";
    $result = $conn->query($sql);

    $dados = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $dados[] = [
                'id' => (int)$row['id_usuario'],
                'nome' => (string)$row['nome'],
                'sobrenome' => (string)($row['sobrenome'] ?? '')
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
