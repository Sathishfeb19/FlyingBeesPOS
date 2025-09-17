import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReportZeroMovingComponent } from './report-zero-moving.component';

describe('ReportZeroMovingComponent', () => {
  let component: ReportZeroMovingComponent;
  let fixture: ComponentFixture<ReportZeroMovingComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReportZeroMovingComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ReportZeroMovingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
