import { Component } from '@angular/core';
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuHeaderComponent } from "../menu-header/menu-header.component";

@Component({
  selector: 'app-view-user',
  standalone: true,
  imports: [MenuFooterComponent, MenuSidebarComponent, MenuHeaderComponent],
  templateUrl: './view-user.component.html',
  styleUrl: './view-user.component.scss'
})
export class ViewUserComponent {

}
