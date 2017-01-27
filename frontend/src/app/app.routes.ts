import { Routes } from '@angular/router';

import { LoginPanel } from './components/login/index';
import { AccountDashboard } from './components/account/index';
import { AuthGuard } from './guards/index';
import { LandingPage } from './components/landing/index'

export const routes: Routes = [
    { path: '', component: LandingPage },
    { path: 'login', component: LoginPanel },
//    { path: 'account', component: AccountDashboard, canActivate: [AuthGuard] },
    { path: '**', redirectTo: '' },
];