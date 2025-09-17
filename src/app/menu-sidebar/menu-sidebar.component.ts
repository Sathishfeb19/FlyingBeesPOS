import { Component } from '@angular/core';
import { FlyingbeesService } from '../flyingbees.service';
import { HttpClient } from '@angular/common/http';
import { Router, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-menu-sidebar',
  standalone: true,
  imports: [RouterModule, CommonModule],
  templateUrl: './menu-sidebar.component.html',
  styleUrl: './menu-sidebar.component.scss'
})
export class MenuSidebarComponent {
  // searchQuery: string = '';
  // constructor(private http: HttpClient, private Bees: FlyingbeesService, private router: Router) { }
  // USER_TYPE: any;
  // userData: any = this.Bees.userData;
  // PERMISSIONS = JSON.parse(this.userData.PERMISSIONS || '{}');
  // ngOnInit(): void {
  //   this.USER_TYPE = this.userData.USER_TYPE;
  // }

}
