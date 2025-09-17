import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BeesProfileComponent } from './bees-profile.component';

describe('BeesProfileComponent', () => {
  let component: BeesProfileComponent;
  let fixture: ComponentFixture<BeesProfileComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BeesProfileComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(BeesProfileComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
