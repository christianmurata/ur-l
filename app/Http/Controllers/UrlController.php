<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Http\Resources\UrlCollection;
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
        return new UrlCollection(Url::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UrlStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UrlStoreRequest $request)
    {
        // create a new short URL
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
     * @param  \App\Http\Requests\UrlStoreRequest  $request
     * @param  \App\Models\Url  $url
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UrlStoreRequest $request, Url $url)
    {
        if($request['user_id'] !== $url->user_id)
            return response()->json([
                'message' => 'This user is not allowed to update this URL'
            ], 403);

        if(!$url->update(['url' => $request['url']]))
            return response()->json([
                'message' => 'Error to update resource'
            ], 400);

        return $url;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function destroy(Url $url)
    {
        if(!$url->delete())
            return response()->json([
                'message' => 'Error to delete this resource'
            ], 400);

        return response()->json([
            'message' => 'Resource deleted successfully'
        ], 200);
    }

    /**
     * Get the short url and then redirect to the url
     *
     * @param $short_code
     * @return \Illuminate\Http\Response
     */
    public function redirect($short_code)
    {
        // redirect the short url to destination
        $url = Url::where('short_code', $short_code)->first();

        if(!$url)
            return response()->json([
                'message' => 'URL not found.'
            ], 404);

        return redirect()->away($url->url);
    }

}
