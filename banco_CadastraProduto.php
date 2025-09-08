<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'erro';
    exit;
}

// Helpers
function normalizeDecimal($val) {
    if ($val === null) return null;
    $val = trim((string)$val);
    if ($val === '') return null;
    // Converte vírgula para ponto e remove qualquer caractere inválido
    $val = str_replace([' ', ','], ['', '.'], $val);
    // Mantém apenas dígitos e ponto
    $val = preg_replace('/[^0-9.]/', '', $val);
    // Garante apenas um ponto
    $parts = explode('.', $val);
    if (count($parts) > 2) {
        $val = $parts[0] . '.' . implode('', array_slice($parts, 1));
    }
    return is_numeric($val) ? (float)$val : null;
}

// Captura e valida campos
$nome       = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$preco      = normalizeDecimal($_POST['preco'] ?? null);
$categoria  = isset($_POST['categoria']) ? (int)$_POST['categoria'] : 0;
$medida     = isset($_POST['medida']) ? strtoupper(trim($_POST['medida'])) : '';
$pesoMin    = normalizeDecimal($_POST['peso'] ?? null);
$intervalo  = normalizeDecimal($_POST['intervalo'] ?? null);
$descricao  = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
$tipos      = isset($_POST['tipo']) && is_array($_POST['tipo']) ? array_filter($_POST['tipo'], 'strlen') : [];

if ($nome === '' || $preco === null || $categoria <= 0 || ($medida !== 'PESO' && $medida !== 'UNIDADE')) {
    echo 'erro';
    exit;
}

// Para UNIDADE, caso não enviados, define como 0.00 para atender NOT NULL
if ($medida === 'UNIDADE') {
    $pesoMin = $pesoMin ?? 0.00;
    $intervalo = $intervalo ?? 0.00;
} else {
    // Para PESO, requer valores numéricos
    if ($pesoMin === null || $intervalo === null) {
        echo 'erro';
        exit;
    }
}

// Upload da imagem (opcionalmente obrigatório conforme formulário)
$imagemUrl = null;
if (isset($_FILES['imagem']) && isset($_FILES['imagem']['error']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        echo 'erro';
        exit;
    }

    $allowedExt = ['jpg','jpeg','png','webp'];
    $original   = $_FILES['imagem']['name'] ?? '';
    $ext        = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        echo 'erro';
        exit;
    }

    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'uploads';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
            echo 'erro';
            exit;
        }
    }

    $filename = uniqid('prod_', true) . '.' . $ext;
    $destFs   = $uploadDir . DIRECTORY_SEPARATOR . $filename;
    $moved    = move_uploaded_file($_FILES['imagem']['tmp_name'], $destFs);
    if (!$moved) {
        echo 'erro';
        exit;
    }
    // Caminho relativo a ser salvo no banco (compatível com os src do site)
    $imagemUrl = 'img/uploads/' . $filename;
}

// Transação para garantir consistência entre produto e seus cortes
$conn->begin_transaction();
try {
    // Insert do produto
    $stmt = $conn->prepare("INSERT INTO tbProduto 
        (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) { throw new Exception('prepare produto'); }

    $stmt->bind_param(
        'isdddsss',
        $categoria,
        $nome,
        $preco,
        $pesoMin,
        $intervalo,
        $descricao,
        $imagemUrl,
        $medida
    );

    if (!$stmt->execute()) { throw new Exception('exec produto'); }
    $produtoId = $stmt->insert_id;
    $stmt->close();

    // Insert dos cortes selecionados (se houver)
    if (!empty($tipos)) {
        $stmtCorte = $conn->prepare("INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES (?, ?)");
        if (!$stmtCorte) { throw new Exception('prepare cortes'); }
        foreach ($tipos as $corteId) {
            $corteId = (int)$corteId;
            if ($corteId <= 0) { continue; }
            $stmtCorte->bind_param('ii', $produtoId, $corteId);
            if (!$stmtCorte->execute()) { throw new Exception('exec cortes'); }
        }
        $stmtCorte->close();
    }

    $conn->commit();
    echo 'ok';
} catch (Throwable $e) {
    $conn->rollback();
    echo 'erro';
}

$conn->close();
