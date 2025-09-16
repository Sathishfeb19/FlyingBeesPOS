import { Routes } from '@angular/router';
import { BeesDashboardComponent } from './bees-dashboard/bees-dashboard.component';
import { AddUserComponent } from './add-user/add-user.component';
import { ViewUserComponent } from './view-user/view-user.component';

export const routes: Routes = [
  { path: '', component: BeesDashboardComponent, title: 'Dashbooard | Flying Bees' },

  //User
  { path: 'add-user', component: AddUserComponent, title: 'Add User | Flying Bees' },
  { path: 'user/:id', component: AddUserComponent, title: 'Add User | Flying Bees' },
  { path: 'view-user', component: ViewUserComponent, title: 'View User | Flying Bees' }
];
