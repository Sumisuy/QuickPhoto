import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';

@Injectable()
export class AuthGuard implements CanActivate {

    constructor( private router: Router ) { }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean  {

        localStorage.setItem('desiredUrl', state.url);

        if (localStorage.getItem('currentUser')) {
            return true;
        }
        this.router.navigate(['/login']);
        return false;
    }
}