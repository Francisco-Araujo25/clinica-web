<?php
include '../clinica/config/conexao.php';

/**
 * Exclusão de médico
 * Fluxo simples, seguro e previsível
 */

// O parâmetro deve ser 'id_medico' (igual ao link) não apenas 'id'
$id_medico = filter_input(INPUT_GET, 'id_medico', FILTER_VALIDATE_INT);

if (!$id_medico || $id_medico <= 0) {
    header('Location: listar_medicos.php?status=erro_id');
    exit;
}

/* Verifica se o médico existe */
$sql_busca = "SELECT id_medico, nome_medico FROM medicos WHERE id_medico = ?";
$stmt_busca = $conexao->prepare($sql_busca);

if (!$stmt_busca) {
    header('Location: listar_medicos.php?status=erro_prepare');
    exit;
}

$stmt_busca->bind_param("i", $id_medico);
$stmt_busca->execute();
$resultado = $stmt_busca->get_result();

if ($resultado->num_rows !== 1) {
    $stmt_busca->close();
    header('Location: listar_medicos.php?status=nao_encontrado');
    exit;
}
$stmt_busca->close();

/* Tenta excluir o médico */
$sql_delete = "DELETE FROM medicos WHERE id_medico = ?";
$stmt_delete = $conexao->prepare($sql_delete);

if (!$stmt_delete) {
    $conexao->close();
    header('Location: listar_medicos.php?status=erro_prepare');
    exit;
}

$stmt_delete->bind_param("i", $id_medico);

if ($stmt_delete->execute()) {
    // Sucesso
    $stmt_delete->close();
    $conexao->close();
    header('Location: listar_medicos.php?status=excluido');
    exit;
} else {
    // Se houver restrição por FK (consultas relacionadas), cai aqui
    $stmt_delete->close();
    $conexao->close();
    header('Location: listar_medicos.php?status=erro_fk');
    exit;
}
?>