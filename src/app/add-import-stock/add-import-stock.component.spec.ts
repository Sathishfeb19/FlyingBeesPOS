import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddImportStockComponent } from './add-import-stock.component';

describe('AddImportStockComponent', () => {
  let component: AddImportStockComponent;
  let fixture: ComponentFixture<AddImportStockComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AddImportStockComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AddImportStockComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
