<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slider;
use App\About;
use App\Service;
use App\Home;
use App\Review;
use App\Partner;
use App\Gallery;
use App\Appointment;
use App\Contact;
use App\Category;
use App\Article;
use App\Team;
use App\Tag;
use App\TeamLeader;
use App\Topic;
use Session;
use Auth;

class PagesController extends Controller
{
    public function Index(){
        //fetch services details
        $services = Service::get();
        $services = json_decode(json_encode($services));
        //fetch slider info
        $sliders = Slider::get();
        $sliders = json_decode(json_encode($sliders));
        //fetch home details
        $homes = Home::first();
        $homes = json_decode(json_encode($homes));
        //fetch customer testimonials
        $testimonials = Review::get();
        $testimonials = json_decode(json_encode($testimonials));
        //fetch gallery photos
        $galleries = Gallery::latest('created_at')->limit(4)->get();
        $galleries = json_decode(json_encode($galleries));
        //fetch partner info
        $partners = Partner::get();
        $partners = json_decode(json_encode($partners));
        return view('frontpages.index')->with(compact('homes','sliders', 'services','galleries','testimonials','partners'));
    }
    public function Leader(){
        $leader = TeamLeader::first();
        return view('frontpages/leader')->with(compact('leader'));
    }
    public function About(){
        $abouts = About::first();
        $abouts = json_decode(json_encode($abouts));

        //fetch team members
        $teams = Team::get();
        $teams = json_decode(json_encode($teams));
        return view('frontpages.about')->with(compact('abouts', 'teams'));
    }
    public function Services(){

        $services = Service::get();
        $services = json_decode(json_encode($services));
        return view('frontpages.services')->with(compact('services'));
    }
    public function Testimonials(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            $reviews = new Review;
            $reviews->name = $data['name'];
            $reviews->textarea = $data['textarea'];
            $reviews->emoji = $data['emoji'];
            $reviews->save();
        }
        $review = Review::get();
        return view('frontpages.testimonials')->with(compact('review'));
    }
    public function Faq(){
        $questions = Topic::get();
        $questions = json_decode(json_encode($questions));
        return view('frontpages.faq')->with(compact('questions'));
    }

    public function Gallery(){
        //fetch gallery photos
        $galleries = Gallery::get();
        $galleries = json_decode(json_encode($galleries));

        return view('frontpages.gallery')->with(compact('galleries'));
    }

    public function Treatment(){
        return view('frontpages.treatment-single');
    }

    public function Articles(){
        //get all articles limit them to display only 4
        $articles = Article::latest('updated_at')->limit(4)->get();
        //dd($articles);

        //get old articles limit them to display only 4
        $oldArticles = Article::latest('created_at')->limit(4)->get();
        //dd($articles);

        //fetch categories
        $categories = Category::with('articles')->latest('updated_at')->limit(8)->get();
        //dd($categories);
        
        return view('frontpages.articles.index', compact('articles', 'categories','oldArticles'));
    }

    public function show(Article $article){

        $getTags = Tag::with('articles')->first();
        //dd($getTags);
        
        //fetch categories
        $categories = Category::with('articles')->latest('updated_at')->limit(8)->get();
        //dd($categories);

         //get old articles limit them to display only 4
        $newArticles = Article::latest('updated_at')->limit(4)->get();
        //dd($articles);

        //fetch articles that are related ie in the same category
        $relatedPosts = Article::with('category')->where('category_id', '=', $article->category->id)
                                ->where('id', '!=', $article->id)
                                ->limit(4)
                                ->get();

        return view('frontpages.articles.show', ['article'=> $article], compact('getTags', 'categories','newArticles','relatedPosts'));

    }

    public function Appointment(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();

            $appointments = new Appointment;
            $appointments->name = $data['name'];
            $appointments->email = $data['email'];
            $appointments->appointment = $data['appointment'];
            $appointments->date = $data['date'];
            $appointments->save();
        }
        return redirect('/');
    }
    public function Contact(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo("<pre>");print_r($data);die;

            $contacts = new Contact;
            $contacts->name = $data['name'];
            $contacts->email = $data['email'];
            $contacts->subject = $data['subject'];
            $contacts->textarea = $data['textarea'];
            $contacts->save();

        }
        return view('frontpages.contact-us')->with('success','Thank You for Your Message we will get in touch..');
    }
    public function logout() {
		Session::flush();
		return redirect('/')->with('flash_message_success','Logged out Succesfully');
	}
}
