<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // Show all listings
    public function index() {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(
                request(['tag', 'search'])
            )->simplePaginate(5)

        ]);

    }

    //Ajax Search
    public function ajasearch(Request $request){


            $listings = Listing::latest()->filter(
            request(['tag', 'search'])
        )->simplePaginate(10);

            $output ="";

            foreach ($listings as $listing) {
                if ($listing->logo != 0){
                    $img = "storage/".$listing->logo;
                }else{
                    $img = "/images/no-image.png";
                }

                $tag = "";
                $tags = explode(',', $listing->tags);

                foreach ($tags as $listags){
                    $tag .= '<li class="flex items-center justify-center bg-black text-white rounded-xl py-1 px-3 mr-2 text-xs"
    >'.$listags.'</li>';
                }

                $output .= '<div class="bg-gray-50 border border-gray-200 rounded p-6"><div class="flex">
                                    <img class="hidden w-48 mr-6 md:block"
                                    src='.$img.'
                                    alt="image"/>
                                    <div>
                                        <h3 class="text-2xl"><a href="/listings/'.$listing->id.'">'.$listing->title.'</a></h3>
                                        <div class="text-xl font-bold mb-4">'.$listing->company.'</div>
                                        <ul class="flex">
                                             '.$tag.'
                                        </ul>


                                        <div class="text-lg mt-4">
                                        <i class="fa-solid fa-location-dot"></i>'.$listing->location.'
                                        </div>
                                    </div>
                                </div></div>';

            };
            if($output != 0){
                $output .= '<div class="col-12 min-vw-100 alert alert-success" role="alert">
                                        Search results...
                                  </div>';
            }else{
                $output .= '<div class="col-12 alert alert-info" role="alert">
                                        Search results...
                                  </div>';
            }
        return response($output);
    }

    // Show single listing
    public function show(Listing $listing) {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }
    //Show Create Form
    public function create(){
        return view('listings.create');
    }

    //Store Listing
    public function store(Request $request){
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings',
            'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Successfully created');
    }

    //Show EditForm
    public function edit(Listing $listing){
        return view('listings.edit', ['listing' => $listing]);
    }

    //Update
    public function update(Request $request, Listing $listing){

        //Make sure logged in User is owner
        if($listing->user_id != auth()->id()){
            abort(403, 'Unauthorized Action');
        }
        //Update action
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return back()->with('message', 'Successfully updated');
    }

    //Show Delete
    public function destroy(Listing $listing){
        //Make sure logged in User is owner
        if($listing->user_id != auth()->id()){
            abort(403, 'Unauthorized Action');
        }
        //Delete
        $listing->delete();
        return redirect('/listings/menage/')->with('message', 'Listing deleted successfully!');
    }

    //Menage function
    public function menage(){
        return view('listings.menage', ['listings' => auth()->user()
        ->listings()->get()]);
    }
}

