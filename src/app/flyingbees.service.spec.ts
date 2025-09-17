import { TestBed } from '@angular/core/testing';

import { FlyingbeesService } from './flyingbees.service';

describe('FlyingbeesService', () => {
  let service: FlyingbeesService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(FlyingbeesService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
