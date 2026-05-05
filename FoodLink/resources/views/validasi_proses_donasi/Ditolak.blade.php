@extends('layouts.app')

@section('title', 'Donasi Ditolak')

@section('content')
<div class="max-w-7xl mx-auto">

    <h1 class="text-2xl font-bold mb-8 text-gray-800">
        Validasi Proses Donasi
    </h1>

    <!-- TABS -->
    <div class="flex space-x-2 mb-6">
        <a href="{{ route('validasi.index') }}"
           class="bg-white border text-gray-400 px-6 py-2 rounded-lg text-xs font-bold hover:bg-gray-50">
            Menunggu Validasi
        </a>

        <a href="{{ route('validasi.disetujui') }}"
           class="bg-white border text-gray-400 px-6 py-2 rounded-lg text-xs font-bold hover:bg-gray-50">
            Disetujui
        </a>

        <a href="{{ route('validasi.ditolak') }}"
           class="bg-[#E5E7EB] text-gray-700 px-6 py-2 rounded-lg text-xs font-bold">
            Ditolak
        </a>
    </div>

    <!-- LIST -->
    <div class="space-y-4">
        @forelse($donations as $item)

        <div class="bg-white p-5 rounded-2xl flex gap-6 shadow-sm border">

            <!-- IMAGE -->
            <div class="w-28 h-28 bg-[#FFF9F0] rounded-xl overflow-hidden">
                <img src="{{ $item->foto ? asset('storage/'.$item->foto) : 'https://cdn-icons-png.flaticon.com/512/3081/3081840.png' }}"
                     class="w-full h-full object-cover">
            </div>

            <!-- INFO -->
            <div class="flex-1">

                <div class="flex justify-between">
                    <h2 class="font-bold text-gray-800">
                        {{ $item->judul }}
                    </h2>

                    <span class="bg-red-400 text-white text-xs px-4 py-1 rounded-full">
                        DITOLAK
                    </span>
                </div>

                <p class="text-sm text-gray-500 mt-1">
                    Donatur: {{ $item->kategori ?? 'Umum' }}
                </p>

                <p class="text-xs text-gray-400">
                    {{ optional($item->created_at)->format('d M Y H:i') }}
                </p>

                <!-- BADGE -->
                <div class="flex gap-2 mt-3">
                    <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded">
                        {{ $item->quantity }} Porsi
                    </span>

                    <span class="bg-gray-400 text-white text-xs px-3 py-1 rounded">
                        Layak konsumsi
                    </span>
                </div>

                <!-- KETERANGAN PENOLAKAN -->
                <div class="bg-red-50 border border-red-200 p-3 rounded-lg mt-4 text-sm text-red-600">
                    {{ $item->keterangan_tolak ?? 'Donasi tidak memenuhi standar kelayakan atau sudah kadaluarsa.' }}
                </div>

                <!-- ACTION -->
                <form action="{{ route('validasi.return', $item->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button onclick="return confirm('Kembalikan ke antrian?')"
                            class="bg-gray-600 text-white px-4 py-1 rounded text-sm">
                        Return
                    </button>
                </form>

            </div>
        </div>

        @empty
            <p class="text-center text-gray-400 py-10">
                Tidak ada donasi yang ditolak
            </p>
        @endforelse
    </div>

</div>
@endsection