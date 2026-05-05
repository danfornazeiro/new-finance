import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { LoginRequest, LoginResponse, User, AuthState } from '../models/auth.model';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost:8000/api';
  private authState = new BehaviorSubject<AuthState>({
    token: this.getToken(),
    user: this.getUser(),
    isAuthenticated: !!this.getToken()
  });

  public authState$ = this.authState.asObservable();

  constructor(private http: HttpClient) {
    this.restoreAuthState();
  }

  login(credentials: LoginRequest): Observable<LoginResponse> {
    return this.http.post<LoginResponse>(`${this.apiUrl}/auth/login`, credentials).pipe(
      tap(response => {
        this.setToken(response.token);
        this.setUser(response.user);
        this.updateAuthState();
      })
    );
  }

  register(userData: any): Observable<LoginResponse> {
    return this.http.post<LoginResponse>(`${this.apiUrl}/auth/register`, userData).pipe(
      tap(response => {
        this.setToken(response.token);
        this.setUser(response.user);
        this.updateAuthState();
      })
    );
  }

  logout(): void {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.updateAuthState();
  }

  isAuthenticated(): boolean {
    return !!this.getToken();
  }

  getToken(): string | null {
    return localStorage.getItem('token');
  }

  getCurrentUser(): User | null {
    return this.getUser();
  }

  private setToken(token: string): void {
    localStorage.setItem('token', token);
  }

  private setUser(user: User): void {
    localStorage.setItem('user', JSON.stringify(user));
  }

  private getUser(): User | null {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  }

  private updateAuthState(): void {
    this.authState.next({
      token: this.getToken(),
      user: this.getUser(),
      isAuthenticated: !!this.getToken()
    });
  }

  private restoreAuthState(): void {
    if (this.getToken()) {
      this.updateAuthState();
    }
  }
}
