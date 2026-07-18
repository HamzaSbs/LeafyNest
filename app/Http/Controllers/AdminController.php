<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'admin123';

    public function showLogin()
    {
        if (session('user_role') === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($credentials['username'] !== self::ADMIN_USERNAME || $credentials['password'] !== self::ADMIN_PASSWORD) {
            return back()
                ->withErrors(['username' => 'Invalid admin credentials.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();
        session([
            'user_role' => 'admin',
            'admin_user' => self::ADMIN_USERNAME,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_role', 'admin_user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

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
        $plants[$index]['stock_qty'] = (int) $request->input('stock', $plants[$index]['stock_qty'] ?? 0);
        $plants[$index]['stock'] = $plants[$index]['stock_qty'];
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

    public function plantsUpdateStock(Request $request, int $id)
    {
        $plants = session('admin_plants', PlantController::plants());
        $index = collect($plants)->search(fn ($item) => $item['id'] === $id);

        if ($index === false) {
            abort(404);
        }

        $stock = max(0, (int) $request->input('stock', 0));
        $plants[$index]['stock_qty'] = $stock;
        $plants[$index]['stock'] = $stock;
        session(['admin_plants' => $plants]);

        return redirect()->route('admin.plants.index')->with('success', 'Stock updated.');
    }

    public function ordersIndex()
    {
        $orders = array_values(session('orders', []));
        usort($orders, fn ($a, $b) => strtotime($b['date'] ?? 'now') <=> strtotime($a['date'] ?? 'now'));

        return view('admin.orders.index', [
            'orders' => $orders,
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

    public function ordersDestroy(string $orderId)
    {
        $orders = array_values(array_filter(session('orders', []), fn ($order) => ($order['order_id'] ?? '') !== $orderId));
        session(['orders' => $orders]);

        return redirect()->route('admin.orders.index')->with('success', 'Order removed.');
    }

    public function lowStockIndex(Request $request)
    {
        $plants = session('admin_plants', PlantController::plants());

        $threshold = 5;
        $lowStock = array_values(array_filter($plants, function ($plant) use ($threshold) {
            return (int) ($plant['stock_qty'] ?? 0) <= $threshold;
        }));

        usort($lowStock, fn ($a, $b) => (int)($a['stock_qty'] ?? 0) <=> (int)($b['stock_qty'] ?? 0));

        return view('admin.plants.low-stock', [
            'plants' => $lowStock,
            'threshold' => $threshold,
        ]);
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
