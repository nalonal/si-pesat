'use strict';

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
    
    /**
     * Toggle the form to add a new item
     */

     /**
        jenis-jenis elemen
        $("#createNewItemDiv").show();
        $("#itemsListDiv").show();
        $("#pengadaanListDiv").hide();
        $("#laporanListDiv").hide();

     */


    $("#createItem").click(function(){
        //-----yang dibutuhkan-----//
        $("#createNewItemDiv").show();
        $("#createNewItemDiv").attr('class', "col-sm-4");
        $("#itemsListDiv").attr('class', "col-sm-8");
        $("#itemsListDiv").show();
        
        $("#barCode").focus();
        lilt();

    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    $(".cancelAddItem").click(function(){
        //reset and hide the form
        document.getElementById("addNewItemForm").reset();//reset the form
        $("#createNewItemDiv").addClass('hidden');//hide the form
        $("#itemsListDiv").attr('class', "col-sm-12");//make the table span the whole div
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
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //handles the submission of adding new item
    $("#addNewItem").click(function(e){
        e.preventDefault();
        
        changeInnerHTML(['itemNameErr', 'itemQuantityErr', 'itemPriceErr', 'kategoriErr', 'itemCodeErr', 'addCustErrMsg'], "");
        
        var itemName = $("#itemName").val();
        var itemQuantity = $("#itemQuantity").val();
        var itemPrice = $("#itemPrice").val().replace(",", "");
        var itemKategori = $("input[name='kategori']").val();

        var itemSatuan = $("#satuan :selected").text();
        var itemLokasi = $("input[name='lokasi']").val();


        //var kategori = $("#kategori").val();
        var barCode = $("#barCode").val();
        var itemCode = $("#itemCode").val();
        var itemMinimal = $("input[name='itemMinimal']").val();
        var itemDescription = $("#itemDescription").val();
        
        
        if(!itemName || !itemCode || !itemSatuan || !itemQuantity || itemQuantity === '0'){
            !itemName ? $("#itemNameErr").text("Harap Untuk Diisi") : "";
            !itemCode ? $("#itemCodeErr").text("Harap Untuk Diisi") : "";
            !itemSatuan ? $("#itemSatuanErr").text("Harap Untuk Diisi") : "";
            itemQuantity === '0'  ? $("#itemQuantityErr").text("Harga tidak boleh nol") : "";
            !itemQuantity ? $("#itemQuantityErr").text("Harap Untuk Diisi") : "";
            $("#addCustErrMsg").text("Silahkan Perhatikan Kembali Pengisian");
            return;
        }
        
        displayFlashMsg("Adding Item '"+itemName+"'", "fa fa-spinner faa-spin animated", '', '');
        
        $.ajax({
            type: "post",
            url: appRoot+"transit/add",
            data:{itemName:itemName, itemQuantity:itemQuantity, itemKategori:itemKategori, itemPrice:itemPrice, itemDescription:itemDescription, itemCode:itemCode, barCode:barCode, itemMinimal:itemMinimal, itemSatuan:itemSatuan, itemLokasi:itemLokasi},
            
            success: function(returnedData){
                if(returnedData.status === 1){
                    changeFlashMsgContent(returnedData.msg, "text-success", '', 1500);
                    document.getElementById("addNewItemForm").reset();
                    
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
        var itemDesc = $("#itemDesc-"+itemId).attr('title');

        
        
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
        
        //remove all error messages that might exist
        $("#editItemFMsg").html("");
        $("#itemNameEditErr").html("");
        $("#itemCodeEditErr").html("");
        $("#itemPriceEditErr").html("");
        
        //launch modal
        $("#editItemModal").modal('show');
    });



    //end










    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $("#editItemSubmit").click(function(){
        var itemId = $("#itemIdEdit").val();
        var itemName = $("#itemNameEdit").val();
        var itemCode = $("#itemCodeEdit").val();
        var itemSatuan = $("#itemSatuanEdit :selected").text();
        var itemLokasi = $("#itemLokasiEdit").val();
        var barCode = $("#barCodeEdit").val();
        var itemPrice = $("#itemPriceEdit").val();
        var itemDesc = $("#itemDescriptionEdit").val();


        var itemKategori = $("#itemKategoriEdit").val();
        var itemQuantity = $("#itemQuantityEdit").val();
        var itemMinimal = $("#itemMinimalEdit").val();
        
        
        if(!itemName){
            !itemName ? $("#itemNameEditErr").html("Nama Tidak Boleh Kosong") : "";
            return;
        }
        
        $("#editItemFMsg").css('color', 'black').html("<i class='"+spinnerClass+"'></i> Processing your request....");
        
        $.ajax({
            method: "POST",
            url: appRoot+"transit/edit",
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
            }
        }).fail(function(){
            $("#editItemFMsg").css('color', 'red').html("Unable to process your request at this time. Please check your internet connection and try again");
        });
    });
    


    /////////////////////////////////////////////////////////////////////////
    //INI BUAT PINDAH ITEM SUBMIT ///


    //||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||//
    $("#pindahItemSubmit").click(function(){
        var itemId = $("#itemIdEdit").val();
        var itemName = $("#itemNameEdit").val();
        var itemCode = $("#itemCodeEdit").val();
        var itemSatuan = $("#itemSatuanEdit :selected").text();
        var itemLokasi = $("#itemLokasiEdit").val();
        var barCode = $("#barCodeEdit").val();
        var itemPrice = $("#itemPriceEdit").val();
        var itemDesc = $("#itemDescriptionEdit").val();


        var itemKategori = $("#itemKategoriEdit").val();
        var itemQuantity = $("#itemQuantityEdit").val();
        var itemMinimal = $("#itemMinimalEdit").val();
        
        
        if(!itemName || !itemPrice || !itemId || !itemPrice || !itemKategori || !itemQuantity || !itemMinimal || !barCode || !itemSatuan || itemPrice === '0' || itemMinimal === '0' ){
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
            url: appRoot+"transit/pindahitem",
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



    /////////////////////////////////////////////////////////////////////////
    
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
        var itemCode = $("#itemCode-"+itemId).html();
        
        $("#stockUpdateItemId").val(itemId);
        $("#stockUpdateItemName").val(itemName);
        $("#stockUpdateItemCode").val(itemCode);
        $("#stockUpdateItemQInStock").val(itemCurQuantity);
        
        $("#updateStockModal").modal('show');
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //runs when the update type is changed while trying to update stock
    //sets a default description if update type is "newStock"
    $("#stockUpdateType").on('change', function(){
        var updateType = $("#stockUpdateType").val();
        
        if(updateType && (updateType === 'newStock')){
            $("#stockUpdateDescription").val("Penambahan Barang Baru");
        }
        
        else{
            $("#stockUpdateDescription").val("Terjadi Pengurangan Barang");
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
        var stockUpdateQuantity = $("#stockUpdateQuantity").val();
        var stockUpdateDescription = $("#stockUpdateDescription").val();
        var itemId = $("#stockUpdateItemId").val();
        
        if(!updateType || !stockUpdateQuantity || !stockUpdateDescription || !itemId){
            !updateType ? $("#stockUpdateTypeErr").html("required") : "";
            !stockUpdateQuantity ? $("#stockUpdateQuantityErr").html("required") : "";
            !stockUpdateDescription ? $("#stockUpdateDescriptionErr").html("required") : "";
            !itemId ? $("#stockUpdateItemIdErr").html("required") : "";
            
            return;
        }
        
        $("#stockUpdateFMsg").html("<i class='"+spinnerClass+"'></i> Updating Stock.....");
        
        $.ajax({
            method: "POST",
            url: appRoot+"transit/updatestock",
            data: {_iId:itemId, _upType:updateType, qty:stockUpdateQuantity, desc:stockUpdateDescription}
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
                    url: appRoot+"transit/delete",
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
        url: url ? url : appRoot+"transit/lilt/",
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