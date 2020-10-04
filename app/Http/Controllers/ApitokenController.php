<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiToken;

class ApitokenController extends Controller
{

    protected $pageCount = 20;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $ApiTokens = ApiToken::orderBy('created_at', 'desc')
            ->paginate($this->pageCount);
        return view('apitokens.index', [
            'ApiTokens' => $ApiTokens,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apitokens.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'service' => 'required',
        ]);

        $newToken = str_random(32);

        $token = new ApiToken();
        $newToken = str_random(32);
        $token->service=$request->service;
        $token->token = bcrypt($newToken);
        $token->otoken = $newToken;
        $token->save();

        return redirect()->route('apitokens.index');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ApiToken = ApiToken::findOrFail($id);
        $ApiToken->delete();
        return redirect()->route('apitokens.index');
    }
}
