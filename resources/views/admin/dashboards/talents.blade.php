@extends('layouts.admin_design')
@section('content')
<div class='col-span-1 lg:col-span-10 px-2 sm:px-4 md:px-5'>

                <div>
                    <h1 class='text-2xl md:text-4xl font-bold '>{{ trans('cruds.talentProfile.title') }}</h1>
                </div>


                <div class='font-medium text-base sm:text-lg flex flex-wrap gap-3 sm:gap-5 my-5'>
                    <p class="cursor-pointer transition-colors text-black border-b-2 border-black pb-1">
                        {{ trans('global.all') }}
                    </p>
                    <p class="cursor-pointer transition-colors text-gray-400 hover:text-gray-800">
                        {{ trans('global.pending') }}
                    </p>
                    <p class="cursor-pointer transition-colors text-gray-400 hover:text-gray-800">
                        {{ trans('global.approved') }}
                    </p>
                    <p class="cursor-pointer transition-colors text-gray-400 hover:text-gray-800">
                        {{ trans('global.rejected') }}
                    </p>
                    <p class="cursor-pointer transition-colors text-gray-400 hover:text-gray-800">
                        {{ trans('global.available') }}
                    </p>
                </div>
                <div class="flex items-center w-full my-5">
                    <div class="relative w-full">
                        <!-- Search Icon -->
                        <span
                            class="absolute left-3 md:left-4 top-1/2 transform -translate-y-1/2 text-[#8F6668] text-lg md:text-xl">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                                    stroke="#000000" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                            </svg>
                        </span>

                        <!-- Input Field -->
                        <input type="text" placeholder="{{ trans('global.search_here') }}"
                            class="w-full bg-[#F1F2F4] border border-[#000000] rounded-lg pl-10 md:pl-12 pr-4 py-2 shadow-md focus:outline-none focus:ring-1 focus:ring-[#000000] transition duration-200 text-sm sm:text-base" />

                        <!-- Search Button -->
                        <button
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-slate-500 text-white px-3 md:px-4 py-1 rounded-lg hover:bg-slate-300 hover:text-black transition duration-200 text-xs sm:text-sm">
                            {{ trans('global.search') }}
                        </button>
                    </div>
                </div>


                <!-- Responsive Table Wrapper -->
                <div class="overflow-visible">
                    <!-- Table for sm and up -->
                    <table
                        class="hidden sm:table border border-slate-300 border-separate rounded-lg w-full">
                        <thead class="border-b border-slate-300 text-left">
                            <tr>
                                <th class="p-2 sm:p-3 text-xs sm:text-sm md:text-base"></th>
                                <th class="p-2 sm:p-3 text-xs sm:text-sm md:text-base">{{ trans('cruds.talentProfile.fields.display_name') }}</th>
                                <th class="p-2 sm:p-3 text-xs sm:text-sm md:text-base">{{ trans('cruds.talentProfile.fields.gender') }}</th>
                                <th class="p-2 sm:p-3 text-xs sm:text-sm md:text-base">{{ trans('cruds.talentProfile.fields.height') }}</th>
                                <th class="p-2 sm:p-3 text-xs sm:text-sm md:text-base">{{ trans('cruds.talentProfile.fields.weight') }}</th>
                                <th class="p-2 sm:p-3 text-xs sm:text-sm md:text-base">{{ trans('cruds.talentProfile.fields.verification_status') }}</th>
                                <th class="p-2 sm:p-3 text-xs sm:text-sm md:text-base">{{ trans('global.date_joined') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($talents as $talent)
                                <tr>
                                    <td class='p-2 md:p-3 text-sm md:text-base'>
                                        @php
                                            $avatarRaw = $talent->headshot_center_path ?? ($talent->headshot_left_path ?? $talent->headshot_right_path);
                                            if (is_array($avatarRaw)) {
                                                $avatar = $avatarRaw['url'] ?? ($avatarRaw['path'] ?? (isset($avatarRaw[0]) ? $avatarRaw[0] : null));
                                            } else {
                                                $avatar = $avatarRaw;
                                            }
                                            $fallbackSvg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96"><rect width="96" height="96" rx="48" fill="#eddcda"/><circle cx="48" cy="38" r="18" fill="#c8adab"/><rect x="20" y="60" width="56" height="22" rx="11" fill="#d8c1bf"/></svg>');
                                            $avatarSrc = $avatar ?: $fallbackSvg;
                                        @endphp
                                        <img src="{{ $avatarSrc }}" alt="Model" class='w-10 h-10 md:w-12 md:h-12 object-cover rounded-full' />
                                    </td>
                                    <td class='p-2 md:p-3 text-sm md:text-base'>
                                        <div class="flex items-center gap-2">
                                            {{ $talent->display_name ?? $talent->legal_name }}
                                            @if($talent->verification_status == 'verified')
                                                <svg class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24" fill="none">
                                                    <path fillRule="evenodd" clipRule="evenodd"
                                                        d="M15.4179 5.64279C15.1352 5.19159 14.5978 4.96897 14.0788 5.08813L12.28 5.50122C12.0959 5.5435 11.9046 5.5435 11.7205 5.50122L9.92162 5.08813C9.4027 4.96897 8.86524 5.19159 8.58257 5.64279L7.60269 7.20685C7.5024 7.36694 7.36713 7.50221 7.20704 7.60251L5.64297 8.58238C5.19177 8.86506 4.96915 9.40251 5.08832 9.92144L5.5014 11.7203C5.54369 11.9044 5.54369 12.0957 5.50141 12.2798L5.08832 14.0787C4.96915 14.5976 5.19177 15.135 5.64297 15.4177L7.20704 16.3976C7.36713 16.4979 7.5024 16.6331 7.60269 16.7932L8.58257 18.3573C8.86524 18.8085 9.4027 19.0311 9.92162 18.912L11.7205 18.4989C11.9046 18.4566 12.0959 18.4566 12.28 18.4989L14.0788 18.912C14.5978 19.0311 15.1352 18.8085 15.4179 18.3573L16.3978 16.7932C16.4981 16.6332 16.6333 16.4979 16.7934 16.3976L18.3575 15.4177C18.8087 15.135 19.0313 14.5976 18.9121 14.0787L18.4991 12.2798C18.4568 12.0957 18.4568 11.9044 18.4991 11.7203L18.9121 9.92144C19.0313 9.40251 18.8087 8.86506 18.3575 8.58238L16.7934 7.60251C16.6333 7.50221 16.4981 7.36694 16.3978 7.20685L15.4179 5.64279ZM14.9152 9.76963C15.0556 9.53186 14.9767 9.22527 14.7389 9.08485C14.5011 8.94443 14.1945 9.02335 14.0541 9.26113L11.4402 13.6872L9.86121 12.1751C9.66177 11.9841 9.34526 11.9909 9.15426 12.1903C8.96327 12.3898 8.97012 12.7063 9.16956 12.8973L11.2042 14.8457C11.3144 14.9514 11.4669 15.0008 11.6182 14.98C11.7694 14.9591 11.9029 14.8704 11.9805 14.7389L14.9152 9.76963Z"
                                                        fill="#5178FC" />
                                                </svg>
                                            @endif
                                        </div>
                                    </td>
                                    <td class='p-2 md:p-3 text-sm md:text-base'>{{ $talent->gender ?? trans('global.not_set') }}</td>
                                    <td class='p-2 md:p-3 text-sm md:text-base'>{{ $talent->height ? $talent->height . ' cm' : trans('global.not_set') }}</td>
                                    <td class='p-2 md:p-3 text-sm md:text-base'>{{ $talent->weight ? $talent->weight . ' kg' : trans('global.not_set') }}</td>
                                    <td class='p-2 md:p-3 text-sm md:text-base'>
                                        <span class="bg-[#F1F2F4] py-1 px-3 rounded-md border {{ $talent->verification_status == 'rejected' ? 'text-[#C10000]' : '' }}">
                                            {{ App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talent->verification_status] ?? trans('global.not_set') }}
                                        </span>
                                    </td>
                                    <td class='p-2 md:p-3'>{{ $talent->created_at ? $talent->created_at->format('d/m/Y') : trans('global.not_set') }}</td>
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
                                                <a href="{{ route('admin.talent-profiles.show', $talent->id) }}" class="block px-3 py-2 hover:bg-gray-100">{{ trans('global.view') }}</a>
                                                @if($talent->verification_status != 'verified')
                                                    <form action="{{ route('admin.talent-profiles.approve', $talent->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="block w-full text-left px-3 py-2 hover:bg-gray-100">{{ trans('global.approve') }}</button>
                                                    </form>
                                                @endif
                                                @if($talent->verification_status != 'rejected')
                                                    <form action="{{ route('admin.talent-profiles.reject', $talent->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="block w-full text-left px-3 py-2 hover:bg-gray-100 text-[#C10000]">{{ trans('global.reject') }}</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        {{ trans('global.no_talents_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Cards for xs screens -->
                    <div class="block sm:hidden">
                        @forelse($talents as $talent)
                            <div class="bg-[#F1F2F4] rounded-lg shadow p-4 mb-4 flex flex-col gap-2 relative">
                                <div class="flex items-center gap-3">
                                    @php
                                        $avatarRaw = $talent->headshot_center_path ?? ($talent->headshot_left_path ?? $talent->headshot_right_path);
                                        if (is_array($avatarRaw)) {
                                            $avatar = $avatarRaw['url'] ?? ($avatarRaw['path'] ?? (isset($avatarRaw[0]) ? $avatarRaw[0] : null));
                                        } else {
                                            $avatar = $avatarRaw;
                                        }
                                        $fallbackSvg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96"><rect width="96" height="96" rx="48" fill="#eddcda"/><circle cx="48" cy="38" r="18" fill="#c8adab"/><rect x="20" y="60" width="56" height="22" rx="11" fill="#d8c1bf"/></svg>');
                                        $avatarSrc = $avatar ?: $fallbackSvg;
                                    @endphp
                                    <img src="{{ $avatarSrc }}" alt="Model" class="w-14 h-14 object-cover rounded-full" />
                                    <div>
                                        <div class="flex items-center gap-2 font-semibold">
                                            {{ $talent->display_name ?? $talent->legal_name }}
                                            @if($talent->verification_status == 'verified')
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M15.4179 5.64279C15.1352 5.19159 14.5978 4.96897 14.0788 5.08813L12.28 5.50122C12.0959 5.5435 11.9046 5.5435 11.7205 5.50122L9.92162 5.08813C9.4027 4.96897 8.86524 5.19159 8.58257 5.64279L7.60269 7.20685C7.5024 7.36694 7.36713 7.50221 7.20704 7.60251L5.64297 8.58238C5.19177 8.86506 4.96915 9.40251 5.08832 9.92144L5.5014 11.7203C5.54369 11.9044 5.54369 12.0957 5.50141 12.2798L5.08832 14.0787C4.96915 14.5976 5.19177 15.135 5.64297 15.4177L7.20704 16.3976C7.36713 16.4979 7.5024 16.6331 7.60269 16.7932L8.58257 18.3573C8.86524 18.8085 9.4027 19.0311 9.92162 18.912L11.7205 18.4989C11.9046 18.4566 12.0959 18.4566 12.28 18.4989L14.0788 18.912C14.5978 19.0311 15.1352 18.8085 15.4179 18.3573L16.3978 16.7932C16.4981 16.6332 16.6333 16.4979 16.7934 16.3976L18.3575 15.4177C18.8087 15.135 19.0313 14.5976 18.9121 14.0787L18.4991 12.2798C18.4568 12.0957 18.4568 11.9044 18.4991 11.7203L18.9121 9.92144C19.0313 9.40251 18.8087 8.86506 18.3575 8.58238L16.7934 7.60251C16.6333 7.50221 16.4981 7.36694 16.3978 7.20685L15.4179 5.64279ZM14.9152 9.76963C15.0556 9.53186 14.9767 9.22527 14.7389 9.08485C14.5011 8.94443 14.1945 9.02335 14.0541 9.26113L11.4402 13.6872L9.86121 12.1751C9.66177 11.9841 9.34526 11.9909 9.15426 12.1903C8.96327 12.3898 8.97012 12.7063 9.16956 12.8973L11.2042 14.8457C11.3144 14.9514 11.4669 15.0008 11.6182 14.98C11.7694 14.9591 11.9029 14.8704 11.9805 14.7389L14.9152 9.76963Z"
                                                        fill="#5178FC" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $talent->gender ?? trans('global.not_set') }} &bull; {{ $talent->height ? $talent->height . ' cm' : trans('global.not_set') }} &bull; {{ $talent->weight ? $talent->weight . ' kg' : trans('global.not_set') }}</div>
                                    </div>
                                    <!-- Three dot menu at right top -->
                                    <div class="absolute top-4 right-4">
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
                                            <div class="absolute right-0 mt-2 w-36 bg-white border rounded shadow z-20">
                                                <a href="{{ route('admin.talent-profiles.show', $talent->id) }}" class="block px-3 py-2 hover:bg-gray-100">{{ trans('global.view') }}</a>
                                                @if($talent->verification_status != 'verified')
                                                    <form action="{{ route('admin.talent-profiles.approve', $talent->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="block w-full text-left px-3 py-2 hover:bg-gray-100">{{ trans('global.approve') }}</button>
                                                    </form>
                                                @endif
                                                @if($talent->verification_status != 'rejected')
                                                    <form action="{{ route('admin.talent-profiles.reject', $talent->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="block w-full text-left px-3 py-2 hover:bg-gray-100 text-[#C10000]">{{ trans('global.reject') }}</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </details>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="bg-[#F1F2F4] py-1 px-3 rounded-md border {{ $talent->verification_status == 'rejected' ? 'text-[#C10000]' : '' }} text-xs">{{ App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talent->verification_status] ?? trans('global.not_set') }}</span>
                                    <span class="text-xs text-gray-500">{{ trans('global.joined') }}: {{ $talent->created_at ? $talent->created_at->format('d/m/Y') : trans('global.not_set') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                {{ trans('global.no_talents_found') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
@endsection
