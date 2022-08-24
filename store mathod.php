<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Faker\Core\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\fileExists;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::get();
        return view('backend.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $thumbnailname = null;
        if ($request->file('thumbnail')) {
            $extention = $request->file('thumbnail')->getClientOriginalExtension();
            $uniquename = uniqid().'.'.$extention;

            $request->file('thumbnail')->storeAs(
                'public/category',
                $uniquename
            );
            $thumbnailname = $uniquename;
        }

        $data = [
            'name' => $request->name,
            'slug' => $request->name,
            'user_id' => Auth::user()->id,
            'thumbnail' => $thumbnailname
        ];

        Category::create($data);
        Session::flash('create');
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::firstWhere('id',$id);
        return view('backend.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::firstWhere('id',$id);
        return view('backend.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $thumbnailname = null;
        if ($request->file('thumbnail')) {
            $extention = $request->file('thumbnail')->getClientOriginalExtension();
            $uniquename = uniqid().'.'.$extention;

            $request->file('thumbnail')->storeAs(
                'public/category',
                $uniquename
            );
            $thumbnailname = $uniquename;
        }


        if($thumbnailname){
            $data = [
                'name' => $request->name,
                'slug' => $request->name,
                'user_id' => Auth::user()->id,
                'thumbnail' => $thumbnailname
            ];

            $file = Category::firstwhere('id', $id)->thumbnail;
            if($file){
                Storage::disk('public')->delete('category/' . $file);
            }


            Category::firstwhere('id', $id)->update($data);
            Session::flash('update');
            return redirect()->route('category.index');
        }else{
            $data = [
                'name' => $request->name,
                'slug' => $request->name,
                'user_id' => Auth::user()->id
            ];
            Category::firstwhere('id', $id)->update($data);
            Session::flash('update');
            return redirect()->route('category.index');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = Category::firstwhere('id', $id)->thumbnail;
        if($file){
            Storage::disk('public')->delete('category/' . $file);
        }
        Category::firstwhere('id', $id)->delete();
        Session::flash('delete');
        return redirect()->route('category.index');
    }
}
