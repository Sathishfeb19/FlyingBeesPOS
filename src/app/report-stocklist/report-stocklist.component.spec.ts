import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReportStocklistComponent } from './report-stocklist.component';

describe('ReportStocklistComponent', () => {
  let component: ReportStocklistComponent;
  let fixture: ComponentFixture<ReportStocklistComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReportStocklistComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ReportStocklistComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
