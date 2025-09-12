import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BeesDashboardComponent } from './bees-dashboard.component';

describe('BeesDashboardComponent', () => {
  let component: BeesDashboardComponent;
  let fixture: ComponentFixture<BeesDashboardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BeesDashboardComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(BeesDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
