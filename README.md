Şantiye Yönetim Sistemi (Construction Management Platform)
Modüler, ölçeklenebilir ve saha-ofis entegrasyonuna sahip şantiye süreçleri yönetim platformu.
📌 Proje Hakkında
Bu proje; inşaat ve şantiye projelerinde saha operasyonlarını, hakediş süreçlerini, malzeme/stok durumlarını, personel puantajlarını ve finansal akışları uçtan uca entegre bir şekilde yönetmek üzere tasarlanmıştır.
Modüler mimarisi sayesinde her modül bağımsız çalışabileceği gibi, veritabanı seviyesinde birbiriyle ilişkili veri akışı sağlayarak saha verilerinin merkezle anlık paylaşılmasını kolaylaştırır.
🧩 Modüller ve İlişkiler Dizilimi
Modül	Ana İşlev	İlişkili Olduğu Modüller
📦 Malzeme & Depo	Stok girişi/çıkışı, zimmet, irsaliye ve fire takibi	Finans, Hakediş, Ekipman
👷 Personel & Puantaj	Günlük puantaj, mesai, vardiya ve yetkinlik takibi	Finans, İSG
📈 İş İlerleme & Hakediş	Metraj takibi, taşeron hakedişleri, günlük saha raporu	Malzeme & Depo, Personel
🚜 Ekipman & Makine	Yakıt tüketimi, periyodik bakım, çalışma saati takibi	Malzeme & Depo, Finans
📊 Finans & Bütçe	Gelir/gider dengesi, bütçe sapma analizi, maliyet kontrolü	Tüm Modüller
🛡️ İSG & Denetim	Saha denetimleri, uygunsuzluk bildirimleri, İSG belgeleri	Personel & Puantaj
🛠️ Teknoloji Yığını (Tech Stack)
Backend: RESTful API (Laravel / Node.js)
Veritabanı: PostgreSQL / MySQL
Frontend / Mobile: React / Vue.js / Flutter
Kimlik Doğrulama: JWT / OAuth2
Sürüm Kontrolü: Git & GitHub
🚀 Kurulum Adımları
Gereksinimler
PHP >= 8.2 veya Node.js >= 18
Composer / NPM
Veritabanı Sunucusu (PostgreSQL / MySQL)
Projeyi Çalıştırma
Depoyu Klonlayın:
Bash
git clone https://github.com/kullanici-adi/santiye-backend.git
cd santiye-backend
Bağımlılıkları Yükleyin:
Bash
composer install
npm install
Çevre Değişkenlerini Ayarlayın:
Bash
cp .env.example .env
php artisan key:generate
Veritabanı Migrasyonlarını Çalıştırın:
Bash
php artisan migrate --seed
Sunucuyu Başlatın:
Bash
php artisan serve
📂 Proje Yapısı
Plaintext
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Modül Controller sınıfları
│   │   └── Requests/       # Form doğrulama (Validation) kuralları
│   ├── Models/             # İlişkisel veritabanı modelleri
│   └── Services/           # Modüller arası iş mantığı (Business Logic)
├── database/
│   ├── migrations/         # Veritabanı şemaları
│   └── seeders/            # Örnek veri yükleyicileri
├── routes/                 # API ve Web rotaları
└── tests/                  # Birim ve entegrasyon testleri
🗺️ Yol Haritası (Roadmap)
[x] Temel veritabanı mimarisi ve modüller arası ilişki tasarımı
[ ] Rol tabanlı kimlik doğrulama (RBAC - Admin, Şantiye Şefi, Taşeron vb.)
[ ] Malzeme & Depo modülü API uçlarının tamamlanması
[ ] Personel Puantaj ve Vardiya modülü entegrasyonu
[ ] Metraj ve Taşeron Hakediş modülü geliştirmesi
[ ] Saha raporlama ve çevrimdışı (offline) veri desteği