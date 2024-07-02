<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\{Category, Product};
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->search()->orderBy('id', 'desc')->paginate($request->global_pagination);

        return view('admin.category.list_category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.add_category');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest  $request)
    {
        $data = [
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'status' => $request->status ?? '0'
        ];

        if ($request->hasFile('category_image')) {
            $image = s3_store_image($request->file('category_image'), 'admin/category');
            if ($image != null) {
                $data['category_image_name'] = $image['name'];
                $data['category_image_url'] = $image['url'];
            }
        }

        $category = Category::create($data);

        if (isset($request->type) && count($request->type) > 0) {
            foreach ($request->type as $type) {
                $category->size_type()->create(["size_type" => $type]);
            }
        }

        return redirect()->route('admin.categories')->with('success', __('category.messages.saveCategory'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load('size_type');
        return view('admin.category.view_category', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $category->load('size_type');
        return view('admin.category.edit_category', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, $id)
    {

        $category = Category::where('id', $id)->first();

        $data = [
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'status' => $request->status ?? '0'
        ];

        if ($request->hasFile('category_image')) {
            $image = s3_store_image($request->file('category_image'), 'admin/category');
            if ($image != null) {
                $data['category_image_name'] = $image['name'];
                $data['category_image_url'] = $image['url'];
            }
            if (!is_null($category->category_image_url)) {
                Storage::disk('s3')->delete('admin/category/' . $category->category_image_url);
                // Storage::delete($category->category_image_url);
            }
        }

        $category->update($data);

        if (isset($request->type) && count($request->type) > 0) {
            $category->size_type()->delete();
            foreach ($request->type as $type) {
                $category->size_type()->create(["size_type" => $type]);
            }
        }

        return redirect()->route('admin.categories')->with('success', __('category.messages.updateCategory'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)->first();
        $category->size_type()->delete();
        $category->delete();

        return redirect()->route('admin.categories')->with('success', __('category.messages.deleteCategory'));
    }

    /**
     * Products of the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function products(Request $request, $id)
    {
        $products = Product::with('retailer', 'category')->where('category_id', $id)->orderByDesc('id')->paginate($request->global_pagination);
        $sno = (($products->currentPage() * $products->perPage()) - $request->global_pagination) + 1;

        return view('admin.category.category_product', compact('products', 'sno'));
    }

    /**
     * Edit category product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProduct($id, $pid)
    {
        $product = Product::with('retailer', 'category', 'locations')->where('category_id', $id)->findOrFail($pid);
        $categories = Category::where('status', '1')->get();

        return view('admin.category.edit_category_product', compact('product', 'categories'));
    }

    /**
     * Update category product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(Request $request, $category_id, Product $product)
    {
        if ($product->where('category_id', $category_id)->count()) {
            $status = isset($request->status) ? '1' : '0';
            if ($product->status != $status) {
                $product->status = $status;
                $product->modified_user_type = 'Admin';
                $product->modified_by = auth()->user()->id;
                $product->save();
            }

            return redirect()->route('admin.categoryproduct', $category_id)->with('success', __('product.messages.updateProduct'));
        }

        return redirect()->route('admin.categoryproduct', $category_id);
    }

    public function getSubcategories(Request $request){

        $categogy_id = $request->categoryId;
        $subcategories = Category::where('status', '1')->where('parent_id', $categogy_id)->get();
        // return Category::where('status', '1')->where('parent_id', $categogy_id)->get();
        return response()->json($subcategories);
    }
}
