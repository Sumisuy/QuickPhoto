import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Observable } from 'rxjs';
import 'rxjs/add/operator/map'
import {isNullOrUndefined} from "util";

@Injectable()
export class ModifierService {

    public modifier_code: string;

    /**
     * CONSTRUCTOR
     * ---
     * @param {Http} http
     * @author MS
     */
    constructor(private http: Http) {

        if (localStorage.hasOwnProperty('modifier')) {
            this.modifier_code = localStorage.getItem('modifier');
        }
    }

    /**
     * GET MODIFIER
     * ---
     * GET request for new modifier string
     * @author MS
     * @returns {Observable<R>}
     */
    getModifier() {

        return this.http.get('/api/modifier').map((response: Response) => {

            let modifier = response.json() && response.json().modifier;

            if (modifier) {
                this.modifier_code = modifier;
                return true;
            }
            return false;
        });
    }

    /**
     * SET CODE
     * ---
     * Set ModifierService property modifier_code with the modifier, if not
     * already set
     * @author MS
     */
    ensureModifier() {
        if (isNullOrUndefined(this.modifier_code)) {
            this.getModifier().subscribe(result => {
                if (result === true) {
                    localStorage.setItem('modifier', this.modifier_code);
                }
            });
        }
    }
}
