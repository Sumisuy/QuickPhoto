import { Component, trigger, state, style, transition, animate, AfterViewInit } from '@angular/core';
import { Image } from "./library/objects/image";
import { Observable } from 'rxjs/Rx';

@Component({
    selector: 'app-root',
    templateUrl: 'app.component.html',
    animations: [
        trigger('backgroundFadeIn', [
            state('true', style({ opacity: 1 })),
            state('false', style({ opacity: 0 })),
            transition('0 => 1', animate('1000ms ease-out'))
        ]),
    ]
})

export class AppComponent implements AfterViewInit {

    public loaded: boolean = false;
    public background_image: Image;

    /**
     * CONSTRUCTOR
     * ---
     * @author MS
     */
    constructor() {
        let number = Math.floor(Math.random() * 13) + 1;
        var bg_image = new Image('backgrounds/'+number+'.jpg');
        bg_image.setScaleToFit(true);
        this.background_image = bg_image;

        Observable.interval(1000 * 60).subscribe(x => {
            this.changeBackground();
        });
    }

    /**
     * CHANGE BACKGROUND
     * ---
     * @author MS
     */
    changeBackground() {
        let number = Math.floor(Math.random() * 13) + 1;
        this.background_image.changeImage('backgrounds/'+number+'.jpg');
    }

    /**
     * SET BACKGROUND STYLES
     * ---
     * @author MS
     * @returns {any}
     */
    setBackgroundStyles() {
        return {
            'background': this.background_image.styleBackgroundImage(),
            'position': 'absolute',
            'top': '60px',
            'bottom': '0px',
            'left': '0px',
            'right': '0px',
            'opacity': '0.0'
        };
    }

    /**
     * IS LOADED
     * ---
     * @author MS
     * @returns {boolean}
     */
    isLoaded() {
        return this.loaded;
    }

    /**
     * NG AFTER VIEW INIT
     * ---
     * @author MS
     */
    ngAfterViewInit() {
        this.loaded = true;
    }
}
