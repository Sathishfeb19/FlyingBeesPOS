import { Component } from '@angular/core';
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuHeaderComponent } from "../menu-header/menu-header.component";

@Component({
  selector: 'app-return-approved',
  standalone: true,
  imports: [MenuFooterComponent, MenuSidebarComponent, MenuHeaderComponent],
  templateUrl: './return-approved.component.html',
  styleUrl: './return-approved.component.scss'
})
export class ReturnApprovedComponent {

}
