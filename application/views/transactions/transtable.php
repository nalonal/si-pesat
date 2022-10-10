<?php defined('BASEPATH') OR exit('') ?>

<?= isset($range) && !empty($range) ? $range : ""; ?>
<div class="panel panel-primary">
    <!-- Default panel contents -->
    <div class="panel-heading">TRANSAKSI</div>
    <?php if($allTransactions): ?>
    <div class="table table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Total</th>
                    <th>Total Harga</th>
                    <th>Pengguna</th>
                    <th>Admin</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allTransactions as $get): ?>
                <tr>
                    <th><?= $sn ?>.</th>
                    <td><a class="pointer vtr" title="Click to view receipt"><?= $get->ref ?></a></td>
                    <td><?=$get->itemName?></td>
                    <td><?=$get->quantity?></td>
                    <td>Rp. <?= number_format($get->totalPrice, 2) ?></td>
                    <td><?=$get->cust_name?></td>
                    <td><?=$get->pengguna?></td>
                    <!-- <td><?= date('jS M, Y h:ia', strtotime($get->transDate)) ?></td> -->
                    <td><?= date('d M Y', strtotime($get->transDate)) ?></td>
                </tr>
                <?php $sn++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<!-- table div end-->
    <?php else: ?>
        <ul><li>No Transactions</li></ul>
    <?php endif; ?>
    
    <!--Pagination div-->
    <div class="col-sm-12 text-center">
        <ul class="pagination">
            <?= isset($links) ? $links : "" ?>
        </ul>
    </div>
</div>
<!-- panel end-->