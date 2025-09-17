import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { Observable } from 'rxjs/internal/Observable';
import CryptoJS from 'crypto-js';

@Injectable({
  providedIn: 'root'
})
export class FlyingbeesService {
  // API = "http://localhost/grace_bees_api/"; 
   API = "https://gracefashion.sg/grace/api/";
  router: any;
  userData: any = Object();
  private secretKey: string = 'SathishKumar';
  constructor(private http: HttpClient, private Cookies: CookieService) { }

  // Encrypt JSON data and return JWT token
  encrypt(data: any): string {
    return CryptoJS.AES.encrypt(JSON.stringify(data), this.secretKey).toString();
  }
  // Decrypt JWT token and return JSON data
  decrypt(token: string): any {
    try {
      const decryptedData = CryptoJS.AES.decrypt(token, this.secretKey).toString(CryptoJS.enc.Utf8);
      return JSON.parse(decryptedData);
    } catch (error: any) {
      console.log('JWT Verification failed:', error.message);
      return { status: 'error' };
    }
  }
  checking(): any {
    var token = this.Cookies.get('usertoken');
    const data = this.decrypt(token);
    return data;
  }
  settoken(data: any) {
    const token = this.encrypt(data);
    this.Cookies.set('usertoken', token);
  }

  LoginCheck(email: any, password: any): Observable<object> {
    var URL = this.API + 'Check.php';
    var formData = new FormData();
    formData.append("password", password);
    formData.append("email", email);
    return this.http.post(URL, formData);
  }
  UserValidation(): Observable<object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    var URL = this.API + 'Token.php';
    var formData = new FormData();
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  User_LogOut() {
    this.userData = Object();
    this.Cookies.delete('token');
    this.Cookies.delete('usertoken');
  }

  ViewProfile(): Observable<Object> {
    var URL = this.API + 'Sessionuser.php';
    var formData = new FormData();
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  View_BranchDetails(): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'ViewBranchDetails.php';
    var formData = new FormData();

    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  Update_UsersProfile(OLD_PASSWORD: any, NEWPASSWORD: any, CNEWPASSWORD: any, INSERTED_URL: any): Observable<Object> {
    const URL = this.API + 'Update_UsersProfile.php';
    var formData = new FormData();
    formData.append("OLD_PASSWORD", OLD_PASSWORD);
    formData.append("NEWPASSWORD", NEWPASSWORD);
    formData.append("CNEWPASSWORD", CNEWPASSWORD);
    formData.append("id", INSERTED_URL);
    return this.http.post(URL, formData);
  }


  View_VIISalesList(WH: any): Observable<Object> {
    var URL = this.API + 'ViewVIISalesList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }

  View_TopVIISalesList(WH: any): Observable<Object> {
    var URL = this.API + 'View_TopVIISalesList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }
  View_TopXXXSalesList(WH: any): Observable<Object> {
    var URL = this.API + 'View_TopXXXSalesList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }

  View_TopVIDSalesList(WH: any): Observable<Object> {
    var URL = this.API + 'View_TopVIDSalesList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }

  View_XXXSalesList(WH: any): Observable<Object> {
    var URL = this.API + 'View_XXXSalesList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }


  //GRACE API
  Create_Users(USER_NAME: any, USER_TYPE: any, BRANCH_CODE: any, USER_DOJ: any, USER_DOB: any,
    USER_MOBILE: any, USER_EMGMOBILE: any, USER_EMAIL: any, USER_AADHAR: any, USER_VEHICLE: any, USER_STATUS: any, INSERTED_URL: any = null
  ): Observable<any> {
    const URL = this.API + 'CreateUsers.php';
    const formData = new FormData();
    formData.append("USER_NAME", USER_NAME);
    formData.append("USER_TYPE", USER_TYPE);
    formData.append("BRANCH_CODE", BRANCH_CODE);
    formData.append("USER_DOJ", USER_DOJ);
    formData.append("USER_DOB", USER_DOB);
    formData.append("USER_MOBILE", USER_MOBILE);
    formData.append("USER_EMGMOBILE", USER_EMGMOBILE);
    formData.append("USER_EMAIL", USER_EMAIL);
    formData.append("USER_AADHAR", USER_AADHAR);
    formData.append("USER_VEHICLE", USER_VEHICLE);
    formData.append("USER_STATUS", USER_STATUS);
    formData.append("INSERTED_URL", INSERTED_URL);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  View_Users(): Observable<Object> {
    var URL = this.API + 'ViewUsers.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Update_Users(id: any): Observable<Object> {
    const URL = this.API + 'UpdateUsers.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }



  Create_Supplier(SUPPLIER_NAME: any, SUPPLIER_CODE: any, SUPPLIER_ADD1: any, SUPPLIER_ADD2: any,
    SUPPLIER_CITY: any, SUPPLIER_STATE: any, SUPPLIER_PINCODE: any, SUPPLIER_EMAIL: any, SUPPLIER_MBL1: any, SUPPLIER_MBL2: any,
    SUPPLIER_GST: any, SUPPLIER_ACCNO: any, SUPPLIER_IFSC: any, SUPPLIER_STS: any, SUPPLIER_DESC: any, INSERTED_URL: any = null
  ): Observable<any> {
    const URL = this.API + 'CreateSupplier.php';
    const formData = new FormData();
    formData.append("SUPPLIER_NAME", SUPPLIER_NAME);
    formData.append("SUPPLIER_CODE", SUPPLIER_CODE);
    formData.append("SUPPLIER_ADD1", SUPPLIER_ADD1);
    formData.append("SUPPLIER_ADD2", SUPPLIER_ADD2);
    formData.append("SUPPLIER_CITY", SUPPLIER_CITY);
    formData.append("SUPPLIER_STATE", SUPPLIER_STATE);
    formData.append("SUPPLIER_PINCODE", SUPPLIER_PINCODE);
    formData.append("SUPPLIER_EMAIL", SUPPLIER_EMAIL);
    formData.append("SUPPLIER_MBL1", SUPPLIER_MBL1);
    formData.append("SUPPLIER_MBL2", SUPPLIER_MBL2);
    formData.append("SUPPLIER_GST", SUPPLIER_GST);
    formData.append("SUPPLIER_ACCNO", SUPPLIER_ACCNO);
    formData.append("SUPPLIER_IFSC", SUPPLIER_IFSC);
    formData.append("SUPPLIER_STS", SUPPLIER_STS);
    formData.append("SUPPLIER_DESC", SUPPLIER_DESC);
    formData.append("INSERTED_URL", INSERTED_URL);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  View_Supplier(): Observable<Object> {
    var URL = this.API + 'ViewSupplier.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Update_Supplier(id: any): Observable<Object> {
    const URL = this.API + 'UpdateSupplier.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }
  //WareHouse
  Create_WareHouse(WH_NAME: any, WH_CODE: any, WH_ADDRESS: any, WH_ADDRESS1: any,
    WH_CITY: any, WH_STATE: any, WH_PINCODE: any, WH_PERSON: any, WH_MOBILE: any, WH_TYPE: any, WH_STATUS: any,
    WH_DESC: any, INSERTED_URL: any = null
  ): Observable<any> {
    const URL = this.API + 'CreateWareHouse.php';
    const formData = new FormData();
    formData.append("WH_NAME", WH_NAME);
    formData.append("WH_CODE", WH_CODE);
    formData.append("WH_ADDRESS", WH_ADDRESS);
    formData.append("WH_ADDRESS1", WH_ADDRESS1);
    formData.append("WH_CITY", WH_CITY);
    formData.append("WH_STATE", WH_STATE);
    formData.append("WH_PINCODE", WH_PINCODE);
    formData.append("WH_PERSON", WH_PERSON);
    formData.append("WH_MOBILE", WH_MOBILE);
    formData.append("WH_TYPE", WH_TYPE);
    formData.append("WH_STATUS", WH_STATUS);
    formData.append("WH_DESC", WH_DESC);
    formData.append("INSERTED_URL", INSERTED_URL);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  View_WareHouse(): Observable<Object> {
    var URL = this.API + 'ViewWareHouse.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Update_WareHouse(id: any): Observable<Object> {
    const URL = this.API + 'UpdateWareHouse.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }
  //Products
  Create_Products(PROD_NAME: any, PROD_CODE: any, PROD_STATUS: any, PROD_DESC: any, INSERTED_URL: any = null
  ): Observable<any> {
    const URL = this.API + 'CreateProducts.php';
    const formData = new FormData();
    formData.append("PROD_NAME", PROD_NAME);
    formData.append("PROD_CODE", PROD_CODE);
    formData.append("PROD_STATUS", PROD_STATUS);
    formData.append("PROD_DESC", PROD_DESC);
    formData.append("id", INSERTED_URL);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  View_Product(): Observable<Object> {
    var URL = this.API + 'ViewProduct.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Update_Products(id: any): Observable<Object> {
    const URL = this.API + 'UpdateProducts.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }

  View_Stock(): Observable<Object> {
    var URL = this.API + 'ViewStock.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  Search_Stock_Id(WH: any, id: any): Observable<Object> {
    var URL = this.API + 'SearchStockId.php';
    var formData = new FormData();
    formData.append("id", id);
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }
  Update_Stock_Id(WH: any, data: any): Observable<Object> {
    var URL = this.API + 'UpdateStockId.php';
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    var formData = new FormData();
    formData.append("token", token);
    formData.append("data", JSON.stringify(data));
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }
  Search_Stock(SEARCH: any, WH: any): Observable<Object> {
    var URL = this.API + 'SearchStock.php';
    var formData = new FormData();
    formData.append("SEARCH", SEARCH);
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }

  AgeStockList(SEARCH: any, WH: any): Observable<Object> {
    var URL = this.API + 'AgeStockList.php';
    var formData = new FormData();
    formData.append("SEARCH", SEARCH);
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }
  NoSalesReport(SEARCH: any, WH: any): Observable<Object> {
    var URL = this.API + 'NoSalesReport.php';
    var formData = new FormData();
    formData.append("SEARCH", SEARCH);
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }
  SlowMoving(SEARCH: any, WH: any): Observable<Object> {
    var URL = this.API + 'SlowMoving.php';
    var formData = new FormData();
    formData.append("SEARCH", SEARCH);
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  } 
  FastMoving(SEARCH: any, WH: any): Observable<Object> {
    var URL = this.API + 'FastMoving.php';
    var formData = new FormData();
    formData.append("SEARCH", SEARCH);
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }

 
 SalesPersons(FROMDATE: any, TODATE: any): Observable<Object> {
  const URL = this.API + 'SalesPersons.php';
  const formData = new FormData();
  formData.append("FROMDATE", FROMDATE);
  formData.append("TODATE", TODATE);
  return this.http.post(URL, formData);
}

  GodownSales(SEARCH: any, WH: any): Observable<Object> {
    var URL = this.API + 'GodownSales.php';
    var formData = new FormData();
    formData.append("SEARCH", SEARCH);
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }
  Search_Bill_Stock(SEARCH: any, WH: any, selected: any): Observable<Object> {
    var URL = this.API + 'SearchBillStock.php';
    var formData = new FormData();
    formData.append("SEARCH", SEARCH);
    formData.append("WH", WH);
    formData.append("selected", JSON.stringify(selected));
    return this.http.post(URL, formData);
  }

  List_UserType(): Observable<Object> {
    const URL = this.API + 'ListUserType.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  List_WareHouse(): Observable<Object> {
    const URL = this.API + 'ListWareHouse.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  List_Sales(WH: any): Observable<Object> {
    const URL = this.API + 'ListSales.php';
    var formData = new FormData();
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }















  //Grace End Project 

  //<<--------------View Count------------------>>
  Total_Product(): Observable<Object> {
    const URL = this.API + 'Total_Product.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  Total_Project(): Observable<Object> {
    const URL = this.API + 'Total_Project.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Total_Customer(): Observable<Object> {
    const URL = this.API + 'Total_Customers.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }


  Total_CompletedJobs(): Observable<Object> {
    const URL = this.API + 'Total_CompletedJobs.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Total_Users(): Observable<Object> {
    const URL = this.API + 'Total_Users.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Total_PaidAmount(): Observable<Object> {
    const URL = this.API + 'Total_PaidAmount.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  Total_PendingAmount(): Observable<Object> {
    const URL = this.API + 'Total_PendingAmount.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Total_7PaidAmount(): Observable<Object> {
    const URL = this.API + 'Total_7PaidAmount.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  Total_30PaidAmount(): Observable<Object> {
    const URL = this.API + 'Total_30PaidAmount.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  //<<--------------View DropDown List------------------>>
  List_Customers(): Observable<Object> {
    const URL = this.API + 'List_Customers.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  List_ProjectType(): Observable<Object> {
    const URL = this.API + 'List_ProjectType.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  List_ExpenceType(): Observable<Object> {
    const URL = this.API + 'List_ExpenceType.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }



  List_UsersJob(): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'View_UsersJobs.php';
    var formData = new FormData();
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  //<<--------------View Active List------------------>>
  Active_Users(): Observable<Object> {
    const URL = this.API + 'Active_Users.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  Active_Projects(): Observable<Object> {
    const URL = this.API + 'Active_Projects.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  Active_Projects2(): Observable<Object> {
    const URL = this.API + 'Active_Projects2.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }


  //<<------------ Expenses Type Operations------------------>>
  CreateExpenses(EXP_NAME: any, EXP_STATUS: any, id: any
  ): Observable<any> {
    const URL = this.API + 'Add_Expenses.php';
    const formData = new FormData();
    formData.append("EXP_NAME", EXP_NAME);
    formData.append("EXP_STATUS", EXP_STATUS);
    formData.append("id", id);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  Update_Expenses(id: any): Observable<Object> {
    const URL = this.API + 'Update_Expens.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }
  View_Expenses(): Observable<Object> {
    var URL = this.API + 'View_Expenses.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  DeleteExpenses(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Delete_Expenses.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  //<<------------Project Type Operations------------------>>
  CreateProjType(TYPE_NAME: any, TYPE_STATUS: any, id: any
  ): Observable<any> {
    const URL = this.API + 'Add_ProjType.php';
    const formData = new FormData();
    formData.append("TYPE_NAME", TYPE_NAME);
    formData.append("TYPE_STATUS", TYPE_STATUS);
    formData.append("id", id);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  Update_ProType(id: any): Observable<Object> {
    const URL = this.API + 'Update_ProjTyp.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }
  View_ProType(): Observable<Object> {
    var URL = this.API + 'View_ProjTyp.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  DeleteProType(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Delete_ProjTyp.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  //<<------------ New Customers Operations------------------>>
  Create_Customers(CUST_NAME: any, CUST_MAIL: any, CUST_MOBILE: any, CUST_ADDRESS1: any,
    CUST_ADDRESS2: any, CUST_CITY: any, CUST_STATE: any, CUST_PINCODE: any, CUST_DESC: any, CUST_STATUS: any, INSERTED_URL: any = null
  ): Observable<any> {
    const URL = this.API + 'Add_Customer.php';
    const formData = new FormData();
    formData.append("CUST_NAME", CUST_NAME);
    formData.append("CUST_MAIL", CUST_MAIL);
    formData.append("CUST_MOBILE", CUST_MOBILE);
    formData.append("CUST_ADDRESS1", CUST_ADDRESS1);
    formData.append("CUST_ADDRESS2", CUST_ADDRESS2);
    formData.append("CUST_CITY", CUST_CITY);
    formData.append("CUST_STATE", CUST_STATE);
    formData.append("CUST_PINCODE", CUST_PINCODE);
    formData.append("CUST_DESC", CUST_DESC);
    formData.append("CUST_STATUS", CUST_STATUS);
    formData.append("INSERTED_URL", INSERTED_URL);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  View_Customers(): Observable<Object> {
    var URL = this.API + 'View_Customer.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  Update_Customer(id: any): Observable<Object> {
    const URL = this.API + 'Update_Customer.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }
  DeleteCustomer(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Delete_Customer.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  //Create Vendor

  Create_Vendor(VENDOR_NAME: any, VENDOR_MOBILE: any, VENDOR_LOC: any, VENDOR_DESC: any,
    VENDOR_STATUS: any, INSERTED_URL: any = null
  ): Observable<any> {
    const URL = this.API + 'Add_Vendor.php';
    const formData = new FormData();
    formData.append("VENDOR_NAME", VENDOR_NAME);
    formData.append("VENDOR_MOBILE", VENDOR_MOBILE);
    formData.append("VENDOR_LOC", VENDOR_LOC);
    formData.append("VENDOR_DESC", VENDOR_DESC);
    formData.append("VENDOR_STATUS", VENDOR_STATUS);
    formData.append("INSERTED_URL", INSERTED_URL);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }


  //<<------------ New Users Operations------------------>>


  DeleteUsers(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Delete_Users.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  //<<------------ New Users Operations------------------>>
  Create_Project(PROJ_NAME: any, CUST_ID: any, PROJ_TYPE: any, PROJ_STRDATE: any,
    PROJ_ENDDATE: any, PROJ_LOCATION: any, PROJ_VALUE: any, PROJ_DESC: any, PROJ_STATUS: any, id: any
  ): Observable<any> {
    const URL = this.API + 'Add_Projects.php';
    const formData = new FormData();
    formData.append("PROJ_NAME", PROJ_NAME);
    formData.append("CUST_ID", CUST_ID);
    formData.append("PROJ_TYPE", PROJ_TYPE);
    formData.append("PROJ_STRDATE", PROJ_STRDATE);
    formData.append("PROJ_ENDDATE", PROJ_ENDDATE);
    formData.append("PROJ_LOCATION", PROJ_LOCATION);
    formData.append("PROJ_VALUE", PROJ_VALUE);
    formData.append("PROJ_STATUS", PROJ_STATUS);
    formData.append("PROJ_DESC", PROJ_DESC);
    formData.append("id", id);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  Update_Project(id: any): Observable<Object> {
    const URL = this.API + 'Update_Project.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }

  View_Project(): Observable<Object> {
    var URL = this.API + 'View_Project.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  View_CompletedProject(): Observable<Object> {
    var URL = this.API + 'View_CompletedProject.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  Delete_Project(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Delete_Project.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  Completed_Project(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Completed_Project.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  //<<------------ New Job Allocations Operations------------------>>
  CreateJobAllocation(JOB_NAME: any, PROJECT_ID: any, JOB_TO: any, JOB_TYPE: any,
    JOB_STARTDATE: any, JOB_ENDDATE: any, JOB_LOCATION: any, JOB_VALUE: any, ADV_AMOUNT: any, BAL_AMOUNT: any, JOB_DESC: any, id: any
  ): Observable<any> {
    const URL = this.API + 'Create_JobAllocation.php';
    const formData = new FormData();
    formData.append("JOB_NAME", JOB_NAME);
    formData.append("PROJECT_ID", PROJECT_ID);
    formData.append("JOB_TO", JOB_TO);
    formData.append("JOB_TYPE", JOB_TYPE);
    formData.append("JOB_STARTDATE", JOB_STARTDATE);
    formData.append("JOB_ENDDATE", JOB_ENDDATE);
    formData.append("JOB_LOCATION", JOB_LOCATION);
    formData.append("JOB_VALUE", JOB_VALUE);
    formData.append("ADV_AMOUNT", ADV_AMOUNT);
    formData.append("BAL_AMOUNT", BAL_AMOUNT);
    formData.append("JOB_DESC", JOB_DESC);
    formData.append("id", id);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  View_JobAllocation(): Observable<Object> {
    var URL = this.API + 'View_JobAllocation.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Update_JobsAllocation(id: any): Observable<Object> {
    const URL = this.API + 'Update_JobAllocation.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }

  DeleteAllocation(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Delete_Allocation.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  CreateMyExpencses(JOB_ID: any, EXP_TYPE: any, BILL_VENDOR: any, BILL_DATE: any, BILL_NUM: any, BILL_VALUE: any,
    BILL_MODE: any, BILL_DESC: any, BILL_STATUS: any, images: any
  ): Observable<any> {
    const URL = this.API + 'Create_MyExpenses.php';
    const formData = new FormData();
    formData.append("JOB_ID", JOB_ID);
    formData.append("EXP_TYPE", EXP_TYPE);
    formData.append("BILL_VENDOR", BILL_VENDOR);
    formData.append("BILL_DATE", BILL_DATE);
    formData.append("BILL_NUM", BILL_NUM);
    formData.append("BILL_VALUE", BILL_VALUE);
    formData.append("BILL_MODE", BILL_MODE);
    formData.append("BILL_DESC", BILL_DESC);
    formData.append("BILL_STATUS", BILL_STATUS);
    formData.append("images", JSON.stringify(images));

    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  //   CreateMyExpencses(formData: FormData): Observable<any> {
  //     const URL = this.API + 'Create_MyExpenses.php';
  //     const token = this.Cookies.get('token') || 'empty';
  //     formData.append("token", token);
  //     return this.http.post(URL, formData);
  // }


  Update_MyExpences(id: any): Observable<Object> {
    const URL = this.API + 'Update_MyExpences.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }


  View_MyExpences(): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'View_MyBills.php';
    var formData = new FormData();
    // formData.append("USER_ID", USER_ID);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  Completed_MyExpences(): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Completed_MyExpences.php';
    var formData = new FormData();
    // formData.append("USER_ID", USER_ID);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }



  Delete_MyExpences(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Delete_Bill.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }


  //<-------------Report Pending Jobs--------------->
  Report_CompletedJobs(): Observable<Object> {
    var URL = this.API + 'View_CompletedJobs.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Job3_Completed(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Job_Completed.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }



  //<-------------Report Pending Jobs--------------->
  View_PendingBills(): Observable<Object> {
    var URL = this.API + 'View_PendingBills.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  Report_PendingJobs(): Observable<Object> {
    var URL = this.API + 'View_PendingJobs.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }

  DeleteBill(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Delete_Bill.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }


  View_DetailedPending(id: any): Observable<Object> {
    const URL = this.API + 'ViewDetailedPending.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }

  View_DetailedCompleted(id: any): Observable<Object> {
    const URL = this.API + 'View_DetailedCompleted.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }

  PayMent_Paid(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'PayMent_Paid.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  Job_Completed(INSERTED_URL: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'Job_Completed.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", INSERTED_URL);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }



  View_CompletedBills(): Observable<Object> {
    var URL = this.API + 'View_CompletedBills.php';
    var formData = new FormData();
    return this.http.post(URL, formData);
  }
  View_MyJobs(): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'View_MyJobs.php';
    var formData = new FormData();

    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  View_MyCompletedJobs(): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    const URL = this.API + 'View_MyCompletedJobs.php';
    var formData = new FormData();

    formData.append("token", token);
    return this.http.post(URL, formData);
  }

  importStock(file: any): Observable<object> {
    var URL = this.API + 'ImportStock.php';
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    var formData = new FormData();
    formData.append("token", token);
    formData.append("file", file);
    return this.http.post(URL, formData);
  }
  viewAddress(WH_ID: any): Observable<Object> {
    const URL = this.API + 'ViewAddress.php';
    var formData = new FormData();
    formData.append("WH_ID", WH_ID);
    return this.http.post(URL, formData);
  }

  View_InvoiceList(SEARCH: any = '', WH: any = 'all'): Observable<Object> {
    var URL = this.API + 'ViewInvoiceList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    formData.append("SEARCH", SEARCH);
    return this.http.post(URL, formData);
  }
  View_TransferList(SEARCH: any = '', WH: any = 'all'): Observable<Object> {
    var URL = this.API + 'ViewTransferList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    formData.append("SEARCH", SEARCH);
    return this.http.post(URL, formData);
  }


  ViewDetailedInvoice(id: any): Observable<Object> {
    const URL = this.API + 'ViewDetailedInvoice.php';
    var formData = new FormData();
    formData.append("id", id);
    return this.http.post(URL, formData);
  }
  CreateInvoice(data: any): Observable<Object> {
    const URL = this.API + 'CreateInvoice.php';
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    var formData = new FormData();
    formData.append("token", token);
    formData.append("data", JSON.stringify(data));
    return this.http.post(URL, formData);
  }
  DeleteInvoice(id: any): Observable<Object> {
    const URL = this.API + 'DeleteInvoice.php';
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    var formData = new FormData();
    formData.append("token", token);
    formData.append("id", id);
    return this.http.post(URL, formData);
  }

  ViewUserDashBoard(WH: any): Observable<Object> {
    var URL = this.API + 'DashBoardDetails.php';
    var formData = new FormData();
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    formData.append("token", token);
    formData.append("WH", WH);
    return this.http.post(URL, formData);
  }

  Create_Returns(O_INVOICENO: any, O_INVOICEDATE: any, O_BARCODE: any, N_INVOICENO: any, N_INVOICEDATE: any,
    N_BARCODE: any, VALUE_PAID: any, REASON_RETURN: any, INSERTED_URL: any = null
  ): Observable<any> {
    const URL = this.API + 'CreateReturns.php';
    const formData = new FormData();
    formData.append("O_INVOICENO", O_INVOICENO);
    formData.append("O_INVOICEDATE", O_INVOICEDATE);
    formData.append("O_BARCODE", O_BARCODE);
    formData.append("N_INVOICENO", N_INVOICENO);
    formData.append("N_INVOICEDATE", N_INVOICEDATE);
    formData.append("N_BARCODE", N_BARCODE);
    formData.append("VALUE_PAID", VALUE_PAID);
    formData.append("REASON_RETURN", REASON_RETURN);
    formData.append("INSERTED_URL", INSERTED_URL);
    const token = this.Cookies.get('token') || 'empty';
    formData.append("token", token);
    return this.http.post(URL, formData);
  }
  View_ReturnsList(SEARCH: any = '', WH: any = 'all'): Observable<Object> {
    var URL = this.API + 'ViewReturnList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    formData.append("SEARCH", SEARCH);
    return this.http.post(URL, formData);
  }
  View_UpdatedReturnsList(SEARCH: any = '', WH: any = 'all'): Observable<Object> {
    var URL = this.API + 'ViewUpdatedReturnsList.php';
    var formData = new FormData();
    formData.append("WH", WH);
    formData.append("SEARCH", SEARCH);
    return this.http.post(URL, formData);
  }
  UpdateWhStock(data: any): Observable<Object> {
    var token = this.Cookies.get('token');
    if (token == '') { token = 'empty' };
    var URL = this.API + 'UpdateWhStock.php';
    var formData = new FormData();
    formData.append("INSERTED_URL", data.INSERTED_URL);
    formData.append("O_BARCODE", data.O_BARCODE);
    formData.append("WH_ID", data.WH_ID);
    formData.append("QTY", data.QTY);
    formData.append("token", token);
    return this.http.post(URL, formData);
  }


}
