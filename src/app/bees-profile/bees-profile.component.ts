import { Component } from '@angular/core';
import { MenuHeaderComponent } from "../menu-header/menu-header.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";

@Component({
  selector: 'app-bees-profile',
  standalone: true,
  imports: [MenuHeaderComponent, MenuSidebarComponent, MenuFooterComponent],
  templateUrl: './bees-profile.component.html',
  styleUrl: './bees-profile.component.scss'
})
export class BeesProfileComponent {

}
