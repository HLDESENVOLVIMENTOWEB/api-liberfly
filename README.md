# API de Autenticação Laravel

## 📌 Sobre o Projeto

Este projeto é uma API de autenticação robusta construída com Laravel, oferecendo funcionalidades de registro de usuário, login e gerenciamento de usuários. A API utiliza autenticação JWT (JSON Web Token) para garantir a segurança das operações.

## 🚀 Tecnologias Utilizadas

- [Laravel 10.x](https://laravel.com/)
- [PHP 8.1+](https://www.php.net/)
- [MySQL](https://www.mysql.com/)
- [JWT (JSON Web Tokens)](https://jwt.io/)
- [Swagger/OpenAPI](https://swagger.io/) para documentação da API

## 🛠 Configuração do Projeto

### Pré-requisitos

- PHP 8.1 ou superior
- Composer
- MySQL
- Node.js e NPM (para compilação de assets, se necessário)

### Passos para Configuração

1. Clone o repositório:
   ```
   git clone https://seu-repositorio.git
   cd nome-do-projeto
   ```

2. Instale as dependências do PHP:
   ```
   composer install
   ```

3. Copie o arquivo `.env.example` para `.env` e configure suas variáveis de ambiente:
   ```
   cp .env.example .env
   ```

4. Gere a chave da aplicação:
   ```
   php artisan key:generate
   ```

5. Configure o banco de dados no arquivo `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=api-liberfly
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Configure a chave JWT no arquivo `.env`:
   ```
   JWT_SECRET=your_secret_key
   ```

7. Execute as migrações do banco de dados:
   ```
   php artisan migrate
   ```

## 🏃‍♂️ Rodando o Projeto

1. Inicie o servidor de desenvolvimento:
   ```
   php artisan serve
   ```

2. O projeto estará disponível em `http://localhost:8000`

## 📚 Documentação da API (Swagger)

A documentação da API está disponível via Swagger UI. Para acessá-la:

1. Certifique-se de que o servidor está rodando
2. Acesse `http://localhost:8000/api/docs` em seu navegador

Para garantir que a documentação Swagger esteja sempre atualizada, configure as seguintes variáveis no seu arquivo `.env`:

```
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_UI_DOC_EXPANSION=list
L5_SWAGGER_CONST_HOST=http://localhost:8000
```

## 🔐 Rotas Protegidas

Todas as rotas, exceto `login` e `register`, são protegidas e requerem um token JWT válido. Para acessar essas rotas, inclua o token no cabeçalho da requisição:

```
Authorization: Bearer {seu_token_jwt}
```

## 🤝 Contribuindo

Contribuições são sempre bem-vindas! Sinta-se à vontade para abrir uma issue ou enviar um pull request.

## 📝 Licença

Este projeto está sob a licença [MIT](https://opensource.org/licenses/MIT).

---

Desenvolvido com ❤️ por Hyuri Miranda Cortes