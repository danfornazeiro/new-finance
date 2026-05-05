import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, BehaviorSubject } from 'rxjs';
import { Transaction, TransactionType, PaymentMethod, DashboardSummary } from '../models/transaction.model';

@Injectable({
  providedIn: 'root'
})
export class TransactionService {
  private apiUrl = 'http://localhost:8000/api';
  private transactionsSubject = new BehaviorSubject<Transaction[]>([]);
  public transactions$ = this.transactionsSubject.asObservable();

  constructor(private http: HttpClient) {}

  // Transaction Types
  getTransactionTypes(): Observable<TransactionType[]> {
    return this.http.get<TransactionType[]>(`${this.apiUrl}/transaction-types`);
  }

  createTransactionType(data: any): Observable<TransactionType> {
    return this.http.post<TransactionType>(`${this.apiUrl}/transaction-types`, data);
  }

  // Payment Methods
  getPaymentMethods(): Observable<PaymentMethod[]> {
    return this.http.get<PaymentMethod[]>(`${this.apiUrl}/payment-methods`);
  }

  createPaymentMethod(data: any): Observable<PaymentMethod> {
    return this.http.post<PaymentMethod>(`${this.apiUrl}/payment-methods`, data);
  }

  // Transactions
  getTransactions(): Observable<Transaction[]> {
    return this.http.get<Transaction[]>(`${this.apiUrl}/transactions`);
  }

  createTransaction(data: any): Observable<Transaction> {
    return this.http.post<Transaction>(`${this.apiUrl}/transactions`, data);
  }

  updateTransaction(id: number, data: any): Observable<Transaction> {
    return this.http.put<Transaction>(`${this.apiUrl}/transactions/${id}`, data);
  }

  deleteTransaction(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/transactions/${id}`);
  }

  // Dashboard Summary
  getDashboardSummary(): Observable<DashboardSummary> {
    return this.http.get<DashboardSummary>(`${this.apiUrl}/dashboard/summary`);
  }

  // Transactions by month
  getTransactionsByMonth(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/transactions/monthly`);
  }

  setTransactions(transactions: Transaction[]): void {
    this.transactionsSubject.next(transactions);
  }
}
