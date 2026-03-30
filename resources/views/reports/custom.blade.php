@extends('layouts.app')
@section('title','Reports — Custom')
@section('page-title','Reports: Custom')

@section('content')
<div class="anim-up">

{{-- Filter form --}}
<div class="card" style="margin-bottom:20px;">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-sliders" style="color:var(--orange);margin-right:8px;"></i> Custom Filters</div>
  </div>
  <div class="card-body">
    <form method="GET" action="{{ route('reports.custom') }}">
      <div class="form-grid" style="margin-bottom:14px;">
        <div class="form-group">
          <label class="form-label">From Date</label>
          <input type="date" name="from" class="form-input" value="{{ request('from') }}">
        </div>
        <div class="form-group">
          <label class="form-label">To Date</label>
          <input type="date" name="to" class="form-input" value="{{ request('to') }}">
        </div>
        <div class="form-group">
          <label class="form-label">Service Type</label>
          <select name="type" class="form-select">
            <option value="">All Types</option>
            <option value="Regular" {{ request('type')=='Regular'?'selected':'' }}>Regular</option>
            <option value="Full"    {{ request('type')=='Full'?'selected':'' }}>Full</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="">All Statuses</option>
            <option value="pending"     {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="in-progress" {{ request('status')=='in-progress'?'selected':'' }}>In Progress</option>
            <option value="completed"   {{ request('status')=='completed'?'selected':'' }}>Completed</option>
            <option value="cancelled"   {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Vehicle Category</label>
          <select name="category" class="form-select">
            <option value="">All Categories</option>
            @foreach(['Car','Van','Mini Truck','Truck','Trailer'] as $cat)
            <option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Payment Status</label>
          <select name="payment_status" class="form-select">
            <option value="">All</option>
            <option value="Unpaid"  {{ request('payment_status')=='Unpaid'?'selected':'' }}>Unpaid</option>
            <option value="Partial" {{ request('payment_status')=='Partial'?'selected':'' }}>Partial</option>
            <option value="Paid"    {{ request('payment_status')=='Paid'?'selected':'' }}>Paid</option>
          </select>
        </div>
      </div>
      <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Run Report</button>
        <a href="{{ route('reports.custom') }}" class="btn btn-outline">Reset</a>
      </div>
    </form>
  </div>
</div>

@if(request()->anyFilled(['from','to','type','status','category','payment_status']))

{{-- Summary --}}
<div class="stat-grid d1" style="margin-bottom:20px;">
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--navy-l);color:var(--navy);"><i class="fas fa-list"></i></div>
    <div><div class="stat-val">{{ $services->count() }}</div><div class="stat-lbl">Results</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--green-l);color:var(--green);"><i class="fas fa-money-bill-wave"></i></div>
    <div><div class="stat-val">KSh {{ number_format($services->sum('amount_paid'),0) }}</div><div class="stat-lbl">Total Paid</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--red-l);color:var(--red);"><i class="fas fa-clock"></i></div>
    <div><div class="stat-val">KSh {{ number_format($services->sum('balance'),0) }}</div><div class="stat-lbl">Outstanding</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--orange-l);color:var(--orange);"><i class="fas fa-chart-bar"></i></div>
    <div><div class="stat-val">KSh {{ number_format($services->sum('total_cost'),0) }}</div><div class="stat-lbl">Total Value</div></div>
  </div>
</div>

{{-- Results table --}}
<div class="card d2">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-table" style="color:var(--orange);margin-right:8px;"></i> Results</div>
    <span class="badge badge-navy">{{ $services->count() }} records</span>
  </div>
  <div class="tbl-wrap">
    @if($services->isEmpty())
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-search"></i></div>
      <div class="empty-title">No results match your filters</div>
      <div class="empty-sub">Try adjusting your criteria above.</div>
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
          <th class="text-right">Total</th>
          <th class="text-right">Paid</th>
          <th class="text-right">Balance</th>
        </tr>
      </thead>
      <tbody>
        @foreach($services->sortByDesc('service_date') as $s)
        @php $sc=['pending'=>'badge-amber','in-progress'=>'badge-sky','completed'=>'badge-green','cancelled'=>'badge-gray'] @endphp
        <tr>
          <td><a href="{{ route('services.show',$s) }}" style="font-family:var(--font-m);color:var(--orange);font-weight:700;text-decoration:none;">{{ $s->job_card_no }}</a></td>
          <td>{{ $s->service_date->format('d M Y') }}</td>
          <td>
            @if($s->vehicle)
            <span style="font-family:var(--font-m);font-size:.78rem;font-weight:700;">{{ $s->vehicle->registration_no }}</span>
            <div style="font-size:.7rem;color:var(--g400);">{{ $s->vehicle->make }} {{ $s->vehicle->model }}</div>
            @endif
          </td>
          <td style="font-size:.82rem;">{{ $s->vehicle->customer->name ?? '—' }}</td>
          <td><span class="badge {{ $s->service_type=='Full'?'badge-purple':'badge-sky' }}">{{ $s->service_type }}</span></td>
          <td><span class="badge {{ $sc[$s->status]??'badge-gray' }}">{{ ucfirst($s->status) }}</span></td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.82rem;font-weight:700;">{{ number_format($s->total_cost,0) }}</td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:var(--green);">{{ number_format($s->amount_paid,0) }}</td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:{{ $s->balance > 0 ? 'var(--red)' : 'var(--green)' }};">
            {{ number_format($s->balance,0) }}
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="background:var(--g50);font-weight:700;">
          <td colspan="6" style="padding:10px 16px;font-size:.78rem;color:var(--g500);">TOTALS</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);">{{ number_format($services->sum('total_cost'),0) }}</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);color:var(--green);">{{ number_format($services->sum('amount_paid'),0) }}</td>
          <td class="text-right" style="padding:10px 16px;font-family:var(--font-m);color:var(--red);">{{ number_format($services->sum('balance'),0) }}</td>
        </tr>
      </tfoot>
    </table>
    @endif
  </div>
</div>

@else
{{-- No filters applied yet --}}
<div class="card">
  <div class="empty-state" style="padding:50px;">
    <div class="empty-icon"><i class="fas fa-sliders"></i></div>
    <div class="empty-title">Apply filters to generate a report</div>
    <div class="empty-sub">Use the form above to set your criteria then click Run Report.</div>
  </div>
</div>
@endif

</div>
@endsection