<?php
namespace App\Http\Controllers;

use App\Models\{Service, ServiceChecklist};
use Illuminate\Http\Request;

class ChecklistController extends Controller {
    private array $groups = [
        'Engine & Fluids'    => [
            ['key'=>'change_oil_filter',       'label'=>'Change Oil & Filter'],
            ['key'=>'check_all_fluids',         'label'=>'Check All Fluids'],
            ['key'=>'check_brake_fluid',        'label'=>'Check Brake Fluid'],
            ['key'=>'check_washer_fluid',       'label'=>'Check Washer Fluid'],
            ['key'=>'check_transmission_fluid', 'label'=>'Check Transmission Fluid'],
        ],
        'Tyres & Brakes'     => [
            ['key'=>'check_tyre_pressure',  'label'=>'Check Tyre Pressure'],
            ['key'=>'check_tyre_condition', 'label'=>'Check Tyre Condition'],
            ['key'=>'check_brakes',         'label'=>'Check Brakes & Wheel Bearings'],
            ['key'=>'check_shock_absorbers','label'=>'Check Shock Absorbers'],
        ],
        'Engine Components'  => [
            ['key'=>'check_belts_hoses',   'label'=>'Check All Belts & Hoses'],
            ['key'=>'lubricate_chassis',    'label'=>'Lubricate Chassis'],
            ['key'=>'replace_spark_plugs',  'label'=>'Replace Spark Plugs'],
            ['key'=>'change_air_filter',    'label'=>'Change Air Filter'],
            ['key'=>'check_leaks',          'label'=>'Check for Leaks'],
        ],
        'Cooling System'     => [
            ['key'=>'check_engine_thermostat','label'=>'Check Engine Thermostat'],
            ['key'=>'inspect_cooling_system', 'label'=>'Inspect Cooling System'],
        ],
        'Electrical & Body'  => [
            ['key'=>'check_battery',      'label'=>'Check Battery'],
            ['key'=>'check_wiper_blades', 'label'=>'Check Wiper Blades'],
            ['key'=>'check_lights',       'label'=>'Check Lights'],
            ['key'=>'check_exhaust',      'label'=>'Check Exhaust'],
        ],
    ];

    public function create(Service $service) {
        if ($service->checklist) return redirect()->route('services.show',$service)->with('info','Checklist already exists.');
        $checklistGroups = collect($this->groups);
        return view('checklists.create', ['service'=>$service->load('vehicle.customer','mechanic'), 'checklistGroups'=>$checklistGroups]);
    }
    public function store(Request $request, Service $service) {
        $data = ['service_id'=>$service->id, 'additional_notes'=>$request->additional_notes];
        foreach (collect($this->groups)->flatten(1)->pluck('key') as $key) {
            $data[$key] = $request->input("items.{$key}.done") == '1';
        }
        ServiceChecklist::create($data);
        $service->update(['status'=>'in-progress']);
        return redirect()->route('services.show',$service)->with('success','Checklist saved. Service is now In Progress.');
    }
    public function edit(Service $service, ServiceChecklist $checklist) {
        $checklistGroups = collect($this->groups);
        return view('checklists.edit', compact('service','checklist','checklistGroups'));
    }
    public function update(Request $request, Service $service, ServiceChecklist $checklist) {
        $data = ['additional_notes'=>$request->additional_notes];
        foreach (collect($this->groups)->flatten(1)->pluck('key') as $key) {
            $data[$key] = $request->input("items.{$key}.done") == '1';
        }
        $checklist->update($data);
        return redirect()->route('services.show',$service)->with('success','Checklist updated.');
    }
}