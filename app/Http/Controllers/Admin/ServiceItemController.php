<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

class ServiceItemController extends Controller
{
    public function index() {
        $items = ServiceItem::orderBy('category')->orderBy('name')->get();
        $categories = $items->pluck('category')->unique()->sort()->values();
        return view('admin.service-items.index', compact('items', 'categories'));
    }

    public function create() {
        return view('admin.service-items.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        ServiceItem::create($request->all());
        return redirect()->route('admin.service-items.index')
            ->with('success', 'Service item added successfully.');
    }

    public function edit(ServiceItem $serviceItem) {
        return view('admin.service-items.edit', compact('serviceItem'));
    }

    public function update(Request $request, ServiceItem $serviceItem) {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $serviceItem->update($request->all());
        return redirect()->route('admin.service-items.index')
            ->with('success', 'Service item updated.');
    }

    public function destroy(ServiceItem $serviceItem) {
        $serviceItem->delete();
        return redirect()->route('admin.service-items.index')
            ->with('success', 'Service item deleted.');
    }

    public function toggle(ServiceItem $serviceItem) {
        $serviceItem->update(['is_active' => !$serviceItem->is_active]);
        return back()->with('success', 'Status updated.');
    }
}