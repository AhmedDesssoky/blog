<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactResource;

class ContactController extends Controller
{
    public function index(){
        $contacts = Contact::get();
        if($contacts){
            return ApiResponse::sendResponse(200,'All Contacts Retrieved successfully',
            ContactResource::collection($contacts));
        }
        return ApiResponse::sendResponse(200,'Contacts is Empty',[]);
    }
    public function store(StoreContactRequest $request)
    {
        $data = $request->validated();
        $record =Contact::create($data);
        if($record){
            return ApiResponse::sendResponse(201,'Message Send Successfully',
            new ContactResource($record));
        }

    }
}
