@extends('layouts.app')
@section('title','Edit Customer')
@section('page-title','Edit Customer')

@section('content')
<div style="max-width:640px;margin:0 auto;" class="anim-up">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-user-edit" style="color:var(--orange);margin-right:6px;"></i>Edit — {{ $customer->name }}</div>
      <a href="{{ route('customers.show',$customer) }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('customers.update',$customer) }}">
        @csrf @method('PUT')
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group">
            <label class="form-label">Full Name <span class="req">*</span></label>
            <input type="text" name="name" class="form-input" value="{{ old('name',$customer->name) }}" required>
            @error('name')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Phone <span class="req">*</span></label>
            <input type="tel" name="phone" class="form-input" value="{{ old('phone',$customer->phone) }}" required>
            @error('phone')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email',$customer->email) }}">
            @error('email')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="form-group" style="margin-bottom:20px;">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-textarea" style="min-height:80px;">{{ old('address',$customer->address) }}</textarea>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:16px;border-top:1px solid var(--g100);">
          <a href="{{ route('customers.show',$customer) }}" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Customer</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection