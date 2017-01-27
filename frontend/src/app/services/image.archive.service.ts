import { Injectable } from '@angular/core';
import { Http, Response, Headers, URLSearchParams } from '@angular/http';
import { Observable } from 'rxjs';
import 'rxjs/add/operator/map'
import { isNullOrUndefined } from "util";
import { FileUploader } from "ng2-file-upload";

@Injectable()
export class ImageArchiveService {

    public token: string;
    public modifier: string;
    public body: any;

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
     * GET STANDARD REQUEST DATA
     * ---
     * @author MS
     * @returns {{}}
     */
    getStandardRequestData(): any {
        var user = JSON.parse(localStorage.getItem('currentUser'));
        var data = {};
        if (!isNullOrUndefined(user) && 'token' in user && user.token) {
            data = {
                search: new URLSearchParams(
                    'modifier=' + localStorage.getItem('modifier') + '&' +
                    'token=' + user.token
                ),
            };
        } else {
            data = {
                search: new URLSearchParams(
                    'modifier=' + localStorage.getItem('modifier')
                )
            };
        }
        return data;
    }

    /**
     * DOWNLOAD ALL
     * ---
     * @author MS
     * @returns {Observable<R>}
     */
    downloadAll() {

        let data = this.getStandardRequestData();

        return this.http
            .get('/api/images/download', data)
            .map((response: Response) => {

                var body = response.json();

                if (body) {
                    this.body = body;
                    return true;
                }
                return false;
            });
    }

    /**
     * DOWNLOAD ARRAY OF FILES
     * ---
     * @param {{}} filenames
     * @returns {Observable<R>}
     */
    downloadArrayOfFiles(filenames: {[index: number] : string}) {

        let data = this.getStandardRequestData();
        data['filenames'] = JSON.stringify(filenames);

        return this.http.post(
            '/api/images/download',
            data
        ).map((response: Response) => {
            var body = response.json();

            if (body) {
                this.body = body;
                return true;
            }
            return false;
        });
    }

    /**
     * DOWNLOAD SINGLE FILE
     * ---
     * @param {string} filename
     * @author MS
     * @returns {Observable<R>}
     */
    downloadSingle(filename) {
        let data = this.getStandardRequestData();
        return this.http
            .get('/api/images/' + filename + '/download', data)
            .map((response: Response) => {

                var body = response.json();

                if (body) {
                    this.body = body;
                    return true;
                }
                return false;
            });
    }

    /**
     * GET MODIFIER
     * ---
     * GET request for new modifier string
     * @author MS
     * @returns {Observable<R>}
     */
    getImages() {

        let data = this.getStandardRequestData();
        return this.http.get('/api/images', data).map((response: Response) => {

            var body = response.json();

            if (body) {
                this.body = body;
                return true;
            }
            return false;
        });
    }

    /**
     * DELETE FILE REQUEST
     * ---
     * @param {string} filename
     * @author MS
     * @returns {Observable<R>}
     */
    deleteFileRequest(filename: string) {

        let data = this.getStandardRequestData();
        return this.http.delete(
            '/api/images/' + filename,
            data
        ).map((response: Response) => {
            var body = response.json();
            if (body) {
                this.body = body;
                return true;
            }
            return false;
        });
    }

    /**
     * DELETE FILE
     * ---
     * @param {string} filename
     * @author MS
     */
    deleteFile(filename: string) {

        this.deleteFileRequest(filename).subscribe(result => {
            if (result === true) {
                return true;
            }
        });
    }

    /**
     * DELETE ALL IMAGES REQUEST
     * ---
     * @author MS
     * @returns {Observable<R>}
     */
    deleteAllImagesRequest() {
        let data = this.getStandardRequestData();
        return this.http.get(
            '/api/images/delete-all',
            data
        ).map((response: Response) => {
            var body = response.json();
            if (body) {
                this.body = body;
                return true;
            }
            return false;
        });
    }

    /**
     * DELETE ALL IMAGES
     * ---
     * @author MS
     */
    deleteAllImages() {
        this.deleteAllImagesRequest().subscribe(result => {
            if (result === true) {
                return true;
            }
        });
    }

    /**
     * UPDATE IMAGE ARCHIVE
     * ---
     * Set ModifierService property modifier_code with the modifier, if not
     * already set
     * @author MS
     */
    updateImageArchive(
        uploader: FileUploader,
        loaded_images: { [index: number]: string }
    ) {
        if (isNullOrUndefined(this.body)) {
            this.getImages().subscribe(result => {
                if (result === true) {

                    this.addFilesToUploader(uploader);
                    this.loadImageLocations(loaded_images);
                    this.setImagesState(uploader);
                }
            });
        }
    }

    /**
     * ADD FILES TO UPLOADER
     * ---
     * Add images to Image queue on the FileUploader object
     * @param {FileUploader} uploader
     * @author MS
     */
    private addFilesToUploader(uploader: FileUploader) {
        for (var filename in this.body.body.image_list) {
            var file: File = new File([''], filename);
            uploader.addToQueue([file], {});
        }
    }

    /**
     * LOAD IMAGE LOCATIONS
     * ---
     * load each images public url into the ref loaded_images property
     * @param {any} loaded_images
     * @author MS
     */
    private loadImageLocations(loaded_images: { [index: number]: string }) {
        var count: number = 0;
        for (var filename in this.body.body.image_list) {
            loaded_images[count.toString()] = this.body
                .body.image_list[filename]['url'];
            count++;
        }
    }

    /**
     * SET IMAGES STATE
     * ---
     * Cycle through the uploader queue and set each of the images, already
     * stored in the archive, to required states as if already uploaded
     * @param {FileUploader} uploader
     * @author MS
     */
    private setImagesState(uploader: FileUploader) {

        for (let item of uploader.queue) {
            item.isUploaded = true;
            item.isSuccess = true;
            item.file.size = this.body
                .body.image_list[item.file.name]['file_size'];
        }
    }
}
