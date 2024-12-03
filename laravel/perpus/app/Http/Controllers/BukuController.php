<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Controllers\MyUtil;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MyUtil::sendResponse(Buku::all(), 'OK');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required',
            'judul' => 'required',
            'tahun' => 'required',
        ]);
        if($validator->fails()){
            return MyUtil::sendError('Validation Error.', $validator->errors());       
        }
        else
        {
            if(Buku::where("isbn","=" ,$request->isbn)->exists())
            {
                return MyUtil::sendError('duplicate isbn', 'duplicate on book');
            }
            else
            { 
                $buku = new Buku();
                $buku->isbn = $request->isbn;
                $buku->judul = $request->judul;
                $buku->pengarang = $request->pengarang;
                $buku->tahun = $request->tahun;
                $buku->save();
                return $this->index();
            }
        }


    }

    /**
     * Display the specified resource.
     */
    public function show($isbn)
    {
        try
        {
            return MyUtil::sendResponse(Buku::findOrFail($isbn), 'OK');
        }
        catch(ModelNotFoundException $ex)
        {
            return MyUtil::sendError("NOT FOUND", 'NOT FOUND');
        }       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try
        {
            $buku = Buku::findOrFail($request->isbn);
            $buku->judul = $request->judul;
            $buku->pengarang = $request->pengarang;
            $buku->tahun = $request->tahun;
            $buku->save();
            return $this->index();
        }
        catch(ModelNotFoundException $ex)
        {
            return MyUtil::sendError("NOT FOUND", 'NOT FOUND');
        }   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($isbn)
    {
        Buku::where('isbn', $isbn)->delete();
         return $this->index();

    }

    public function search(Request $request)
    {
        // Ambil parameter pencarian, start, dan limit
        $cari = $request->cari;
        $start = $request->start;
        $limit = $request->limit;

        // Jika pencarian kosong, tampilkan semua data
        $query = DB::table('buku');
        if (trim($cari) !== '') {
            $query->where('judul', 'like', "%".$cari."%")
                ->orWhere('pengarang', 'like', "%".$cari."%");
        }

        // Hitung total data
        $count = $query->count();

        // Ambil data sesuai paginasi
        $buku = $query->orderBy('judul')
                    ->offset($start)
                    ->limit($limit)
                    ->get();

        // Buat response
        $obj = new \stdClass();
        $obj->count = $count;
        $obj->buku = $buku;
        return MyUtil::sendResponse($obj, 'OK');
    }


}