import { Routes } from '@angular/router';
import { BeesDashboardComponent } from './bees-dashboard/bees-dashboard.component';
import { AddUserComponent } from './add-user/add-user.component';
import { ViewUserComponent } from './view-user/view-user.component';
import { BeesLoginComponent } from './bees-login/bees-login.component';
import { BeesProfileComponent } from './bees-profile/bees-profile.component';
import { BeesGeneralSettingComponent } from './bees-general-setting/bees-general-setting.component';
import { BeesErrorpageComponent } from './bees-errorpage/bees-errorpage.component';
import { AddWareHouseComponent } from './add-ware-house/add-ware-house.component';
import { ViewWareHouseComponent } from './view-ware-house/view-ware-house.component';
import { ReportStocklistComponent } from './report-stocklist/report-stocklist.component';
import { ReportFastMovingComponent } from './report-fast-moving/report-fast-moving.component';
import { ReportSlowMovingComponent } from './report-slow-moving/report-slow-moving.component';
import { ReportZeroMovingComponent } from './report-zero-moving/report-zero-moving.component';
import { ReportAgeStockComponent } from './report-age-stock/report-age-stock.component';
import { SalesTransferComponent } from './sales-transfer/sales-transfer.component';
import { SalesInvoiceComponent } from './sales-invoice/sales-invoice.component';
import { SalesPriceComponent } from './sales-price/sales-price.component';
import { SalesPersonComponent } from './sales-person/sales-person.component';

export const routes: Routes = [
  { path: '', component: BeesLoginComponent, title: 'Login | Flying Bees' },
  { path: 'login', component: BeesLoginComponent, title: 'Login | Flying Bees' },
  
  { path: 'dashboard', component: BeesDashboardComponent, title: 'Dashbooard | Flying Bees' },
  { path: 'profile', component: BeesProfileComponent, title: 'My Profile | Flying Bees' },
  { path: 'user-profile', component: BeesGeneralSettingComponent, title: 'Setting | Flying Bees' },
  { path: '404-page', component: BeesErrorpageComponent, title: 'Setting | Flying Bees' },
  //User
  { path: 'add-user', component: AddUserComponent, title: 'Add User | Flying Bees' },
  { path: 'user/:id', component: AddUserComponent, title: 'User Details | Flying Bees' },
  { path: 'view-user', component: ViewUserComponent, title: 'User List | Flying Bees' },
  //WareHouse
  { path: 'add-warehouse', component: AddWareHouseComponent, title: 'Add WareHouse | Flying Bees' },
  { path: 'add-warehouse/:id', component: AddWareHouseComponent, title: 'WareHouse Details | Flying Bees' },
  { path: 'view-warehouse', component: ViewWareHouseComponent, title: 'WareHouse List | Flying Bees' },
  // Stock Reports
  { path: 'godown-stock', component: ReportStocklistComponent, title: 'Godown Stock List | Flying Bees' },
  { path: 'fast-moving-report', component: ReportFastMovingComponent, title: 'Fast Moving Stock List | Flying Bees' },
  { path: 'slow-moving-report', component: ReportSlowMovingComponent, title: 'Slow Moving Stock List | Flying Bees' },
  { path: 'no-moving-report', component: ReportZeroMovingComponent, title: 'Zero Moving Stock List | Flying Bees' },
  { path: 'age-report', component: ReportAgeStockComponent, title: 'Age Stock List | Flying Bees' },
  //Sales Report
  { path: 'transfer-list', component: SalesTransferComponent, title: 'Stock Transfer List | Flying Bees' },
  { path: 'invoice-list', component: SalesInvoiceComponent, title: 'Sales Invoice List | Flying Bees' },
  { path: 'godown-report', component: SalesPriceComponent, title: 'Stock  List | Flying Bees' },
  { path: 'salesperson-report', component: SalesPersonComponent, title: 'Stock Transfer List | Flying Bees' },
  { path: '**', component: BeesLoginComponent, title: 'Login | Flying Bees' },

];
