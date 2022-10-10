<?php
defined('BASEPATH') OR exit('');

$current_items = [];

if(isset($items) && !empty($items)){    
    foreach($items as $get){
        $current_items[$get->code] = $get->name;
    }
}
?>

<style href="<?=base_url('public/ext/datetimepicker/bootstrap-datepicker.min.css')?>" rel="stylesheet"></style>

<script>
    var currentItems = <?=json_encode($current_items)?>;
</script>

<div class="pwell hidden-print">   
    <div class="row">
        <div class="col-sm-12">
            <!--- Row to create new transaction-->
            <div class="row">
                <div class="col-sm-4">
                    <span class="pointer text-primary">
                        <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#reportModalx'>
                            <i class="fa fa-newspaper-o"></i> Buat Laporan Stock Opname
                        </button>
                    </span>
                </div>
                <div class="col-sm-4">
                    <span class="pointer text-primary">
                        <a href="<?= base_url().'kartu/laporanexcel' ?>" target="_blank">
                        <button class='btn btn-success btn-sm'>
                            <i class="fa fa-newspaper-o"></i> Export Laporan Opname (File Excel)
                        </button>
                        </a>
                    </span>
                </div>
                <div class="col-sm-3">
                    <span class="pointer text-primary">
                        
                            <button class='btn btn-warning btn-sm' id="cetakSemua">
                                <i class="fa fa-newspaper-o"></i> Cetak Semua Kartu Stok Barang (Pdf)
                            </button>

                    </span>
                </div>
            </div>
            <br>
            <!--- End of row to create new transaction-->
        </div>
    </div>
    
    <hr>
    
</div>


<div class="modal fade" id='reportModal' data-backdrop='static' role='dialog'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss='modal'>&times;</div>
                <h4 class="text-center">Buat Kartu Stok Barang</h4>
            </div>
            
            <div class="modal-body">
                <div class="row" id="datePair">
                    <div class="col-sm-12 form-group-sm">
                                <label for="itemCode">Kode Barang</label>
                                <input type="text" id="kodebarangkartu" name="kodebarangkartu" placeholder="Masukkan Kode Barang" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'kodeErr')" autofocus>
                                <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                                <span class="help-block errMsg" id="kodeErr"></span>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-success" id='clickToGenKartu'>Laporan</button>
                <button class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id='reportModalx' data-backdrop='static' role='dialog'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss='modal'>&times;</div>
                <h4 class="text-center">Laporan Stock Opname</h4>
            </div>
            
            <div class="modal-body">
                <div class="row" id="datePair">
                    <div class="col-sm-6 form-group-sm">
                        <label class="control-label">Tanggal</label>                                    
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" id='tanggal' class="form-control date start" placeholder="YYYY-MM-DD"><br>
                        </div>
                        <span class="help-block errMsg" id='tanggalErr'></span>
                    </div>
                            <div class="col-sm-12 form-group-sm">
                                <label for="itemCode">Nomor Surat <small>(Gunakan tanda '-' sebagai ganti tanda '/')</small></label>
                                <input type="text" id="nomor" name="nomor" placeholder="Contoh: S43-28-06-2018" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'nomorErr')" autofocus>
                                <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                                <span class="help-block errMsg" id="nomorErr"></span>
                            </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-success" id='clickToGenx'>Laporan</button>
                <button class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>

<!---End of copy of div to clone when adding more items to sales transaction---->
<script src="<?=base_url()?>public/js/kartu.js"></script>
<script src="<?=base_url('public/ext/datetimepicker/bootstrap-datepicker.min.js')?>"></script>
<script src="<?=base_url('public/ext/datetimepicker/jquery.timepicker.min.js')?>"></script>
<script src="<?=base_url()?>public/ext/datetimepicker/datepair.min.js"></script>
<script src="<?=base_url()?>public/ext/datetimepicker/jquery.datepair.min.js"></script>