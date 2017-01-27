import { Component } from '@angular/core';

import { AuthenticationService } from '../../services/index';
import { ModifierService } from "../../services/modifier.service";
import {LoginRegisterPanel} from "../../services/cc/login.register.panel";

@Component({
    selector: 'login-panel',
    moduleId: module.id,
    templateUrl: '../../resources/login/login.panel.html',
    styleUrls: [
        '../../../assets/css/layout/login.css'
    ]
})

export class LoginPanel {

    public model: any = {};
    public loading = false;
    public error = '';
    public logged_in: boolean = false;
    public login_tab_active = true;

    /**
     * CONSTRUCTOR
     * ---
     * @param {AuthenticationService} authenticationService
     * @param {ModifierService} modifierService
     * @param {LoginRegisterPanel} loginRegisterPanel
     * @author MS
     */
    constructor(
        private authenticationService: AuthenticationService,
        protected modifierService: ModifierService,
        public loginRegisterPanel: LoginRegisterPanel
    ) {
        if (this.authenticationService.token) {
            this.logged_in = true;
        }
    }

    /**
     * CLICKED CONTAINER
     * ---
     * @param event
     * @author MS
     */
    clickedOutsidePanel(event) {
        var element = document.getElementById('login-panel');
        if (event.target !== element && !element.contains(event.target)) {
            this.loginRegisterPanel.state = false;
            event.target.click();
        }
    }

    /**
     * SELECT LOGIN
     * ---
     * @author MS
     */
    selectLogin() {
        this.login_tab_active = true;
    }

    /**
     * SELECT REGISTER
     * ---
     * @author MS
     */
    selectRegister() {
        this.login_tab_active = false;
    }

    /**
     * LOGOUT
     * ---
     * @author MS
     */
    logout() {
        this.authenticationService.logout();
        window.location.reload();
    }

    /**
     * LOGIN
     * ---
     * @author MS
     */
    login() {

        this.loading = true;
        this.authenticationService
            .login(this.model.username, this.model.password)
            .subscribe(result => {
                if (result === true) {
                    this.loading = false;
                    window.location.reload();
                } else {
                    this.error = 'Username or password is incorrect';
                    this.loading = false;
                }
            });
    }

    /**
     * REGISTER
     * ---
     * @author MS
     */
    register() {

        this.loading = true;
        this.authenticationService
            .register(this.model.username, this.model.password)
            .subscribe(result => {
                if (result === true) {
                    this.loading = false;
                    window.location.reload();
                } else {
                    this.error = 'Error trying to register';
                    this.loading = false;
                }
            });
    }
}