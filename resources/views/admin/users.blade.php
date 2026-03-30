@extends('layouts.app')
@section('title','Manage Users')
@section('page-title','Admin — Users')

@section('content')
<div class="page-hdr anim-up">
  <div class="page-hdr-left">
    <div class="page-hdr-title">System Users</div>
    <div class="page-hdr-sub">Manage who can access AGMS</div>
  </div>
  <div class="page-hdr-actions">
    <button onclick="document.getElementById('addUserModal').classList.add('show')" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add User</button>
  </div>
</div>

<div class="card anim-up d1">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:34px;height:34px;border-radius:50%;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.82rem;flex-shrink:0;">{{ strtoupper(substr($user->name,0,1)) }}</div>
              <div style="font-weight:600;color:var(--g800);">{{ $user->name }}</div>
            </div>
          </td>
          <td style="color:var(--g600);font-size:.83rem;">{{ $user->email }}</td>
          <td>
            @php $rc=['admin'=>'badge-red','supervisor'=>'badge-purple','receptionist'=>'badge-sky','mechanic'=>'badge-green']; @endphp
            <span class="badge {{ $rc[$user->role??'mechanic']??'badge-gray' }}">{{ ucfirst($user->role??'User') }}</span>
          </td>
          <td><span class="badge {{ $user->is_active?'badge-green':'badge-gray' }}">{{ $user->is_active?'Active':'Inactive' }}</span></td>
          <td style="font-size:.8rem;color:var(--g500);">{{ $user->last_login_at?->diffForHumans()??'Never' }}</td>
          <td>
            <div class="tbl-actions">
              @if($user->id !== auth()->id())
              <form action="{{ route('admin.users.toggle',$user) }}" method="POST" class="inline">@csrf @method('PATCH')
                <button class="btn btn-ghost btn-sm btn-icon" style="color:{{ $user->is_active?'var(--red)':'var(--green)' }};" title="{{ $user->is_active?'Deactivate':'Activate' }}"><i class="fas fa-{{ $user->is_active?'ban':'check' }}"></i></button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6"><div class="empty-state" style="padding:30px;"><div class="empty-icon"><i class="fas fa-users"></i></div><div class="empty-sub">No users found.</div></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="modal-backdrop" id="addUserModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-user-plus" style="color:var(--orange);margin-right:6px;"></i>Add New User</div>
      <button class="modal-close" onclick="document.getElementById('addUserModal').classList.remove('show')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <div class="modal-body">
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group"><label class="form-label">Full Name <span class="req">*</span></label><input type="text" name="name" class="form-input" placeholder="John Kamau" required></div>
          <div class="form-group"><label class="form-label">Email <span class="req">*</span></label><input type="email" name="email" class="form-input" placeholder="john@garage.co.ke" required></div>
          <div class="form-group">
            <label class="form-label">Role <span class="req">*</span></label>
            <select name="role" class="form-select" required>
              <option value="">— Select role —</option>
              <option value="admin">Administrator</option>
              <option value="supervisor">Supervisor</option>
              <option value="receptionist">Receptionist</option>
              <option value="mechanic">Mechanic</option>
            </select>
          </div>
          <div class="form-group"><label class="form-label">Password <span class="req">*</span></label><input type="password" name="password" class="form-input" placeholder="Min 8 characters" required></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addUserModal').classList.remove('show')">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create User</button>
      </div>
    </form>
  </div>
</div>
@endsection',