<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\MemorandumSubject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemorandumSubjectController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('open_memo_subjects', true);
        }

        MemorandumSubject::updateOrCreate(
            ['company_id' => $request->user()->company_id, 'name' => $validator->validated()['name']],
            ['name' => $validator->validated()['name']]
        );

        return back()->with('status', 'Asunto guardado.')->with('open_memo_subjects', true);
    }

    public function update(Request $request, MemorandumSubject $subject): RedirectResponse
    {
        abort_unless($subject->company_id === $request->user()->company_id, 403);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('open_memo_subjects', true);
        }

        $subject->update($validator->validated());

        return back()->with('status', 'Asunto actualizado.')->with('open_memo_subjects', true);
    }

    public function destroy(Request $request, MemorandumSubject $subject): RedirectResponse
    {
        abort_unless($subject->company_id === $request->user()->company_id, 403);
        $subject->delete();

        return back()->with('status', 'Asunto eliminado.')->with('open_memo_subjects', true);
    }
}
