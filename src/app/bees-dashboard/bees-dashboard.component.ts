import { Component, OnInit } from '@angular/core';
import { MenuHeaderComponent } from "../menu-header/menu-header.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { FlyingbeesService } from '../flyingbees.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-bees-dashboard',
  standalone: true,
  imports: [MenuHeaderComponent, MenuSidebarComponent, MenuFooterComponent, CommonModule],
  templateUrl: './bees-dashboard.component.html',
  styleUrls: ['./bees-dashboard.component.scss']
})
export class BeesDashboardComponent implements OnInit {

  // DASHBOARD DETAILS
  TopSalesList: any;
  TopXXXSalesList: any;
  TopVIDSalesList: any;
  TOT_TRASFER: any;
  TODAY_SALES: any;
  MAINWAREHOUSE: any;
  SUBWAREHOUSE: any;
  SUBWAREHOUSEQTY: any;
  LAST_WEEK: any;
  YESTERDAY_SALES: any;
  LAST_MONTH: any;
  SalesList7: any;
  SalesList30: any;
  MAINWAREHOUSEQTY: any;
  TOT_TRASFERQTY: any;
  LAST_MONTHQTY: any;
  LAST_WEEKQTY: any;
  TODAY_SALESQTY: any;
  YESTERDAY_SALESQTY: any;
  LAST_IIIDAYS: any;
  LAST_XVDAYS: any;
  LAST_XXXXVDAYS: any;
  LAST_IIIDAYSQTY: any;
  LAST_XVDAYSQTY: any;
  LAST_XXXXVDAYSQTY: any;
  WH_MONTHQTY: any;
  WH_MONTH: any;
  WH_WEEKQTY: any;
  WH_WEEK: any;
  WHTODAY_SALES: any;
  WHTODAY_SALESQTY: any;
  WHYESTERDAY_SALES: any;
  WHYESTERDAY_SALESQTY: any;
  WHLAST_IIIDAYS: any;
  WHLAST_IIIDAYSQTY: any;
  WHLAST_XVDAYS: any;
  WHLAST_XVDAYSQTY: any;
  WHLAST_XXXXVDAYS: any;
  WHLAST_XXXXVDAYSQTY: any;
  WH_TRASFER: any;
  WH_TRASFERQTY: any;

  // USER DETAILS
  userData: any;
  USERNAME: any;
  WH_ID: any;

  // Chart toggles
  chart1 = false;
  chart2 = false;

  constructor(
    private http: HttpClient,
    private Bees: FlyingbeesService,
    private router: Router
  ) { }

  ngOnInit(): void {
    // ✅ Now it's safe to access Bees service
    this.userData = this.Bees.userData;

    if (this.userData) {
      this.USERNAME = this.userData.USER_NAME;  // ⚠️ your backend uses USER_NAME not USERNAME
      this.WH_ID = this.userData.WH_ID;
    }

    // Load dashboard data
    this.Bees.View_VIISalesList(this.WH_ID).subscribe((resp: any) => {
      this.SalesList7 = resp;
      // this.barChartData1.labels = resp.map((e: any) => e.LOCATION);
      // this.barChartData1.datasets[0].data = resp.map((e: any) => e.SAL_AMOUNT);
      // this.chart1 = true;
    });

    this.Bees.View_XXXSalesList(this.WH_ID).subscribe((resp: any) => {
      this.SalesList30 = resp;
      // this.barChartData2.labels = resp.map((e: any) => e.LOCATION);
      // this.barChartData2.datasets[0].data = resp.map((e: any) => e.SAL_AMOUNT);
      // this.chart2 = true;
    });

    this.Bees.View_TopVIISalesList(this.WH_ID).subscribe((resp: any) => {
      this.TopSalesList = resp;
    });

    this.Bees.View_TopXXXSalesList(this.WH_ID).subscribe((resp: any) => {
      this.TopXXXSalesList = resp;
    });

    this.Bees.View_TopVIDSalesList(this.WH_ID).subscribe((resp: any) => {
      this.TopVIDSalesList = resp;
    });


      this.Bees.ViewUserDashBoard(this.WH_ID).subscribe((data: any) => {
      //SUPER ADMIN
      //MAIN
      this.LAST_XXXXVDAYS = data.LAST_XXXXVDAYS;
      this.LAST_XXXXVDAYSQTY = data.LAST_XXXXVDAYSQTY;

      this.LAST_IIIDAYS = data.LAST_IIIDAYS;
      this.LAST_IIIDAYSQTY = data.LAST_IIIDAYSQTY;

      this.LAST_XVDAYS = data.LAST_XVDAYS;
      this.LAST_XVDAYSQTY = data.LAST_XVDAYSQTY;

      this.TODAY_SALES = data.TODAY_SALES;
      this.TODAY_SALESQTY = data.TODAY_SALESQTY;

      this.YESTERDAY_SALES = data.YESTERDAY_SALES;
      this.YESTERDAY_SALESQTY = data.YESTERDAY_SALESQTY;

      this.LAST_WEEK = data.LAST_WEEK;
      this.LAST_WEEKQTY = data.LAST_WEEKQTY;

      this.LAST_MONTH = data.LAST_MONTH;
      this.LAST_MONTHQTY = data.LAST_MONTHQTY;

      this.MAINWAREHOUSE = data.MAINWAREHOUSE;
      this.MAINWAREHOUSEQTY = data.MAINWAREHOUSEQTY;

      this.SUBWAREHOUSE = data.SUBWAREHOUSE;
      this.SUBWAREHOUSEQTY = data.SUBWAREHOUSEQTY;

      this.TOT_TRASFER = data.TOT_TRASFER;
      this.TOT_TRASFERQTY = data.TOT_TRASFERQTY;

      //WAREHOUSE
      this.WH_TRASFER = data.WH_TRASFER;
      this.WH_TRASFERQTY = data.WH_TRASFERQTY;

      this.WH_MONTH = data.WH_MONTH;
      this.WH_MONTHQTY = data.WH_MONTHQTY;

      this.WH_WEEK = data.WH_WEEK;
      this.WH_WEEKQTY = data.WH_WEEKQTY;

      this.WHTODAY_SALES = data.WHTODAY_SALES;
      this.WHTODAY_SALESQTY = data.WHTODAY_SALESQTY;

      this.WHYESTERDAY_SALES = data.WHYESTERDAY_SALES;
      this.WHYESTERDAY_SALESQTY = data.WHYESTERDAY_SALESQTY;

      this.WHLAST_IIIDAYS = data.WHLAST_IIIDAYS;
      this.WHLAST_IIIDAYSQTY = data.WHLAST_IIIDAYSQTY;

      this.WHLAST_XVDAYS = data.WHLAST_XVDAYS;
      this.WHLAST_XVDAYSQTY = data.WHLAST_XVDAYSQTY;

      this.WHLAST_XXXXVDAYS = data.WHLAST_XXXXVDAYS;
      this.WHLAST_XXXXVDAYSQTY = data.WHLAST_XXXXVDAYSQTY;

     


      //WAREHOUSE ADMIN

    });
  }
}
