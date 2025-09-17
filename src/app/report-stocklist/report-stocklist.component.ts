import { Component } from '@angular/core';
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { MenuHeaderComponent } from "../menu-header/menu-header.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";

@Component({
  selector: 'app-report-stocklist',
  standalone: true,
  imports: [MenuFooterComponent, MenuHeaderComponent, MenuSidebarComponent],
  templateUrl: './report-stocklist.component.html',
  styleUrl: './report-stocklist.component.scss'
})
export class ReportStocklistComponent {

}
