<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(){
        $subscribers = Subscriber::latest()->get();
        if($subscribers){
        return ApiResponse::sendResponse(200,'Subscribers Retrieved Successfully',SubscriberResource::collection($subscribers) );      
    } 
    return ApiResponse::sendResponse(200,'Subscribers is Empty',[] );      

    }
    public function store(SubscriberRequest $request){
        $data = $request->validated();
        $record= Subscriber::create($data);
        if($record){
            return ApiResponse::sendResponse(201,'Subscriber Created Successfully', 
            new SubscriberResource($record) );      
        }

    }
}
