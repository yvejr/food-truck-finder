import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";

import {Status} from "../classes/status";
import {Truck} from "../classes/truck";
import {Category} from "../classes/category";
import {Observable} from "rxjs/Observable";
import {TruckReply} from "../classes/truck.reply";

@Injectable()
export class TruckService {

    constructor(protected http: HttpClient) {

    }

    //define the API endpoint
    private truckUrl = "api/truck/";
    private categoryUrl = "api/category/";


    //reach out to the Truck API and create the truck (POST)
    createTruck(truck : Truck) : Observable<Status> {
        return(this.http.post<Status>(this.truckUrl, truck));

    }

    // call to the Truck API to grab the truck in question and edit it (PUT)
    editTruck(truck : Truck) : Observable<Status> {
        return(this.http.put<Status>(this.truckUrl + truck.truckId, truck));
    }

    //reach out to the Truck API and delete the truck in question (DELETE)
    deleteTruck(truckId : string) : Observable<Status> {
        return(this.http.delete<Status>(this.truckUrl + truckId));
    }

    // call to the Truck API and get a truck object by its truck id (GET specific truck)
    getTruck(id : string) : Observable<TruckReply> {
        return(this.http.get<TruckReply>(this.truckUrl + id));
    }

    // call to the Truck API and get truck objects by a profile id (GET array of trucks)
    getTruckByTruckProfileId(truckProfileId : string) : Observable<Truck[]> {
        return(this.http.get<Truck[]>(this.truckUrl + "?truckProfileId=" + truckProfileId));
    }

    getTruckByTruckName(truckName : string) : Observable<Truck[]> {
        return(this.http.get<Truck[]>(this.truckUrl + "?truckName=" + truckName))
    }

    // call to the Truck API and get truck objects by isOpen (GET array of trucks)
    getTruckByTruckIsOpen() : Observable<Truck[]> {
        return(this.http.get<Truck[]>(this.truckUrl));

    }

    // call to the Truck API and grab the corresponding trucks and categories by categoryId (GET array)
    getCategoriesAndTrucksByCategoryId(truckCategories: number[]) : Observable<Truck[]> {
        return((this.http.get<any> (this.truckUrl + "?truckCategories=" + truckCategories)));
            // (this.http.get<Category[]> (this.categoryUrl + "?categoryId=" + categoryId)));
    }

}