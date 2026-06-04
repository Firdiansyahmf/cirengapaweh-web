<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show the user management page.
     */
    public function index()
    {
        // Check if user is superadmin
        if (!auth()->user()->isSuperAdmin()) {
            return redirect('/admin/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $users = User::paginate(10);
        return view('admin.pengguna', compact('users'));
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk membuat pengguna.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|in:superadmin,staff',
                'is_active' => 'nullable|boolean',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dibuat',
                'user' => $user
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah pengguna.'
            ], 403);
        }

        try {
            // Check if editing another superadmin
            $isEditingSuperAdmin = $user->isSuperAdmin() && auth()->id() !== $user->id;

            // Validate password if needed
            if ($isEditingSuperAdmin && $request->has('password_verified')) {
                $passwordInput = $request->input('current_password', '');
                if (!Hash::check($passwordInput, auth()->user()->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password verifikasi tidak sesuai',
                        'requires_verification' => true
                    ], 403);
                }
            }

            // Build validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
            ];

            $rules['role'] = 'required|in:superadmin,staff';
            $rules['is_active'] = 'nullable|boolean';

            $validated = $request->validate($rules);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            // Only update password if provided
            if ($validated['password'] ?? null) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Only update role/is_active if not editing self
            $updateData['role'] = $validated['role'];
            $updateData['is_active'] = $validated['is_active'] ?? true;

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diperbarui'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify password for editing another superadmin.
     */
    public function verifyPassword(Request $request, $userId)
    {
        $targetUser = User::findOrFail($userId);

        // Only superadmins can be verified this way
        if ($targetUser->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya superadmin yang memerlukan verifikasi'
            ], 403);
        }

        // Validate password
        if (!Hash::check($request->password, $targetUser->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password tidak cocok'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password terverifikasi'
        ]);
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus pengguna.'
            ], 403);
        }

        try {
            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak bisa menghapus akun Anda sendiri.'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
