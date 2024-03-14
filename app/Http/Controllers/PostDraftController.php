<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateDraftPostRequest;
use App\Models\PostDraft;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostDraftController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        return response()->json([
            'data'=>$user->drafts()->get(),
        ]);
    }
    public function store(CreateOrUpdateDraftPostRequest $request){    
        $user = $request->user();
        $id = $request->input('id');
        $draft = PostDraft::find($id);
        if(!$draft) {
            $draft = PostDraft::create([
                'id' => $id,
                'name' => $request->name,
                'content' => $request->json('content'),
                'author_id' => $user->getKey(),
                'thumbnail' => $request->input('thumbnail'),
                'description' => $request->input('description'),
            ]);
            return response()->json([
                'status' => 200,
                'data' => $draft,
            ], 200);
        }
        $draft->update([
            'name' => $request->name,
            'content' => $request->json('content'),
            'thumbnail' => $request->input('thumbnail'),
            'description' => $request->input('description'),
        ]);
        return response()->json(['messge' => 'ok'],200);
    }
      
}
