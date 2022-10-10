<?php
defined('BASEPATH') OR exit('');

$current_items = [];

if(isset($items) && !empty($items)){    
    foreach($items as $get){
        $current_items[$get->code] = $get->name;
    }
}

//untuk transit items
$transit_items = [];

if(isset($transititems) && !empty($transititems)){    
    foreach($transititems as $get){
        $transit_items[$get->code] = $get->name;
    }
}


//end


?>

<style href="<?=base_url('public/ext/datetimepicker/bootstrap-datepicker.min.css')?>" rel="stylesheet"></style>

<script>
    var currentItems = <?=json_encode($current_items)?>;
    var transitcurrentItems = <?=json_encode($transit_items)?>;
    //fungsi autofokus
    function autofocusbarcode(item){
        $("#nilaibarcode").focus();
    }
</script>

<div class="pwell hidden-print">   
    <div class="row">
        <div class="col-sm-12">
            <!--- Row to create new transaction-->
            <div class="row">
                <div class="col-sm-3">
                    <span class="pointer text-primary">
                        <button class='btn btn-primary btn-sm' data-toggle="collapse" data-target="#newTransDiv" id='showTransForm' aria-expanded="false" aria-controls="newTransDiv"><i class="fa fa-plus"></i> Transaksi Baru &nbsp;&nbsp;&nbsp;</button>

                        <button class='btn btn-danger btn-sm' data-toggle="collapse" data-target="#newTransDiv" id='hideTransForm'><i class="fa fa-close"></i> Batal Transaksi &nbsp;&nbsp;&nbsp;</button>
                    </span>
                </div>

                 <div class="col-sm-3">
                </div>

                <div class="col-sm-3">
                    <span class="pointer text-primary">
                        <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#reportModal'>
                            <i class="fa fa-newspaper-o"></i> Buat Laporan 
                        </button>
                    </span>
                </div>
            </div>
            <br>
            <!--- End of row to create new transaction-->
            <!---form to create new transactions--->
            <div class="row collapse multi-collapse" id="newTransDiv">
                <!---div to display transaction form--->
                <div class="col-sm-12" id="salesTransFormDiv">
                    <div class="well">
                        <form name="salesTransForm" id="salesTransForm" role="form">
                            <div class="text-center errMsg" id='newTransErrMsg'></div>
                            <br>

                            <div class="row">
                                <div class="col-sm-12">
                                    <!--Cloned div comes here--->
                                    
                                    <!--End of cloned div here--->
                                    
                                    <!--- Text to click to add another item to transaction-->
                                    <div class="row">
                                    <div class="col-sm-3 form-group-sm" id="coba">
                                            <label for="modeOfPayment">Nama Bagian</label>
                                            <select class="form-control checkField" id="custName" onchange="autofocusbarcode(this);">
                                                <option value="">---</option>
                                                <?php if($semuaUnit){ ?>
                                                <?php foreach ($semuaUnit as $key) { ?>
                                                <option value="<?=$key->satuan; ?>"><?= $key->satuan;?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="help-block errMsg" id="custNameErr"></span>
                                        </div>
                                    </div>

                                    <div id="appendClonedDivHere"></div>
                                    
                                    <div class="row">
                                        <div class="col-sm-2 text-primary pointer">
                                            <button class="btn btn-primary btn-sm" id="clickToClone"><i class="fa fa-plus"></i> Tambahkan Item</button>
                                        </div>
                                        
                                        <br class="visible-xs">
                                        
                                        <div class="col-sm-2 form-group-sm">
                                            <input type="text" id="nilaibarcode" class="form-control" placeholder="Barcode" autofocus>
                                            <script type="text/javascript">
                                            $(document).ready(function(){
                                            $("#nilaibarcode").keyup(function() { 
                                                // e.preventDefault();
                                                var barcode = $("#nilaibarcode").val();
                                                if(!barcode || barcode == ''){
                                                    $('#barcodeText').val('');
                                                }
                                                else{
                                                                                                    // $('#barcodeText').val(barcode);
                                                $.ajax({
                                                    type: "post",
                                                    url: appRoot+"items/lihatbarcode",
                                                    data:{barcode:barcode},
                                                    success: function(hasil){
                                                        if(hasil.status === 1){
                                                              $('#barcodeText').val(hasil.nilaicode);
                                                        }
                                                        else{
                                                            $('#barcodeText').val('error');
                                                        }
                                                    },
                                                    error: function(hasil){
                                                        $('#barcodeText').val('gagal');
                                                    }
                                                });
                                                }
                                            }); 
                                            }); 

                                            </script>
                                            <input type="text" id="barcodeText" class="form-control" placeholder="Code Barang" style="display: none">
                                            <span class="help-block errMsg" id="itemCodeNotFoundMsg"></span>
                                        </div>
                                    </div>
                                    <!-- End of text to click to add another item to transaction-->
                                    <br>
                                    
                                    <div class="row">
                                        <!--
                                        <div class="col-sm-3 form-group-sm">
                                            <label for="vat">VAT(%)</label>
                                            <input type="number" min="0" id="vat" class="form-control" value="0">
                                        </div>
                                        
                                        <div class="col-sm-3 form-group-sm">
                                            <label for="discount">Discount(%)</label>
                                            <input type="number" min="0" id="discount" class="form-control" value="0">
                                        </div>
                                        
                                        <div class="col-sm-3 form-group-sm">
                                            <label for="discount">Discount(value)</label>
                                            <input type="number" min="0" id="discountValue" class="form-control" value="0">
                                        </div>
                                    -->
                                    </div>
                                        
                                        <!--
                                    <div class="row">
                                        <div class="col-sm-4 form-group-sm">
                                            <label for="cumAmount">Cumulative Amount</label>
                                            <span id="cumAmount" class="form-control">0.00</span>
                                        </div>
                                        
                                        <div class="col-sm-4 form-group-sm">
                                            <div class="cashAndPos hidden">
                                                <label for="cashAmount">Cash</label>
                                                <input type="text" class="form-control" id="cashAmount">
                                                <span class="help-block errMsg"></span>
                                            </div>

                                            <div class="cashAndPos hidden">
                                                <label for="posAmount">POS</label>
                                                <input type="text" class="form-control" id="posAmount">
                                                <span class="help-block errMsg"></span>
                                            </div>

                                            <div id="amountTenderedDiv">
                                                <label for="amountTendered" id="amountTenderedLabel">Amount Tendered</label>
                                                <input type="text" class="form-control" id="amountTendered">
                                                <span class="help-block errMsg" id="amountTenderedErr"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4 form-group-sm">
                                            <label for="changeDue">Change Due</label>
                                            <span class="form-control" id="changeDue"></span>
                                        </div>
                                    </div>
                                        
                                    <div class="row">
                                        <div class="col-sm-4 form-group-sm">
                                            <label for="custName">Customer Name</label>
                                            <input type="text" id="custName" class="form-control" placeholder="Name">
                                        </div>
                                        
                                        <div class="col-sm-4 form-group-sm">
                                            <label for="custPhone">Customer Phone</label>
                                            <input type="tel" id="custPhone" class="form-control" placeholder="Phone Number">
                                        </div>
                                        
                                        <div class="col-sm-4 form-group-sm">
                                            <label for="custEmail">Customer Email</label>
                                            <input type="email" id="custEmail" class="form-control" placeholder="E-mail Address">
                                        </div>
                                    </div>
                                -->
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <!--
                                <div class="col-sm-2 form-group-sm">
                                    <button class="btn btn-primary btn-sm" id='useScanner'>Use Barcode Scanner</button>
                                </div>
                            -->
                                <br class="visible-xs">
                                <div class="col-sm-6"></div>
                                <br class="visible-xs">
                                <div class="col-sm-4 form-group-sm">
                                    <button type="button" class="btn btn-primary btn-sm" id="transitconfirmSaleOrder">Konfirmasi</button>
                                    <button type="button" class="btn btn-danger btn-sm" id="cancelSaleOrder">Reset</button>
                                </div>
                            </div>
                        </form><!-- end of form-->
                    </div>
                </div>
                <!-- end of div to display transaction form-->
            </div>
            <!--end of form-->


            <!--- AKHIR BARANG TRANSIT ---------->







    
            <br><br>
            <!-- sort and co row-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="transListPerPage">Per Halaman</label>
                        <select id="transListPerPage" class="form-control">
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
                    </div>

                    <div class="col-sm-3 form-group-sm form-inline">
                        <label for="transListSortBy">Urutkan</label>
                        <select id="transListSortBy" class="form-control">
                            <option value="transId-DESC">Tanggal(Teratas)</option>
                            <option value="transId-ASC">Tanggal(Terbawah)</option>
                            <option value="quantity-DESC">Jumlah(Teratas)</option>
                            <option value="quantity-ASC">Jumlah(Terbawah)</option>
                            <option value="totalPrice-DESC">Harga(Tertinggi)</option>
                            <option value="totalPrice-ASC">Harga(Terendah)</option>
                            <option value="totalMoneySpent-DESC">Pengeluaran(Tertinggi)</option>
                            <option value="totalMoneySpent-ASC">Pengeluaran(Terendah)</option>
                            <option value="cust_name-ASC">Pengguna (A-Z)</option>
                            <option value="cust_name-DESC">Pengguna (Z-A)</option>
                        </select>
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="transSearch">Cari Transaksi</label>
                        <label for='transSearch'><i class="fa fa-search"></i></label>
                        <input type="search" id="transSearch" class="form-control" placeholder="Cari Transaksi">
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="transSearchDate">Cari Tanggal</label>
                        <label for='transSearchDate'><i class="fa fa-search"></i></label>
                        <input type="search" id="transSearchDate" class="form-control" placeholder="Contoh: 2018-07-15">
                    </div>
                </div>
            </div>
            <!-- end of sort and co div-->
        </div>
    </div>
    
    <hr>
    
    <!-- transaction list table-->
    <div class="row">
        <!-- Transaction list div-->
        <div class="col-sm-12" id="transListTable"></div>
        <!-- End of transactions div-->
    </div>
    <!-- End of transactions list table-->
</div>


<div class="row hidden" id="divToClone">
    <div class="col-sm-3 form-group-sm">
        <label>Item</label>
        <select class="form-control selectedItemDefault" onchange="selectedItem(this)"></select>
    </div>

    <div class="col-sm-2 form-group-sm">
        <label>Jenis Barang</label>
        <select class="form-control" id="jenisbarang">
            <option value="definitif">definitif</option>
            <option value="transit">transit</option>
        </select>
    </div>
    <div class="col-sm-1 form-group-sm itemAvailQtyDiv" id="defenitif">
        <label>Definitif</label>
        <span class="form-control itemAvailQty">0</span>
    </div>
    <div class="col-sm-1 form-group-sm itemAvailQtyDiv" id="transit">
        <label>Transit</label>
        <span class="form-control itemAvailQty">0</span>
    </div>

    <div class="col-sm-2 form-group-sm">
        <label>Harga Perbarang</label>
        <span class="form-control itemUnitPrice">0.00</span>
    </div>

    <div class="col-sm-2 form-group-sm itemTransQtyDiv" id="jumlahpermintaan">
        <label>Jumlah Permintaan</label>
        <input type="number" min="0" class="form-control itemTransQty" value="0">
        <span class="help-block itemTransQtyErr errMsg"></span>
    </div>

    <div class="col-sm-2 form-group-sm" style="display: none">
        <label>Total Harga</label>
        <span class="form-control itemTotalPrice">0.00</span>
    </div>
    
    <br class="visible-xs">
    
    <div class="col-sm-1">
        <button class="close retrit">&times;</button>
    </div>
    
    <br class="visible-xs">
</div>


<div class="modal fade" id='reportModal' data-backdrop='static' role='dialog'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss='modal'>&times;</div>
                <h4 class="text-center">Buat Laporan</h4>
            </div>
            
            <div class="modal-body">
                <div class="row" id="datePair">

                    <div class="col-sm-4 form-group-sm">
                        <label class="control-label">Pilih Unit</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span><i class="fa fa-users"></i></span>
                            </div>
                            <select class="form-control checkField" id="pilihUnit">
                                <option value="">Semua Unit</option>
                                <?php if($semuaUnit){ ?>
                                <?php foreach ($semuaUnit as $key) { ?>
                                <option value="<?=$key->satuan; ?>"><?= $key->satuan;?></option>
                                <?php } } ?>
                            </select>
                        </div>
                        <span class="help-block errMsg" id='transToErr'></span>
                    </div>

                    <div class="col-sm-4 form-group-sm">
                        <label class="control-label">Dari Tanggal</label>                                    
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" id='transFrom' class="form-control date start" placeholder="YYYY-MM-DD">
                        </div>
                        <span class="help-block errMsg" id='transFromErr'></span>
                    </div>

                    <div class="col-sm-4 form-group-sm">
                        <label class="control-label">Hingga Tanggal</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" id='transTo' class="form-control date end" placeholder="YYYY-MM-DD">
                        </div>
                        <span class="help-block errMsg" id='transToErr'></span>
                    </div>

                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-success" id='clickToGen'>Generate</button>
                <button class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>

<!---End of copy of div to clone when adding more items to sales transaction---->
<script src="<?=base_url()?>public/js/transactions.js"></script>
<script src="<?=base_url('public/ext/datetimepicker/bootstrap-datepicker.min.js')?>"></script>
<script src="<?=base_url('public/ext/datetimepicker/jquery.timepicker.min.js')?>"></script>
<script src="<?=base_url()?>public/ext/datetimepicker/datepair.min.js"></script>
<script src="<?=base_url()?>public/ext/datetimepicker/jquery.datepair.min.js"></script>