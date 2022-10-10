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
            <div class="col-sm-12" id="pengaturan">
                <div class="col-sm-3 form-inline form-group-sm">
                    <span class="pointer text-primary">
                        <button class='btn btn-primary btn-sm' id="laporanBarang">
                            <i class="fa fa-newspaper-o"></i> Lihat Laporan Barang &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </button>
                    </span>
                </div>

                <div class="col-sm-3 form-inline form-group-sm">
                    <span class="pointer text-primary">
                        <button class='btn btn-success btn-sm' id="generateBarang">
                            <i class="fa fa-newspaper-o"></i> Buat Laporan Pengadaan Barang
                        </button>
                        <button class='btn btn-danger btn-sm' id="batalgenerateBarang" style="display: none">
                            <i class="fa fa-newspaper-o"></i> Batal Buat Laporan Pengadaan
                        </button>
                    </span>
                </div>
                <div class="col-sm-3 form-inline form-group-sm">
                    <span class="pointer text-primary">
                        <button class='btn btn-warning btn-sm' id="laporangenerateBarang">
                            <i class="fa fa-newspaper-o"></i> Lihat Laporan Pengadaan Barang
                        </button>
                    </span>
                </div>

                <div class="col-sm-2 form-inline form-group-sm">
                    <span class="pointer text-primary">
                        <button id="kategorigenerateBarang" class='btn btn-success btn-sm'>
                            <i class="fa fa-plus"></i> Kategori
                        </button>
                    </span>
                </div>

                <div class="col-sm-1 form-inline form-group-sm">
                    <span class="pointer text-primary">
                        <button id="unitgenerateBarang" class='btn btn-success btn-sm'>
                            <i class="fa fa-plus"></i> Unit
                        </button>
                    </span>
                </div>
            </div>

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
                <button class="close cancelAddItem">&times;</button><br>

                <center><h3> PENDAFTARAN BARANG </h3></center>
               
                <form name="addNewItemForm" id="addNewItemForm" role="form">
                    <div class="text-center errMsg" id='addCustErrMsg'></div>
                    
                    <br>
                    
                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="itemCode">Scan Barcode</label>
                            <input type="text" id="barCode" name="barCode" placeholder="Barcode" maxlength="80"
                            class="form-control" onchange="checkField(this.value, 'itemCodeErr')">
                            <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                            <span class="help-block errMsg" id="barCodeErr"></span>
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="itemName">Nama Barang <font style="color: red">(wajib)</font></label>
                            <input type="text" id="itemName" name="itemName" placeholder="Nama Barang" maxlength="80"
                            class="form-control" onchange="checkField(this.value, 'itemNameErr')">
                            <span class="help-block errMsg" id="itemNameErr"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="kategori">Merk/Tipe<font style="color: red">(wajib)</font></label>
                            <select id="itemKategori" name="itemKategori" class="form-control" style="visibility: none;" onchange="checkField(this.value, 'itemKategoriErr')">
                                <option disabled selected value></option>
                                <?php foreach($items as $get): ?>
                                <option value="<?= $get->kategori ?>"><?= $get->kategori ?></option>
                                <?php endforeach;?>
                            </select>
                            <span class="help-block errMsg" id="itemKategoriErr"></span>
                        </div>
                    </div>
                                    
                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="itemCode">Kodefikasi BMN <font style="color: red">(wajib)</font></label>
                            <input type="text" id="itemCode" name="itemCode" placeholder="Kodefikasi BMN" maxlength="80"
                            class="form-control" onchange="checkField(this.value, 'itemCodeErr')">
                            <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                            <span class="help-block errMsg" id="itemCodeErr"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="itemCode">NUP BMN <font style="color: red">(wajib)</font></label>
                            <input type="text" id="itemNup" name="itemNup" placeholder="NUP BMN" maxlength="80"
                            class="form-control" onchange="checkField(this.value, 'itemNupErr')">
                            <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                            <span class="help-block errMsg" id="itemNupErr"></span>
                        </div>
                    </div>                 

                    <!-- <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="kategori">Satuan <font style="color: red">(wajib)</font></label>
                            <select id="itemSatuan" name="itemSatuan" class="form-control" style="visibility: none;" onchange="checkField(this.value, 'itemSatuanErr')">
                                <option value="">-----</option>
                                <option value="Unit">Unit</option>
                                <option value="Buah">Buah</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Keping">Keping</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Rim">Rim</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                            <span class="help-block errMsg" id="itemSatuanError"></span>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="kategori">Kondisi <font style="color: red">(wajib)</font></label>
                            <select id="itemKondisi" name="itemKondisi" class="form-control" style="visibility: none;" onchange="checkField(this.value, 'itemKondisiErr')">
                                <option value=""></option>    
                                <option value="B">Baik</option>
                                <option value="RR">Rusak Ringan</option>
                                <option value="RB">Rusak Berat</option>
                            </select>
                            <span class="help-block errMsg" id="itemKondisiError"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="kategori">Unit</label>
                            <select id="itemUnit" name="itemUnit" class="form-control" style="visibility: none;" onchange="checkField(this.value, 'itemUnitErr')">
                                <option value="BMN">BMN</option>
                                <?php foreach($units as $get): ?>
                                <option value="<?= $get->satuan ?>"><?= $get->satuan ?></option>
                                <?php endforeach;?>
                            </select>
                            <span class="help-block errMsg" id="itemUnitErr"></span>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="itemQuantity">Jumlah</label>
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
                            <label for="unitPrice">Harga Satuan (Rp.)</label>
                            <input type="text" id="itemPrice" name="itemPrice" placeholder="(Rp. ) Harga Satuan" class="form-control"
                            onchange="checkField(this.value, 'itemPriceErr')">
                            <span class="help-block errMsg" id="itemPriceErr"></span>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="itemCode">Tahun Pengadaan</label>
                            <input type="text" id="itemTahun" name="itemTahun" placeholder="(Optional) Tahun Pengadaan" maxlength="80"
                            class="form-control" onchange="checkField(this.value, 'itemTahunErr')">
                            <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                            <span class="help-block errMsg" id="itemTahunErr"></span>
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
                            <label for="itemCode">Nama PIC</label>
                            <input type="text" id="itemPIC" name="itemPIC" placeholder="(Optional) Nama PIC" maxlength="80"
                            class="form-control" onchange="checkField(this.value, 'itemPICErr')">
                            <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                            <span class="help-block errMsg" id="itemPIC"></span>
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


        <div class="col-sm-12" id="unitListDiv">
            <!-- Item list Table-->
            <div class="row">
                <div class="col-sm-12" id="unitListTable"></div>
            </div>
            
            <!--end of table-->
        </div>

        <div class="col-sm-12" id="kategoriListDiv">
            <!-- Item list Table-->
            <div class="row">
                <div class="col-sm-12" id="kategoriListTable"></div>
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
                <h4 class="text-center">Penambahan Barang</h4>
                <div id="stockUpdateFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form name="updateStockForm" id="updateStockForm" role="form">
                    <div class="row">
                        <div class="col-sm-6 form-group-sm">
                            <label>Nama Barang</label>
                            <input type="text" readonly id="stockUpdateItemName" class="form-control">
                        </div>
                        
                        <div class="col-sm-6 form-group-sm">
                            <label>Kode Barang</label>
                            <input type="text" readonly id="stockUpdateItemCode" class="form-control">
                        </div>
                        
                    </div>

                    <div class="row"> 
                        <div class="col-sm-6 form-group-sm">
                            <label>Jumlah Barang Definitif</label>
                            <input type="text" readonly id="stockUpdateItemQInStock" class="form-control">
                        </div>
                        <div class="col-sm-6 form-group-sm">
                            <label>Jumlah Barang Transit</label>
                            <input type="text" readonly id="stockUpdateItemTInStock" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6 form-group-sm" style="display: none">
                            <label for="stockUpdateType">Jenis Perubahan</label>
                            <select id="stockUpdateType" class="form-control checkField">
                                <option value="newStock">Penambahan</option>
                            </select>
                            <span class="help-block errMsg" id="stockUpdateTypeErr"></span>
                        </div>

                        <div class="col-sm-6 form-group-sm">
                            <label for="pilihanUpdateType">Pilih Jenis Barang</label>
                            <select id="pilihanUpdateType" class="form-control checkField">
                                <option value="">------</option>
                                <option value="definitif">definitif</option>
                                <option value="transit">transit</option>
                            </select>
                            <span class="help-block errMsg" id="pilihanUpdateTypeErr"></span>
                        </div>
                        
                        <div class="col-sm-6 form-group-sm">
                            <label for="stockUpdateQuantity">Jumlah Penambahan</label>
                            <input type="number" id="stockUpdateQuantity" placeholder="Update Jumlah"
                            class="form-control checkField" min="0">
                            <span class="help-block errMsg" id="stockUpdateQuantityErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="stockUpdateDescription" class="">Keterangan</label>
                            <textarea class="form-control checkField" id="stockUpdateDescription" placeholder="Silahkan Pilih Jenis Barang" readonly=""></textarea>
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
                <h4 class="text-center">Edit Item Barang</h4>
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
                            <label for="unitNUP">NUP BMN</label>
                            <input type="text" id="itemNUPEdit" name="itemNUPEdit" placeholder="NUP BMN" class="form-control checkField">
                            <span class="help-block errMsg" id="itemNUPEditErr"></span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemNameEdit">Barcode</label>
                            <input type="text" id="barCodeEdit" placeholder="Barcode" autofocus class="form-control checkField">
                            <span class="help-block errMsg" id="barCodeEditErr"></span>
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemCode">Tahun Pengadaan</label>
                            <input type="text" id="itemTahunEdit" name="itemTahunEdit" placeholder="Tahun Pengadaan" class="form-control checkField">
                            <span class="help-block errMsg" id="itemTahunEditErr"></span>
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
                            <select id="itemKategoriEdit" name="itemKategoriEdit" class="form-control" onchange="checkField(this.value, 'kategoriErr')">
                                <option value=""></option>
                                <?php foreach($items as $get): ?>
                                    <option value="<?= $get->kategori ?>"><?= $get->kategori ?></option>
                                <?php endforeach;?>
                            </select>
                            <span class="help-block errMsg" id="itemKategoriEditErr"></span>
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label for="itemUnitEdit">Unit Kerja</label>
                            <select id="itemUnitEdit" name="itemUnitEdit" class="form-control" onchange="checkField(this.value, 'itemUnitErr')">
                                <option value="BMN">BMN</option>
                                <?php foreach($units as $get): ?>
                                    <option value="<?= $get->satuan ?>"><?= $get->satuan ?></option>
                                <?php endforeach;?>
                            </select>
                            <span class="help-block errMsg" id="itemUnitEditErr"></span>
                        </div>
                    
                        <div class="col-sm-4 form-group-sm">
                            <label for="itemKondisiEdit">Kondisi</label>
                            <select id="itemKondisiEdit" name="itemKondisiEdit" class="form-control" onchange="checkField(this.value, 'itemKondisiErr')">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                            <span class="help-block errMsg" id="itemKondisiEditErr"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="unitPIC">PIC</label>
                            <input type="text" id="itemPICEdit" name="itemPICEdit" placeholder="PIC" class="form-control checkField">
                            <span class="help-block errMsg" id="itemPICEditErr"></span>
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
                
                 <button class="btn btn-success" id="pindahItemSubmit">Aktivasi Data Barang</button>

                <button class="btn btn-primary" id="editItemSubmit">Simpan</button>
                <button class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<!--end of modal-->


<!--==== MULAI SESI : Pindah jumlah barang dari transit ke item ===-->
<div id="pindahTransitModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Pemindahan Barang Transit Definitif</h4>
                <div id="stockUpdateFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form name="updateStockForm" id="updateStockForm" role="form">
                    <div class="row">
                        <div class="col-sm-6 form-group-sm">
                            <label>Nama Barang</label>
                            <input type="text" readonly id="transitUpdateItemName" class="form-control">
                        </div>
                        
                        <div class="col-sm-6 form-group-sm">
                            <label>Kode Barang</label>
                            <input type="text" readonly id="transitUpdateItemCode" class="form-control">
                        </div>
                        
                    </div>

                    <div class="row"> 
                        <div class="col-sm-6 form-group-sm">
                            <label>Jumlah Barang Definitif</label>
                            <input type="text" readonly id="updateBarangDefinitif" class="form-control">
                        </div>
                        <div class="col-sm-6 form-group-sm">
                            <label>Jumlah Barang Transit</label>
                            <input type="text" readonly id="updateBarangTransit" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6 form-group-sm">
                            <label for="stockUpdateQuantity">Jumlah Total Barang Definitif</label>
                             <input type="text" readonly id="totalUpdate" class="form-control" readonly="">
                            <span class="help-block errMsg" id="stockUpdateQuantityErr"></span>
                        </div>
                        <div class="col-sm-6 form-group-sm">
                            <font>Dengan menekan tombol Pindahkan, maka semua data transit pada database akan dipindahkan ke data definitif</font>
                        </div>
                    </div>
                    
                    <input type="hidden" id="transitUpdateItemId">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="pindahkanUpdateSubmit">Pindahkan</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!--==== BATAS SESI ===-->




<script src="<?=base_url()?>public/js/items.js"></script>