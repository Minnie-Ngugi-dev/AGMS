<?php
namespace App\Http\Controllers;

use App\Models\{Part, Service};
use Illuminate\Http\Request;

class PartController extends Controller {
    public function allIndex() {
        $parts = Part::with('service.vehicle')->latest()->paginate(20);
        return view('parts.index', compact('parts'));
    }
    public function create(Service $service) { return view('parts.create', compact('service')); }
    public function store(Request $request, Service $service) {
        $request->validate([
            'name'       => 'required|string',
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);
        Part::create(['service_id'=>$service->id] + $request->only('name','part_number','quantity','unit_price','notes'));
        if ($request->action === 'save_add') return redirect()->route('services.parts.create',$service)->with('success','Part added. Add another.');
        return redirect()->route('services.show',$service)->with('success','Part added.');
    }
    public function edit(Part $part) { return view('parts.edit', compact('part')); }
    public function update(Request $request, Part $part) {
        $part->update($request->only('name','part_number','quantity','unit_price','notes'));
        return redirect()->route('services.show',$part->service)->with('success','Part updated.');
    }
    public function destroy(Part $part) {
        $service = $part->service;
        $part->delete();
        return redirect()->route('services.show',$service)->with('success','Part removed.');
    }
}