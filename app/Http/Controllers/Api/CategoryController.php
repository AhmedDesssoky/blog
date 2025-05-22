<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $categories = Category::get();
        if($categories){
            return ApiResponse::sendResponse(200, 'Categories Retrieved Successfully',CategoryResource::collection($categories));
        }
        return ApiResponse::sendResponse(200, 'Categories is empty',[]);
    }
}
