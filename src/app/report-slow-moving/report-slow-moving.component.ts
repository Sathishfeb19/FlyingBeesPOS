import { Component } from '@angular/core';
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuHeaderComponent } from "../menu-header/menu-header.component";

@Component({
  selector: 'app-report-slow-moving',
  standalone: true,
  imports: [MenuFooterComponent, MenuSidebarComponent, MenuHeaderComponent],
  templateUrl: './report-slow-moving.component.html',
  styleUrl: './report-slow-moving.component.scss'
})
export class ReportSlowMovingComponent {

}
