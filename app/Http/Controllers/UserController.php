<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $user = User::all(['id','name']);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'password' => 'required|min:6'
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        $user = User::create($formFields);

        return response()->json([
            'message'=>'Dodano użytkownika!'
        ]);
    }


    public function update(Request $request, User $user)
    {

        $formFields = $request->validate([
            'name' => 'required',
            'password' => 'nullable'
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        //check if its work
        $user->update($formFields);

        return response()->json([
            'message'=>'Zaktualizowano użytkownika!'
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message'=>'Usunięto użytkownika!'
        ]);
    }

    public function authenticate(Request $request) {
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

    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
