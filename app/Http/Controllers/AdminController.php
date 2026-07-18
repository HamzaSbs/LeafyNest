<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LowStockAlert;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Plant;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, true)) {
            return back()
                ->withErrors(['email' => 'Invalid admin credentials.'])
                ->onlyInput('email');
        }

        $user = Auth::user();
        if (!$user->isAdmin()) {
            Auth::logout();
            return back()
                ->withErrors(['email' => 'This account does not have admin access.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $totalPlants = Plant::count();
        $totalOrders = Order::count();
        $lowStockCount = Plant::where('stock_qty', '<=', 5)->count();
        $totalRevenue = (float) Order::sum('total_amount');

        $formattedRevenue = '৳' . number_format($totalRevenue, 0, '.', ',');

        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Total Plants', 'value' => (string) $totalPlants, 'accent' => 'green'],
                ['label' => 'Total Orders', 'value' => (string) $totalOrders, 'accent' => 'mint'],
                ['label' => 'Low Stock Items', 'value' => (string) $lowStockCount, 'accent' => 'amber'],
                ['label' => 'Total Revenue', 'value' => $formattedRevenue, 'accent' => 'dark'],
            ],
        ]);
    }

    public function plantsIndex()
    {
        $plants = Plant::with(['category', 'supplier'])
            ->orderBy('plant_id')
            ->get()
            ->map(fn (Plant $p) => PlantController::toArray($p))
            ->all();

        return view('admin.plants.index', [
            'plants' => $plants,
        ]);
    }

    public function plantsCreate()
    {
        return view('admin.plants.form', $this->plantFormOptions(null));
    }

    public function plantsStore(Request $request): RedirectResponse
    {
        $data = $this->validatePlant($request);

        Plant::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'supplier_id' => $data['supplier_id'],
            'price' => $data['price'],
            'stock_qty' => (int) $data['stock'],
            'sunlight' => $data['sunlight'] ?? null,
            'pot_size' => $data['pot_size'] ?? null,
            'season' => $data['season'] ?? null,
            'care_instructions' => $data['care_instructions'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
        ]);

        return redirect()->route('admin.plants.index')->with('success', 'Plant added.');
    }

    public function plantsEdit(int $id)
    {
        $plant = Plant::find($id);
        if (!$plant) {
            abort(404);
        }

        return view('admin.plants.form', $this->plantFormOptions(PlantController::toArray($plant)));
    }

    public function plantsUpdate(Request $request, int $id): RedirectResponse
    {
        $plant = Plant::find($id);
        if (!$plant) {
            abort(404);
        }

        $data = $this->validatePlant($request);
        $plant->update([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'supplier_id' => $data['supplier_id'],
            'price' => $data['price'],
            'stock_qty' => (int) $data['stock'],
            'sunlight' => $data['sunlight'] ?? null,
            'pot_size' => $data['pot_size'] ?? null,
            'season' => $data['season'] ?? null,
            'care_instructions' => $data['care_instructions'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
        ]);

        return redirect()->route('admin.plants.index')->with('success', 'Plant updated.');
    }

    public function plantsDestroy(int $id): RedirectResponse
    {
        $plant = Plant::find($id);
        if (!$plant) {
            abort(404);
        }

        $plant->delete();

        return redirect()->route('admin.plants.index')->with('success', 'Plant deleted.');
    }

    public function plantsUpdateStock(Request $request, int $id): RedirectResponse
    {
        $plant = Plant::find($id);
        if (!$plant) {
            abort(404);
        }

        $plant->stock_qty = max(0, (int) $request->input('stock', 0));
        $plant->save();

        return redirect()->route('admin.plants.index')->with('success', 'Stock updated.');
    }

    public function ordersIndex()
    {
        $orders = Order::with('items.plant', 'user')
            ->orderByDesc('order_date')
            ->get()
            ->map(fn (Order $o) => $this->orderToArray($o))
            ->all();

        return view('admin.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function ordersUpdateStatus(Request $request, string $orderId): RedirectResponse
    {
        $order = Order::find($orderId);
        if (!$order) {
            abort(404);
        }

        $status = $request->input('status', 'Pending');
        $allowed = ['Pending', 'Shipped', 'Delivered', 'Cancelled'];

        $order->status = in_array($status, $allowed, true) ? $status : 'Pending';
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated.');
    }

    public function ordersDestroy(string $orderId): RedirectResponse
    {
        $order = Order::find($orderId);
        if (!$order) {
            abort(404);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order removed.');
    }

    public function lowStockIndex()
    {
        $threshold = 5;

        $lowStock = Plant::with(['category', 'supplier'])
            ->where('stock_qty', '<=', $threshold)
            ->orderBy('stock_qty')
            ->get()
            ->map(fn (Plant $p) => PlantController::toArray($p))
            ->all();

        return view('admin.plants.low-stock', [
            'plants' => $lowStock,
            'threshold' => $threshold,
        ]);
    }

    private function plantFormOptions(?array $plant): array
    {
        $filters = PlantController::filterOptions();

        return [
            'plant' => $plant,
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
            'sunlightOptions' => $filters['sunlights'],
            'potSizeOptions' => $filters['potSizes'],
            'seasonOptions' => $filters['seasons'],
        ];
    }

    private function validatePlant(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,category_id'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,supplier_id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sunlight' => ['nullable', 'string', 'max:255'],
            'pot_size' => ['nullable', 'string', 'max:255'],
            'season' => ['nullable', 'string', 'max:255'],
            'care_instructions' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
        ]);
    }

    private function orderToArray(Order $o): array
    {
        return [
            'order_id' => (string) $o->order_id,
            'date' => $o->order_date?->format('Y-m-d H:i'),
            'status' => $o->status,
            'total' => (float) $o->total_amount,
            'customer_name' => $o->user?->name,
            'customer_email' => $o->user?->email,
            'items' => $o->items->map(fn (OrderItem $it) => [
                'id' => (int) $it->plant_id,
                'name' => $it->plant?->name,
                'image' => $it->plant?->image,
                'price' => (float) $it->unit_price,
                'quantity' => (int) $it->quantity,
                'row_total' => (float) $it->unit_price * (int) $it->quantity,
            ])->all(),
        ];
    }
}
