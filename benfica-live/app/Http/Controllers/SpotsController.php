<?php

namespace App\Http\Controllers;

use App\Spot;
use DB;
use Illuminate\Http\Request;
use Image;
use Storage;

class SpotsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries_list = DB::table('countries')
            ->select('id', 'name_pt')
            ->where('continent_id', 2)
            ->orWhere('continent_id', 6)
            ->orWhere('continent_id', 7)
            ->orderBy('name_pt', 'asc')
            ->get();

        return view('spots.create')->with(compact('countries_list'));
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
            'name' => 'required|max:60',
            'city' => 'required|max:35',
            'email' => 'email|nullable',
            'country_id' => 'required|exists:countries,id',
            'spot_image' => 'image|nullable'
        ]);

        $image = $request->file('spot_image');
        $imagePath = null;
        $thumbnailPath = null;

        if ($image) {
            $imagePath = $image->hashName('spots');
            $thumbnailPath = $image->hashName('spots');
            $thumbnailPathExploded = explode('.',$thumbnailPath);
            $thumbnailPath = $thumbnailPathExploded[0] . '-thumbnail.' . $thumbnailPathExploded[1];

            $img = Image::make($image);
            $img->fit(350, 240);

            $imgThumbnail = Image::make($image);
            $imgThumbnail->fit(500, 450);

            Storage::put($imagePath, (string) $img->encode());
            Storage::put($thumbnailPath, (string) $imgThumbnail->encode());
        }

        $name = $request['name'];

        Spot::create([
            'name' => $name,
            'slug' => str_slug($name),
            'address' => $request->has('address') ? $request->input('address') : null,
            'email' => $request->has('email') ? $request->input('email') : null,
            'phone_number' => $request->has('phone_number') ? $request->input('phone_number') : null,
            'city' => $request->has('city') ? $request->input('city') : null,
            'country_id' => $request->has('country_id') ? $request->input('country_id') : null,
            'image' => $imagePath,
            'thumbnail_image' => $thumbnailPath
        ]);

         return redirect('/')->with('success', "Spot $name submetido com sucesso. Será publicado após ser revisto e aprovado. Obrigado!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function show($countrySlug, $spotSlug)
    {
        // TODO activate this
        // $spot = Spot::where('slug', $spotSlug)->where('is_approved', true)->firstOrFail();
        $spot = Spot::where('slug', $spotSlug)->where('is_approved', false)->firstOrFail();
        return view('spots.show')->with(compact('spot'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function edit(Spot $spot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spot $spot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spot $spot)
    {
        //
    }
}
