<?php

namespace App\Http\Controllers;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    //
    public function index(){
        return Tutorial::all();
    }
    public function show($id){
        $tutorial = Tutorial::with('comments')->where('id', $id)->first();
        if (!$tutorial) {
            return response()->json(['error'=>'data tidak ada'], 404);
        }else{
            return $tutorial;
        }
    }
    public function store(Request $request){
        $this->validate($request, [
            'title'  => 'required',
            'body'  => 'required'
        ]);
        $tutorial = $request->user()->tutorials()->create([
            'title'  => $request->json('title'),
            'slug'     => str_slug($request->json('title')),
            'body'  => $request->json('body'),
        ]);
        return $tutorial;
    }
    public function update(Request $request, $id){
        // die("hard");
        $this->validate($request, [
            'title'  => 'required',
            'body'  => 'required'
        ]);
        $tutorial = Tutorial::find($id);
        if ($request->user()->id != $tutorial->user_id) {
            return response()->json(['error' => 'tidak boleh mengedit tutorial ini'], 403);
        }
        $tutorial->title = $request->json('title');
        $tutorial->body = $request->json('body');
        $tutorial->save();
        return response()->json(['success' => 'data berhasil diedit '], 200);
    }
    public function destroy(Request $request, $id){
        $tutorial = Tutorial::find($id);
        //  mengecek user id
        if ($request->user()->id != $tutorial->user_id) {
            return response()->json(['error' => 'tidak boleh mengedit tutorial ini'], 403);
        }
        $tutorial->delete();
        return response()->json(['success' => 'data berhasil dihapus '], 200);
    }
}
