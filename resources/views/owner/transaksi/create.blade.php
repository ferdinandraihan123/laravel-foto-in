@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buat Transaksi Baru</h1>
    
    <form action="{{ route('owner.transaksi.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label>Produk</label>
            <select name="id_jasa" class="form-control" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                <option value="{{ $product->id_jasa }}" {{ $selectedProduct && $selectedProduct->id_jasa == $product->id_jasa ? 'selected' : '' }}>
                    {{ $product->nama_jasa }} - Rp {{ number_format($product->harga) }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label>Kasir</label>
            <select name="kasir_id" class="form-control" required>
                <option value="">Pilih Kasir</option>
                @foreach($kasirs as $kasir)
                <option value="{{ $kasir->id }}">{{ $kasir->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" required min="1">
        </div>
        
        <div class="mb-3">
            <label>Total Harga</label>
            <input type="number" name="total_harga" class="form-control" required min="0">
        </div>
        
        <div class="mb-3">
            <label>Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-control" required>
                <option value="tunai">Tunai</option>
                <option value="debit">Debit</option>
                <option value="kredit">Kredit</option>
                <option value="qris">QRIS</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control" rows="3"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('owner.transaksi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection