@extends('layouts.admin')

@section('title', 'Manajemen Produk - Cireng A\'paweh')

@section('content')
    <div id="produk" class="pageActive">
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Produk</h2>
                <p>Kelola tipe produk Cireng A'paweh</p>
            </div>
        </div>

        <div class="tableWrapper">
            <table class="productTable">
                <tbody>
                    <tr>
                        <td class="fotoCell"><img
                                src="{{ asset('assets/img/produk/Cireng Isi Ayam Suwir Kuah Keju Creamy.jpg') }}"
                                alt="Cireng Kuah Keju Juara" class="productThumb"></td>
                        <td class="namaProduk">Cireng Kuah Keju Juara</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="paginationWrapper">
        </div>
    </div>
@endsection
