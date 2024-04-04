<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.user');
    }

    public function UserList(Request $request)
    {
        $name = $request->name;
        $email = $request->email;

        $users = User::with('role:id,role_name')
                       ->orderBy('name', 'ASC');

        if ($name != '') {
            $users->where('name', 'LIKE', '%'.$name.'%');
        }

        if ($email != '') {
            $users->where('email', 'LIKE', '%'.$email.'%');
        }

        $users = $users->paginate(10);

        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required',
        ]);

        try {
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
            $user->role_id = $request->role;
            $user->branch_id = 1;
            $user->save();

            return response()->json(['status' => 'success', 'message' => 'Usuario Criado']);
        } catch (\Exception $e) {
            // return $e;
            return response()->json(['status' => 'error', 'message' => 'Algo deu ruim!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $user = User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'role' => 'required',
        ]);

        try {
            $user = User::find($id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_id = $request->role;
            $user->update();

            return response()->json(['status' => 'success', 'message' => 'Usuario atualizado']);
        } catch (\Exception $e) {
            // return $e;
            return response()->json(['status' => 'error', 'message' => 'Algo deu ruim!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();

            return response()->json(['status' => 'success', 'message' => 'Usuario Apagado']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Algo deu ruim!']);
        }
    }
}
