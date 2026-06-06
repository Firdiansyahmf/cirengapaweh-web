<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::with('products')->orderBy('created_at', 'desc')->paginate(5);

        // Hitung statistik promo
        $totalPromo = Promo::count();
        // Aktif: status aktif AND periode sudah dimulai AND belum berakhir
        $activePromo = Promo::where('is_active', true)
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->count();
        // Expired: masa berlaku sudah berakhir (terlepas dari is_active)
        $expiredPromo = Promo::whereDate('end_date', '<', now()->toDateString())
            ->count();

        return view('admin.promo', compact('promos', 'totalPromo', 'activePromo', 'expiredPromo'));
    }

    public function getProducts()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:150',
                'promo_code' => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'promo_type' => 'required|in:otomatis,kode',
                'discount_percentage' => 'required|integer|min:1|max:100',
                'max_usage' => 'required|integer|min:1',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'nullable|boolean',
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'exists:products,id',
            ]);

            DB::beginTransaction();

            $promo = Promo::create([
                'title' => $validated['title'],
                'promo_code' => $validated['promo_code'],
                'description' => $validated['description'],
                'promo_type' => $validated['promo_type'],
                'discount_percentage' => $validated['discount_percentage'],
                'max_usage' => $validated['max_usage'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            $promo->products()->attach($validated['product_ids']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Promo berhasil ditambahkan'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating promo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Promo $promo)
    {
        $promo->load('products');
        return response()->json([
            'id' => $promo->id,
            'title' => $promo->title,
            'promo_code' => $promo->promo_code,
            'description' => $promo->description,
            'promo_type' => $promo->promo_type,
            'discount_percentage' => $promo->discount_percentage,
            'max_usage' => $promo->max_usage,
            'start_date' => $promo->start_date->format('Y-m-d'),
            'end_date' => $promo->end_date->format('Y-m-d'),
            'is_active' => $promo->is_active,
            'products' => $promo->products,
        ]);
    }

    public function show(Promo $promo)
    {
        $promo->load('products');
        return response()->json($promo);
    }

    public function update(Request $request, Promo $promo)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:150',
                'promo_code' => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'promo_type' => 'required|in:otomatis,kode',
                'discount_percentage' => 'required|integer|min:1|max:100',
                'max_usage' => 'required|integer|min:1',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'nullable|boolean',
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'exists:products,id',
            ]);

            DB::beginTransaction();

            $promo->update([
                'title' => $validated['title'],
                'promo_code' => $validated['promo_code'],
                'description' => $validated['description'],
                'promo_type' => $validated['promo_type'],
                'discount_percentage' => $validated['discount_percentage'],
                'max_usage' => $validated['max_usage'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            $promo->products()->sync($validated['product_ids']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Promo berhasil diperbarui'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating promo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Promo $promo)
    {
        try {
            $promo->delete();
            return response()->json([
                'success' => true,
                'message' => 'Promo berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting promo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Promo $promo)
    {
        try {
            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $promo->update([
                'is_active' => $validated['is_active'],
            ]);

            $statusText = $validated['is_active'] ? 'Aktif' : 'Draft';

            return response()->json([
                'success' => true,
                'message' => "Status promo berhasil diubah menjadi {$statusText}"
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating promo status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
