import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewWareHouseComponent } from './view-ware-house.component';

describe('ViewWareHouseComponent', () => {
  let component: ViewWareHouseComponent;
  let fixture: ComponentFixture<ViewWareHouseComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ViewWareHouseComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ViewWareHouseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
