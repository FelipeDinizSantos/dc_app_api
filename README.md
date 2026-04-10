# API Laravel

## Pré-requisitos

Antes de iniciar, certifique-se de possuir instalado em seu ambiente:

* PHP (versão compatível com o projeto)
* Composer
* Node.js e NPM
* Banco de dados MySQL

---

## Instalação e Configuração

Siga os passos abaixo para configurar o ambiente local:

### 1. Instalar dependências do PHP

```bash
composer install
```

### 2. Criar arquivo de ambiente

```bash
cp .env.example .env
```

> Edite o arquivo `.env` e configure:

* Conexão com banco de dados
* Credenciais necessárias
* Outras variáveis específicas do ambiente

---

### 3. Gerar chave da aplicação

```bash
php artisan key:generate
```

---

### 4. Executar migrations

```bash
php artisan migrate
```

---

### 5. Instalar dependências do Node (opcional)

Embora o projeto não utilize frontend, recomenda-se executar:

```bash
npm install
```

---

## Execução do Projeto

Para iniciar o servidor local:

```bash
php artisan serve
```

A API estará disponível em:

```
http://localhost:8000
```

Para subir a API em uma porta diferente da padrão:

```bash
php artisan serve --port=8080