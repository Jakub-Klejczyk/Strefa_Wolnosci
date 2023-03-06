<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\NewsCollection;
use App\Http\Requests\NewsCreateRequest;
use App\Http\Requests\NewsDeleteRequest;
use App\Http\Requests\NewsUpdateRequest;

class NewsController extends Controller
{
    public function index()
    {
        $news = new NewsCollection(News::all());
        return response($news);
    }

    public function store(Request $request)
    {
        $file = $request->file('img');

        return response()->json([
            'message' => $file
        ]);
        // $file_name = time(). '.' .$file->getCilentOriginalName();
        // $file->move(public_path('images'), $file_name); 

        // $request['img'] = $file_name;

        // News::create($request->validate($request->rules()));

        // return ['message'=>'Zaktualizowano wpis!'];
    }

    public function show(News $news)
    {
        //wgl nie działa, albo nie wiem co to robi -> do resource (imo powinno zwracać news where id = $id)
        return response()->json($news);
    }

    public function update(NewsUpdateRequest $request, News $news, JsonResponse $json)
    {
        $news->update($request->validate($request->rules()));
        return ['message'=>'Zaktualizowano wpis!', $json->content() ];
        
    }

    public function destroy(News $news)
    {
        $news->delete();
        return ['message'=>'Usunięto wpis!'];
    }
}
