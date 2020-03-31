src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"> // get ajax library

$(document).ready(function() { // when document is ready

    ///=======================================================================
    /// load gifts from user
    ///=======================================================================
    $.ajax ({
      url: 'server.php', // url yo
      type: 'POST', // type type
      dataType:'json',
      data: {
          load : "yes"
      },
      success: function(response){ // if success
        alert("test");
        // $.each(response, function(idx, obj) {
        //   alert(obj.NAME);
        // });
        //$("tbody").append("<tr><td>" + JQname + "</td><td>" + JQboughtby + "</td> <td><button class= 'dlt'>Verwijder Cadeau</button><td>" + JQgiftid + "</td></tr>"); // create new html oject for gift 
      },
      error: function() { // if not
        alert("Gifts didn't load, please try to reload the page"); // gib this message 
      }
    })


    ///========================================================================
    /// delete things
    ///========================================================================
    $("#tabel").on("click", ".dlt", function () { // if gift delete is pressed
      confirm("Weet je zeker dat je dit cadeau wilt verwijderen?") // ask confirmation
      
      // var copy = $(this).parent().parent();
      // alert($("#tabel").find('id'));

      var currentRow=$(this).closest("tr");
      var id = currentRow.find("td:eq(3)").text();
      
      
      $(this).parent().parent().remove(); // remove the object

      $.ajax ({
          url: 'server.php', // url yo
          type: 'POST', // type type
          data: {
              id: id
          },
          success: function(){ // if success
            alert("Gift Removed"); // alert gift removed
          },
          error: function() { // if not
            alert("Gift NOT removed, please try again"); // gib this message 
          }
        })
    });
  
    ///========================================================================
    /// add things
    ///========================================================================
    $("body").on("click", ".btnadd", function () { // if add button has been pressed
      var Cadeau = $("#name").val(); // name
      
      $.ajax ({ // ajax stuff yo
        url: 'server.php', // url
        type: 'POST', // type type
        data: {
          NAME: Cadeau // sending name
        },
        success: function(response){ // if success
          alert("Gift added"); // alert gift added

          $("tbody").append("<tr><td>" + Cadeau + "</td><td>. . .</td> <td><button class= 'dlt'>Verwijder Cadeau</button><td>" + response + "</td></tr>"); // create new html oject for gift
        },
        error: function() { // if not
          alert("Gift NOT added, please try again"); // gib this message 
      }  
      })
      
      $("#name").val(""); 
    });
    
    $('tbody').sortable(); // make table sortable
});

function navigate(location){
    window.location.href= location;
    console.log(location);
}