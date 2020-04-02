src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"> // get ajax library

$(document).ready(function() { // when document is ready




  ///=======================================================================
  /// load gifts from user for logged in folks
  ///=======================================================================
  if(window.location.href.includes("main.php")) {
    $.ajax ({
      url: 'server.php', // url yo
      type: 'POST', // type type
      dataType:'json',
      data: {
          load : "yes"
      },
      success: function(response){ // if success
        var arranged = new Object();
        $.each(response, function(idx, obj) {
          if(obj.BOUGHTBY != null) {
           $("tbody").append("<tr id="+obj.GIFTID+"><td>" + obj.NAME + "</td><td>" + obj.BOUGHTBY + "</td> <td><button class= 'dlt'>Verwijder Cadeau</button><td>" + obj.GIFTID + "</td></tr>"); // create new html oject for gift 
          }else {
            $("tbody").append("<tr id="+obj.GIFTID+"><td>" + obj.NAME + "</td><td>. . .</td> <td><button class= 'dlt'>Verwijder Cadeau</button><td>" + obj.GIFTID + "</td></tr>"); // create new html oject for gift 

          }
        });
        
      },
      error: function() { // if not
        alert("Gifts didn't load, please try to reload the page"); // gib this message 
      }
    })
  }

  ///=======================================================================
  /// load gifts from user for guesty boys
  ///=======================================================================
  if(window.location.href.includes("mainGuest.php")) {
    $.ajax ({
      url: 'server.php', // url yo
      type: 'POST', // type type
      dataType:'json',
      data: {
          load : "yes"
      },
      success: function(response){ // if success
        $.each(response, function(idx, obj) {
          if(obj.BOUGHTBY != null) {
            $("tbody").append("<tr id="+obj.GIFTID+"><td>" + obj.NAME + "</td><td>" + obj.BOUGHTBY + "</td><td>" + obj.GIFTID + "</td></tr>"); // create new html oject for gift            
          }else {
            $("tbody").append("<tr id="+obj.GIFTID+"><td>" + obj.NAME + "</td><td><button class= 'koop'>Koop Cadeau</button></td><td>" + obj.GIFTID + "</td></tr>"); // create new html oject for gift 
          }
        });
      },
      error: function() { // if not
        alert("Gifts didn't load, please try to reload the page"); // gib this message 
      }
    })
  }

   ///========================================================================
  /// buy things
  ///========================================================================
  $("#tabel").on("click", ".koop", function () { // if gift delete is pressed
    if(confirm("Weet je zeker dat je dit cadeau wilt kopen?")) { // ask confirmation

      var currentRow=$(this).closest("tr");
      var id = currentRow.find("td:eq(2)").text();
      
      $(this).parent().parent().remove(); // remove the object

      $.ajax ({
          url: 'serverGuest.php', // url yo
          type: 'POST', // type type
          dataType:'json',
          data: {
              id: id
          },
          success: function(response){ // if success
            $("tbody").empty();
            $.each(response, function(idx, obj) {
              if(obj.BOUGHTBY != null) {
                $("tbody").append("<tr id="+obj.GIFTID+"><td>" + obj.NAME + "</td><td>" + obj.BOUGHTBY + "</td><td>" + obj.GIFTID + "</td></tr>"); // create new html oject for gift            
              }else {
                $("tbody").append("<tr id="+obj.GIFTID+"><td>" + obj.NAME + "</td><td><button class= 'koop'>Koop Cadeau</button></td><td>" + obj.GIFTID + "</td></tr>"); // create new html oject for gift 
              } 
            });
          },
          error: function() { // if not
            alert("Er ging iets fout. probeer het opnieuw"); // gib this message 
          }
        })
    }
  });

  ///========================================================================
  /// delete things
  ///========================================================================
  $("#tabel").on("click", ".dlt", function () { // if gift delete is pressed
    if(confirm("Weet je zeker dat je dit cadeau wilt verwijderen?")) { // ask confirmation

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
    }
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

        $("tbody").append("<tr id="+ response +"><td>" + Cadeau + "</td><td>. . .</td> <td><button class= 'dlt'>Verwijder Cadeau</button><td>" + response + "</td></tr>"); // create new html oject for gift
      },
      error: function() { // if not
        alert("Gift NOT added, please try again"); // gib this message 
    }  
    })
    
    $("#name").val(""); 
  });
  
  // send safesequence to post to php to save sequence every time you move a piece
  if(window.location.href.includes("main.php")) {
    $('tbody').sortable({
      stop: function() {
        var obj = new Array();
        $('table > tbody  > tr').each(function(index, tr) { 
          var indexX = index + 1;
          obj.push(indexX , $(tr).attr('id'));
        });

        $.ajax({
          url: 'server.php',
          type: 'POST',
          dataType: 'json',
          data: JSON.stringify({
            'SAFESEQUENCE': obj
          }),
          succes: function() {
            console.log('sequence safed');
          },
          error: function() {
            console.log('saving sequence failed')
          }
        })
      }
    }); // make table sortable
  }
});

function navigate(location){
    window.location.href= location;
    console.log(location);
}