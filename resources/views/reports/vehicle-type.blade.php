@extends('layouts.app')
@section('title','Reports — By Vehicle Type')
@section('page-title','Reports: By Vehicle Type')

@section('content')
<div class="anim-up">

{{-- Filter --}}
<div class="filter-bar" style="margin-bottom:18px;">
  <form method="GET" action="{{ route('reports.vehicle-type') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
    <div class="filter-item">
      <label class="filter-label">From</label>
      <input type="date" name="from" class="form-input" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}">
    </div>
    <div class="filter-item">
      <label class="filter-label">To</label>
      <input type="date" name="to" class="form-input" value="{{ request('to', now()->format('Y-m-d')) }}">
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('reports.vehicle-type') }}" class="btn btn-outline">Reset</a>
  </form>
</div>

{{-- Category cards --}}
@php
  $colors = ['Car'=>'orange','Van'=>'sky','Mini Truck'=>'purple','Truck'=>'amber','Trailer'=>'green'];
  $icons  = ['Car'=>'fa-car','Van'=>'fa-van-shuttle','Mini Truck'=>'fa-truck-pickup','Truck'=>'fa-truck','Trailer'=>'fa-trailer'];
  $total  = $stats->sum('count');
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:20px;">
  @forelse($stats as $row)
  @php
    $cat   = $row['category'] ?: 'Unknown';
    $clr   = $colors[$cat] ?? 'gray';
    $icon  = $icons[$cat]  ?? 'fa-car';
    $pct   = $total > 0 ? round(($row['count']/$total)*100) : 0;
  @endphp
  <div class="stat-card" style="flex-direction:column;align-items:flex-start;gap:10px;">
    <div style="display:flex;align-items:center;gap:10px;width:100%;">
      <div class="stat-icon" style="background:var(--{{ $clr }}-l);color:var(--{{ $clr }});"><i class="fas {{ $icon }}"></i></div>
      <div>
        <div style="font-family:var(--font-h);font-size:.82rem;font-weight:700;color:var(--g800);">{{ $cat }}</div>
        <div style="font-size:.68rem;color:var(--g400);">{{ $row['count'] }} services</div>
      </div>
      <div style="margin-left:auto;font-family:var(--font-m);font-size:1.1rem;font-weight:700;color:var(--{{ $clr }});">{{ $pct }}%</div>
    </div>
    <div style="width:100%;background:var(--g100);border-radius:4px;height:5px;">
      <div style="width:{{ $pct }}%;background:var(--{{ $clr }});height:5px;border-radius:4px;transition:width .5s;"></div>
    </div>
    <div style="font-family:var(--font-m);font-size:.78rem;color:var(--g500);">KSh {{ number_format($row['revenue'],0) }}</div>
  </div>
  @empty
  <div style="grid-column:1/-1;" class="empty-state">
    <div class="empty-icon"><i class="fas fa-car"></i></div>
    <div class="empty-title">No data for this period</div>
  </div>
  @endforelse
</div>

{{-- Summary table --}}
@if($stats->isNotEmpty())
<div class="card d2">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-table" style="color:var(--orange);margin-right:8px;"></i> Breakdown by Category</div>
  </div>
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Category</th>
          <th class="text-right">Services</th>
          <th class="text-right">% Share</th>
          <th class="text-right">Revenue (KSh)</th>
          <th class="text-right">% Revenue</th>
        </tr>
      </thead>
      <tbody>
        @php $totalRev = $stats->sum('revenue'); @endphp
        @foreach($stats as $row)
        @php
          $cat    = $row['category'] ?: 'Unknown';
          $clr    = $colors[$cat] ?? 'gray';
          $pct    = $total > 0 ? round(($row['count']/$total)*100,1) : 0;
          $revPct = $totalRev > 0 ? round(($row['revenue']/$totalRev)*100,1) : 0;
        @endphp
        <tr>
          <td>
            <span class="badge badge-{{ $clr }}"><i class="fas {{ $icons[$cat] ?? 'fa-car' }}" style="margin-right:4px;"></i>{{ $cat }}</span>
          </td>
          <td class="text-right" style="font-weight:700;">{{ $row['count'] }}</td>
          <td class="text-right">
            <span style="font-family:var(--font-m);font-size:.78rem;">{{ $pct }}%</span>
          </td>
          <td class="text-right" style="font-family:var(--font-m);font-weight:700;">{{ number_format($row['revenue'],0) }}</td>
          <td class="text-right">
            <span style="font-family:var(--font-m);font-size:.78rem;">{{ $revPct }}%</span>
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="background:var(--g50);font-weight:700;">
          <td style="padding:10px 16px;">TOTAL</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);">{{ $total }}</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);">100%</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);color:var(--green);">{{ number_format($totalRev,0) }}</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);">100%</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endif
</div>
@endsection