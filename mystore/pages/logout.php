<?php
require_once '../includes/functions.php';

session_start();

logoutUser();

header('Location: /mystore/pages/products.php');
exit;
