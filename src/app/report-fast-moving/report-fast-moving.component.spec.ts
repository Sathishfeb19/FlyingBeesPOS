import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReportFastMovingComponent } from './report-fast-moving.component';

describe('ReportFastMovingComponent', () => {
  let component: ReportFastMovingComponent;
  let fixture: ComponentFixture<ReportFastMovingComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReportFastMovingComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ReportFastMovingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
