import { Routes } from '@angular/router';
import { AuthGuard } from './auth.guard';
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
import { ReturnPendingComponent } from './return-pending/return-pending.component';
import { ReturnApprovedComponent } from './return-approved/return-approved.component';

export const routes: Routes = [
  { path: '', component: BeesLoginComponent, title: 'Login | Flying Bees', canActivate: [AuthGuard] },
  { path: 'login', component: BeesLoginComponent, title: 'Login | Flying Bees', canActivate: [AuthGuard] },

  { path: 'dashboard', component: BeesDashboardComponent, title: 'Dashbooard | Flying Bees', canActivate: [AuthGuard] },
  { path: 'profile', component: BeesProfileComponent, title: 'My Profile | Flying Bees', canActivate: [AuthGuard] },
  { path: 'user-profile', component: BeesGeneralSettingComponent, title: 'Setting | Flying Bees' },
  { path: '404-page', component: BeesErrorpageComponent, title: 'Setting | Flying Bees' },
  //User
  { path: 'add-user', component: AddUserComponent, title: 'Add User | Flying Bees', canActivate: [AuthGuard] },
  { path: 'user/:id', component: AddUserComponent, title: 'User Details | Flying Bees', canActivate: [AuthGuard] },
  { path: 'view-user', component: ViewUserComponent, title: 'User List | Flying Bees', canActivate: [AuthGuard] },
  //WareHouse
  { path: 'add-warehouse', component: AddWareHouseComponent, title: 'Add WareHouse | Flying Bees', canActivate: [AuthGuard] },
  { path: 'add-warehouse/:id', component: AddWareHouseComponent, title: 'WareHouse Details | Flying Bees', canActivate: [AuthGuard] },
  { path: 'view-warehouse', component: ViewWareHouseComponent, title: 'WareHouse List | Flying Bees', canActivate: [AuthGuard] },
  // Stock Reports
  { path: 'godown-stock', component: ReportStocklistComponent, title: 'Godown Stock List | Flying Bees', canActivate: [AuthGuard] },
  { path: 'fast-moving-report', component: ReportFastMovingComponent, title: 'Fast Moving Stock List | Flying Bees', canActivate: [AuthGuard] },
  { path: 'slow-moving-report', component: ReportSlowMovingComponent, title: 'Slow Moving Stock List | Flying Bees', canActivate: [AuthGuard] },
  { path: 'no-moving-report', component: ReportZeroMovingComponent, title: 'Zero Moving Stock List | Flying Bees', canActivate: [AuthGuard] },
  { path: 'age-report', component: ReportAgeStockComponent, title: 'Age Stock List | Flying Bees', canActivate: [AuthGuard] },
  //Sales Report
  { path: 'transfer-list', component: SalesTransferComponent, title: 'Stock Transfer List | Flying Bees', canActivate: [AuthGuard] },
  { path: 'invoice-list', component: SalesInvoiceComponent, title: 'Sales Invoice List | Flying Bees', canActivate: [AuthGuard] },
  { path: 'godown-report', component: SalesPriceComponent, title: 'Stock  List | Flying Bees', canActivate: [AuthGuard] },
  { path: 'salesperson-report', component: SalesPersonComponent, title: 'Stock Transfer List | Flying Bees', canActivate: [AuthGuard] },
  //Sales Return
  { path: 'view-returns', component: ReturnPendingComponent, title: 'Sales Return Pending List | Flying Bees', canActivate: [AuthGuard] },
  { path: 'return-update', component: ReturnApprovedComponent, title: 'Sales Return Approved List | Flying Bees', canActivate: [AuthGuard] },

  { path: '**', component: BeesErrorpageComponent, title: 'Error | Flying Bees' },
];
