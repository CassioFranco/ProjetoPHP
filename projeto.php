<?php 

$usuarios = [
    'admin' => password_hash('1234', PASSWORD_DEFAULT)
];


$produtos = [];
$orcamentos = [];

// impar o terminal
function limpar_tela() {
    if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
        system('cls');
    } else {
        system('clear');
    }
}


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

// Registrar novo usuário
function registrar_usuario(&$usuarios) {
    limpar_tela();
    echo "----- Registro de Novo Usuário -----\n";
    $novo_usuario = ler_entrada("Novo Usuário: ");
    $nova_senha = ler_entrada("Nova Senha: ");

    if (!isset($usuarios[$novo_usuario])) {
        $usuarios[$novo_usuario] = password_hash($nova_senha, PASSWORD_DEFAULT);
        echo "\nUsuário registrado com sucesso! Redirecionando para o menu...\n";
        sleep(2); 
        return true; 
    } else {
        echo "\nUsuário já existe.\n";
        return false;
    }
}

// Login / regitrar caso não tenha cadastro
function login(&$usuarios) {
    limpar_tela();
    $username = ler_entrada("Usuário: ");

    if (!isset($usuarios[$username])) {
        $resposta = ler_entrada("\nUsuário não encontrado. Deseja se registrar? (s/n): ");

        if (strtolower($resposta) === 's') {
            if (registrar_usuario($usuarios)) {
                return true;
            } else {
                return false;
            }
        } else {
            echo "\nLogin cancelado.\n";
            return false;
        }
    }

    $password = ler_entrada("Senha: ");

    if (isset($usuarios[$username]) && password_verify($password, $usuarios[$username])) {
        echo "\nLogin bem-sucedido!\n";
        return true;
    } else {
        echo "\nLogin ou senha inválidos.\n";
        return false;
    }
}