<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index() {
        $news = News::all(['id','title','text']);
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $news = News::create($request->post());
        return response()->json([
            'message'=>'Dodano nowy wpis!',
            'news'=>$news
        ]);
    }

    public function show(News $news)
    {
        return response()->json($news);
    }

    public function update(Request $request, News $news)
    {
        $news->fill($request->post())->save();
        return response()->json([
            'message'=>'Zaktualizowano wpis!',
            'news'=>$news
        ]);
    }

    public function destroy(News $news)
    {
        $news->delete();
        return response()->json([
            'message'=>'Usunięto wpis!'
        ]);
    }
}
