<?php

namespace App\Http\Controllers;

use App\Socket;
use App\Table;
use Illuminate\Http\Request;

class SocketController extends Controller
{
    public function index() {
        $sockets = Socket::all();
        return view('socket', compact('sockets'));
    }

    public function config() {

    }


    public function turnOn($id_table = null, $time = null) {

        $socket = Table::where('id', $id_table)->first();

        if(isset($socket) && $socket->socket == 'socket1') {
            $socketPort = Socket::where('socket_id', 1)->first();
        }

        if(isset($socket) && $socket->socket == 'socket2') {
            $socketPort = Socket::where('socket_id', 2)->first();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://'.$socketPort->login.':'.$socketPort->pas.'@'. $socketPort->ip.':'.$socketPort->port.'/protect/status.xml');
        curl_close($ch);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://'.$socketPort->login . ':'.$socketPort->pas .'@'. $socketPort->ip.':'.$socketPort->port.'/protect/leds.cgi?led='.$socket->port.'&timeout=0');
        $output = curl_exec($ch);
        return redirect('/table')->with('status', 'Yes!');

    }

    public function edit(Request $request) {
        $orderCreate = Socket::find($request->id);
        $orderCreate->ip = $request->ip;
        $orderCreate->port = $request->port;
        $orderCreate->login = $request->login;
        $orderCreate->pas = $request->pas;
        $orderCreate->save();

        return redirect('/socket')->with('status', 'Edit!');
    }

}
