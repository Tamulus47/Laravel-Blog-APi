<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags, 200);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|unique:tags,name',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validation->errors(),
            ], 422);
        }

        $tag = Tag::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Tag ' . $tag->name . ' created successfully.',
        ], 200);
    }

    public function update(Request $request, Tag $tag)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|unique:tags,name,' . $tag->id,
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validation->errors(),
            ], 422);
        }

        $tagName = $tag->name;
        $tag->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Tag ' . $tagName . ' updated successfully.',
        ], 200);
    }

    public function destroy(Tag $tag)
    {
        $tagName = $tag->name;

        $tag->delete();

        return response()->json([
            'message' => 'Tag ' . $tagName . ' deleted successfully.',
        ], 200);
    }
}
