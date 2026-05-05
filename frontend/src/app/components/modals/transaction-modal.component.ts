import { Component, Input, Output, EventEmitter, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { TransactionService } from '../../services/transaction.service';
import { NotificationService } from '../../services/notification.service';
import { TransactionType, PaymentMethod } from '../../models/transaction.model';

@Component({
  selector: 'app-transaction-modal',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  template: `
    <div class="modal-overlay" (click)="this.onClose()">
      <div class="modal" (click)="$event.stopPropagation()">
        <div class="modal-header">
          <h2>Nova Transação</h2>
          <button class="btn-close" (click)="this.onClose()">✕</button>
        </div>
        <form [formGroup]="form" (ngSubmit)="this.onSubmit()" class="modal-body">
          <div class="form-group">
            <label>Tipo de Transação</label>
            <select formControlName="transaction_type_id" class="form-control">
              <option value="">Selecione um tipo</option>
              <option *ngFor="let type of transactionTypes" [value]="type.id">
                {{ type.name }} ({{ type.type === 'incoming' ? 'Entrada' : 'Saída' }})
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Forma de Pagamento</label>
            <select formControlName="payment_method_id" class="form-control">
              <option value="">Selecione uma forma</option>
              <option *ngFor="let method of paymentMethods" [value]="method.id">
                {{ method.name }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Valor</label>
            <input 
              type="number" 
              formControlName="amount" 
              placeholder="0.00"
              step="0.01"
              class="form-control"
            />
          </div>

          <div class="form-group">
            <label>Descrição</label>
            <textarea 
              formControlName="description"
              placeholder="Descrição da transação"
              class="form-control"
              rows="3"
            ></textarea>
          </div>

          <div class="form-group">
            <label>Data</label>
            <input 
              type="date" 
              formControlName="transaction_date"
              class="form-control"
            />
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-cancel" (click)="this.onClose()">Cancelar</button>
            <button type="submit" class="btn btn-save" [disabled]="form.invalid || isLoading">
              {{ isLoading ? 'Salvando...' : 'Salvar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  `,
  styles: [`
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal {
      background: white;
      border-radius: 10px;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      border-bottom: 1px solid #e0e0e0;
    }

    .modal-header h2 {
      margin: 0;
      font-size: 18px;
      color: #333;
    }

    .btn-close {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #999;
    }

    .modal-body {
      padding: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 600;
      font-size: 14px;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      border: 1px solid #e0e0e0;
      border-radius: 5px;
      font-size: 14px;
      box-sizing: border-box;
    }

    .form-control:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
    }

    .modal-footer {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      padding: 20px;
      border-top: 1px solid #e0e0e0;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-cancel {
      background: #f0f0f0;
      color: #333;
    }

    .btn-cancel:hover {
      background: #e0e0e0;
    }

    .btn-save {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .btn-save:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-save:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
  `]
})
export class TransactionModalComponent implements OnInit {
  @Input() transactionTypes: TransactionType[] = [];
  @Input() paymentMethods: PaymentMethod[] = [];
  @Output() close = new EventEmitter<void>();
  
  form!: FormGroup;
  isLoading = false;

  constructor(
    private formBuilder: FormBuilder,
    private transactionService: TransactionService,
    private notificationService: NotificationService
  ) {}

  ngOnInit(): void {
    this.initializeForm();
  }

  initializeForm(): void {
    const today = new Date().toISOString().split('T')[0];
    this.form = this.formBuilder.group({
      transaction_type_id: ['', Validators.required],
      payment_method_id: ['', Validators.required],
      amount: ['', [Validators.required, Validators.min(0.01)]],
      description: ['', Validators.required],
      transaction_date: [today, Validators.required]
    });
  }

  onSubmit(): void {
    if (this.form.valid) {
      this.isLoading = true;
      this.transactionService.createTransaction(this.form.value).subscribe({
        next: () => {
          this.notificationService.showSuccess('Transação criada com sucesso!');
          this.isLoading = false;
          this.close.emit();
        },
        error: () => {
          this.notificationService.showError('Erro ao criar transação');
          this.isLoading = false;
        }
      });
    }
  }

  onClose(): void {
    this.close.emit();
  }
}
