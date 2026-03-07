<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class PublicClientController extends Controller
{
    public function viewByToken($token)
    {
        // Find the client by the share_token
        // firstOrFail() will throw a 404 if the token is invalid
        $client = Client::where('share_token', $token)->firstOrFail();

        // Return the view you want the client to see
        return view('client.view', compact('client'));
    }
}
