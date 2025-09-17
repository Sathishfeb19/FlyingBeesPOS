import { Component } from '@angular/core';
import { MenuFooterComponent } from "../menu-footer/menu-footer.component";
import { MenuSidebarComponent } from "../menu-sidebar/menu-sidebar.component";
import { MenuHeaderComponent } from "../menu-header/menu-header.component";

@Component({
  selector: 'app-sales-invoice',
  standalone: true,
  imports: [MenuFooterComponent, MenuSidebarComponent, MenuHeaderComponent],
  templateUrl: './sales-invoice.component.html',
  styleUrl: './sales-invoice.component.scss'
})
export class SalesInvoiceComponent {

}
