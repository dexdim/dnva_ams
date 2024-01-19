<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $category = '';
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->middleware('auth:api');
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->paginate(10);
        return $this->sendResponse($categories, 'Category list');
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $categories = $this->category->pluck('name', 'id');
        return $this->sendResponse($categories, 'Category list');
    }


    /**
     * Store a newly created resource in storage.
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $categories = $this->category->create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);
        return $this->sendResponse($categories, 'Category Created Successfully');
    }

    /**
     * Update the resource in storage
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $categories = $this->category->findOrFail($id);
        $categories->update($request->all());
        return $this->sendResponse($categories, 'Category Information has been updated');
    }

    /**
     * Remove the specified category.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $categories = $this->category->findOrFail($id);
        $categories->delete();
        return $this->sendResponse($categories, 'Category has been deleted');
    }
}
