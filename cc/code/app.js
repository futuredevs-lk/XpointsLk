
// Common Functions ------------------------------------------------------------
comfirm_action_popup = (text, op1,op2,action, param)=>{
    $("#cc_confirmation_popup").toggleClass("visible");
    if(text == ""){
        return;
    }
    $("#cc_confirmation_popup h4").text(text);
    $("#cc_confirmation_popup .cc_yes").text(op1.toUpperCase());
    $("#cc_confirmation_popup .cc_no").text(op2.toUpperCase());
    $("#cc_confirmation_popup .cc_yes").click(function() {
        action(param);
        $("#cc_confirmation_popup").toggleClass("visible");
        $("#cc_confirmation_popup .cc_yes").unbind();
    });
};

Toggle_notify_popup = (text)=>{
    $("#cc_success_popup h4").text(text);
    $('#cc_success_popup').toggleClass('visible');
}

function togglechatbox(){
    getdata('fetch_current_group.php',(data)=>{
        $('#cc_current_group').html(data);
    })
    
    $(".chat-container").eq(0).toggleClass("visible");
    chathistelemnt.scrollTo(0,chathistelemnt.scrollHeight);
    
    $(".chat-new-notification").eq(0).toggleClass("hide");
    
    fetch_toggle_datas();
    fetch_bets();
}

function showtab(id,tab){
    var chat_tabs = $(".chat-tab");
    for (let chat_tab_tab = 0; chat_tab_tab < chat_tabs.length; chat_tab_tab++) {
        $(chat_tabs[chat_tab_tab]).removeClass("visible");
    }
    $(`#${id}`).addClass("visible");
    
    var chat_tabs = $(".chat-tab-btn");
    for (let chat_tab_tab = 0; chat_tab_tab < chat_tabs.length; chat_tab_tab++) {
        $(chat_tabs[chat_tab_tab]).removeClass("current-tab");
    }
    $(tab).addClass("current-tab");
}

// Click next input[type='file'] on image click
document.querySelectorAll(".group-image-container img").forEach(img=>$(img).click(()=>{
    $(img).next().click();
}));


// Group chat functions---------------------------------------------------------

var chathistelemnt = $(".chat-history-container")[0];
chathistelemnt.scrollTo(0,chathistelemnt.scrollHeight);

remove_paticipate = (id)=>{
    $("#cc_confirmation_popup").removeClass("visible");
    deletedata({'id': id},'delete_participate.php',()=>{
        $(`#user${id}`).remove();
    });
}

delete_group = ()=>{
    $("#cc_confirmation_popup").removeClass("visible");
    deletedata('','delete_group.php',()=>{
        togglechatbox();
        togglechatbox();
        togglecontactlist();
        $("#group-Info").toggleClass("visible"); 
        last_msg_id = 0;
        $('#current_chat_history').html('');
    });
}

function togglecontactlist(){
    getdata('fetch_groups.php',(data)=>{
        $('#cc_contacts_list').eq(0).html(data);
    })
    $(".chat-contacts-list").eq(0).toggleClass("visible");
}

function viewcontact(id){
    deletedata({'id':id},'view_group.php',()=>{
        togglechatbox();
        togglechatbox();
        last_msg_id = 0;
        $('#current_chat_history').html('');
        togglecontactlist();
    })
}

function urlify(text) {
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, function(url) {
      return '<a target="_blank" href="' + url + '">' + url + '</a>';
    })
}
$("#chat-input").keyup(function(event) {
    if (event.keyCode === 13) {
            event.preventDefault();
            sendthismsg();
    }
});
function sendthismsg(){
    var chatmsg = urlify($("#chat-input").val().trim());
    $("#chat-input").val(chatmsg);
    if(chatmsg == "" && document.getElementById("write_chat_attah_file").files.length == 0){
        return;
    }
    postform('write_chat','write_chat.php',()=>{
        $("#chat-input").focus();
        $("#chat-input").val('');
        $("#write_chat_attah_file").val('');
        change_btn_fnc();
    });
}

change_btn_fnc = ()=>{
    if($("#chat-input").val().trim() != ""){
        $("#chat-write-btn_place").html(
            `<button title="Send Message" id="cc-send_btn" onclick="sendthismsg()">
                <i class="fa fa-send"></i>
            </button>`
        );
    }else{
        $("#chat-write-btn_place").html(
            `<button title="Attach File" id="cc-send_btn" onclick="$('#write_chat_attah_file').click();">
                <i class="fa fa-paperclip"></i>
            </button>`
        ); 
    }
};

view_add_contact_panel = (id1,id2)=>{
    $("#select-group-participates").toggleClass("visible");
    
    $('#select-group-participates .cc-save-btn').unbind('click');
    $('#select-group-participates .cc-save-btn').eq(0).click(()=>{
        $(id1).text(getCheckedBoxes('add_participate').length > 0 ? getCheckedBoxes('add_participate').length + " PARTICIPATE(S)" : "+ ADD PARTICIPATES");
        $(id2).val(getCheckedBoxes('add_participate'));
        
        $("#select-group-participates").removeClass("visible");
    })
};

function getCheckedBoxes(chkboxName) {
    var checkboxes = document.getElementsByName(chkboxName);
    var checkboxesChecked = [];
    // loop over them all
    for (var i=0; i<checkboxes.length; i++) {
       // And stick the checked ones onto an array...
       if (checkboxes[i].checked) {
          checkboxesChecked.push(checkboxes[i].value);
       }
    }
    // Return the array if it is non-empty, or null
    return checkboxesChecked;
}

// Group Settings
toggle_group_info = ()=>{
    getdata('fetch_group_info.php',(data)=>{
        $('#cc_group_info').html(data);
    })
    getdata('fetch_group_external_users.php',(data)=>{
        $('#cc_users_list').html(data);
    })
    $("#group-Info").toggleClass("visible");    
};

function update_group_info(){
    if($('#edit_group_name').val() == ''){
        Toggle_notify_popup('Provide a Group Name to Save Changes!');
    }
    else{
        let success = (data)=>{
            $("#group-Info").toggleClass("visible");
            Toggle_notify_popup('Successfully Updated!')
        }
        postform('update_group_form','update_group_info.php',success);
        togglechatbox();
        togglechatbox();
    }
}

// Create New Group
toggle_group_new = ()=>{
    $("#create-new-group").toggleClass("visible");    
    getdata('fetch_users.php',(data)=>{
        $('#cc_users_list').html(data);
    })
};


function cc_create_group(){
    if($('#new_group_name').val() == ''){
        Toggle_notify_popup('Provide a Group Name to Create New Group!');
    }
    else{
        let success = (data)=>{
            $('#create-new-group').toggleClass('visible');
            Toggle_notify_popup('Successfully Created!');
        }
        postform('create_group_form','create_group.php',success);
    }
}

let last_msg_id = 0;
setInterval(()=>{
    getdata(`fetch_msgs.php?id=${last_msg_id}`,(data)=>{
        $('#current_chat_history').append(data);
    });
    
}, 1000);

// Polling tab functions--------------------------------------------------------

fetch_toggle_datas = ()=>{
    getdata(`fetch_pollings.php`,(data)=>{
        $('#poll-question-wrap').html(data);
        document.querySelectorAll(".poll-question").forEach(an => $(an).click(() => {$(an).next().toggleClass("visible");}));
        
        getdata(`fetch_poll_tags.php`,(data)=>{
            $('#poll-tags').html(data);
        
            var poll_tags = [];
            poll_tags_span = $(".poll-tag");
            
            filter_poll_questions = ()=>{
                if(poll_tags.length == 0){
                    document.querySelectorAll(".poll-question-container").forEach(q => $(q).addClass("visible"));
                    $(poll_tags_span[0]).addClass("selected");
                    return;
                }
                document.querySelectorAll(".poll-question-container").forEach(q => $(q).removeClass("visible"));
                poll_tags.forEach(tag =>  document.querySelectorAll(`.${tag}`).forEach(q => $(q).addClass("visible")));
            }
            filter_poll_questions();
            
            for (let tag_index = 0; tag_index < poll_tags_span.length; tag_index++) {
                $(poll_tags_span[tag_index]).click(()=>{
                    let clicked_tag = poll_tags_span[tag_index];
                    $(clicked_tag).toggleClass("selected");
                    if(clicked_tag.innerText == "All" || poll_tags == []){
                        document.querySelectorAll(".poll-tag").forEach(el => $(el).removeClass("selected"));
                        poll_tags = [];
                        $(clicked_tag).addClass("selected");
                        filter_poll_questions();
                        return;
                    }
                    $(poll_tags_span[0]).removeClass("selected");
                    // Remove if exist
                    if(poll_tags.includes(clicked_tag.innerText)){
                        var index = poll_tags.indexOf(clicked_tag.innerText);
                        if (index > -1) {
                            poll_tags.splice(index, 1);
                        }
                    }
                    // If not Exist
                    else{
                        poll_tags.push(clicked_tag.innerText);
                    }
                    filter_poll_questions();
                });
            }
        });
    });
}

submit_poll_answer = (Q,A)=>{
    deletedata({'q':Q,'a':A},"submit_poll_answer.php",()=>{
        // $(`#cc_poll_q_${Q}`).html(`
        //     <div class="poll_option_submited">Submitted</div>
        // `);
        togglechatbox();
        togglechatbox();
    });
}

function add_polling_option(id,id2){
    $(`#${id}`).append(`
        <input name="${id2}[]" placeholder="Option" type="text">
    `);
}

toggle_poll_new = ()=>{
    $("#create-new-poll").toggleClass("visible");    
};
post_new_poll = ()=>{
    if($('#poll_new_question').val() == "" || $('.poll_new_options').eq(0).val() == "" || $('.poll_new_options').eq(0).val() == ""){
        Toggle_notify_popup('All Fields are required!');
        return;
    }
    postform('create_poll_form','create_poll.php',()=>{
            toggle_poll_new();
            fetch_toggle_datas();
            Toggle_notify_popup('Successfully Created!');
    });
}

delete_poll = (id)=>{
    deletedata({'id':id},"delete_poll.php",()=>{
        togglechatbox();
        togglechatbox();
        Toggle_notify_popup('Successfully Deleted!');
    });
}
// post_edit_poll = ()=>{
    
// }


// toggle_poll_edit = (id)=>{
//     $("#edit-poll").toggleClass("visible");    
// };



// Betting Tab Functions--------------------------------------------------------

toggle_bet_new = ()=>{
    $("#create-new-bet").toggleClass("visible");    
};
toggle_bet_edit = ()=>{
    $("#cc-edit-bet").toggleClass("visible");    
};

create_new_bet = ()=>{
    if($('#bet_new_desc').val() == ""){
        Toggle_notify_popup('Provide some details about your auction!');
        return;
    }
    if($('#bet_new_amount_min').val() == "" || $('#bet_new_amount_max').val() == ""){
        Toggle_notify_popup('Pls provide vaid minimum and maximum value!');
        return;
    }
    if($('#bet_new_amount_min').val() >= $('#bet_new_amount_max').val()){
        Toggle_notify_popup('Maximum auction value should be bigger than Minimum auction value!');
        return;
    }
    if(Date.parse($('#bet_new_time').val()) <= Date.now() || $('#bet_new_time').val() == ""){
        Toggle_notify_popup('Pls provide a valid date and time!');
        return;
    }
    
    postform('create_bet_form','create_bet.php',()=>{
        Toggle_notify_popup('Successfully Created New Auction!');
        toggle_bet_new();
        fetch_bets();
    })
}
var fetch_bid_datas = "";
let last_bid_id = 0;
fetch_bets = ()=>{
    getdata('fetch_bet.php',(data)=>{
        $('#cc_betting_list').html(data);
        
        document.querySelectorAll(".betting-container.show_preview_to_this").forEach(bet => $(bet).click(
        () => {
            $(".betting-info-card").html(`
                ${$(bet).children(".betting-info-text").html()}
            `);
            $(".betting-info-numer").html(`
                ${$(bet).children(".invisible").html()}
            `);
            deletedata({
                    'id':$(bet).children(".cc-bet-id").text()
                },'set_bet_id.php',()=>{});
            
            toggle_bet_preview();
            
            fetch_bid_datas =  setInterval(()=>{
               getdata(`fetch_bids.php?id=${last_bid_id}`,(data)=>{
                   $('#bet-ask-history').append(data);
               });
            }, 1000);
            
        }));
    })
}

setbettimer = (datetime, id)=>{
    var x = setInterval(function() {
      var distance = new Date(datetime).getTime() - new Date().getTime();

      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      if(document.getElementById(id) == null){
        clearInterval(x);
        return;
      }
      document.getElementById(id).innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
      if (distance < 0) {
        clearInterval(x);
        document.getElementById(id).innerHTML = "EXPIRED";
        $("#betting-container-"+id).removeClass('show_preview_to_this');
        // fetch_bets();
      }
    }, 1000);
    
}

function askbet(){
    var ask_value = $("#ask-bet").val();
    if(ask_value == ""){
        return;
    }
    var chat_datetime = new Date().toLocaleTimeString('en-GB', { hour: "numeric", minute: "numeric"});

    $(".bet-ask-history").eq(0).append(`
    <div class="chat-message-wrap">
                        <div class="chat-message sent">
                            <div class="chat-message-body">
                                ${ask_value}
                            </div>
                            <span class="chat-message-time">
                                ${chat_datetime}
                            </span>
                        </div>
                    </div>`
                    );
    $("#ask-bet").val("");
    document.getElementsByClassName("bet-ask-history")[0].scrollTo(0,document.getElementsByClassName("bet-ask-history")[0].scrollHeight);
    $("#ask-bet").focus();
    toggle_bet_toppers;
    toggle_bet_toppers;
    deletedata({'text':ask_value},'ask_bet.php',()=>{});
}

$("#ask-bet").keyup(function(event) {
    if (event.keyCode === 13) {
            event.preventDefault();
            askbet();
    }
});

toggle_bet_preview = ()=>{
    $("#betting-info-panel").toggleClass("visible");
    document.getElementsByClassName("bet-ask-history")[0].scrollTo(0,document.getElementsByClassName("bet-ask-history")[0].scrollHeight);
    if(fetch_bid_datas != ""){
        clearInterval(fetch_bid_datas);
        last_bid_id = 0;
        $('#bet-ask-history').html("");
    }
}

toggle_bet_toppers = ()=>{
    getdata(`fetch_top_betters.php`,(data)=>{
        $('#top_betters_place').html(data);
        $(".betting-top-betters").toggleClass("visible");
    });
}

delete_bet = (id)=>{
    deletedata({'id':id},"delete_bet.php",()=>{
        fetch_bets();
        $("#betting-info-panel").toggleClass("visible");
        Toggle_notify_popup('Successfully Deleted!');
    });
}

// CRUD Function----------------------------------------------------------------

function postform(form,page,action){
    var formData = new FormData($(`#${form}`)[0]); 
    $.ajax({ 
        url: `/cc/code/crud/${page}`, 
        type: 'POST', 
        data: formData, 
        async: false, 
        cache: false, 
        dataType: 'json',
        contentType: false, 
        processData: false, 
        success: function(data) {
            action(data);
            console.log(data);
        }, 
        error: function(data) {   
            console.log(data.responseText);
            Toggle_notify_popup('There is an error on this process please try again!');
        } 
    });
}

function getdata(page,action){
    $.ajax({    //create an ajax request to display.php
        type: "GET",
        url: `/cc/code/crud/${page}`,             
        dataType: "html",   //expect html to be returned                
        success: function(response){
            action(response); 
            //alert(response);
        }
    });
}

function deletedata(arg,page,action){
    $.ajax({ 
        url: `/cc/code/crud/${page}`, 
        type: 'POST', 
        data: arg ,
        success: function(data) {
            action(data);
            console.log(data);
        }, 
        error: function(data) {   
            console.log(data.responseText);
            Toggle_notify_popup('There is an error on this process please try again!');
        } 
    });
}















