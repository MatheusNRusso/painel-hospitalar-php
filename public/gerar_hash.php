<?php
$senha = 'myprojeto@2025'; // Senha em texto puro
$senhaHash = password_hash($senha, PASSWORD_DEFAULT); // Gera o hash da senha

echo "Senha hash: " . $senhaHash;
?>