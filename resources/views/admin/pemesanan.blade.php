@extends('layouts.admin')

@section('title', 'Manajemen Pesanan - Cireng A\'paweh')

@section('content')
    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Pesanan</h2>
                <p>Kelola pesanan pelanggan Cireng A'paweh</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari ID Pesanan atau Nama...">
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="tabsContainer">
            <div class="tabsList">
                <button class="tabButton tabButton-active" data-tab="tab-pesanan-baru">
                    <span class="material-symbols-outlined">add_circle</span>
                    <span>1. Pesanan Baru</span>
                </button>
                <button class="tabButton" data-tab="tab-perlu-dikirim">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <span>2. Perlu Dikirim (Packing)</span>
                </button>
                <button class="tabButton" data-tab="tab-sedang-dikirim">
                    <span class="material-symbols-outlined">directions_car</span>
                    <span>3. Sedang Dikirim</span>
                </button>
                <button class="tabButton" data-tab="tab-selesai">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>4. Selesai</span>
                </button>
                <button class="tabButton" data-tab="tab-komplain">
                    <span class="material-symbols-outlined">error_circle</span>
                    <span>5. Komplain/Retur</span>
                </button>
            </div>
        </div>

        <!-- Tab Content: Pesanan Baru -->
        <div id="tab-pesanan-baru" class="tabContent tabContent-active">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelangggan</th>
                            <th>Pesanan</th>
                            <th>Kurir</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="idPesanan">ORD-20230501-001</td>
                            <td>12 Okt 2023, 10:30</td>
                            <td class="namaPelanggan">Andi Wijaya</td>
                            <td class="pesanan">3x Cireng Rujak, 1x Es Teh</td>
                            <td><span class="badge badgeKurir">GrabExpress</span></td>
                            <td class="totalHarga"><strong>Rp 45.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnIcon btnDetail" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                                <button class="btnIcon btnEdit" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-002</td>
                            <td>12 Okt 2023, 11:15</td>
                            <td class="namaPelanggan">Siti Aminah</td>
                            <td class="pesanan">2x Cireng Keju, 2x Ciliok Kuah</td>
                            <td><span class="badge badgeKurir badgeKurirGosend">GoSend</span></td>
                            <td class="totalHarga"><strong>Rp 62.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnIcon btnDetail" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                                <button class="btnIcon btnEdit" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-003</td>
                            <td>12 Okt 2023, 11:45</td>
                            <td class="namaPelanggan">Budi Santoso</td>
                            <td class="pesanan">5x Cireng Rujak (Family Pack)</td>
                            <td><span class="badge badgeKurir badgeKurirLamove">Lamove</span></td>
                            <td class="totalHarga"><strong>Rp 125.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnIcon btnDetail" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                                <button class="btnIcon btnEdit" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-004</td>
                            <td>12 Okt 2023, 12:05</td>
                            <td class="namaPelanggan">Dewi Lestari</td>
                            <td class="pesanan">1x Cireng Campur, 1x Kopi Tubruk</td>
                            <td><span class="badge badgeKurir badgeKurirPickup">Self Pickup</span></td>
                            <td class="totalHarga"><strong>Rp 38.500</strong></td>
                            <td class="aksiCell">
                                <button class="btnIcon btnDetail" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                                <button class="btnIcon btnEdit" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                    <button class="paginationNumber">2</button>
                    <button class="paginationNumber">3</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 4 dari 24 pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Perlu Dikirim (Packing) -->
        <div id="tab-perlu-dikirim" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Pesanan</th>
                            <th>Kurir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="idPesanan">ORD-20230501-005</td>
                            <td>13 Okt 2023, 08:30</td>
                            <td class="namaPelanggan">Rina Wijaya</td>
                            <td class="pesanan">
                                <div>4x Cireng Rujak Pedas</div>
                                <div class="pesananEst">Est: 1.2 kg</div>
                            </td>
                            <td>
                                <div class="kurirContainer">
                                    <div class="kurirName">GrabExpress</div>
                                    <span class="badge badgeKurirStatus badgeKurirStatusSiap">Siap diambil</span>
                                </div>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnAturPengiriman">
                                    <span>Atur Pengiriman</span>
                                </button>
                                <button class="btnAction btnCetakResi">
                                    <span>Cetak Resi</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-006</td>
                            <td>13 Okt 2023, 09:15</td>
                            <td class="namaPelanggan">Bambang Heru</td>
                            <td class="pesanan">
                                <div>2x Cireng Keju Melt, 1x Es Jeruk</div>
                                <div class="pesananEst">Est: 0.8 kg</div>
                            </td>
                            <td>
                                <div class="kurirContainer">
                                    <div class="kurirName">GoSend</div>
                                    <span class="badge badgeKurirStatus badgeKurirStatusMenunggu">Menunggu Kurir</span>
                                </div>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnAturPengiriman">
                                    <span>Atur Pengiriman</span>
                                </button>
                                <button class="btnAction btnCetakResi">
                                    <span>Cetak Resi</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-007</td>
                            <td>13 Okt 2023, 10:00</td>
                            <td class="namaPelanggan">Siska Pratama</td>
                            <td class="pesanan">
                                <div>10x Cireng Campur (Bulk)</div>
                                <div class="pesananEst">Est: 3.5 kg / 0.02 m³</div>
                            </td>
                            <td>
                                <div class="kurirContainer">
                                    <div class="kurirName">Lalamove</div>
                                    <span class="badge badgeKurirStatus badgeKurirStatusSiap">Siap diambil</span>
                                </div>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnAturPengiriman">
                                    <span>Atur Pengiriman</span>
                                </button>
                                <button class="btnAction btnCetakResi">
                                    <span>Cetak Resi</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-008</td>
                            <td>13 Okt 2023, 11:45</td>
                            <td class="namaPelanggan">Fajar Nugraha</td>
                            <td class="pesanan">
                                <div>3x Cireng Rujak, 2x Ciliok</div>
                                <div class="pesananEst">Est: 1.5 kg</div>
                            </td>
                            <td>
                                <div class="kurirContainer">
                                    <div class="kurirName">Self Pickup</div>
                                    <span class="badge badgeKurirStatus badgeKurirStatusMenunggu">Menunggu Kurir</span>
                                </div>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnAturPengiriman">
                                    <span>Atur Pengiriman</span>
                                </button>
                                <button class="btnAction btnCetakResi">
                                    <span>Cetak Resi</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                    <button class="paginationNumber">2</button>
                    <button class="paginationNumber">3</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 4 dari 24 pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Sedang Dikirim -->
        <div id="tab-sedang-dikirim" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Pesanan</th>
                            <th>Kurir</th>
                            <th>Status Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="idPesanan">ORD-20230501-009</td>
                            <td>14 Okt 2023, 10:20</td>
                            <td class="namaPelanggan">Andi Pratama</td>
                            <td class="pesanan">
                                <div>2x Cireng Rujak</div>
                                <div class="pesananEst">Est: 0.6 kg</div>
                            </td>
                            <td class="kurirCell">J&T Express</td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingDalam">Dalam Perjalanan</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLacakPaket">
                                    <span>Lacak Paket</span>
                                </button>
                                <button class="btnAction btnHubungiKurir">
                                    <span>Hubungi Kurir</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-010</td>
                            <td>14 Okt 2023, 11:45</td>
                            <td class="namaPelanggan">Siti Aminah</td>
                            <td class="pesanan">
                                <div>5x Cireng Keju Melt</div>
                                <div class="pesananEst">Est: 1.5 kg</div>
                            </td>
                            <td class="kurirCell">SiCepat</td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingTiba">Tiba di Transit</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLacakPaket">
                                    <span>Lacak Paket</span>
                                </button>
                                <button class="btnAction btnHubungiKurir">
                                    <span>Hubungi Kurir</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-011</td>
                            <td>14 Okt 2023, 13:10</td>
                            <td class="namaPelanggan">Budi Santoso</td>
                            <td class="pesanan">
                                <div>1x Paket Komplit Family</div>
                                <div class="pesananEst">Est: 2.2 kg</div>
                            </td>
                            <td class="kurirCell">GoSend</td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingOut">Out for Delivery</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLacakPaket">
                                    <span>Lacak Paket</span>
                                </button>
                                <button class="btnAction btnHubungiKurir">
                                    <span>Hubungi Kurir</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-012</td>
                            <td>14 Okt 2023, 14:05</td>
                            <td class="namaPelanggan">Dewi Lestari</td>
                            <td class="pesanan">
                                <div>3x Cireng Original</div>
                                <div class="pesananEst">Est: 0.9 kg</div>
                            </td>
                            <td class="kurirCell">GrabExpress</td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingDalam">Dalam Perjalanan</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLacakPaket">
                                    <span>Lacak Paket</span>
                                </button>
                                <button class="btnAction btnHubungiKurir">
                                    <span>Hubungi Kurir</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                    <button class="paginationNumber">2</button>
                    <button class="paginationNumber">3</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 4 dari 18 pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Selesai -->
        <div id="tab-selesai" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Pesanan</th>
                            <th>Kurir</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="idPesanan">ORD-20230501-013</td>
                            <td>15 Okt 2023, 09:15</td>
                            <td class="namaPelanggan">Budi Raharjo</td>
                            <td class="pesanan">
                                <div>3x Cireng Keju</div>
                                <span class="badge badgeItemStatus badgeItemStatusTerkirim">Terkirim</span>
                            </td>
                            <td class="kurirCell">J&T Express</td>
                            <td class="totalHarga"><strong>Rp 45.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnAction btnLihatDetail">
                                    <span>Lihat Detail</span>
                                </button>
                                <button class="btnAction btnUlangiPesanan">
                                    <span>Ulangi Pesanan</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-014</td>
                            <td>15 Okt 2023, 14:30</td>
                            <td class="namaPelanggan">Ani Wijaya</td>
                            <td class="pesanan">
                                <div>2x Cireng Rujak</div>
                                <span class="badge badgeItemStatus badgeItemStatusTerkirim">Terkirim</span>
                            </td>
                            <td class="kurirCell">SiCepat</td>
                            <td class="totalHarga"><strong>Rp 30.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnAction btnLihatDetail">
                                    <span>Lihat Detail</span>
                                </button>
                                <button class="btnAction btnUlangiPesanan">
                                    <span>Ulangi Pesanan</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-015</td>
                            <td>16 Okt 2023, 10:05</td>
                            <td class="namaPelanggan">Dedi Kurniawan</td>
                            <td class="pesanan">
                                <div>5x Cireng Original</div>
                                <span class="badge badgeItemStatus badgeItemStatusTerkirim">Terkirim</span>
                            </td>
                            <td class="kurirCell">GoSend</td>
                            <td class="totalHarga"><strong>Rp 60.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnAction btnLihatDetail">
                                    <span>Lihat Detail</span>
                                </button>
                                <button class="btnAction btnUlangiPesanan">
                                    <span>Ulangi Pesanan</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230501-016</td>
                            <td>16 Okt 2023, 11:20</td>
                            <td class="namaPelanggan">Linda Sari</td>
                            <td class="pesanan">
                                <div>1x Paket Komplit Family</div>
                                <span class="badge badgeItemStatus badgeItemStatusTerkirim">Terkirim</span>
                            </td>
                            <td class="kurirCell">GrabExpress</td>
                            <td class="totalHarga"><strong>Rp 35.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnAction btnLihatDetail">
                                    <span>Lihat Detail</span>
                                </button>
                                <button class="btnAction btnUlangiPesanan">
                                    <span>Ulangi Pesanan</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                    <button class="paginationNumber">2</button>
                    <button class="paginationNumber">3</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 4 dari 120 pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Komplain/Retur -->
        <div id="tab-komplain" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelangggan</th>
                            <th>Pesanan</th>
                            <th>Kurir</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="idPesanan">ORD-20230425-001</td>
                            <td>8 Okt 2023, 12:00</td>
                            <td class="namaPelanggan">Wahyu Dharma</td>
                            <td class="pesanan">2x Cireng Keju (Rusak saat pengiriman)</td>
                            <td><span class="badge badgeKurir">GrabExpress</span></td>
                            <td class="totalHarga"><strong>Rp 70.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnIcon btnDetail" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                                <button class="btnIcon btnEdit" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="idPesanan">ORD-20230424-001</td>
                            <td>7 Okt 2023, 14:30</td>
                            <td class="namaPelanggan">Ratna Wijaya</td>
                            <td class="pesanan">1x Cireng Keju (Item tidak sesuai pesanan)</td>
                            <td><span class="badge badgeKurir badgeKurirGosend">GoSend</span></td>
                            <td class="totalHarga"><strong>Rp 35.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnIcon btnDetail" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                                <button class="btnIcon btnEdit" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="btnIcon btnMore" title="Lebih Banyak">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 2 dari 2 pesanan</p>
            </div>
        </div>
    </div>
@endsection
