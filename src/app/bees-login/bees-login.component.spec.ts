import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BeesLoginComponent } from './bees-login.component';

describe('BeesLoginComponent', () => {
  let component: BeesLoginComponent;
  let fixture: ComponentFixture<BeesLoginComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BeesLoginComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(BeesLoginComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
