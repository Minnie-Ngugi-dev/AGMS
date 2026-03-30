@extends('layouts.app')
@section('title','Customers')
@section('page-title','Customers')

@section('content')
<div class="page-hdr anim-up">
  <div class="page-hdr-left">
    <div class="page-hdr-title">Customers</div>
    <div class="page-hdr-sub">{{ $customers->total() }} registered customers</div>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add Customer</a>
  </div>
</div>

<div class="filter-bar anim-up d1">
  <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;width:100%;">
    <div style="flex:1;min-width:220px;">
      <div class="filter-label">Search</div>
      <div class="search-bar" style="padding:8px 12px;">
        <i class="fas fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, phone or email…" style="background:none;border:none;outline:none;font-size:.82rem;color:var(--g700);width:100%;">
      </div>
    </div>
    <button type="submit" class="btn btn-navy"><i class="fas fa-search"></i> Search</button>
    @if(request('search'))<a href="{{ route('customers.index') }}" class="btn btn-outline" style="color:var(--red);"><i class="fas fa-times"></i></a>@endif
  </form>
</div>

<div class="card anim-up d2">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr><th>Customer</th><th>Phone</th><th>Email</th><th>Vehicles</th><th>Services</th><th>Last Service</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($customers as $c)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:36px;height:36px;border-radius:50%;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.82rem;flex-shrink:0;">{{ strtoupper(substr($c->name,0,1)) }}</div>
              <div>
                <div style="font-weight:600;color:var(--g800);">{{ $c->name }}</div>
                <div style="font-size:.68rem;color:var(--g400);">Since {{ $c->created_at->format('M Y') }}</div>
              </div>
            </div>
          </td>
          <td style="font-family:var(--font-m);font-size:.82rem;">{{ $c->phone }}</td>
          <td style="font-size:.8rem;color:var(--g500);">{{ $c->email ?? '—' }}</td>
          <td><span style="background:var(--navy-l);color:var(--navy);padding:2px 10px;border-radius:20px;font-size:.75rem;font-weight:700;">{{ $c->vehicles_count }}</span></td>
          <td><span style="background:var(--sky-l);color:var(--sky);padding:2px 10px;border-radius:20px;font-size:.75rem;font-weight:700;">{{ $c->services_count }}</span></td>
          <td style="font-size:.78rem;color:var(--g500);">{{ $c->last_service_date ? \Carbon\Carbon::parse($c->last_service_date)->format('d M Y') : 'Never' }}</td>
          <td>
            <div class="tbl-actions">
              <a href="{{ route('customers.show',$c) }}" class="btn btn-ghost btn-sm btn-icon" title="View"><i class="fas fa-eye"></i></a>
              <a href="{{ route('customers.edit',$c) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
              <form action="{{ route('customers.destroy',$c) }}" method="POST" class="inline" onsubmit="return confirm('Delete {{ $c->name }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);" title="Delete"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="empty-state"><div class="empty-icon"><i class="fas fa-users"></i></div><div class="empty-title">No customers yet</div><a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add First Customer</a></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($customers->hasPages())
  <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
    <div style="font-size:.78rem;color:var(--g500);">{{ $customers->firstItem() }}–{{ $customers->lastItem() }} of {{ $customers->total() }}</div>
    <div class="pagination">
      @if(!$customers->onFirstPage())<a href="{{ $customers->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>@endif
      @foreach($customers->getUrlRange(max(1,$customers->currentPage()-2),min($customers->lastPage(),$customers->currentPage()+2)) as $pg=>$url)
        <a href="{{ $url }}" class="page-link {{ $pg==$customers->currentPage()?'active':'' }}">{{ $pg }}</a>
      @endforeach
      @if($customers->hasMorePages())<a href="{{ $customers->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>@endif
    </div>
  </div>
  @endif
</div>
@endsection