import { Component } from '@angular/core';
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuHeaderComponent } from "../menu-header/menu-header.component";
import { HttpClient } from '@angular/common/http';
import { Router, ActivatedRoute } from '@angular/router';
import Swal from 'sweetalert2';
import { FlyingbeesService } from '../flyingbees.service';

@Component({
  selector: 'app-view-user',
  standalone: true,
  imports: [MenuFooterComponent, MenuSidebarComponent, MenuHeaderComponent],
  templateUrl: './view-user.component.html',
  styleUrl: './view-user.component.scss'
})
export class ViewUserComponent {

  BeesService: any;
  INSERTED_URL: any;
  product_name: any;   dtOptions: any;
  constructor(private http: HttpClient,private Bees:FlyingbeesService,private router: Router, private route: ActivatedRoute) { }
  Product: any;
  displaytype =true;
  ngOnInit(): void {
  
    this.Bees.View_Users().subscribe((data: any) => {
      this.Product = data;
     
   
    }); 

    this.dtOptions = {
      pagingType: 'full_numbers',
      pageLength: 10,
      processing: true,
      dom: 'tlip', // ✅ Controls layout
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]], // ✅ Page size dropdown
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'] // ✅ Export buttons
    };
  }

  DeleteProducts(INSERTED_URL:any)
  {
    Swal.fire({
      icon: "warning",
      title: 'ARE YOU SURE? DELETE !',
      showCancelButton: true,
      confirmButtonColor: "#1976d2",
      cancelButtonText: "No",
      confirmButtonText: "Yes",
      
}).then((swal)=>{
  if(swal.isConfirmed){
    this.Bees.DeleteUsers(INSERTED_URL).subscribe((data: any) => {
      const currentUrl = this.router.url;
    this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
        this.router.navigate([currentUrl]);
    });
       });
  //  console.log('Yes');
  }
});
    
  }

}
