<?php

namespace App\Http\Controllers;

use App\Models\Clubes;
use Illuminate\Http\Request;

class ClubesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clubs = Clubes::all();
        return view('inicio', compact('clubs'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        print_r($_POST);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clubes  $clubes
     * @return \Illuminate\Http\Response
     */
    public function show(Clubes $clubes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clubes  $clubes
     * @return \Illuminate\Http\Response
     */
    public function edit(Clubes $clubes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clubes  $clubes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clubes $clubes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clubes  $clubes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clubes $clubes)
    {
        //
    }
}
