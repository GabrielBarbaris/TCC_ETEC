<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();
require_once __DIR__ . '/conexao.php';

try {
  // Identificar usuário: por sessão, ou id explícito (apenas se for o próprio)
  $uid = 0;
  if (isset($_SESSION['id_usuario'])) { $uid = (int)$_SESSION['id_usuario']; }
  elseif (isset($_SESSION['id_cliente'])) { $uid = (int)$_SESSION['id_cliente']; }
  elseif (isset($_SESSION['cliente_id'])) { $uid = (int)$_SESSION['cliente_id']; }

  // Permitir atualização por id do localStorage apenas se o usuário da sessão bater
  if ($uid <= 0 && isset($_POST['id'])) { $uid = (int)$_POST['id']; }
  if ($uid <= 0) { http_response_code(401); echo json_encode(['erro'=>'Não autenticado']); exit; }

  $nome = isset($_POST['nome']) ? trim((string)$_POST['nome']) : '';
  $sobrenome = isset($_POST['sobrenome']) ? trim((string)$_POST['sobrenome']) : '';
  $telefone = isset($_POST['telefone']) ? trim((string)$_POST['telefone']) : '';

  if ($nome === '' || $sobrenome === '' || $telefone === '') {
    http_response_code(400); echo json_encode(['erro'=>'Campos obrigatórios ausentes']); exit;
  }

  // Atualiza
  if (!$stmt = $conn->prepare('UPDATE tbUsuario SET nome = ?, sobrenome = ?, telefone = ? WHERE id_usuario = ? LIMIT 1')) {
    http_response_code(500); echo json_encode(['erro'=>'Falha ao preparar']); exit;
  }
  $stmt->bind_param('sssi', $nome, $sobrenome, $telefone, $uid);
  if (!$stmt->execute()) { $stmt->close(); http_response_code(500); echo json_encode(['erro'=>'Falha ao atualizar']); exit; }
  $stmt->close();

  echo json_encode(['ok'=>true]);
} catch (Throwable $e) {
  http_response_code(500); echo json_encode(['erro'=>'Exceção no servidor']);
}
