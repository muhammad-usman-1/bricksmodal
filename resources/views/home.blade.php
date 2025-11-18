@extends('layouts.admin_design')
@section('content')
<div class='col-span-1 lg:col-span-10 px-4 md:px-5' ref={tableRef}>
    <div class='flex flex-col md:flex-row md:justify-between md:items-center w-full gap-4 mb-10'>
        <div>
            <h1 class='text-2xl md:text-4xl font-bold '>Dashboard</h1>
        </div>
        <div class="flex justify-center md:justify-end items-center w-full md:w-auto">
            <div class="relative w-full max-w-md md:max-w-lg">
                <!-- {/*  Search Icon */} -->
                <span
                    class="absolute left-3 md:left-4 top-1/2 transform -translate-y-1/2 text-[#8F6668] text-lg md:text-xl">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                            stroke="#000000" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                </span>

                <!-- {/*  Input Field */} -->
                <input type="text" placeholder="Search here..."
                    class="w-full border border-[#000000] bg-white rounded-lg pl-10 md:pl-12 pr-4 py-2 shadow-md focus:outline-none focus:ring-1 focus:ring-[#000000] transition duration-200" />

                <!-- {/*  Search Button  */} -->
                <button
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-slate-500 text-white px-3 md:px-4 py-1 rounded-lg hover:bg-slate-300 hover:text-black transition duration-200 text-sm">
                    Search
                </button>
            </div>
        </div>
    </div>
    <!-- {/* Stats Cards - Responsive Grid */} -->
    <div class='grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 md:gap-5 mb-6'>
        <div class='border border-slate-300 shadow-md w-full p-2 sm:p-4 md:p-5 rounded-lg'>
            <p class='pb-2 text-xs sm:text-sm md:text-base'>Total Models</p>
            <p class='text-base sm:text-lg md:text-xl font-semibold'>{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class='border border-slate-300 shadow-md w-full p-2 sm:p-4 md:p-5 rounded-lg'>
            <p class='pb-2 text-xs sm:text-sm md:text-base'>Pending Verification</p>
            <p class='text-base sm:text-lg md:text-xl font-semibold'>{{ $stats['pending_verification'] ?? 0 }}</p>
        </div>
        <div class='border border-slate-300 shadow-md w-full p-2 sm:p-4 md:p-5 rounded-lg'>
            <p class='pb-2 text-xs sm:text-sm md:text-base'>Recent Sign-ups</p>
            <p class='text-base sm:text-lg md:text-xl font-semibold'>{{ $stats['recent_signups'] ?? 0 }}</p>
        </div>
        <div class='border border-slate-300 shadow-md w-full p-2 sm:p-4 md:p-5 rounded-lg'>
            <p class='pb-2 text-xs sm:text-sm md:text-base'>Active Campaigns</p>
            <p class='text-base sm:text-lg md:text-xl font-semibold'>{{ $stats['active_campaigns'] ?? 0 }}</p>
        </div>
        <div class='border border-slate-300 shadow-md w-full p-2 sm:p-4 md:p-5 rounded-lg'>
            <p class='pb-2 text-xs sm:text-sm md:text-base'>Pending Payments</p>
            <p class='text-base sm:text-lg md:text-xl font-semibold'>861 KWD</p>
        </div>
        <div class='border border-slate-300 shadow-md w-full p-2 sm:p-4 md:p-5 rounded-lg'>
            <p class='pb-2 text-xs sm:text-sm md:text-base'>Total </p>
            <p class='text-base sm:text-lg md:text-xl font-semibold'>861 KWD</p>
        </div>
    </div>

    <!-- {/* Responsive Table Wrapper */} -->
    <div class="overflow-x-auto">
        <table class='border border-slate-300 border-separate overflow-hidden rounded-lg w-full min-w-[800px]'>
            <thead class='border-b border-slate-300 text-left'>
                <tr>
                    <th class='p-2 md:p-3 text-sm md:text-base'></th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>Talent</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>Gender</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>Height</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>Weight</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>Status</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>Date Joined</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'></th>
                </tr>
            </thead>
            <tbody class='border-b border-slate-300 text-left'>
                @forelse($talents as $talent)
                <tr>
                    @php
                        $name = optional($talent->user)->name ?? ($talent->display_name ?? $talent->legal_name ?? '—');
                        $avatar = null;

                        if (!empty($talent->headshot_center_path)) {
                            $publicPath = public_path('storage/' . ltrim($talent->headshot_center_path, '/'));
                            if (file_exists($publicPath)) {
                                $avatar = asset('storage/' . ltrim($talent->headshot_center_path, '/'));
                            } else {
                                $avatar = $talent->headshot_center_path;
                            }
                        }

                        if (empty($avatar)) {
                            $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=ffffff&color=5a5a5a&rounded=true&size=64';
                        }
                    @endphp
                    <td class='p-2 md:p-3'><img src="{{ $avatar }}" alt="Model" class='w-10 h-10 md:w-12 md:h-12 object-cover rounded-full' /></td>
                    <td class='p-2 md:p-3 text-sm md:text-base'>
                        <div class="flex items-center gap-2">
                            Jane Doe
                            <svg class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fillRule="evenodd" clipRule="evenodd" d="M15.4179 5.64279C15.1352 5.19159 14.5978 4.96897 14.0788 5.08813L12.28 5.50122C12.0959 5.5435 11.9046 5.5435 11.7205 5.50122L9.92162 5.08813C9.4027 4.96897 8.86524 5.19159 8.58257 5.64279L7.60269 7.20685C7.5024 7.36694 7.36713 7.50221 7.20704 7.60251L5.64297 8.58238C5.19177 8.86506 4.96915 9.40251 5.08832 9.92144L5.5014 11.7203C5.54369 11.9044 5.54369 12.0957 5.50141 12.2798L5.08832 14.0787C4.96915 14.5976 5.19177 15.135 5.64297 15.4177L7.20704 16.3976C7.36713 16.4979 7.5024 16.6331 7.60269 16.7932L8.58257 18.3573C8.86524 18.8085 9.4027 19.0311 9.92162 18.912L11.7205 18.4989C11.9046 18.4566 12.0959 18.4566 12.28 18.4989L14.0788 18.912C14.5978 19.0311 15.1352 18.8085 15.4179 18.3573L16.3978 16.7932C16.4981 16.6332 16.6333 16.4979 16.7934 16.3976L18.3575 15.4177C18.8087 15.135 19.0313 14.5976 18.9121 14.0787L18.4991 12.2798C18.4568 12.0957 18.4568 11.9044 18.4991 11.7203L18.9121 9.92144C19.0313 9.40251 18.8087 8.86506 18.3575 8.58238L16.7934 7.60251C16.6333 7.50221 16.4981 7.36694 16.3978 7.20685L15.4179 5.64279ZM14.9152 9.76963C15.0556 9.53186 14.9767 9.22527 14.7389 9.08485C14.5011 8.94443 14.1945 9.02335 14.0541 9.26113L11.4402 13.6872L9.86121 12.1751C9.66177 11.9841 9.34526 11.9909 9.15426 12.1903C8.96327 12.3898 8.97012 12.7063 9.16956 12.8973L11.2042 14.8457C11.3144 14.9514 11.4669 15.0008 11.6182 14.98C11.7694 14.9591 11.9029 14.8704 11.9805 14.7389L14.9152 9.76963Z" fill="#5178FC" />
                            </svg>
                        </div>
                    </td>
                    <td class='p-2 md:p-3 text-sm md:text-base'>{{ ucfirst($talent->gender ?? '—') }}</td>
                    <td class='p-2 md:p-3 text-sm md:text-base'>{{ $talent->height ? $talent->height . ' cm' : '—' }}</td>
                    <td class='p-2 md:p-3 text-sm md:text-base'>{{ $talent->weight ? $talent->weight . ' kg' : '—' }}</td>
                    <td class='p-2 md:p-3'>
                        <span class='bg-green-100 px-2 py-1 rounded-full text-xs md:text-sm flex justify-center items-center w-16 md:w-20'>{{ $talent->verification_status ?? 'pending' }}</span>
                    </td>
                    <td class='p-2 md:p-3 text-sm md:text-base'>{{ $talent->created_at ? $talent->created_at->format('d/m/Y') : '—' }}</td>
                    <td class="p-2 md:p-3 relative text-center">
                        <details class="inline-block">
                            <summary
                                class="p-2 hover:bg-gray-200 rounded-full transition cursor-pointer flex items-center justify-center">
                                <svg fill="#000000" width="20px" height="20px" viewBox="0 0 32 32">
                                    <path
                                        d="M13,16c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,14.346,13,16z" />
                                    <path
                                        d="M13,26c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,24.346,13,26z" />
                                    <path
                                        d="M13,6c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,4.346,13,6z" />
                                </svg>
                            </summary>
                            <div
                                class="absolute top-8 right-16  w-36 bg-white border rounded shadow z-20 mt-2">
                                <button class="w-full text-left px-3 py-2 hover:bg-gray-100">View</button>
                                <button
                                    class="w-full text-left px-3 py-2 hover:bg-gray-100">Verified</button>
                                <button
                                    class="w-full text-left px-3 py-2 hover:bg-gray-100">Suspend</button>
                                <button
                                    class="w-full text-left px-3 py-2 hover:bg-gray-100">Pending</button>
                                <button
                                    class="w-full text-left px-3 py-2 hover:bg-gray-100 text-red-600">Delete</button>
                            </div>
                        </details>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No recent talents found</td>
                    </tr>
                @endforelse
            </tbody>
            
        </table>
    </div>
</div>
@endsection

@section('scripts')
@parent

@endsection