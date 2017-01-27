import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Observable } from 'rxjs';
import 'rxjs/add/operator/map'

@Injectable()
export class AuthenticationService {

    public token: string;
    public modifier: string;

    /**
     * CONSTRUCTOR
     * ---
     * @param {Http} http
     * @author MS
     */
    constructor(private http: Http) {

        var currentUser = JSON.parse(localStorage.getItem('currentUser'));
        this.token = currentUser && currentUser.token;
        this.modifier = currentUser && currentUser.modifier;
    }

    /**
     * LOGIN
     * ---
     * @param {string} username
     * @param {string} password
     * @author MS
     */
    login(username: string, password: string): Observable<boolean> {

        return this.http.post(
            '/api/auth/login',
            {
                email: username,
                password: password,
                modifier: localStorage.getItem('modifier')
            }
        ).map((response: Response) => {

            let token = response.json() && response.json().token;
            let modifier = response.json() && response.json().modifier;
            if (token) {
                this.token = token;
                this.modifier = modifier;
                localStorage.setItem(
                    'currentUser',
                    JSON.stringify({
                        username: username,
                        token: token,
                        modifier: modifier
                    })
                );
                return true;
            }
            return false;
        });
    }

    /**
     * REGISTER
     * ---
     * @param {string} username
     * @param {string} password
     * @author MS
     */
    register(username: string, password: string): Observable<boolean> {

        return this.http.post(
            '/api/auth/signup',
            {
                email: username,
                password: password,
                modifier: localStorage.getItem('modifier')
            }
        ).map((response: Response) => {

            let token = response.json() && response.json().token;
            let modifier = response.json() && response.json().modifier;
            if (token) {
                this.token = token;
                this.modifier = modifier;
                localStorage.setItem(
                    'currentUser',
                    JSON.stringify({
                        username: username,
                        token: token,
                        modifier: modifier
                    })
                );
                return true;
            }
            return false;
        });
    }

    /**
     * LOGOUT
     * ---
     * @author MS
     */
    logout(): void {

        this.token = null;
        localStorage.removeItem('currentUser');
    }
}
