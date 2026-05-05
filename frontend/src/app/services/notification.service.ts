import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {
  private notifications: any[] = [];

  showSuccess(message: string): void {
    console.log('✓ Success:', message);
    this.addNotification('success', message);
  }

  showError(message: string): void {
    console.error('✗ Error:', message);
    this.addNotification('error', message);
  }

  showInfo(message: string): void {
    console.log('ℹ Info:', message);
    this.addNotification('info', message);
  }

  showWarning(message: string): void {
    console.warn('⚠ Warning:', message);
    this.addNotification('warning', message);
  }

  private addNotification(type: string, message: string): void {
    const notification = { type, message, id: Date.now() };
    this.notifications.push(notification);
    
    setTimeout(() => {
      this.notifications = this.notifications.filter(n => n.id !== notification.id);
    }, 5000);
  }
}
