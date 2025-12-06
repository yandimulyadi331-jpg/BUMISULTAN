# CATATAN PENTING - Template Excel

File `template_import_keuangan_santri.csv` sudah dibuat di folder `public/templates/`.

Namun, untuk pengalaman user yang lebih baik, Anda bisa membuat file XLSX proper dengan format:

## Cara Membuat Template XLSX:

1. Buka Microsoft Excel atau Google Sheets
2. Buat header di baris pertama:
   ```
   | Tanggal | Deskripsi | Jumlah | Jenis | Kategori | Metode Pembayaran | Catatan |
   ```

3. Format kolom:
   - **Tanggal:** Format Date (DD/MM/YYYY)
   - **Deskripsi:** Text
   - **Jumlah:** Number (tanpa Rp)
   - **Jenis:** Dropdown (Pemasukan, Pengeluaran)
   - **Kategori:** Text (opsional, bisa kosong untuk auto-detect)
   - **Metode Pembayaran:** Dropdown (Tunai, Transfer Bank, E-Wallet, Lainnya)
   - **Catatan:** Text (opsional)

4. Tambahkan contoh data di baris 2-5:
   ```
   01/11/2025 | Uang saku bulan November | 500000 | Pemasukan | | Transfer Bank | Dari orangtua
   02/11/2025 | Beli sabun dan shampo | 25000 | Pengeluaran | | Tunai | 
   03/11/2025 | Makan siang di kantin | 15000 | Pengeluaran | | Tunai | 
   04/11/2025 | Beli buku dan pulpen | 20000 | Pengeluaran | | Tunai | 
   05/11/2025 | Pulsa internet 50rb | 50000 | Pengeluaran | | E-Wallet | 
   ```

5. Save as: `template_import_keuangan_santri.xlsx`

6. Copy file ke folder: `public/templates/`

## Alternative: Generate via Code

Atau Anda bisa generate XLSX menggunakan Laravel Excel:

```php
// Buat file: app/Exports/TemplateKeuanganSantriExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateKeuanganSantriExport implements WithHeadings, WithStyles, WithTitle
{
    public function headings(): array
    {
        return [
            'Tanggal',
            'Deskripsi',
            'Jumlah',
            'Jenis',
            'Kategori',
            'Metode Pembayaran',
            'Catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Template Import';
    }
}

// Lalu update Controller method downloadTemplate():
public function downloadTemplate()
{
    return Excel::download(new TemplateKeuanganSantriExport(), 'template_import_keuangan_santri.xlsx');
}
```

## File CSV Sementara

File CSV yang sudah dibuat (`template_import_keuangan_santri.csv`) sudah cukup untuk testing awal, tapi untuk production lebih baik gunakan XLSX.
