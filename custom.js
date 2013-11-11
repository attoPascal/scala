$(document).ready(function() {

  //Initialisierung
  initTextarea();
  initChat();
  initTabs();
  initNav();
  initButtons();
  initBirthday();
  
  //Ajax-Links
  var timeout = false;
  $("a.ajax").live("click", function(e) {
    e.preventDefault();

    var page = $(this).attr("href").split("page=")[1];

    $("#content").load("index.php?page=" + page + " #content > div", function() {
      timeout = true;
      setTimeout(function() {
        timeout = false;
      }, 500);
      
      window.location.hash = page;
      initTabs();
      initNav();
      initChat();
      initButtons();
      initTextarea();
    });
  });
  
  //Status
  $("input#submit_status").die().live("click", function(e) {
    e.preventDefault();
    
    var status = $("textarea#status").val();
    var recipient = $("input#recipient").val();
    
    $("textarea#status").val("Sag es!").removeClass("expand");
    $("form div").hide();
    
    $.post("ajax/poststatus.php", {text: status, rec_id: recipient}, function(data) {
      $("div.feed").prepend(data);
      $("div.new").addClass("highlight").slideDown("slow");
      setTimeout(function() {
        $("div.new").removeClass("new").removeClass("highlight", "slow");
      }, 3000);
    });
  });
  
  //Signup-Formular
  $("input#nick").keyup(function() {
    var nick = $(this).val();
    if (nick.length !== 0) {
      $("#checknick").html("<img src='img/circleball-red-white.gif' alt='loading' />");
      setTimeout(function() {
        $("#checknick").load("ajax/checknick.php?nick=" + nick);
      }, 1000);
    } else {
      $("#checknick").html("");
    }
  });     
  $("input#pass1").keyup(function() {
    var pass1 = $(this).val();
    if (pass1.length > 4) {
      $("#checkpass1").css("color", "green").text("Passwort ok!");
    } else {
      $("#checkpass1").css("color", "red").text("Passwort zu kurz");
    }
  });
  $("input#pass2").keyup(function() {
    var pass1 = $("input#pass1").val();
    var pass2 = $(this).val();
    if (pass1 == pass2) {
      $("#checkpass2").css("color", "green").text("Passwörter stimmen überein!");
    } else {
      $("#checkpass2").css("color", "red").text("Passwörter stimmen nicht überein");
    }
  });
  
  //Hash-Navigation
  $(window).bind("hashchange", function() {
    if (!timeout) {
      var hash = window.location.hash;
      var page;
      if (hash) {
        page = hash.slice(1);
      } else {
        page = "home";
      }
      $("#content").load("index.php?page=" + page + " #content > div", function() {
        initTextarea();
        initChat();
        initTabs();
        initNav();
        initButtons();
      });
    }
  });
  
  //Beitraege nachladen
  $("div.more a").live("click", function(e) {
    e.preventDefault();
    
    var status = $("div.feed div.status:last").attr("id").split("status")[1];
    var profile = $("div.feed").attr("id").split("feed")[1];
    
    $.get("ajax/morefeed.php", {sid: status, pid: profile}, function(data) {
      $("div.more").remove();
      $("div.feed").append(data);
      initButtons();
    });
  });
  
  //Freundschaftsanfragen
  //Anfrage senden
  $("a.addfriend").die().live("click", function(e) {
    e.preventDefault();
    
    if ($(this).button("option", "disabled") !== true) {
      $(this).addClass("clicked");
      
      var href = $(this).attr("href");
      var friend = href.split("fid=")[1];
      var dialog = "<div class=\"dialog\">M&ouml;chtest du wirklich eine Freundschaftsanfrage senden?</div>";
      
      $(dialog).dialog({
        title: "Freundschaftsanfrage",
        modal: true,
        resizable: false,
        width: 335,
        buttons: {
          "Anfrage senden": function() {
            $(this).dialog("close");
            $.post("ajax/sendrequest.php", {fid: friend}, function() {});
            $("a.clicked").removeClass("clicked").addClass("sent").button({
                label: "Versendet!",
                disabled: true
            });
          },
          "Abbrechen": function() {
            $(this).dialog("close");
            $("a.clicked").removeClass("clicked");
          }
        }
      })
    }
  });
  //Anfrage akzeptieren
  $("a.accept").die().live("click", function(e) {
    e.preventDefault();
    $(this).addClass("clicked");
    
    var href = $(this).attr("href");
    var rid = href.split("req_id=")[1];
    
    $.post("ajax/acceptrequest.php", {req_id: rid}, function(data) {
      $(data).dialog({
        title: "Freundschaftsanfrage",
        modal: true,
        resizable: false,
        width: 335,
        buttons: {
          "OK": function() {
            $(this).dialog("close");
            $("a.clicked").parents("div.friend").removeClass("friend").slideUp("slow");
            $("a.clicked").removeClass("clicked");
            
            if ($("#inbox div.friend").length == 0) {
              $("#inbox").append("<p>Du hast zur Zeit keine unbeantworteten Anfragen im Eingang</p>");
            }
          }
        }
      })
    });
  });
  //Anfrage ablehnen
  $("a.decline").die().live("click", function(e) {
    e.preventDefault();
    $(this).addClass("clicked");
    
    var href = $(this).attr("href");
    var rid = href.split("req_id=")[1];
    
    $.post("ajax/declinerequest.php", {req_id: rid}, function(data) {
      $(data).dialog({
        title: "Freundschaftsanfrage",
        modal: true,
        resizable: false,
        width: 335,
        buttons: {
          "OK": function() {
            $(this).dialog("close");
            $("a.clicked").parents("div.friend").removeClass("friend").slideUp("slow");
            $("a.clicked").removeClass("clicked");
            
            if ($("#inbox div.friend").length == 0) {
              $("#inbox").append("<p>Du hast zur Zeit keine unbeantworteten Anfragen im Eingang</p>");
            }
          }
        }
      })
    });
  });
  //Anfrage zurückziehen
  $("a.retract").die().live("click", function(e) {
    e.preventDefault();
    $(this).addClass("clicked");
    
    var href = $(this).attr("href");
    var rid = href.split("req_id=")[1];
    
    $.post("ajax/retractrequest.php", {req_id: rid}, function(data) {
      $(data).dialog({
        title: "Freundschaftsanfrage",
        modal: true,
        resizable: false,
        width: 335,
        buttons: {
          "OK": function() {
            $(this).dialog("close");
            $("a.clicked").parents("div.friend").removeClass("friend").slideUp("slow");
            $("a.clicked").removeClass("clicked");
            
            if ($("#outbox div.friend").length == 0) {
              $("#outbox").append("<p>Du hast zur Zeit keine unbeantworteten Anfragen im Ausgang</p>");
            }
          }
        }
      })
    });
  });

  //Chat
  //Nachricht senden
  $("#chat input").live("keydown", function(e) {
    if (e.which == "13") {
      e.preventDefault();
      var mssg = $(this).val();
      var rec = $(this).attr("id").split("input")[1];
      $(this).val("");
      
      $.post("ajax/sendchat.php", {text: mssg, rec_id: rec}, function(data) {
        $("div#chats" + rec).append(data);
        $("div#chats" + rec).scrollTop($("div#chats" + rec)[0].scrollHeight);
        
        var last_mssg = $(data).find("div.text").html();
        $("h3#chat" + rec).find("span.last_mssg").html(last_mssg);
      });
    }
  });
  //Neuer Chat
  $("#newchat").die().live("click", function(e) {
    e.preventDefault();
    var dialog = "<div>Neuen Chat beginnen mit: (Username)<br /><input type='text' value='' id='chatnick' /></div>";
    
    $(dialog).dialog({
        title: "Neuer Chat",
        modal: true,
        resizable: false,
        width: 335,
        buttons: {
          "OK": function() {
            var chatnick = $(this).find("input").val();
            $(this).dialog("close");
            
            if (chatnick !== "") {
              $.get("ajax/newchat.php", {nick: chatnick}, function(xml) {
                var id = $("id", xml).text();
                if (id == 0) {
                  alert($("error", xml).text());
                } else if ($("h3#chat" + id).length) {
                  $("#chat").prepend($("#chat div:has(h3#chat" + id + ")"));
                  $("#chat").accordion("activate", $("h3#chat" + id));
                } else {
                  var nick = $("nick", xml).text();
                  var insert = "<div><h3 id=\"chat" + id + "\"><a href=\"#\">" + nick + " <span class=\"last_mssg\"></span></a></h3><div></div></div>"
                  
                  $("#chat").accordion("destroy").prepend(insert);
                  initChat();
                  $("#chat").accordion("activate", 0);
                }
              });
            }
          },
          "Abbrechen": function() {
            $(this).dialog("close");
          }
        }
      })
  });

  //Auto-Reload
  //Feed
  setInterval(function() {
    if ($("div.feed").length) {
      var status = $("div.feed div.status:first").attr("id").split("status")[1];
      var profile = $("div.feed").attr("id").split("feed")[1];
      
      $.get("ajax/checkfeed.php", {sid: status, pid: profile}, function(data) {
        var num = $(data).find("div.status").length;
        
        if (num > 0) {
          if ($("#refresh").length == 0) {
            $("div.feed").prepend("<div id=\"refresh\" class=\"hide\"><a href=\"#\" class=\"button\"><span class=\"num\">" + num + "</span> neue Meldungen</a></div>");
            $("#refresh").slideDown("slow");
            initButtons();
          } else {
            $("#refresh span.num").html(num);
          }
          
          $("#refresh a").die().live("click", function(e) {
            e.preventDefault();
            $(this).remove();
            $("div.feed").prepend(data);
          });
        }
      });
    }
  }, 10000);
  //aktiver Chat
  setInterval(function() {
    if ($("#chat").length && $("#chat").accordion("option", "active") !== false) {
      var friend = $("#chat h3.ui-state-active").attr("id").split("chat")[1]
      var message = $("#chat div.ui-accordion-content-active div.message:last").attr("id").split("message")[1];
      
      $.get("ajax/checkchat.php", {fid: friend, mssg_id: message}, function(data) {
        if (data) {
          $("div#chats" + friend).append(data);
          $("div#chats" + friend).scrollTop($("div#chats" + friend)[0].scrollHeight);
          
          var last_mssg = $("div#chats" + friend).find("div.text:last").html();
          $("h3#chat" + friend).find("span.last_mssg").html(last_mssg);
        }
      });
    }
  }, 3000);
  //Freundschaftsanfragen
  setInterval(function() {
    $.get("ajax/checkfriends.php", function(data) {
    });
  }, 30000);
});

//Funktionen
//Chat
function initChat() { 
  var stop = false;
  $("#chat h3").click(function(e) {
			if (stop) {
				e.stopImmediatePropagation();
				e.preventDefault();
				stop = false;
			}
		});
  $("#chat").accordion({
    //collapsible: false,
    header: "> div > h3",
    active: false,
    clearStyle: true,
    changestart: function(event, ui) {
      $(ui.oldHeader).find("span.last_mssg").show();
      $(ui.newHeader).find("span.last_mssg").hide();
      
      var fid = $(ui.newHeader).attr("id").split("chat")[1];
      $(ui.newContent).load("ajax/chat.php?fid=" + fid, function() {
        var chats = $(ui.newContent).find("div.chats");
        $(chats).scrollTop($(chats)[0].scrollHeight);
      });
    }
  }).sortable({
    axis: "y",
		handle: "h3",
    stop: function() {
			stop = true;
		}
  });
}

//Textarea
function initTextarea() {
  var clicked = false;
  $("textarea").val("Sag es!").live("click", function() {
    $(this).addClass("expand");
    $("form div.hide").show();
    
    if (!clicked) {
      $(this).val("");
      clicked = true;
    }
  });
}

function initTabs() {
  $("#tabs, #friendtabs").tabs({
    load: function() {
      initButtons();
    }
  });
}
  
function initNav() {
  $("#accordion").accordion({
    animated: "bounceslide"
  });
}

function initButtons() {
  $("input:submit, a.button").button();
  $("td:has(input:radio)").buttonset();
  $("a.button.sent").button({
      label: "Versendet!",
      disabled: true
  });
  $("a.button.recieved").button({
      label: "Antwort ausst&auml;ndig",
      disabled: true
  });
}

function initBirthday() {
  $("#birthday").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
    minDate: new Date(1970, 0, 1),
    maxDate: new Date(2000, 11, 31),
    defaultDate: new Date(1990, 0, 1)
  });
}