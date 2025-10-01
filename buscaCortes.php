<?php
header('Content-Type: application/json; charset=UTF-8');
require_once 'conexao.php';

$produtoNome = isset($_GET['produto']) ? trim((string)$_GET['produto']) : '';
$resultado = [];

if ($produtoNome !== '') {
    // Busca os cortes associados ao produto informado
    $sql = 'SELECT c.id_corte, c.nome_corte
            FROM tbProduto p
            JOIN tbQuantidadeCorte qc ON qc.cod_produto = p.id_produto
            JOIN tbcorte c ON c.id_corte = qc.cod_corte
            WHERE p.nome_produto = ?
            ORDER BY c.nome_corte';

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $produtoNome);
        if ($stmt->execute()) {
            $stmt->bind_result($idCorte, $nomeCorte);
            while ($stmt->fetch()) {
                $resultado[] = [
                    'id' => (int)$idCorte,
                    'nome' => (string)$nomeCorte,
                ];
            }
        }
        $stmt->close();
    }
}

echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
