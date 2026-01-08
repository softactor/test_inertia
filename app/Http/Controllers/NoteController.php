<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoteController extends Controller
{
    
    public function index()
    {
        $notes = Note::get();
        return Inertia::render('Note/Index', [
            'notes' => $notes
        ]);
    }
    
    public function create()
    {
        return Inertia::render('Note/Create');
    }   


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        Note::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('note.create')->with('success', 'Note Created!');

    }

}
