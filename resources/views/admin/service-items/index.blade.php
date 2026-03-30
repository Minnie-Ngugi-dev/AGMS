@extends('layouts.app')
@section('title','Service Items')
@section('page-title','Service Items')

@section('content')
<div class="anim-up">

<div class="page-hdr">
  <div>
    <div class="page-hdr-title">Service Items</div>
    <div class="page-hdr-sub">Manage all services offered with prices — data stored in database</div>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.service-items.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add Service Item
    </a>
  </div>
</div>

@if($items->isEmpty())
<div class="card">
  <div class="empty-state">
    <div class="empty-icon"><i class="fas fa-list-check"></i></div>
    <div class="empty-title">No service items yet</div>
    <div class="empty-sub">Add your first service item. These will appear when creating a new service job card.</div>
    <a href="{{ route('admin.service-items.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add First Item
    </a>
  </div>
</div>

@else

{{-- Stats strip --}}
<div class="stat-grid d1" style="margin-bottom:20px;">
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--navy-l);color:var(--navy);"><i class="fas fa-list-check"></i></div>
    <div><div class="stat-val">{{ $items->count() }}</div><div class="stat-lbl">Total Items</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--green-l);color:var(--green);"><i class="fas fa-eye"></i></div>
    <div><div class="stat-val">{{ $items->where('is_active',true)->count() }}</div><div class="stat-lbl">Active</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--g100);color:var(--g500);"><i class="fas fa-eye-slash"></i></div>
    <div><div class="stat-val">{{ $items->where('is_active',false)->count() }}</div><div class="stat-lbl">Inactive</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--orange-l);color:var(--orange);"><i class="fas fa-tags"></i></div>
    <div><div class="stat-val">{{ $categories->count() }}</div><div class="stat-lbl">Categories</div></div>
  </div>
</div>

{{-- Group by category --}}
@foreach($categories as $category)
@php $catItems = $items->where('category', $category); @endphp
<div class="card d2" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">
      <i class="fas fa-tag" style="color:var(--orange);margin-right:8px;"></i>
      {{ $category }}
    </div>
    <span class="badge badge-navy">{{ $catItems->count() }} items</span>
  </div>
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Service Name</th>
          <th>Description</th>
          <th class="text-right">Price (KSh)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($catItems as $item)
        <tr>
          <td>
            <div style="font-weight:600;color:var(--g800);">{{ $item->name }}</div>
          </td>
          <td style="font-size:.78rem;color:var(--g500);max-width:260px;">
            {{ $item->description ?: '—' }}
          </td>
          <td class="text-right">
            <span style="font-family:var(--font-m);font-weight:700;font-size:.9rem;color:var(--green);">
              {{ number_format($item->price, 2) }}
            </span>
          </td>
          <td>
            <span class="badge {{ $item->is_active ? 'badge-green' : 'badge-gray' }}">
              <i class="fas fa-{{ $item->is_active ? 'check' : 'minus' }}"></i>
              {{ $item->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <div class="tbl-actions">
              <a href="{{ route('admin.service-items.edit', $item) }}"
                class="btn btn-ghost btn-sm btn-icon" title="Edit">
                <i class="fas fa-pen"></i>
              </a>
              <form method="POST" action="{{ route('admin.service-items.toggle', $item) }}" class="inline">
                @csrf @method('PATCH')
                <button class="btn btn-ghost btn-sm btn-icon"
                  title="{{ $item->is_active ? 'Deactivate' : 'Activate' }}"
                  style="color:{{ $item->is_active ? 'var(--amber)' : 'var(--green)' }}">
                  <i class="fas fa-{{ $item->is_active ? 'eye-slash' : 'eye' }}"></i>
                </button>
              </form>
              <form method="POST" action="{{ route('admin.service-items.destroy', $item) }}" class="inline"
                onsubmit="return confirm('Delete {{ addslashes($item->name) }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);" title="Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endforeach

@endif
</div>
@endsection