<?php
namespace App\Http\Controllers;

use App\Models\{Repair, Service};
use Illuminate\Http\Request;

class RepairController extends Controller {
    public function allIndex() {
        $repairs = Repair::with('service.vehicle')->latest()->paginate(20);
        return view('repairs.index', compact('repairs'));
    }
    public function create(Service $service) { return view('repairs.create', compact('service')); }
    public function store(Request $request, Service $service) {
        $request->validate([
            'diagnosis' => 'required|string',
            'cost'      => 'required|numeric|min:0',
        ]);
        Repair::create(['service_id'=>$service->id] + $request->only('diagnosis','action_taken','cost','status','notes'));
        return redirect()->route('services.show',$service)->with('success','Repair logged.');
    }
    public function edit(Repair $repair) { return view('repairs.edit', compact('repair')); }
    public function update(Request $request, Repair $repair) {
        $repair->update($request->only('diagnosis','action_taken','cost','status','notes'));
        return redirect()->route('services.show',$repair->service)->with('success','Repair updated.');
    }
    public function destroy(Repair $repair) {
        $service = $repair->service;
        $repair->delete();
        return redirect()->route('services.show',$service)->with('success','Repair deleted.');
    }
}