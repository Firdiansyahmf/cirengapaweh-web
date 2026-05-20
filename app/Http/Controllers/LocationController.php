<?php

namespace App\Http\Controllers;

use App\Models\PartnerLocation;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = PartnerLocation::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.lokasi', compact('locations'));
    }

    public function show(PartnerLocation $location)
    {
        return response()->json($location);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:150',
                'address' => 'required|string',
                'open_time' => 'required|date_format:H:i',
                'close_time' => 'required|date_format:H:i',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'nullable|boolean',
            ]);

            // Validate open_time < close_time
            $openTime = \DateTime::createFromFormat('H:i', $validated['open_time']);
            $closeTime = \DateTime::createFromFormat('H:i', $validated['close_time']);

            if ($openTime >= $closeTime) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jam buka harus lebih awal dari jam tutup',
                    'errors' => ['close_time' => ['Jam tutup harus lebih besar dari jam buka']]
                ], 422);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('assets/img/lokasi', 'public');
            }

            $operatingHours = $validated['open_time'] . '-' . $validated['close_time'];

            PartnerLocation::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'operating_hours' => $operatingHours,
                'image' => $imagePath,
                'is_active' => $validated['is_active'] ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil ditambahkan'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, PartnerLocation $location)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:150',
                'address' => 'required|string',
                'open_time' => 'required|date_format:H:i',
                'close_time' => 'required|date_format:H:i',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'nullable|boolean',
            ]);

            // Validate open_time < close_time
            $openTime = \DateTime::createFromFormat('H:i', $validated['open_time']);
            $closeTime = \DateTime::createFromFormat('H:i', $validated['close_time']);

            if ($openTime >= $closeTime) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jam buka harus lebih awal dari jam tutup',
                    'errors' => ['close_time' => ['Jam tutup harus lebih besar dari jam buka']]
                ], 422);
            }

            $imagePath = $location->image;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('assets/img/lokasi', 'public');
            }

            $operatingHours = $validated['open_time'] . '-' . $validated['close_time'];

            $location->update([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'operating_hours' => $operatingHours,
                'image' => $imagePath,
                'is_active' => $validated['is_active'] ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil diperbarui'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(PartnerLocation $location)
    {
        try {
            $location->delete();
            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
