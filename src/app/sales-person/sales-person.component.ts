import { Component } from '@angular/core';
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { MenuHeaderComponent } from "../menu-header/menu-header.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";

@Component({
  selector: 'app-sales-person',
  standalone: true,
  imports: [MenuFooterComponent, MenuHeaderComponent, MenuSidebarComponent],
  templateUrl: './sales-person.component.html',
  styleUrl: './sales-person.component.scss'
})
export class SalesPersonComponent {

}
