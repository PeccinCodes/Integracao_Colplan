# Template de Site para Desenvolvimento de Aplicações

Este repositório contém um template de site projetado para servir como base para o desenvolvimento de futuras aplicações web. Este documento README fornece uma visão geral do projeto, seu propósito e como ele deve ser usado para manter um padrão de layout em todas as aplicações.

## Propósito

O objetivo principal deste template é estabelecer um padrão de layout consistente para todas as aplicações desenvolvidas na área. Ele fornece um conjunto de recursos e componentes pré-construídos que podem ser facilmente utilizados para criar novas aplicações e atualizar aquelas que já estão em funcionamento. Isso ajuda a manter uma identidade visual unificada e a garantir uma experiência do usuário coerente em todas as aplicações.

## Estrutura do Projeto

O projeto está organizado da seguinte maneira:

/TEMPLATEPECCIN <br>
├── index.php <br> 
├── .gitignore <br>
├── .htaccess <br>
├── config.php <br>
├── Readme <br>
│ ├── src/ <br>
│ │ ├── css/ <br>
| | |  ├── ... <br> 
│ │ ├── db/ <br>
│ │ ├── img/ <br>
| | |  ├── logo.png
│ │ ├── js/ <br>
| | |  ├── ...<br>
│ │ ├── pages/ <br>
| | |  ├── ...<br>
│ │ ├── partials/ <br>
| | |  ├── header.php <br>
| | |  ├── footer.php <br>
│ │ ├── php/ <br>
| | |  ├── ... <br>
| | |  ├── controller/ <br>
| | |  |  ├── ... <br>
| | |  ├── model/ <br>
| | |  |  ├── ... <br>



- `index.php`: A página inicial do template que serve como exemplo de como utilizar os componentes e estilos com um backgound default.

- `src/`:  Diretório padrão onde ficam armazenados os arquivos PHP fonte (source) que compõem o código principal da aplicação, incluindo classes, funções e outros componentes necessários para o funcionamento da aplicação."

- `css/`: Diretório que contém os arquivos CSS, incluindo um arquivo principal `main.css` e quaisquer estilos específicos para os componentes.(Bootstrap-V5.0)

- `img/`: Diretório para armazenar imagens e recursos gráficos que podem ser utilizados nas aplicações.

- `js/`: Diretório que contém os arquivos JS, incluindo um arquivo principal `main.js` e quaisquer estilos específicos para os componentes.(Bootstrap-V5.0)

- `pages/`: Diretório que contém as páginas de interação do usuário.

- `partials/`: Diretório que contém os componentes reutilizáveis, como cabeçalhos, rodapés, barras de navegação, formulários, etc.

- `php/`: Diretório que contém os componentes reutilizáveis, como cabeçalhos, rodapés, barras de navegação, formulários, etc.

- `model/`: Diretório que armazena arquivos PHP que definem a lógica de acesso a dados (Model) e o ViewModel é responsável por intermediar a comunicação entre o Model e a camada de apresentação (View)."

- `controller/`: Diretório que contém os arquivos PHP responsáveis por receber e processar solicitações, gerenciar a lógica de negócios e interagir com o modelo de dados da aplicação.


## Como Utilizar

Para criar uma nova aplicação ou atualizar uma aplicação existente com base neste template, siga os seguintes passos:

1. Clone ou faça o download deste repositório.

2. Copie os arquivos e diretórios necessários do template para o seu projeto. Isso inclui os arquivos PHP, CSS, JavaScript e quaisquer imagens ou componentes específicos que você deseja utilizar.

3. Personalize os arquivos conforme necessário para atender aos requisitos da sua aplicação.

4. Lembre-se de manter a estrutura de diretórios e seguir as convenções de nomenclatura para garantir a consistência visual em todas as aplicações.

5. Consulte o supervisor da área para validar se as alterações atendem aos requisitos e padrões de layout estabelecidos.

## Contribuição

Se você tiver sugestões de melhorias ou adições ao template, sinta-se à vontade para criar uma "issue" ou enviar uma solicitação de "pull request" para este repositório. Suas contribuições são bem-vindas e ajudarão a aprimorar o template para uso futuro.

## Contato

Para mais informações ou dúvidas, entre em contato com a equipe de desenvolvimento da área.
