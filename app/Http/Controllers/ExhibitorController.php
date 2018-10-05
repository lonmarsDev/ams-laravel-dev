<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Image;
use Hash;
use Validator;
use Log;

class ExhibitorController extends MyBaseController
{
    /**
     * Show the select Exhibitor page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showSelectExhibitor()
    {
        return view('ManageExhibitor.SelectExhibitor');
    }

    /**
     * Show the create Exhibitor page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showCreateExhibitor($event_id)
    {
        $event = Event::where('id',$event_id)->first(); 
        return view('ManageExhibitor.Modals.CreateExhibitor',['event'=>$event]);
    }

    /**
     * Create the Exhibitor
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function postCreateExhibitor(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email',
        ];
        $messages=[];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $event = Event::where('id',$request->event_id)->first(); 
        
        $pass=$this->randomString();
        $user = new User();
        $user->password = Hash::make($pass);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->role = 1;
        $user->account_id = $event->account_id;
        
        try {
            $user->save();
            //send mail to user after saved
            session()->flash('message', 'Exhibitor created successfully.');
            return response()->json([
                'status'   => 'success',
                'messages' => 'Exhibitor created successfully.',
            ]);
        } catch (\Exception $e) {
            return ($e);

            return response()->json([
                'status'   => 'error',
                'messages' => 'Whoops! There was a problem creating your exhibitor. Please try again.',
            ]);
        }

        
    }

    public function randomString(){
        $str= '';
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i=0; $i < 8 ; $i++) { 
            $str.=substr($chars, rand(0,60),1);
        }
        return $str;
    }
}
