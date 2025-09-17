import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReportAgeStockComponent } from './report-age-stock.component';

describe('ReportAgeStockComponent', () => {
  let component: ReportAgeStockComponent;
  let fixture: ComponentFixture<ReportAgeStockComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReportAgeStockComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ReportAgeStockComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
