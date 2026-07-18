<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Total Plants', 'value' => '12', 'accent' => 'green'],
                ['label' => 'Total Orders', 'value' => '8', 'accent' => 'mint'],
                ['label' => 'Low Stock Items', 'value' => '3', 'accent' => 'amber'],
                ['label' => 'Total Revenue', 'value' => '৳14,500', 'accent' => 'dark'],
            ],
        ]);
    }

    public function plantsIndex()
    {
        return view('admin.plants.index', [
            'plants' => session('admin_plants', PlantController::plants()),
        ]);
    }

    public function plantsCreate()
    {
        return view('admin.plants.form', ['plant' => null]);
    }

    public function plantsStore(Request $request)
    {
        $plant = $this->validatePlant($request);
        $plants = session('admin_plants', PlantController::plants());
        $plant['id'] = (int) (collect($plants)->max('id') + 1);
        $plants[] = $plant;
        session(['admin_plants' => $plants]);

        return redirect()->route('admin.plants.index')->with('success', 'Plant added.');
    }

    public function plantsEdit(int $id)
    {
        $plants = session('admin_plants', PlantController::plants());
        $plant = collect($plants)->firstWhere('id', $id);

        if (!$plant) {
            abort(404);
        }

        return view('admin.plants.form', ['plant' => $plant]);
    }

    public function plantsUpdate(Request $request, int $id)
    {
        $plants = session('admin_plants', PlantController::plants());
        $index = collect($plants)->search(fn ($item) => $item['id'] === $id);

        if ($index === false) {
            abort(404);
        }

        $plants[$index] = array_merge($plants[$index], $this->validatePlant($request));
        session(['admin_plants' => $plants]);

        return redirect()->route('admin.plants.index')->with('success', 'Plant updated.');
    }

    public function plantsDestroy(int $id)
    {
        $plants = session('admin_plants', PlantController::plants());
        $plants = array_values(array_filter($plants, fn ($plant) => (int) $plant['id'] !== $id));
        session(['admin_plants' => $plants]);

        return redirect()->route('admin.plants.index')->with('success', 'Plant deleted.');
    }

    public function ordersIndex()
    {
        return view('admin.orders.index', [
            'orders' => session('orders', []),
        ]);
    }

    public function ordersUpdateStatus(Request $request, string $orderId)
    {
        $orders = session('orders', []);
        $index = collect($orders)->search(fn ($order) => ($order['order_id'] ?? '') === $orderId);

        if ($index === false) {
            abort(404);
        }

        $status = $request->input('status', 'Pending');
        $orders[$index]['status'] = in_array($status, ['Pending', 'Shipped', 'Delivered'], true) ? $status : 'Pending';
        session(['orders' => $orders]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated.');
    }

    private function validatePlant(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'supplier' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer', 'min:0'],
            'sunlight' => ['required', 'string'],
            'pot_size' => ['required', 'string'],
            'season' => ['required', 'string'],
            'care_instructions' => ['required', 'string'],
        ]);
    }
}
