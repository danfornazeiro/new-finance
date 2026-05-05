import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TransactionService } from '../../services/transaction.service';
import { AuthService } from '../../services/auth.service';
import { ChartComponent } from '../../components/chart/chart.component';
import { HeaderComponent } from '../../components/header/header.component';
import { TransactionListComponent } from '../../components/transaction-list/transaction-list.component';
import { NotificationService } from '../../services/notification.service';
import { DashboardSummary, Transaction, TransactionType, PaymentMethod } from '../../models/transaction.model';
import { TransactionModalComponent } from '../../components/modals/transaction-modal.component';
import { PaymentMethodModalComponent } from '../../components/modals/payment-method-modal.component';
import { TransactionTypeModalComponent } from '../../components/modals/transaction-type-modal.component';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, ChartComponent, HeaderComponent, TransactionListComponent, TransactionModalComponent, PaymentMethodModalComponent, TransactionTypeModalComponent],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  summary: DashboardSummary | null = null;
  transactions: Transaction[] = [];
  transactionTypes: TransactionType[] = [];
  paymentMethods: PaymentMethod[] = [];
  isLoading = true;
  showTransactionModal = false;
  showPaymentMethodModal = false;
  showTransactionTypeModal = false;

  constructor(
    private transactionService: TransactionService,
    private authService: AuthService,
    private notificationService: NotificationService
  ) {}

  ngOnInit(): void {
    this.loadDashboardData();
  }

  loadDashboardData(): void {
    this.isLoading = true;
    
    Promise.all([
      this.transactionService.getDashboardSummary().toPromise(),
      this.transactionService.getTransactions().toPromise(),
      this.transactionService.getTransactionTypes().toPromise(),
      this.transactionService.getPaymentMethods().toPromise()
    ]).then(([summary, transactions, types, methods]) => {
      this.summary = summary || null;
      this.transactions = transactions || [];
      this.transactionTypes = types || [];
      this.paymentMethods = methods || [];
      this.isLoading = false;
    }).catch(error => {
      this.notificationService.showError('Erro ao carregar dados');
      this.isLoading = false;
    });
  }

  openTransactionModal(): void {
    this.showTransactionModal = true;
  }

  closeTransactionModal(): void {
    this.showTransactionModal = false;
    this.loadDashboardData();
  }

  openPaymentMethodModal(): void {
    this.showPaymentMethodModal = true;
  }

  closePaymentMethodModal(): void {
    this.showPaymentMethodModal = false;
    this.loadDashboardData();
  }

  openTransactionTypeModal(): void {
    this.showTransactionTypeModal = true;
  }

  closeTransactionTypeModal(): void {
    this.showTransactionTypeModal = false;
    this.loadDashboardData();
  }

  onTransactionDeleted(): void {
    this.loadDashboardData();
  }
}
