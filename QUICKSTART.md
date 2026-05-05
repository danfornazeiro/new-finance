# 🚀 Guia Rápido - Financeiro SAAS

## ⚡ Inicializar em 5 Minutos

### Terminal 1 - Backend PHP

```bash
cd backend

# Configurar banco de dados
mysql -u root < ../database/schema.sql

# Configurar variáveis de ambiente
cp .env.example .env

# Iniciar servidor
php -S localhost:8000 -t public
```

✅ Backend rodando em: `http://localhost:8000/api`

### Terminal 2 - Frontend Angular

```bash
cd frontend

# Instalar dependências (primeira vez)
npm install

# Iniciar desenvolvimento
npm start
```

✅ Frontend rodando em: `http://localhost:4200`

## 🔓 Login de Teste

```
Email: demo@email.com
Senha: 123456
```

## 📱 Funcionalidades Principais

### Dashboard
- 💰 Saldo total atualizado
- 📊 Gráficos de fluxo mensal
- 📈 Estatísticas de tipos de transação
- 📋 Lista de últimas transações

### Cadastros
- ➕ **Nova Transação** - Registrar entrada/saída
- 🏷️ **Novo Tipo** - Criar categorias (Salário, Aluguel, etc)
- 💳 **Forma de Pagamento** - PIX, Cartão, Dinheiro, etc

### Transações
- ✏️ Editar transações
- 🗑️ Deletar transações
- 📅 Filtrar por data
- 🔍 Buscar por descrição

## 🎨 Cores & Design

```css
Gradiente Principal: #667eea → #764ba2
Entradas (Verde):   #2ecc71
Saídas (Vermelho):  #ff4757
Info (Azul):        #1e90ff
```

## 🔐 Segurança

✅ Senhas hasheadas com bcrypt
✅ Autenticação JWT (7 dias)
✅ CORS configurado
✅ Proteção contra SQL Injection
✅ Headers de segurança

## 📚 Documentação Completa

- [README.md](../README.md) - Documentação principal
- [FRONTEND.md](../frontend/FRONTEND.md) - Guia Frontend
- [BACKEND.md](../backend/BACKEND.md) - Guia Backend

## 🆘 Problemas Comuns

### "Erro de conexão com banco de dados"
```bash
# Verifique se MySQL está rodando
mysql -u root -p

# Se não conectar, revise as credenciais em backend/.env
```

### "CORS error no navegador"
```bash
# Frontend tenta conectar no backend
# Verifique se ambos os servidores estão rodando
# Frontend: localhost:4200
# Backend: localhost:8000
```

### "Token inválido"
```bash
# Limpe o localStorage e faça login novamente
# Se persistir, verifique se JWT_SECRET está igual
```

## 📦 Estrutura Rápida

```
Financeiro/
├── frontend/          # Angular 17+
├── backend/           # PHP 8
├── database/          # Schema SQL
└── README.md
```

## 🎯 Próximos Passos

1. ✅ Instalar dependências
2. ✅ Configurar banco de dados
3. ✅ Iniciar backend e frontend
4. ✅ Acessar http://localhost:4200
5. ✅ Fazer login com demo@email.com / 123456
6. ✅ Explorar o dashboard!

---

**Bom desenvolvimento! 🚀**
