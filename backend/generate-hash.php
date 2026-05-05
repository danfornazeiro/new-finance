<?php
// Script para gerar hash seguro para a senha de demo
// Gera hash para "123456"
$hash = password_hash('123456', PASSWORD_BCRYPT, ['cost' => 12]);
echo "Hash para senha '123456': " . $hash . "\n";
?>
