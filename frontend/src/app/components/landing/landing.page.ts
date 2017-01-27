import { Component } from '@angular/core';
import { FileUploader } from 'ng2-file-upload';
import { NgbProgressbarConfig } from '@ng-bootstrap/ng-bootstrap';
import { ModifierService } from "../../services/modifier.service";
import { ImagesPanel } from "../../services/cc/images.panel";
import { ImageArchiveService } from "../../services/image.archive.service";

const URL = '/api/images';

@Component({
    moduleId: module.id,
    templateUrl: '../../resources/landing/landing.page.html',
    providers: [ NgbProgressbarConfig ],
    styleUrls: [
        '../../../assets/css/pages/landing.css'
    ]
})

export class LandingPage {

    public uploader:FileUploader;
    public hasBaseDropZoneOver: boolean = false;
    public selected_items: { [index: number]: any } = {};
    public loaded_images: { [index: number]: string } = {};
    public upload_details_states: { [index: number]: boolean } = {};
    public upload_click_states: { [index: number]: boolean } = {};
    public editing_in_progress: boolean = false;
    public editing_image_address: string;

    /**
     * MODIFIER SERVICE
     * ---
     * @param {ModifierService} modifierService
     * @param {ImagesPanel} imagePanel
     * @param {ImageArchiveService} imageArchiveService
     * @author MS
     */
    constructor(
        protected modifierService: ModifierService,
        public imagePanel: ImagesPanel,
        public imageArchiveService: ImageArchiveService
    ) {
        this.modifierService.ensureModifier();
        this.prepareUploader();
        this.prepareStateEvents();
        this.imageArchiveService.updateImageArchive(
            this.uploader,
            this.loaded_images
        );
    }

    /**
     * PREPARE UPLOADER
     * ---
     * Prepare uploader for making successful calls to the API
     * @author MS
     */
    prepareUploader() {
        var uploader_url = URL;
        if (localStorage.hasOwnProperty('currentUser')) {
            var user = JSON.parse(localStorage.getItem('currentUser'));
            uploader_url += '?token=' + user.token;
        }
        this.uploader = new FileUploader({url: uploader_url});
        this.uploader.onBuildItemForm = (item, form) => {
            form.append('modifier', localStorage.getItem('modifier'));
        };
    }

    /**
     * PREPARE STATE EVENTS
     * ---
     * Register logic to run on selected events against file uploader
     * @author MS
     */
    prepareStateEvents() {

        this.uploader.onSuccessItem = (item: any, response: any) => {

            response = JSON.parse(response);
            let index = this.uploader.getIndexOfItem(item);
            this.loaded_images[index] = response.body.path;
            this.upload_details_states[index] = false;
        };

        this.uploader.onAfterAddingFile = (fileItem: any) => {
            this.loaded_images[this.uploader.getIndexOfItem(fileItem)] = '';
        };
    }

    /**
     * SELECT IMAGE FOR EDITING
     * ---
     * The image of item at index will be chosen for editing
     * @param {number} item
     * @author MS
     */
    selectImageForEditing(item: any) {

        if (item.isSuccess) {
            var index = this.uploader.getIndexOfItem(item);
            this.editing_image_address = this.loaded_images[index];
            this.clickEvent(index);
        }
    }

    /**
     * CLICK EVENT
     * ---
     * When an image in list is clicked, update states
     * @param {number} index
     * @author MS
     */
    clickEvent(index: number) {
        if (this.upload_click_states[index]) {

            this.upload_click_states[index] = false;
            this.editing_in_progress = false;
        } else {
            for (var i in this.upload_click_states) {
                this.upload_click_states[i] = false;
            }
            this.upload_click_states[index] = true;
            this.editing_in_progress = true;
        }
    }

    /**
     * HOVER EVENT
     * ---
     * On item specific hover event, update hover state against items index.
     * @param {number} index
     * @author MS
     */
    hoverEvent(index: number) {
        for (var i in this.upload_details_states) {
            this.upload_details_states[i] = false;
        }
        this.upload_details_states[index] = true;
    }

    /**
     * HOVER OUT EVENT
     * ---
     * Undo everything we just did in hoverEvent()
     * @author MS
     */
    hoverOutEvent() {
        for (var i in this.upload_details_states) {
            this.upload_details_states[i] = false;
        }
    }

    /**
     * CLEAR ALL
     * ---
     * Clear whole queue, and dont forget to clear registered item details held
     * in arrays and objects.
     * @param {FileUploader} uploader
     * @author MS
     */
    clearAll(uploader: FileUploader) {
        uploader.clearQueue();
        this.selected_items = {};
        this.loaded_images = {};
        this.upload_details_states = {};
        this.upload_click_states = {};
        this.imageArchiveService.deleteAllImages();
    }

    /**
     * HAS NOTHING TO DOWNLOAD
     * ---
     * @author MS
     * @returns {boolean}
     */
    hasNothingToDownload() {
        for (let item of this.uploader.queue) {
            if (item.isSuccess) {
                return false;
            }
        }
        return true;
    }

    /**
     * LOAD SELECTED
     * ---
     * Iterate through selected items and load them to the server
     * @author MS
     */
    loadSelected() {
        for(var key in this.selected_items) {
            if (!this.selected_items[key].isSuccess) {
                this.selected_items[key].upload();
            }
        }
    }

    /**
     * SELECTED ALREADY LOADED
     * ---
     * @author MS
     * @returns {boolean}
     */
    selectedAlreadyLoaded() {
        for(var key in this.selected_items) {
            if (!this.selected_items[key].isSuccess) {
                return false;
            }
        }
        return true;
    }

    /**
     * DOWNLOAD ALL
     * ---
     * @author MS
     */
    downloadAll() {
        this.imageArchiveService.downloadAll().subscribe(result => {
            if (result === true) {
                window.location.href = this.imageArchiveService.body.body.url;
            }
        });
    }

    /**
     * DOWNLOAD SELECTED
     * ---
     * @author MS
     */
    downloadSelected() {
        var count = 0;
        var files = {};
        for(var key in this.selected_items) {
            files[count] = this.selected_items[key].file.name;
            count++;
        }
        if (count === 1) {
            this.downloadSingleImage(files[0]);
        }
        if (count > 1) {
            this.downloadArrayOfImages(files);
        }
    }

    /**
     * DOWNLOAD ARRAY OF IMAGES
     * ---
     * @param {{}} files
     * @author MS
     */
    downloadArrayOfImages(files) {
        this.imageArchiveService
            .downloadArrayOfFiles(files)
            .subscribe(result => {
                if (result === true) {
                    window.location.href =
                        this.imageArchiveService.body.body.url;
                }
            });
    }

    /**
     * DOWNLOAD SINGLE IMAGE
     * ---
     * @param {string} filename
     * @author MS
     */
    downloadSingleImage(filename) {
        this.imageArchiveService
            .downloadSingle(filename)
            .subscribe(result => {
                if (result === true) {
                    window.location.href =
                        this.imageArchiveService.body.body.url;
                }
            });
    }

    /**
     * HAS NO SELECTED ITEMS
     * ---
     * @author MS
     * @returns {boolean}
     */
    hasNoSelectedItems() {
        for(var key in this.selected_items) {
            return false;
        }
        return true;
    }

    /**
     * DELETE SELECTED
     * ---
     * Delete everything that has been registered under selected property, and
     * ensure that relevant arrays and objects reflect the changes and updated
     * indexes also.
     * @author MS
     */
    deleteSelected() {
        for(var key in this.selected_items) {

            let filename = this.selected_items[key].file.name;
            if (filename) {
                this.imageArchiveService.deleteFile(filename);
            }
            this.selected_items[key].remove();
            delete this.selected_items[key];

            if (key in this.loaded_images) {
                delete this.loaded_images[key];
            }
            if (key in this.upload_details_states) {
                delete this.upload_details_states[key];
            }
            if (
                key in this.upload_click_states &&
                this.upload_click_states[key] == true
            ) {
                this.upload_click_states[key] = false;
                this.editing_in_progress = false;
            }
        }
        this.loaded_images = this.resetNumericalIndexes(this.loaded_images);

        this.upload_details_states = this.resetNumericalIndexes(
            this.upload_details_states
        );
    }

    /**
     * RESET NUMERICAL INDEXES
     * ---
     * Just a helper function to reset object numerical incrementing indexes
     * @param {object} object
     * @author MS
     * @returns {object}
     */
    resetNumericalIndexes(object) {
        var output = {};
        var counter = 0;
        for (var index in object) {
            output[counter.toString()] = object[index];
            counter++;
        }
        return output
    }

    /**
     * TOGGLE SELECTED
     * ---
     * Add file item to selected array/object at items index
     * @param {event} event
     * @param {any} item
     * @param {number} i
     * @author MS
     */
    toggleSelected(event, item: any, i: number) {
        if(event.target.checked){
            this.selected_items[i] = item;
        } else {
            delete this.selected_items[i];
        }
    }

    /**
     * FILE OVER BASE
     * ---
     * @param {any} e
     * @author MS
     */
    public fileOverBase(e:any):void {
        this.hasBaseDropZoneOver = e;
        if (!this.imagePanel.state) {
            this.imagePanel.toggleState();
        }
    }
}

export class NgbdProgressbarConfig {
    constructor(config: NgbProgressbarConfig) {
        config.max = 100;
        config.striped = true;
        config.animated = true;
        config.type = 'success';
    }
}
