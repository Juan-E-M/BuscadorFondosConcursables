<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Crl;
use App\Models\Trl;
use App\Models\Ocde;
use App\Models\Ods;
use App\Models\Country;
use App\Models\ContestableFund;
use Illuminate\Support\Facades\Storage;


class ContestableFundController extends Controller
{

    public function index()
    {
        $crlOptions = Crl::all();
        $trlOptions = Trl::all();
        $countries = Country::all();
        $contestableFunds = ContestableFund::with('ods', 'ocde', 'trl', 'crl')->get();
        $ocdes = Ocde::all();
        $odss = Ods::all();
        return Inertia::render('ContestableFunds/Index', [
            'contestableFunds' => $contestableFunds,
            'crlOptions' => $crlOptions,
            'trlOptions' => $trlOptions,
            'ocdes' => $ocdes,
            'odss' => $odss,
            'countries' => $countries,
        ]);
    }

    public function create()
    {
        $crlOptions = Crl::all();
        $trlOptions = Trl::all();
        $ocdes = Ocde::all();
        $odss = Ods::all();
        $countries = Country::all();
        return Inertia::render('ContestableFunds/Create', [
            'crlOptions' => $crlOptions,
            'trlOptions' => $trlOptions,
            'ocdes' => $ocdes,
            'odss' => $odss,
            'countries' => $countries,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'institution' => 'required',
            'name' => 'required',
            'summary' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
            'budget' => 'required',
            'link' => 'required|url',
            'others' => 'required',
            'country' => 'required',
            'region' => 'required',
            'crl' => 'required',
            'trl' => 'required',
            'ods' => 'required|array',
            'ocde' => 'required|array',
            'file' => 'file|mimes:pdf,doc,docx',
        ]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $data['file_path'] = $filePath;
        }
        $contestableFund = ContestableFund::create($data);
        $contestableFund->country()->associate($request->input('country'));
        $contestableFund->trl()->associate($request->input('trl'));
        $contestableFund->crl()->associate($request->input('crl'));
        $contestableFund->save();
        if ($request->has('ods')) {
            $contestableFund->ods()->attach($request->input('ods'));
        }
        if ($request->has('ocde')) {
            $contestableFund->ocde()->attach($request->input('ocde'));
        }
        return redirect()->back();
    }
    private function getFileLink($filePath)
    {
        return asset(Storage::url($filePath));
    }

    public function show($id)
    {
        $contestableFund = ContestableFund::with('ods', 'ocde', 'crl', 'trl', 'country')->find($id);
        if (!$contestableFund) {
            return response()->json(['message' => 'registro no encontrado'], 404);
        }
        $fileLink = $this->getFileLink($contestableFund->file_path);
        $contestableFund->file_path = $fileLink;
        return Inertia::render('ContestableFunds/Show', [
            'contestableFund' => $contestableFund,
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $contestableFund = ContestableFund::find($id);
        if (!$contestableFund) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        if ($contestableFund->file_path) {
            Storage::disk('public')->delete($contestableFund->file_path);
        }
        $contestableFund->delete();
        
        return Inertia::location(route('contestablefunds.index'));
    }
}
