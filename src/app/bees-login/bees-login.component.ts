import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import { FlyingbeesService } from '../flyingbees.service';
import Swal from 'sweetalert2';

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
    this.Bees.LoginValidation(
      this.LoginFrm.get('email').value,
      this.LoginFrm.get('password').value
    ).subscribe((response: any) => {
      if (response.status === 'error') {
        Swal.fire({
          icon: 'error',
          title: 'Login Failed',
          text: 'Mobile number or password is incorrect. Please try again.',
          confirmButtonText: 'OK'
        });
      }

      if (response.status === 'ok') {
        this.error = '';
        this.Cookies.set('token', response.token);
        this.Bees.settoken(response);

        Swal.fire({
          icon: 'success',
          title: 'Login Successful',
          text: 'Redirecting to Dashboard...',
          timer: 2000,
          showConfirmButton: false,
          didClose: () => {
            this.router.navigate(['dashboard']);
          }
        });
      }
    });
  }
}
}
