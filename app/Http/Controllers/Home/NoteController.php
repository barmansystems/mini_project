<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function index()
    {

        $notes = Note::where('user_id', auth()->id())->latest()->paginate(30);
        return view('notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        if (!$request->title && !$request->text){
            return response()->json(['title or text required']);
        }

        $data = [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'text' => $request->text,
        ];
        if (!$request->note_id) {
            $note = Note::create($data);
        } else {
            Note::find($request->note_id)->update($data);
            $note = Note::find($request->note_id);
        }

        // log

        return response()->json(['data' => true, 'id' => $note->id]);
    }

    public function delete(Request $request)
    {
        Note::find($request->note_id)->delete();
        // log
        return response()->json(['data' => true]);
    }



}
