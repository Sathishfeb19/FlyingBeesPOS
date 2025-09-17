import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddWareHouseComponent } from './add-ware-house.component';

describe('AddWareHouseComponent', () => {
  let component: AddWareHouseComponent;
  let fixture: ComponentFixture<AddWareHouseComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AddWareHouseComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AddWareHouseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
