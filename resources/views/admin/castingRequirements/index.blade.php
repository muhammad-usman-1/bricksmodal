@extends('layouts.admin_design')
@section('content')
@section('content')
<div class='col-span-1 lg:col-span-10 px-4 md:px-5' ref={tableRef}>
    <div class='flex justify-between items-center'>
        <div>
            <h1 class='text-2xl md:text-4xl font-bold '>{{ trans('cruds.castingRequirement.title_singular') }} {{ trans('global.list') }}</h1>
        </div>
        <div>
            @can('casting_requirement_create')
                <a href="{{ route('admin.casting-requirements.create') }}">
                    <button class="bg-[#F1F2F4] hover:bg-slate-400 hover:text-white py-1 px-3 rounded-md border border-[#000000]">+
                        {{ trans('global.create_new_project') }}
                    </button>
                </a>
            @endcan
        </div>
    </div>
    <div class='font-medium text-xs sm:text-base flex gap-6 sm:gap-5 my-5'>
        <p class="cursor-pointer transition-colors text-black border-b-2 border-black pb-1">
            All {{ trans('cruds.castingRequirement.title') }}
        </p>
        <p class="cursor-pointer transition-colors text-gray-400 hover:text-gray-800">
            Open {{ trans('cruds.castingRequirement.title') }}
        </p>
        <p class="cursor-pointer transition-colors text-gray-400 hover:text-gray-800">
            Closed {{ trans('cruds.castingRequirement.title') }}
        </p>
    </div>
    <div class="flex items-center w-full my-5 ">
        <div class="relative w-full ">
            <!-- Search Icon -->
            <span class="absolute left-3 md:left-4 top-1/2 transform -translate-y-1/2 text-[#8F6668] text-lg md:text-xl">
                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="#000000" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                </svg>
            </span>
            <!-- Input Field -->
            <input type="text" placeholder="Search here..." class="w-full bg-[#F1F2F4] border border-[#000000] rounded-lg pl-10 md:pl-12 pr-4 py-2 shadow-md focus:outline-none focus:ring-1 focus:ring-[#000000] transition duration-200" />
            <!-- Search Button -->
            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-slate-500 text-white px-3 md:px-4 py-1 rounded-lg hover:bg-slate-300 hover:text-black transition duration-200 text-sm ">
                Search
            </button>
        </div>
    </div>
    <div class='mb-5 flex gap-5'>
        <button class=" bg-[#F1F2F4] hover:bg-slate-400 hover:text-white py-1 px-3 rounded-md border border-[#000000]">
            {{ trans('cruds.castingRequirement.title_singular') }}
        </button>
    </div>

    <!-- Responsive Table Wrapper -->
    <div class="overflow-visible">
        <!-- Table for sm and up -->
        <table class="hidden sm:table border border-slate-300 border-separate rounded-lg w-full">
            <thead class='border-b border-slate-300 text-left'>
                <tr>
                    <th class='p-2 md:p-3 text-sm md:text-base'>{{ trans('cruds.castingRequirement.fields.project_name') }}</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>{{ trans('cruds.castingRequirement.fields.shoot_date_time') }}</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>{{ trans('cruds.castingRequirement.fields.location') }}</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>{{ trans('global.applicants') }}</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>{{ trans('cruds.castingRequirement.fields.status') }}</th>
                    <th class='p-2 md:p-3 text-sm md:text-base'>{{ trans('global.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($castingRequirements as $castingRequirement)
                    <tr>
                        <td class='p-2 md:p-3 text-sm md:text-base'>{{ $castingRequirement->project_name ?? '' }}</td>
                        <td class='p-2 md:p-3 text-sm md:text-base'>{{ $castingRequirement->shoot_date_time ?? '' }}</td>
                        <td class='p-2 md:p-3 text-sm md:text-base'>{{ $castingRequirement->location ?? '' }}</td>
                        <td class='p-2 md:p-3 text-sm md:text-base'>
                            <a href="{{ route('admin.casting-requirements.applicants', $castingRequirement->id) }}">
                                <button class="bg-[#F1F2F4] py-1 px-3 rounded-md border border-[#c1c3c5]">{{ trans('global.view_applicants') }}</button>
                            </a>
                        </td>
                        <td class='p-2 md:p-3'>
                            <span class='bg-green-100 px-2 py-1 rounded-full text-xs md:text-sm flex justify-center items-center w-16 md:w-20'>{{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? '' }}</span>
                        </td>
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
                                    @can('casting_requirement_show')
                                        <a href="{{ route('admin.casting-requirements.show', $castingRequirement->id) }}" class="block px-3 py-2 hover:bg-gray-100">{{ trans('global.view') }}</a>
                                    @endcan
                                    @can('casting_requirement_edit')
                                        <a href="{{ route('admin.casting-requirements.edit', $castingRequirement->id) }}" class="block px-3 py-2 hover:bg-gray-100">{{ trans('global.edit') }}</a>
                                    @endcan
                                    @can('casting_requirement_delete')
                                        <form action="{{ route('admin.casting-requirements.destroy', $castingRequirement->id) }}" method="POST" style="display: inline;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" onclick="return confirm('{{ trans('global.areYouSure') }}');" class="block w-full px-3 py-2 hover:bg-gray-100 text-[#C10000]">{{ trans('global.delete') }}</button>
                                        </form>
                                    @endcan
                                </div>
                            </details>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Cards for xs screens -->
        <div class="block sm:hidden">
            @foreach($castingRequirements as $castingRequirement)
                <div class="bg-[#F1F2F4] rounded-lg shadow p-4 mb-4 flex flex-col gap-2 border border-slate-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="font-semibold text-base">{{ $castingRequirement->project_name ?? '' }}</h2>
                            <div class="text-xs text-gray-500">{{ trans('cruds.castingRequirement.fields.location') }}: {{ $castingRequirement->location ?? '' }}</div>
                        </div>
                        <span class='bg-green-100 px-2 py-1 rounded-full text-xs font-semibold text-green-700'>{{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? '' }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs mt-2">
                        <a href="{{ route('admin.casting-requirements.applicants', $castingRequirement->id) }}">
                            <span class="bg-[#F1F2F4] py-1 px-3 rounded-md border border-[#c1c3c5]">{{ trans('global.view_applicants') }}</span>
                        </a>
                        <span class="text-gray-500">{{ trans('cruds.castingRequirement.fields.shoot_date_time') }}: {{ $castingRequirement->shoot_date_time ?? '' }}</span>
                        <div class="flex gap-2">
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
                                    @can('casting_requirement_show')
                                        <a href="{{ route('admin.casting-requirements.show', $castingRequirement->id) }}" class="block px-3 py-2 hover:bg-gray-100">{{ trans('global.view') }}</a>
                                    @endcan
                                    @can('casting_requirement_edit')
                                        <a href="{{ route('admin.casting-requirements.edit', $castingRequirement->id) }}" class="block px-3 py-2 hover:bg-gray-100">{{ trans('global.edit') }}</a>
                                    @endcan
                                    @can('casting_requirement_delete')
                                        <form action="{{ route('admin.casting-requirements.destroy', $castingRequirement->id) }}" method="POST" style="display: inline;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" onclick="return confirm('{{ trans('global.areYouSure') }}');" class="block w-full text-left px-3 py-2 hover:bg-gray-100 text-[#C10000]">{{ trans('global.delete') }}</button>
                                        </form>
                                    @endcan
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-5">
        @if(method_exists($castingRequirements, 'links'))
            {{ $castingRequirements->links() }}
        @endif
    </div>
</div>
@endsection

