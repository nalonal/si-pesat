<?php defined('BASEPATH') OR exit('') ?>

<div class='col-xs-12'>
    <form name="addNewUnitForm" id="addNewUnitForm" role="form">
        <div class="text-center errMsg" id='addUnitErrMsg'></div>
        <br>
        <div class="row">
            <div class="col-sm-2 form-group-sm">
                <label for="itemCode">Nama Satuan/Unit Kerja</label>
            </div>
            <div class="col-sm-4 form-group-sm">
                <input type="text" id="newunit" name="newunit" placeholder="Masukkan Nama Satuan/Unit Kerja" maxlength="80"
                class="form-control" onchange="checkField(this.value, 'newunitErr')">
                <span class="help-block errMsg" id="newunitErr"></span>
            </div>

            <div class="col-sm-4 form-group-sm">
                <button class="btn btn-primary btn-sm" id="addNewUnit">Tambahkan</button>
            </div>
        </div>
    </form>
    <br><br>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Daftar Satuan Unit</div>
        <?php if($allItems): ?>
            <div class="table table-responsive">
                <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Satuan/Unit Kerja</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 0;
                        foreach($allItems as $get):
                            ?>
                            <tr>
                                <input type="hidden" value="<?=$get->id?>" class="curUnitId">
                                <th class="itemSN"><?=$sn?>.</th>
                                <td><span id="itemName-<?=$get->id?>"><?=$get->satuan?></span></td>
                                <td class="text-center"><i class="fa fa-trash text-danger delUnit pointer"></i></td>
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