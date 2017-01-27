import { Injectable } from '@angular/core';

@Injectable()
export class LoginRegisterPanel {

    public state: boolean = false;

    toggleState() {
        this.state = (!this.state);
    }
}
