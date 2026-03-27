# 🎬 Laravel Video Downloader

Laravel 11 + Livewire 4 ile geliştirilmiş gelişmiş video indirici.

## ✨ Özellikler

- 📹 YouTube ve 1000+ site desteği (yt-dlp)
- 🎵 Format seçimi (MP4, MP3)
- 🎬 Kalite seçimi (4K, 1080p, 720p, 480p, 360p)
- 📋 Playlist desteği
- 📊 Gerçek zamanlı progress takibi
- 📥 İndirme geçmişi
- 🌙 Modern karanlık tema arayüz
- ⚡ Livewire ile dinamik içerik

## 🛠️ Teknoloji Stack

- **Laravel 13.2.0** (Framework)
- **Livewire 4.2.2** (Reactive Components)
- **Tailwind CSS v4** (UI Framework)
- **yt-dlp** (Video İndirme)
- **FFmpeg** (Video İşleme)
- **SQLite** (Veritabanı)

## 📋 Gereksinimler

- PHP 8.2+
- Composer
- Node.js & NPM
- yt-dlp ([İndirme](https://github.com/yt-dlp/yt-dlp#installation))
- FFmpeg ([İndirme](https://ffmpeg.org/download.html))

## 🚀 Kurulum

### 1. Depoyu Klonlayın

```bash
git clone https://github.com/serhankoral/laravel-video-downloader.git
cd laravel-video-downloader
```

### 2. Bağımlılıkları Yükleyin

```bash
composer install
npm install
```

### 3. Ortam Dosyasını Ayarlayın

```bash
cp .env.example .env
php artisan key:generate
```

`.env` dosyasında aşağıdaki değişkenleri kontrol edin:

```env
APP_NAME="Video Downloader"
APP_URL=http://laravel-video-downloader.test

DB_CONNECTION=sqlite

QUEUE_CONNECTION=database

YTDLP_PATH=yt-dlp
FFMPEG_PATH=ffmpeg
```

### 4. Veritabanını Hazırlayın

```bash
# SQLite database dosyası oluştur
touch database/database.sqlite

# Migrasyonları çalıştır
php artisan migrate
```

### 5. Asset'leri Derleyin

 ```bash
npm run build
```

### 6. Queue Worker'ı Başlatın

Ayrı bir terminal penceresinde:

```bash
php artisan queue:work
```

### 7. Geliştirme Sunucusunu Başlatın

```bash
php artisan serve
```

Tarayıcınızda `http://localhost:8000` adresini açın.

## 📖 Kullanım

1. Ana sayfada **URL girişi** alanına video URL'sini yapıştırın
2. **"Getir"** butonuna tıklayarak video bilgilerini çekin
3. Format (MP4/MP3) ve kalite seçin
4. **"İndirmeyi Başlat"** butonuna tıklayın
5. İndirme geçmişinden progress'i takip edin

### Desteklenen Siteler

- YouTube (tekil videolar ve playlist'ler)
- Vimeo
- Dailymotion
- Facebook
- Twitter/X
- Instagram
- Ve 1000+ site ([Tam liste](https://github.com/yt-dlp/yt-dlp/blob/master/supportedsites.md))

## 🗂️ Proje Yapısı

```
laravel-video-downloader/
├── app/
│   ├── Http/Controllers/
│   ├── Jobs/
│   │   └── ProcessDownload.php      # İndirme job'u
│   ├── Livewire/
│   │   ├── VideoDownloader.php      # Ana indirici bileşen
│   │   ├── DownloadHistory.php      # Geçmiş bileşeni
│   │   └── ProgressTracker.php      # Progress bileşeni
│   ├── Models/
│   │   └── Download.php             # İndirme modeli
│   └── Services/
│       └── YtDlpService.php         # yt-dlp servisi
├── database/
│   └── migrations/
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── livewire/
│       │   ├── video-downloader.blade.php
│       │   └── download-history.blade.php
│       └── welcome.blade.php
└── storage/
    └── app/
        └── downloads/                # İndirilen dosyalar
```

## ⚙️ Yapılandırma

### yt-dlp Path

Eğer yt-dlp farklı bir konumda ise `.env` dosyasında düzenleyin:

```env
YTDLP_PATH=/usr/local/bin/yt-dlp
```

### FFmpeg Path

```env
FFMPEG_PATH=/usr/local/bin/ffmpeg
```

### İndirme Klasörü

Varsayılan olarak `storage/app/downloads` kullanılır. Değiştirmek için `YtDlpService.php` dosyasını düzenleyin.

## 🐛 Sorun Giderme

### yt-dlp bulunamıyor

```bash
# Windows
winget install yt-dlp

# macOS
brew install yt-dlp

# Linux
sudo curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
sudo chmod a+rx /usr/local/bin/yt-dlp
```

### FFmpeg bulunamıyor

```bash
# Windows
winget install ffmpeg

# macOS
brew install ffmpeg

# Linux
sudo apt install ffmpeg
```

### Queue worker çalışmıyor

```bash
php artisan queue:restart
php artisan queue:work
```

### Asset'ler yüklenmiyor

```bash
npm run build
php artisan view:clear
```

## 📝 Lisans

Bu proje [MIT Lisansı](LICENSE) ile lisanslanmıştır.

## 👤 Geliştirici

**Serhan Koral**

- GitHub: [@serhankoral](https://github.com/serhankoral)

## 🙏 Teşekkürler

- [Laravel](https://laravel.com)
- [Livewire](https://livewire.laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [yt-dlp](https://github.com/yt-dlp/yt-dlp)
- [FFmpeg](https://ffmpeg.org)

---

⭐ Projeyi beğendiyseniz yıldız vermeyi unutmayın!
