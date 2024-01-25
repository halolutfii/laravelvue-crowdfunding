<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use File;

class CampaignController extends Controller
{
    public function __construct(){
        $this->middleware(['isAdmin','auth'])->except(['show', 'getAll']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['campaign'] = Campaign::all();

        if(count($data['campaign']) === 0){
            return response()->json([
                'response_code' => '00',
                'response_message' => 'Tidak ada data campaign!',
            ],200);
        }else{
            return response()->json([
                'response_code' => '00',
                'response_message' => 'Data campaign berhasil ditampilkan!',
                'data' => $data,
            ],200);
        }
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
            'title' => 'required',
            'required' => 'required',
            'address' => 'required',
            'description' => 'required',
            'image' => 'mimes:png,jpg,jpeg',
        ]);

        $campaign = new Campaign;
        $campaign->title = $request->title;
        $campaign->required = $request->required;
        $campaign->address = $request->address;
        $campaign->description = $request->description;
        $campaign->colected = $request->colected;

        if($request->hasFile('image')){
            $image = $request->file('image');
            // format file
            $image_extention = $image->getClientOriginalExtension();
            // name upload file
            $image_name = time().'.'.$image_extention;
            // lokasi penyimpanan gambar
            $image_folder = '/image/campaign/';
            $image_location = $image_folder . $image_name;
            
            try {
                $image->move(public_path($image_folder), $image_name);

                $campaign->image = $image_location;

            } catch (\Throwable $th) {
                return response()->json([
                    'response_code' => '01',
                    'response_message' => 'Image gagal diupload!',
                ],400);
            }
        }
        $campaign->save();

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Data berhasil ditambah!',
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Campaign::findOrFail($id);

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Detail data campaign berhasil ditampilkan!',
            'data' => $data,
        ],200);

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
            'title' => 'required',
            'required' => 'required',
            'address' => 'required',
            'description' => 'required',
            'image' => 'mimes:png,jpg,jpeg',
        ]);

        $campaign = Campaign::find($id);
        $campaign->title = $request->title;
        $campaign->required = $request->required;
        $campaign->address = $request->address;
        $campaign->description = $request->description;
        $campaign->colected = $request->colected;

        if($request->hasFile('image')){
            $image = $request->file('image');
            // format file
            $image_extention = $image->getClientOriginalExtension();
            // name upload file
            $image_name = time().'.'.$image_extention;
            // lokasi penyimpanan gambar
            $image_folder = '/image/campaign/';
            $subCampaign = substr($campaign->image, 1);
            File::delete($subCampaign);
            $image_location = $image_folder . $image_name;
            
            try {
                $image->move(public_path($image_folder), $image_name);
                $campaign->image = $image_location;

            } catch (\Throwable $th) {
                return response()->json([
                    'response_code' => '01',
                    'response_message' => 'Image gagal diupload!',
                ],400);
            }
        }
        $campaign->save();

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Update data berhasil ditambah!',
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::find($id);

        $subCampaign = substr($campaign->image, 1);
        File::delete($subCampaign);

        $campaign->delete();

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Data campaig berhasil dihapus!',
        ],200);
    }

    public function getAll()
    {
        $campaign = Campaign::paginate(2);

        $data['campaign'] = $campaign;

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Data campaign pagination success show!',
            'data' => $data
        ],200);
    }
}
