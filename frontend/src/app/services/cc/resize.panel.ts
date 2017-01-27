import { Injectable } from '@angular/core';

@Injectable()
export class ResizePanel {

    public state: boolean = false;

    toggleState() {
        this.state = (!this.state);
    }
}
