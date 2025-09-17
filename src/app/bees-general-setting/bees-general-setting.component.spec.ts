import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BeesGeneralSettingComponent } from './bees-general-setting.component';

describe('BeesGeneralSettingComponent', () => {
  let component: BeesGeneralSettingComponent;
  let fixture: ComponentFixture<BeesGeneralSettingComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BeesGeneralSettingComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(BeesGeneralSettingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
