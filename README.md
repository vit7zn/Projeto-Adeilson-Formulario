1. conexao.php
Este arquivo é o coração da comunicação com o banco de dados.

Propósito: Estabelecer a conexão com o servidor MySQL.

Detalhes: Define as constantes de conexão (HOST, USUARIO, SENHA, DB) e utiliza a função mysqli_connect para criar a variável $conexao. Qualquer outro arquivo que precise interagir com o banco de dados deve incluir este.

2. verifica_login.php
Este arquivo é um mecanismo de segurança para proteger páginas restritas.

Propósito: Garantir que o usuário esteja logado antes de acessar o conteúdo.

Detalhes: Verifica se a variável de sessão $_SESSION['email'] está definida. Se não estiver, o usuário é redirecionado imediatamente para a página de login (index.php).

3. index.php
Esta é a página inicial e a interface de login do sistema.

Propósito: Apresentar o formulário para autenticação de usuários.

Detalhes: Utiliza Bootstrap 5 para o layout, contém o formulário que envia os dados (email e senha) para o script login.php. Também oferece links para o cadastro (telacadastro.php) e recuperação de senha (forgot.html).

4. telacadastro.php
Esta é a interface de cadastro de novos usuários.

Propósito: Permitir que novos usuários se registrem no sistema.

Detalhes: Contém o formulário de registro (Nome, Email, Senha) que envia os dados para cadastro.php. Inclui a lógica para exibir mensagens de status (sucesso/erro/e-mail já cadastrado) armazenadas na sessão ($_SESSION['mensagem']).

5. cadastro.php
Este script é responsável por processar e salvar o registro de novos usuários no banco de dados.

Propósito: Inserir um novo usuário na tabela users.

Detalhes: Recebe os dados do telacadastro.php, verifica se o e-mail já existe, e se não, insere o novo registro no banco. A senha é armazenada usando a função de hash MD5(). Redireciona o usuário para a página de login (index.php) com uma mensagem de sucesso após o cadastro.

6. menu.php
Este é um componente reutilizável que define a barra de navegação.

Propósito: Definir o layout e os links de navegação principais (Home, Formulário, Gráficos).

Detalhes: É um arquivo HTML/PHP puro que utiliza Bootstrap 5 para estilizar a navbar. É incluído em outras páginas (como formulario.php) para manter a consistência do cabeçalho.

7. formulario.php
Esta é a página para cadastro de novos alunos no sistema.

Propósito: Coletar informações detalhadas de um aluno, endereço e dados do responsável.

Detalhes: Inclui o menu.php. Contém um formulário extenso que envia todos os dados via método POST para o script salvar_cadastro.php.

8. salvar_cadastro.php
Este script é o processador do formulário de aluno.

Propósito: Receber, sanitizar e salvar os dados de um novo aluno na tabela alunos_cadastrados.

Detalhes: Verifica se todos os campos obrigatórios foram preenchidos. Utiliza mysqli_real_escape_string para sanitizar os dados. Em caso de sucesso, exibe um resumo detalhado do cadastro em uma mensagem de alerta Bootstrap; em caso de falha, exibe o erro do MySQL.

9. painel.php
Esta é a página principal do sistema, o Dashboard.

Propósito: Exibir um painel de indicadores (KPIs) e gráficos para análise dos dados dos alunos.

Detalhes: Inclui verifica_login.php para acesso restrito. Contém uma série de consultas SQL para calcular totais (alunos, cursos, bairros, responsáveis) e dados para os gráficos. Usa Chart.js e PHP (json_encode) para renderizar visualizações interativas (Barras, Pizza, Linha) sobre cursos, responsáveis, bairros, idade e cadastros mensais.

10. logout.php
Este é o script responsável por encerrar a sessão do usuário.

Propósito: Deslogar o usuário do sistema.

Detalhes: Chama as funções session_start() e session_destroy() para remover todas as variáveis de sessão. Em seguida, redireciona o usuário para a página de login (index.php).

11.login.php
Este script é responsável por autenticar o usuário no sistema.

Propósito: Processar os dados de login (email e senha) enviados pelo formulário em index.php e verificar se as credenciais correspondem a um usuário registrado no banco de dados.

Detalhes:

Verificação Inicial: Checa se os campos de email e senha estão vazios. Se estiverem, o usuário é redirecionado para a página de login (index.php).

Segurança: Utiliza mysqli_real_escape_string para sanitizar as entradas, prevenindo injeção de SQL.

Consulta: Executa uma query SQL na tabela users, buscando por uma linha onde o user_email corresponda ao email fornecido e o user_password corresponda à senha fornecida, que também é criptografada usando MD5 para comparação.

Resultado:

Se uma linha for encontrada ($row == 1), o email é armazenado na variável de sessão $_SESSION['email'], concedendo acesso, e o usuário é redirecionado para o Painel (painel.php).

Se nenhuma linha for encontrada, a variável de sessão $_SESSION['nao_autenticado'] é definida como true, e o usuário é redirecionado de volta para a tela de login (index.php) para exibir uma mensagem de erro.
