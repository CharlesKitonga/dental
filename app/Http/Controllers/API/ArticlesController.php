<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Article;
use App\Tag;
use Illuminate\Support\Facades\Auth;
use Image;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::latest()->paginate(15);
        //echo "<pre>";print_r($articles);die;
        return $articles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get the currently authenticated user...
        $userId = auth('api')->user()->id;
        $this->validate($request, [
            'heading' => 'required|string|max:191',
            'category_id' => 'required|integer',
            'tag_id' => 'required|integer',
            'description' => 'required|string',
            'photo' => 'required|string|min:191'
        ]);

        if($request->isMethod('post')){
            $article = new Article;
            $article->heading = $request['heading'];
            $article->user_id = $userId;
            $article->category_id = $request['category_id'];
            $article->tag_id = $request['tag_id'];
            $article->description = $request['description'];
            //check for current photo
            $currentPhoto = $article->photo;
            //Upload Image
            if($request->photo != $currentPhoto){
                $imgUpload = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
                \Image::make($request->photo)->save(public_path('images/articles/').$imgUpload);
                //upload to the db using the merge function
                $article->photo = $imgUpload;

                //delete old photo if user updates their articles picture
                $oldPhoto = public_path('images/articles/').$currentPhoto;
                if (file_exists($oldPhoto)) {
                    @unlink($oldPhoto);
                }

            }
            //echo "<pre>";print_r($product);die;
            $article->save();
            //attach tags
            $article->tags()->attach(request('tag_id'));

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

        $articles = Article::findOrFail($id);

        //validate article information
        $this->validate($request, [
            'heading' => 'required|string|max:191',
            'category_id' => 'required|integer',
            'tag_id' => 'required|integer',
            'description' => 'required|string'
        ]);

        //check for current photo
        $currentPhoto = $articles->photo;
        //Upload Image
        if($request->photo != $currentPhoto){
            $imgUpload = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('images/articles/').$imgUpload);
            //upload to the db using the merge function
            $request->merge(['photo' =>$imgUpload]);

            //delete old photo if articles updates their homepage picture
            $oldPhoto = public_path('images/articles/').$currentPhoto;
            if (file_exists($oldPhoto)) {
                @unlink($oldPhoto);
            }

        }
        //update articles
         $articles->update($request->all());
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

        $articles = Article::findOrFail($id);
        //delete the articles$articles
        $articles->delete();
        //return ['message' => 'product is Deleted'];
    }
}
