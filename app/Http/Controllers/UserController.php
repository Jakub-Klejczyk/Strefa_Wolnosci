<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index()
    {
        $user = new UserCollection(User::all());
        return response($user);
    }

    public function store(UserCreateRequest $request)
    {

        $request['password'] = bcrypt($request['password']);

        User::create($request->validate($request->rules()));

        return ['message'=>'Dodano użytkownika!'];
    }


    public function update(UserUpdateRequest $request, User $user)
    {
        $request['password'] = bcrypt($request['password']);

        $user->update($request->validate($request->rules()));

        return ['message'=>'Zaktualizowano użytkownika!'];
    }

    public function destroy(User $user)
    {
        $user->delete();
        return ['message'=>'Usunięto użytkownika!'];
    }

    //dodać customowe request do log in i log out(?) przy tworzeniu strony logowania
    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)) {
            $request->session()->regenerate();

            // return redirect('/')->with('message', 'User logged in!');
        }

        return back()->withErrors(['message' => 'Niepoprawne dane logowania.']);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
