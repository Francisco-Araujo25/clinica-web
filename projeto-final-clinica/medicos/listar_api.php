
<?php
header('Content-Type: application/json; charset=utf-8');
include '/../config/conexao.php';
$rows = [];
if ($res = $conexao->query("SELECT id_medico, nome_medico FROM medicos ORDER BY nome_medico ASC")) {
  while ($r = $res->fetch_assoc()) $rows[] = $r;
  $res->free();
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);
?>