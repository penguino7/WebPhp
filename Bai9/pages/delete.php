<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : -1;

if ($id >= 0) {
    deleteStudent($id);
}

header('Location: index.php?page=list');
exit;
