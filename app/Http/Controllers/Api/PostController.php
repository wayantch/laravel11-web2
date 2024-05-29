<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        // Get all projects
        $projects = Project::latest()->paginate(5);

        // Return collection of posts as a resource
        return new PostResource(true, 'List Data Projects', $projects);
    }

    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'title'       => 'required',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Upload image
        $file = $request->file('image');
        $path = $file->storeAs('project-images', $file->hashName(), 'public');

        // Create post
        $post = Project::create([
            'title'       => $request->title,
            'image'       => $path,
            'description' => $request->description,
        ]);

        // Return response
        return new PostResource(true, 'Data Project Berhasil Ditambahkan!', $post);
    }

    public function show($id)
    {
        // Find project by ID
        $post = Project::find($id);

        // Return response
        return new PostResource(true, 'Detail Data Post', $post);
    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'title'       => 'required',
            'image'       => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find project by ID
        $post = Project::find($id);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image
            Storage::delete('public/' . $post->image);

            // Store new image
            $file = $request->file('image');
            $path = $file->storeAs('project-images', $file->hashName(), 'public');

            // Update post with new image
            $post->update([
                'title'       => $request->title,
                'image'       => $path,
                'description' => $request->description,
            ]);
        } else {
            // Update post without image
            $post->update([
                'title'       => $request->title,
                'description' => $request->description,
            ]);
        }

        // Return response
        return new PostResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    public function destroy($id)
    {

        //find post by ID
        $post = Project::find($id);

        //delete image
        Storage::delete('public/project-images/'.basename($post->image));

        //delete post
        $post->delete();

        //return response
        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}
