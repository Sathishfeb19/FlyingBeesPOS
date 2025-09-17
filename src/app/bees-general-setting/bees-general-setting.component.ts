import { Component } from '@angular/core';
import { MenuHeaderComponent } from "../menu-header/menu-header.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";

@Component({
  selector: 'app-bees-general-setting',
  standalone: true,
  imports: [MenuHeaderComponent, MenuSidebarComponent, MenuFooterComponent],
  templateUrl: './bees-general-setting.component.html',
  styleUrl: './bees-general-setting.component.scss'
})
export class BeesGeneralSettingComponent {

}
