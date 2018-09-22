<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'type' => 'required',
            'password' => 'required|min:6'
       ]);

       $user = new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->bio = $request->bio;
       $user->type = $request->type;
       $user->image = $request->image;
       $user->password = Hash::make($request->password);
       $user->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    //profile...
    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'type' => 'required',
            'password' => 'sometimes|min:6'
        ]);
        // $user->update($request->all());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->type = $request->type;
        $user->image = $request->image;
        $user->password = Hash::make($request->password);
        $user->save();
    }


    public function profileUpdate(Request $request){

        $user = auth('api')->user();
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);
        $currentImage = $user->image;
        if($request->image != $currentImage){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            
            \Image::make($request->image)->save(public_path('img/profile/').$name);
            $request->merge(['image' => $name]);
        }
        $user->update($request->all());
        return ['message' => "Success"];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
