import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Transaction } from '../../models/transaction.model';
import { TransactionService } from '../../services/transaction.service';
import { NotificationService } from '../../services/notification.service';

@Component({
  selector: 'app-transaction-list',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="transactions-wrapper">
      <h3>Últimas Transações</h3>
      <div *ngIf="transactions.length === 0" class="empty-state">
        <p>Nenhuma transação registrada</p>
      </div>
      <div *ngIf="transactions.length > 0" class="transactions-table">
        <table>
          <thead>
            <tr>
              <th>Data</th>
              <th>Descrição</th>
              <th>Tipo</th>
              <th>Forma de Pagamento</th>
              <th>Valor</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr *ngFor="let transaction of transactions" [ngClass]="getTransactionClass(transaction)">
              <td>{{ transaction.transaction_date | date: 'dd/MM/yyyy' }}</td>
              <td>{{ transaction.description }}</td>
              <td>{{ transaction.transaction_type?.name }}</td>
              <td>{{ transaction.payment_method?.name }}</td>
              <td class="amount">{{ transaction.amount | currency: 'BRL' }}</td>
              <td>
                <button class="btn-delete" (click)="deleteTransaction(transaction.id)">
                  🗑️ Deletar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  `,
  styles: [`
    .transactions-wrapper {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .transactions-wrapper h3 {
      margin: 0 0 20px 0;
      color: #333;
      font-size: 16px;
      font-weight: 600;
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #999;
    }

    .transactions-table {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead tr {
      border-bottom: 2px solid #e0e0e0;
    }

    th {
      padding: 12px;
      text-align: left;
      font-weight: 600;
      color: #666;
      font-size: 12px;
      text-transform: uppercase;
    }

    tbody tr {
      border-bottom: 1px solid #f0f0f0;
      transition: background 0.3s ease;
    }

    tbody tr:hover {
      background: #f9f9f9;
    }

    tbody tr.incoming td.amount {
      color: #2ecc71;
      font-weight: 600;
    }

    tbody tr.outgoing td.amount {
      color: #ff4757;
      font-weight: 600;
    }

    td {
      padding: 12px;
      font-size: 14px;
    }

    .btn-delete {
      background: #ff4757;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s ease;
    }

    .btn-delete:hover {
      background: #ff3838;
    }
  `]
})
export class TransactionListComponent {
  @Input() transactions: Transaction[] = [];
  @Output() onDelete = new EventEmitter<void>();

  constructor(
    private transactionService: TransactionService,
    private notificationService: NotificationService
  ) {}

  getTransactionClass(transaction: Transaction): string {
    return transaction.transaction_type?.type === 'incoming' ? 'incoming' : 'outgoing';
  }

  deleteTransaction(id: number): void {
    if (confirm('Tem certeza que deseja deletar esta transação?')) {
      this.transactionService.deleteTransaction(id).subscribe({
        next: () => {
          this.notificationService.showSuccess('Transação deletada com sucesso!');
          this.onDelete.emit();
        },
        error: () => {
          this.notificationService.showError('Erro ao deletar transação');
        }
      });
    }
  }
}
