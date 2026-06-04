<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEvent;
use App\Http\Requests\UpdateEvent;
use App\Http\Resources\EventResource;
use App\Models\Category;
use App\Models\Event;
use App\Service\ImageService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //to inject service
    protected $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }


    public function index(){
        //get events
        $events =Event::with('category')->get();  //n+1            interview: eigger loading
                   // category return to method in model Event (belong)
                   //try to delete    with('category')->

        //check
        if($events->isEmpty()){
            return response()->json([
                 "success" => false,
                 "message" => "No events found",
            ],404);
        }

        //response
        return response()->json([
             "success" => true,
              "data" => EventResource::collection($events)
        ],200);
    }

    public function show($id){
        // check if id is missing
    if (empty($id)) {
        return response()->json([
            "success" => false,
            "message" => "id is required",
        ], 400); // Bad Request
    }

       //get one event
      $event =Event::find($id);



       //check
        if(!$event){
            return response()->json([
                "success" => false,
                "message" => "this event not found",
            ],404);
        }



        //response
          return response()->json([
                "success" => true,
                "data"  => new EventResource($event),
            ],200);
        }

        public function store(CreateEvent $request){
            //validate finish

            //check if this category found or no
            $category =Category::find($request->category_id);

            if(!$category){
            return response()->json([
                "success" => false,
                "message" => " Category id not found"
            ],404);
        }

            //create
            $event= Event::create([
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'date' => $request->date,
                'avalible_seats' => $request->AvalibleSeats,
                'category_id' => $request->category_id

            ]);

            //check image
            if($request->hasFile('images')){
                foreach($request->file('images') as $file){
                      $this->imageService->createFile($event, $file, "event_images");
                }
            }

            //response
             return response()->json([
             "success" => true,
             "message" => "Event created successfully",
             "event"  => new EventResource($event)      //optional
        ],201);

        }


        public function update(UpdateEvent $request, $id){

        //find
        $event = Event::find($id);

        //check
        if(!$event){
            return response()->json([
                "success" => false,
                "message" => "No event found"
            ],404);
        }


        if(isset($request->category_id) && !empty($request->category_id)){
           //check if this category found or no
            $category =Category::find($request->category_id);

            if(!$category){
            return response()->json([
                "success" => false,
                "message" => " Category id not found"
            ],404);
        }

    }

        //update
        $event->update([
            'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'date' => $request->date,
                'avalible_seats' => $request->AvalibleSeats,
                'category_id' => $request->category_id
        ]);
             $clear=true;
             if($request->hasFile('images')){
                foreach($request->file('images') as $file){
                      $this->imageService->updateFile($event, $file, "event_images", $clear);
                      $clear =false;
                }
            }

        //response
        return response()->json([
                "success" => true,
                "message" => "event updated successfully",
                "event"  => new EventResource($event)
            ],404);
        }

         public function destroy($id){
        //find
        $event = Event::find($id);

        //check
        if(!$event){
            return response()->json([
                "success" => false,
                
                "message" => "No event found"
            ],404);
        }

          if($event->getMedia("event_images")->isNotEmpty()){

                      $this->imageService->deleteFile($event,  "event_images");

            }


        //delete
        $event->delete();

        //response
        return response()->json([
                "success" => true,
                "message" => "event deleted successfully",
            ],404);
       }
    }

