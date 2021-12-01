<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tag;

class TagsController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags= Tag::latest()->paginate(25);
        //echo "<pre>";print_r($tags;die;
        return $tags;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tag_name' => 'required|string|max:191'
        ]);

        if($request->isMethod('post')){
            $tags = new Tag;
            $tags->tag_name=$request['tag_name'];
            //echo "<pre>";print_r($product);die;
            $tags->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        $tags = Tag::findOrFail($id);
        //validate product information
        $this->validate($request, [
            'tag_name' => 'required|string|max:191'
        ]);
        //update tags
         $tags->update($request->all());
        //return ['message'=>'updating'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $tags = Tag::findOrFail($id);
        //delete the blogs
        $tags->delete();
        //return ['message' => 'product is Deleted'];
    }
}
