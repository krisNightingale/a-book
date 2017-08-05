<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Validator;

class UsersController extends Controller
{
    /**
     * Getting a User by ID
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|string|\Symfony\Component\HttpFoundation\Response
     */
    public function getUserById(Request $request){
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $userId = $request->segment(count(request()->segments()));
        $user = User::find($userId);

        $header = [ 'Content-Type' => 'application/json; charset=utf-8' ];

        //Short user's representation. For everybody access.
        $userCondensed = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name
            ];

        return response()->json($userCondensed, 200, $header, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Getting current User's info
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|string|\Symfony\Component\HttpFoundation\Response
     */
    public function getCurrentUser(Request $request){
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $userId = SessionsController::isSessionActive($request)->getContent();
        $user = User::find($userId);

        $header = [ 'Content-Type' => 'application/json; charset=utf-8' ];

        return response()->json($user, 200, $header, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Updating current User's info
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function updateUserInfo(Request $request){
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:40',
            'last_name' =>  'required|max:40'
        ]);

        if ($validator->fails()){
            return response('Invalid params passed', 400);
        }

        $userId = SessionsController::isSessionActive($request)->getContent();
        $user = User::find($userId);

        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name')
        ]);

        return response("User's info is updated", 200);
    }
}
