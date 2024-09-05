<?php 

$usuarios = [
    'admin' => password_hash('1234', PASSWORD_DEFAULT)
];


$produtos = [];
$orcamentos = [];


// ler entradas
function ler_entrada($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

// Menu
function mostrar_menu() {
    limpar_tela();
    echo "\n----- Sistema de Orçamentos -----\n";
    echo "1. Orçamentos\n";
    echo "2. Estoque\n";
    echo "3. Histórico de Orçamentos\n";
    echo "4. Sair\n";
    echo "Escolha uma opção: ";
}