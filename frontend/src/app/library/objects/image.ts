import { IImage } from '../interfaces/i.image';

export class Image {

  private _hasLocation: boolean;
  private _hasWidth: boolean;
  private _hasHeight: boolean;
  private _scale: boolean;

  private _image: IImage;
  private _path = '../../../assets/images/';
  private _url = '/assets/images/';

  /**
   * CONSTRUCTOR
   * ---
   * @param {string} href
   * @param {number} width
   * @param {number} height
   * @author MS
   */
  constructor( href: string, width: number = null, height: number = null ) {

    let rel_path = this._setLocation(href);
    let file_path = this._path + rel_path;

    href = this._url + rel_path;
    width = this._setWidth(width);
    height = this._setHeight(height);

    this._image = { file_path, href, width, height }
  }

  /**
   * CHANGE IMAGE
   * ---
   * @param {string} href
   * @param {number} width
   * @param {number} height
   * @author MS
   */
  changeImage( href: string, width: number = null, height: number = null ) {
    this.constructor( href, width, height );
  }

  /**
   * [GET] HTML
   * ---
   * Builds and outputs a HTML string of the <img> tag, with source and any
   * dimensions that have been set
   * @author MS
   * @returns {string}
   */
  getHtml(): string {
    let image = '<img src="' + this.details.href + '" ';
    if (this.hasWidth) {
      image += 'width="' + this.details.width + '" ';
    }
    if (this.hasHeight) {
      image += 'height="' + this.details.height + '" ';
    }
    image += '>';
    return image;
  }

  /**
   * GET BACKGROUND
   * ---
   * @author MS
   * @returns {string}
   */
  styleBackgroundImage(): string {
    let image = 'url(\'' + this.details.href + '\')';
    if (this._scale) {
      image += ' no-repeat center center / cover fixed';
    }
    return image;
  }

  /**
   * SET SCALE TO FIT
   * ---
   * @param {boolean} bool
   * @author MS
   */
  setScaleToFit(bool) {
    this._scale = bool;
  }

  /**
   * [GET] DETAILS
   * ---
   * Returns the images details, as outlined by the IImage interface
   * @author MS
   * @return {IImage}
   */
  get details(): IImage {
    return this._image;
  }

  /**
   * [GET] HAS LOCATION
   * ---
   * @author MS
   * @returns {boolean}
   */
  get hasLocation(): boolean {
    return this._hasLocation;
  }

  /**
   * [GET] HAS WIDTH
   * ---
   * @author MS
   * @returns {boolean}
   */
  get hasWidth(): boolean {
    return this._hasWidth;
  }

  /**
   * [GET] HAS HEIGHT
   * ---
   * @author MS
   * @return {boolean}
   */
  get hasHeight(): boolean {
    return this._hasHeight;
  }

  /**
   * SET LOCATION
   * ---
   * @param href
   * @author MS
   * @return {string}
   */
  private _setLocation( href ): string {
    href = href || '';
    this._hasLocation = (href != '');
    return href;
  }

  /**
   * SET WIDTH
   * ---
   * @param width
   * @author MS
   * @return {number}
   */
  private _setWidth( width ): number {
    width = width || 0;
    this._hasWidth = (width != 0);
    return width;
  }

  /**
   * SET HEIGHT
   * ---
   * @param height
   * @author MS
   * @return {number}
   */
  private _setHeight( height ): number {
    height = height || 0;
    this._hasHeight = (height != 0);
    return height;
  }
}
