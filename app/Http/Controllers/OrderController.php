<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
       if (Gate::allows('isAdmin') || Gate::allows('isHr')) {
            return ok("Permission granted");
       }
       else
            return ok("Only Admin Can Access");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (Gate::allows('isAdmin') || Gate::allows('isHr')) {
            return ok("Permission granted");
       }
       else
            return ok("Only Admin Can Access");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        if (Gate::allows('isAdmin') || Gate::allows('isHr') ||  Gate::allows('isEmployee')) {
            return ok("Permission granted");
       }
       else
            return ok("Only Admin Can Access");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            return ok("Permission granted");
       }
       else
            return ok("Only Admin Can Access");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        if (Gate::allows('isAdmin')) {
            return ok("Permission granted");
       }
       else
            return ok("Only Admin Can Access");
    }
}

