@extends('layouts.app')

@section('title', 'Penugasan Relawan')

@section('content')

<style>
.main-content{
    /* Mengubah padding atas menjadi 60px dan memberikan jarak aman margin-top */
    padding: 20px 40px 40px 40px; 
    margin-top: 20px; 
}

.header-section{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header-title h2{
    margin-top: -25px;
    color:#6B4F2A;
    font-size:28px;
    line-height: 1.2; /* Menjaga teks tinggi font tetap stabil */
}

.header-title p{
    margin-top:5px;
    color:#777;
    font-size:14px;
}

.btn-add{
    background:#6B4F2A;
    color:white;
    padding:12px 18px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    transition:0.2s;
}

.btn-add:hover{
    background:#5a4123;
}

.table-box{
    background:white;
    border-radius:16px;
    padding:20px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#6B4F2A;
    color:white;
    padding:14px;
    font-size:14px;
}

td{
    padding:14px;
    text-align:center;
    font-size:14px;
}

tr:nth-child(even){
    background:#f8f8f8;
}

.action-icons{
    display:flex;
    justify-content:center;
    gap:12px;
}

.action-icons a{
    color:#6B4F2A;
}

.action-icons button{
    border:none;
    background:none;
    cursor:pointer;
    color:#dc3545;
}

.alert{
    background:#d4edda;
    color:#155724;
    padding:14px;
    border-radius:10px;
    margin-bottom:20px;
}
</style>

<div class="main-content">

    <div class="header-section">

        <div class="header-title">
            <h2>Penugasan Relawan</h2>
        </div>

        <a href="{{ route('admin.penugasan.create') }}" class="btn-add">
            Tambah Relawan
        </a>

    </div>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-box">

        <table>
            <thead>
                <tr>
                    <th>ID Penugasan</th>
                    <th>ID Donasi</th>
                    <th>Nama Donatur</th>
                    <th>Relawan</th>
                    <th>Lokasi Pengambilan</th>
                    <th>Lokasi Pengantaran</th>
                    <th>Tanggal Penugasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->id_penugasan }}</td>
                    <td>{{ $item->id_donasi }}</td>
                    <td>{{ $item->nama_donatur }}</td>
                    <td>{{ $item->relawan }}</td>
                    <td>{{ $item->lokasi_pengambilan }}</td>
                    <td>{{ $item->lokasi_pengantaran }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_penugasan)->format('d-m-Y') }}</td>

                    <td>
                        <div class="action-icons">

                            <a href="{{ route('admin.penugasan.edit', $item->id) }}">
    <i class="fa-solid fa-pen"></i>
</a>

                            <form action="{{ route('admin.penugasan.destroy', $item->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">
        <i class="fa-solid fa-trash"></i>
    </button>
</form>

                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection