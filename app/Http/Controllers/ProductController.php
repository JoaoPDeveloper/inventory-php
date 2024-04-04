<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Stock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('name', 'asc')
                    ->get();

        return view('product.product', ['category' => $category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function ProductList(Request $request)
    {
        $product = Product::with(['category' => function ($query) {
            $query->select('id', 'name');
        }])->orderBy('product_name', 'asc');

        $name = $request->name;

        if ($name != '') {
            $product->where('product_name', 'LIKE', '%'.$name.'%');
        }

        if ($request->cat != '') {
            $product->where('category_id', '=', $request->cat);
        }

        $product = $product->paginate(10);

        return $product;
    }

    public function productByCategory($id)
    {
        $product = Product::where('category_id', '=', $id)->get();

        return $product;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
         'name' => 'required|unique:products,product_name',
         'category' => 'required',
        ]);

        try {
            $product = new Product();

            $product->category_id = $request->category;
            $product->product_name = $request->name;
            $product->details = $request->details;

            $product->save();

            return response()->json(['status' => 'success', 'message' => 'Produto Adicionado']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Algo deu errado. Por favor, tente novamente.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return $product;
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
           'category' => 'required',
        ]);

        try {
            $product = Product::find($id);
            $product->category_id = $request->category;
            $product->product_name = $request->name;
            $product->details = $request->details;

            $product->update();

            return response()->json(['status' => 'success', 'message' => 'Produto Atualizado']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Algo deu errado. Por favor, tente novamente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */

    // delete product

    public function destroy($id)
    {
        $product = Product::find($id);

        $check = Stock::where('product_id', '=', $product->id)->count();

        if ($check > 0) {
            return response()->json(['status' => 'error', 'message' => 'Primeiro devemos remover o stock do produto']);
        } else {
            try {
                $product->delete();

                return response()->json(['status' => 'success', 'message' => 'Produto Apagado']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Algo deu errado. Por favor, tente novamente.']);
            }
        }
    }
}
