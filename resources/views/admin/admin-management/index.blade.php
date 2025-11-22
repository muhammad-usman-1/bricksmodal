@extends('layouts.admin_design')
@section('content')

<div class="col-span-1 lg:col-span-10 px-4 md:px-5">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl md:text-4xl font-bold">Admin Management</h1>
        </div>
        <div>
            <a href="{{ route('admin.admin-management.create') }}">
                <button class="bg-[#F1F2F4] hover:bg-slate-400 hover:text-white py-1 px-3 rounded-md border border-[#000000]">+
                    Add New Admin
                </button>
            </a>
        </div>
    </div>

    @if(session('message'))
        <div class="mt-3 mb-3 alert alert-success alert-dismissible fade show">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mt-3 mb-3 alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="flex items-center w-full my-5">
        <div class="relative w-full">
            <span class="absolute left-3 md:left-4 top-1/2 transform -translate-y-1/2 text-[#8F6668] text-lg md:text-xl">
                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search admins..."
                class="w-full bg-[#F1F2F4] border border-[#000000] rounded-lg pl-10 md:pl-12 pr-4 py-2 shadow-md focus:outline-none focus:ring-1 focus:ring-[#000000] transition duration-200" />

            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-slate-500 text-white px-3 md:px-4 py-1 rounded-lg hover:bg-slate-300 hover:text-black transition duration-200 text-sm ">
                Search
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="hidden sm:table border border-slate-300 border-separate overflow-hidden rounded-lg w-full">
            <thead class="border-b border-slate-300 text-left">
                <tr>
                    <th class="p-2 md:p-3 text-sm md:text-base">ID</th>
                    <th class="p-2 md:p-3 text-sm md:text-base">Name</th>
                    <th class="p-2 md:p-3 text-sm md:text-base">Email</th>
                    <th class="p-2 md:p-3 text-sm md:text-base">Role(s)</th>
                    <th class="p-2 md:p-3 text-sm md:text-base">Permissions</th>
                    <th class="p-2 md:p-3 text-sm md:text-base">Can Make Payments</th>
                    <th class="p-2 md:p-3 text-sm md:text-base">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td class="p-2 md:p-3 text-sm md:text-base">{{ $admin->id }}</td>
                        <td class="p-2 md:p-3 text-sm md:text-base">
                            <div class="flex items-center gap-2">
                                <span class="font-medium">{{ $admin->name }}</span>
                                @if($admin->is_super_admin)
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">Super Admin</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-2 md:p-3 text-sm md:text-base">{{ $admin->email }}</td>
                        <td class="p-2 md:p-3 text-sm md:text-base">
                            @foreach($admin->roles as $role)
                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs mr-1">{{ $role->title }}</span>
                            @endforeach
                        </td>
                        <td class="p-2 md:p-3 text-sm md:text-base">
                            @if($admin->is_super_admin)
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs">All Permissions</span>
                            @elseif($admin->adminPermissions)
                                @if($admin->adminPermissions->project_management)
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full text-xs mr-1">Projects</span>
                                @endif
                                @if($admin->adminPermissions->talent_management)
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full text-xs mr-1">Talents</span>
                                @endif
                                @if($admin->adminPermissions->payment_management)
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full text-xs mr-1">Payments</span>
                                @endif
                                @if(!$admin->adminPermissions->project_management && !$admin->adminPermissions->talent_management && !$admin->adminPermissions->payment_management)
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">No Permissions</span>
                                @endif
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">No Permissions</span>
                            @endif
                        </td>
                        <td class="p-2 md:p-3 text-sm md:text-base">
                            @if($admin->is_super_admin)
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs">Yes</span>
                            @elseif($admin->adminPermissions && $admin->adminPermissions->can_make_payments)
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">No</span>
                            @endif
                        </td>
                        <td class="p-2 md:p-3 text-sm md:text-base relative">
                            @if(!$admin->is_super_admin)
                                <details class="inline-block">
                                    <summary class="p-2 hover:bg-gray-200 rounded-full transition cursor-pointer flex items-center justify-center">
                                        <svg fill="#000000" width="20px" height="20px" viewBox="0 0 32 32"><path d="M13,16c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,14.346,13,16z"/><path d="M13,26c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,24.346,13,26z"/><path d="M13,6c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,4.346,13,6z"/></svg>
                                    </summary>
                                    <div class="absolute top-8 right-0 w-40 bg-white border rounded shadow z-20 mt-2">
                                        <a href="{{ route('admin.admin-management.edit', $admin) }}" class="block px-3 py-2 hover:bg-gray-100">Edit</a>
                                        <form action="{{ route('admin.admin-management.destroy', $admin) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this admin?');" class="block w-full text-left px-3 py-2 hover:bg-gray-100 text-[#C10000]">Delete</button>
                                        </form>
                                    </div>
                                </details>
                            @else
                                <span class="text-gray-500">Protected</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-muted">No admins found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Mobile cards -->
        <div class="block sm:hidden">
            @foreach($admins as $admin)
                <div class="bg-[#F1F2F4] rounded-lg shadow p-4 mb-4 flex flex-col gap-2 border border-slate-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="font-semibold text-base">{{ $admin->name }}</h2>
                            <div class="text-xs text-gray-500">{{ $admin->email }}</div>
                        </div>
                        <div class="text-right">
                            @if($admin->is_super_admin)
                                <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">Super</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs mt-2">
                        @foreach($admin->roles as $role)
                            <span class="bg-blue-100 px-2 py-1 rounded-md">{{ $role->title }}</span>
                        @endforeach
                        <div class="ml-auto">
                            @if(!$admin->is_super_admin)
                                <details class="inline-block">
                                    <summary class="p-2 hover:bg-gray-200 rounded-full transition cursor-pointer flex items-center justify-center">•••</summary>
                                    <div class="absolute right-0 mt-2 w-40 bg-white border rounded shadow z-20">
                                        <a href="{{ route('admin.admin-management.edit', $admin) }}" class="block px-3 py-2 hover:bg-gray-100">Edit</a>
                                        <form action="{{ route('admin.admin-management.destroy', $admin) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this admin?');" class="block w-full text-left px-3 py-2 hover:bg-gray-100 text-[#C10000]">Delete</button>
                                        </form>
                                    </div>
                                </details>
                            @else
                                <span class="text-gray-500">Protected</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    @if(method_exists($admins, 'links'))
        <div class="mt-5">
            {{ $admins->links() }}
        </div>
    @endif

</div>

@endsection
