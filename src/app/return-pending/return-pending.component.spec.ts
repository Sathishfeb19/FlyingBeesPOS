import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReturnPendingComponent } from './return-pending.component';

describe('ReturnPendingComponent', () => {
  let component: ReturnPendingComponent;
  let fixture: ComponentFixture<ReturnPendingComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReturnPendingComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ReturnPendingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
