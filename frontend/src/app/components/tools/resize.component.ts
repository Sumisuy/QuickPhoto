import { Component } from '@angular/core';
import {ResizePanel} from "../../services/cc/resize.panel";

@Component({
    selector: 'tool-resize',
    templateUrl: '../../resources/tools/resize.component.html',
    styleUrls: [
        '../../../assets/css/tools/resize.css'
    ]
})

export class ResizeComponent {

    constructor(
        public resizePanel: ResizePanel
    ) {

    }

    clickOutsidePanel(event) {

    }
}
