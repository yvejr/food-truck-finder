import {RouterModule, Routes} from "@angular/router";
import {SplashComponent} from "./splash/splash.component";
//todo this will be customized to our project
import {UserService} from "./shared/services/user.services";
import {APP_BASE_HREF} from "@angular/common";


export const allAppComponents = [SplashComponent];

export const routes: Routes = [
    {path: "", component: SplashComponent}
];

export const appRoutingProviders: any[] = [
    {provide: APP_BASE_HREF, useValue: window["_base_href"]},
    UserService
];

export const routing = RouterModule.forRoot(routes);