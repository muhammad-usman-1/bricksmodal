@extends('layouts.admin_design')
@section('content')
<div class="col-span-1 lg:col-span-10 px-4 md:px-5">
        <div>
            <h1 class='text-2xl md:text-4xl font-bold '>Payments</h1>
        </div>

        @if(session('message'))
            <div class="mt-3 mb-3 alert alert-success alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mt-3 mb-3 alert alert-danger alert-dismissible fade show">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class='font-medium text-base sm:text-lg flex flex-wrap gap-3 sm:gap-5 my-5'>
            <p class="cursor-pointer transition-colors {{ !request('status') ? 'text-black border-b-2 border-black pb-1' : 'text-gray-400 hover:text-gray-800' }}">
                <a href="{{ route('admin.payment-requests.index') }}">All</a>
            </p>
            <p class="cursor-pointer transition-colors {{ request('status') === 'requested' ? 'text-black border-b-2 border-black pb-1' : 'text-gray-400 hover:text-gray-800' }}">
                <a href="{{ route('admin.payment-requests.index', ['status' => 'requested']) }}">Pending</a>
            </p>
            <p class="cursor-pointer transition-colors {{ request('status') === 'released' ? 'text-black border-b-2 border-black pb-1' : 'text-gray-400 hover:text-gray-800' }}">
                <a href="{{ route('admin.payment-requests.index', ['status' => 'released']) }}">Released</a>
            </p>
        </div>

        <div class="flex items-center w-full my-5">
            <div class="relative w-full">
                <span class="absolute left-3 md:left-4 top-1/2 transform -translate-y-1/2 text-[#8F6668] text-lg md:text-xl">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search here..."
                    class="w-full bg-[#F1F2F4] border border-[#000000] rounded-lg pl-10 md:pl-12 pr-4 py-2 shadow-md focus:outline-none focus:ring-1 focus:ring-[#000000] transition duration-200" />

                <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-slate-500 text-white px-3 md:px-4 py-1 rounded-lg hover:bg-slate-300 hover:text-black transition duration-200 text-sm ">
                    Search
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class='hidden sm:table border border-slate-300 border-separate overflow-hidden rounded-lg w-full min-w-[800px]'>
                <thead class='border-b border-slate-300 text-left'>
                    <tr>
                        <th class='p-2 md:p-3 text-sm md:text-base'></th>
                        <th class='p-2 md:p-3 text-sm md:text-base'>Talent</th>
                        <th class='p-2 md:p-3 text-sm md:text-base'>Project</th>
                        <th class='p-2 md:p-3 text-sm md:text-base'>Amount</th>
                        <th class='p-2 md:p-3 text-sm md:text-base'>Status</th>
                        <th class='p-2 md:p-3 text-sm md:text-base'>Request to Admin</th>
                        <th class='p-2 md:p-3 text-sm md:text-base'>Actions</th>
                    </tr>
                </thead>
                <tbody class='border-b border-slate-300 text-left'>
                    @forelse($paymentRequests as $application)
                        <tr>
                            <td class='p-2 md:p-3 text-sm md:text-base'>
                                <img src="../../assets/images/model.jpg" alt="model" class='w-10 h-10 md:w-12 md:h-12 object-cover rounded-full' />
                            </td>
                            <td class='p-2 md:p-3 text-sm md:text-base'>
                                <div class="flex items-center gap-2">
                                    {{ optional(optional($application->talent_profile)->user)->name ?? 'N/A' }}
                                    <svg class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.4179 5.64279C15.1352 5.19159 14.5978 4.96897 14.0788 5.08813L12.28 5.50122C12.0959 5.5435 11.9046 5.5435 11.7205 5.50122L9.92162 5.08813C9.4027 4.96897 8.86524 5.19159 8.58257 5.64279L7.60269 7.20685C7.5024 7.36694 7.36713 7.50221 7.20704 7.60251L5.64297 8.58238C5.19177 8.86506 4.96915 9.40251 5.08832 9.92144L5.5014 11.7203C5.54369 11.9044 5.54369 12.0957 5.50141 12.2798L5.08832 14.0787C4.96915 14.5976 5.19177 15.135 5.64297 15.4177L7.20704 16.3976C7.36713 16.4979 7.5024 16.6331 7.60269 16.7932L8.58257 18.3573C8.86524 18.8085 9.4027 19.0311 9.92162 18.912L11.7205 18.4989C11.9046 18.4566 12.0959 18.4566 12.28 18.4989L14.0788 18.912C14.5978 19.0311 15.1352 18.8085 15.4179 18.3573L16.3978 16.7932C16.4981 16.6332 16.6333 16.4979 16.7934 16.3976L18.3575 15.4177C18.8087 15.135 19.0313 14.5976 18.9121 14.0787L18.4991 12.2798C18.4568 12.0957 18.4568 11.9044 18.4991 11.7203L18.9121 9.92144C19.0313 9.40251 18.8087 8.86506 18.3575 8.58238L16.7934 7.60251C16.6333 7.50221 16.4981 7.36694 16.3978 7.20685L15.4179 5.64279ZM14.9152 9.76963C15.0556 9.53186 14.9767 9.22527 14.7389 9.08485C14.5011 8.94443 14.1945 9.02335 14.0541 9.26113L11.4402 13.6872L9.86121 12.1751C9.66177 11.9841 9.34526 11.9909 9.15426 12.1903C8.96327 12.3898 8.97012 12.7063 9.16956 12.8973L11.2042 14.8457C11.3144 14.9514 11.4669 15.0008 11.6182 14.98C11.7694 14.9591 11.9029 14.8704 11.9805 14.7389L14.9152 9.76963Z" fill="#5178FC" />
                                    </svg>
                                </div>
                            </td>
                            <td class='p-2 md:p-3 text-sm md:text-base'>{{ optional($application->casting_requirement)->project_name ?? 'N/A' }}</td>
                            <td class='p-2 md:p-3 text-sm md:text-base'>${{ number_format($application->getPaymentAmount(), 2) }}</td>
                            <td class='p-2 md:p-3'>
                                <span class='{{ $application->payment_status === 'released' ? 'bg-green-100' : 'bg-stone-100' }} px-2 py-1 rounded-full text-xs md:text-sm flex justify-center items-center w-16 md:w-20'>
                                    {{ \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$application->payment_status] ?? ucfirst($application->payment_status) }}
                                </span>
                            </td>
                            <td class='p-2 md:p-3 text-sm md:text-base'>
                                @if($application->requestedByAdmin)
                                    {{ $application->requestedByAdmin->name }}
                                @else
                                    Direct Request
                                @endif
                            </td>
                            <td class='p-2 md:p-3 text-sm md:text-base'>
                                @if($application->payment_status === 'requested')
                                    <form action="{{ route('admin.payment-requests.approve', $application) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class='bg-slate-200 hover:bg-slate-300 py-1 px-3 rounded-md border-2 border-[#029C66]'>Approve</button>
                                    </form>
                                    <button type="button" class='bg-red-600 text-white py-1 px-3 rounded-md ml-2' data-toggle="modal" data-target="#rejectModal{{ $application->id }}">Reject</button>
                                @elseif($application->payment_status === 'approved')
                                    <a href="{{ route('admin.payment-requests.release-form', $application) }}" class='bg-blue-600 text-white py-1 px-3 rounded-md'>Release</a>
                                @elseif($application->payment_status === 'released')
                                    <span class='text-sm text-info'>Released</span>
                                @else
                                    <span class='text-sm text-muted'>-</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.payment-requests.reject', $application) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Payment Request</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="rejection_reason">Rejection Reason</label>
                                                <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Explain why this payment is being rejected..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <p>No payment requests found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Cards for xs screens -->
            <div class="block sm:hidden">
                @foreach($paymentRequests as $application)
                    <div class="bg-[#F1F2F4] rounded-lg shadow p-4 mb-4 flex flex-col gap-2 border border-slate-300">
                        <div class="flex items-center gap-3">
                            <img src="../../assets/images/model.jpg" alt="model" class="w-14 h-14 object-cover rounded-full" />
                            <div>
                                <div class="flex items-center gap-2 font-semibold">
                                    {{ optional(optional($application->talent_profile)->user)->name ?? 'N/A' }}
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.4179 5.64279C15.1352 5.19159 14.5978 4.96897 14.0788 5.08813L12.28 5.50122C12.0959 5.5435 11.9046 5.5435 11.7205 5.50122L9.92162 5.08813C9.4027 4.96897 8.86524 5.19159 8.58257 5.64279L7.60269 7.20685C7.5024 7.36694 7.36713 7.50221 7.20704 7.60251L5.64297 8.58238C5.19177 8.86506 4.96915 9.40251 5.08832 9.92144L5.5014 11.7203C5.54369 11.9044 5.54369 12.0957 5.50141 12.2798L5.08832 14.0787C4.96915 14.5976 5.19177 15.135 5.64297 15.4177L7.20704 16.3976C7.36713 16.4979 7.5024 16.6331 7.60269 16.7932L8.58257 18.3573C8.86524 18.8085 9.4027 19.0311 9.92162 18.912L11.7205 18.4989C11.9046 18.4566 12.0959 18.4566 12.28 18.4989L14.0788 18.912C14.5978 19.0311 15.1352 18.8085 15.4179 18.3573L16.3978 16.7932C16.4981 16.6332 16.6333 16.4979 16.7934 16.3976L18.3575 15.4177C18.8087 15.135 19.0313 14.5976 18.9121 14.0787L18.4991 12.2798C18.4568 12.0957 18.4568 11.9044 18.4991 11.7203L18.9121 9.92144C19.0313 9.40251 18.8087 8.86506 18.3575 8.58238L16.7934 7.60251C16.6333 7.50221 16.4981 7.36694 16.3978 7.20685L15.4179 5.64279ZM14.9152 9.76963C15.0556 9.53186 14.9767 9.22527 14.7389 9.08485C14.5011 8.94443 14.1945 9.02335 14.0541 9.26113L11.4402 13.6872L9.86121 12.1751C9.66177 11.9841 9.34526 11.9909 9.15426 12.1903C8.96327 12.3898 8.97012 12.7063 9.16956 12.8973L11.2042 14.8457C11.3144 14.9514 11.4669 15.0008 11.6182 14.98C11.7694 14.9591 11.9029 14.8704 11.9805 14.7389L14.9152 9.76963Z" fill="#5178FC" />
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-500">{{ optional($application->casting_requirement)->project_name ?? '' }}</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="{{ $application->payment_status === 'released' ? 'bg-green-100' : 'bg-stone-200' }} px-2 py-1 rounded-full text-xs">{{ \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$application->payment_status] ?? ucfirst($application->payment_status) }}</span>
                            <span class="text-xs text-gray-500">Amount: ${{ number_format($application->getPaymentAmount(), 2) }}</span>
                            <span class="text-xs text-gray-500">
                                @if($application->payment_status === 'requested')
                                    <form action="{{ route('admin.payment-requests.approve', $application) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class='bg-slate-200 hover:bg-slate-300 py-1 px-3 rounded-md border-2 border-[#029C66]'>Approve</button>
                                    </form>
                                @elseif($application->payment_status === 'approved')
                                    <a href="{{ route('admin.payment-requests.release-form', $application) }}" class='bg-slate-50 text-slate-300 py-1 px-3 rounded-md'>Release</a>
                                @else
                                    <button class='bg-slate-200 py-1 px-3 rounded-md'>Status</button>
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        @if($paymentRequests->hasPages())
            <div class="mt-4">
                {{ $paymentRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
