<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBankDetailRequest;
use App\Http\Requests\StoreBankDetailRequest;
use App\Http\Requests\UpdateBankDetailRequest;
use App\Models\BankDetail;
use App\Models\TalentProfile;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BankDetailController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('bank_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bankDetails = BankDetail::with(['talent_profile'])->get();

        return view('admin.bankDetails.index', compact('bankDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('bank_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talent_profiles = TalentProfile::pluck('legal_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.bankDetails.create', compact('talent_profiles'));
    }

    public function store(StoreBankDetailRequest $request)
    {
        $bankDetail = BankDetail::create($request->all());

        return redirect()->route('admin.bank-details.index');
    }

    public function edit(BankDetail $bankDetail)
    {
        abort_if(Gate::denies('bank_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talent_profiles = TalentProfile::pluck('legal_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bankDetail->load('talent_profile');

        return view('admin.bankDetails.edit', compact('bankDetail', 'talent_profiles'));
    }

    public function update(UpdateBankDetailRequest $request, BankDetail $bankDetail)
    {
        $bankDetail->update($request->all());

        return redirect()->route('admin.bank-details.index');
    }

    public function show(BankDetail $bankDetail)
    {
        abort_if(Gate::denies('bank_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bankDetail->load('talent_profile');

        return view('admin.bankDetails.show', compact('bankDetail'));
    }

    public function destroy(BankDetail $bankDetail)
    {
        abort_if(Gate::denies('bank_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bankDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyBankDetailRequest $request)
    {
        $bankDetails = BankDetail::find(request('ids'));

        foreach ($bankDetails as $bankDetail) {
            $bankDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
