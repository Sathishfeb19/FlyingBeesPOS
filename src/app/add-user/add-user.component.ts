import { Component } from '@angular/core';
import { MenuHeaderComponent } from "../menu-header/menu-header.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";

@Component({
  selector: 'app-add-user',
  standalone: true,
  imports: [MenuHeaderComponent, MenuSidebarComponent, MenuFooterComponent],
  templateUrl: './add-user.component.html',
  styleUrl: './add-user.component.scss'
})
export class AddUserComponent {

}
