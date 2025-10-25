<?php

namespace App\Http\Controllers\UserController;


use id;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\UserModels\User;
use App\Models\ProductModels\Movie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;   
use App\Http\Controllers\UserController\Controller;

class AdminController extends Controller
{
    public function showAdminDashboard(): View
    {
        return view('adminDashboard.index');
    }

    public function showMainManagementUser(Request $request): View
    {
        return view('adminDashboard.userManagement.main');
    }

    public function showUpdateUser(Request $request): View
    {
        $query = User::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($x) use ($q) {
                $x->where('username', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }
        if ($request->filled('role'))   $query->where('role',   $request->role);
        if ($request->filled('status')) $query->where('status', $request->status);

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('adminDashboard.userManagement._updateUser', compact('users'));
    }

    public function showCreateUser(Request $request): View
    {
        return view('adminDashboard.userManagement._createUser');
    }

    public function createUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => 'required|string|max:20|unique:users,username',
            'email'    => 'required|email|max:60|unique:users,email',
            'password' => ['required', Password::min(5)->numbers()],
            'role'     => 'required|in:customers,admin',
        ], [
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.unique'    => 'Email này đã được sử dụng.',
        ]);

        $birthday = null;
        if ($request->filled(['day','month','year'])) {
            $birthday = sprintf('%04d-%02d-%02d', $request->year, $request->month, $request->day);
        }

        User::create([
            'username'  => $data['username'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'birthday'  => $birthday,
        ]);

        return redirect()->route('userManagement_updateUser.form')
            ->with('adminCreateSuccess', 'Tạo tài khoản thành công!');
    }

    protected function removeOldAvatarIfAny(User $user): void
    {
        if ($user->avatar) {
            $path = str_replace('storage/', '', $user->avatar);
            Storage::disk('public')->delete($path);
        }
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,customers',
            'status'   => 'required|in:active,blocked',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_avatar' => 'sometimes|boolean',
        ]);

        if ($request->boolean('remove_avatar')) {
            $this->removeOldAvatarIfAny($user);
            $data['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            $this->removeOldAvatarIfAny($user);
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = 'storage/' . $path;
        }

        $user->update($data);

        return back()->with('UpdateProfileSuccess', 'Cập nhật người dùng thành công!');
    }

    public function delete(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của mình.');
        }

        try {
            $this->removeOldAvatarIfAny($user);
            $user->forceDelete();
            return back()->with('success', 'Đã xóa người dùng.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Không thể xóa người dùng. Kiểm tra ràng buộc dữ liệu.');
        }
    }

    public function showMain(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $movies = Movie::when($q, function ($qr) use ($q) {
                $qr->where('title','like',"%$q%")
                   ->orWhere('genre','like',"%$q%")
                   ->orWhere('rating','like',"%$q%");
            })
            ->latest('movieID')
            ->paginate(12)
            ->withQueryString();

        return view('adminDashboard.moviesManagement.main', compact('movies','q'));
    }

    public function movieStore(Request $req): RedirectResponse
    {
        $data = $req->validate([
            'title'        => 'required|string|max:255',
            'poster'       => 'nullable|string|max:2000',
            'durationMin'  => 'required|integer|min:0|max:65535',
            'genre'        => 'nullable|string|max:255',
            'rating'       => 'nullable|string|max:50',
            'releaseDate'  => 'nullable|date',
            'description'  => 'nullable|string',
            'status'       => 'nullable|in:active,unable',
        ]);

        if (empty($data['status'])) {
            $data['status'] = 'active';
        }

        Movie::create($data);
        return back()->with('status','Đã thêm phim.');
    }

    public function movieUpdate(Request $req, Movie $movie): RedirectResponse
    {
        $data = $req->validate([
            'title'        => 'required|string|max:255',
            'poster'       => 'nullable|string|max:2000',
            'durationMin'  => 'required|integer|min:0|max:65535',
            'genre'        => 'nullable|string|max:255',
            'rating'       => 'nullable|string|max:50',
            'releaseDate'  => 'nullable|date',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,unable',
        ]);

        $movie->update($data);
        return back()->with('status','Đã cập nhật.');
    }

    public function movieDestroy(Movie $movie): RedirectResponse
    {
        $movie->delete();
        return back()->with('status','Đã xoá phim.');
    }
    ////////////////////////// CSV //////////////////////////
    public function movieTemplateCsv()
    {
        $header = ['movieID','title','poster','durationMin','genre','rating','releaseDate','description','status'];
        return response()->streamDownload(function () use ($header) {
            $out = fopen('php://output','w');
            fputcsv($out,$header);
            fputcsv($out, ['', 'MAI', 'https://.../mai.jpg', 120, 'Drama', 'T13', '2024-02-10', 'Mô tả...', 'active']);
            fclose($out);
        }, 'movies_template.csv', ['Content-Type' => 'text/csv']);
    }

    public function movieExportCsv(Request $request)
    {
        $q = (string) $request->query('q','');
        $rows = Movie::when($q, function ($qr) use ($q) {
                $qr->where('title','like',"%$q%")
                ->orWhere('genre','like',"%$q%")
                ->orWhere('rating','like',"%$q%");
            })->orderBy('movieID')->get();

        $header = ['movieID','title','poster','durationMin','genre','rating','releaseDate','description','status'];

        return response()->streamDownload(function () use ($rows,$header) {
            $out = fopen('php://output','w');
            fputcsv($out,$header);
            foreach ($rows as $m) {
                fputcsv($out, [
                    $m->movieID, $m->title, $m->poster, $m->durationMin,
                    $m->genre, $m->rating, $m->releaseDate, $m->description, $m->status,
                ]);
            }
            fclose($out);
        }, 'movies.csv', ['Content-Type' => 'text/csv']);
    }

    const MOVIE_STATUSES = ['active', 'unable'];
    public function movieImportCsv(Request $req): RedirectResponse
    {
        $req->validate([
            'file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $fp = fopen($req->file('file')->getRealPath(), 'r');
        if (!$fp) return back()->with('error','Không mở được file CSV.');

        $header = fgetcsv($fp) ?: [];
        $map = array_change_key_case(array_flip($header), CASE_LOWER);

        foreach (['title','durationmin'] as $need) {
            if (!array_key_exists($need, $map)) {
                fclose($fp);
                return back()->with('error', "Thiếu cột: $need");
            }
        }

        $created=0; $updated=0;
        $get = fn($row,$key,$def=null)=> $row[$map[$key]] ?? $def;

        while (($row = fgetcsv($fp)) !== false) {
            if (count(array_filter($row))===0) continue;

            $status = strtolower((string) $get($row,'status','active'));
            if (!in_array($status, self::MOVIE_STATUSES, true)) {
                $status = 'active';
            }

            $payload = [
                'title'        => $get($row,'title',''),
                'poster'       => $get($row,'poster'),
                'durationMin'  => (int) $get($row,'durationmin',0),
                'genre'        => $get($row,'genre'),
                'rating'       => $get($row,'rating'),
                'releaseDate'  => $get($row,'releasedate'),
                'description'  => $get($row,'description'),
                'status'       => $status,
            ];

            $id = $get($row,'movieid');
            if ($id && ($movie = Movie::find($id))) {
                $movie->update($payload);
                $updated++;
            } else {
                Movie::create($payload);
                $created++;
            }
        }
        fclose($fp);

        return back()->with('status', "Import xong: thêm $created, cập nhật $updated.");
    }


    ////////////////////////// Set Banner //////////////////////////
    public function setBanner(Movie $movie)
    {
        if (!$movie->is_banner) {
            $movie->update(['is_banner' => true]);
        }
        return back()->with('success', "Đã đặt '{$movie->title}' làm banner.");
    }

    public function unsetBanner(Movie $movie)
    {
        if ($movie->is_banner) {
            $movie->update(['is_banner' => false]);
        }
        return back()->with('success', "Đã bỏ banner cho '{$movie->title}'.");
    }

    ////////////////////////// Nut Upload poster //////////////////////////
    public function uploadPoster(Request $request)
    {
        $request->validate([
            'file' => ['required','image','max:2048'],
        ]);
        $path = $request->file('file')->store('pictures', 'public');
        return response()->json(['path' => 'storage/'.$path]);
    }

    ////////////////////////// Movie Theater //////////////////////////
}
