<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-sm-6 text-right'><!--<b>Items Total Worth/Price:</b> &#8358;<?=$cum_total ? number_format($cum_total, 2) : '0.00'?>--></div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Daftar Barang Transit</div>
        <?php if($allItems): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th style="display: none">Barcode</th>
                        <th>Kode Barang</th>
                        <th style="display: none">Harga (Rp.)</th>
                        <th style="display: none">Kategori</th>
                        <th style="display: none">Satuan</th>
                        <th style="display: none">Lokasi</th>
                        <th>Jumlah</th>
                        <th style="display: none">Stok Aman</th>
                        
                        <!--
                        <th>Harga</th>
                        <th>Jumlah Pemakaian</th>
                        <th>Jumlah Pengeluaran</th>-->
                        <th>Keterangan</th>
                        <th>Ubah Data</th>
                        <th>Edit / Pindahkan Data</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 0;
                    foreach($allItems as $get): ?>
                    <tr>
                        <input type="hidden" value="<?=$get->id?>" class="curItemId">
                        <th class="itemSN"><?=$sn?>.</th>
                        <td><span id="itemName-<?=$get->id?>"><?=$get->name?></span></td>
                        <td style="display: none"><span id="barCode-<?=$get->id?>"><?=$get->barcode?></td>
                        <td><span id="itemCode-<?=$get->id?>"><?=$get->code?></td>
                        <td style="display: none"><span id="itemPrice-<?=$get->id?>"><?=$get->unitPrice?></td>
                        <td style="display: none"><span id="itemKategori-<?=$get->id?>"><?=$get->kategori?></td>
                        <td style="display: none"><span id="itemSatuan-<?=$get->id?>"><?=$get->satuan?></td>
                        <td style="display: none"><span id="itemLokasi-<?=$get->id?>"><?=$get->lokasi?></td>
                        <?php
                            if($get->quantity <= $get->minimal){
                                $kurang[$no] = $get->name;
                            }

                            $stokaman = $get->minimal + ceil($get->minimal * 0.1);
                        ?>
                        <td>
                            <span id="itemQuantity-<?=$get->id?>"><?=$get->quantity?></span>
                        </td>
                        <td style="display: none"><span id="itemMinimal-<?=$get->id?>"><?=$get->minimal?></td>
                        <!--
                        <td>&#8358;<span id="itemPrice-<?=$get->id?>"><?=number_format($get->unitPrice, 2)?></span></td>
                        <td><?=$this->genmod->gettablecol('transactions', 'SUM(quantity)', 'itemCode', $get->code)?></td>
                        <td>
                            &#8358;<?=number_format($this->genmod->gettablecol('transactions', 'SUM(totalPrice)', 'itemCode', $get->code), 2)?>
                        </td>
                        -->
                        <td>
                            <span id="itemDesc-<?=$get->id?>" data-toggle="tooltip" title="<?=$get->description?>" data-placement="auto">
                                <?=word_limiter($get->description, 15)?>
                            </span>
                        </td>
                        <td><a class="pointer updateStock" id="stock-<?=$get->id?>">Ubah Data</a></td>
                        <td class="text-center text-primary">
                            <span class="editItem" id="edit-<?=$get->id?>"><i class="fa fa-file pointer"></i> Edit / Pindahkan Data Transit </span>
                        </td>
                        <td class="text-center"><i class="fa fa-trash text-danger delItem pointer"></i></td>
                    </tr>
                    <?php 
                    $sn++; 
                    $no++;?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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
