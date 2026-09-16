<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminBankController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        return view('manage.admin.banks.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = auth('admin')->user();

        $data = $request->validate([
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'ifsc_code' => ['nullable', 'string', 'max:255'],
            'branch_name' => ['nullable', 'string', 'max:255'],
            'account_type' => ['nullable', 'string', 'max:255'],
            'upi_id' => ['nullable', 'string', 'max:255'],
            'qr_code_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('qr_code_image')) {
            if ($admin->qr_code_image && File::exists(public_path($admin->qr_code_image))) {
                File::delete(public_path($admin->qr_code_image));
            }
            $fileName = time().'_qr_'.Str::random(5).'.'.$request->file('qr_code_image')->extension();
            $request->file('qr_code_image')->move(public_path('uploads/banks'), $fileName);
            $data['qr_code_image'] = 'uploads/banks/'.$fileName;
        }

        $admin->update($data);

        return redirect()->route('admin.account-setting.index')->with('success', 'Bank details updated successfully.');
    }
}
