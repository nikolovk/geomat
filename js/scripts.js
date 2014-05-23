/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// Read all activities for selected project
// Add information to activiti select list


// Check/Uncheck function in send page
function CheckAll(){
    $('input.models').prop('checked', true);
    $('#check').html('Uncheck');
    $('#check').attr('onclick','UnCheckAll()');
}
function UnCheckAll(){
    $('input.models').removeAttr('checked');
    $('#check').html('Check');
    $('#check').attr('onclick','CheckAll()');
}
//send models - add send date
function SendChecked(id_project,lot){
    var checked = [];
    $(".models:checked").each(function ()
    {
        checked.push($(this).attr('id'));
    });
    $.getJSON("inc/send.php", {
        'send':checked
    }, function(data){
        if (data) {
            window.location = 'send_job.php?_submit_check=1&lot=' + lot + '&project=' + id_project;
        } else {
            alert('Please, select models first!!!');
        }
    });
}

//Show popup ADD leave
function ShowAddLeave(){
    $(".submit").html('Add');
    $(".submit").attr('onclick', 'AddLeave()')

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
}
function AddLeave(){
    var id = $('#name').val();
    var type = $('#type').val();
    var from = $('#from').val();
    var to = $('#to').val();
    var days = $('#days').val();
    if (id && type && from && to && days) {
        $.getJSON('inc/add_leave.php', {
            'id':id,
            'type':type,
            'from':from,
            'to':to,
            'days':days
        }, function (data){
            if (data) {
                window.location = 'leave.php';
            }
        });
    } else {
        alert('Please fill all fields!')
    }
}
function ShowEditLeave(id,from,to){
    $(".submit").html('Edit');
    $(".submit").attr('onclick', 'EditLeave('+id+')')
    var name = $("#" + id + "> td:nth-child(1)").html();
    var type = $("#" + id + "> td:nth-child(2)").html();
    var days = $("#" + id + "> td:nth-child(5)").html();
    $("option:contains('"+name+"')").prop('selected','selected');
    $("option:contains('"+type+"')").prop('selected','selected');
    $('#days').val(days);
    $('#from').val(from);
    $('#to').val(to);

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
}
function EditLeave(id){
    var id_user = $('#name').val();
    var type = $('#type').val();
    var from = $('#from').val();
    var to = $('#to').val();
    var days = $('#days').val();
    if (id_user && type && from && to && days) {
        $.getJSON('inc/edit_leave.php', {
            'id':id,
            'id_user':id_user,
            'type':type,
            'from':from,
            'to':to,
            'days':days
        }, function (data){
            if (data) {
                window.location = 'leave.php';
            }
        });
    } else {
        alert('Please fill all fields!')
    }
}
function DeleteLeave(id){
    var answer = confirm('Are you sure, that you want to remove leave?');
    if (answer) {
        $.getJSON('inc/delete_leave.php', {
            'id':id
        }, function (data){
            if (data) {
                window.location = 'leave.php';
            }
        });
    }
}

function ReadActivities() {
    $('#activiti').empty().append('<option value="">----</option>');
    $.getJSON("inc/read_activities.php?", {
        'id_project': $("#project").val()
    },
    function(data) {
        if (data) {
            $.each(data, function(key, value) {
                $('#activiti').append($('<option>').text(value).attr('value', key));
            });
        }
    });
}

// Read all activities for selected project
// Add information to activiti select list
function ReadCommonActivities() {
    $('#activiti').empty().append('<option value="">----</option>');
    $.getJSON("inc/read_common_activities.php?", {
        'id_project': $("#project").val()
    },
    function(data) {
        if (data) {
            $.each(data, function(key, value) {
                $('#activiti').append($('<option>').text(value).attr('value', key));
            });
        }
    });
}

// Read all lots for selected project
// Add lots to  select list
function ReadLots() {
    $('#lot').empty().append('<option value="">----</option>');
    $.getJSON("inc/read_lots.php?", {
        'id_project': $("#project").val()
    },
    function(data) {
        if (data) {
            $.each(data, function(key, value) {
                $('#lot').append($('<option>').text(value).attr('value', key));
            });
        }
    });
}

//Delete activiti
function RemoveActiviti(id) {
    var answer = confirm('Are you sure, that you want to remove activiti?');
    if (answer) {
        $.getJSON('inc/remove_activiti.php', {
            'id': id
        },
        function(data) {
            if (data) {
                $('#' + id).remove();
                alert('Activiti removed');
            } else {
                alert('Can\'t remove this activiti!')
            }
        });
    }
}

//Delete record from work
function DeleteWork(id) {
    var answer = confirm('Are you sure, that you want to remove work record?');
    if (answer) {
        $.getJSON('inc/remove_work.php', {
            'id': id
        },
        function(data) {
            if (data) {
                $('#' + id).remove();
                alert('Work record removed');
            } else {
                alert('Can\'t remove this work record!')
            }
        });
    }
}
//Delete model
function DeleteModel(id) {
    var answer = confirm('Are you sure, that you want to remove model?');
    if (answer) {
        $.getJSON('inc/remove_model.php', {
            'id': id
        },
        function(data) {
            if (data) {
                $('#' + id).remove();
            } else {
                alert('Can\'t remove model with started job! Please remove jobs first!');
            }
        });
    }
}
//Delete project
function DeleteProject(id) {
    var answer = confirm('Are you sure, that you want to remove project?');
    if (answer) {
        $.getJSON('inc/remove_project.php', {
            'id': id
        },
        function(data) {
            if (data == 1) {
                $('#' + id).remove();
            } else {
                alert('Can\'t remove project! Please check if exist work, activities or models!');
            }
        });
    }
}

//Take model
function TakeModel(id_model, id_activiti, id_project) {
    var answer = confirm('Are you sure, that you want to start model?');
    if (answer) {
        $.getJSON('inc/take_model.php', {
            'id_model': id_model,
            'id_activiti': id_activiti,
            'id_project': id_project
        },
        function(data) {
            if (data) {
                $('#' + id_model).remove();
            } else {
                alert('You can\'t start that model!')
            }
        });
    }
}

function FinishJobPopup(id) {
    var count = $("#" + id + "> td:nth-child(9)").html();
    var time = $("#" + id + "> td:nth-child(10)").html();
    $('#count').val(count);
    $('#time').val(time);
    $("#popup legend").html(id);
    $("#finish").html("Finish");
    $("#finish").show();
    $("#edit").hide();

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);

}

function EditPopup(id) {
    var count = $("#" + id + "> td:nth-child(9)").html();
    var time = $("#" + id + "> td:nth-child(10)").html();
    $('#count').val(count);
    $('#time').val(time);
    $("#popup legend").html(id);
    $("#edit").html("Change");
    $("#finish").hide();
    $("#edit").show();

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);

}

function ShowEditModel(id) {
    var model = $('#' + id + ' td:first-child').html();
    var stage = $('#' + id + ' td:nth-child(2)').html();
    var term = $('#' + id + ' td:nth-child(3)').attr('class');
    $("#popup legend").html(id);
    $("#model").val(model);
    $("#stage").val(stage);
    $("#term").val(term);
    $(".submit").html('Edit');
    $(".submit").attr('onclick', 'EditModel(' + id + ')');

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
}
function ChangePassword(id) {
    $.getJSON("inc/edit_password.php?", {
        'id': id,
        'password': $('#password').val(),
        'confirm': $('#confirm').val()
    }, function(data) {
        if (data == 0) {
            $('#mask , .popup').fadeOut(300, function() {
                $('#mask').remove();
            });
        } else {
            if (data.match) {
                alert(data.match);
            }
            if (data.pass) {
                alert(data.pass);
            }
        }
    });
}
function EditUser(id) {
    $.getJSON("inc/edit_user.php?", {
        'id': id,
        'nickname': $('#nickname').val(),
        'email': $('#email').val(),
        'name': $('#name').val(),
        'egn': $('#egn').val(),
        'phone': $('#phone').val()
    }, function(data) {
        if (data == 0) {
            $('#mask , #popup').fadeOut(300, function() {
                $('#mask').remove();
            });
            window.location = 'users.php';
        } else {
            if (data.nickname) {
                alert(data.nickname);
            }
            if (data.name) {
                alert(data.name);
            }
            if (data.email) {
                alert(data.email);
            }
        }
    });
}

function ShowChangePassword(id) {
    $("#change_pass legend").html('Change Password');
    $("#change_pass .submit").html('Change');
    $("#change_pass .submit").attr('onclick', 'ChangePassword(' + id + ')');

    //Fade in the Popup
    $(".popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
}
function ShowEditUser(id) {
    $("#popup legend").html(id);
    $("#nickname").val($('#' + id + ' td:nth-child(2)').html());
    $("#email").val($('#' + id + ' td:nth-child(3)').html());
    $("#name").val($('#' + id + ' td:nth-child(4)').html());
    $("#egn").val($('#' + id + ' td:nth-child(5)').html());
    $("#phone").val($('#' + id + ' td:nth-child(6)').html());
    $(".submit").html('Edit');
    $(".submit").attr('onclick', 'EditUser(' + id + ')');

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
}
function ShowEditProject(id) {
    var name = $('#' + id + ' td:nth-child(2)').html();
    $("#popup legend").html(name);
    $("#name").val(name);
    $(".submit").html('Edit');
    $(".submit").attr('onclick', 'EditProject(' + id + ')');

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
}
function ShowAddProject() {
    $("#popup legend").html('Add Project');
    $("#name").val('new project');
    $(".submit").html('Add');
    $(".submit").attr('onclick', 'AddProject()');

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
}
function AddProject() {
    var name = $('#name').val();
    if (name) {
        $.getJSON("inc/add_project.php?", {
            'name': name
        }, function(data) {
            if (data) {
                window.location = 'projects.php';
            } else {
                alert('This project allready exist! Please choose other name!');
            }
        });
    } else {
        alert('Please insert name of project!');
    }

}


function ShowAddModel(id_project, lot) {
    var model = "name";
    var stage = '100';
    $("#popup legend").html('Add Model');

    $("#model").val(model);
    $("#stage").val(stage);
    if (lot) {
        $("#popup_lot").val(lot);
    } else {
        $("#popup_lot").val('001');
    }
    $('#p_project').html($('#project').html());
    if (id_project) {
        $('#p_project option[value="' + id_project + '"]').attr('selected', 'selected');

    }

    $(".submit").html('Add');
    $(".submit").attr('onclick', 'AddModel()');

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
}

function AddModel() {
    var id_project = $('#p_project').val();
    if (id_project) {
        var lot = $('#popup_lot').val();
        $('#mask , #popup').fadeOut(300, function() {
            $('#mask').remove();
        });
        $.getJSON("inc/add_model.php?", {
            'model': $('#model').val(),
            'stage': $('#stage').val(),
            'note': $('#note').val(),
            'lot': lot,
            'term': $('#term').val(),
            'id_project': id_project
        }, function(data) {
            if (data) {
                window.location = 'models.php?_submit_check=1&lot=' + lot + '&project=' + id_project;
            } else {
                alert('There is problem, ask admin!!!');
            }
        });
    } else {
        alert('Please choose project!');
    }

}
function EditProject(id) {
    $.getJSON("inc/edit_project.php?", {
        'id': id,
        'name': $('#name').val()
    }, function(data) {
        if (data) {
            $('#' + id + ' td:nth-child(2)').html($('#name').val());
            $('#mask , #popup').fadeOut(300, function() {
                $('#mask').remove();
            });
        } else {
            $('#mask , #popup').fadeOut(300, function() {
                $('#mask').remove();
            });
        }
    });
}
function EditModel(id) {
    $.getJSON("inc/edit_model.php?", {
        'id': id,
        'model': $('#model').val(),
        'stage': $('#stage').val(),
        'term': $('#term').val()
    }, function(data) {
        if (data) {
            $("#" + id + ' td:first-child').html($('#model').val());
            $('#' + id + ' td:nth-child(2)').html($('#stage').val());
            $('#' + id + ' td:nth-child(3)').html(data);
            $('#' + id + ' td:nth-child(3)').attr('class', $('#term').val());
            $('#mask , #popup').fadeOut(300, function() {
                $('#mask').remove();
            });
        } else {
            $('#mask , #popup').fadeOut(300, function() {
                $('#mask').remove();
            });
        }
    });
}

function EditNote(id) {
    var note = $("#" + id).html();
    $('#note').val(note);
    $("#popup legend").html(id);
    $("#edit_note").html('Edit');

    //Fade in the Popup
    $("#popup").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);

}
function ShowModelTable(id, model) {
    ClosePopup();
    $.getJSON("inc/show_model.php", {
        'id_model': id,
        'from': $('#from').val(),
        'to': $('#to').val()
    }, function(data) {
        if (data) {
            $('#show_model table').html();
            $('#show_model table').append("<caption>" + model + ' ' + $('#from').val() + ' ' + $('#from').val() + "</caption>");
            $.each(data, function(key, value) {
                $('#show_model table').append("<tr>" + "<td>" + key + "</td>" + "<td>" + value + "</td>" + "</tr>");
            });
            //Fade in the Popup
            $("#show_model").fadeIn(300);
            // Add the mask to body
            $('body').append('<div id="mask"></div>');
            $('#mask').fadeIn(300);
        } else {
            alert("No info for this date!");
        }
    });
}
function ChoosePeriod(id, date, model) {
    //Fade in the Popup
    $("#choose_period").fadeIn(300);
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
    $('#choose_period a.submit').attr('onclick', 'ShowModelTable(' + id + ',\'' + model + '\')');
    $('#choose_period legend').html(model);
    $('#from').val(date);
    $('#to').val(date);
}
function ClosePopup() {
    $('#mask , #popup, .popup').fadeOut(300, function() {
        $('#mask').remove();
    });
}

$(document).ready(function() {
    $("#finish").click(function() {
        $.getJSON("inc/finish_work.php?", {
            'id': $("#popup legend").html(),
            'time': $('#time').val(),
            'count': $('#count').val()
        }, function(data) {
            if (data) {
                $("#" + $("#popup legend").html() + "> td:nth-child(8)").html(data);
                $("#" + $("#popup legend").html() + "> td:nth-child(9)").html($('#count').val());
                $("#" + $("#popup legend").html() + "> td:nth-child(10)").html($('#time').val());
                $('#mask , #popup').fadeOut(300, function() {
                    $('#mask').remove();
                });
            } else {
                $('#mask , #popup').fadeOut(300, function() {
                    $('#mask').remove();
                });
            }
        });
    });

    $("#edit").click(function() {
        $.getJSON("inc/edit_work.php?", {
            'id': $("#popup legend").html(),
            'time': $('#time').val(),
            'count': $('#count').val()
        }, function(data) {
            if (data == "1") {
                $("#" + $("#popup legend").html() + "> td:nth-child(9)").html($('#count').val());
                $("#" + $("#popup legend").html() + "> td:nth-child(10)").html($('#time').val());
                $('#mask , #popup').fadeOut(300, function() {
                    $('#mask').remove();
                });
            } else {
                $('#mask , #popup').fadeOut(300, function() {
                    $('#mask').remove();
                });
            }
        });
    });

    $('#edit_note').click(function() {
        $.getJSON("inc/edit_note.php?", {
            'id': $("#popup legend").html(),
            'note': $('#note').val()
        }, function(data) {
            if (data == "1") {
                $("#" + $("#popup legend").html()).html($('#note').val());
                $('#mask , #popup').fadeOut(300, function() {
                    $('#mask').remove();
                });
            } else {
                $('#mask , #popup').fadeOut(300, function() {
                    $('#mask').remove();
                });
            }
        });
    });


    // When clicking on the button close or the mask layer the popup closed
    $('a.close, #mask').bind('click', function() {
        $('#mask , #popup, .popup').fadeOut(300, function() {
            $('#mask').remove();
        });
        return false;
    });
});





