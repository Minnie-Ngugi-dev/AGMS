@extends('layouts.app')
@section('title','Vehicles')
@section('page-title','Vehicles')

@section('content')
<div class="page-hdr anim-up">
  <div class="page-hdr-left">
    <div class="page-hdr-title">Vehicles</div>
    <div class="page-hdr-sub">{{ $vehicles->total() }} registered vehicles</div>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('vehicles.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Register Vehicle</a>
  </div>
</div>

<div class="filter-bar anim-up d1">
  <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;width:100%;">
    <div style="flex:1;min-width:200px;">
      <div class="filter-label">Search</div>
      <div class="search-bar" style="padding:8px 12px;">
        <i class="fas fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Plate, make, customer…" style="background:none;border:none;outline:none;font-size:.82rem;color:var(--g700);width:100%;">
      </div>
    </div>
    <div class="filter-item">
      <div class="filter-label">Category</div>
      <select name="category" class="form-select" style="min-width:140px;">
        <option value="">All Categories</option>
        @foreach(['Car','Van','Mini Truck','Truck','Trailer'] as $cat)
        <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
        @endforeach
      </select>
    </div>
    <button type="submit" class="btn btn-navy"><i class="fas fa-search"></i> Search</button>
    @if(request()->hasAny(['search','category']))<a href="{{ route('vehicles.index') }}" class="btn btn-outline" style="color:var(--red);"><i class="fas fa-times"></i></a>@endif
  </form>
</div>

<div class="card anim-up d2">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr><th>Plate No.</th><th>Make / Model</th><th>Category</th><th>Owner</th><th>Mileage</th><th>Next Service</th><th>Services</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($vehicles as $v)
        <tr>
          <td>
            <div style="font-family:var(--font-m);font-size:.92rem;font-weight:700;color:var(--navy);">{{ $v->registration_no }}</div>
            <div style="font-size:.7rem;color:var(--g400);">{{ $v->color ?? '' }} · {{ $v->year ?? '' }}</div>
          </td>
          <td><div style="font-weight:600;color:var(--g800);">{{ $v->make }}</div><div style="font-size:.78rem;color:var(--g500);">{{ $v->model }}</div></td>
          <td><span class="badge badge-gray">{{ $v->category }}</span></td>
          <td><div style="font-weight:500;">{{ $v->customer->name }}</div><div style="font-size:.72rem;color:var(--g400);">{{ $v->customer->phone }}</div></td>
          <td style="font-family:var(--font-m);font-size:.82rem;">{{ number_format($v->current_mileage) }} km</td>
          <td>
            @if($v->next_service_date)
            <div style="font-size:.8rem;font-weight:600;color:{{ $v->isOverdue()?'var(--red)':'var(--g700)' }};">{{ $v->next_service_date->format('d M Y') }}</div>
            @if($v->isOverdue())<span class="badge badge-red" style="font-size:.62rem;">Overdue</span>@else<div style="font-size:.7rem;color:var(--g400);">{{ $v->next_service_date->diffForHumans() }}</div>@endif
            @else<span style="color:var(--g400);font-size:.78rem;">Not set</span>@endif
          </td>
          <td><span style="background:var(--orange-l);color:var(--orange);border-radius:20px;padding:2px 10px;font-size:.75rem;font-weight:700;">{{ $v->services->count() }}</span></td>
          <td>
            <div class="tbl-actions">
              <a href="{{ route('vehicles.show',$v) }}" class="btn btn-ghost btn-sm btn-icon" title="View"><i class="fas fa-eye"></i></a>
              <a href="{{ route('services.create') }}?vehicle={{ $v->id }}" class="btn btn-ghost btn-sm btn-icon" style="color:var(--orange);" title="New Service"><i class="fas fa-plus"></i></a>
              <a href="{{ route('vehicles.edit',$v) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
              <form action="{{ route('vehicles.destroy',$v) }}" method="POST" class="inline" onsubmit="return confirm('Delete {{ $v->registration_no }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="empty-state"><div class="empty-icon"><i class="fas fa-car"></i></div><div class="empty-title">No vehicles found</div><a href="{{ route('vehicles.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Register Vehicle</a></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($vehicles->hasPages())
  <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
    <div style="font-size:.78rem;color:var(--g500);">{{ $vehicles->firstItem() }}–{{ $vehicles->lastItem() }} of {{ $vehicles->total() }}</div>
    <div class="pagination">
      @if(!$vehicles->onFirstPage())<a href="{{ $vehicles->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>@endif
      @foreach($vehicles->getUrlRange(max(1,$vehicles->currentPage()-2),min($vehicles->lastPage(),$vehicles->currentPage()+2)) as $pg=>$url)
        <a href="{{ $url }}" class="page-link {{ $pg==$vehicles->currentPage()?'active':'' }}">{{ $pg }}</a>
      @endforeach
      @if($vehicles->hasMorePages())<a href="{{ $vehicles->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>@endif
    </div>
  </div>
  @endif
</div>
@endsection