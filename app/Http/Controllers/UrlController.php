<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Url;
use App\Http\Requests\UrlStoreRequest;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all urls
        return Url::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UrlStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UrlStoreRequest $request)
    {
        $domain = $request->root();
        $short_code = substr(md5(time(). $request->url()), 0, 5);

        return Url::create([
            'url'        => $request->url,
            'user_id'    => $request->user_id,
            'short_url'  => "{$domain}/$short_code",
            'short_code' => $short_code
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Url $url
     * @return \Illuminate\Http\Response
     */
    public function show(Url $url)
    {
        return $url;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Url $url)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function destroy(Url $url)
    {
        //
    }

    /**
     * Get the short url and then redirect to the url
     *
     * @param $short_code
     * @return \Illuminate\Http\Response
     */
    public function redirect($short_code)
    {
        $url = Url::where('short_code', $short_code)->first();

        if(!$url)
            return response()->json([
                'message' => 'URL not found.'
            ], 404);

        return redirect()->away($url->url);
    }

}
