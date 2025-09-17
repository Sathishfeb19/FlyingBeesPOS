import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import { FlyingbeesService } from '../flyingbees.service';

@Component({
  selector: 'app-bees-login',
  standalone: true,
  imports: [ReactiveFormsModule, FormsModule], // <- Add these
  templateUrl: './bees-login.component.html',
  styleUrl: './bees-login.component.scss'
})
export class BeesLoginComponent {
LoginFrm: FormGroup | any;
  error = '';
  constructor(private Bees: FlyingbeesService, private router: Router, private Cookies: CookieService, private http: HttpClient) { }
  ngOnInit(): void {
    this.LoginFrm = new FormGroup(
      {
        email: new FormControl('', Validators.required),
        password: new FormControl('', Validators.required)
      }
    );
  }
  LoginMyFrm() {
    this.LoginFrm.markAllAsTouched();
    if (this.LoginFrm.valid) {
      this.Bees.LoginCheck(this.LoginFrm.get('email').value, this.LoginFrm.get('password').value).subscribe((response: any) => {
        if (response.status == 'error') {
          this.router.navigate(['/login']);
        }
        if (response.status == 'ok') {
          this.error = '';
          this.Cookies.set('token', response.token);
          this.Bees.settoken(response);
          this.router.navigate(['/dashboard']);
        }
      });
    }
  }
}
