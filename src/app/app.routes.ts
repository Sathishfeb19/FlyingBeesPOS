import { Routes } from '@angular/router';
import { BeesDashboardComponent } from './bees-dashboard/bees-dashboard.component';
import { AddUserComponent } from './add-user/add-user.component';
import { ViewUserComponent } from './view-user/view-user.component';
import { BeesLoginComponent } from './bees-login/bees-login.component';
import { BeesProfileComponent } from './bees-profile/bees-profile.component';
import { BeesGeneralSettingComponent } from './bees-general-setting/bees-general-setting.component';

export const routes: Routes = [
  { path: '', component: BeesDashboardComponent, title: 'Dashbooard | Flying Bees' },
  { path: 'login', component: BeesLoginComponent, title: 'Login | Flying Bees' },
  { path: 'profile', component: BeesProfileComponent, title: 'My Profile | Flying Bees' },
  { path: 'user-profile', component: BeesGeneralSettingComponent, title: 'Setting | Flying Bees' },
  //User
  { path: 'add-user', component: AddUserComponent, title: 'Add User | Flying Bees' },
  { path: 'user/:id', component: AddUserComponent, title: 'Add User | Flying Bees' },
  { path: 'view-user', component: ViewUserComponent, title: 'View User | Flying Bees' },

  { path: '**', component: BeesLoginComponent, title: 'Login | Flying Bees' },

];
