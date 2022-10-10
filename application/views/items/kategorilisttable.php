<?php defined('BASEPATH') OR exit('') ?>

<div class='col-xs-12'>
    <form name="addNewKategoriForm" id="addNewKategoriForm" role="form">
        <div class="text-center errMsg" id='addKategoriErrMsg'></div>
        <br>
        <div class="row">
            <div class="col-sm-2 form-group-sm">
                <label for="itemCode">Nama Kategori</label>
            </div>
            <div class="col-sm-4 form-group-sm">
                <input type="text" id="newkategori" name="newkategori" placeholder="Masukkan Kategori" maxlength="80"
                class="form-control" onchange="checkField(this.value, 'newkategoriErr')">
                <span class="help-block errMsg" id="newkategoriErr"></span>
            </div>

            <div class="col-sm-4 form-group-sm">
                <button class="btn btn-primary btn-sm" id="addNewKategori">Tambahkan</button>
            </div>
        </div>
    </form>
    <br><br>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Daftar Kategori Barang</div>
        <?php if($allItems): ?>
            <div class="table table-responsive">
                <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 0;
                        foreach($allItems as $get):
                            ?>
                            <tr>
                                <input type="hidden" value="<?=$get->id?>" class="curKategoriId">
                                <th class="itemSN"><?=$sn?>.</th>
                                <td><span id="itemName-<?=$get->id?>"><?=$get->kategori?></span></td>
                                <td class="text-center"><i class="fa fa-trash text-danger delKategori pointer"></i></td>
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

<script src="<?=base_url()?>public/js/items.js"></script>