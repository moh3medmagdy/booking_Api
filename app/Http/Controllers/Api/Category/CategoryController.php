<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategory;
use App\Http\Requests\UpdateCategory;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //crud methods will be here

    //index All
    public function index(){
        //get categories
        $category = Category::get();

        //check
        if($category->isEmpty()){
            return response()->json([
                "success" => false,
                "message" => "No categories found"
            ],404);
        }

        //response
        return response()->json([
            "success" => true,
            "data" => CategoryResource::collection($category)
        ],200);
    }

    //show one
    public function show($id){
        //get category
        $category = Category::find($id);       //findOrFail
        // $category = Category::where('id',$id)->first();

        //check
        if(!$category){
            return response()->json([
                "success" => false,
                "message" => "No category found"
            ],404);
        }

        //response
        return response()->json([
            "success" => true,
            "data" => new CategoryResource($category)
        ],200);
    }


     //create category
    public function store(CreateCategory $request){
        //validate
        // $request->validate(); // not needed as we are using FormRequest

        //create
        $category = Category::create([
            'title' => $request->title,
        ]);

        //response
        return response()->json([
             "success" => true,
             "message" => "category created successfully",
        ],201);
    }


    //update category
    public function update(UpdateCategory $request, $id){

        //find
        $category = Category::find($id);

        //check
        if(!$category){
            return response()->json([
                "success" => false,
                "message" => "No category found"
            ],404);
        }

        //update
        $category->update([
            "title" => $request->title,
        ]);

        //response
        return response()->json([
                "success" => true,
                "message" => "category updated successfully",
            ],404);
}

       //Delete category
       public function destroy($id){
        //find
        $category = Category::find($id);

        //check
        if(!$category){
            return response()->json([
                "success" => false,
                "message" => "No category found"
            ],404);
        }

        //delete
        $category->delete();

        //response
        return response()->json([
                "success" => true,
                "message" => "category deleted successfully",
            ],404);
       }


}
