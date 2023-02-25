<?php

namespace App\Http\Controllers;

use App\Models\Sections;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    public function index() {
        $sections = Sections::all(['id','section','text']);
        return response()->json($sections);
    }

    public function store(Request $request)
    {
        $section = Sections::create($request->post());
        return response()->json([
            'message'=>'Dodano nową sekcję!',
            'section'=>$section
        ]);
    }

    public function update(Request $request, Sections $section)
    {
        $section->fill($request->post())->save();
        return response()->json([
            'message'=>'Zaktualizowano sekcję!',
            'section'=>$section
        ]);
    }

    public function destroy(Sections $section)
    {
        $section->delete();
        return response()->json([
            'message'=>'Usunięto sekcję!'
        ]);
    }

}
