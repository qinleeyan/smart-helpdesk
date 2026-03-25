<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $user = User::firstOrCreate(
            ['email' => 'user@user.com'],
            ['name' => 'Dany Saputra', 'password' => Hash::make('password'), 'role' => 'user']
        );

        $cats = [];
        foreach (['Hardware', 'Software', 'Network', 'Account', 'General'] as $name) {
            $cats[] = Category::firstOrCreate(['name' => $name], ['slug' => \Illuminate\Support\Str::slug($name)]);
        }

        $ticketData = [
            ['Printer lantai 3 paper jam tidak bisa ditarik', 'Urgent', 'Open', 0, 'Hardware'],
            ['Akun email terblokir karena salah password 3 kali', 'Urgent', 'In Progress', 0, 'Account'],
            ['Request akses ke folder Finance (Samba/NAS)', 'Normal', 'Open', 1, 'Account'],
            ['Laptop Dell Latitude mati total tidak bisa di-charge', 'Urgent', 'In Progress', 1, 'Hardware'],
            ['Koneksi VPN AnyConnect putus-nyambung WFH', 'Urgent', 'Resolved', 2, 'Network'],
            ['Install ulang Adobe Premiere CC 2024 lisensi', 'Normal', 'Resolved', 2, 'Software'],
            ['Tolong reset password Active Directory (AD)', 'Urgent', 'Open', 0, 'Account'],
            ['Proyektor ruang meeting utama HDMI tidak detect', 'Urgent', 'In Progress', 0, 'Hardware'],
            ['Web portal HRIS error 500 Bad Gateway', 'Urgent', 'Open', 1, 'Software'],
            ['Upgrade RAM laptop WFH dari 8GB ke 16GB', 'Normal', 'Resolved', 5, 'Hardware'],
            ['Lisensi Microsoft Office 365 minta re-activate', 'Urgent', 'Open', 0, 'Software'],
            ['Internet kantor lantai 2 lambat parah 1Mbps', 'Urgent', 'In Progress', 1, 'Network'],
            ['Request tambah kuota mailbox Exchange email', 'Normal', 'Resolved', 4, 'Account'],
            ['Keyboard wireless Logitech ketumpahan kopi, beberapa tombol mati', 'Normal', 'Open', 2, 'Hardware'],
            ['Error ERP SAP login timeout connection failed', 'Urgent', 'In Progress', 0, 'Software'],
            ['Headset Jabra mic tidak fungsi untuk Zoom', 'Low', 'Resolved', 6, 'Hardware'],
            ['Tolong dibuatkan email baru untuk intern (Masa Berlaku 3 bulan)', 'Normal', 'Open', 1, 'Account'],
            ['Monitor external resolusinya pecah setelah update driver', 'Normal', 'In Progress', 2, 'Hardware'],
            ['Kabel LAN di meja B32 terputus kabelnya', 'Low', 'Resolved', 7, 'Network'],
            ['Jaringan WiFi Guest passwordnya tidak bisa digunakan', 'Normal', 'Open', 0, 'Network'],
            ['Mouse wireless kadang scroll sendiri (Phantom scrolling)', 'Low', 'Resolved', 8, 'Hardware'],
            ['Antivirus memblokir aplikasi internal perusahaan (False Positive)', 'Urgent', 'In Progress', 1, 'Software'],
            ['Request instalasi Visio Pro untuk 2 user team Design', 'Normal', 'Resolved', 9, 'Software'],
            ['Laptop sangat lemot saat buka file Excel makro >50MB', 'Urgent', 'Open', 1, 'Hardware'],
            ['Gagal akses sistem Intranet setelah ganti divisi', 'Urgent', 'In Progress', 0, 'Account'],
            ['Layar laptop bergaris pink vertikal secara tiba-tiba', 'Urgent', 'Resolved', 10, 'Hardware'],
            ['Zoom Client minta update admin credentials', 'Normal', 'Open', 0, 'Software'],
            ['SharePoint syncing error "Credentials Needed"', 'Normal', 'In Progress', 2, 'Software'],
            ['Request buka blokir YouTube khusus jam istirahat (Tim Kreatif)', 'Low', 'Resolved', 12, 'Network'],
            ['Cisco IP Phone di meja reception layarnya mati', 'Urgent', 'Open', 1, 'Hardware'],
        ];

        foreach ($ticketData as $i => $t) {
            $cat = collect($cats)->firstWhere('name', $t[4]) ?: collect($cats)->random();
            Ticket::firstOrCreate(
                ['subject' => $t[0]],
                [
                    'description' => 'Detail kronologi: User melaporkan kendala "' . $t[0] . '" pada jam sibuk. Mohon tim support segera melakukan verifikasi.',
                    'priority' => $t[1],
                    'status' => $t[2],
                    'category_id' => $cat->id,
                    'user_id' => ($i % 3 === 0) ? $admin->id : $user->id,
                    'created_at' => now()->subHours(rand(1, 48) + ($t[3] * 24)),
                ]
            );
        }

        $articles = [
            ['Cara Reset Password Email', 'Buka Outlook > File > Account Settings > Klik Change Password. Masukkan password lama dan password baru. Pastikan password baru memenuhi syarat kompleksitas (min 8 karakter, huruf besar, angka, simbol).', 'password,email,reset,outlook'],
            ['Troubleshoot Printer Tidak Bisa Print', 'Langkah 1: Cek apakah printer menyala dan kertas tersedia.\nLangkah 2: Restart Print Spooler service.\nLangkah 3: Cek driver printer di Device Manager.\nLangkah 4: Hubungi IT jika masih gagal.', 'printer,print,cetak,spooler'],
            ['Cara Connect VPN dari Rumah', 'Download Cisco AnyConnect dari portal IT.\nBuka AnyConnect > Masukkan server vpn.company.com.\nLogin dengan akun AD.\nTunggu connected, tes akses intranet.', 'vpn,remote,work from home,cisco'],
            ['Fix Laptop Blue Screen (BSOD)', 'Catat error code yang muncul.\nRestart laptop.\nJika berulang: boot ke Safe Mode > run sfc /scannow.\nUpdate driver yang outdated.\nJika masih terjadi, bawa ke IT Support.', 'bsod,blue screen,crash,laptop'],
        ];

        foreach ($articles as $a) {
            Article::firstOrCreate(
                ['title' => $a[0]],
                [
                    'slug' => \Illuminate\Support\Str::slug($a[0]),
                    'content' => $a[1],
                    'keywords' => $a[2],
                    'category_id' => $cats[array_rand($cats)]->id,
                    'views' => rand(5, 120),
                ]
            );
        }
    }
}
