<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('plants')
            ->orderBy('name')
            ->get();

        return view('admin.suppliers.index', [
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
        ]);

        Supplier::create($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier added.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
        ]);

        $supplier->update($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            abort(404);
        }

        if ($supplier->plants()->exists()) {
            return redirect()->route('admin.suppliers.index')
                ->with('error', 'Cannot delete a supplier that still has plants.');
        }

        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier removed.');
    }
}