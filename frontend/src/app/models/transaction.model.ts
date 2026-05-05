export interface TransactionType {
  id: number;
  name: string;
  type: 'incoming' | 'outgoing';
  user_id: number;
  created_at: Date;
}

export interface PaymentMethod {
  id: number;
  name: string;
  user_id: number;
  created_at: Date;
}

export interface Transaction {
  id: number;
  user_id: number;
  transaction_type_id: number;
  payment_method_id: number;
  amount: number;
  description: string;
  transaction_date: Date;
  transaction_type?: TransactionType;
  payment_method?: PaymentMethod;
  created_at: Date;
  updated_at: Date;
}

export interface DashboardSummary {
  total_incoming: number;
  total_outgoing: number;
  balance: number;
  transactions_count: number;
}
