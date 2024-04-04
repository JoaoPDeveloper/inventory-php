<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vendor.vendor');
    }

    public function Vendor(Request $request)
    {
        $vendor = Vendor::orderBy('id', 'desc');

        if ($request->name != '') {
            $vendor->where('name', 'LIKE', '%'.$request->name.'%');
        }

        if ($request->email != '') {
            $vendor->where('email', 'LIKE', '%'.$request->email.'%');
        }

        if ($request->phone != '') {
            $vendor->where('phone', 'LIKE', '%'.$request->phone.'%');
        }

        $vendor = $vendor->paginate(10);

        return $vendor;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|unique:vendors',
            'phone' => 'required|unique:vendors',
        ]);

        try {
            $vendor = new Vendor();

            $vendor->name = $request->name;
            $vendor->email = $request->email;
            $vendor->phone = $request->phone;
            $vendor->address = $request->address;

            $vendor->save();

            return response()->json(['status' => 'success', 'message' => 'Provedor criado
            ']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Um erro foi encontrado! Tente novamente']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $supplier)
    {
        return $supplier;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable',
            'phone' => 'required',
        ]);

        try {
            $supplier = Vendor::find($id);
            $supplier->name = $request->name;
            $supplier->email = $request->email;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;

            $supplier->update();

            return response()->json(['status' => 'success', 'message' => 'Provedor atualizado']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Foi Encontrado um erro, tente novamente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Vendor::find($id);

            $data->delete();

            return response()->json(['status' => 'success', 'message' => 'Provedor Atualizado']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Foi Encontrado um erro, tente novamente.']);
        }
    }
}
