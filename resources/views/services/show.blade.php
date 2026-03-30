@extends('layouts.app')
@section('title', $service->job_card_no)
@section('page-title','Service Details')

@section('content')
<div class="anim-up">

{{-- Status bar --}}
<div style="background:var(--navy);border-radius:var(--r-lg);padding:20px 26px;margin-bottom:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
  <div>
    <div style="font-size:.6rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:4px;">Job Card</div>
    <div style="font-family:var(--font-m);font-size:1.5rem;font-weight:700;color:#fff;letter-spacing:2px;">{{ $service->job_card_no }}</div>
  </div>
  <div style="width:1px;height:40px;background:rgba(255,255,255,.1);"></div>
  <div><div style="font-size:.6rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:3px;">Vehicle</div><div style="font-family:var(--font-m);font-size:1rem;font-weight:700;color:var(--green);">{{ $service->vehicle->registration_no }}</div></div>
  <div><div style="font-size:.6rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:3px;">Customer</div><div style="font-size:.88rem;font-weight:600;color:rgba(255,255,255,.8);">{{ $service->vehicle->customer->name }}</div></div>
  <div><div style="font-size:.6rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:3px;">Type</div><span class="badge {{ $service->service_type==='Full'?'badge-orange':'badge-sky' }}">{{ $service->service_type }}</span></div>
  <div><div style="font-size:.6rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:3px;">Status</div>
    @php $sc=['pending'=>'badge-amber','in-progress'=>'badge-sky','completed'=>'badge-green','cancelled'=>'badge-red']; @endphp
    <span class="badge {{ $sc[$service->status]??'badge-gray' }}">{{ ucfirst(str_replace('-',' ',$service->status)) }}</span>
  </div>
  <div style="margin-left:auto;display:flex;gap:8px;flex-wrap:wrap;">
    @if(!$service->checklist)
      <a href="{{ route('checklists.create',$service) }}" class="btn btn-primary btn-sm"><i class="fas fa-clipboard-list"></i> Fill Checklist</a>
    @else
      <a href="{{ route('checklists.edit',[$service,$service->checklist]) }}" class="btn btn-outline btn-sm" style="border-color:rgba(255,255,255,.2);color:#fff;background:rgba(255,255,255,.08);"><i class="fas fa-edit"></i> Edit Checklist</a>
    @endif
    @if($service->status !== 'completed')
     {{-- Quick status update --}}
<form method="POST" action="{{ route('services.update',$service) }}" style="display:inline;">
  @csrf @method('PUT')
  <input type="hidden" name="_status_only" value="1">
  <select name="status" class="form-select" style="display:inline;width:auto;padding:5px 10px;font-size:.8rem;font-weight:700;"
    onchange="this.form.submit()">
    @foreach(['pending'=>'Pending','in-progress'=>'In Progress','completed'=>'Completed','cancelled'=>'Cancelled'] as $val => $label)
    <option value="{{ $val }}" {{ $service->status===$val?'selected':'' }}>{{ $label }}</option>
    @endforeach
  </select>
</form>
    @endif
    @if($service->invoice_generated)
      <a href="{{ route('invoices.show',$service) }}" class="btn btn-outline btn-sm" style="border-color:rgba(255,255,255,.2);color:#fff;background:rgba(255,255,255,.08);"><i class="fas fa-file-invoice"></i> Invoice</a>
    @endif
    <a href="{{ route('services.edit',$service) }}" class="btn btn-outline btn-sm" style="border-color:rgba(255,255,255,.2);color:#fff;background:rgba(255,255,255,.08);"><i class="fas fa-edit"></i> Edit</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:18px;">
  <div>
    {{-- Vehicle & Customer --}}
    <div class="card anim-up d1" style="margin-bottom:16px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-car" style="color:var(--navy);margin-right:6px;"></i>Vehicle & Customer</div></div>
      <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
          <div style="font-size:.65rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Vehicle</div>
          @foreach([['Plate',strtoupper($service->vehicle->registration_no)],['Make',$service->vehicle->make.' '.$service->vehicle->model],['Year',$service->vehicle->year??'—'],['Color',$service->vehicle->color??'—'],['Category',$service->vehicle->category],['Mileage In',number_format($service->mileage_in).' km']] as [$l,$v])
          <div style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--g100);font-size:.8rem;"><span style="color:var(--g500);">{{ $l }}</span><span style="font-weight:600;color:var(--g800);">{{ $v }}</span></div>
          @endforeach
        </div>
        <div>
          <div style="font-size:.65rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Customer / Driver</div>
          @foreach([['Name',$service->vehicle->customer->name],['Phone',$service->vehicle->customer->phone],['Email',$service->vehicle->customer->email??'—'],['Driver',$service->driver_name??'—'],['Driver Ph.',$service->driver_phone??'—']] as [$l,$v])
          <div style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--g100);font-size:.8rem;"><span style="color:var(--g500);">{{ $l }}</span><span style="font-weight:600;color:var(--g800);">{{ $v }}</span></div>
          @endforeach
        </div>
      </div>
      @if($service->customer_complaint)
      <div style="padding:12px 20px;border-top:1px solid var(--g100);background:var(--amber-l);">
        <div style="font-size:.65rem;font-weight:700;color:var(--amber);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Customer Complaint</div>
        <div style="font-size:.83rem;color:var(--g700);font-style:italic;">"{{ $service->customer_complaint }}"</div>
      </div>
      @endif
    </div>

    {{-- Checklist --}}
    @if($service->checklist)
    <div class="card anim-up d2" style="margin-bottom:16px;">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-clipboard-list" style="color:var(--green);margin-right:6px;"></i>Service Checklist</div>
       @php
$doneCount = collect([
    'change_oil_filter','check_all_fluids','check_brake_fluid','check_washer_fluid',
    'check_transmission_fluid','check_tyre_pressure','check_tyre_condition','check_brakes',
    'check_shock_absorbers','check_belts_hoses','lubricate_chassis','replace_spark_plugs',
    'change_air_filter','check_leaks','check_engine_thermostat','inspect_cooling_system',
    'check_battery','check_wiper_blades','check_lights','check_exhaust'
])->filter(fn($key) => $service->checklist->$key)->count();
@endphp
<span class="badge badge-green">{{ $doneCount }} items done</span>
      </div>
      <div class="card-body">
        @php
        $groups=[
          'Engine & Fluids'=>['change_oil_filter'=>'Oil & Filter','check_all_fluids'=>'All Fluids','check_brake_fluid'=>'Brake Fluid','check_washer_fluid'=>'Washer Fluid','check_transmission_fluid'=>'Transmission Fluid'],
          'Tyres & Brakes'=>['check_tyre_pressure'=>'Tyre Pressure','check_tyre_condition'=>'Tyre Condition','check_brakes'=>'Brakes','check_shock_absorbers'=>'Shock Absorbers'],
          'Engine Components'=>['check_belts_hoses'=>'Belts & Hoses','lubricate_chassis'=>'Chassis Lube','replace_spark_plugs'=>'Spark Plugs','change_air_filter'=>'Air Filter','check_leaks'=>'Leak Check'],
          'Cooling'=>['check_engine_thermostat'=>'Thermostat','inspect_cooling_system'=>'Cooling System'],
          'Electrical'=>['check_battery'=>'Battery','check_wiper_blades'=>'Wipers','check_lights'=>'Lights','check_exhaust'=>'Exhaust'],
        ];
        @endphp
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
          @foreach($groups as $grp=>$items)
          <div>
            <div style="font-size:.65rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">{{ $grp }}</div>
            @foreach($items as $key=>$label)
            @php $done = $service->checklist->$key ?? false; @endphp
            <div style="display:flex;align-items:center;gap:7px;padding:4px 0;border-bottom:1px solid var(--g100);font-size:.78rem;">
              <i class="fas fa-{{ $done?'check-circle':'circle-xmark' }}" style="color:{{ $done?'var(--green)':'var(--red)' }};"></i>
              <span style="color:{{ $done?'var(--g800)':'var(--g400)' }};">{{ $label }}</span>
            </div>
            @endforeach
          </div>
          @endforeach
        </div>
        @if($service->checklist->additional_notes)
        <div style="margin-top:12px;padding:10px 14px;background:var(--g50);border-radius:var(--r-sm);font-size:.8rem;color:var(--g600);"><i class="fas fa-sticky-note" style="color:var(--purple);margin-right:6px;"></i>{{ $service->checklist->additional_notes }}</div>
        @endif
      </div>
    </div>
    @endif

    {{-- Parts --}}
    <div class="card anim-up d3" style="margin-bottom:16px;">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-cogs" style="color:var(--green);margin-right:6px;"></i>Parts Used</div>
        <a href="{{ route('services.parts.create',$service) }}" class="btn btn-green btn-sm"><i class="fas fa-plus"></i> Add Part</a>
      </div>
      @if($service->parts->count())
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>Part Name</th><th>Part No.</th><th>Qty</th><th>Unit Price</th><th>Total</th><th></th></tr></thead>
          <tbody>
            @foreach($service->parts as $p)
            <tr>
              <td style="font-weight:600;">{{ $p->name }}</td>
              <td><span style="font-family:var(--font-m);font-size:.75rem;color:var(--g400);">{{ $p->part_number??'—' }}</span></td>
              <td style="text-align:center;">{{ $p->quantity }}</td>
              <td style="font-family:var(--font-m);">KSh {{ number_format($p->unit_price,2) }}</td>
              <td style="font-family:var(--font-m);font-weight:700;color:var(--green);">KSh {{ number_format($p->total,2) }}</td>
              <td>
                <div class="tbl-actions">
                  <a href="{{ route('parts.edit',$p) }}" class="btn btn-ghost btn-sm btn-icon"><i class="fas fa-edit"></i></a>
                  <form action="{{ route('parts.destroy',$p) }}" method="POST" class="inline" onsubmit="return confirm('Remove part?')">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);"><i class="fas fa-trash"></i></button></form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot><tr style="background:var(--green-l);"><td colspan="4" style="padding:10px 16px;font-weight:700;color:var(--g700);">Parts Total</td><td style="padding:10px 16px;font-family:var(--font-m);font-weight:700;color:var(--green);">KSh {{ number_format($service->parts_total,2) }}</td><td></td></tr></tfoot>
        </table>
      </div>
      @else
      <div class="card-body"><div class="empty-state" style="padding:20px;"><div class="empty-icon" style="width:40px;height:40px;font-size:.9rem;"><i class="fas fa-cogs"></i></div><div class="empty-sub">No parts added yet.</div></div></div>
      @endif
    </div>

    {{-- Repairs --}}
    <div class="card anim-up d4" style="margin-bottom:16px;">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-tools" style="color:var(--purple);margin-right:6px;"></i>Repairs & Diagnostics</div>
        <a href="{{ route('services.repairs.create',$service) }}" class="btn btn-outline btn-sm" style="color:var(--purple);border-color:var(--purple);"><i class="fas fa-plus"></i> Add Repair</a>
      </div>
      @if($service->repairs->count())
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>Diagnosis</th><th>Action Taken</th><th>Status</th><th>Cost</th><th></th></tr></thead>
          <tbody>
            @foreach($service->repairs as $r)
            <tr>
              <td style="font-weight:600;">{{ $r->diagnosis }}</td>
              <td style="font-size:.8rem;color:var(--g500);">{{ Str::limit($r->action_taken,60)??'—' }}</td>
              <td><span class="badge {{ $r->status==='completed'?'badge-green':($r->status==='in-progress'?'badge-sky':'badge-amber') }}">{{ ucfirst($r->status) }}</span></td>
              <td style="font-family:var(--font-m);font-weight:700;color:var(--purple);">KSh {{ number_format($r->cost,2) }}</td>
              <td>
                <div class="tbl-actions">
                  <a href="{{ route('repairs.edit',$r) }}" class="btn btn-ghost btn-sm btn-icon"><i class="fas fa-edit"></i></a>
                  <form action="{{ route('repairs.destroy',$r) }}" method="POST" class="inline" onsubmit="return confirm('Delete repair?')">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);"><i class="fas fa-trash"></i></button></form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot><tr style="background:var(--purple-l);"><td colspan="3" style="padding:10px 16px;font-weight:700;color:var(--g700);">Repairs Total</td><td style="padding:10px 16px;font-family:var(--font-m);font-weight:700;color:var(--purple);">KSh {{ number_format($service->repairs_total,2) }}</td><td></td></tr></tfoot>
        </table>
      </div>
      @else
      <div class="card-body"><div class="empty-state" style="padding:20px;"><div class="empty-icon" style="width:40px;height:40px;font-size:.9rem;"><i class="fas fa-tools"></i></div><div class="empty-sub">No repairs logged yet.</div></div></div>
      @endif
    </div>
  </div>

  {{-- Right sidebar --}}
  <div>
    {{-- Cost summary --}}
    <div class="card anim-up d1" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-calculator" style="color:var(--orange);margin-right:6px;"></i>Cost Summary</div></div>
      <div class="card-body" style="padding:16px;">
        @foreach([['Parts','KSh '.number_format($service->parts_total,2),'green'],['Repairs','KSh '.number_format($service->repairs_total,2),'purple'],['Labour','KSh '.number_format($service->labour_charge,2),'sky']] as [$l,$v,$c])
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--g100);font-size:.82rem;"><span style="color:var(--g500);">{{ $l }}</span><span style="font-family:var(--font-m);font-weight:700;color:var(--{{ $c }});">{{ $v }}</span></div>
        @endforeach
        @if($service->discount > 0)
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--g100);font-size:.82rem;"><span style="color:var(--g500);">Discount</span><span style="font-family:var(--font-m);font-weight:700;color:var(--red);">− KSh {{ number_format($service->discount,2) }}</span></div>
        @endif
        @if($service->vat_amount > 0)
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--g100);font-size:.82rem;"><span style="color:var(--g500);">VAT</span><span style="font-family:var(--font-m);font-weight:700;color:var(--amber);">KSh {{ number_format($service->vat_amount,2) }}</span></div>
        @endif
        <div style="background:var(--navy);border-radius:var(--r-sm);padding:14px;margin-top:10px;display:flex;justify-content:space-between;align-items:center;">
          <span style="color:rgba(255,255,255,.6);font-size:.8rem;font-weight:600;">TOTAL DUE</span>
          <span style="font-family:var(--font-m);font-size:1.15rem;font-weight:700;color:#fff;">KSh {{ number_format($service->total_cost,2) }}</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:10px;">
          <div style="background:var(--green-l);border-radius:var(--r-sm);padding:10px;text-align:center;">
            <div style="font-size:.65rem;color:var(--green);font-weight:700;text-transform:uppercase;margin-bottom:3px;">Paid</div>
            <div style="font-family:var(--font-m);font-weight:700;color:var(--green);">KSh {{ number_format($service->amount_paid,2) }}</div>
          </div>
          <div style="background:{{ $service->balance>0?'var(--red-l)':'var(--green-l)' }};border-radius:var(--r-sm);padding:10px;text-align:center;">
            <div style="font-size:.65rem;color:{{ $service->balance>0?'var(--red)':'var(--green)' }};font-weight:700;text-transform:uppercase;margin-bottom:3px;">Balance</div>
            <div style="font-family:var(--font-m);font-weight:700;color:{{ $service->balance>0?'var(--red)':'var(--green)' }};">KSh {{ number_format($service->balance,2) }}</div>
          </div>
        </div>
        @if($service->balance > 0)
        <a href="{{ route('services.payments.create',$service) }}" class="btn btn-green" style="width:100%;justify-content:center;margin-top:10px;"><i class="fas fa-money-bill-wave"></i> Record Payment</a>
        @endif
      </div>
    </div>

    {{-- Service info --}}
    <div class="card anim-up d2" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-info-circle" style="color:var(--sky);margin-right:6px;"></i>Service Info</div></div>
      <div class="card-body" style="padding:14px;">
        @foreach([
          ['Date',$service->service_date->format('d M Y')],
          ['Mechanic',$service->mechanic?->name??'Unassigned'],
          ['Next Service',$service->next_service_date?->format('d M Y')??'—'],
          ['Next Km',number_format($service->next_service_km??0).' km'],
          ['Created',$service->created_at->format('d M Y H:i')],
        ] as [$l,$v])
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--g100);font-size:.78rem;"><span style="color:var(--g500);">{{ $l }}</span><span style="font-weight:600;color:var(--g800);">{{ $v }}</span></div>
        @endforeach
      </div>
    </div>

    {{-- Payments --}}
    <div class="card anim-up d3">
      <div class="card-header"><div class="card-title"><i class="fas fa-money-bill-wave" style="color:var(--green);margin-right:6px;"></i>Payments</div></div>
      <div class="card-body" style="padding:12px 16px;">
        @forelse($service->payments as $pmt)
        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--g100);">
          <div style="width:30px;height:30px;background:{{ $pmt->method==='M-Pesa'?'var(--green-l)':'var(--amber-l)' }};color:{{ $pmt->method==='M-Pesa'?'var(--green)':'var(--amber)' }};border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0;"><i class="fas fa-{{ $pmt->method==='M-Pesa'?'mobile-alt':'money-bill-wave' }}"></i></div>
          <div style="flex:1;"><div style="font-size:.78rem;font-weight:600;color:var(--g800);">{{ $pmt->method }}@if($pmt->mpesa_code) <span style="font-family:var(--font-m);font-size:.68rem;color:var(--g400);">{{ $pmt->mpesa_code }}</span>@endif</div><div style="font-size:.68rem;color:var(--g400);">{{ $pmt->payment_date->format('d M Y') }}</div></div>
          <div style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:var(--green);">KSh {{ number_format($pmt->amount,2) }}</div>
          <form action="{{ route('payments.destroy',$pmt) }}" method="POST" class="inline" onsubmit="return confirm('Remove payment?')">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);"><i class="fas fa-times"></i></button></form>
        </div>
        @empty
        <div class="empty-sub" style="text-align:center;padding:14px;font-size:.8rem;">No payments yet.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
</div>
@endsection