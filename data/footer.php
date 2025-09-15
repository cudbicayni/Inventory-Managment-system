<!-- General JS Scripts -->
  <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <script src="assets/bundles/apexcharts/apexcharts.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="assets/js/page/index.js"></script>
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>





  <!-- JS Libraies -->
  <script src="assets/bundles/datatables/datatables.min.js"></script>
  <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/bundles/jquery-ui/jquery-ui.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="assets/js/page/datatables.js"></script>
  <!-- Template JS File -->
<script src="https://common.olemiss.edu/_js/sweet-alert/sweet-alert.min.js"></script>
<script src="assets/js/demos.js"></script>
<script src="assets/js/material-dashboard.min.js"></script>
<!-- new -->
<!-- jQuery -->

<!-- Bootstrap 4 JS -->


<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      // Optional configs:
      // paging: true,
      // searching: true,
      // ordering: true,
      // info: true
    });
  });
</script>

<!-- end new -->

<!-- SweetAlert2 JavaScript -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script>
        // $('#summernote').summernote({
        //  placeholder: '',
        //  tabsize: 2,
        //  height: 200
        // });
        $(document).ready(function() {
          $.fn.dataTableExt.sErrMode = 'throw';
              //$('.selects').select2();
            $('.tables').DataTable();
            // Javascript method's body can be found in assets/js/demos.js
            md.initDashboardPageCharts();
            md.initVectorMap();
      });
function SYD_ALL(modalName,qry,groupBy){
 
    var tbl_name=modalName.replace("mdl_","");
    $(document).delegate(".btn_edit_"+tbl_name,"click",function(e){
        $("#"+tbl_name+"_alert").hide();
        var up_id=$(this).val();
        $("#btn_save_"+tbl_name).hide();
        $("#btn_update_"+tbl_name).show();
        var qry1=qry+up_id+" "+groupBy;
        $.post("config/SYD_search.php","qry="+qry1.replace(/\s/g,"^"),function(data){
          var array=data.split(",");
          var a=0;
            $('#frm_'+tbl_name).find(':input').each(function() {
                if(this.id!="" && this.type!="button" && this.type!="submit"){
                    $("#"+this.id).val(array[a]);
                    $('.selects').trigger('change');
                    a=a+1;
                }
            });
          
        });
        $("#"+modalName).modal('show');
        return false;
    });
    $(document).delegate(".btn_remove_"+tbl_name,"click",function(e){ 
        $("#"+tbl_name+"_alert").hide();
        var up_id=$(this).val();
        $("#btn_save_"+tbl_name).hide();
        $("#btn_update_"+tbl_name).show();
        var qry1=qry+up_id+" "+groupBy;
        $.post("config/SYD_search.php","qry="+qry1.replace(/\s/g,"^"),function(data){
          var array=data.split(",");
          var a=0;
            $('#frm_'+tbl_name).find(':input').each(function() {
                if(this.id!="" && this.type!="button" && this.type!="submit"){
                    $("#"+this.id).val(array[a]);
                    $('.selects').trigger('change');
                    a=a+1;
                }
            });
          
        });
        $('#yes_d_btn').attr('class',"btn btn-danger btn-sm btn-circle btn_delete_"+tbl_name);
        $("#mdl_delete_all").modal('show');
        return false;
    });
    $(document).delegate(".btn_new_"+tbl_name,"click",function(e){ 
        // alert()
        $("#"+tbl_name+"_alert").hide();
        $("#btn_save_"+tbl_name).show();
        $("#btn_update_"+tbl_name).hide();
        $("#frm_"+tbl_name).trigger("reset");
        $('.selects').trigger('change');
    });
$(document).delegate("#btn_save_" + tbl_name, "click", function(e) {
    e.preventDefault();

    $.post("config/all.php", $("#frm_" + tbl_name).serialize() + "&user=insert", function(data) {

        data = data.trim(); // Remove extra spaces/newlines

        setTimeout(function() {
            if (data === 'fill the blank inputs') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please fill in all required fields!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#btn_save_" + tbl_name).attr("disabled", false);
                });
            } else if (data.includes('already exists')) { // <- safer
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'This entry already exists!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#btn_save_" + tbl_name).attr("disabled", false);
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: data,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }

        }, 500);

        // Reload table
        $("#tbl_id_" + tbl_name).load(" .tbl_cls_" + tbl_name, function() {
            $('#dt_' + tbl_name).DataTable({
                aaSorting: [[0, 'desc']]
            });
        });

        $("#" + modalName).modal('hide');

    });

    return false;
});


   
    $(document).delegate("#btn_no_"+tbl_name,"click",function(e){
        $.post("config/all.php",$("#frm_"+tbl_name).serialize(),function(data){
            swal("", data);
            $("#tbl_id_"+tbl_name).load(" .tbl_cls_"+tbl_name,function(){
               $('#dt_'+tbl_name).DataTable({         aaSorting: [[0, 'desc']]     });
            });
        });
        return false;
    });
    $(document).delegate("#btn_update_"+tbl_name,"click",function(e){
        // alert();
        $.post("config/all.php",$("#frm_"+tbl_name).serialize()+"&user=update",function(data){
            alert(data);
            //alert("Waa la update gareeyey", data);
            $("#tbl_id_"+tbl_name).load(" .tbl_cls_"+tbl_name,function(){
               $('#dt_'+tbl_name).DataTable({         aaSorting: [[0, 'desc']]     });
            });
        });
        $("#"+modalName).modal('hide');
        return false;
    });
    $(document).delegate(".btn_delete_"+tbl_name,"click",function(e){
        
        $.post("config/all.php",$("#frm_"+tbl_name).serialize()+"&user=delete",function(data){
        $("#mdl_delete_all").modal('hide');
        alert(data);
        //swal("", data);

            $("#tbl_id_"+tbl_name).load(" .tbl_cls_"+tbl_name,function(){
               $('#dt_'+tbl_name).DataTable({         aaSorting: [[0, 'desc']]     });
            });
        });
        return false;
    });}
function SYD_Combo_Dia(qry,comName){
        var httpxml;
        try{
            // Firefox, Opera 8.0+, Safari
            httpxml=new XMLHttpRequest();
        }catch (e){
        // Internet Explorer
            try{
                httpxml=new ActiveXObject("Msxml2.XMLHTTP");
            }catch (e){
                try{
                    httpxml=new ActiveXObject("Microsoft.XMLHTTP");
                }catch (e){
                    alert("Your browser does not support AJAX!");
                    return false;
                }
            }
        }
        function stateck(){
            var combo = document.getElementById(comName);
            if(httpxml.readyState==4){
                var myarray = JSON.parse(httpxml.responseText);
          //    alert(httpxml.responseText);
              for(j=combo.options.length;j>0;j--){
                combo.remove(j);
              }
              for (i=0;i<myarray.data.length;i++){
                var optn = document.createElement("OPTION");
                optn.value = myarray.data[i][0].replace(/_/g, ' ');
                optn.text = myarray.data[i][1].replace(/_/g, ' ');
                combo.options.add(optn);
              } 
            }
        } // end of function stateck
        var url="config/dynamicCombo.php";
        url=url+"?qry="+qry;
        url=url+"&sid="+Math.random();
        httpxml.onreadystatechange=stateck;
        httpxml.open("GET",url,true);
        httpxml.send(null); 
    }
function SYD_Combo_load(btnclose,classcom,idcomb,combox,modal){
    var clas= classcom.split("|");
    var id= idcomb.split("|");
    var combo= combox.split("|");
    var mdl= modal.split("|");
    var i,x;
    $('#'+btnclose).on('hidden.bs.modal', function () {
        // alert();
        for(i=0; i<combo.length; i++){
         $("."+clas[i]).load(" #"+id[i],function(){
            for (x=0; x<=i-1; x++){
                if(mdl[x]==""){
                    $("#"+combo[x]).select2();
                }else{
                    $("#"+combo[x]).select2({
                        dropdownParent:$("#"+mdl[x])
                    });
                }    
            }
         });   
        }
    });}
function SYD_TableLoad(btnclose,classcom,idcomb,tbls){
    var clas= classcom.split("|");
    var id= idcomb.split("|");
    var tbl= tbls.split("|");
    var i,x;
    $("#"+btnclose).click(function(){
        for(i=0; i<tbl.length; i++){
         $("."+clas[i]).load(" #"+id[i],function(){
            for (x=0; x<=i-1; x++){
                $("#"+tbl[x]).DataTable();   
            }
         });   
        }
    });}
        function printData(){
   var divToPrint=document.getElementById("rpt_show_PRINT");
   newWin= window.open("");
   newWin.document.write("<style> .table th { padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: blue;color: white; } </style>"+divToPrint.outerHTML);
   newWin.print();
   newWin.close();

}

setTimeout(()=>{
$('#btn_prt_dt_rpt').click(function(){
printData()})
},500)
    
</script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
  feather.replace();
</script>

