@extends('layouts.app')
@section('title', $vehicle->registration_no)
@section('page-title','Vehicle Profile')

@section('content')
<div class="anim-up">

{{-- Hero --}}
<div style="background:linear-gradient(135deg,var(--navy),var(--navy-m));border-radius:var(--r-lg);padding:26px 30px;margin-bottom:20px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
  <div style="width:64px;height:64px;background:var(--orange);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;flex-shrink:0;"><i class="fas fa-car"></i></div>
  <div style="flex:1;">
    <div style="font-family:var(--font-m);font-size:2rem;font-weight:700;color:#fff;letter-spacing:3px;">{{ $vehicle->registration_no }}</div>
    <div style="font-size:.88rem;color:rgba(255,255,255,.55);margin-top:4px;">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }} · {{ $vehicle->color }}</div>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    @foreach([['badge-gray',$vehicle->category],['badge-sky',$vehicle->make.' '.$vehicle->model]] as [$cls,$lbl])
    <span class="badge {{ $cls }}">{{ $lbl }}</span>
    @endforeach
    @if($vehicle->isOverdue())<span class="badge badge-red"><i class="fas fa-triangle-exclamation"></i> Overdue</span>@endif
  </div>
  <div style="display:flex;gap:8px;">
    <a href="{{ route('vehicles.edit',$vehicle) }}" class="btn btn-outline btn-sm" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:#fff;"><i class="fas fa-edit"></i> Edit</a>
    <a href="{{ route('services.create') }}?vehicle={{ $vehicle->id }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Service</a>
  </div>
</div>

{{-- Stats strip --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;" class="anim-up d1">
  @foreach([
    ['Current Mileage',number_format($vehicle->current_mileage).' km','fas fa-gauge','orange'],
    ['Total Services',$vehicle->services->count(),'fas fa-wrench','sky'],
    ['Next Service',$vehicle->next_service_date?->format('d M Y')??'Not set','fas fa-calendar-check','green'],
    ['Next Service km',number_format($vehicle->next_service_km??0).' km','fas fa-road','purple'],
  ] as [$lbl,$val,$ico,$clr])
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--{{ $clr }}-l);color:var(--{{ $clr }});"><i class="{{ $ico }}"></i></div>
    <div class="stat-info"><div class="stat-val" style="font-size:1rem;">{{ $val }}</div><div class="stat-lbl">{{ $lbl }}</div></div>
  </div>
  @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:18px;">
  {{-- Service Timeline --}}
  <div class="card anim-up d2">
    <div class="card-header"><div class="card-title"><i class="fas fa-history" style="color:var(--orange);margin-right:6px;"></i>Service History</div></div>
    <div class="card-body">
      @if($vehicle->services->count())
      <ul class="timeline">
        @foreach($vehicle->services->sortByDesc('service_date') as $svc)
        <li class="tl-item">
          <div class="tl-dot" style="background:{{ $svc->status==='completed'?'var(--green-l)':($svc->status==='in-progress'?'var(--sky-l)':'var(--amber-l)') }};color:{{ $svc->status==='completed'?'var(--green)':($svc->status==='in-progress'?'var(--sky)':'var(--amber)') }};"><i class="fas fa-{{ $svc->status==='completed'?'check':'wrench' }}"></i></div>
          <div class="tl-body">
            <div class="tl-date">{{ $svc->service_date->format('d M Y') }}</div>
            <div class="tl-title">
              <a href="{{ route('services.show',$svc) }}" style="color:var(--navy);text-decoration:none;">{{ $svc->job_card_no }}</a>
              &nbsp;<span class="badge {{ $svc->service_type==='Full'?'badge-orange':'badge-sky' }}">{{ $svc->service_type }}</span>
            </div>
            <div style="display:flex;gap:10px;margin-top:6px;flex-wrap:wrap;">
              <span style="font-size:.75rem;color:var(--g500);"><i class="fas fa-gauge" style="color:var(--orange);margin-right:3px;"></i>{{ number_format($svc->mileage_in) }} km</span>
              @if($svc->mechanic)<span style="font-size:.75rem;color:var(--g500);"><i class="fas fa-user-tie" style="color:var(--sky);margin-right:3px;"></i>{{ $svc->mechanic->name }}</span>@endif
              <span style="font-family:var(--font-m);font-size:.75rem;font-weight:700;color:var(--green);">KSh {{ number_format($svc->total_cost) }}</span>
              @php $ps=$svc->payment_status; @endphp
              <span class="badge {{ $ps==='Paid'?'badge-green':($ps==='Partial'?'badge-amber':'badge-red') }}">{{ $ps }}</span>
            </div>
            @if($svc->parts->count()||$svc->repairs->count())
            <div style="margin-top:8px;font-size:.75rem;color:var(--g500);">
              @if($svc->parts->count())<span style="margin-right:10px;"><i class="fas fa-cogs" style="color:var(--green);margin-right:3px;"></i>{{ $svc->parts->count() }} part(s)</span>@endif
              @if($svc->repairs->count())<span><i class="fas fa-tools" style="color:var(--purple);margin-right:3px;"></i>{{ $svc->repairs->count() }} repair(s)</span>@endif
            </div>
            @endif
          </div>
        </li>
        @endforeach
      </ul>
      @else
      <div class="empty-state" style="padding:30px;"><div class="empty-icon"><i class="fas fa-wrench"></i></div><div class="empty-title">No services yet</div><a href="{{ route('services.create') }}?vehicle={{ $vehicle->id }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create First Service</a></div>
      @endif
    </div>
  </div>

  {{-- Sidebar --}}
  <div>
    <div class="card anim-up d3" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-user" style="color:var(--orange);margin-right:6px;"></i>Owner</div></div>
      <div class="card-body" style="padding:14px;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
          <div style="width:40px;height:40px;border-radius:50%;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0;">{{ strtoupper(substr($vehicle->customer->name,0,1)) }}</div>
          <div><div style="font-weight:700;color:var(--g800);">{{ $vehicle->customer->name }}</div><div style="font-size:.75rem;color:var(--g400);">{{ $vehicle->customer->phone }}</div></div>
        </div>
        <a href="{{ route('customers.show',$vehicle->customer) }}" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;"><i class="fas fa-eye"></i> View Customer</a>
      </div>
    </div>

    <div class="card anim-up d4" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-info-circle" style="color:var(--sky);margin-right:6px;"></i>Vehicle Info</div></div>
      <div class="card-body" style="padding:14px;">
        @foreach([
          ['Registration',strtoupper($vehicle->registration_no)],
          ['Make',$vehicle->make],
          ['Model',$vehicle->model],
          ['Year',$vehicle->year??'—'],
          ['Color',$vehicle->color??'—'],
          ['Category',$vehicle->category],
          ['Chassis',$vehicle->chassis_no??'—'],
        ] as [$l,$v])
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--g100);font-size:.82rem;"><span style="color:var(--g500);">{{ $l }}</span><span style="font-weight:600;color:var(--g800);">{{ $v }}</span></div>
        @endforeach
      </div>
    </div>

    @if($vehicle->isOverdue())
    <div style="background:var(--red-l);border:1px solid #fca5a5;border-radius:var(--r-sm);padding:14px;margin-bottom:14px;" class="anim-up d5">
      <div style="display:flex;align-items:center;gap:8px;font-weight:700;color:var(--red);margin-bottom:6px;"><i class="fas fa-triangle-exclamation"></i> Service Overdue</div>
      <div style="font-size:.78rem;color:var(--g600);">This vehicle's service was due <strong>{{ $vehicle->next_service_date->diffForHumans() }}</strong>. Please schedule a service as soon as possible.</div>
      <a href="{{ route('services.create') }}?vehicle={{ $vehicle->id }}" class="btn btn-red btn-sm" style="margin-top:10px;width:100%;justify-content:center;"><i class="fas fa-plus"></i> Schedule Now</a>
    </div>
    @endif
  </div>
</div>
</div>
@endsection