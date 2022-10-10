<?php
defined('BASEPATH') OR exit('');
?>

<div class="pwell hidden-print">   
    <div class="row">
        <div class="col-sm-12">
            <!-- sort and co row-->
            <div class="row">
                <div class="col-sm-12" id="navbar pengaturannavbar">
                    <div class="col-sm-2 form-inline form-group-sm">
                        <button class="btn btn-primary btn-sm" id='createItem'>Tambahkan Barang Baru</button>
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="itemsListPerPage">Tampilkan</label>
                        <select id="itemsListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label>per halaman</label>
                    </div>

                    <div class="col-sm-4 form-group-sm form-inline">
                        <label for="itemsListSortBy">Urutkan</label>
                        <select id="itemsListSortBy" class="form-control">
                            <option value="name-ASC">Nama Barang (A-Z)</option>
                            <option value="code-ASC">Kode Barang (Ascending)</option>
                            <!--
                            <option value="unitPrice-DESC">Unit Price (Highest first)</option>
                            <option value="quantity-DESC">Quantity (Highest first)</option>
                            <option value="name-DESC">Item Name (Z-A)</option>
                            <option value="code-DESC">Item Code (Descending)</option>
                            <option value="unitPrice-ASC">Unit Price (lowest first)</option>
                            <option value="quantity-ASC">Quantity (lowest first)</option>
                        -->
                        </select>
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for='itemSearch'><i class="fa fa-search"></i></label>
                        <input type="search" id="itemSearch" class="form-control" placeholder="Cari Barang Persediaan">
                    </div>
                </div><br><br>
            </div>
            <!-- end of sort and co div-->
        </div>
    </div>
    <hr>
    <!-- row of adding new item form and items list table-->
    <div class="row">
        <div class="col-sm-12">
            <!--Form to add/update an item-->
            <div class="col-sm-4" id='createNewItemDiv'>
                <div class="well">
                    <!-- <button class="btn btn-info btn-xs pull-left" id="useBarcodeScanner">Use Scanner</button> -->
                    <button class="close cancelAddItem">&times;</button><br>
                    <form name="addNewItemForm" id="addNewItemForm" role="form">
                        <div class="text-center errMsg" id='addCustErrMsg'></div>
                        
                        <br>
                        
                         <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="itemCode">Scan Barcode</label>
                                <input type="text" id="barCode" name="barCode" placeholder="Scan Barcode" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'itemCodeErr')">
                                <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                                <span class="help-block errMsg" id="barCodeErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="itemCode">Kode Barang <font style="color: red">(wajib)</font></label>
                                <input type="text" id="itemCode" name="itemCode" placeholder="Item Code" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'itemCodeErr')">
                                <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                                <span class="help-block errMsg" id="itemCodeErr"></span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="itemName">Nama Barang <font style="color: red">(wajib)</font></label>
                                <input type="text" id="itemName" name="itemName" placeholder="Item Name" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'itemNameErr')">
                                <span class="help-block errMsg" id="itemNameErr"></span>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="kategori">Kategori</label>
                                <input type="text" id="kategori" name="kategori" placeholder="Masukkan Kategori" maxlength="90"
                                    class="form-control" onchange="checkField(this.value, 'kategoriErr')">
                                <span class="help-block errMsg" id="kategoriErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="kategori">Satuan <font style="color: red">(wajib)</font></label>
                                <select id="satuan" name="satuan" class="form-control" style="visibility: none;" onchange="checkField(this.value, 'satuanErr')">
                                    <option value="">-----</option>
                                    <option value="Unit">Unit</option>
                                    <option value="Buah">Buah</option>
                                    <option value="Pasang">Pasang</option>
                                    <option value="Lembar">Lembar</option>
                                    <option value="Keping">Keping</option>
                                    <option value="Batang">Batang</option>
                                    <option value="Doz">Doz</option>
                                    <option value="Bungkus">Bungkus</option>
                                    <option value="Potong">Potong</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Rim">Rim</option>
                                    <option value="Botol">Botol</option>
                                    <option value="Dus">Dus</option>
                                    <option value="Karung">Karung</option>
                                    <option value="Koli">Koli</option>
                                    <option value="Sak">Sak</option>
                                    <option value="Set">Set</option>
                                    <option value="Gulung">Gulung</option>
                                    <option value="kiloGram">kiloGram</option>
                                    <option value="Gram">Gram</option>
                                    <option value="Meter">Meter</option>
                                    <option value="Liter">Liter</option>
                                    <option value="Lain-lain">Lain-lain</option>
                        </select>
                                <span class="help-block errMsg" id="itemSatuanErr"></span>
                            </div>
                        </div>

    <!--                     <div class="col-sm-8 form-group-sm">
                        <label class="control-label">Satuan</label>   
                        <select id="satuan" class="form-control" style="visibility: none;">
                            <option value="">-----</option>
                            <option value="Buah">Buah</option>
                            <option value="Lusin">Lusin</option>
                            <option value="Pak">Pak</option>
                            <option value="Doz">Doz</option>
                        </select>
                         <span class="help-block errMsg" id='satuanErr'></span>
                        <select id="transListSortBy" class="form-control" style="display: none;">
                            <option value="">-----</option>
                        </select>
                        <br>
                    </div> -->

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="itemQuantity">Jumlah <font style="color: red">(wajib)</font></label>
                                <input type="number" id="itemQuantity" name="itemQuantity" placeholder="Jumlah Ketersediaan"
                                    class="form-control" min="0" onchange="checkField(this.value, 'itemQuantityErr')">
                                <span class="help-block errMsg" id="itemQuantityErr"></span>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="itemMinimal">Jumlah Minimal</label>
                                <input type="number" id="itemMinimal" name="itemMinimal" placeholder="Jumlah Minimal"
                                    class="form-control" min="0" onchange="checkField(this.value, 'itemMinimalErr')">
                                <span class="help-block errMsg" id="itemMinimalErr"></span>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="unitPrice">Harga Satuan (Rp.) <font style="color: red">(wajib)</font></label>
                                <input type="text" id="itemPrice" name="itemPrice" placeholder="(Rp. ) Harga Satuan" class="form-control"
                                    onchange="checkField(this.value, 'itemPriceErr')">
                                <span class="help-block errMsg" id="itemPriceErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="lokasi">Lokasi Barang</label>
                                <input type="text" id="lokasi" name="lokasi" placeholder="(optional) Nomor Rak/Lemari/Tempat" maxlength="90"
                                    class="form-control" onchange="checkField(this.value, 'lokasiErr')">
                                <span class="help-block errMsg" id="lokasiErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="itemDescription" class="">Keterangan (Optional)</label>
                                <textarea class="form-control" id="itemDescription" name="itemDescription" rows='4'
                                    placeholder="Deskripsi Item (Optional)"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row text-center">
                            <div class="col-sm-6 form-group-sm">
                                <button class="btn btn-primary btn-sm" id="addNewItem">Tambahkan</button>
                            </div>

                            <div class="col-sm-6 form-group-sm">
                                <button type="reset" id="cancelAddItem" class="btn btn-danger btn-sm cancelAddItem" form='addNewItemForm'>Batal</button>
                            </div>
                        </div>
                    </form><!-- end of form-->
                </div>
            </div>
            
            <!--- Item list div-->
            <div class="col-sm-12" id="itemsListDiv">
                <!-- Item list Table-->
                <div class="row">
                    <div class="col-sm-12" id="itemsListTable"></div>
                </div>
                <!--end of table-->
            </div>


            <div class="col-sm-12" id="pengadaanListDiv">
                <!-- Item list Table-->
                <div class="row">
                    <div class="col-sm-12" id="pengadaanListTable"></div>
                    <center><button class="btn btn-success btn-md" id='generateLaporan'>Buat Laporan Pengadaan Terbaru</button></center>
                </div>
                
                <!--end of table-->
            </div>


            <div class="col-sm-12" id="laporanListDiv">
                <!-- Item list Table-->
                <div class="row">
                    <div class="col-sm-12" id="laporanListTable"></div>
                </div>
                
                <!--end of table-->
            </div>
            <!--- End of item list div-->

        </div>
    </div>
    <!-- End of row of adding new item form and items list table-->
</div>


<!-- buat report modal -->
<div class="modal fade" id='reportModal' data-backdrop='static' role='dialog'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss='modal'>&times;</div>
                <h4 class="text-center">Laporan Stock Opname</h4>
            </div>
            
            <div class="modal-body">
                <div class="row" id="datePair">
    <!--                 <div class="col-sm-8 form-group-sm">
                        <label class="control-label">Jenis</label>   
                        <select id="jenis" class="form-control" style="visibility: none;">
                            <option value="">-----</option>
                            <option value="items">Laporan ATK</option>
                            <option value="obat">Laporan Obat-obatan</option>
                        </select>
                         <span class="help-block errMsg" id='jenisErr'></span>
                        <select id="transListSortBy" class="form-control" style="display: none;">
                            <option value="">-----</option>
                        </select>
                        <br>
                    </div>
                    <div class="col-sm-6 form-group-sm">
                        <label class="control-label">Tanggal</label>                                    
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" id='tanggal' class="form-control date start" placeholder="YYYY-MM-DD"><br>
                        </div>
                        <span class="help-block errMsg" id='tanggalErr'></span>
                    </div> -->
                            <div class="col-sm-12 form-group-sm">
                                <label for="itemCode">Nomor Surat</label>
                                <input type="text" id="nomor" name="nomor" placeholder="Nomor Surat" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'nomorErr')" autofocus>
                                <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                                <span class="help-block errMsg" id="nomorErr"></span>
                            </div>
       
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-success" id='clickToGen'>Laporan</button>
                <button class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end report moda -->





<!--modal to update stock-->
<div id="updateStockModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Perubahan Data</h4>
                <div id="stockUpdateFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form name="updateStockForm" id="updateStockForm" role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label>Nama Barang</label>
                            <input type="text" readonly id="stockUpdateItemName" class="form-control">
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label>Kode Barang</label>
                            <input type="text" readonly id="stockUpdateItemCode" class="form-control">
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label>Jumlah Barang</label>
                            <input type="text" readonly id="stockUpdateItemQInStock" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6 form-group-sm">
                            <label for="stockUpdateType">Jenis Perubahan</label>
                            <select id="stockUpdateType" class="form-control checkField">
                                <option value="">---</option>
                                <option value="newStock">Penambahan</option>
                                <option value="deficit">Pengurangan</option>
                            </select>
                            <span class="help-block errMsg" id="stockUpdateTypeErr"></span>
                        </div>
                        
                        <div class="col-sm-6 form-group-sm">
                            <label for="stockUpdateQuantity">Jumlah</label>
                            <input type="number" id="stockUpdateQuantity" placeholder="Update Jumlah"
                                class="form-control checkField" min="0">
                            <span class="help-block errMsg" id="stockUpdateQuantityErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="stockUpdateDescription" class="">Description</label>
                            <textarea class="form-control checkField" id="stockUpdateDescription" placeholder="Ubah Deskripsi"></textarea>
                            <span class="help-block errMsg" id="stockUpdateDescriptionErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="stockUpdateItemId">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="stockUpdateSubmit">Update</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!--end of modal-->




<!--modal to edit item-->
<div id="editItemModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Edit / Pindahkan Data Transit</h4>
                <div id="editItemFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemNameEdit">Nama Barang</label>
                            <input type="text" id="itemNameEdit" placeholder="Item Name" autofocus class="form-control checkField">
                            <span class="help-block errMsg" id="itemNameEditErr"></span>
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemCode">Kode Barang</label>
                            <input type="text" id="itemCodeEdit" class="form-control" readonly="">
                            <span class="help-block errMsg" id="itemCodeEditErr"></span>
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label for="unitPrice">Harga Satuan (Rp.)</label>
                            <input type="text" id="itemPriceEdit" name="itemPrice" placeholder="Unit Price" class="form-control checkField">
                            <span class="help-block errMsg" id="itemPriceEditErr"></span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemNameEdit">Barcode</label>
                            <input type="text" id="barCodeEdit" placeholder="Barcode" autofocus class="form-control checkField">
                            <span class="help-block errMsg" id="barCodeEditErr"></span>
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemCode">Satuan Barang</label>

                             <select id="itemSatuanEdit" name="satuan" class="form-control" style="visibility: none;" onchange="checkField(this.value, 'satuanErr')">
                                    <option value=""></option>
                                    <option value="Unit">Unit</option>
                                    <option value="Buah">Buah</option>
                                    <option value="Pasang">Pasang</option>
                                    <option value="Lembar">Lembar</option>
                                    <option value="Keping">Keping</option>
                                    <option value="Batang">Batang</option>
                                    <option value="Doz">Doz</option>
                                    <option value="Bungkus">Bungkus</option>
                                    <option value="Potong">Potong</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Rim">Rim</option>
                                    <option value="Botol">Botol</option>
                                    <option value="Dus">Dus</option>
                                    <option value="Karung">Karung</option>
                                    <option value="Koli">Koli</option>
                                    <option value="Sak">Sak</option>
                                    <option value="Set">Set</option>
                                    <option value="Gulung">Gulung</option>
                                    <option value="kiloGram">kiloGram</option>
                                    <option value="Gram">Gram</option>
                                    <option value="Meter">Meter</option>
                                    <option value="Liter">Liter</option>
                                    <option value="Lain-lain">Lain-lain</option>
                        </select>

                            <span class="help-block errMsg" id="itemSatuanEditErr"></span>
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label for="unitPrice">Lokasi</label>
                            <input type="text" id="itemLokasiEdit" name="itemLokasi" placeholder="-" class="form-control checkField">
                            <span class="help-block errMsg" id="itemLokasiEditErr"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemNameEdit">Kategori</label>
                            <input type="text" id="itemKategoriEdit" placeholder="Item Name" autofocus class="form-control checkField">
                            <span class="help-block errMsg" id="itemKategoriEditErr"></span>
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemCode">Jumlah</label>
                            <input type="text" id="itemQuantityEdit" class="form-control" readonly="">
                            <span class="help-block errMsg" id="itemQuantityEditErr"></span>
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label for="unitPrice">Stok Aman</label>
                            <input type="text" id="itemMinimalEdit" name="itemPrice" placeholder="Unit Price" class="form-control checkField">
                            <span class="help-block errMsg" id="itemMinimalEditErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="itemDescriptionEdit" class="">Deskripsi (Optional)</label>
                            <textarea class="form-control" id="itemDescriptionEdit" placeholder="Deskripsi Barang (Optional)"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="itemIdEdit">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="pindahItemSubmit">Pindahkan Data Transit</button>

                <button class="btn btn-primary" id="editItemSubmit">Simpan</button>
                <button class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<!--end of modal-->


<script src="<?=base_url()?>public/js/transit.js"></script>