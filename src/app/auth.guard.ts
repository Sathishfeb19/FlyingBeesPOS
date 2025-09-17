import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, Router } from '@angular/router';
import { FlyingbeesService } from './flyingbees.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard {
  constructor(private Bees: FlyingbeesService, private router: Router) { }
  canActivate(route: ActivatedRouteSnapshot) {
    const url: any = route.url[0]?.path;
    let data = this.Bees.userData;
    if (Object.keys(data).length === 0 || data.status != 'ok') {
      data = this.Bees.checking();
      this.Bees.userData = data;
    }

    if (data.status !== 'ok') {
      if (url === 'login' || !route.routeConfig?.path) return true;
      this.router.navigate(['login']);
      return false;
    }
    const PERMISSIONS = JSON.parse(data?.PERMISSIONS || "{}");

    // If user tries to access login page while logged in, redirect to homepage
    if (url === 'login' || !route.routeConfig?.path) {
      this.router.navigate([data.HOMEPAGE]);
      return false;
    }

    // Check if the user has permission for this route
    if (!PERMISSIONS[url]) {
      this.router.navigate(['404-page']);
      return false;
    }

    return true;
  }

}
