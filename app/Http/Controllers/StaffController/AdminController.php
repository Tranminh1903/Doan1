<?php

namespace App\Http\Controllers\StaffController;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserModels\User;
use Illuminate\Validation\Rule;
use App\Models\UserModels\Admin;
use App\Models\UserModels\Order;
use App\Models\ProductModels\Seat;
use Illuminate\Support\Facades\DB;
use App\Models\ProductModels\Movie;
use App\Models\UserModels\Customer;
use App\Models\ProductModels\Ticket;
use App\Models\UserModels\Promotion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\ProductModels\Showtime;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\ProductModels\MovieTheater;
use App\Http\Controllers\UserController\Controller;

class AdminController extends Controller
{
    // =============== Main AdminDashboard ============= //
    public function showAdminDashboard(): View
    {
        $tz    = 'Asia/Ho_Chi_Minh';
        $today = Carbon::today($tz);
        $now   = Carbon::now($tz);


        $paidStatuses = ['paid', 'success', 'completed', 1, '1'];
        $paidDateColumn = Schema::hasColumn('orders', 'paid_at')
            ? 'orders.paid_at'
            : 'orders.created_at';

        $kpi = [];
        $kpi['revenue_today'] = Order::query()
            ->whereIn('orders.status', $paidStatuses)
            ->whereDate($paidDateColumn, $today)
            ->sum('orders.amount');

        $kpi['tickets_today'] = Ticket::query()
            ->whereDate('created_at', $today)
            ->count();

        $kpi['users_total']   = User::query()->count();

        $kpi['movies_active'] = Movie::query()
            ->where('status', 'active')
            ->count();

        $recentTickets = Ticket::query()
            ->with(['showtime.movie', 'seat'])
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($t) use ($tz) {
                return (object)[
                    'code'  => $t->ticketCode ?? '',
                    'movie' => optional(optional($t->showtime)->movie)->title ?? '',
                    'seat'  => optional($t->seat)->seatName ?? '',
                    'price' => (int)($t->price ?? 0),
                    'time'  => optional($t->created_at)->setTimezone($tz)->format('H:i'),
                ];
            });

        $topMovies = Order::query()
            ->whereIn('orders.status', $paidStatuses)
            ->join('showtime', 'showtime.showtimeID', '=', 'orders.showtimeID')
            ->join('movies',  'movies.movieID',      '=', 'showtime.movieID')
            ->groupBy('movies.movieID', 'movies.title')
            ->selectRaw('movies.title as title, SUM(orders.amount) as revenue')
            ->orderByRaw('SUM(orders.amount) DESC')
            ->limit(5)
            ->get();

        if ($topMovies->isEmpty()) {
            $topMovies = Ticket::query()
                ->join('showtime', 'showtime.showtimeID', '=', 'ticket.showtimeID')
                ->join('movies',  'movies.movieID',       '=', 'showtime.movieID')
                ->groupBy('movies.movieID', 'movies.title')
                ->selectRaw('movies.title as title, SUM(ticket.price) as revenue')
                ->orderByRaw('SUM(ticket.price) DESC')
                ->limit(5)
                ->get();
        }

        $upcomingShowtimes = Showtime::query()
            ->with(['movie', 'theater'])
            ->whereBetween('startTime', [$now, $now->copy()->addDays(7)])
            ->orderBy('startTime')
            ->limit(10)
            ->get();

        if ($upcomingShowtimes->isEmpty()) {
            $upcomingShowtimes = Showtime::query()
                ->with(['movie', 'theater'])
                ->orderByDesc('startTime')
                ->limit(10)
                ->get();
        }

        $upcomingShowtimes = $upcomingShowtimes->map(function ($s) use ($tz, $paidStatuses) {
            $sold  = $s->orders()->whereIn('status', $paidStatuses)->count();
            $total = (int) (optional($s->theater)->capacity ?? 0);
            return (object)[
                'time'    => optional($s->startTime)->setTimezone($tz)->format('H:i'),
                'movie'   => optional($s->movie)->title ?? '',
                'theater' => optional($s->theater)->name ?? (optional($s->theater)->roomName ?? ''),
                'seats'   => $sold . '/' . $total,
            ];
        });

        $theaterMini = DB::table('movie_theaters')
            ->orderBy('roomName')
            ->limit(5)
            ->get(['roomName', 'capacity']);

        return view('adminDashboard.index', compact(
            'kpi',
            'recentTickets',
            'topMovies',
            'upcomingShowtimes',
            'theaterMini'
        ));
    }

    // =============== USER MANAGEMENT ============= //
    public function showMainManagementUser(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $users = User::query()
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($x) use ($q) {
                    $x->where('name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('role', 'like', "%$q%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $kpi = [
            'users_total'  => User::count(),
            'users_active' => User::where('status', 'active')->count(),
        ];

        return view('adminDashboard.userManagement.main', compact('users', 'kpi', 'q'));
    }


    protected function removeOldAvatarIfAny(User $user): void
    {
        if ($user->avatar) {
            $path = str_replace('storage/', '', $user->avatar);
            Storage::disk('public')->delete($path);
        }
    }
    public function store(Request $request)
    {
        // Cho phép form cũ dùng "name" => map sang "username" nếu thiếu
        if ($request->filled('name') && !$request->filled('username')) {
            $request->merge(['username' => $request->input('name')]);
        }

        // Chuẩn hoá role (admin/customers)
        if ($request->filled('role')) {
            $request->merge(['role' => Str::of($request->input('role'))->lower()->value()]);
        }

        // Validate
        $data = $request->validate([
            'username' => ['required', 'string', 'max:20', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:60', 'unique:users,email'],
            'password' => ['required', Password::min(5)->letters()->numbers()],
            'role'     => ['nullable', 'in:admin,customers'],
            'status'   => ['nullable', 'in:active,locked'],
            'avatar'   => ['nullable', 'string', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:30'],
            // nếu bạn gửi trực tiếp birthday dạng Y-m-d
            'birthday' => ['nullable', 'date'],
            // hoặc gửi theo day/month/year (không bắt buộc)
            'day'      => ['nullable', 'integer', 'between:1,31'],
            'month'    => ['nullable', 'integer', 'between:1,12'],
            'year'     => ['nullable', 'integer', 'between:1900,2100'],
        ], [
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.unique'    => 'Email này đã được sử dụng.',
        ]);

        $birthday = $data['birthday'] ?? null;
        if (empty($birthday) && $request->filled(['day', 'month', 'year'])) {
            $day   = (int) $request->input('day');
            $month = (int) $request->input('month');
            $year  = (int) $request->input('year');
            $birthday = sprintf('%04d-%02d-%02d', $year, $month, $day);
        }

        $role   = $data['role']   ?? 'customers';
        $status = $data['status'] ?? 'active';

        // Tạo user
        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $role,                 
            'status'   => $status,              
            'birthday' => $birthday,             
            'avatar'   => $request->input('avatar'), 
            'phone'    => $request->input('phone'),
        ]);

        if ($user->role === 'customers') {
            Customer::create([
                'user_id'        => $user->id,
                'customer_name'  => $user->username,
                'customer_point' => 0,
            ]);
        } elseif ($user->role === 'admin') {
            Admin::create([
                'user_id' => $user->id,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Tạo người dùng thành công!');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'password' => ['nullable', Password::min(6)],
            'role'     => ['nullable', 'in:admin,customers'],
            'status'   => ['nullable', 'in:active,locked'],
            'avatar'   => ['nullable', 'string', 'max:1024'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'note'     => ['nullable', 'string', 'max:2000'],
        ]);

        $user->fill([
            'name'   => $data['name'],
            'email'  => $data['email'],
            'role'   => $data['role']   ?? $user->role,
            'status' => $data['status'] ?? $user->status,
            'avatar' => $data['avatar'] ?? $user->avatar,
            'phone'  => $data['phone']  ?? $user->phone,
            'note'   => $data['note']   ?? $user->note,
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'Đã cập nhật người dùng.');
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
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'max:2048'] // 2MB
        ]);

        $file = $request->file('file');

        // Lưu vào storage/app/public/avatars
        $path = $file->store('public/avatars');
        // Trả đường dẫn public
        $publicPath = Str::replaceFirst('public/', 'storage/', $path);

        return response()->json(['path' => $publicPath]);
    }

    public function toggleStatus(User $user)
    {
        $user->status = ($user->status === 'locked') ? 'active' : 'locked';
        $user->save();

        return back()->with('ok', $user->status === 'locked' ? 'Đã khoá tài khoản.' : 'Đã mở khoá tài khoản.');
    }

    // =============== MOVIES MANAGEMENT ============= //
    public function showMain(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $type = (string) $request->query('type', 'all'); 
        $today = Carbon::today()->toDateString();

        // Query danh sách phim (có tìm kiếm)
        $moviesQuery = Movie::query();
        if ($q !== '') {
            $moviesQuery->where(function ($qr) use ($q) {
                $qr->where('title', 'like', "%{$q}%")
                    ->orWhere('genre', 'like', "%{$q}%")
                    ->orWhere('rating', 'like', "%{$q}%");
            });
        }
        switch ($type) {
            case 'coming_soon': // phim sắp chiếu
                $moviesQuery
                    ->where('status', 'active')
                    ->whereDate('releaseDate', '>', $today);
                break;

            case 'now_showing': // phim đang chiếu
                $moviesQuery
                    ->where('status', 'active')
                    ->whereDate('releaseDate', '<=', $today);
                break;

            case 'hidden': // phim đã ẩn
                $moviesQuery
                    ->where('status', 'unable');
                break;

            case 'all':
            default:
                break;
        }
        
        $movies = $moviesQuery
            ->orderBy('releaseDate', 'desc')
            ->orderBy('movieID', 'desc')
            ->paginate(10)
            ->withQueryString();

        // KPI
        $kpi = [
            'movies_active' => Movie::where('status', 'active')->count(),
            'movies_total'  => Movie::count(),
            'movies_coming_soon' => Movie::where('status', 'active')
                                     ->whereDate('releaseDate', '>', $today)
                                     ->count(),
        ];

        return view('adminDashboard.moviesManagement.main', compact('movies', 'q', 'kpi','type'));
    }

    public function movieStore(Request $req): RedirectResponse
    {
        $data = $req->validate([
            'title'        => 'required|string|max:255',
            'poster'       => 'nullable|string|max:2000',
            'background'   => 'nullable|string|max:2000',            
            'durationMin'  => 'required|integer|min:0|max:65535',
            'genre'        => 'nullable|string|max:255',
            'rating'       => 'nullable|string|in:P,K,T13,T16,T18',
            'releaseDate'  => 'nullable|date',
            'description'  => 'nullable|string',
            'status'       => 'nullable|in:active,unable',
        ]);

        if (empty($data['status'])) {
            $data['status'] = 'active';
        }

        Movie::create($data);
        return back()->with('status', 'Đã thêm phim.');
    }

    public function movieUpdate(Request $req, Movie $movie): RedirectResponse
    {
        $data = $req->validate([
            'title'        => 'required|string|max:255',
            'poster'       => 'nullable|string|max:2000',
            'background'   => 'nullable|string|max:2000',
            'durationMin'  => 'required|integer|min:0|max:65535',
            'genre'        => 'nullable|string|max:255',
            'rating'       => 'nullable|string|in:P,K,T13,T16,T18',
            'releaseDate'  => 'nullable|date',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,unable',
        ]);

        $movie->update($data);
        return back()->with('status', 'Đã cập nhật.');
    }

    public function movieDestroy(Movie $movie): RedirectResponse
    {
        $movie->delete();
        return back()->with('status', 'Đã xoá phim.');
    }
    // =============== UPLOAD POSTER ============= //
    public function uploadPoster(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'max:2048'],
        ]);
        $path = $request->file('file')->store('pictures', 'public');
        return response()->json(['path' => 'storage/' . $path]);
    }
    // =============== CSV ============= //
    public function movieTemplateCsv()
    {
        $header = [
            'movieID',
            'title',
            'poster',
            'background',
            'durationMin',
            'genre',
            'rating',
            'releaseDate',
            'description',
            'status'
        ];

        return response()->streamDownload(function () use ($header) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $header);
            fputcsv($out, ['', 'MAI', 'https://.../mai.jpg', 120, 'Drama', 'T13', '2024-02-10', 'Mô tả...', 'active']);
            fclose($out);
        }, 'movies_template.csv', ['Content-Type' => 'text/csv']);
    }

    public function movieExportCsv(Request $request)
    {
        $q = (string) $request->query('q', '');
        $rows = Movie::when($q, function ($qr) use ($q) {
            $qr->where('title', 'like', "%$q%")
                ->orWhere('genre', 'like', "%$q%")
                ->orWhere('rating', 'like', "%$q%");
        })->orderBy('movieID')->get();

        $header = [
            'movieID', 
            'title',
            'poster',
            'background',
            'durationMin',
            'genre',
            'rating',
            'releaseDate',
            'description',
            'status'
        ];

        return response()->streamDownload(function () use ($rows, $header) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $header);
            foreach ($rows as $m) {
                fputcsv($out, [
                    $m->movieID,
                    $m->title,
                    $m->poster,
                    $m->background,
                    $m->durationMin,
                    $m->genre,
                    $m->rating,
                    $m->releaseDate,
                    $m->description,
                    $m->status,
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
        if (!$fp) return back()->with('error', 'Không mở được file CSV.');

        $header = fgetcsv($fp) ?: [];
        $map = array_change_key_case(array_flip($header), CASE_LOWER);

        foreach (['title', 'durationmin'] as $need) {
            if (!array_key_exists($need, $map)) {
                fclose($fp);
                return back()->with('error', "Thiếu cột: $need");
            }
        }

        $created = 0;
        $updated = 0;
        $get = fn($row, $key, $def = null) => $row[$map[$key]] ?? $def;

        while (($row = fgetcsv($fp)) !== false) {
            if (count(array_filter($row)) === 0) continue;

            $status = strtolower((string) $get($row, 'status', 'active'));
            if (!in_array($status, self::MOVIE_STATUSES, true)) {
                $status = 'active';
            }

            $payload = [
                'title'        => $get($row, 'title', ''),
                'poster'       => $get($row, 'poster'),
                'background'   => $get($row, 'background'),
                'durationMin'  => (int) $get($row, 'durationmin', 0),
                'genre'        => $get($row, 'genre'),
                'rating'       => $get($row, 'rating'),
                'releaseDate'  => $get($row, 'releasedate'),
                'description'  => $get($row, 'description'),
                'status'       => $status,
            ];

            $id = $get($row, 'movieid');
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


    // =============== BANNER ============= //
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

    // =============== PROMOTION ============= //
    public function showPromotion(Request $request): View
    {
        // Lấy tất cả khuyến mãi
        $promotions = promotion::orderBy('created_at', 'desc')->get();

        $q = $request->q ?? '';
        $linkPage = promotion::when($q, function ($query) use ($q) {
            $query->where('code', 'LIKE', "%$q%")
                  ->orWhere('description', 'LIKE', "%$q%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->appends(['q' => $q]);
        $user = Auth::user();

        $kpi = [
            'promotion_total'  => promotion::count(),
            'promotion_active' => promotion::where('status', 'active')->count(),
        ];
        // Trả về view với dữ liệu
        return view('adminDashboard.promotionManagement.main', compact('promotions', 'user','kpi','linkPage'));
    }

    // =============== Xử lý lưu khuyến mãi mớ ============= //
    public function PromotionStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promotion,code',
            'type' => 'required|string|max:50',
            'value' => 'required|numeric|min:1',
            'limit_count' => 'required|integer|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'min_ticket_quantity' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:255',
        ]);

        promotion::create([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'limit_count' => $request->limit_count,
            'min_order_value' => $request->min_order_value,
            'min_ticket_quantity' => $request->min_ticket_quantity,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->route('admin.promotionManagement.form')->with('success', 'Thêm khuyến mãi thành công!');
    }
    // =============== Cập nhật khuyến mãi ============= //
    
    public function PromotionUpdate(Request $request, $id)
    {
    $promotion = promotion::findOrFail($id);

    $request->validate([
        'code' => 'required|string|max:50|unique:promotion,code,' . $promotion->id,
        'type' => 'required|string|max:50',
        'value' => 'required|numeric|min:1',
        'limit_count' => 'required|integer|min:0',
        'min_order_value' => 'nullable|numeric|min:0',
        'min_ticket_quantity' => 'nullable|integer|min:1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'description' => 'nullable|string|max:255',
    ]);

    $promotion->update([
        'code' => $request->code,
        'type' => $request->type,
        'value' => $request->value,
        'limit_count' => $request->limit_count,
        'min_order_value' => $request->min_order_value,
        'min_ticket_quantity' => $request->min_ticket_quantity,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'description' => $request->description,
    ]);

    return redirect()->route('admin.promotionManagement.form')->with('success', 'Cập nhật khuyến mãi thành công!');
    }
    // =============== Xóa khuyến mãi ============= // 

    public function PromotionDestroy($id)
    {
        $promotion = promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('admin.promotionManagement.form')->with('success', 'Xóa khuyến mãi thành công!');
    }

    // =============== SHOWTIME ============= //
    public function showShowtime(Request $request): View
    {
        $q = trim($request->q ?? '');

        $showtimes = Showtime::with(['movie', 'theater'])
            ->when($q, function($qr) use ($q) {
                $qr->whereHas('movie', function($m) use ($q){
                    $m->where('title', 'like', "%$q%");
                })->orWhereHas('theater', function($t) use ($q){
                    $t->where('roomName', 'like', "%$q%");
                });
            })
            ->orderBy('startTime', 'desc')
            ->paginate(12)
            ->withQueryString();

        $kpi = [
            'showtime_total' => Showtime::count(),
            'today' => Showtime::whereDate('startTime', today())->count(),
        ];
        $movies = Movie::where('status','active')->orderBy('title')->get();
        $theaters = MovieTheater::where('status','active')->orderBy('roomName')->get();
        return view('adminDashboard.showtimeManagement.main', compact('showtimes', 'movies', 'theaters', 'kpi', 'q'));
    }

    public function showtimeStore(Request $request)
    {
        $data = $request->validate([
            'movieID'   => 'required|exists:movies,movieID',
            'theaterID' => 'required|exists:movie_theaters,theaterID',
            'startTime' => 'required|date',
            'endTime'   => 'required|date|after:startTime',
        ]);

        $exists = Showtime::where('theaterID', $data['theaterID'])
            ->where(function($q) use ($data){
                $q->whereBetween('startTime', [$data['startTime'], $data['endTime']])
                ->orWhereBetween('endTime', [$data['startTime'], $data['endTime']]);
            })
            ->exists();

        if ($exists) {
            return back()->with('error', 'Phòng chiếu đang có suất chiếu trùng thời gian.');
        }

        Showtime::create($data);

        return redirect()->route('admin.showtimeManagement.form')
            ->with('success','Đã thêm suất chiếu.');
    }
    public function showtimeUpdate(Request $request, Showtime $showtime)
    {
        $data = $request->validate([
            'movieID'   => 'required|exists:movies,movieID',
            'theaterID' => 'required|exists:movie_theaters,theaterID',
            'startTime' => 'required|date',
            'endTime'   => 'required|date|after:startTime',
        ]);

        $start = Carbon::parse($request->startTime);
        $end   = Carbon::parse($request->endTime);

        $exists = Showtime::where('theaterID', $request->theaterID)
            ->where('showtimeID', '!=', $id)  // 🚀 
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('startTime', [$start, $end])
                ->orWhereBetween('endTime', [$start, $end])
                ->orWhere(function($q2) use ($start, $end) {
                    $q2->where('startTime', '<=', $start)
                        ->where('endTime', '>=', $end);
                });
            })
            ->exists();

        if ($exists) {
            return back()->with('error', 'Phòng chiếu đang có suất chiếu trùng thời gian.');
        }

        $showtime->update($data);

        return back()->with('success', 'Cập nhật thành công.');
    }
    public function showtimeDestroy(Showtime $showtime)
    {
        $showtime->delete();

        return back()->with('success', 'Đã xoá suất chiếu.');
    }
    // =============== MOVIE THEATER ============= //
    public function showMovieTheater(Request $request): View
    {        
        $q = trim((string) $request->get('q', ''));
        $theaterMini = MovieTheater::select('theaterID','roomName','capacity','status')
            ->orderBy('roomName')
            ->limit(10)
            ->get();
        $kpi = [
            'movieTheaters_total' => MovieTheater::count(),
        ]; 

        $theaters = MovieTheater::with('seats')
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($sub) use ($q) {
                    $sub->where('roomName', 'like', "%{$q}%")
                        ->orWhere('note', 'like', "%{$q}%")
                        ->orWhere('status', 'like', "%{$q}%");
                });
            })
            ->orderBy('roomName')
            ->paginate(12)
            ->withQueryString();
        return view('adminDashboard.movietheaterManagement.main',compact('theaterMini','kpi','q','theaters'));
    }
    public function theaterStore(Request $request)
    {
    $validated = $request->validate([
        'roomName' => ['required','string','max:255',Rule::unique('movie_theaters', 'roomName'),],
        'rows'     => ['required','integer','min:1','max:26'],
        'cols'     => ['required','integer','min:1','max:50'],
        'status'   => ['required', Rule::in(['active','inactive'])],
        'normal_price' => ['required','integer','min:0'],
        'vip_price'    => ['required','integer','min:0'],
    ]);

    $rows = (int) $validated['rows'];
    $cols = (int) $validated['cols'];
    $capacity = $rows * $cols;
    $letters = range('A','Z');

    return DB::transaction(function () use ($validated, $rows, $cols, $capacity, $letters) {
        $theater = MovieTheater::create([
            'roomName' => $validated['roomName'],
            'capacity' => $capacity,
            'status'   => $validated['status'],
        ]);

        $now = now();
        $payload = [];
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 1; $c <= $cols; $c++) {
                $seatType = $r === 0 ? 'vip' : 'normal';
                $price    = $seatType === 'vip'
                            ? $validated['vip_price']
                            : $validated['normal_price'];
                $payload[] = [
                    'theaterID'     => $theater->theaterID,
                    'verticalRow'   => $letters[$r],
                    'horizontalRow' => $c,
                    'seatType'      => $r === 0 ? 'vip' : 'normal', // hàng A là VIP
                    'status'        => 'available',
                    'price'         => $price,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
        }
        Seat::insert($payload);

        return back()->with('success', 'Tạo phòng chiếu & sơ đồ ghế thành công.');
    });
    }

    public function theaterDestroy(MovieTheater $movieTheater)
    {
        Seat::where('theaterID', $movieTheater->theaterID)->delete();
        $movieTheater->delete();
        return back()->with('deleteTheaterSuccess', 'Đã xoá phòng chiếu và toàn bộ ghế.');
    }

    public function theaterUpdate(Request $request, MovieTheater $movieTheater)
    {
        $data = $request->validate([
            'roomName' => ['required','string','max:255',Rule::unique('movie_theaters', 'roomName')->ignore($movieTheater->theaterID, 'theaterID'),],
            'rows'     => 'required|integer|min:1|max:26',
            'cols'     => 'required|integer|min:1|max:50',
            'normal_price' => 'required|integer|min:0',
            'vip_price'    => 'required|integer|min:0',
            'status'   => ['required','in:active,unable'],
            'note'     => ['nullable','string','max:255'],
        ]);

        $rows = (int)$data['rows'];
        $cols = (int)$data['cols'];
        $capacity = $rows * $cols;

        Seat::where('theaterID', $movieTheater->theaterID)->delete();
        $letters = range('A','Z');
        $payload = [];
        $now = now();

        for ($r = 0; $r < $rows; $r++) {
            for ($c = 1; $c <= $cols; $c++) {

                $seatType = $r === 0 ? 'vip' : 'normal';
                $price = $seatType === 'vip'
                    ? $data['vip_price']
                    : $data['normal_price'];

                $payload[] = [
                    'theaterID'     => $movieTheater->theaterID,
                    'verticalRow'   => $letters[$r],
                    'horizontalRow' => $c,
                    'seatType'      => $seatType,
                    'status'        => 'available',
                    'price'         => $price,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
        }
        Seat::insert($payload);

        $movieTheater->update([
            'roomName' => $data['roomName'],
            'capacity' => $capacity,  // AUTO
            'status'   => $data['status'],
            'note'     => $data['note'] ?? null,
        ]);
        return back()->with('success','Cập nhật phòng chiếu thành công.');
    }
    // ====================== SHOW SEAT ======================
    public function showSeats($theaterID)
    {
        $theater = MovieTheater::findOrFail($theaterID);
        $seats = Seat::where('theaterID', $theaterID)
            ->orderBy('verticalRow')
            ->orderBy('horizontalRow')
            ->get()
            ->groupBy('verticalRow');

        return view('adminDashboard.movietheaterManagement.seatmap', compact('theater', 'seats'));
    }
}
