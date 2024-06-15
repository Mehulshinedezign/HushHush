<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::orderBy('id', 'desc')->search()->paginate($request->global_pagination);

        return view('admin.brand.list_brand', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.add_brand');
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            $data = [
                'name' => $request->brand_name,
                'status' => $request->status ?? '0'
            ];

            Brand::create($data);

            DB::commit();
            return redirect()->route('admin.brand')->with('success', __('brand.messages.saveBrand'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back();
        }
    }

    public function edit(Brand $brand)
    {
        return view('admin.brand.edit_brand', compact('brand'));
    }

    public function update(StoreBrandRequest $request, $id)
    {
        $brand =  Brand::where('id',$id)->first();
        $data = [
            'name' => $request->brand_name,
            'status' => $request->status ?? '0'
        ];

        $brand->update($data);

        return redirect()->route('admin.brand')->with('success',__('brand.messages.updateBrand'));
    }

    public function destroy($id)
    {
       $brand =  Brand::where('id',$id)->first();
       $brand->delete();

       return redirect()->route('admin.brand')->with('success',__('brand.messages.deleteBrand'));
    }
}
