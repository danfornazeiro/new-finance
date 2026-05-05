<!-- README para Angular Frontend -->
# Financeiro Frontend - Angular 17+

## Inicializar o Projeto

```bash
# Instalar dependências
npm install

# Iniciar servidor de desenvolvimento
npm start

# Compilar para produção
npm run build
```

## Arquitetura

O frontend está estruturado em:
- **components/** - Componentes reutilizáveis (Header, Chart, Modais)
- **pages/** - Páginas principais (Login, Dashboard)
- **services/** - Serviços HTTP e de negócio
- **models/** - Interfaces TypeScript
- **guards/** - Proteção de rotas
- **interceptors/** - Interceptadores HTTP para autorização

## Componentes Principais

### LoginComponent
Tela de login segura com:
- Validação de email
- Visualização de senha
- Feedback visual

### DashboardComponent
Dashboard completo com:
- Cards de resumo (Saldo, Entradas, Saídas)
- Gráficos interativos
- Lista de transações recentes
- Botões para criar registros

### Modais
- TransactionModalComponent - Cadastro de transações
- PaymentMethodModalComponent - Cadastro de formas de pagamento
- TransactionTypeModalComponent - Cadastro de tipos de transação

## Configuração do Backend

Atualize a URL da API em `src/app/services/auth.service.ts` e `src/app/services/transaction.service.ts`:

```typescript
private apiUrl = 'http://localhost:8000/api';
```

Mude para sua URL de produção quando necessário.
