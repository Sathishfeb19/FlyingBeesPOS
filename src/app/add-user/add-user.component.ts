import { Component } from '@angular/core';
import { MenuHeaderComponent } from "../menu-header/menu-header.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { FlyingbeesService } from '../flyingbees.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-add-user',
  standalone: true,
  imports: [MenuHeaderComponent, MenuSidebarComponent, MenuFooterComponent],
  templateUrl: './add-user.component.html',
  styleUrl: './add-user.component.scss'
})
export class AddUserComponent {
 UserType: any; WareHouse: any;
  id: any;
  constructor(private http: HttpClient, private Bees: FlyingbeesService, private fb: FormBuilder, private router: Router, private route: ActivatedRoute) { }
  UserFrm: FormGroup | any;

  ngOnInit(): void {

    this.Bees.List_UserType().subscribe((res) => {
      this.UserType = res;
    });

    this.Bees.List_WareHouse().subscribe((res) => {
      this.WareHouse = res;
    });

    this.id = this.route.snapshot.params['id'];
    this.UserFrm = new FormGroup(
      {
        USER_NAME: new FormControl('', Validators.required),
        USER_TYPE: new FormControl('', Validators.required),
        BRANCH_CODE: new FormControl('', Validators.required),
        USER_DOJ: new FormControl(this.getCurrentDate(), Validators.required),
        USER_DOB: new FormControl(this.getCurrentDate()),
        USER_MOBILE: new FormControl('',  Validators.required),
        USER_EMGMOBILE: new FormControl('',),
        USER_EMAIL: new FormControl('', [Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$")]),
        USER_AADHAR: new FormControl('', [Validators.minLength(12), Validators.maxLength(12)]),
        USER_VEHICLE: new FormControl('', [Validators.minLength(7), Validators.maxLength(12)]),
        USER_STATUS: new FormControl('', Validators.required),
      }

    );

    this.Bees.Update_Users(this.id).subscribe((res: any) => {
      if (res.status == 'ok') {
        this.UserFrm.get('USER_NAME').setValue(res.USER_NAME);
        this.UserFrm.get('USER_EMAIL').setValue(res.USER_EMAIL);
        this.UserFrm.get('USER_TYPE').setValue(res.USER_TYPE);
        this.UserFrm.get('BRANCH_CODE').setValue(res.BRANCH_CODE);
        this.UserFrm.get('USER_DOB').setValue(res.USER_DOB);
        this.UserFrm.get('USER_DOJ').setValue(res.USER_DOJ);
        this.UserFrm.get('USER_MOBILE').setValue(res.USER_MOBILE);
        this.UserFrm.get('USER_EMGMOBILE').setValue(res.USER_EMGMOBILE);
        this.UserFrm.get('USER_AADHAR').setValue(res.USER_AADHAR);
        this.UserFrm.get('USER_VEHICLE').setValue(res.USER_VEHICLE);
        this.UserFrm.get('USER_STATUS').setValue(res.USER_STATUS);
      }

    })
  }

  private getCurrentDate(): string {
    const today = new Date();
    const year = today.getFullYear();
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    const day = today.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  CreateUsers() {
    this.UserFrm.markAllAsTouched();
    if (this.UserFrm.get('USER_NAME').invalid) {
      Swal.fire({
        title: 'ENTER USER NAME',
        icon: 'warning',
      })
    }
    else if (this.UserFrm.get('USER_TYPE').invalid) {
      Swal.fire({
        title: 'SELECT USER TYPE',
        icon: 'warning',
      })
    }
    else if (this.UserFrm.get('BRANCH_CODE').invalid) {
      Swal.fire({
        title: 'SELECT USER WAREHOUSE',
        icon: 'warning',
      })
    }

    else if (this.UserFrm.get('USER_DOJ').invalid) {
      Swal.fire({
        title: 'SELECT DATE OF JOINING',
        icon: 'warning',
      })
    }
    else if (this.UserFrm.get('USER_MOBILE').invalid) {
      Swal.fire({
        title: 'ENTER MOBILE NUMBER',
        icon: 'warning',
      })
    }
    else if (this.UserFrm.get('USER_STATUS').invalid) {
      Swal.fire({
        title: 'SELECT USER STATUS',
        icon: 'warning',
      })
    }
    //--------------------------
    if (this.UserFrm.valid) {
      const USER_NAME = this.UserFrm.get('USER_NAME').value;
      const USER_TYPE = this.UserFrm.get('USER_TYPE').value;
      const BRANCH_CODE = this.UserFrm.get('BRANCH_CODE').value;
      const USER_DOJ = this.UserFrm.get('USER_DOJ').value;
      const USER_DOB = this.UserFrm.get('USER_DOB').value;
      const USER_MOBILE = this.UserFrm.get('USER_MOBILE').value;
      const USER_EMGMOBILE = this.UserFrm.get('USER_EMGMOBILE').value;
      const USER_EMAIL = this.UserFrm.get('USER_EMAIL').value;
      const USER_AADHAR = this.UserFrm.get('USER_AADHAR').value;
      const USER_VEHICLE = this.UserFrm.get('USER_VEHICLE').value;
      const USER_STATUS = this.UserFrm.get('USER_STATUS').value;
      this.Bees.Create_Users(USER_NAME, USER_TYPE,BRANCH_CODE, USER_DOJ, USER_DOB, USER_MOBILE, USER_EMGMOBILE, USER_EMAIL, USER_AADHAR, USER_VEHICLE, USER_STATUS, this.id
      ).subscribe(
        (data: any) => {
          if (data.status === 'Success') {
            Swal.fire({
              title: 'Added Successfully',
              icon: 'success',
            });

            this.UserFrm.reset();
            location.href = "view-users"
          } else if (data.status === 'error') {
            Swal.fire({
              title: 'mobile number or email already exists',
              icon: 'warning',
            });
          }
        },
      );
    }
  }
  resetForm() {
    this.UserFrm.reset();
  }
}
