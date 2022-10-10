<?php
defined('BASEPATH') OR exit('');

$total_earned = 0;
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Laporan Transaksi</title>
		
        <!-- Favicon -->
        <link rel="shortcut icon" href="<?=base_url()?>public/images/icon.ico">
        <!-- favicon ends --->
        
        <!--- LOAD FILES -->
        <?php if((stristr($_SERVER['HTTP_HOST'], "localhost") !== FALSE) || (stristr($_SERVER['HTTP_HOST'], "192.168.") !== FALSE)|| (stristr($_SERVER['HTTP_HOST'], "127.0.0.") !== FALSE)): ?>
        <link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/bootstrap.min.css">

        <?php else: ?>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <?php endif; ?>
        
        <!-- custom CSS -->
        <link rel="stylesheet" href="<?= base_url() ?>public/css/main.css">
    </head>

    <body>
        <div class="container margin-top-5">
            
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h4>Data Transaksi</h4>
                </div>
            </div>
            
            <div class="row margin-top-5">
                <div class="col-xs-12 table-responsive">
                    <div class="panel panel-primary">
                        <!-- Default panel contents -->
                        <div class="panel-heading text-center">
                            Data Transaksi <?=date('jS M, Y', strtotime($from))?> - <?=date('jS M, Y', strtotime($to))?>
                        </div>
                        <?php if($allTransactions): ?>
                        <div class="table table-responsive">
                             <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Receipt No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Total</th>
                    <th>Total Harga</th>
                    <th>Untuk</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sn = 1;
                foreach($allTransactions as $get): ?>
                <tr>
                    <th><?= $sn ?>.</th>
                    <td><a class="pointer vtr" title="Click to view receipt"><?= $get->ref ?></a></td>
                    <td><?=$get->itemName?></td>
                    <td><?=$get->quantity?></td>
                    <td>Rp. <?= number_format($get->totalPrice, 2) ?></td>
                    <td><?=$get->cust_name?></td>
                    <td><?= date('jS M, Y h:ia', strtotime($get->transDate)) ?></td>
                </tr>
                <?php $sn++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
                        </div>
                        <!-- table div end--->
                        <?php else: ?>
                            <ul><li>Belum ada transaksi</li></ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="row" style="margin-bottom: 10px">
                <div class="col-xs-6">
                    <button class="btn btn-primary btn-sm hidden-print" onclick="window.print()">Cetak Laporan</button>
                </div>
                
                <!--<div class="col-xs-6 text-right">
                    <h4>Total Earned: &#8358;<?=number_format($total_earned, 2)?></h4>
                </div>-->
            </div>
        </div>
        <!--- panel end-->
    </body>
</html>