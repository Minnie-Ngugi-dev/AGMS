@extends('layouts.app')
@section('title','Reports — By Service Type')
@section('page-title','Reports: By Service Type')

@section('content')
<div class="anim-up">

{{-- Filter --}}
<div class="filter-bar" style="margin-bottom:18px;">
  <form method="GET" action="{{ route('reports.service-type') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
    <div class="filter-item">
      <label class="filter-label">From</label>
      <input type="date" name="from" class="form-input" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}">
    </div>
    <div class="filter-item">
      <label class="filter-label">To</label>
      <input type="date" name="to" class="form-input" value="{{ request('to', now()->format('Y-m-d')) }}">
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('reports.service-type') }}" class="btn btn-outline">Reset</a>
  </form>
</div>

{{-- Regular vs Full comparison --}}
@php
  $regular = $services->where('service_type','Regular');
  $full    = $services->where('service_type','Full');
  $total   = $services->count();
  $regPct  = $total > 0 ? round(($regular->count()/$total)*100) : 0;
  $fullPct = $total > 0 ? round(($full->count()/$total)*100) : 0;
@endphp

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
  {{-- Regular --}}
  <div class="card" style="padding:20px;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
      <div class="stat-icon" style="background:var(--sky-l);color:var(--sky);"><i class="fas fa-oil-can"></i></div>
      <div>
        <div style="font-family:var(--font-h);font-size:1rem;font-weight:700;color:var(--g900);">Regular Service</div>
        <div style="font-size:.7rem;color:var(--g400);">5,000 km / 3-month interval</div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
      <div style="text-align:center;padding:10px;background:var(--sky-l);border-radius:var(--r-sm);">
        <div style="font-family:var(--font-h);font-size:1.4rem;font-weight:700;color:var(--sky);">{{ $regular->count() }}</div>
        <div style="font-size:.68rem;color:var(--g500);">Services</div>
      </div>
      <div style="text-align:center;padding:10px;background:var(--green-l);border-radius:var(--r-sm);">
        <div style="font-family:var(--font-h);font-size:1rem;font-weight:700;color:var(--green);">KSh {{ number_format($regular->sum('total_cost'),0) }}</div>
        <div style="font-size:.68rem;color:var(--g500);">Revenue</div>
      </div>
    </div>
    <div style="background:var(--g100);border-radius:4px;height:8px;margin-bottom:6px;">
      <div style="width:{{ $regPct }}%;background:var(--sky);height:8px;border-radius:4px;"></div>
    </div>
    <div style="font-size:.72rem;color:var(--g400);text-align:right;">{{ $regPct }}% of all services</div>
  </div>

  {{-- Full --}}
  <div class="card" style="padding:20px;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
      <div class="stat-icon" style="background:var(--purple-l);color:var(--purple);"><i class="fas fa-car-battery"></i></div>
      <div>
        <div style="font-family:var(--font-h);font-size:1rem;font-weight:700;color:var(--g900);">Full Service</div>
        <div style="font-size:.7rem;color:var(--g400);">10,000 km / 6-month interval</div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
      <div style="text-align:center;padding:10px;background:var(--purple-l);border-radius:var(--r-sm);">
        <div style="font-family:var(--font-h);font-size:1.4rem;font-weight:700;color:var(--purple);">{{ $full->count() }}</div>
        <div style="font-size:.68rem;color:var(--g500);">Services</div>
      </div>
      <div style="text-align:center;padding:10px;background:var(--green-l);border-radius:var(--r-sm);">
        <div style="font-family:var(--font-h);font-size:1rem;font-weight:700;color:var(--green);">KSh {{ number_format($full->sum('total_cost'),0) }}</div>
        <div style="font-size:.68rem;color:var(--g500);">Revenue</div>
      </div>
    </div>
    <div style="background:var(--g100);border-radius:4px;height:8px;margin-bottom:6px;">
      <div style="width:{{ $fullPct }}%;background:var(--purple);height:8px;border-radius:4px;"></div>
    </div>
    <div style="font-size:.72rem;color:var(--g400);text-align:right;">{{ $fullPct }}% of all services</div>
  </div>
</div>

{{-- Services table --}}
<div class="card d2">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-table" style="color:var(--orange);margin-right:8px;"></i> All Services</div>
    <span class="badge badge-navy">{{ $total }} records</span>
  </div>
  <div class="tbl-wrap">
    @if($services->isEmpty())
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-chart-pie"></i></div>
      <div class="empty-title">No services found</div>
      <div class="empty-sub">Adjust the date range above.</div>
    </div>
    @else
    <table class="tbl">
      <thead>
        <tr>
          <th>Job Card</th>
          <th>Date</th>
          <th>Vehicle</th>
          <th>Type</th>
          <th>Status</th>
          <th class="text-right">Total (KSh)</th>
          <th class="text-right">Paid (KSh)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($services->sortByDesc('service_date') as $s)
        <tr>
          <td><a href="{{ route('services.show',$s) }}" style="font-family:var(--font-m);color:var(--orange);font-weight:700;text-decoration:none;">{{ $s->job_card_no }}</a></td>
          <td>{{ $s->service_date->format('d M Y') }}</td>
          <td>
            @if($s->vehicle)
            <span style="font-family:var(--font-m);font-size:.78rem;font-weight:700;">{{ $s->vehicle->registration_no }}</span>
            <div style="font-size:.7rem;color:var(--g400);">{{ $s->vehicle->make }} {{ $s->vehicle->model }}</div>
            @endif
          </td>
          <td><span class="badge {{ $s->service_type=='Full'?'badge-purple':'badge-sky' }}">{{ $s->service_type }}</span></td>
          <td>
            @php $sc=['pending'=>'badge-amber','in-progress'=>'badge-sky','completed'=>'badge-green','cancelled'=>'badge-gray'] @endphp
            <span class="badge {{ $sc[$s->status]??'badge-gray' }}">{{ ucfirst($s->status) }}</span>
          </td>
          <td class="text-right" style="font-family:var(--font-m);font-weight:700;">{{ number_format($s->total_cost,0) }}</td>
          <td class="text-right" style="font-family:var(--font-m);font-weight:700;color:var(--green);">{{ number_format($s->amount_paid,0) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>
</div>
</div>
@endsection