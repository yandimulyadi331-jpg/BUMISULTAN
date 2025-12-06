# Dark Mode Implementation - Mode Karyawan

## Overview
Dark mode telah diimplementasikan secara universal untuk seluruh aplikasi mode karyawan menggunakan CSS variables dan JavaScript localStorage untuk menyimpan preferensi user.

## Struktur Implementasi

### 1. Layout Utama (`layouts/mobile/app.blade.php`)
- **CSS Variables Global**: Mendefinisikan variables untuk light dan dark mode
- **Toggle Button**: Tombol floating di kanan layar untuk switch mode
- **LocalStorage**: Menyimpan preferensi theme user
- **Bootstrap Sync**: Sinkronisasi dengan `data-bs-theme` attribute

### 2. CSS Variables yang Digunakan

#### Light Mode (Default)
```css
--bg-body: #e8ecf1
--bg-nav: #e8ecf1
--color-primary: #32745e
--color-secondary: #58907D
--color-text: #333
--color-text-secondary: #6b7280
--shadow-light: rgba(255, 255, 255, 0.8)
--shadow-dark: rgba(163, 177, 198, 0.6)
```

#### Dark Mode
```css
--bg-body: #1e2128
--bg-nav: #1e2128
--color-primary: #4ade80
--color-secondary: #22c55e
--color-text: #e5e7eb
--color-text-secondary: #9ca3af
--shadow-light: rgba(255, 255, 255, 0.02)
--shadow-dark: rgba(0, 0, 0, 0.4)
```

## Halaman yang Telah Diupdate

### ✅ Dashboard Pages
1. **Fasilitas Dashboard** (`fasilitas/dashboard-karyawan.blade.php`)
   - Semua backgrounds menggunakan `var(--bg-body)`
   - Text colors menggunakan variables
   - Neumorphic shadows responsive terhadap theme

2. **Saung Santri Dashboard** (`saungsantri/dashboard-karyawan.blade.php`)
   - Identik dengan fasilitas dashboard
   - Menu cards dengan dark mode support

3. **Manajemen Gedung** (`fasilitas/gedung/index-karyawan.blade.php`)
   - Gradient border dengan opacity adjustment
   - Icon containers dengan inset shadow
   - Buttons dengan subtle shadows di dark mode

### 🔄 Pages dengan Auto Dark Mode Support
Halaman-halaman berikut otomatis mendapat dark mode support melalui universal CSS di layout:
- Lembur Index (`lembur/index-karyawan.blade.php`)
- Lembur Create (`lembur/create-karyawan.blade.php`)
- Pengunjung Index & Detail (`fasilitas/pengunjung/*-karyawan.blade.php`)
- Barang Index, Transfer, Riwayat (`fasilitas/barang/*-karyawan.blade.php`)
- Ruangan Index (`fasilitas/ruangan/index-karyawan.blade.php`)
- Dokumen Index & Preview (`dokumen/*-karyawan.blade.php`)
- Administrasi Pages (`administrasi/*-karyawan.blade.php`)

## Cara Kerja

### 1. Toggle Theme
```javascript
function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    const theme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
    localStorage.setItem('theme', theme);
    document.documentElement.setAttribute('data-bs-theme', theme);
}
```

### 2. Load Saved Theme
```javascript
const currentTheme = localStorage.getItem('theme') || 'light';
if (currentTheme === 'dark') {
    document.body.classList.add('dark-mode');
    document.documentElement.setAttribute('data-bs-theme', 'dark');
}
```

### 3. CSS Selectors
Dark mode diterapkan menggunakan dua selector untuk kompatibilitas:
```css
[data-bs-theme="dark"],
body.dark-mode {
    /* Dark mode styles */
}
```

## Universal Override Rules

Layout utama memiliki universal rules untuk memastikan semua halaman mendapat dark mode:

```css
/* Override hardcoded backgrounds */
body.dark-mode [style*="background: #e8ecf1"] {
    background: #1e2128 !important;
}

/* Forms */
body.dark-mode input,
body.dark-mode textarea,
body.dark-mode select {
    background: #252932 !important;
    color: #e5e7eb !important;
}

/* Cards */
body.dark-mode .card,
body.dark-mode .menu-card {
    background: #1e2128 !important;
}
```

## Best Practices untuk Developer

### 1. Gunakan CSS Variables
❌ **Jangan:**
```css
background: #e8ecf1;
color: #333;
```

✅ **Gunakan:**
```css
background: var(--bg-body);
color: var(--color-text);
```

### 2. Neumorphic Shadows
Untuk neumorphic design, gunakan shadow variables:
```css
box-shadow: 
    8px 8px 16px var(--shadow-dark),
    -8px -8px 16px var(--shadow-light);
```

### 3. Dark Mode Specific Styles
Jika perlu style khusus dark mode:
```css
[data-bs-theme="dark"] .element {
    /* dark mode only styles */
}
```

## Design Characteristics

### Light Mode
- Background: Soft gray (#e8ecf1)
- Primary: Green (#32745e)
- Shadows: Prominent neumorphic effect
- Text: Dark gray (#333)

### Dark Mode
- Background: Dark blue-gray (#1e2128)
- Primary: Mint green (#4ade80)
- Shadows: Subtle and soft
- Text: Light gray (#e5e7eb)
- Border/gradients: Reduced opacity untuk elegance

## Testing Checklist

- [ ] Toggle button berfungsi di semua halaman
- [ ] Theme preference tersimpan setelah refresh
- [ ] Semua text readable di dark mode
- [ ] Forms dan inputs terlihat jelas
- [ ] Neumorphic effects tidak terlalu kuat di dark mode
- [ ] Icons dan badges readable
- [ ] Gradient borders terlihat subtle
- [ ] Navigation bottom bar compatible

## Maintenance Notes

1. **Menambah Halaman Baru**: 
   - Gunakan CSS variables dari awal
   - Test di light dan dark mode
   - Pastikan tidak ada hardcoded colors

2. **Update Existing Pages**:
   - Replace hardcoded `#e8ecf1` dengan `var(--bg-body)`
   - Replace hardcoded text colors dengan `var(--color-text)`
   - Test shadow visibility di dark mode

3. **Custom Components**:
   - Selalu define dark mode variant
   - Gunakan `[data-bs-theme="dark"]` selector
   - Test contrast ratio untuk accessibility

## Troubleshooting

### Issue: Halaman masih terang saat dark mode
**Solution**: Cek apakah ada inline styles atau hardcoded colors. Gunakan universal override di layout.

### Issue: Shadows terlalu kuat/lemah
**Solution**: Adjust `--shadow-light` dan `--shadow-dark` values di dark mode section.

### Issue: Text tidak readable
**Solution**: Update color menggunakan `var(--color-text)` atau `var(--color-text-secondary)`.

---

**Last Updated**: November 21, 2025
**Version**: 1.0
**Status**: ✅ Production Ready
