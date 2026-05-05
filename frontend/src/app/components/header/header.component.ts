import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule],
  template: `
    <header class="header">
      <div class="header-container">
        <div class="logo">
          <h2>💰 Financeiro</h2>
        </div>
        <nav class="nav">
          <button class="nav-link" (click)="goToDashboard()">Dashboard</button>
          <button class="nav-link logout" (click)="logout()">Sair</button>
        </nav>
      </div>
    </header>
  `,
  styles: [`
    .header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 15px 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .header-container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo h2 {
      margin: 0;
      font-size: 24px;
    }

    .nav {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    .nav-link {
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 5px;
    }

    .nav-link:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .nav-link.logout {
      background: rgba(255, 71, 87, 0.3);
    }

    .nav-link.logout:hover {
      background: rgba(255, 71, 87, 0.5);
    }
  `]
})
export class HeaderComponent {
  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  goToDashboard(): void {
    this.router.navigate(['/dashboard']);
  }

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}
