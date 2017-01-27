import { Component } from '@angular/core';
import { ImagesPanel } from "../../services/cc/images.panel";
import {LoginRegisterPanel} from "../../services/cc/login.register.panel";
import {ResizePanel} from "../../services/cc/resize.panel";

@Component({
    selector: 'app-header',
    templateUrl: '../../resources/layout/header.component.html',
    styleUrls: [
        '../../../assets/css/layout/header.css'
    ]
})

export class HeaderComponent {

    constructor(
        public imagePanel: ImagesPanel,
        public loginRegisterPanel: LoginRegisterPanel,
        public resizePanel: ResizePanel
    ) {

    }
}
