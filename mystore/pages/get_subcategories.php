<?php
require_once '../includes/functions.php';

if (!isset($_GET['parent_id'])) {
    echo json_encode([]);
    exit;
}

$parent_id = (int)$_GET['parent_id'];
$subcategories = getSubcategories($parent_id);

echo json_encode($subcategories);
?>
