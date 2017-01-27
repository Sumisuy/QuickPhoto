import { Injectable } from '@angular/core';

@Injectable()
export class ImagesPanel {

    public state: boolean = false;

    toggleState() {
        this.state = (!this.state);
    }
}
