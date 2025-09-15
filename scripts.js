// Initialize DataTable
        $(document).ready(function () {
            $('#example').DataTable({
                paging: true, // Enable pagination
                searching: true, // Enable search
                lengthChange: true, // Enable "Show Entries" dropdown
                pageLength: 5 // Default number of entries to show
            });
        });

        // function printModal() {
        //     // Get the modal content
        //     const modalContent = document.getElementById('modalContent').innerHTML;

        //     // Create a new window for printing
        //     const printWindow = window.open('', '_blank', 'width=800,height=600');
        //     printWindow.document.open();
        //     printWindow.document.write(`
        //         <!DOCTYPE html>
        //         <html lang="en">
        //         <head>
        //             <meta charset="UTF-8">
        //             <meta name="viewport" content="width=device-width, initial-scale=1.0">
        //             <title>Print Modal Content</title>
        //             <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        //         </head>
        //         <body>
        //             <div class="container mt-3">
        //                 ${modalContent}
        //             </div>
        //         </body>
        //         </html>
        //     `);
        //     printWindow.document.close();
        //     printWindow.print();
        // }

        // function printDocument() {
        //     window.print();
        // }


        //search
        var inp = document.getElementsByClassName("inp");
        var  ctr=document.getElementsByClassName("ctr")
        $(document).on("click", ".b1", function() {
            let index = $(".b1").index(this); 
            let sosNo = inp[index].value;
            // sql = "select * from registration where sos_no='" + inp[index].value + "'";
          // id = inp[index].value;
          // alert(sql);
          $.ajax({
            url: "search.php",
            data: { qry: sql },
            success: function(res) {
             arr = res.split(","); 
             id = arr[0];
             for ( i = 1; i < arr.length; i++) 
              ctr[i].innerHTML = arr[i];
      // alert(arr.length);
           
          }
        });
        });
// end search