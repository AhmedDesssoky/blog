<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBlogRequest;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    // Get aLl Blogs
    public function index(){

        $blogs = Blog::latest()->paginate(1);
        if($blogs){
            if($blogs->total() > $blogs->perPage()){
                $data = [
                    'records' => BlogResource::collection($blogs),
                    'pagination Links' =>[
                        'current Page' => $blogs->currentPage(),
                        'per Page' => $blogs->perPage(),
                        'links' =>[
                            'fist page' => $blogs->url(1),
                            'last page' => $blogs->url($blogs->lastPage()),
                        ]
                    ]


                ];


            }else{
                $data = BlogResource::collection($blogs);

            }
            return ApiResponse::sendResponse(200, 'Blogs Retrieved Successfully',$data);

        }
          return ApiResponse::sendResponse(200, 'Blogs Is Empty',[]);


    }
    // Get Latest Blogs
    public function latest(){
        $blogs = Blog::latest()->take(2)->get();
        if($blogs){
            return ApiResponse::sendResponse(200,'Latest Blogs Retrieved Successfully',$blogs);
        }
        return ApiResponse::sendResponse(200,'Latest Blogs is Empty',[]);
    }
    //  Search Blogs
    public function search (Request $request){

        $word = $request->has('search') ? $request->input('search') : null;
        $blogs = Blog::when($word != null, function($q) use ($word){
            $q->where('name','like','%'.$word.'%');
        })->latest()->get();


        if($blogs){
            return ApiResponse::sendResponse(200, 'Search Completed',$blogs);
        }
        return ApiResponse::sendResponse(200, 'No matching data',[]);
    }
    // Create Blog
    public function create (StoreBlogRequest $request){
        $data = $request->validated();
        // image uploading
        // 1- get image
        $image = $request->image;
        // 2- change it's current name
        $newImageName = time() . '-' . $image->getClientOriginalName();
        // 3- move image to my project
        $image->storeAs('blogs', $newImageName, 'public');
        // 4- save new name to database record
        $data['image'] = $newImageName;
        $data['user_id'] = Auth::user()->id;

        $record = Blog::create($data);
        if($record){
            return ApiResponse::sendResponse(201,'Blog Created Successfully',new BlogResource($record));
        }

    }
    // Update Blog
    public function update(StoreBlogRequest $request ,$blogId){
        $blog = Blog::findOrFail($blogId);
        if($blog->user_id != $request->user()->id){
            return ApiResponse::sendResponse(403,"Your aren't take this action",[]);

        }
        $data= $request->validated();
        if ($request->hasFile('image')) {
                // image uploading
                // 0- delete old image
                Storage::delete("public/blogs/$blog->image");
                // 1- get image
                $image = $request->image;
                // 2- change it's current name
                $newImageName = time() . '-' . $image->getClientOriginalName();
                // 3- move image to my project
                $image->storeAs('blogs', $newImageName, 'public');
                // 4- save new name to database record
                $data['image'] = $newImageName;
        }
        $updating = $blog->update($data);
        if($updating){
            return ApiResponse::sendResponse(201,'Blog Update Successfully',new BlogResource($blog));
        }
    }
    // Delete Blog
    public function delete(Request $request, $blogId)
    {
        $blog = Blog::findOrFail($blogId);
        if($blog->user_id != $request->user()->id){
            return ApiResponse::sendResponse(403,'You aren\'t take this action',[]);
        }
        Storage::delete("public/blogs/$blog->image");
        $deleting = $blog->delete();
        if($deleting){
            return ApiResponse::sendResponse(200,'Blog Deleted Successfully',[]);
        }
    }
    // My Blogs
    public function MyBlogs(Request $request){
        $myBlogs= Blog::where('user_id',$request->user()->id)->latest()->get();
        if($myBlogs){
            return ApiResponse::sendResponse(200,'My Blogs Retrieved Successfully',BlogResource::collection($myBlogs));
        }
        return ApiResponse::sendResponse(200,'You don\'t have any blog',[]);

    }



}
