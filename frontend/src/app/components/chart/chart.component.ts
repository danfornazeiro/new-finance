import { Component, Input, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Chart, ChartConfiguration } from 'chart.js';
import { Transaction } from '../../models/transaction.model';

@Component({
  selector: 'app-chart',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="charts-container">
      <div class="chart-wrapper">
        <h3>Fluxo Mensal</h3>
        <canvas #balanceChart></canvas>
      </div>
      <div class="chart-wrapper">
        <h3>Tipos de Transações</h3>
        <canvas #typeChart></canvas>
      </div>
    </div>
  `,
  styles: [`
    .charts-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .chart-wrapper {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .chart-wrapper h3 {
      margin: 0 0 20px 0;
      color: #333;
      font-size: 16px;
      font-weight: 600;
    }

    canvas {
      max-height: 300px;
    }
  `]
})
export class ChartComponent implements OnInit {
  @Input() transactions: Transaction[] = [];
  private balanceChart: Chart | null = null;
  private typeChart: Chart | null = null;

  ngAfterViewInit(): void {
    this.initCharts();
  }

  ngOnInit(): void {
    setTimeout(() => this.initCharts(), 100);
  }

  initCharts(): void {
    this.createBalanceChart();
    this.createTypeChart();
  }

  private createBalanceChart(): void {
    const canvas = document.querySelector('#balanceChart') as HTMLCanvasElement;
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const monthlyData = this.getMonthlyData();
    
    const config: ChartConfiguration = {
      type: 'line',
      data: {
        labels: monthlyData.map(m => m.month),
        datasets: [
          {
            label: 'Entradas',
            data: monthlyData.map(m => m.incoming),
            borderColor: '#2ecc71',
            backgroundColor: 'rgba(46, 204, 113, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          },
          {
            label: 'Saídas',
            data: monthlyData.map(m => m.outgoing),
            borderColor: '#ff4757',
            backgroundColor: 'rgba(255, 71, 87, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: 'bottom'
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    };

    if (this.balanceChart) {
      this.balanceChart.destroy();
    }
    this.balanceChart = new Chart(ctx, config);
  }

  private createTypeChart(): void {
    const canvas = document.querySelector('#typeChart') as HTMLCanvasElement;
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const typeData = this.getTypeData();
    
    const config: ChartConfiguration = {
      type: 'doughnut',
      data: {
        labels: typeData.map(t => t.label),
        datasets: [
          {
            data: typeData.map(t => t.value),
            backgroundColor: [
              '#667eea',
              '#764ba2',
              '#f093fb',
              '#4facfe',
              '#00f2fe',
              '#2ecc71',
              '#ff4757'
            ]
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    };

    if (this.typeChart) {
      this.typeChart.destroy();
    }
    this.typeChart = new Chart(ctx, config);
  }

  private getMonthlyData(): any[] {
    const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    const data = months.map(month => ({
      month,
      incoming: Math.random() * 5000,
      outgoing: Math.random() * 3000
    }));
    return data;
  }

  private getTypeData(): any[] {
    const typeMap = new Map<string, number>();
    
    this.transactions.forEach(t => {
      const typeName = t.transaction_type?.name || 'Outro';
      typeMap.set(typeName, (typeMap.get(typeName) || 0) + 1);
    });

    return Array.from(typeMap.entries()).map(([label, value]) => ({
      label,
      value
    }));
  }
}
