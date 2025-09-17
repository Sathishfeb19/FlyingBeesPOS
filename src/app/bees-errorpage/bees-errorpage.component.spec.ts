import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BeesErrorpageComponent } from './bees-errorpage.component';

describe('BeesErrorpageComponent', () => {
  let component: BeesErrorpageComponent;
  let fixture: ComponentFixture<BeesErrorpageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BeesErrorpageComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(BeesErrorpageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
