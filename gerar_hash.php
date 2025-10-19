<?php
$senha_pura = 'senha123'; // Sua senha para o ADM
$hash_correto = password_hash($senha_pura, PASSWORD_BCRYPT);
echo "Senha Pura: " . $senha_pura . "\n";
echo "HASH GERADO: " . $hash_correto . "\n";
?>