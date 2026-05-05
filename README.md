# 💰 Financeiro SAAS - Gestão Financeira Inteligente

Um SAAS moderno e completo para gestão financeira pessoal ou empresarial, desenvolvido com Angular 17+ no frontend e PHP 8 no backend.

## ✨ Funcionalidades

- ✅ Autenticação segura com JWT e hashing de senhas (bcrypt)
- ✅ Dashboard com gráficos modernos e cores vibrantes
- ✅ Controle de fluxo de entradas e saídas
- ✅ Cálculo de saldo total em tempo real
- ✅ Cadastro de tipos de recebimentos e pagamentos
- ✅ Cadastro de formas de pagamento/recebimento
- ✅ Registros de transações financeiras
- ✅ Interface responsiva e intuitiva
- ✅ Modais para cadastro rápido
- ✅ API RESTful com segurança

## 🛠️ Stack Tecnológico

### Frontend
- **Angular 17+** - Framework moderno
- **TypeScript** - Tipagem estática
- **Chart.js** - Gráficos interativos
- **CSS3** - Design responsivo com cores modernas

### Backend
- **PHP 8** - Versão recente e segura
- **MySQL** - Banco de dados relacional
- **JWT** - Autenticação segura
- **bcrypt** - Hash de senhas

## 📋 Pré-requisitos

- Node.js 18+
- PHP 8.0+
- MySQL 5.7+
- npm ou yarn

## 🚀 Instalação

### 1. Backend (PHP 8)

```bash
cd backend

# Configurar ambiente
cp .env.example .env

# Editar .env com suas credenciais do banco de dados
nano .env

# Criar banco de dados
mysql -u root < ../database/schema.sql

# Ou use o MySQL Workbench/phpMyAdmin para executar o schema.sql

# Iniciar servidor
php -S localhost:8000 -t public
```

**Dados de Demo:**
- Email: `demo@email.com`
- Senha: `123456`

### 2. Frontend (Angular 17+)

```bash
cd frontend

# Instalar dependências
npm install

# Iniciar servidor de desenvolvimento
npm start

# A aplicação estará disponível em http://localhost:4200
```

## 🔐 Segurança

- Senhas são hasheadas com bcrypt (cost: 12)
- JWT para autenticação stateless
- CORS configurado para localhost
- Headers de segurança implementados
- Validação de entrada em todos os endpoints
- Proteção contra SQL Injection

## 📁 Estrutura do Projeto

```
Financeiro/
├── frontend/                    # Angular 17+
│   ├── src/
│   │   ├── app/
│   │   │   ├── components/     # Componentes reutilizáveis
│   │   │   ├── services/       # Serviços HTTP
│   │   │   ├── models/         # Interfaces/Tipos
│   │   │   ├── pages/          # Páginas principais
│   │   │   ├── guards/         # Guards de autenticação
│   │   │   └── interceptors/   # Interceptadores HTTP
│   │   └── styles.css          # Estilos globais
│   └── package.json
│
├── backend/                     # PHP 8
│   ├── app/
│   │   ├── Controllers/        # Controladores
│   │   ├── Models/             # Modelos de dados
│   │   ├── Services/           # Lógica de negócios
│   │   ├── Middleware/         # Middlewares
│   │   └── Utils/              # Utilitários
│   ├── config/                 # Configurações
│   ├── public/                 # Entrada principal (index.php)
│   └── composer.json
│
└── database/
    └── schema.sql              # Script SQL
```

## 🔗 Endpoints da API

### Autenticação
- `POST /api/auth/register` - Registrar novo usuário
- `POST /api/auth/login` - Login

### Transações
- `GET /api/transactions` - Listar todas as transações
- `POST /api/transactions` - Criar nova transação
- `PUT /api/transactions/:id` - Atualizar transação
- `DELETE /api/transactions/:id` - Deletar transação

### Tipos de Transação
- `GET /api/transaction-types` - Listar tipos
- `POST /api/transaction-types` - Criar tipo
- `PUT /api/transaction-types/:id` - Atualizar tipo
- `DELETE /api/transaction-types/:id` - Deletar tipo

### Formas de Pagamento
- `GET /api/payment-methods` - Listar formas
- `POST /api/payment-methods` - Criar forma
- `PUT /api/payment-methods/:id` - Atualizar forma
- `DELETE /api/payment-methods/:id` - Deletar forma

### Dashboard
- `GET /api/dashboard/summary` - Resumo financeiro

## 🎨 Design & Cores

O SAAS utiliza um design moderno com cores vibrantes e tecnológicas:
- Gradiente Purple/Blue (#667eea → #764ba2)
- Verde para Entradas (#2ecc71)
- Vermelho para Saídas (#ff4757)
- Azul para Informações (#1e90ff)
- Fundo claro com destaques

## 📝 Notas Importantes

1. **Senha Demo**: Para usar os dados de demo, atualize a senha hasheada no schema.sql
   ```php
   // Para gerar hash de "123456":
   $hash = password_hash('123456', PASSWORD_BCRYPT, ['cost' => 12]);
   ```

2. **Configuração JWT**: Altere o JWT_SECRET no `.env` do backend para uma chave mais segura

3. **CORS**: Se precisar adicionar mais origens, atualize `config/config.php`

4. **Variáveis de Ambiente**: Crie `.env` no backend com suas configurações

## 🐛 Troubleshooting

### Erro de conexão com banco de dados
- Verifique se MySQL está rodando
- Confirme credenciais em `.env`
- Execute o schema.sql

### CORS errors
- Verifique se a origem está configurada em config.php
- Confirme headers da requisição

### Token inválido
- Verifique se o JWT_SECRET está igual em ambas chamadas
- Confirme se o token não expirou (7 dias)

## 📞 Suporte

Para dúvidas ou problemas, verifique:
1. Logs do console do navegador
2. Logs do servidor PHP (`php -S`)
3. Logs do MySQL

## 📄 Licença

Código aberto e sem direitos autorais.

---

**Desenvolvido com ❤️ usando Angular 17+ e PHP 8**
