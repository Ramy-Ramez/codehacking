<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersEditRequestRequest as UsersEditRequestRequestAlias;
use App\Photo;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UsersRequest;
use App\Http\Requests\UsersEditRequest;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::lists('name', 'id')->all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
        //
        if (trim($request->password) == '') {//If the password field is empty
            $input = $request->except('password');//We gonna pass everything except the password field if it is empty
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($request->password);
        }
        //User::create($request->all());
        //$input = $request->all();
        //dd($request);

        //If there is a photo uploaded
        if ($file = $request->file('photo_id')) {
            //return 'Photo exists FROM ADMINUSERSCONTROLLER.PHP<br>';
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);
            $photo = Photo::create(['file' => $name]);//once we create a photo, then we have a photo_id available for us //file is the column name in photos table in database
            $input['photo_id'] = $photo->id;
        }

        //If there is no photo uploaded

        User::create($input);

        return redirect('/admin/users');
        //return $request->all();
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
        return view('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        $roles = Role::lists('name', 'id')->all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersEditRequest $request, $id)
    {
        //
        //return $request->all();
        $user = User::findOrFail($id);
        if (trim($request->password) == '') {//If the password field is empty
            $input = $request->except('password');//We gonna pass everything except the password field if it is empty
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($request->password);
        }
        $input = $request->all();
        if ($file = $request->file('photo_id')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);
            $photo = Photo::create(['file' => $name]);
            $input['photo_id'] = $photo->id;
        }
        $user->update($input);
        return redirect('/admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //return 'DESTROY';
        $user = User::findOrFail($id);
        unlink(public_path() . $user->photo->file);//Deleting the image of the deleted user
        $user->delete();
        Session::flash('deleted_user', 'The user has been DELETED');
        return redirect('/admin/users');
    }
}
