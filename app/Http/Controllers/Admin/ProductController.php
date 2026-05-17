<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\RestockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Konstanta jenis perhiasan & tier
    const JEWELRY_TYPES = [
        'ATG' => 'Anting',
        'BRS' => 'Bros',
        'CCN' => 'Cincin',
        'GLG' => 'Gelang',
        'GWG' => 'Giwang',
        'KLG' => 'Kalung',
    ];

    const PRICE_TIERS = [
        'A' => '100K – 200K',
        'B' => '250K',
        'C' => '300K',
        'D' => '350K',
        'E' => '500K – 999K',
        'F' => '1.000K',
        'G' => '2.000K – 3.000K',
    ];

    public function index(Request $request)
    {
        $query = Product::withTrashed(false)->with('primaryPhoto');

        // Filter kategori
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Filter status
        if ($request->status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($request->status === 'nonaktif') {
            $query->where('is_active', false);
        } elseif ($request->status === 'habis') {
            $query->where('stock', 0);
        } elseif ($request->status === 'menipis') {
            $query->whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        $products = $query->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'jewelryTypes' => self::JEWELRY_TYPES,
            'priceTiers' => self::PRICE_TIERS,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:perhiasan,oleh-oleh',
            'jewelry_type' => 'required_if:category,perhiasan',
            'price_tier' => 'required_if:category,perhiasan',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        // Generate SKU
        $sku = Product::generateSku(
            $request->category,
            $request->jewelry_type,
            $request->price_tier
        );

        // Simpan produk
        $product = Product::create([
            'name' => $request->name,
            'sku' => $sku,
            'category' => $request->category,
            'jewelry_type' => $request->jewelry_type,
            'price_tier' => $request->price_tier,
            'price' => $request->price,
            'stock' => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold ?? 3,
            'description' => $request->description,
            'is_active' => $request->stock > 0,
        ]);

        // Upload foto
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $image = \Intervention\Image\ImageManager::withDriver(\Intervention\Image\Drivers\Gd\Driver::class)->read($photo);

                // Resize kalau lebih dari 1200px
                if ($image->width() > 1200) {
                    $image->scale(width: 1200);
                }

                // Encode ke jpg dengan quality 80 (sekitar 400-500kb)
                $encoded = $image->toJpeg(80);

                $filename = 'products/' . uniqid() . '.jpg';
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $encoded);

                ProductPhoto::create([
                    'product_id' => $product->id,
                    'photo_path' => $filename,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Produk {$product->name} berhasil ditambahkan! SKU: {$sku}");
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product->load('photos'),
            'jewelryTypes' => self::JEWELRY_TYPES,
            'priceTiers' => self::PRICE_TIERS,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold ?? 3,
            'description' => $request->description,
            'is_active' => $request->stock > 0,
        ]);

        // Upload foto baru
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $image = \Intervention\Image\ImageManager::withDriver(\Intervention\Image\Drivers\Gd\Driver::class)->read($photo);

                // Resize kalau lebih dari 1200px
                if ($image->width() > 1200) {
                    $image->scale(width: 1200);
                }

                // Encode ke jpg dengan quality 80 (sekitar 400-500kb)
                $encoded = $image->toJpeg(80);

                $filename = 'products/' . uniqid() . '.jpg';
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $encoded);

                ProductPhoto::create([
                    'product_id' => $product->id,
                    'photo_path' => $filename,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Produk {$product->name} berhasil diupdate!");
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', "Produk {$product->name} berhasil dihapus!");
    }

    // Restock
    public function restock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'supplier' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $stockBefore = $product->stock;
        $stockAfter = $stockBefore + $request->quantity;

        $product->update([
            'stock' => $stockAfter,
            'is_active' => true,
        ]);

        RestockLog::create([
            'product_id' => $product->id,
            'user_id' => auth()->id() ?? 1,
            'quantity_added' => $request->quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'supplier' => $request->supplier,
            'notes' => $request->notes,
        ]);

        return redirect()->back()
            ->with('success', "Stok {$product->name} berhasil ditambah {$request->quantity} unit!");
    }

    // Toggle aktif/nonaktif
    public function toggleStatus(Product $product)
    {
        if ($product->stock === 0 && !$product->is_active) {
            return redirect()->back()
                ->with('error', 'Produk tidak bisa diaktifkan karena stok kosong!');
        }

        $product->update(['is_active' => !$product->is_active]);

        return redirect()->back()
            ->with('success', "Status produk berhasil diubah!");
    }
}
