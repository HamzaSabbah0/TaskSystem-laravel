<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Admin::where('id','!=',auth('admin')->id())->get();
        return response()->view('cms.admins.index',['admins'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->view('cms.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator($request->all(),
        [
            'name' => 'required|string|min:3|max:30',
            'email' => 'required|email',
            'active'=>'required|boolean'],
        );
        if(!$validator->fails()){
            $admin = new Admin();
            $admin->name = $request->get('name');
            $admin->email = $request->get('email');
            $admin->active = $request->get('active');
            $isSaved = $admin->save();
            // Mail::to($admin->email)->send(new WelcomeEmail($admin));
            return response()->json([
                'message'=> $isSaved ? "Created Successfully" : "Faild!"],
                $isSaved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
        }else{
            return response()->json([
             'message' => $validator->getMessageBag()->first()
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
        return response()->view('cms.admins.edit',['admin'=>$admin]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
        $validator = Validator($request->all(),
    [   'name'=>'required|string|min:3|max:30',
        'email'=>'required|email',
        'active'=>'required|boolean'],
    );
    if(!$validator->fails()){
        $admin->name = $request->get('name');
        $admin->email = $request->get('email');
        $admin->active = $request->get('active');
        $isUpdated = $admin->save();
        return response()->json([
            'message'=> $isUpdated ? "Admin Updated Successfully"
        : "Faild to Update Admin"],$isUpdated ? Response::HTTP_CREATED :
          Response::HTTP_BAD_REQUEST );
    }else{
        return response()->json([
            'message'=> $validator->getMessageBag()->first()
        ],Response::HTTP_BAD_REQUEST);
    }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
        $isDeleted = $admin->delete();
        if($isDeleted){
            return response()->json(['title'=>'Success!','text'=>'Admin Deleted Successfully','icon'=>'success'
            ],Response::HTTP_OK);
        }else{
            return response()->json(['title'=>'Faild!','text'=>'Admin Delete Faild','icon'=>'error'
            ],Response::HTTP_BAD_REQUEST);
        }
    }
}
