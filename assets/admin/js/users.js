jQuery(document).ready(function($) {
  $(document).on("click", ".edit-user", function(event) {
    $("#success-update").html("");
    $id = $(this).attr("user-id");
    $_token = $('[name="_token"]').val();
    $(".edit-user-response").html(
      '<center><i class="fa fa-spinner fa-spin text-green" style="font-size:48px;"></i><center>'
    );
    jQuery.ajax({
      url: "users/edit",
      type: "POST",
      dataType: "html",
      data: {
        id: $id,
        _token: $_token
      },
      complete: function(xhr, textStatus) {
        //called when complete
      },
      success: function(data, textStatus, xhr) {
        //called when successful
        $(".edit-user-response").html(data);
      },
      error: function(xhr, textStatus, errorThrown) {
        //called when there is an error
        $(".edit-user-response").html(
          '<span class="alert alert-danger" >' + textStatus + "</span>"
        );
      }
    });
    /* Act on the event */
  });

/* View User Details */
    $(document).on("click", ".view-user", function (event) {
        $id = $(this).attr("user-id");
        $_token = $('[name="_token"]').val();
        $(".view-user-response").html(
            '<center><i class="fa fa-spinner fa-spin text-green" style="font-size:48px;"></i><center>'
        );
        jQuery.ajax({
            url: "users/"+$id,
            type: "get",
            dataType: "html",
            data: {
            },
            complete: function (xhr, textStatus) {
                //called when complete
            },
            success: function (data, textStatus, xhr) {
                //called when successful
                $(".view-user-response").html(data);
            },
            error: function (xhr, textStatus, errorThrown) {
                //called when there is an error
                $(".view-user-response").html(
                    '<span class="alert alert-danger" >' + textStatus + "</span>"
                );
            }
        });
        /* Act on the event */
    });




  //update-user ajax

  $(document).on("click", ".update-user", function(event) {
    /* Act on the event */
    $formdata = $("#update-user-form").serialize();
    $("#success-update").html(
      '<center><i class="fa fa-spinner fa-spin text-green" style="font-size:20px;"></i><center>'
    );
    //alert($formdata);
    jQuery.ajax({
      url: $("#update-user-form").attr("action"),
      type: "PATCH",
      dataType: "html",
      data: $formdata,
      complete: function(xhr, textStatus) {
        //called when complete
      },
      success: function(data, textStatus, xhr) {
        //called when successful
        // $("#success-update").html(data);
        //console.log(data);
        data = jQuery.parseJSON(data);
        if (data.success) {
          $("#user-table").load(location.href + " #user-table");
          $("#success-update").html(
            '<span class="alert alert-success" >' + data.message + "</span>"
          );
          setTimeout(function() {
            $("#edit-user").modal("hide");
          }, 700);
        } else {
          $("#success-update").html("");
          /* iterate through array or object */
          // alert(val);
          $("#success-update").append(
            '<div class="alert alert-danger" >' + data.message + "</div>"
          );
        }
      },
      error: function(xhr, textStatus, errorThrown) {
        //called when there is an error
        var errors = jQuery.parseJSON(xhr.responseText);
        $("#success-update").html("");
        $.each(errors.errors, function(key, val) {
          /* iterate through array or object */
          //alert(val);
          $("#upd-err-" + key).html(val);
          //$("#success-update").append('<div class="alert alert-danger" >'+val+'</div>');
        });
        //$("#success-update").html('<span class="alert alert-danger" >'+textStatus+'</span>');
      }
    });
  });
});

// Add user Ajax--

$(".add-user").click(function(event) {
  /* Act on the event */
  $(".user-err").html("");
  $userdata = $("#add-user-form").serialize();
  $("#success-add").html(
    '<center><i class="fa fa-spinner fa-spin text-green" style="font-size:20px;"></i><center>'
  );
  jQuery.ajax({
    url: $("#add-user-form").attr("action"),
    type: "POST",
    dataType: "html",
    data: $userdata,
    complete: function(xhr, textStatus) {
      //called when complete
    },
    success: function(data, textStatus, xhr) {
      //called when successful
      var data = jQuery.parseJSON(data);
      $("#success-add").html("");
      if (data.success) {
        $("#success-add").append(
          '<div class="h4 badge bg-green" >' + data.message + "</div>"
        );
        $("#user-table").load(location.href + " #user-table");
        $("#add-user-form")[0].reset();
        setTimeout(function() {
          $("#success-add").html("");
          $("#add-user").modal("hide");
        }, 700);
      } else {
        $("#success-add").append(
          '<div class="h4 badge bg-red" >' + data.message + "</div>"
        );
      }
    },
    error: function(xhr, textStatus, errorThrown) {
      //called when there is an error
      var errors = jQuery.parseJSON(xhr.responseText);
      $("#success-add").html("");
      $.each(errors.errors, function(key, val) {
        /* iterate through array or object */
        //alert(val);
        $("#err-" + key).html(val);
        //
      });
    }
  });
});

// User delete confirm
$(".user-delete").submit(function(event) {
  event.preventDefault();
  /* Act on the event */
  $form = $(this);
  $.confirm({
    title: "Confirm!",
    content: "Are you sure you want to Disable ?",
    theme: "material",
    type: "purple",
    animation: "rotate",
    draggable: true,
    buttons: {
      confirm: function() {
        $form.unbind("submit").submit();
      },
      cancel: function() {
        return "cancel";
      }
    }
  });
  //return false;
});

// $(function() {
//   $("#user_search").autocomplete({
//     source: $("#user_search").attr("url")
//   });
// });


// Add user Ajax--

$(".set_status").click(function (event) {
  /* Act on the event */

  $selected = $(this);
  $(this).html(
     '<a class="btn btn-xs btn-primary" ><i class="fa fa-spinner fa-spin text-white"></i></a>'
  );


$id = $(this).attr('user-id');
  $_token = $('[name="_token"]').val();
  $url = $("#set_status_url").val();
  jQuery.ajax({
    url: $url,
    type: "POST",
    dataType: "html",
    data: { id: $id, _token: $_token},
    complete: function (xhr, textStatus) {
      //called when complete
    },
    success: function (data, textStatus, xhr) {
      //called when successful
      var data = jQuery.parseJSON(data);

      if (data.success) {
        $type = data.type;
        
        if($type == "1"){
          $selected.html('<a class="btn btn-xs btn-success" ><i class="fa fa-ban"></i></a>');
        }
        if($type == "3"){
          $selected.html('<a class="btn btn-xs btn-danger" ><i class="fa fa-ban"></i></a>');
        }
 
            
      } else {
        console.log(data.message);
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log("Error Something Wrong");
    }
  });
});



// Update Request--

$(".maa").click(function (event) {
  /* Act on the event */

  $id = $(this).attr('data-iid');
  $selected = $(this);
    $selected.html(
      '<a class="btn btn-xs btn-primary" ><i class="fa fa-spinner fa-spin text-white"></i></a>'
    );
  $_token = $('[name="_token"]').val();
  $url = $("#set_request_update_url").val();
  jQuery.ajax({
    url: $url,
    type: "POST",
    dataType: "html",
    data: { id: $id, _token: $_token },
    complete: function (xhr, textStatus) {
      //called when complete
    },
    success: function (data, textStatus, xhr) {
      //called when successful
      var data = jQuery.parseJSON(data);

      if (data.success) {
        $status = data.status;

        if ($status == "0") {
          $selected.html('<button class="btn btn-success btn-xs">Mark as Accepted</button>');
        }
        if ($status == "2") {
          $selected.html('<button class="btn btn-danger btn-xs">Unmark as Accepted</button>');
        }


      } else {
        console.log(data.message);
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log("Error Something Wrong");
    }
  });
});




// Add news Ajax--

$(".add-news").click(function (event) {
  /* Act on the event */
  $(".news-err").html("");

  var form = $('#add-news-form');
  var formdata = false;
  if (window.FormData) {
    formdata = new FormData(form[0]);
  }


  $("#success-add").html(
    '<center><i class="fa fa-spinner fa-spin text-green" style="font-size:20px;"></i><center>'
  );

  jQuery.ajax({
    url: $("#add-news-form").attr("action"),
    type: "POST",
    dataType: "html",
    data: formdata ? formdata : form.serialize(),
    cache: false,
    contentType: false,
    processData: false,
    complete: function (xhr, textStatus) {
      //called when complete
    },
    success: function (data, textStatus, xhr) {
      //called when successful
      var data = jQuery.parseJSON(data);
      $("#success-add").html("");
      if (data.success) {
        $("#success-add").append(
          '<div class="h4 badge bg-green" >' + data.message + "</div>"
        );
        $("#news-div").load(location.href + " #news-div");
        $("#add-news-form")[0].reset();
        setTimeout(function () {
          $("#success-add").html("");
          $("#add-news").modal("hide");
        }, 700);
      } else {
        $("#success-add").append(
          '<div class="h4 badge bg-red" >' + data.message + "</div>"
        );
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      //called when there is an error
      // $("#success-add").html(
      //   '<div class="h4 badge bg-red" >' + textStatus + "</div>"
      // );
      var errors = jQuery.parseJSON(xhr.responseText);
      $("#success-add").html("");
      $.each(errors.errors, function (key, val) {
        /* iterate through array or object */
        //alert(val);
        $("#err-" + key).html(val);
        //
      });
    }
  });
});


/* Delete Confirm */
$(document).on("click", ".delete-confirm", function(event) {
   $link = $(this).attr('link');
  $.confirm({
    title: 'Confirm!',
    content: 'Are you sure ?',
    type: 'purple',
    buttons: {
      confirm: function () {
       location.href = $link;
      },
      cancel: function () {
  
      }
    }
  });


});


/* change user password */
$(document).on("click", "#change_pwd", function (event) {

  $link = $(this).attr('link');
  $newpwd = $("#usr_pwd").val();
  $id = $("#usr_pwd").attr('user-id');

  if($id == "" || $newpwd == "" ){
    $.alert({
      title: 'Alert!',
      content: "Field cannot be blank !",
      type: 'red'
    });
    return false;
  }
  $.confirm({
    title: 'Confirm!',
    content: 'Are you sure you want to update ?',
    type: 'red',
    buttons: {
      confirm: function () {
        $("#change_response_"+$id).html(
          '<center><i class="fa fa-spinner fa-spin text-red fa-2x"></i><center>'
        );
        /* Ajax request */
        jQuery.ajax({
          url: $link,
          type: "POST",
          dataType: "html",
          data: { "id": $id, "password": $newpwd, "_token": $_token = $('[name="_token"]').val()},
          success: function (data, textStatus, xhr) {
            //called when successful
            var data = jQuery.parseJSON(data);
            $("#success-add").html("");
            if (data.success) {
              $("#change_response_" + $id).html(
                '<div class="h4 badge bg-green" >' + data.message + "</div>"
              );

            } else {
              $("#change_response_" + $id).html(
                '<div class="h4 badge bg-red" >' + data.message + "</div>"
              );
            }
          },
          error: function (xhr, textStatus, errorThrown) {
            //called when there is an error
            var errors = jQuery.parseJSON(xhr.responseText);
            $("#change_response_" + $id).html("");
            $.each(errors.errors, function (key, val) {
              /* iterate through array or object */
              $("#change_response_" + $id).html(
                '<div class="h4 badge bg-red" >' + val + "</div>"
              );
              //
            });
          }
        });
      },
      cancel: function () {

      }
    }
  });


});


// Send Notification Ajax--




$(".user-notify").click(function (event) {
  $user_id = $(this).attr('user-id');
  $(".user-notify-send").attr('user-id',$user_id);
  //$.alert($user_id);
});

$(".user-notify-send").click(function (event) {
  /* Act on the event */
  $(".news-err").html("");

  var form = $('#user-notify-form');

 // $.alert(form.attr('action'));
 $("#user-notify-response").html(
   '<center><i class="fa fa-spinner fa-spin text-green" style="font-size:20px;"></i><center>'
  );
  
  //return false;
  jQuery.ajax({
    url: form.attr('action'),
    type: "POST",
    dataType: "html",
    data: form.serialize()+ "&user_id=" + $user_id,
    success: function (data, textStatus, xhr) {
      //called when successful
      var data = jQuery.parseJSON(data);
      $("#user-notify-response").html("");
      if (data.success) {
        $("#user-notify-response").append(
          '<div class="h4 badge bg-green" >Sent Successfully</div>'
        );
        //$("#news-div").load(location.href + " #news-div");
        //form[0].reset();
        
      } else {
        $("#user-notify-response").append(
          '<div class="h4 badge bg-red" >' + data.message + "</div>"
        );
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      // called when there is an error
      //console.log(xhr);
      $("#user-notify-response").html(
        '<div class="h4 badge bg-red" >'+textStatus+": " + xhr.statusText + "</div>"
      );
      var errors = jQuery.parseJSON(xhr.responseText);
      $("#user-notify-response").html("");
      $.each(errors.errors, function (key, val) {
        /* iterate through array or object */
        //alert(val);
        $("#err-notify-" + key).html(val);
        //
      });
    }
  });
});

