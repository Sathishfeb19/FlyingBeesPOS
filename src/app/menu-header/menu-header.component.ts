import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FlyingbeesService } from '../flyingbees.service';

@Component({
  selector: 'app-menu-header',
  standalone: true,
  imports: [],
  templateUrl: './menu-header.component.html',
  styleUrls: ['./menu-header.component.scss']
})
export class MenuHeaderComponent implements OnInit {
  USERNAME: any;
  TYPE_NAME: any;
  INSERTED_URL: any;
  WH_NAME: any;
  userData: any;

  constructor(
    private http: HttpClient,
    private Bees: FlyingbeesService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.userData = this.Bees.userData;
    this.USERNAME = this.userData.USER_NAME;
    this.TYPE_NAME = this.userData.TYPE_NAME;
    this.WH_NAME = this.userData.WH_NAME;
    this.INSERTED_URL = this.userData.INSERTED_URL;
  }

  UserLogOut() {
    this.Bees.User_LogOut();
    this.router.navigate(['/login']);
  }
}
