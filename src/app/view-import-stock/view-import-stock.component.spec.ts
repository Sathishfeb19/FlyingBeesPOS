import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewImportStockComponent } from './view-import-stock.component';

describe('ViewImportStockComponent', () => {
  let component: ViewImportStockComponent;
  let fixture: ComponentFixture<ViewImportStockComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ViewImportStockComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ViewImportStockComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
