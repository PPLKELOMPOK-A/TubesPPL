namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturDonasi extends Model
{
    protected $fillable = [
        'id_donasi',
        'nama_makanan',
        'jumlah',
        'kategori',
        'alasan',
        'tanggal_pengajuan',
        'deskripsi',
        'bukti'
    ];
}