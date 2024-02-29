<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = products::all();
        $sections = sections::all();
        return view('products.products' , compact('sections' , 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Product_name' => 'required|unique:products|max:255',
            'description'  => 'required',
        ] , [
            'Product_name.required' => 'يرجى ادخال اسم المنتج',
            'Product_name.unique'   => 'اسم المنتج مسجل مسبقا',
            'description.required'  => 'يرجى ادخال البيان',

        ]);
        
        products::create([
            'Product_name' => $request->Product_name,
            'description'  => $request->description,
            'section_id'   => $request->section_id,
            'Created_by'   => (Auth::user()->name),
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, products $products)
    {
        $validated = $request->validate([
            'Product_name' => 'required',
                             'max:190',
                            Rule::unique('Product_name')->ignore($request->id),
            'description'  => 'required',
        ] , [
            'Product_name.required' => 'يرجى ادخال اسم المنتج',
            'Product_name.unique'   => 'اسم المنتج مسجل مسبقا',
            'description.required'  => 'يرجى ادخال البيان',

        ]);

        $product = products::findOrFail($request->pro_id) ;
        $section_id = sections::where('section_name' , $request->section_name)->first()->id;

        $product->update([
            'Product_name' => $request->Product_name,
            'description'  => $request->description,
            'section_id'   => $section_id
        ]);
        // $product->section_id   = $request->section_name;
        // $product->Product_name = $request->Product_name;
        // $product->description  = $request->description;
        // $product->save();
        
        session()->flash('Edit', 'تم  تعديل المنتج بنجاح ');
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(products $products , Request $request)
    {
        $product = products::findOrFail($request->pro_id);
        $product->delete();
        session()->flash('Delete', 'تم  حذف المنتج بنجاح ');
        return redirect('/products');
    }
}