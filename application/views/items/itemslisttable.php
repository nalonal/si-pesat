<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-sm-6 text-right'><!--<b>Items Total Worth/Price:</b> &#8358;<?=$cum_total ? number_format($cum_total, 2) : '0.00'?>--></div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Daftar Persediaan Barang</div>
        <?php if($allItems): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th style="display: none">Barcode</th>
                        <th>Kode Barang</th>
                        <th>NUP BMN</th>
                        <th>Tahun Pengadaan</th>
                        <th>Unit Kerja</th>
                        <th>Lokasi</th>
                        <th>PIC</th>
                        <th>Kondisi BMN</th>
                        <th style="display: none">Satuan</th>
                        <th style="display: none">Lokasi</th>
                        <!-- <th>Jumlah</th>
                        <th>Transit</th>
                        <th>Stok Aman</th> -->
                        
                        <!--
                        <th>Harga</th>
                        <th>Jumlah Pemakaian</th>
                        <th>Jumlah Pengeluaran</th>-->
                        <!-- <th>Keterangan</th> -->
                        <th>Edit</th>
                        <!-- <th>Aksi</th> -->
                        
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 0;
                    foreach($allItems as $get): 
                        if(!$get->transit){
                            $get->transit=0;
                        }
                        ?>
                    <tr>
                        <input type="hidden" value="<?=$get->id?>" class="curItemId">
                        <th class="itemSN"><?=$sn?>.</th>
                        <td><span id="itemName-<?=$get->id?>"><?=$get->name?></span></td>
                        <td><span id="itemKategori-<?=$get->id?>"><?=$get->kategori?></td>
                        <td style="display: none"><span id="barCode-<?=$get->id?>"><?=$get->barcode?></td>
                        <td class="text-primary"><span class="cetakKartu" id="itemCode-<?=$get->id?>"><?=$get->code?></td>
                        <td><span id="itemNUP-<?=$get->id?>"><?=$get->nup?></td>
                        <td><span id="itemTahun-<?=$get->id?>"><?=$get->tahun?></td>
                        <td><span id="itemUnit-<?=$get->id?>"><?=$get->unit?></td>
                        <td><span id="itemLokasi-<?=$get->id?>"><?=$get->lokasi?></td>
                        <td><span id="itemPIC-<?=$get->id?>"><?=$get->pic?></td>
                        <td><span id="itemKondisi-<?=$get->id?>"><?=$get->kondisi?></td>
                        <td style="display: none"><span id="itemSatuan-<?=$get->id?>"><?=$get->satuan?></td>
                        <td style="display: none"><span id="itemPrice-<?=$get->id?>"><?=$get->unitPrice?></td>
                        <td style="display: none"><span id="aktivasi-<?=$get->id?>"><?=$get->aktivasi?></td>
                        <td style="display: none"><span id="itemLokasi-<?=$get->id?>"><?=$get->lokasi?></td>
                        <?php
                            if($get->quantity <= $get->minimal){
                                $kurang[$no] = $get->name;
                            }
                            $stokaman = $get->minimal + ceil($get->minimal * 0.1);
                        ?>
                        <!-- <td class="<?=($get->quantity+$get->transit) <= $get->minimal ? 'bg-danger' : (($get->quantity+$get->transit) <= ($stokaman) && ($get->quantity+$get->transit) >= $get->minimal ? 'bg-warning' : '')?>">
                            <span id="itemQuantity-<?=$get->id?>"><?=$get->quantity?></span>
                            <span style="display: none" id="itemTransit-<?=$get->id?>"><?=$get->transit?></span>
                        </td> -->
                        <!-- <td class="text-center text-primary <?=($get->quantity+$get->transit) <= $get->minimal ? 'bg-danger' : (($get->quantity+$get->transit) <= ($stokaman) && ($get->quantity+$get->transit) >= $get->minimal ? 'bg-warning' : '')?>"><span class="pindahTransit" id="itemTranist-<?=$get->id?>"><i class="fa fa-reply pointer"></i> <?=$get->transit?></td> -->

                        <!-- <td><span id="itemMinimal-<?=$get->id?>"><?=$get->minimal?></td> -->
                
                        <td style="display: none">
                            <span id="itemDesc-<?=$get->id?>" data-toggle="tooltip" title="<?=$get->description?>" data-placement="auto">
                                <?=word_limiter($get->description, 15)?>
                            </span>
                        </td>
                        <td class="text-center text-primary">
                            <?php if($get->aktivasi == 1){ ?>
                            <span class="editItem" id="edit-<?=$get->id?>"><i class="fa fa-pencil pointer"></i> edit</span>
                            <?php } else{ ?>
                         <span class="editItem" id="edit-<?=$get->id?>">aktivasi</span>
                            <?php } ?>
                        </td>
                        <!-- <td>
                            <?php if($get->aktivasi == 1){ ?>
                            <a class="pointer updateStock" id="stock-<?=$get->id?>"><button class="btn-primary"> <i class="fa fa-plus pointer"></i> Penambahan</button></a>
                            <?php } else{ ?>
                            <button class="btn-light disable"> <i class="fa fa-cancel pointer"></i> disable</button>
                            <?php } ?>

                        </td> -->
                        <td class="text-center"><i class="fa fa-trash text-danger delItem pointer"></i></td>
                    </tr>
                    <?php 
                    $sn++; 
                    $no++;?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
      
    <?php endif; ?>





    <!--- panel end-->
</div>

<!---Pagination div-->
<div class="col-sm-12 text-center">
    <ul class="pagination">
        <?= isset($links) ? $links : "" ?>
    </ul>
</div>
