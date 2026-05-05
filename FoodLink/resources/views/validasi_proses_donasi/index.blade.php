@extends('layouts.app')

@section('title', 'Validasi Proses Donasi')

@section('content')
<div class="max-w-7xl mx-auto">

    <h1 class="text-2xl font-bold mb-8">
        Validasi Proses Donasi
    </h1>

    <!-- STATS (DINAMIS) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-[#FFF9F0] p-6 rounded-xl border">
            <div class="text-3xl font-bold">
                {{ \App\Models\Donation::where('status','menunggu')->count() }}
            </div>
            <div class="text-xs font-bold text-gray-500">
                Menunggu
            </div>
        </div>

        <div class="bg-[#FFF9F0] p-6 rounded-xl border">
            <div class="text-3xl font-bold">
                {{ \App\Models\Donation::where('status','disetujui')->count() }}
            </div>
            <div class="text-xs font-bold text-gray-500">
                Disetujui
            </div>
        </div>

        <div class="bg-[#FFF9F0] p-6 rounded-xl border">
            <div class="text-3xl font-bold">
                {{ \App\Models\Donation::where('status','ditolak')->count() }}
            </div>
            <div class="text-xs font-bold text-gray-500">
                Ditolak
            </div>
        </div>

    </div>

    <!-- TABS -->
    <div class="flex space-x-2 mb-6">
        <a href="{{ route('validasi.index') }}"
           class="bg-[#E5E7EB] text-gray-700 px-6 py-2 rounded-lg text-xs font-bold">
            Menunggu Validasi
        </a>

        <a href="{{ route('validasi.disetujui') }}"
           class="bg-white border text-gray-400 px-6 py-2 rounded-lg text-xs font-bold hover:bg-gray-50">
            Disetujui
        </a>

        <a href="{{ route('validasi.ditolak') }}"
           class="bg-white border text-gray-400 px-6 py-2 rounded-lg text-xs font-bold hover:bg-gray-50">
            Ditolak
        </a>
    </div>

    <!-- NOTIF -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

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

                    <span class="bg-yellow-200 text-yellow-800 text-xs px-3 py-1 rounded-full">
                        MENUNGGU
                    </span>
                </div>

                <p class="text-sm text-gray-500 mt-1">
                    Donatur: {{ $item->kategori ?? '-' }}
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

                <!-- ACTION -->
                <div class="flex gap-2 mt-4">
                    
                    <form action="{{ route('validasi.setujui', $item->id) }}" method="POST">
                        @csrf
                        <button onclick="return confirm('Setujui donasi ini?')"
                                class="bg-green-400 text-white px-4 py-1 rounded text-sm">
                            Setujui
                        </button>
                    </form>

                    <form action="{{ route('validasi.tolak', $item->id) }}" method="POST">
                        @csrf
                        <button onclick="return confirm('Tolak donasi ini?')"
                                class="bg-red-400 text-white px-4 py-1 rounded text-sm">
                            Tolak
                        </button>
                    </form>

                </div>

            </div>
        </div>

        @empty
            <p class="text-center text-gray-400 py-10">
                Tidak ada donasi
            </p>
        @endforelse
    </div>

    <!-- PAGINATION INFO -->
    <div class="mt-6 text-sm text-gray-500">
        Total: {{ $donations->count() }} data
    </div>

</div>
@endsection