@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Admin Details: {{ $admin->name }}</h4>
                    <div class="card-header-actions">
                        <a class="btn btn-sm btn-secondary" href="{{ route('admin.admins.index') }}">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a class="btn btn-sm btn-info" href="{{ route('admin.admins.edit', $admin->id) }}">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $admin->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $admin->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $admin->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role(s)</th>
                                    <td>
                                        @foreach($admin->roles as $role)
                                            <span class="badge badge-{{ $role->title === 'Super Admin' ? 'danger' : 'primary' }}">
                                                {{ $role->title }}
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>User Type</th>
                                    <td>{{ $admin->type }}</td>
                                </tr>
                                <tr>
                                    <th>Email Verified</th>
                                    <td>
                                        @if($admin->email_verified_at)
                                            <span class="badge badge-success">Yes</span>
                                            <small class="text-muted">({{ $admin->email_verified_at }})</small>
                                        @else
                                            <span class="badge badge-warning">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $admin->created_at ? $admin->created_at->format('M d, Y \a\t g:i A') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $admin->updated_at ? $admin->updated_at->format('M d, Y \a\t g:i A') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
