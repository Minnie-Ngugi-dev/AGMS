@extends('layouts.app')
@section('title','Services')
@section('page-title','Services')

@section('content')
<div class="page-hdr anim-up">
  <div class="page-hdr-left">
    <div class="page-hdr-title">Services</div>
    <div class="page-hdr-sub">{{ $services->total() }} total records</div>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('services.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Service</a>
  </div>
</div>

{{-- Quick tabs --}}
<div style="display:flex;gap:6px;margin-bottom:16px;flex-wrap:wrap;" class="anim-up d1">
  @foreach([''=>'All','pending'=>'Pending','in-progress'=>'In Progress','completed'=>'Completed','cancelled'=>'Cancelled'] as $val=>$lbl)
  <a href="{{ route('services.index') }}?status={{ $val }}" style="padding:7px 14px;border-radius:var(--r-sm);font-size:.78rem;font-weight:600;text-decoration:none;border:1.5px solid;transition:all .18s;{{ request('status')===$val?'background:var(--navy);border-color:var(--navy);color:#fff;':'background:#fff;border-color:var(--g200);color:var(--g600);' }}">{{ $lbl }}</a>
  @endforeach
</div>

<div class="filter-bar anim-up d2">
  <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;width:100%;">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <div style="flex:1;min-width:200px;">
      <div class="filter-label">Search</div>
      <div class="search-bar" style="padding:8px 12px;">
        <i class="fas fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Plate, customer, job card…" style="background:none;border:none;outline:none;font-size:.82rem;color:var(--g700);width:100%;">
      </div>
    </div>
    <div class="filter-item">
      <div class="filter-label">Service Type</div>
      <select name="service_type" class="form-select" style="min-width:130px;">
        <option value="">All Types</option>
        <option value="Regular" {{ request('service_type')==='Regular'?'selected':'' }}>Regular</option>
        <option value="Full"    {{ request('service_type')==='Full'   ?'selected':'' }}>Full</option>
      </select>
    </div>
    <div class="filter-item">
      <div class="filter-label">Date</div>
      <input type="date" name="date" class="form-input" value="{{ request('date') }}" style="min-width:150px;">
    </div>
    <button type="submit" class="btn btn-navy"><i class="fas fa-search"></i> Filter</button>
    @if(request()->hasAny(['search','service_type','date']))<a href="{{ route('services.index') }}?status={{ request('status') }}" class="btn btn-outline" style="color:var(--red);"><i class="fas fa-times"></i></a>@endif
  </form>
</div>

<div class="card anim-up d3">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr><th>Job Card</th><th>Vehicle</th><th>Customer</th><th>Type</th><th>Date</th><th>Mechanic</th><th>Payment</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($services as $s)
        <tr>
          <td><span style="font-family:var(--font-m);font-size:.8rem;font-weight:700;color:var(--navy);">{{ $s->job_card_no }}</span></td>
          <td><span style="font-family:var(--font-m);font-weight:700;color:var(--green);">{{ $s->vehicle->registration_no }}</span></td>
          <td style="font-size:.82rem;">{{ $s->vehicle->customer->name }}</td>
          <td><span class="badge {{ $s->service_type==='Full'?'badge-orange':'badge-sky' }}">{{ $s->service_type }}</span></td>
          <td style="font-size:.8rem;">{{ $s->service_date->format('d M Y') }}</td>
          <td style="font-size:.78rem;color:var(--g500);">{{ $s->mechanic?->name ?? '—' }}</td>
          <td>
            @php $ps=$s->payment_status; @endphp
            <span class="badge {{ $ps==='Paid'?'badge-green':($ps==='Partial'?'badge-amber':'badge-red') }}">{{ $ps }}</span>
          </td>
          <td>
            @php $sc=['pending'=>'badge-amber','in-progress'=>'badge-sky','completed'=>'badge-green','cancelled'=>'badge-red']; @endphp
            <span class="badge {{ $sc[$s->status]??'badge-gray' }}">{{ ucfirst(str_replace('-',' ',$s->status)) }}</span>
          </td>
          <td>
            <div class="tbl-actions">
              <a href="{{ route('services.show',$s) }}" class="btn btn-ghost btn-sm btn-icon" title="View"><i class="fas fa-eye"></i></a>
              <a href="{{ route('services.edit',$s) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
              <form action="{{ route('services.destroy',$s) }}" method="POST" class="inline" onsubmit="return confirm('Delete service {{ $s->job_card_no }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9"><div class="empty-state"><div class="empty-icon"><i class="fas fa-wrench"></i></div><div class="empty-title">No services found</div><a href="{{ route('services.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create First Service</a></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($services->hasPages())
  <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
    <div style="font-size:.78rem;color:var(--g500);">{{ $services->firstItem() }}–{{ $services->lastItem() }} of {{ $services->total() }}</div>
    <div class="pagination">
      @if(!$services->onFirstPage())<a href="{{ $services->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>@endif
      @foreach($services->getUrlRange(max(1,$services->currentPage()-2),min($services->lastPage(),$services->currentPage()+2)) as $pg=>$url)
        <a href="{{ $url }}" class="page-link {{ $pg==$services->currentPage()?'active':'' }}">{{ $pg }}</a>
      @endforeach
      @if($services->hasMorePages())<a href="{{ $services->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>@endif
    </div>
  </div>
  @endif
</div>
@endsection