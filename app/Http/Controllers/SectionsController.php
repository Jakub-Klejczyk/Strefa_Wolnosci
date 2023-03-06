<?php

namespace App\Http\Controllers;

use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\SectionsCollection;
use App\Http\Requests\SectionCreateRequest;
use App\Http\Requests\SectionUpdateRequest;

class SectionsController extends Controller
{
    public function index()
    {
        $sections = new SectionsCollection(Sections::all());
        return response($sections);
    }

    public function store(SectionCreateRequest $request)
    {
        Sections::create($request->validate($request->rules()));
        
        return ['message'=>'Dodano nową sekcję!'];
    }

    public function update(SectionUpdateRequest $request, Sections $section, JsonResponse $json)
    {
        $section->update($request->validate($request->rules()));
        return ['message'=>'Zaktualizowano sekcję!', $json->content() ];
        
    }

    public function destroy(Sections $section)
    {
        $section->delete();
        return [
            'message'=>'Usunięto sekcję!'
        ];
    }

}
