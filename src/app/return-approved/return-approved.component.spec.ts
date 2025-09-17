import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReturnApprovedComponent } from './return-approved.component';

describe('ReturnApprovedComponent', () => {
  let component: ReturnApprovedComponent;
  let fixture: ComponentFixture<ReturnApprovedComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReturnApprovedComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ReturnApprovedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
