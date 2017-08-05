<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    /**
     * Checks the session
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response | string
     */
    public static function isSessionActive(Request $request){
        $client = new Client();
        $headers = ['X-CSRF-TOKEN' => $request->header('X-CSRF-TOKEN')];

        try {
            $checkSession = $client->post('http://api-signin/v1/session/check', compact('headers'))
                ->withHeader('Content-Type', 'application/form-data; charset=utf-8');

        } catch  (ClientException $e) {
            $responseCode = $e->getResponse()->getStatusCode();
            $responseMessage = $e->getResponse()->getBody();
            return response($responseMessage, $responseCode);
        }

        return response($responseMessage = $checkSession->getBody()->getContents()); // Sends userId
    }
}
