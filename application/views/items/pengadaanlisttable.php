<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-sm-6 text-right'><!--<b>Items Total Worth/Price:</b> &#8358;<?=$cum_total ? number_format($cum_total, 2) : '0.00'?>--></div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Pengadaan</div>
        <?php if($allItems): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th style="display: none">Barcode</th>
                        <th>Kode Barang</th>
                        <th>Harga (Rp.)</th>
                        <th>Kategori</th>
                        <th style="display: none">Satuan</th>
                        <th style="display: none">Lokasi</th>
                        <th>Jumlah</th>
                        <th>Stok Aman</th>
                        
                        <!--
                        <th>Harga</th>
                        <th>Jumlah Pemakaian</th>
                        <th>Jumlah Pengeluaran</th>-->
                        <th>Keterangan</th>
                        <th>Akan diadakan</th>
                        <th>Jumlah Pengadaan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 0;
                    foreach($allItems as $get):
                        $stokaman = $get->minimal + ceil($get->minimal * 0.1);
                        $penambahan = $stokaman - $get->quantity;
                        if($get->quantity <= $get->minimal || ($get->quantity <= ($stokaman) && $get->quantity >= $get->minimal)){
                        ?>
                    <tr>
                        <input type="hidden" value="<?=$get->id?>" class="curItemId">
                        <th class="itemSN"><?=$sn?>.</th>
                        <td><span id="itemName-<?=$get->id?>"><?=$get->name?></span></td>
                        <td style="display: none"><span id="barCode-<?=$get->id?>"><?=$get->barcode?></td>
                        <td><span id="itemCode-<?=$get->id?>"><?=$get->code?></td>
                        <td><span id="itemPrice-<?=$get->id?>"><?=$get->unitPrice?></td>
                        <td><span id="itemKategori-<?=$get->id?>"><?=$get->kategori?></td>
                        <td style="display: none"><span id="itemSatuan-<?=$get->id?>"><?=$get->satuan?></td>
                        <td style="display: none"><span id="itemLokasi-<?=$get->id?>"><?=$get->lokasi?></td>
                        <?php
                            if($get->quantity <= $get->minimal){
                                $kurang[$no] = $get->name;
                            }

                        ?>
                        <td class="<?=$get->quantity <= $get->minimal ? 'bg-danger' : ($get->quantity <= ($stokaman) && $get->quantity >= $get->minimal ? 'bg-warning' : '')?>">
                            <span id="itemQuantity-<?=$get->id?>"><?=$get->quantity?></span>
                        </td>
                        <td><span id="itemMinimal-<?=$get->id?>"><?=$get->minimal?></td>
                        <td>
                            <span id="itemDesc-<?=$get->id?>" data-toggle="tooltip" title="<?=$get->description?>" data-placement="auto">
                                <?=word_limiter($get->description, 15)?>
                            </span>
                        </td>
                        <td class="text-center suspendAdmin text-success" id="sus-<?=$get->id?>">
                            <?php if($get->pengadaan === "1"): ?>
                            <i class="fa fa-toggle-on pointer"></i>
                            <?php else: ?>
                            <i class="fa fa-toggle-off pointer"></i>
                            <?php endif; ?>
                        </td>
                        <td><input type="number" class="pengadaanBarang" id="itemTambah-<?=$get->id?>" name="itemTambah-<?=$get->id?>" value="<?php echo $penambahan ?>"></td>
                    </tr>
                    <?php 
                    $sn++; 
                    $no++;?>
                    <?php } ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br><br>
        <?php endif; ?>
    </div>

    <!--- panel end-->
</div>

<!---Pagination div-->
<div class="col-sm-12 text-center">
    <ul class="pagination">
        <?= isset($links) ? $links : "" ?>
    </ul>
</div>
