'use strict';
//Onal

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm user's log in status
    
    //load all items once the page is ready
    lilt();    
    $("#createNewItemDiv").hide();
    $("#generateLaporan").hide();
    
    
    //WHEN USE BARCODE SCANNER IS CLICKED
    $("#useBarcodeScanner").click(function(e){
        e.preventDefault();
        
        $("#itemCode").focus();
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $("#createItem").click(function(){
        //-----yang dibutuhkan-----//
        $("#createNewItemDiv").show();
        $("#createNewItemDiv").attr('class', "col-sm-4");
        $("#itemsListDiv").attr('class', "col-sm-8");
        $("#itemsListDiv").show();
        
        $("#barCode").focus();
        //---- yang tidak dibutuhkan ---//
        $("#generateLaporan").hide();
        $("#pengadaanListDiv").hide();
        $("#laporanListDiv").hide();
        $("#unitListDiv").hide();

        lilt();

    });


    $("#laporanBarang").click(function(){
        //-----yang dibutuhkan-----//
        $("#itemsListDiv").show();
        $("#generateLaporan").show();
        $("#itemsListDiv").attr('class', "col-sm-12");

        //---- yang tidak dibutuhkan ---//
        $("#generateLaporan").hide();
        $("#createNewItemDiv").hide();
        $("#laporanListDiv").hide();
        $("#pengadaanListDiv").hide();
        $("#kategoriListDiv").hide();
        $("#unitListDiv").hide();
        lilt();
    });



    $("#generateBarang").click(function(){
        //-----yang dibutuhkan-----//
        $("#pengadaanListDiv").show();
        $("#generateLaporan").show();

        //---- yang tidak dibutuhkan ---//
        $("#createNewItemDiv").hide();
        $("#itemsListDiv").hide();
        $("#laporanListDiv").hide();
        $("#kategoriListDiv").hide();
        $("#unitListDiv").hide();

        pengadaanlilt();
    });

    $("#laporangenerateBarang").click(function(){
        $("#createNewItemDiv").hide();
        $("#pengadaanListDiv").hide();
        $("#itemsListDiv").hide();
        $("#laporanListDiv").show();
        $("#kategoriListDiv").hide();
        $("#unitListDiv").hide();
        laporanlilt();
    });

    $("#kategorigenerateBarang").click(function(){
        $("#createNewItemDiv").hide();
        $("#pengadaanListDiv").hide();
        $("#itemsListDiv").hide();
        $("#laporanListDiv").hide();
        $("#kategoriListDiv").show();
        $("#unitListDiv").hide();
        kategorililt();
    });


    $("#unitgenerateBarang").click(function(){
        $("#createNewItemDiv").hide();
        $("#pengadaanListDiv").hide();
        $("#itemsListDiv").hide();
        $("#laporanListDiv").hide();
        $("#kategoriListDiv").hide();
        $("#unitListDiv").show();
        unitlilt();
    });






    ///batas

    $("#generateLaporan").click(function(){
        var value = "onal";
        $.ajax({
            type: "POST",
            data: {v:value},
            url: appRoot+"items/dokumen",
            success: function(returnedData){
                alert('Berhasil Membuat Laporan');
                // $("#itemsListTable").html(returnedData.itemsListTable);
            }
        });
        // }
        
        // else{
        //     //reload the table if all text in search box has been cleared
        //     displayFlashMsg("Loading page...", spinnerClass, "", "");
        //     lilt();
        // }
    });

    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    $(".cancelAddItem").click(function(){
        //reset and hide the form
        document.getElementById("addNewItemForm").reset();//reset the form
        $("#createNewItemDiv").hide();//hide the form
        $("#itemsListDiv").attr('class', "col-sm-12");//make the table span the whole div
        $("#itemsListDiv").show();



    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //execute when 'auto-generate' checkbox is clicked while trying to add a new item
    $("#gen4me").click(function(){
        //if checked, generate a unique item code for user. Else, clear field
        if($("#gen4me").prop("checked")){
            var codeExist = false;
            
            do{
                //generate random string, reduce the length to 10 and convert to uppercase
                var rand = Math.random().toString(36).slice(2).substring(0, 10).toUpperCase();
                $("#itemCode").val(rand);//paste the code in input
                $("#itemCodeErr").text('');//remove the error message being displayed (if any)
                
                //check whether code exist for another item
                $.ajax({
                    type: 'get',
                    url: appRoot+"items/gettablecol/id/code/"+rand,
                    success: function(returnedData){
                        codeExist = returnedData.status;//returnedData.status could be either 1 or 0
                    }
                });
            }
            
            while(codeExist);
            
        }
        
        else{
            $("#itemCode").val("");
        }
    });
    

    $("#clickToGen").click(function(e){
        e.preventDefault();
        
        var tanggal = $("#tanggal").val();
        var jenis = $("#jenis").val();
        var nomor = $("#nomor").val();

        
        if(!tanggal || !jenis || !nomor){
            !tangggal ? $("#tanggalErr").text("Masukkan Tanggal") : "";
            !jenis ? $("#jenisErr").text("Masukkan Jenis Laporan") : "";
            !nomor ? $("#nomorErr").text("Nomor Surat Tidak Boleh Kosong") : "";
            return;
        }
        
        /*
         * remove any error msg, hide modal, launch window to display the report in
         */
         
         $("#transFromErr").html("");
         $("#reportModal").modal('hide');

         var strWindowFeatures = "width=1000,height=500,scrollbars=yes,resizable=yes";

         window.open(appRoot+"opname/report/"+tanggal+"/"+jenis+"/"+nomor, 'Print', strWindowFeatures);
     });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////
    //////////////
    $("#pengadaanListTable").on('click', '.suspendAdmin', function(){
        var ElemId = $(this).attr('id');
        
            var barangId = ElemId.split("-")[1];//buat nangkep id barang
            
            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");
            
            if(barangId){
                $.ajax({
                    url: appRoot+"items/suspend",
                    method: "POST",
                    data: {_aId:barangId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                        //change the icon to "on" if it's "off" before the change and vice-versa
                        var newIconClass = returnedData._ns === 1 ? "fa fa-toggle-on pointer" : "fa fa-toggle-off pointer";
                        
                        //change the icon
                        $("#sus-"+returnedData._aId).html("<i class='"+ newIconClass +"'></i>");
                        
                    }
                    
                    else{
                        console.log('err');
                    }
                });
            }
        });


    $("#pengadaanListTable").on('change', '.pengadaanBarang', function(){
        var ElemId = $(this).attr('id');
        
            var barangId = ElemId.split("-")[1];//buat nangkep id barang
            
            // //show spinner
            // $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");
            
            if(barangId){
                $.ajax({
                    url: appRoot+"items/pengadaanBarang",
                    method: "POST",
                    data: {_aId:barangId}
                }).done(function(returnedData){

                });
            }
        });



    $("#pengadaanListTable").on('change', '.pengadaanBarang', function(){
        var ElemId = $(this).attr('id');
            var barangId = ElemId.split("-")[1];//buat nangkep id barang
            var jumlah = $(this).val();

            // //show spinner
            // $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");
            
            if(barangId){
                $.ajax({
                    url: appRoot+"items/pengadaanBarang",
                    method: "POST",
                    data: {_aId:barangId, jumlah:jumlah}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                        $("#itemTambah-"+returnedData._aId).val(returnedData._jumlah);  
                    }

                    else{
                      console.log('err');
                  }
              });
            }
        });


    //handles the submission of adding new item
    $("#addNewItem").click(function(e){
        e.preventDefault();
        
        changeInnerHTML(['itemNameErr', 'itemQuantityErr', 'itemPriceErr', 'kategoriErr', 'itemCodeErr', 'addCustErrMsg'], "");
        
        // var itemName = $("#itemName").val();
        // var itemQuantity = $("#itemQuantity").val();
        // // var itemPrice = $("#itemPrice").val().replace(",", "");
        // var itemKategori = $("#itemKategori :selected").text();
        // var itemKondisi = $("#itemKondisi :selected").text();
        // // var itemSatuan = $("#itemSatuan :selected").text();
        var itemLokasi = $("input[name='lokasi']").val();

        //versi input terbaru (wajib)
        var itemName = $("#itemName").val();
        var itemKategori = $("#itemKategori :selected").text();
        var itemCode = $("#itemCode").val();
        var itemNup = $("#itemNup").val();
        var itemTahun = $("#itemTahun").val();
        var itemKondisi = $("#itemKondisi :selected").text();        

        //var kategori = $("#kategori").val();
        var barCode = $("#barCode").val();
        var itemUnit = $("#itemUnit :selected").text();
        // var itemCode = $("#itemCode").val();
        // var itemMinimal = $("input[name='itemMinimal']").val();  
        var itemDescription = $("#itemDescription").val();
        // var itemLokasi = $("#itemLokasi").val();
        var itemPIC = $("#itemPIC").val();
        
        if(!itemName || itemKategori == "" || !itemCode || !itemNup || itemKondisi == "" ){
            !itemName ? $("#itemNameErr").text("Mohon diisi") : "";
            // !itemKategori ? $("#itemKategoriErr").text("Mohon diisi x") : "";
            itemKategori == "" ? $("#itemKategoriErr").text("Mohon diisi") : "";
            !itemCode ? $("#itemCodeErr").text("Mohon diisi") : "";
            !itemNup ? $("#itemNupErr").text("Mohon diisi") : "";
            !itemKondisi ? $("#itemKondisiErr").text("Mohon diisi") : "";
            
            $("#addCustErrMsg").text("One or more required fields are empty");
            return;
        }
        
        displayFlashMsg("Adding Item '"+itemName+"'", "fa fa-spinner faa-spin animated", '', '');
        
        $.ajax({
            type: "post",
            url: appRoot+"items/add",
            data:{
                itemName:itemName, 
                itemKategori:itemKategori, 
                itemCode:itemCode, 
                itemNup:itemNup, 
                itemKondisi:itemKondisi, 
                itemUnit:itemUnit, 
                barCode:barCode, 
                itemDescription:itemDescription, 
                itemTahun:itemTahun,
                itemLokasi:itemLokasi,
                itemPIC:itemPIC
            },
            
            success: function(returnedData){
                // console.log(returnedData.status)
                if(returnedData.status === 1){
                    changeFlashMsgContent(returnedData.msg, "text-success", '', 1500);
                    document.getElementById("addNewItemForm").reset();
                    hideFlashMsg();
                    //refresh the items list table
                    lilt();
                    
                    //return focus to item code input to allow adding item with barcode scanner
                    $("#itemCode").focus();
                }
                
                else{
                    hideFlashMsg();
                    
                    //display all errors
                    $("#kategoriErr").text(returnedData.itemName);
                    $("#itemNameErr").text(returnedData.itemName);
                    $("#itemPriceErr").text(returnedData.itemPrice);
                    $("#itemCodeErr").text(returnedData.itemCode);
                    $("#barCodeErr").text(returnedData.itemCode);
                    $("#itemMinimalErr").text(returnedData.itemMinimal);
                    $("#itemQuantityErr").text(returnedData.itemQuantity);
                    $("#addCustErrMsg").text(returnedData.msg);
                }
            },

            error: function(){
                if(!navigator.onLine){
                    changeFlashMsgContent("You appear to be offline. Please reconnect to the internet and try again", "", "red", "");
                }

                else{
                    changeFlashMsgContent("Unable to process your request at this time. Pls try again later!", "", "red", "");
                }
            }
        });
    });
    
    /**
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : KATEGORI CRUD
     * TANGGAL PEMBUATAN              : 16 SEPTEMBER 2018
     * KETERANGAN                     : JS untuk Kategori
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     **/

     $("#addNewKategori").click(function(e){
        e.preventDefault();
        changeInnerHTML(['kategoriErr'], "");
        var kategoriName = $("#newkategori").val();

        if(!kategoriName){
            !kategoriName ? $("#newkategoriErr").text("Harap diisi") : "";
            $("#addKategoriErrMsg").text("Terdapat Kesalahan");
            return;
        }
        
        displayFlashMsg("Menambahkan kategori '"+kategoriName+"'", "fa fa-spinner faa-spin animated", '', '');

        $.ajax({
            type: "post",
            url: appRoot+"items/addkategori",
            data:{kategori:kategoriName},
            success: function(returnedData){
                if(returnedData.status === 1){
                    changeFlashMsgContent(returnedData.msg, "text-success", '', 1500);
                    document.getElementById("addNewKategoriForm").reset();
                    kategorililt();
                    $("#newkategori").focus();
                }
                
                else{
                    hideFlashMsg();
                    $("#newkategoriErr").text(returnedData.kategoriName);
                }
            },

            error: function(){
                if(!navigator.onLine){
                    changeFlashMsgContent("Terdapat Kesalahan", "", "red", "");
                }

                else{
                    changeFlashMsgContent("Silahkan Ulangi Lagi", "", "red", "");
                }
            }
        });
    });


     $("#kategoriListTable").on('click', '.delKategori', function(e){//apabila tombol delete ditekan dari tabel kategori
        e.preventDefault();
        var itemId = $(this).parents('tr').find('.curKategoriId').val();
        var itemRow = $(this).closest('tr');//to be used in removing the currently deleted row  
        if(itemId){
            $.ajax({
                url: appRoot+"items/deletekategori",
                method: "POST",
                data: {i:itemId}
            }).done(function(rd){
                if(rd.status === 1){
                    $(itemRow).remove();
                    changeFlashMsgContent('Berhasil Menghapus Kategori', '', 'green', 1000);
                }
                else{
                    changeFlashMsgContent('Gagal Menghapus Kategori', '', 'red', 1000);
                }
            }).fail(function(){
                console.log('Proses Gagal');
            });

        }
    });



   /**
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : UNIT CRUD
     * TANGGAL PEMBUATAN              : 18 OKT 2018
     * KETERANGAN                     : JS untuk Kategori
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     **/

     $("#addNewUnit").click(function(e){
        e.preventDefault();
        changeInnerHTML(['unitErr'], "");
        var unitName = $("#newunit").val();

        if(!unitName){
            !unitName ? $("#newkategoriErr").text("Harap diisi") : "";
            $("#addUnitErrMsg").text("Terdapat Kesalahan");
            return;
        }
        
        displayFlashMsg("Menambahkan kategori '"+unitName+"'", "fa fa-spinner faa-spin animated", '', '');

        $.ajax({
            type: "post",
            url: appRoot+"items/addunit",
            data:{unit:unitName},
            success: function(returnedData){
                if(returnedData.status === 1){
                    changeFlashMsgContent(returnedData.msg, "text-success", '', 1500);
                    document.getElementById("addNewUnitForm").reset();
                    unitlilt();
                    $("#newunit").focus();
                }
                
                else{
                    hideFlashMsg();
                    $("#newunitErr").text(returnedData.kategoriName);
                }
            },

            error: function(){
                if(!navigator.onLine){
                    changeFlashMsgContent("Terdapat Kesalahan", "", "red", "");
                }

                else{
                    changeFlashMsgContent("Silahkan Ulangi Lagi", "", "red", "");
                }
            }
        });
    });


     $("#unitListTable").on('click', '.delUnit', function(e){//apabila tombol delete ditekan dari tabel kategori
        e.preventDefault();
        var itemId = $(this).parents('tr').find('.curUnitId').val();
        var itemRow = $(this).closest('tr');//to be used in removing the currently deleted row  
        if(itemId){
            $.ajax({
                url: appRoot+"items/deleteunit",
                method: "POST",
                data: {i:itemId}
            }).done(function(rd){
                if(rd.status === 1){
                    $(itemRow).remove();
                    changeFlashMsgContent('Berhasil Menghapus Unit', '', 'green', 1000);
                }
                else{
                    changeFlashMsgContent('Gagal Menghapus Unit', '', 'red', 1000);
                }
            }).fail(function(){
                console.log('Proses Gagal');
            });

        }
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload items list table when events occur
    $("#itemsListPerPage, #itemsListSortBy").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        lilt();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    $("#itemSearch").keyup(function(){

        var value = $(this).val();
        
        if(value){
            $.ajax({
                url: appRoot+"search/itemsearch",
                type: "get",
                data: {v:value},
                success: function(returnedData){
                //---- yang tidak dibutuhkan ---//
                $("#generateLaporan").hide();
                $("#createNewItemDiv").hide();
                $("#laporanListDiv").hide();
                $("#pengadaanListDiv").hide();

                //-----yang dibutuhkan-----//
                $("#itemsListDiv").show();
                $("#generateLaporan").show();
                $("#itemsListDiv").attr('class', "col-sm-12");
                $("#itemsListTable").show();

                $("#itemsListTable").html(returnedData.itemsListTable);
            }
        });
        }
        
        else{
            //reload the table if all text in search box has been cleared
            displayFlashMsg("Loading page...", spinnerClass, "", "");
            lilt();
        }
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    

    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //trigers the modal to update stock
    $("#itemsListTable").on('click', '.updateStock', function(){
        //get item info and fill the form with them
        var itemId = $(this).attr('id').split("-")[1];
        var itemName = $("#itemName-"+itemId).html();
        var itemCurQuantity = $("#itemQuantity-"+itemId).html();
        var itemCurTransit = $("#itemTransit-"+itemId).html();
        var itemCode = $("#itemCode-"+itemId).html();
        
        $("#stockUpdateItemId").val(itemId);
        $("#stockUpdateItemName").val(itemName);
        $("#stockUpdateItemCode").val(itemCode);
        $("#stockUpdateItemQInStock").val(itemCurQuantity);
        $("#stockUpdateItemTInStock").val(itemCurTransit);
        
        $("#updateStockModal").modal('show');
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //runs when the update type is changed while trying to update stock
    //sets a default description if update type is "newStock"
    $("#pilihanUpdateType").on('change', function(){
        var updateType = $("#pilihanUpdateType").val();
        
        if(updateType && (updateType === 'definitif')){
            $("#stockUpdateDescription").val("Penambahan Barang Baru Definitif");
        }
        
        else{
            $("#stockUpdateDescription").val("Penambahan Barang Baru Transit");
        }
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //handles the updating of item's quantity in stock
    $("#stockUpdateSubmit").click(function(){
        var updateType = $("#stockUpdateType").val();
        var pilihanType = $("#pilihanUpdateType :selected").text();
        var stockUpdateQuantity = $("#stockUpdateQuantity").val();
        var stockUpdateDescription = $("#stockUpdateDescription").val();
        var itemId = $("#stockUpdateItemId").val();

        if(!updateType || !stockUpdateQuantity || !stockUpdateDescription || !itemId ||!pilihanType){
        !updateType ? $("#stockUpdateTypeErr").html("harap untuk diisi") : "";
            !pilihanType ? $("#pilihanUpdateTypeErr").html("harap untuk diisi") : "";
            !stockUpdateQuantity ? $("#stockUpdateQuantityErr").html("harap untuk diisi") : "";
            !pilihanType ? $("#stockUpdateDescriptionErr").html("Mohon untuk memilih jenis barang !") : "";
            !itemId ? $("#stockUpdateItemIdErr").html("harap untuk diisi") : "";
            
            return;
        }
        
        $("#stockUpdateFMsg").html("<i class='"+spinnerClass+"'></i> Updating Stock.....");
        
        $.ajax({
            method: "POST",
            url: appRoot+"items/updatestock",
            data: {_iId:itemId, _upType:updateType, qty:stockUpdateQuantity, desc:stockUpdateDescription, jenis:pilihanType}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                $("#stockUpdateFMsg").html(returnedData.msg);
                //refresh items' list
                lilt();
                
                //reset form
                document.getElementById("updateStockForm").reset();
                
                //dismiss modal after some secs
                setTimeout(function(){
                    $("#updateStockModal").modal('hide');//hide modal
                    $("#stockUpdateFMsg").html("");//remove msg
                }, 1000);
            }
            
            else{
                $("#stockUpdateFMsg").html(returnedData.msg);
                
                $("#stockUpdateTypeErr").html(returnedData._upType);
                $("#stockUpdateQuantityErr").html(returnedData.qty);
                $("#stockUpdateDescriptionErr").html(returnedData.desc);
            }
        }).fail(function(){
            $("#stockUpdateFMsg").html("Unable to process your request at this time. Please check your internet connection and try again");
        });
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //PREVENT AUTO-SUBMISSION BY THE BARCODE SCANNER
    $("#barCode").keypress(function(e){
        if(e.which === 13){
            e.preventDefault();
            //change to next input by triggering the tab keyboard
            $("#itemCode").focus();
        }
    });
    
    
    
    //TO DELETE AN ITEM (The item will be marked as "deleted" instead of removing it totally from the db)
    $("#itemsListTable").on('click', '.delItem', function(e){
        e.preventDefault();
        
        //get the item id
        var itemId = $(this).parents('tr').find('.curItemId').val();
        var itemRow = $(this).closest('tr');//to be used in removing the currently deleted row
        
        if(itemId){
            var confirm = window.confirm("Are you sure you want to delete item? This cannot be undone.");
            
            if(confirm){
                displayFlashMsg('Please wait...', spinnerClass, 'black');
                
                $.ajax({
                    url: appRoot+"items/delete",
                    method: "POST",
                    data: {i:itemId}
                }).done(function(rd){
                    if(rd.status === 1){
                        //remove item from list, update items' SN, display success msg
                        $(itemRow).remove();

                        //update the SN
                        resetItemSN();

                        //display success message
                        changeFlashMsgContent('Item deleted', '', 'green', 1000);
                    }

                    else{

                    }
                }).fail(function(){
                    console.log('Req Failed');
                });
            }
        }
    });


    //untuk menghapus arsip pada dokumen pengadaaan
    $("#laporanListTable").on('click', '.delItem', function(e){
        e.preventDefault();
        
        //get the item id
        var itemId = $(this).parents('tr').find('.curItemId').val();

        var alamatDokumen = $(this).parents('tr').find('.alamatDokumen').val();

        var itemRow = $(this).closest('tr');//to be used in removing the currently deleted row
        
        if(itemId){
                displayFlashMsg('Mohon Tunggu...', spinnerClass, 'black');
                
                $.ajax({
                    url: appRoot+"items/deletelaporan",
                    method: "POST",
                    data: {i:itemId, alamatDokumen:alamatDokumen}
                }).done(function(rd){
                    if(rd.status === 1){
                        //remove item from list, update items' SN, display success msg
                        $(itemRow).remove();

                        //update the SN
                        resetItemSN();

                        //display success message
                        changeFlashMsgContent('Berhasil Menghapus Laporan', '', 'green', 1000);
                    }

                    else{

                    }
                }).fail(function(){
                    console.log('Req Failed');
                });
            
        }
    });



    /**
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : UPDATE/EDIT BARANG
     * TANGGAL PEMBUATAN              : 16 SEMPTEMBER 2018
     * KETERANGAN                     : 
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     **/

     //triggers when an item's "edit" icon is clicked
    $("#itemsListTable").on('click', ".editItem", function(e){
        e.preventDefault();
        //get item info
        var itemId = $(this).attr('id').split("-")[1];
        var itemName = $("#itemName-"+itemId).html();
        var itemCode = $("#itemCode-"+itemId).html();
        var barCode = $("#barCode-"+itemId).html();
        var itemSatuan = $("#itemSatuan-"+itemId).html();
        var itemLokasi = $("#itemLokasi-"+itemId).html();
        var itemPrice = $("#itemPrice-"+itemId).html().split(".")[0].replace(",", "");
        var itemKategori = $("#itemKategori-"+itemId).html();
        var itemQuantity = $("#itemQuantity-"+itemId).html();
        var itemMinimal = $("#itemMinimal-"+itemId).html();
        var aktivasi = $("#aktivasi-"+itemId).html();
        
        var itemDesc = $("#itemDesc-"+itemId).attr('title');

        //new item
        var itemPIC = $("#itemPIC-"+itemId).html();
        var itemNUP = $("#itemNUP-"+itemId).html();
        var itemTahun = $("#itemTahun-"+itemId).html();
        var itemKondisi = $("#itemKondisi-"+itemId).html();
        var itemUnit = $("#itemUnit-"+itemId).html();

        
        //prefill form with info
        $("#itemIdEdit").val(itemId);
        $("#itemNameEdit").val(itemName);
        $("#itemCodeEdit").val(itemCode);
        $("#itemPriceEdit").val(itemPrice);
        $("#itemKategoriEdit").val(itemKategori);
        $("#itemQuantityEdit").val(itemQuantity);
        $("#itemMinimalEdit").val(itemMinimal);
        $("#barCodeEdit").val(barCode);
        $("#itemSatuanEdit").val(itemSatuan);
        $("#itemLokasiEdit").val(itemLokasi);
        $("#itemDescriptionEdit").val(itemDesc);


        $("#itemPICEdit").val(itemPIC);
        $("#itemNUPEdit").val(itemNUP);
        $("#itemTahunEdit").val(itemTahun);
        $("#itemKondisiEdit").val(itemKondisi);
        $("#itemUnitEdit").val(itemUnit);
        
        //remove all error messages that might exist
        $("#editItemFMsg").html("");
        $("#itemNameEditErr").html("");
        $("#itemCodeEditErr").html("");
        $("#itemPriceEditErr").html("");
        
        
        //launch modal
        $("#editItemModal").modal('show');
        if(aktivasi == '1'){
            $("#pindahItemSubmit").hide();
        }
        else{
            $("#pindahItemSubmit").show();
        }
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $("#editItemSubmit").click(function(){
        var itemId = $("#itemIdEdit").val();
        var itemName = $("#itemNameEdit").val();
        var itemCode = $("#itemCodeEdit").val();
        var itemSatuan = $("#itemSatuanEdit").val();
        var itemLokasi = $("#itemLokasiEdit").val();
        var barCode = $("#barCodeEdit").val();
        var itemPrice = $("#itemPriceEdit").val();
        var itemDesc = $("#itemDescriptionEdit").val();


        var itemKategori = $("#itemKategoriEdit").val();
        var itemQuantity = $("#itemQuantityEdit").val();
        var itemMinimal = $("#itemMinimalEdit").val();
        
        
        if(!itemName || !itemPrice || !itemId || !itemPrice || !itemKategori || !itemQuantity || !itemMinimal){
            !itemName ? $("#itemNameEditErr").html("Nama Tidak Boleh Kosong") : "";
            !itemPrice ? $("#itemPriceEditErr").html("Harga tidak boleh kosong") : "";
            !itemKategori ? $("#itemKateogriEditErr").html("kategori tidak boleh kosong") : "";
            !itemQuantity ? $("#itemQuantityEditErr").html("Jumlah tidak boleh kosong") : "";
            !itemMinimal ? $("#itemMinimalEditErr").html("Stok Aman tidak boleh kosong") : "";



            !itemId ? $("#editItemFMsg").html("Item Tidak Diketahui...") : "";
            return;
        }
        
        $("#editItemFMsg").css('color', 'black').html("<i class='"+spinnerClass+"'></i> Processing your request....");
        
        $.ajax({
            method: "POST",
            url: appRoot+"items/edit",
            data: {itemName:itemName, itemPrice:itemPrice, itemDesc:itemDesc, _iId:itemId, itemCode:itemCode, itemKategori:itemKategori, itemQuantity:itemQuantity, itemMinimal:itemMinimal, barCode:barCode, itemSatuan:itemSatuan,itemLokasi:itemLokasi}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                $("#editItemFMsg").css('color', 'green').html("Item successfully updated");
                
                setTimeout(function(){
                    $("#editItemModal").modal('hide');
                }, 1000);
                
                lilt();
            }
            
            else{
                $("#editItemFMsg").css('color', 'red').html("One or more required fields are empty or not properly filled");
                
                $("#itemNameEditErr").html(returnedData.itemName);
                $("#itemCodeEditErr").html(returnedData.itemCode);
                $("#itemPriceEditErr").html(returnedData.itemPrice);
            }
        }).fail(function(){
            $("#editItemFMsg").css('color', 'red').html("Unable to process your request at this time. Please check your internet connection and try again");
        });
    });
    

     $("#pindahItemSubmit").click(function(){
        var itemId = $("#itemIdEdit").val();
        var itemName = $("#itemNameEdit").val();
        var itemCode = $("#itemCodeEdit").val();
        var itemSatuan = $("#itemSatuanEdit :selected").text();
        var itemLokasi = $("#itemLokasiEdit").val();
        var barCode = $("#barCodeEdit").val();
        var itemPrice = $("#itemPriceEdit").val();
        var itemDesc = $("#itemDescriptionEdit").val();
        var itemKategori = $("#itemKategoriEdit :selected").text();
        var itemQuantity = $("#itemQuantityEdit").val();
        var itemMinimal = $("#itemMinimalEdit").val();
        
        
        if(!itemName || !itemPrice || !itemId || !itemPrice || !itemKategori || itemKategori == null || !itemQuantity || !itemMinimal || !barCode || !itemSatuan || itemPrice === '0' || itemMinimal === '0' ){
            !itemName ? $("#itemNameEditErr").html("Nama Tidak Boleh Kosong") : "";
            !itemSatuan ? $("#itemSatuanEditErr").html("Satuan Tidak Boleh Kosong") : "";
            !barCode ? $("#barCodeEditErr").html("Barcode Boleh Kosong") : "";
            !itemPrice ? $("#itemPriceEditErr").html("Harga tidak boleh kosong") : "";
            itemPrice === '0'  ? $("#itemPriceEditErr").html("Harga tidak boleh nol") : "";
            !itemKategori ? $("#itemKategoriEditErr").html("kategori tidak boleh kosong") : "";
            !itemQuantity ? $("#itemQuantityEditErr").html("Jumlah tidak boleh kosong") : "";
            itemMinimal === '0'  ? $("#itemMinimalEditErr").html("Stok Aman tidak boleh nol") : "";
            !itemMinimal ? $("#itemMinimalEditErr").html("Stok Aman tidak boleh kosong") : "";
            !itemId ? $("#editItemFMsg").html("Item Tidak Diketahui...") : "";
            return;
        }

        $("#editItemFMsg").css('color', 'black').html("<i class='"+spinnerClass+"'></i> Processing your request....");
        
        $.ajax({
            method: "POST",
            url: appRoot+"items/pindahitem",
            data: {itemName:itemName, itemPrice:itemPrice, itemDesc:itemDesc, _iId:itemId, itemCode:itemCode, itemKategori:itemKategori, itemQuantity:itemQuantity, itemMinimal:itemMinimal, barCode:barCode, itemSatuan:itemSatuan,itemLokasi:itemLokasi}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                $("#editItemFMsg").css('color', 'green').html("Item successfully updated");
                
                setTimeout(function(){
                    $("#editItemModal").modal('hide');
                }, 1000);
                
                lilt();
            }
            
            else{
                $("#editItemFMsg").css('color', 'red').html("One or more required fields are empty or not properly filled");
                
                $("#itemNameEditErr").html(returnedData.itemName);
                $("#itemCodeEditErr").html(returnedData.itemCode);
                $("#itemPriceEditErr").html(returnedData.itemPrice);
            }
        }).fail(function(){
            $("#editItemFMsg").css('color', 'red').html("Unable to process your request at this time. Please check your internet connection and try again");
        });
    });



     /**
      ////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////
      * KOMPLEKS CODINGAN UNTUK FUNGSI : PINDAH ITEM DARI TRANSIT
      * TANGGAL PEMBUATAN              : 16 SEPTEMBER 2018
      * KETERANGAN                     : -
      ////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////
      **/

          //trigers the modal to update stock
    $("#itemsListTable").on('click', '.pindahTransit', function(){
        var itemId = $(this).attr('id').split("-")[1];
        var itemName = $("#itemName-"+itemId).html();
        var itemCurQuantity = $("#itemQuantity-"+itemId).html();
        var itemCurTransit = $("#itemTransit-"+itemId).html();
        var itemCode = $("#itemCode-"+itemId).html();
        var total = parseInt(itemCurTransit) + parseInt(itemCurQuantity);
        
        $("#transitUpdateItemId").val(itemId);
        $("#transitUpdateItemName").val(itemName);
        $("#transitUpdateItemCode").val(itemCode);
        $("#updateBarangDefinitif").val(itemCurQuantity);
        $("#updateBarangTransit").val(itemCurTransit);
        $("#totalUpdate").val(total);

        $("#pindahTransitModal").modal('show');
    });


     $("#pindahkanUpdateSubmit").click(function(){
        var itemId = $("#transitUpdateItemId").val();
        var itemTransit = $("#updateBarangTransit").val();
        var itemCode = $("#transitUpdateItemCode").val();
        var itemTotal = $("#totalUpdate").val();

        $("#editItemFMsg").css('color', 'black').html("<i class='"+spinnerClass+"'></i> Processing your request....");
        $.ajax({
            method: "POST",
            url: appRoot+"items/datatransitdefisit",
            data: {_iId:itemId, itemQuantity:itemTotal, itemCode:itemCode}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                //alert('berhasil');
                $("#editItemFMsg").css('color', 'green').html("Item successfully updated");
                
                setTimeout(function(){
                    $("#pindahTransitModal").modal('hide');
                }, 1000);
                
                lilt();
            }
            
            else{
                $("#editItemFMsg").css('color', 'red').html("Silahkan Ulangi Proses !");
            }
        }).fail(function(){
            $("#editItemFMsg").css('color', 'red').html("Gagal Menambahkan Data");
        });
    });





    //END

        $("#editItemSubmit").click(function(){
        var itemId = $("#itemIdEdit").val();
        var itemName = $("#itemNameEdit").val();
        var itemCode = $("#itemCodeEdit").val();
        var itemSatuan = $("#itemSatuanEdit").val();
        var itemLokasi = $("#itemLokasiEdit").val();
        var barCode = $("#barCodeEdit").val();
        var itemPrice = $("#itemPriceEdit").val();
        var itemDesc = $("#itemDescriptionEdit").val();


        var itemKategori = $("#itemKategoriEdit").val();
        var itemQuantity = $("#itemQuantityEdit").val();
        var itemMinimal = $("#itemMinimalEdit").val();
        
        
        if(!itemName || !itemPrice || !itemId || !itemPrice || !itemKategori || !itemQuantity || !itemMinimal){
            !itemName ? $("#itemNameEditErr").html("Nama Tidak Boleh Kosong") : "";
            !itemPrice ? $("#itemPriceEditErr").html("Harga tidak boleh kosong") : "";
            !itemKategori ? $("#itemKateogriEditErr").html("kategori tidak boleh kosong") : "";
            !itemQuantity ? $("#itemQuantityEditErr").html("Jumlah tidak boleh kosong") : "";
            !itemMinimal ? $("#itemMinimalEditErr").html("Stok Aman tidak boleh kosong") : "";



            !itemId ? $("#editItemFMsg").html("Item Tidak Diketahui...") : "";
            return;
        }
        
        $("#editItemFMsg").css('color', 'black').html("<i class='"+spinnerClass+"'></i> Processing your request....");
        
        $.ajax({
            method: "POST",
            url: appRoot+"items/edit",
            data: {itemName:itemName, itemPrice:itemPrice, itemDesc:itemDesc, _iId:itemId, itemCode:itemCode, itemKategori:itemKategori, itemQuantity:itemQuantity, itemMinimal:itemMinimal, barCode:barCode, itemSatuan:itemSatuan,itemLokasi:itemLokasi}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                $("#editItemFMsg").css('color', 'green').html("Item successfully updated");
                
                setTimeout(function(){
                    $("#editItemModal").modal('hide');
                }, 1000);
                
                lilt();
            }
            
            else{
                $("#editItemFMsg").css('color', 'red').html("One or more required fields are empty or not properly filled");
                
                $("#itemNameEditErr").html(returnedData.itemName);
                $("#itemCodeEditErr").html(returnedData.itemCode);
                $("#itemPriceEditErr").html(returnedData.itemPrice);
            }
        }).fail(function(){
            $("#editItemFMsg").css('color', 'red').html("Unable to process your request at this time. Please check your internet connection and try again");
        });
    });



        /**
         ////////////////////////////////////////////////////////////////////////
         ////////////////////////////////////////////////////////////////////////
         * KOMPLEKS CODINGAN UNTUK FUNGSI : CETAK KARTU
         * TANGGAL PEMBUATAN              : 17 SEPTEMBER 2018
         * KETERANGAN                     : 
         ////////////////////////////////////////////////////////////////////////
         ////////////////////////////////////////////////////////////////////////
         **/
    
        $("#itemsListTable").on('click', '.cetakKartu', function(){
            var itemId = $(this).attr('id').split("-")[1];
            var itemCode = $("#itemCode-"+itemId).html();
            var URL = appRoot+"kartu/dokumen/"+itemCode;
            window.open(URL, '_blank');
        });

});



/**
 * "lilt" = "load Items List Table"
 * @param {type} url
 * @returns {undefined}
 */
 function lilt(url){
    var orderBy = $("#itemsListSortBy").val().split("-")[0];
    var orderFormat = $("#itemsListSortBy").val().split("-")[1];
    var limit = $("#itemsListPerPage").val();
    
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"items/lilt/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
        
        success: function(returnedData){
            hideFlashMsg();
            $("#itemsListTable").html(returnedData.itemsListTable);
        },
        
        error: function(){

        }
    });
    
    return false;
}



function pengadaanlilt(url){
    var orderBy = $("#itemsListSortBy").val().split("-")[0];
    var orderFormat = $("#itemsListSortBy").val().split("-")[1];
    var limit = $("#itemsListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"items/pengadaanlilt/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
        
        success: function(returnedData){
            hideFlashMsg();
            $("#pengadaanListTable").html(returnedData.itemsListTable);
        },
        
        error: function(){

        }
    });
    
    return false;
}

function barangpengadaan(url){
    var orderBy = $("#itemsListSortBy").val().split("-")[0];
    var orderFormat = $("#itemsListSortBy").val().split("-")[1];
    var limit = $("#itemsListPerPage").val();
    
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"items/lilt/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
        
        success: function(returnedData){
            hideFlashMsg();
            $("#itemsListTable").html(returnedData.itemsListTable);
        },
        
        error: function(){

        }
    });
    
    return false;
}


/**
 * "vittrhist" = "View item's transaction history"
 * @param {type} itemId
 * @returns {Boolean}
 */
 function vittrhist(itemId){
    if(itemId){

    }
    
    return false;
}



function resetItemSN(){
    $(".itemSN").each(function(i){
        $(this).html(parseInt(i)+1);
    });
}


function laporanlilt(url){
    var orderBy = $("#itemsListSortBy").val().split("-")[0];
    var orderFormat = $("#itemsListSortBy").val().split("-")[1];
    var limit = $("#itemsListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"items/laporanlilt/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
        
        success: function(returnedData){
            hideFlashMsg();
            $("#laporanListTable").html(returnedData.itemsListTable);
        },
        
        error: function(){

        }
    });
    
    return false;
}

function kategorililt(url){
    var orderBy = $("#itemsListSortBy").val().split("-")[0];
    var orderFormat = $("#itemsListSortBy").val().split("-")[1];
    var limit = $("#itemsListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"items/kategorililt/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
        
        success: function(returnedData){
            hideFlashMsg();
            $("#kategoriListTable").html(returnedData.itemsListTable);
        },
        
        error: function(){

        }
    });
    
    return false;
}


function unitlilt(url){
    var orderBy = $("#itemsListSortBy").val().split("-")[0];
    var orderFormat = $("#itemsListSortBy").val().split("-")[1];
    var limit = $("#itemsListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"items/unitlilt/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
        
        success: function(returnedData){
            hideFlashMsg();
            $("#unitListTable").html(returnedData.itemsListTable);
        },
        
        error: function(){

        }
    });
    
    return false;
}