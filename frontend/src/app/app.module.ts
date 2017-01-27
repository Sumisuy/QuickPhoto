import { NgModule }      from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule }    from '@angular/forms';
import { RouterModule } from '@angular/router';
import { HttpModule } from '@angular/http';
import { FileUploadModule } from "ng2-file-upload";
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { MockBackend, MockConnection } from '@angular/http/testing';
import { BaseRequestOptions } from '@angular/http';

import { AppComponent }  from './app.component';
import { routes } from './app.routes';

import { AuthGuard } from './guards/index';
import { AuthenticationService, ModifierService, ImageArchiveService } from './services/index';
import { ImagesPanel } from './services/cc/images.panel';
import { LoginRegisterPanel } from './services/cc/login.register.panel';
import { ResizePanel } from './services/cc/resize.panel';
import { LoginPanel } from './components/login/index';
import { AccountDashboard } from './components/account/index';
import { LandingPage } from './components/landing/index';
import { HeaderComponent } from './components/layout/header.component';
import { ResizeComponent } from './components/tools/resize.component';

@NgModule({
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    RouterModule.forRoot(routes),
    FileUploadModule,
    NgbModule.forRoot(),
  ],
  declarations: [
    AppComponent,
    LoginPanel,
    AccountDashboard,
    LandingPage,
    HeaderComponent,
    ResizeComponent,
  ],
  providers: [
    AuthGuard,
    AuthenticationService,
    ModifierService,
    ImageArchiveService,
    ImagesPanel,
    ResizePanel,
    LoginRegisterPanel,
    MockBackend,
    BaseRequestOptions,
  ],
  bootstrap: [AppComponent]
})

export class AppModule { }