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

// Estoque e configurações
function gerenciar_estoque(&$produtos) {
    while (true) {
        limpar_tela();
        echo "\n----- Gerenciamento de Estoque -----\n";
        echo "1. Adicionar Produto\n";
        echo "2. Editar Produto\n";
        echo "3. Excluir Produto\n";
        echo "4. Visualizar Produtos\n";
        echo "5. Voltar\n";
        $opcao = ler_entrada("Escolha uma opção: ");

        switch ($opcao) {
            case '1':
                $nome = ler_entrada("Nome do Produto: ");
                $valor = ler_entrada("Valor do Produto: ");
                $produtos[] = ['id' => count($produtos) + 1, 'nome' => $nome, 'valor' => $valor];
                echo "Produto adicionado com sucesso!\n";
                break;

            case '2':
                
                $id = ler_entrada("ID do Produto a Editar: ");

                

                foreach ($produtos as &$produto) {
                    
                    if ($produto['id'] == $id) {
                        $produto['nome'] = ler_entrada("Novo Nome do Produto: ");
                        $produto['valor'] = ler_entrada("Novo Valor do Produto: ");
                        echo "Produto editado com sucesso!\n";
                        break;
                    }
                }
                break;

            case '3':
                $id = ler_entrada("ID do Produto a Excluir: ");
                foreach ($produtos as $index => $produto) {
                    if ($produto['id'] == $id) {
                        unset($produtos[$index]);
                        echo "Produto excluído com sucesso!\n";
                        break;
                    }
                }
                $produtos = array_values($produtos); 
                break;

            case '4':
                echo "\n----- Produtos em Estoque -----\n";
                foreach ($produtos as $produto) {
                    echo "ID: " . $produto['id'] . " | Nome: " . $produto['nome'] . " | Valor: R$" . $produto['valor'] . "\n";
                }
                ler_entrada("\nPressione Enter para continuar...");
                break;

            case '5':
                return;

            default:
                echo "Opção inválida. Tente novamente.\n";
        }
    }
}

// Criar Orçamentos
function criar_orcamento(&$produtos, &$orcamentos) {
    limpar_tela();
    $cliente_nome = ler_entrada("\nNome do Cliente: ");

    $orcamento = ['cliente' => $cliente_nome, 'itens' => [], 'data_hora' => date('Y-m-d H:i:s')];
    $valor_total = 0;

    while (true) {
        echo "\nProdutos disponíveis:\n";
        foreach ($produtos as $produto) {
            echo "ID: " . $produto['id'] . " | Nome: " . $produto['nome'] . " | Valor: R$" . $produto['valor'] . "\n";
        }

        $produto_id = ler_entrada("\nAdicionar Produto ao Orçamento (ID do Produto, 0 para finalizar): ");

        if ($produto_id == '0') {
            break;
        }

        foreach ($produtos as $produto) {
            if ($produto['id'] == $produto_id) {
                $quantidade = ler_entrada("Quantidade de {$produto['nome']}: ");
                $orcamento['itens'][] = ['produto' => $produto, 'quantidade' => $quantidade];
                $valor_total += $produto['valor'] * $quantidade;
                echo "Produto adicionado ao orçamento.\n";
                break;
            }
        }
    }

    $orcamento['valor_total'] = $valor_total;
    $orcamentos[] = $orcamento;
    echo "\nOrçamento criado com sucesso!\n";
    echo "Valor Total: R$" . $valor_total . "\n";
    ler_entrada("Pressione Enter para continuar...");
}

// Histórico de orçamentos
function mostrar_historico($orcamentos) {
    limpar_tela();
    echo "\n----- Histórico de Orçamentos -----\n";
    foreach ($orcamentos as $orcamento) {
        echo "Cliente: " . $orcamento['cliente'] . "\n";
        echo "Data/Hora: " . $orcamento['data_hora'] . "\n";
        foreach ($orcamento['itens'] as $item) {
            echo "- Produto: " . $item['produto']['nome'] . ", Quantidade: " . $item['quantidade'] . ", Valor Unitário: R$" . $item['produto']['valor'] . "\n";
        }
        echo "Valor Total do Orçamento: R$" . $orcamento['valor_total'] . "\n";
        echo "\n";
    }
    ler_entrada("Pressione Enter para continuar...");
}

// Inicio
echo "Bem-vindo ao Sistema de Orçamentos!\n";

if (!login($usuarios)) {
    exit();
}

while (true) {
    mostrar_menu();
    $opcao = ler_entrada("");

    switch ($opcao) {
        case '1':
            criar_orcamento($produtos, $orcamentos);
            break;
        case '2':
            gerenciar_estoque($produtos);
            break;
        case '3':
            mostrar_historico($orcamentos);
            break;
        case '4':
            echo "Saindo do sistema...\n";
            exit();
        default:
            echo "Opção inválida. Tente novamente.\n";
    }
}