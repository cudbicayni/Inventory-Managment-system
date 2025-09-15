<script>
  id=null;
  var  btn_save=document.getElementById("btn_save")
  var  btn_update=document.getElementById("btn_update")
  var  add=document.getElementById("mdl_items")


// form
  add.addEventListener('click',()=>{
    btn_save.style.display = "block";
    btn_update.style.display = "none";
    const form = document.getElementById("imageUploadForm"); 
    form.reset();
  })
// form


 // search
  var  inp=document.getElementsByClassName("inp")
  var  ctr=document.getElementsByClassName("ctr")

  

  $(document).on("click", ".b1", function() {
    let index = $(".b1").index(this); 
    btn_update.style.display = "block";
    btn_save.style.display = "none";
    sql = "select * from items where item_no='" + inp[index].value + "'";
    id = inp[index].value;
    // alert(sql);

    $.ajax({
      url: "data/search.php",
      data: { qry: sql },
      success: function(res) {
       arr = res.split(","); 
       id = arr[0];
       for ( i = 1; i < arr.length; i++) 
        ctr[i].value = arr[i];

      $(".b1").eq(index).css("display", "block").modal("show");
    }
  });

  });

// end search



// update data
 $("#btn_update").click(()=>operations("update"))
 // end update data

 //save data
 let allFilled = true;
 $("#btn_save").click(()=>{
   var fm=document.getElementById("fm")
   for(let a=0; a<fm.elements.length; a+=1){
    if(fm.elements[a].value==""){
         alert('fadlan formka buuxi');
          allFilled = false;
         break;

    }
       if(allFilled)
           operations("insert")

      
    }
 })
 //end save data

  operations=(oper)=>{

      $.ajax({
    url: "opera.php",
    data: $("#fm").serialize() + "&oper=" + oper + "&id=" + id,

    success: function(res) {
            // alert(res)
      $("#dv").load("tables.php"); 
      $("#exampleModal").modal('hide');
      const form = document.getElementById("fm"); 
      form.reset(); 

      Swal.fire({
        icon: 'success',      
        title: 'Success!',  
        text: res, 
        confirmButtonText: 'OK',  
        timer: 1700,        
        timerProgressBar: true 
      });

    }
  });
  }
 




 // delete
$(document).on("click", ".b2", function() {
    let index = $(".b2").index(this); 
    btn_update.style.display = "block";
    btn_save.style.display = "none";
    sql = "select * from customers where cus_no='" + inp[index].value + "'";
    id = inp[index].value;
    
    $.ajax({
        url: "search.php",
        data: { qry: sql },
        success: function(res) {
            arr = res.split(","); 
            id = arr[0];
            for (let i = 1; i < arr.length; i++) {
                ctr[i].value = arr[i];
            }
        }
    });

   
    Swal.fire({
        title: 'Ma hubtaa?',  
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'haaa',
        cancelButtonText: 'maya'
    }).then((result) => {
        if (result.isConfirmed) {
           
            operations("delete");
            Swal.fire(
                'Deleted!',
                'Delete successful.',
                'success'
            );
        } else if (result.dismiss === Swal.DismissReason.cancel) {
           
            Swal.fire({
                title: 'Cancelled',
                text: 'lama delete gareyn :)',
                icon: 'info',
                timer: 1500,  
                showConfirmButton: false,
                timerProgressBar: true 
                
            });
        }
    });
});



  // end delete
 
 
</script>
