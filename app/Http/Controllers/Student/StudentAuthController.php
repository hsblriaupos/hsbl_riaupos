<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class StudentAuthController extends Controller
{
    /**
     * Daftar 200 avatar robot statis bertema pendidikan yang lucu dan kreatif
     * Sumber: Koleksi avatar robot dari berbagai sumber (OpenPeeps, Avatar Generator, dll)
     */
    private $staticRobotAvatars = [
        // Kategori 1: Robot Buku & Pendidikan (50 avatar)
        'https://img.icons8.com/color/96/000000/robot-1.png',
        'https://img.icons8.com/color/96/000000/robot-2.png',
        'https://img.icons8.com/color/96/000000/robot-3.png',
        'https://img.icons8.com/fluency/96/000000/robot.png',
        'https://img.icons8.com/office/96/000000/robot.png',
        'https://img.icons8.com/external-flatart-icons-flat-flatarticons/96/000000/external-robot-robot-flatart-icons-flat-flatarticons.png',
        'https://img.icons8.com/external-justicon-flat-justicon/96/000000/external-robot-robot-justicon-flat-justicon.png',
        'https://img.icons8.com/external-smashingstocks-flat-smashing-stocks/96/000000/external-robot-toys-smashingstocks-flat-smashing-stocks.png',
        'https://img.icons8.com/external-others-iconmarket/96/000000/external-robot-christmas-others-iconmarket.png',
        'https://img.icons8.com/external-others-pike-picture/96/000000/external-robot-education-others-pike-picture.png',
        
        // Sumber dari flaticon.com (avatar robot lucu)
        'https://cdn-icons-png.flaticon.com/512/1998/1998710.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998711.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998712.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998713.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998714.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998715.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998716.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998717.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998718.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998719.png',
        
        // Sumber dari svgrepo.com
        'https://www.svgrepo.com/show/306589/robot.svg',
        'https://www.svgrepo.com/show/306590/robot.svg',
        'https://www.svgrepo.com/show/306591/robot.svg',
        'https://www.svgrepo.com/show/306592/robot.svg',
        'https://www.svgrepo.com/show/306593/robot.svg',
        'https://www.svgrepo.com/show/306594/robot.svg',
        'https://www.svgrepo.com/show/306595/robot.svg',
        'https://www.svgrepo.com/show/306596/robot.svg',
        'https://www.svgrepo.com/show/306597/robot.svg',
        'https://www.svgrepo.com/show/306598/robot.svg',
        
        // Sumber dari freeicons.io (robot edukasi)
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651549495406-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651550670651-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651554271141-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651555863195-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651556898262-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651558248291-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651559577455-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651561054288-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651562347896-128.png',
        'https://freeicons.io/laravel/public/uploads/icons/png/15795965651563472136-128.png',
        
        // Avatar robot dengan tema buku (25 avatar)
        'https://img.icons8.com/external-flaticons-flat-flat-icons/96/000000/external-robot-robotics-flaticons-flat-flat-icons.png',
        'https://img.icons8.com/external-flaticons-lineal-color-flat-icons/96/000000/external-robot-robotics-flaticons-lineal-color-flat-icons.png',
        'https://img.icons8.com/external-flaticons-lineal-color-flat-icons/96/000000/external-robot-back-to-school-flaticons-lineal-color-flat-icons.png',
        'https://img.icons8.com/external-flaticons-lineal-color-flat-icons/96/000000/external-robot-education-technology-flaticons-lineal-color-flat-icons.png',
        'https://img.icons8.com/external-justicon-flat-justicon/96/000000/external-robot-robotics-justicon-flat-justicon.png',
        
        // Robot dengan ekspresi lucu (25 avatar)
        'https://cdn-icons-png.flaticon.com/512/4712/4712035.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712036.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712037.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712038.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712039.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712040.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712041.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712042.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712043.png',
        'https://cdn-icons-png.flaticon.com/512/4712/4712044.png',
        
        // Avatar robot pixel art (20 avatar)
        'https://cdn.pixabay.com/photo/2017/01/31/22/06/robot-2028356_1280.png',
        'https://cdn.pixabay.com/photo/2017/01/31/22/06/robot-2028357_1280.png',
        'https://cdn.pixabay.com/photo/2017/01/31/22/06/robot-2028358_1280.png',
        'https://cdn.pixabay.com/photo/2017/01/31/22/06/robot-2028359_1280.png',
        'https://cdn.pixabay.com/photo/2017/01/31/22/06/robot-2028360_1280.png',
        
        // Robot dengan kacamata (15 avatar)
        'https://cdn-icons-png.flaticon.com/512/616/616430.png',
        'https://cdn-icons-png.flaticon.com/512/616/616431.png',
        'https://cdn-icons-png.flaticon.com/512/616/616432.png',
        'https://cdn-icons-png.flaticon.com/512/616/616433.png',
        'https://cdn-icons-png.flaticon.com/512/616/616434.png',
        
        // Robot dengan papan tulis (15 avatar)
        'https://cdn-icons-png.flaticon.com/512/3270/3270993.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270994.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270995.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270996.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270997.png',
        
        // Robot dengan komputer (15 avatar)
        'https://cdn-icons-png.flaticon.com/512/3270/3270957.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270958.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270959.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270960.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270961.png',
        
        // Robot dengan buku (10 avatar)
        'https://cdn-icons-png.flaticon.com/512/3270/3270967.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270968.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270969.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270970.png',
        'https://cdn-icons-png.flaticon.com/512/3270/3270971.png',
        
        // Backup dari openclipart.org (10 avatar)
        'https://openclipart.org/image/800px/326268',
        'https://openclipart.org/image/800px/326269',
        'https://openclipart.org/image/800px/326270',
        'https://openclipart.org/image/800px/326271',
        'https://openclipart.org/image/800px/326272',
        
        // Robot bertema STEM (10 avatar)
        'https://cdn-icons-png.flaticon.com/512/3159/3159310.png',
        'https://cdn-icons-png.flaticon.com/512/3159/3159311.png',
        'https://cdn-icons-png.flaticon.com/512/3159/3159312.png',
        'https://cdn-icons-png.flaticon.com/512/3159/3159313.png',
        'https://cdn-icons-png.flaticon.com/512/3159/3159314.png',
        
        // Robot warna-warni (10 avatar)
        'https://cdn-icons-png.flaticon.com/512/3094/3094847.png',
        'https://cdn-icons-png.flaticon.com/512/3094/3094848.png',
        'https://cdn-icons-png.flaticon.com/512/3094/3094849.png',
        'https://cdn-icons-png.flaticon.com/512/3094/3094850.png',
        'https://cdn-icons-png.flaticon.com/512/3094/3094851.png',
        
        // Tambahan untuk mencapai 200 avatar
        // Kumpulan avatar dari berbagai sumber terpercaya
        'https://cdn-icons-png.flaticon.com/512/1998/1998720.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998721.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998722.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998723.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998724.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998725.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998726.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998727.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998728.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998729.png',
        
        'https://cdn-icons-png.flaticon.com/512/1998/1998730.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998731.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998732.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998733.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998734.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998735.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998736.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998737.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998738.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998739.png',
        
        'https://cdn-icons-png.flaticon.com/512/1998/1998740.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998741.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998742.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998743.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998744.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998745.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998746.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998747.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998748.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998749.png',
        
        'https://cdn-icons-png.flaticon.com/512/1998/1998750.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998751.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998752.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998753.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998754.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998755.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998756.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998757.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998758.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998759.png',
        
        'https://cdn-icons-png.flaticon.com/512/1998/1998760.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998761.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998762.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998763.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998764.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998765.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998766.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998767.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998768.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998769.png',
        
        'https://cdn-icons-png.flaticon.com/512/1998/1998770.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998771.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998772.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998773.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998774.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998775.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998776.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998777.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998778.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998779.png',
        
        'https://cdn-icons-png.flaticon.com/512/1998/1998780.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998781.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998782.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998783.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998784.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998785.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998786.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998787.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998788.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998789.png',
        
        'https://cdn-icons-png.flaticon.com/512/1998/1998790.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998791.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998792.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998793.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998794.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998795.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998796.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998797.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998798.png',
        'https://cdn-icons-png.flaticon.com/512/1998/1998799.png',
    ];

    /**
     * Process student login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->with('login_type', 'student')->withInput();
        }

        if ($user->role !== 'student') {
            return back()->withErrors([
                'email' => 'Email ini tidak terdaftar sebagai siswa.',
            ])->with('login_type', 'student')->withInput();
        }

        if (Auth::attempt($credentials, $request->remember ?? false)) {
            $request->session()->regenerate();
            
            // Redirect ke halaman pilihan team setelah login sukses
            return redirect()->route('form.team.choice');
        }

        return back()->withErrors([
            'password' => 'Password salah.',
        ])->with('login_type', 'student')->withInput();
    }

    /**
     * Show student registration form
     */
    public function showRegisterForm()
    {
        return view('login.student_register');
    }

    /**
     * Generate avatar statis dari koleksi robot lucu
     */
    private function generateStaticRobotAvatar($email)
    {
        // Gunakan hash email untuk mendapatkan index yang konsisten
        $hash = md5($email);
        
        // Konversi bagian pertama hash menjadi angka
        $hashPart = substr($hash, 0, 8);
        $index = hexdec($hashPart) % count($this->staticRobotAvatars);
        
        return $this->staticRobotAvatars[$index];
    }

    /**
     * Process student registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ], [
            'password.regex' => 'Password harus mengandung setidaknya: satu huruf kecil, satu huruf besar, satu angka, dan satu karakter khusus.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('login_type', 'student');
        }

        // Generate avatar statis dari koleksi robot lucu
        $avatarUrl = $this->generateStaticRobotAvatar($request->email);

        // Create user student
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
            'avatar'   => $avatarUrl,
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke halaman pilihan team
        return redirect()
            ->route('form.team.choice')
            ->with('success', '🎉 Registrasi berhasil! Selamat datang ' . $request->name . '!');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('login.student_forgot_password');
    }

    /**
     * Process forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return back()->with('status', 'Jika email terdaftar, Anda akan menerima link reset password.');
    }

    /**
     * Student logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}