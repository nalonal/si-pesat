<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-sm-6 text-right'><!--<b>Items Total Worth/Price:</b> &#8358;<?=$cum_total ? number_format($cum_total, 2) : '0.00'?>--></div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Laporan Pengadaan Barang</div>
        <?php if($allItems): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Administrator</th>
                        <th>File</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 0;
                    foreach($allItems as $get): 
                        $file = explode("/",$get->direktori);
                        $total = count($file);
                        $home = base_url();
                        $direktori = $home.$file[5]."/".$file[6]."/".$file[7];//mengambil lokasi file
                        ?>
                    <tr>
                        <input type="hidden" value="<?=$get->id?>" class="curItemId">
                        <input type="hidden" value="<?=$get->direktori?>" class="alamatDokumen">
                        <th class="itemSN"><?=$sn?>.</th>
                        <td><span id="itemName-<?=$get->id?>"><?=$get->tanggal?></span></td>
                        <td style="display: none"><span id="barCode-<?=$get->id?>"><?=$get->barcode?></td>
                        <td><span id="itemCode-<?=$get->id?>"><?=$get->administrator?></td>
                        <td><span id="itemPrice-<?=$get->id?>"><a href="<?= $direktori ?>"><?php echo $file[$total-1]; ?></a></td>
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
