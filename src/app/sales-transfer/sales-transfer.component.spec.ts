import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SalesTransferComponent } from './sales-transfer.component';

describe('SalesTransferComponent', () => {
  let component: SalesTransferComponent;
  let fixture: ComponentFixture<SalesTransferComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SalesTransferComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SalesTransferComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
