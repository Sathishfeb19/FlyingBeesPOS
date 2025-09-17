import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReportSlowMovingComponent } from './report-slow-moving.component';

describe('ReportSlowMovingComponent', () => {
  let component: ReportSlowMovingComponent;
  let fixture: ComponentFixture<ReportSlowMovingComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReportSlowMovingComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ReportSlowMovingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
