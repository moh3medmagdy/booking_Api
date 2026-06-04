<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // create booking
    public function book(Request $request, $eventId){

        $userId  = Auth::id();           //already user logined
        $event   = Event::find($eventId);

        //check
        if(!$event){
            return response()->json([
                'success' => false,
                'message' => "Event Not Found"
            ],404);
        }

        //check
        $booking =Booking::where('user_id',$userId)->where('event_id',$event->id)->exists();

        if($booking){
            return response()->json([
                'success' => false,
                'message' => "you already booked this Event"
            ],400);
        }

      $booking = Booking::create([
          "event_id" => $event->id,
          "user_id"  => $userId,
          "status"   => $request->status ?? "pending",
      ]);

      $event->decrement('avalible_seats');

      return response()->json([
        "success" => true,
        "message" => "Event booked successfully",
        "book"    => new BookingResource($booking),
      ],201);
    }

    public function myBooking(){
          $userId  = Auth::id();
          $bookings = Booking::with('event',)->where('user_id',$userId)->get(); //n+1

           return response()->json([
             "success" => true,
              "bookings"    =>  BookingResource::collection($bookings),
      ],201);
     }

     //canceled

     public function cancel(Request $request, $id){

        $booking = Booking::find($id);

        if(!$booking){
            return response()->json([
               "success" => false,
               "message" => "booking not found",

             ],404);
        }

        if($booking->status == 'canceled'){
                return response()->json([
                   "success" => false,
                   "message" => "booking already canceled",

                      ],404);

        }

        $booking->update(['status' => 'canceled']);

        $booking->event->increment('avalible_seats');

        return response()->json([
               "success" => true,
               "message" => "booking canceled successfully",

             ],200);
     }
}

