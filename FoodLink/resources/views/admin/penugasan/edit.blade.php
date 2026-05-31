@extends('layouts.app')

@section('title', 'Edit Penugasan Relawan')

@section('content')

<style>

.main-content{
    width:100%;
    min-height:calc(100vh - 70px);

    padding:30px;

    background:
        linear-gradient(
            rgba(255,255,255,0.55),
            rgba(255,255,255,0.55)
        ),
        url('{{ asset("img/BackgroundCreate.png") }}');

    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;

    position:relative;
}

/* HEADER */
.page-header{
    background:#A86F1F;
    color:white;

    width:fit-content;
    min-width:520px;

    margin:0 auto;

    padding:22px 35px;

    border-radius:18px;

    display:flex;
    align-items:center;
    gap:18px;

    box-shadow:0 5px 14px rgba(0,0,0,0.15);
}

.page-header i{
    font-size:34px;
}

.page-header h2{
    margin:0;
    font-size:24px;
    font-weight:700;
}

.page-header p{
    margin-top:4px;
    font-size:13px;
    font-weight:400;
}

/* FORM CONTAINER */
.form-wrapper{
    width:100%;
    max-width:980px;

    margin:35px auto 0;

    display:grid;
    grid-template-columns:1fr 1fr;

    gap:30px;
}

/* CARD */
.form-card{
    background:rgba(255,255,255,0.95);

    border-radius:24px;

    padding:30px;

    box-shadow:0 4px 14px rgba(0,0,0,0.08);
}

/* FORM GROUP */
.form-group{
    margin-bottom:22px;
}

.form-group label{
    display:block;

    margin-bottom:8px;

    font-size:14px;
    font-weight:600;

    color:#333;
}

.form-group input{
    width:100%;

    padding:14px 16px;

    border:1px solid #d7d7d7;
    border-radius:12px;

    background:white;

    font-size:14px;

    transition:.3s;
}

.form-group input:focus{
    outline:none;
    border-color:#A86F1F;
    box-shadow:0 0 0 3px rgba(168,111,31,.12);
}

/* READONLY */
.field-readonly{
    background:#efefef !important;
    cursor:not-allowed;
}

/* BUTTON */
.action-buttons{
    margin-top:30px;

    display:flex;
    justify-content:center;

    gap:15px;
}

.btn-update{
    background:#A86F1F;
    color:white;

    border:none;

    padding:12px 30px;

    border-radius:12px;

    font-size:14px;
    font-weight:600;

    cursor:pointer;

    transition:.2s;
}

.btn-update:hover{
    background:#8d5c17;
    transform:translateY(-2px);
}

.btn-cancel{
    background:#e4e4e4;
    color:#333;

    text-decoration:none;

    padding:12px 30px;

    border-radius:12px;

    font-size:14px;
    font-weight:600;

    transition:.2s;
}

.btn-cancel:hover{
    background:#d5d5d5;
}

/* ALERT */
.alert-success{
    background:#d4edda;
    color:#155724;

    padding:14px;

    border-radius:12px;

    margin-bottom:20px;
}

.alert-error{
    background:#f8d7da;
    color:#721c24;

    padding:14px;

    border-radius:12px;

    margin-bottom:20px;
}

.error-msg{
    color:red;
    font-size:12px;
    margin-top:5px;
    display:block;
}

/* RESPONSIVE */
@media(max-width:900px){

    .form-wrapper{
        grid-template-columns:1fr;
    }

    .page-header{
        width:100%;
        min-width:unset;

        text-align:center;
        justify-content:center;
    }

}
</style>

<div class="main-content">

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- HEADER -->
    <div class="page-header">

        <i class="fa-solid fa-user-pen"></i>

        <div>
            <h2>Edit Penugasan Relawan</h2>
            <p>Lengkapi data untuk memperbarui penugasan relawan</p>
        </div>

    </div>

    <!-- FORM -->
   <form action="{{ route('admin.penugasan.update', $penugasan->id) }}" method="POST">
    @csrf
    @method('PUT')

        <div class="form-wrapper">

            <!-- LEFT CARD -->
            <div class="form-card">

                <div class="form-group">
                    <label>ID Penugasan</label>

                    <input type="text"
                           name="id_penugasan"
                           value="{{ old('id_penugasan', $penugasan->id_penugasan) }}"
                           class="field-readonly"
                           readonly>
                </div>

                <div class="form-group">
                    <label>ID Donasi</label>

                    <input type="text"
                           name="id_donasi"
                           value="{{ old('id_donasi', $penugasan->id_donasi) }}"
                           class="field-readonly"
                           readonly>
                </div>

                <div class="form-group">
                    <label>Nama Donatur</label>

                    <input type="text"
                           name="nama_donatur"
                           value="{{ old('nama_donatur', $penugasan->nama_donatur) }}">

                    @error('nama_donatur')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Nama Relawan</label>

                    <input type="text"
                           name="relawan"
                           value="{{ old('relawan', $penugasan->relawan) }}">

                    @error('relawan')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- RIGHT CARD -->
            <div class="form-card">

                <div class="form-group">
                    <label>Lokasi Pengambilan</label>

                    <input type="text"
                           name="lokasi_pengambilan"
                           value="{{ old('lokasi_pengambilan', $penugasan->lokasi_pengambilan) }}">
                </div>

                <div class="form-group">
                    <label>Lokasi Pengantaran</label>

                    <input type="text"
                           name="lokasi_pengantaran"
                           value="{{ old('lokasi_pengantaran', $penugasan->lokasi_pengantaran) }}">

                    @error('lokasi_pengantaran')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Tanggal Penugasan</label>

                    <input type="date"
                           name="tanggal_penugasan"
                           value="{{ old('tanggal_penugasan', $penugasan->tanggal_penugasan) }}">

                    @error('tanggal_penugasan')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

            </div>

        </div>

        <!-- BUTTON -->
        <div class="action-buttons">

            <button type="submit" class="btn-update">
                Update
            </button>

            <a href="{{ route('admin.penugasan.index') }}" class="btn-cancel">
    Batal
</a>

        </div>

    </form>

</div>

@endsection