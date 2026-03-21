@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Transaksi</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Produk</th>
                <th>Kasir</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $transaksi)
            <tr>
                <td>{{ $transaksi->id }}</td>
                <td>{{ $transaksi->product->nama_jasa ?? '-' }}</td>
                <td>{{ $transaksi->kasir->name ?? '-' }}</td>
                <td>{{ $transaksi->jumlah }}</td>
                <td>Rp {{ number_format($transaksi->total_harga) }}</td>
                <td>
                    <span class="badge bg-{{ $transaksi->status == 'selesai' ? 'success' : ($transaksi->status == 'batal' ? 'danger' : 'warning') }}">
                        {{ $transaksi->status }}
                    </span>
                </td>
                <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('owner.transaksi.show', $transaksi->id) }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $transaksis->links() }}
</div>
@endsection