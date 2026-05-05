# 🏗️ Arquitetura - Financeiro SAAS

## Fluxo de Autenticação

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER (Browser)                              │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
        ┌──────────────────────────┐
        │   LoginComponent          │
        │ (Email + Senha)          │
        └──────────────┬───────────┘
                       │
                       ▼
         ┌─────────────────────────────┐
         │   AuthService.login()       │
         │   HTTP POST /auth/login     │
         └──────────────┬──────────────┘
                        │
        ┌───────────────┴────────────────┐
        │                                 │
        ▼                                 ▼
    ┌────────────────────┐    ┌─────────────────────┐
    │  Backend PHP 8     │    │  MySQL Database     │
    ├────────────────────┤    ├─────────────────────┤
    │ AuthController     │    │  users table        │
    │  └─ AuthService    │    │  - id               │
    │     └─ UserModel   ├────┤  - email            │
    │   (bcrypt verify)  │    │  - password (hash)  │
    │   (JWT generate)   │    └─────────────────────┘
    └────────────────────┘
        │
        ▼ JWT Token + User Data
┌─────────────────────────────────┐
│   Client (localStorage)         │
│   - token (7 dias)             │
│   - user data                  │
└─────────────────────────────────┘
```

## Fluxo de Requisição com Autenticação

```
┌─────────────────────────────┐
│   Dashboard (Component)      │
│   getTransactions()         │
└────────────────┬────────────┘
                 │
                 ▼
    ┌────────────────────────────────┐
    │  TransactionService.getAll()   │
    │  HTTP GET /api/transactions    │
    │  Header: Authorization: Bearer │
    │  <token>                       │
    └────────────────┬───────────────┘
                     │
     ┌───────────────┴─────────────────┐
     │ AuthInterceptor                 │
     │ (Injetar token no header)       │
     └────────────┬────────────────────┘
                  │
                  ▼
     ┌────────────────────────────────┐
     │   Backend (Router)             │
     │   Validar JWT                  │
     └────────────┬───────────────────┘
                  │
    ┌─────────────┴──────────────┐
    │                             │
    ▼ Token Válido               ▼ Token Inválido
┌──────────────────────────┐  ┌──────────────────┐
│ TransactionController    │  │ Response Error   │
│  └─ getAll($userId)      │  │ Status 401       │
│     └─ TransactionModel  │  │ (Redirect login) │
└──────────────┬───────────┘  └──────────────────┘
               │
               ▼
        ┌─────────────────────────┐
        │   MySQL Database        │
        │   SELECT * FROM         │
        │   transactions WHERE    │
        │   user_id = ?           │
        └────────────┬────────────┘
                     │
                     ▼
        ┌──────────────────────────┐
        │   Response JSON          │
        │   {data: [...], ...}     │
        └────────────┬─────────────┘
                     │
                     ▼
        ┌──────────────────────────────┐
        │   Frontend (Service)         │
        │   Parse JSON                 │
        │   Emit transações$           │
        └────────────┬─────────────────┘
                     │
                     ▼
        ┌──────────────────────────────┐
        │   Dashboard (Component)      │
        │   Subscribe transações$      │
        │   Renderizar tabela          │
        └──────────────────────────────┘
```

## Arquitetura em Camadas - Backend

```
┌───────────────────────────────────────────────────────────┐
│                    HTTP Request                          │
└────────────────────────┬────────────────────────────────┘
                         │
                         ▼
         ┌──────────────────────────────────┐
         │    PUBLIC/INDEX.PHP (Router)    │
         │    - Parse URL                  │
         │    - Match route                │
         └────────────────┬─────────────────┘
                          │
                          ▼
        ┌────────────────────────────────────────┐
        │         CONTROLLERS (Presentation)     │
        │  ├─ AuthController                     │
        │  ├─ TransactionController              │
        │  ├─ TransactionTypeController          │
        │  └─ PaymentMethodController            │
        │  (Recebe requisição, retorna resposta) │
        └────────────┬─────────────────────────┘
                     │
                     ▼
        ┌────────────────────────────────────────┐
        │         SERVICES (Lógica de Negócio)  │
        │  ├─ AuthService                        │
        │  ├─ TransactionService                 │
        │  ├─ TransactionTypeService             │
        │  └─ PaymentMethodService               │
        │  (Validação, regras, processamento)    │
        └────────────┬────────────────────────┘
                     │
                     ▼
        ┌────────────────────────────────────────┐
        │         MODELS (Acesso a Dados)       │
        │  ├─ User                              │
        │  ├─ Transaction                       │
        │  ├─ TransactionType                   │
        │  └─ PaymentMethod                     │
        │  (CRUD, queries ao banco)             │
        └────────────┬────────────────────────┘
                     │
                     ▼
        ┌────────────────────────────────────────┐
        │         UTILS (Utilitários)           │
        │  ├─ Database (PDO conexão)            │
        │  ├─ JWT (gerar/validar tokens)        │
        │  ├─ Auth (bcrypt, getToken)           │
        │  └─ Response (JSON formatado)         │
        └────────────┬────────────────────────┘
                     │
                     ▼
        ┌────────────────────────────────────────┐
        │    MYSQL DATABASE (Persistência)      │
        │  ├─ users                             │
        │  ├─ transactions                      │
        │  ├─ transaction_types                 │
        │  └─ payment_methods                   │
        └────────────────────────────────────────┘
```

## Fluxo de Cadastro de Transação (Completo)

```
1. FRONTEND (Usuario preenche modal)
   ↓
2. TransactionModalComponent
   - Valida form
   ↓
3. TransactionService.create()
   - POST /api/transactions
   ↓
4. AuthInterceptor
   - Injeta token
   ↓
5. BACKEND - Router
   - Match POST /transactions
   ↓
6. TransactionController.store()
   - Recebe JSON
   - Chama service
   ↓
7. TransactionService.create()
   - Valida dados
   - Verifica tipo e forma
   ↓
8. TransactionModel.create()
   - INSERT query
   ↓
9. MySQL
   - Insere transação
   ↓
10. Response
    - JSON com nova transação
    ↓
11. FRONTEND
    - Service parse JSON
    - Component atualiza tabela
    - Notificação sucesso
```

## Segurança - Fluxo

```
┌─────────────────┐
│  Senha Usuario  │ (Texto plano)
└────────┬────────┘
         │
         ▼
    ┌──────────────┐
    │  Bcrypt      │
    │  (cost: 12)  │ → Hash (não reversível)
    └──────┬───────┘
           │
    ┌──────▼──────────────────────────┐
    │ Salva no MySQL (users.password) │
    │ $2y$12$k8pz7...                 │
    └─────────────────────────────────┘

    ↓ (Login)

┌──────────────────┐
│ Senha informada  │ (Texto plano)
│ + Hash do BD     │
│ password_verify()│ → Comparação
└────────┬─────────┘
         │
         ▼
    ┌─────────────────┐
    │  Se válido:     │
    │  Gera JWT       │
    │  com payload:   │
    │  {             │
    │    id,         │
    │    email,      │
    │    name,       │
    │    exp: +7d    │
    │  }             │
    └────────┬────────┘
             │
             ▼
    ┌──────────────────┐
    │  Retorna Token   │
    │  ao Cliente      │
    │  (localStorage)  │
    └──────────────────┘
```

## Integração Componentes

```
┌──────────────────────────────────────┐
│         AppComponent                 │
│         (router-outlet)              │
└────────────────┬─────────────────────┘
                 │
    ┌────────────┴────────────┐
    │                         │
    ▼                         ▼
┌────────────┐        ┌─────────────────┐
│LoginComponent        │DashboardComponent
│                      │  ├─ HeaderComponent
│                      │  ├─ ChartComponent
│                      │  ├─ TransactionList
│                      │  ├─ TransactionModal
│                      │  ├─ PaymentMethodModal
│                      │  └─ TypeModal
└────────────┘        └─────────────────┘
```

---

**Arquitetura Profissional em Camadas para Escalabilidade e Manutenção! 🚀**
