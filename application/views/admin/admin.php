<?php
defined('BASEPATH') OR exit('');
?>

<div class="row hidden-print">
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new admin, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-user-plus pointer" style="color:#337ab7" data-target='#addNewAdminModal' data-toggle='modal'>
                        Admin Baru
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="adminListPerPage">Tampilkan</label>
                        <select id="adminListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="adminListPerPage">per halaman</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="adminListSortBy" class="control-label">Urutkan</label> 
                        <select id="adminListSortBy" class="form-control">
                            <option value="first_name-ASC" selected>Nama (A - Z)</option>
                            <option value="first_name-DESC">Nama (Z - A)</option>
                            <option value="created_on-ASC">Tanggal Pembuatan (terbaru)</option>
                            <option value="created_on-DESC">Tanggal Pembuatan (Terlama)</option>
                            <option value="email-ASC">E-mail - A-Z</option>
                            <option value="email-DESC">E-mail - Z-A</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="adminSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="adminSearch" placeholder="Cari...." class="form-control">
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- Admin list -->
            <div class="row">
                <div class="col-sm-12" id="allAdmin"></div>
            </div>
            <!-- Admin list ends -->
        </div>
    </div>
</div>


<!--- Modal to add new admin --->
<div class='modal fade' id='addNewAdminModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Tambahkan Admin Baru</h4>
                <div class="text-center">
                    <i id="fMsgIcon"></i><span id="fMsg"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewAdminForm' name='addNewAdminForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='firstName' class="control-label">Nama Depan</label>
                            <input type="text" id='firstName' class="form-control checkField" placeholder="Nama Depan">
                            <span class="help-block errMsg" id="firstNameErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='lastName' class="control-label">Nama Belakang</label>
                            <input type="text" id='lastName' class="form-control checkField" placeholder="Nama Belakang">
                            <span class="help-block errMsg" id="lastNameErr"></span>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='email' class="control-label">Email</label>
                            <input type="email" id='email' class="form-control checkField" placeholder="Email">
                            <span class="help-block errMsg" id="emailErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='role' class="control-label">Role</label>
                            <select class="form-control checkField" id='role'>
                                <option value='Super'>Super</option>
                            </select>
                            <span class="help-block errMsg" id="roleErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='mobile1' class="control-label">Nomor Telepon</label>
                            <input type="tel" id='mobile1' class="form-control checkField" placeholder="Nomor Telepon">
                            <span class="help-block errMsg" id="mobile1Err"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='mobile2' class="control-label">Nomor Telepon (Lain)</label>
                            <input type="tel" id='mobile2' class="form-control" placeholder="Nomor Telepon Lain">
                            <span class="help-block errMsg" id="mobile2Err"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for="passwordOrig" class="control-label">Password:</label>
                            <input type="password" class="form-control checkField" id="passwordOrig" placeholder="Password">
                            <span class="help-block errMsg" id="passwordOrigErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for="passwordDup" class="control-label">Ulangi Password:</label>
                            <input type="password" class="form-control checkField" id="passwordDup" placeholder="Ulangi Password">
                            <span class="help-block errMsg" id="passwordDupErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewAdminForm" class="btn btn-warning pull-left">Reset</button>
                <button type='button' id='addAdminSubmit' class="btn btn-primary">Tambahkan Admin</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to add new admin --->


<!--- Modal for editing admin details --->
<div class='modal fade' id='editAdminModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Edit Informasi Admin</h4>
                <div class="text-center">
                    <i id="fMsgEditIcon"></i>
                    <span id="fMsgEdit"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editAdminForm' name='editAdminForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='firstNameEdit' class="control-label">Nama Depan</label>
                            <input type="text" id='firstNameEdit' class="form-control checkField" placeholder="First Name">
                            <span class="help-block errMsg" id="firstNameEditErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='lastNameEdit' class="control-label">Nama Belakang</label>
                            <input type="text" id='lastNameEdit' class="form-control checkField" placeholder="Last Name">
                            <span class="help-block errMsg" id="lastNameEditErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='emailEdit' class="control-label">Email</label>
                            <input type="email" id='emailEdit' class="form-control checkField" placeholder="Email">
                            <span class="help-block errMsg" id="emailEditErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='roleEdit' class="control-label">Role</label>
                            <select class="form-control checkField" id='roleEdit'>
                                <option value=''>Role</option>
                                <option value='Super'>Super</option>
                                <option value='Basic'>Basic</option>
                            </select>
                            <span class="help-block errMsg" id="roleEditErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='mobile1Edit' class="control-label">No Telepon</label>
                            <input type="tel" id='mobile1Edit' class="form-control checkField" placeholder="Nomor Telepon">
                            <span class="help-block errMsg" id="mobile1EditErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='mobile2Edit' class="control-label">No Telepon (Lain)</label>
                            <input type="tel" id='mobile2Edit' class="form-control" placeholder="Nomor Telepon Lain">
                            <span class="help-block errMsg" id="mobile2EditErr"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" id="adminId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editAdminForm" class="btn btn-warning pull-left">Reset</button>
                <button type='button' id='editAdminSubmit' class="btn btn-primary">Update Data</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit admin details --->
<script src="<?=base_url()?>public/js/admin.js"></script>