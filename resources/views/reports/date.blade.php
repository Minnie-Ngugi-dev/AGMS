@extends('layouts.app')
@section('title','Reports — By Date')
@section('page-title','Reports: By Date')

@section('content')
<div class="anim-up">

{{-- Filter bar --}}
<div class="filter-bar" style="margin-bottom:18px;">
  <form method="GET" action="{{ route('reports.date') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
    <div class="filter-item">
      <label class="filter-label">From</label>
      <input type="date" name="from" class="form-input" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}">
    </div>
    <div class="filter-item">
      <label class="filter-label">To</label>
      <input type="date" name="to" class="form-input" value="{{ request('to', now()->format('Y-m-d')) }}">
    </div>
    <div class="filter-item">
      <label class="filter-label">Type</label>
      <select name="type" class="form-select">
        <option value="">All Types</option>
        <option value="Regular" {{ request('type')=='Regular'?'selected':'' }}>Regular</option>
        <option value="Full"    {{ request('type')=='Full'?'selected':'' }}>Full</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('reports.date') }}" class="btn btn-outline">Reset</a>
  </form>
</div>

{{-- Summary stats --}}
<div class="stat-grid d1" style="margin-bottom:20px;">
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--navy-l);color:var(--navy);"><i class="fas fa-wrench"></i></div>
    <div><div class="stat-val">{{ $summary['total_services'] }}</div><div class="stat-lbl">Total Services</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--green-l);color:var(--green);"><i class="fas fa-check-circle"></i></div>
    <div><div class="stat-val">{{ $summary['completed'] }}</div><div class="stat-lbl">Completed</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--orange-l);color:var(--orange);"><i class="fas fa-money-bill-wave"></i></div>
    <div><div class="stat-val">KSh {{ number_format($summary['total_revenue'],0) }}</div><div class="stat-lbl">Revenue</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--sky-l);color:var(--sky);"><i class="fas fa-chart-line"></i></div>
    <div><div class="stat-val">KSh {{ number_format($summary['avg_service_value'],0) }}</div><div class="stat-lbl">Avg Value</div></div>
  </div>
</div>

{{-- Services table --}}
<div class="card d2">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-table" style="color:var(--orange);margin-right:8px;"></i> Services in Period</div>
    <span class="badge badge-navy">{{ $services->count() }} records</span>
  </div>
  <div class="tbl-wrap">
    @if($services->isEmpty())
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-chart-bar"></i></div>
      <div class="empty-title">No services in this period</div>
      <div class="empty-sub">Try adjusting your date range.</div>
    </div>
    @else
    <table class="tbl">
      <thead>
        <tr>
          <th>Job Card</th>
          <th>Date</th>
          <th>Vehicle</th>
          <th>Customer</th>
          <th>Type</th>
          <th>Status</th>
          <th class="text-right">Parts</th>
          <th class="text-right">Labour</th>
          <th class="text-right">Total</th>
          <th class="text-right">Paid</th>
        </tr>
      </thead>
      <tbody>
        @foreach($services as $s)
        <tr>
          <td><a href="{{ route('services.show',$s) }}" style="font-family:var(--font-m);color:var(--orange);font-weight:700;text-decoration:none;">{{ $s->job_card_no }}</a></td>
          <td>{{ $s->service_date->format('d M Y') }}</td>
          <td>
            @if($s->vehicle)
            <span style="font-family:var(--font-m);font-size:.78rem;font-weight:700;">{{ $s->vehicle->registration_no }}</span>
            <div style="font-size:.7rem;color:var(--g400);">{{ $s->vehicle->make }} {{ $s->vehicle->model }}</div>
            @else <span class="badge badge-gray">N/A</span> @endif
          </td>
          <td>{{ $s->vehicle->customer->name ?? '—' }}</td>
          <td>
            <span class="badge {{ $s->service_type=='Full'?'badge-purple':'badge-sky' }}">{{ $s->service_type }}</span>
          </td>
          <td>
            @php $sc=['pending'=>'badge-amber','in-progress'=>'badge-sky','completed'=>'badge-green','cancelled'=>'badge-gray'] @endphp
            <span class="badge {{ $sc[$s->status]??'badge-gray' }}">{{ ucfirst($s->status) }}</span>
          </td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.78rem;">{{ number_format($s->parts_total,0) }}</td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.78rem;">{{ number_format($s->labour_charge,0) }}</td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:var(--navy);">{{ number_format($s->total_cost,0) }}</td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:var(--green);">{{ number_format($s->amount_paid,0) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="background:var(--g50);font-weight:700;">
          <td colspan="6" style="padding:10px 16px;font-size:.78rem;color:var(--g500);">TOTALS</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);font-size:.82rem;">{{ number_format($services->sum('parts_total'),0) }}</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);font-size:.82rem;">{{ number_format($services->sum('labour_charge'),0) }}</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);font-size:.82rem;color:var(--navy);">{{ number_format($services->sum('total_cost'),0) }}</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);font-size:.82rem;color:var(--green);">{{ number_format($services->sum('amount_paid'),0) }}</td>
        </tr>
      </tfoot>
    </table>
    @endif
  </div>
</div>
</div>
@endsection