<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Inventory\InventoryRequest;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends BaseController
{
    protected $inventory = '';
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(Inventory $inventory)
    {
        $this->middleware('auth:api');
        $this->inventory = $inventory;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inventory.index');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $inventories = Inventory::where(function ($query) use ($search) {
            $query->where('idcode', 'LIKE', "%$search%")
                ->orWhere('category', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%")
                ->orWhere('serialnumber', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('status', 'LIKE', "%$search%")
                ->orWhere('history', 'LIKE', "%$search%")
                ->orWhere('notes', 'LIKE', "%$search%");
        })->latest()->with('category', 'employee')->paginate(10);
        return $this->sendResponse($inventories, 'Inventory list');
    }

    public function all()
    {
        $inventories = Inventory::all();
        return $this->sendResponse($inventories, 'All inventory list');
    }

    public function inStorage()
    {
        $inventories = Inventory::where('status', 'Storage')->latest()->with('category', 'employee')->paginate(5);
        return $this->sendResponse($inventories, 'In Storage List');
    }

    public function inBroken()
    {
        $inventories = Inventory::where('status', 'Broken')->latest()->with('category', 'employee')->paginate(5);
        return $this->sendResponse($inventories, 'In Storage List');
    }

    /**
     * Store a newly created resource in storage.
     * @param  App\Http\Requests\Inventory\InventoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InventoryRequest $request)
    {
        $inventories = Inventory::create([
            'idcode' => $request->get('idcode'),
            'category_id' => $request->get('category_id'),
            'description' => $request->get('description'),
            'brand' => $request->get('brand'),
            'serialnumber' => $request->get('serialnumber'),
            'supplier' => $request->get('supplier'),
            'quantity' => $request->get('quantity'),
            'purchasecost' => $request->get('purchasecost'),
            'purchasedate' => $request->get('purchasedate'),
            'license' => $request->get('license'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'status' => $request->get('status'),
            'history' => $request->get('history'),
            'notes' => $request->get('notes'),
            'checkdate' => $request->get('checkdate'),
            'checkedby' => $request->get('checkedby'),
        ]);
        return $this->sendResponse($inventories, 'Inventory created successfully');
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventories = Inventory::with('category', 'employee')->findOrFail($id);
        return $this->sendResponse($inventories, 'Inventory Details');
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inventories = Inventory::findOrFail($id);
        $inventories->update($request->all());
        return $this->sendResponse($inventories, 'Inventory information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $inventories = Inventory::findOrFail($id);
        $inventories->delete();
        return $this->sendResponse($inventories, 'Inventory has been deleted');
    }

    public function duplicate($id)
    {
        $record = Inventory::find($id);
        $duplicate = $record->replicate();
        $duplicate->push();
    }

    public function counts()
    {
        $deployed = Inventory::where('status', 'Deployed')->count();
        $storage = Inventory::where('status', 'Storage')->count();
        $inservice = Inventory::where('status', 'In Service')->count();
        $broken = Inventory::where('status', 'Broken')->count();
        $laptop = Inventory::where('category_id', '1')->count();
        $monitor = Inventory::where('category_id', '7')->count();
        $server = Inventory::where('category_id', '4')->count();
        $handphone = Inventory::where('category_id', '5')->count();
        $inventories = compact('deployed', 'storage', 'inservice', 'broken', 'laptop', 'monitor', 'server', 'handphone');

        return $this->sendResponse($inventories, 'Dashboard list');
    }
}
