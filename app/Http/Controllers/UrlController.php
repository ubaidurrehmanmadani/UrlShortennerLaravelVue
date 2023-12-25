<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\ShortenerUrl;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ShortenerUrl::paginate(100));
    }

    public static function getAll() {
        return response()->json(ShortenerUrl::all());
    }

    public static function generateUrl(Request $request, $shortCode){
        return UrlFeature::generateRedirectUrl($request, $shortCode);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UrlRequest $request)
    {
        $validator = $request->validated();
        if (is_object($validator)) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        return response()->json((new UrlFeature())->prepareAndSaveShortUrl($request));
    }

    /**
     * Display the specified resource.
     */
    public function show(ShortenerUrl $urls)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShortenerUrl $urls)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UrlRequest $request, $urlId)
    {
        $validator = $request->validated();

        if (is_object($validator)) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $urlObj = ShortenerUrl::find($urlId);
        $urlObj->url = $request['url'] ?? null;
        $result = $urlObj->save();
        if ($result){
            $data=[
                'status'=>'1',
                'msg'=>'success'
            ];
        }else {
            $data = [
                'status' => '0',
                'msg' => 'fail'
            ];
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $result = ShortenerUrl::find($id)->delete();
        if ($result){
            $data=[
                'status'=>'1',
                'msg'=>'success'
            ];
        }else {
            $data = [
                'status' => '0',
                'msg' => 'fail'
            ];
        }
        return response()->json($data);
    }
}
