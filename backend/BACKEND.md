# Backend - PHP 8

## Estrutura

```
backend/
├── app/
│   ├── Controllers/      # Controladores REST
│   ├── Models/           # Modelos de dados
│   ├── Services/         # Lógica de negócios
│   └── Utils/            # Utilidades (DB, JWT, Auth)
├── config/               # Configurações
├── public/               # Entrada da aplicação (index.php)
└── composer.json
```

## Instalação

```bash
# 1. Configurar .env
cp .env.example .env

# 2. Editar .env com suas credenciais
nano .env

# 3. Criar banco de dados
mysql -u root < ../database/schema.sql

# 4. Gerar hash para demo (opcional)
php generate-hash.php

# 5. Iniciar servidor
php -S localhost:8000 -t public
```

## Autenticação

A autenticação é baseada em JWT. Fluxo:

1. Usuário faz login com email e senha
2. Backend valida e retorna token JWT
3. Cliente armazena token no localStorage
4. Cada requisição envia `Authorization: Bearer <token>`
5. Interceptor verifica validade do token

## Segurança

- Senhas hasheadas com bcrypt (cost: 12)
- JWT com expiração de 7 dias
- CORS configurado
- Validação de entrada em todos endpoints
- Proteção contra SQL Injection (prepared statements)

## Endpoints

Veja `../README.md` para lista completa de endpoints.

## Configuração

Arquivo `.env`:
```env
APP_ENV=development
APP_DEBUG=true

DB_HOST=localhost
DB_PORT=3306
DB_NAME=financeiro_db
DB_USER=root
DB_PASSWORD=

JWT_SECRET=seu_secret_key_muito_seguro_aqui_2024
```

## Troubleshooting

Se o banco de dados não conectar:
- Verifique se MySQL está rodando
- Confirme credenciais em `.env`
- Execute o schema.sql manualmente
