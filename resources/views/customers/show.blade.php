@extends('layouts.app')
@section('title', $customer->name)
@section('page-title','Customer Profile')

@section('content')
<div class="anim-up">
{{-- Hero --}}
<div style="background:linear-gradient(135deg,var(--navy),var(--navy-m));border-radius:var(--r-lg);padding:28px 32px;margin-bottom:20px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
  <div style="width:72px;height:72px;border-radius:50%;background:var(--orange);display:flex;align-items:center;justify-content:center;font-family:var(--font-h);font-size:1.8rem;font-weight:700;color:#fff;flex-shrink:0;">{{ strtoupper(substr($customer->name,0,1)) }}</div>
  <div style="flex:1;">
    <div style="font-family:var(--font-h);font-size:1.5rem;font-weight:700;color:#fff;">{{ $customer->name }}</div>
    <div style="font-size:.85rem;color:rgba(255,255,255,.55);margin-top:4px;"><i class="fas fa-phone" style="margin-right:5px;"></i>{{ $customer->phone }}@if($customer->email) &nbsp;·&nbsp;<i class="fas fa-envelope" style="margin-right:5px;"></i>{{ $customer->email }}@endif</div>
    @if($customer->address)<div style="font-size:.8rem;color:rgba(255,255,255,.4);margin-top:3px;"><i class="fas fa-map-marker-alt" style="margin-right:5px;"></i>{{ $customer->address }}</div>@endif
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <div style="text-align:center;padding:12px 20px;background:rgba(255,255,255,.06);border-radius:var(--r-sm);"><div style="font-family:var(--font-m);font-size:1.4rem;font-weight:700;color:#fff;">{{ $customer->vehicles->count() }}</div><div style="font-size:.68rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;">Vehicles</div></div>
    <div style="text-align:center;padding:12px 20px;background:rgba(255,255,255,.06);border-radius:var(--r-sm);"><div style="font-family:var(--font-m);font-size:1.4rem;font-weight:700;color:#fff;">{{ $customer->vehicles->sum(fn($v)=>$v->services->count()) }}</div><div style="font-size:.68rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;">Services</div></div>
  </div>
  <div style="display:flex;gap:8px;">
    <a href="{{ route('customers.edit',$customer) }}" class="btn btn-outline btn-sm" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:#fff;"><i class="fas fa-edit"></i> Edit</a>
    <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Service</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:18px;">
  <div>
    @forelse($customer->vehicles as $vehicle)
    <div class="card" style="margin-bottom:16px;">
      <div class="card-header">
        <div style="display:flex;align-items:center;gap:12px;">
          <div style="width:40px;height:40px;background:var(--navy);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;"><i class="fas fa-car"></i></div>
          <div>
            <div style="font-family:var(--font-m);font-size:1rem;font-weight:700;color:var(--green);">{{ $vehicle->registration_no }}</div>
            <div style="font-size:.75rem;color:var(--g400);">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }} · {{ $vehicle->color }}</div>
          </div>
        </div>
        <div style="display:flex;gap:8px;">
          <span class="badge badge-gray">{{ $vehicle->category }}</span>
          @if($vehicle->isOverdue())<span class="badge badge-red"><i class="fas fa-triangle-exclamation"></i> Overdue</span>@endif
          <a href="{{ route('vehicles.show',$vehicle) }}" class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i> View</a>
        </div>
      </div>
      <div class="card-body" style="padding:14px 22px;">
        <div style="display:flex;gap:16px;margin-bottom:14px;font-size:.8rem;flex-wrap:wrap;">
          <span style="color:var(--g500);"><i class="fas fa-gauge" style="margin-right:4px;color:var(--orange);"></i>{{ number_format($vehicle->current_mileage) }} km</span>
          <span style="color:var(--g500);"><i class="fas fa-calendar-check" style="margin-right:4px;color:var(--sky);"></i>Next: {{ $vehicle->next_service_date?->format('d M Y') ?? 'Not set' }}</span>
        </div>
        @if($vehicle->services->count())
        <div style="font-size:.68rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Recent Services</div>
        @foreach($vehicle->services->sortByDesc('service_date')->take(3) as $svc)
        <div style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:var(--g50);border-radius:var(--r-sm);border:1px solid var(--g100);margin-bottom:6px;">
          <span style="font-family:var(--font-m);font-size:.76rem;background:var(--navy);color:#fff;padding:2px 8px;border-radius:4px;">{{ $svc->job_card_no }}</span>
          <span class="badge {{ $svc->service_type==='Full'?'badge-orange':'badge-sky' }}">{{ $svc->service_type }}</span>
          <span style="font-size:.78rem;color:var(--g500);">{{ $svc->service_date->format('d M Y') }}</span>
          @php $ps=$svc->payment_status; @endphp
          <span class="badge {{ $ps==='Paid'?'badge-green':($ps==='Partial'?'badge-amber':'badge-red') }}">{{ $ps }}</span>
          <span style="margin-left:auto;font-family:var(--font-m);font-size:.82rem;font-weight:700;">KSh {{ number_format($svc->total_cost) }}</span>
          <a href="{{ route('services.show',$svc) }}" class="btn btn-ghost btn-sm btn-icon"><i class="fas fa-eye"></i></a>
        </div>
        @endforeach
        @else
        <div style="font-size:.8rem;color:var(--g400);text-align:center;padding:12px;">No services yet for this vehicle.</div>
        @endif
      </div>
    </div>
    @empty
    <div class="card"><div class="card-body"><div class="empty-state" style="padding:30px;"><div class="empty-icon"><i class="fas fa-car"></i></div><div class="empty-title">No Vehicles Registered</div><a href="{{ route('vehicles.create') }}?customer={{ $customer->id }}" class="btn btn-primary"><i class="fas fa-plus"></i> Register Vehicle</a></div></div></div>
    @endforelse
  </div>

  <div>
    <div class="card" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-info-circle" style="color:var(--orange);margin-right:6px;"></i>Details</div></div>
      <div class="card-body" style="padding:14px;">
        @foreach([['Name',$customer->name],['Phone',$customer->phone],['Email',$customer->email??'—'],['Address',$customer->address??'—'],['Since',$customer->created_at->format('d M Y')]] as [$l,$v])
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--g100);font-size:.82rem;"><span style="color:var(--g500);">{{ $l }}</span><span style="font-weight:600;color:var(--g800);">{{ $v }}</span></div>
        @endforeach
      </div>
    </div>
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-tools" style="color:var(--sky);margin-right:6px;"></i>Quick Actions</div></div>
      <div class="card-body" style="padding:14px;">
        <a href="{{ route('vehicles.create') }}?customer={{ $customer->id }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-bottom:8px;"><i class="fas fa-car"></i> Register Vehicle</a>
        <a href="{{ route('services.create') }}" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:8px;"><i class="fas fa-plus"></i> New Service</a>
        <a href="{{ route('customers.edit',$customer) }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-bottom:8px;"><i class="fas fa-edit"></i> Edit Customer</a>
        <form action="{{ route('customers.destroy',$customer) }}" method="POST" onsubmit="return confirm('Delete this customer?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center;color:var(--red);border-color:var(--red);"><i class="fas fa-trash"></i> Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
@endsection