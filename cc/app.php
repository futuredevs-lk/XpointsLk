<?php
include($_SERVER['DOCUMENT_ROOT'] . "/cc/code/config.php");
?>


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/3d1334ec7c.js" crossorigin="anonymous"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="/cc/code/app.css">

<div class="chat-app">

    <div class="chat-container">
        <div class="chat-header">
            <!-- <span class="chat-details-pic"></span> -->
            <span class="chat-username"><?php echo $current_user_name; ?></span>
            <a href="/" class="chat-hide" title="Hide" onclick="togglechatbox();"><i class="fa fa-minus"></i></a>
            <!-- <span class="chat-hide" title="New Group"><i class="fa fa-ellipsis-v"></i></span> -->
        </div>

        <div class="chat-nav-bar">
            <span onclick="showtab('chat-tab-groups',this)" class="chat-tab-btn current-tab" title="Groups"><i class="fa fa-group"></i></span>
            <span onclick="showtab('chat-tab-polls',this)" class="chat-tab-btn " title="Polls"><i class="fa fa-poll"></i></span>
            <span onclick="showtab('chat-tab-bettings',this)" class="chat-tab-btn " title="Bettings"><i class="fa fa-trophy"></i></span>
        </div>


        <div class="chat-body">

            <!-- Chat Groups TAB -->

            <div class="chat-tab visible" id="chat-tab-groups">
                <div class="cc-height-fill" id="group-chat">

                    <div class="chat-details">

                        <div class="chat-contact" title="Contact" onclick="togglecontactlist()">
                            <!-- <i class="fa fa-ellipsis-v"></i> -->
                            <span class="chat-new" title="You Got New Messages"></span><i class="fa fa-group"></i>
                            <div id="cc_current_group"></div>
                        </div>

                        <div class="chat-contacts-list">
                            <?php if ($current_user_role != 0) : ?>
                                <div class="chat-group-options">
                                    <div onclick="toggle_group_info()">Group Settings</div>
                                    <div onclick="toggle_group_new()">New Group</div>
                                </div>
                            <?php endif; ?>
                            <div id="cc_contacts_list"></div>
                        </div>
                    </div>

                    <!-- Chat goes here  -->
                    <div class="chat-history-container" id="current_chat_history">
                    </div>

                    <div class="chat-create">
                        <form style="width:100%;margin-right: 10px;margin-bottom: -5px;" enctype="multipart/form-data" name="write_chat" id="write_chat" method="post">
                            <textarea class="chat-input" name="chat_text" id="chat-input" type="text" placeholder="Type Here!" onkeyup="change_btn_fnc()"></textarea>
                            <input type="file" id="write_chat_attah_file" onchange="sendthismsg();" name="write_chat_attah_file" style="display:none;">
                        </form>

                        <div id="chat-write-btn_place">
                            <button title="Attach File" id="cc-send_btn" onclick="$('#write_chat_attah_file').click();">
                                <i class="fa fa-paperclip"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Group Info Begin -->
                <div class="cc-height-fill cc-second-panel" id="group-Info">
                    <div class="back-btn-container" onclick="toggle_group_info();">
                        <i class="fa fa-angle-left"></i> Back
                    </div>

                    <h2 style="text-align:center;margin:10px;">Group Settings</h2>
                    <form enctype="multipart/form-data" name="update_group_form" id="update_group_form" method="post">

                        <div id="cc_group_info"></div>

                    </form>

                    <div class="cc-action-btn-container">
                        <div class="cc-cancel-btn" onclick="toggle_group_info()">CANCEL</div>
                        <div class="cc-save-btn" onclick="update_group_info();">SAVE</div>
                    </div>
                    <div class="cc-cancel-btn" style="margin: 10px 0;background: lightsalmon;" onclick="comfirm_action_popup('Are you sure do you want to delete this group?','yes','no',delete_group);">DELETE GROUP</div>




                </div>
                <!-- Group Info End -->

                <!-- New Group Begin -->
                <div class="cc-height-fill cc-second-panel" id="create-new-group">
                    <div class="back-btn-container" onclick="toggle_group_new();">
                        <i class="fa fa-angle-left"></i> Back
                    </div>

                    <h2 style="text-align:center;margin:10px;">Create Group</h2>

                    <form enctype="multipart/form-data" name="create_group_form" id="create_group_form" method="post">

                        <div class="group-image-container">
                            <img id="group_new_img" src="http://xpoints.lk/cc/code/assets/image.png" alt="img">
                            <input accept="image/*" onchange="loadimgbeforeupload(event);" style="display: none;" type="file" name="new_group_image" id="new_group_image">
                        </div>
                        <script>
                            var loadimgbeforeupload = function(event) {
                                var output = document.getElementById('group_new_img');
                                output.src = URL.createObjectURL(event.target.files[0]);
                                output.onload = function() {
                                    URL.revokeObjectURL(output.src) // free memory
                                }
                            };
                        </script>

                        <input class="group-name" id="new_group_name" type="text" name="group_name" placeholder="GROUP NAME">


                        <input type="text" name="new_group_participate[]" id="new_group_participate" value="" style="display:none;">

                        <div class="chat-contact-add-participate" id="create_group_total_participates" onclick="view_add_contact_panel('#create_group_total_participates','#new_group_participate');">
                            + ADD PARTICIPATES
                        </div>

                        <div class="cc-action-btn-container">
                            <div class="cc-cancel-btn" onclick="toggle_group_new()">CANCEL</div>
                            <div class="cc-save-btn" onclick="cc_create_group()">CREATE</div>
                        </div>

                    </form>
                </div>
                <!-- New Group End -->

                <!-- Select Participates Begin-->

                <div class="cc-height-fill cc-second-panel" id="select-group-participates">
                    <div id="cc_users_list"></div>
                    <div class="cc-save-btn">DONE</div>
                </div>

                <!-- Select Participates End-->


            </div>

            <!-- Polls Tab -->

            <div class="chat-tab" id="chat-tab-polls">
                <div class="cc-height-fill">

                    <div class="chat-history-container">
                        <?php if ($current_user_role != 0) : ?>
                            <div class="chat-contact-add-participate" onclick="toggle_poll_new();">
                                New Polling
                            </div>
                        <?php endif; ?>

                        <div class="poll-tags" id="poll-tags">

                        </div>

                        <!-- Question Conatainer -->
                        <div class="poll-question-wrap" id="poll-question-wrap">
                        </div>

                    </div>
                </div>

                <!-- Craete new Polling Satrt -->

                <div class="cc-height-fill cc-second-panel" id="create-new-poll">
                    <div class="back-btn-container" onclick="toggle_poll_new();">
                        <i class="fa fa-angle-left"></i> Back
                    </div>

                    <form enctype="multipart/form-data" name="create_poll_form" id="create_poll_form" method="post">

                        <div class="group-image-container cc-full-img">
                            <img id="poll_new_place" src="https://xpoints.lk/cc/code/assets/image.png" alt="">
                            <input style="display: none;" onchange="loadimgbeforeupload3(event);" type="file" accept="image/*" name="poll_new_img" id="poll_new_img">
                            <script>
                                var loadimgbeforeupload3 = function(event) {
                                    var output = document.getElementById('poll_new_place');
                                    output.src = URL.createObjectURL(event.target.files[0]);
                                    output.onload = function() {
                                        URL.revokeObjectURL(output.src) // free memory
                                    }
                                };
                            </script>
                        </div>

                        <input class="group-name" type="text" placeholder="Question" name="poll_new_question" id="poll_new_question">
                        <!-- <h2 style="text-align:center;margin:10px;">Participates</h2> -->
                        <input class="group-name" name="poll_new_tag" placeholder="Category" type="text">

                        <div id="new_poll_options_place">
                            <input name="poll_new_options[]" class="poll_new_options" placeholder="Option" type="text">
                            <input name="poll_new_options[]" class="poll_new_options" placeholder="Option" type="text">
                        </div>

                    </form>

                    <div class="chat-contact-add-participate" onclick="add_polling_option('new_poll_options_place','poll_new_options');">
                        + ADD OPTION
                    </div>

                    <div class="cc-action-btn-container">
                        <div class="cc-cancel-btn" onclick="toggle_poll_new()">CANCEL</div>
                        <div class="cc-save-btn" onclick="post_new_poll();">POST</div>
                    </div>
                </div>

                <!-- Craete new Polling End -->

                <!-- Edit Polling Satrt -->

                <!--<div class="cc-height-fill cc-second-panel" id="edit-poll">-->
                <!--    <div class="back-btn-container" onclick="toggle_poll_edit();">-->
                <!--        <i class="fa fa-angle-left"></i> Back-->
                <!--    </div>-->

                <!--    <form enctype="multipart/form-data" name="edit_poll_form" id="edit_poll_form" method="post">-->

                <!--        <div class="group-image-container cc-full-img">-->
                <!--            <img id="poll_edit_place" src="https://xpoints.lk/cc/code/assets/image.png" alt="">-->
                <!--            <input style="display: none;" onchange="loadimgbeforeupload4(event);" type="file" accept="image/*" name="poll_edit_img" id="poll_edit_img">-->
                <!--            <script>-->
                <!--                var loadimgbeforeupload5 = function(event) {-->
                <!--                    var output = document.getElementById('poll_edit_place');-->
                <!--                    output.src = URL.createObjectURL(event.target.files[0]);-->
                <!--                    output.onload = function() {-->
                <!--URL.revokeObjectURL(output.src) // free memory-->
                <!--                    }-->
                <!--                };-->
                <!--            </script>-->
                <!--        </div>-->

                <!--        <input class="group-name" type="text" placeholder="Question" name="poll_edit_question" id="poll_edit_question">-->
                <!-- <h2 style="text-align:center;margin:10px;">Participates</h2> -->
                <!--        <input class="group-name" name="poll_edit_tag" id="poll_edit_tag" placeholder="Category" type="text">-->

                <!--        <div id="edit_poll_options_place">-->
                <!--            <input name="poll_edit_options[]" placeholder="Option" type="text">-->
                <!--            <input name="poll_edit_options[]" placeholder="Option" type="text">-->
                <!--        </div>-->

                <!--    </form>-->

                <!--    <div class="chat-contact-add-participate" onclick="add_polling_option('edit_poll_options_place','poll_edit_options');">-->
                <!--        + ADD OPTION-->
                <!--    </div>-->

                <!--    <div class="cc-action-btn-container">-->
                <!--        <div class="cc-cancel-btn" onclick="toggle_poll_edit()">CANCEL</div>-->
                <!--        <div class="cc-save-btn" onclick="post_edit_poll();">DONE</div>-->
                <!--    </div>-->
                <!--</div>-->

                <!-- Edit Polling End -->

            </div>

            <!-- Betting Tab -->

            <div class="chat-tab" id="chat-tab-bettings">
                <div class="cc-height-fill">

                    <div class="ongoing-betting-list">
                        <?php if ($current_user_role != 0) : ?>
                            <div class="chat-contact-add-participate" onclick="toggle_bet_new();">
                                New Betting
                            </div>
                        <?php endif; ?>

                        <div id="cc_betting_list"></div>

                    </div>

                    <div class="cc-height-fill cc-second-panel" id="betting-info-panel">
                        <div>
                            <div class="back-btn-container" onclick="toggle_bet_preview()"><i class="fa fa-angle-left"></i> Back</div>
                            <div class="back-btn-container view-top-betters" onclick="toggle_bet_toppers()"><i class="fa fa-star"></i> Top Betters</div>
                        </div>
                        <div class="betting-info-card">
                        </div>
                        <div class="betting-info-numer">
                        </div>

                        <div class="bet-ask-space">
                            <div class="bet-ask-history" id="bet-ask-history">
                            </div>

                            <div class="chat-create bet-chat-create">
                                <input class="chat-input" id="ask-bet" type="number">
                                <button title="Ask" onclick="askbet()"><i class="fa fa-send"></i></button>
                            </div>
                        </div>

                        <div class="betting-top-betters" onclick="toggle_bet_toppers();">
                            <h3>TOP BETTERS <i class="fa fa-times"></i></h3>
                            <div id="top_betters_place"></div>
                        </div>

                    </div>

                </div>

                <!-- Create New Betting start-->
                <div class="cc-height-fill cc-second-panel" id="create-new-bet">
                    <div class="back-btn-container" onclick="toggle_bet_new();">
                        <i class="fa fa-angle-left"></i> Back
                    </div>

                    <form enctype="multipart/form-data" name="create_bet_form" id="create_bet_form" method="post">
                        <div class="group-image-container cc-full-img">
                            <img id="bet_new_place" src="https://xpoints.lk/cc/code/assets/image.png" alt="">
                            <input style="display: none;" onchange="loadimgbeforeupload4(event);" type="file" accept="image/*" name="bet_new_img" id="bet_new_img">
                            <script>
                                var loadimgbeforeupload4 = function(event) {
                                    var output = document.getElementById('bet_new_place');
                                    output.src = URL.createObjectURL(event.target.files[0]);
                                    output.onload = function() {
                                        URL.revokeObjectURL(output.src) // free memory
                                    }
                                };
                            </script>
                        </div>

                        <textarea style="width: 100%;" class="group-name" name="bet_new_desc" id="bet_new_desc" type="text" placeholder="Description"></textarea>
                        <!-- <h2 style="text-align:center;margin:10px;">Participates</h2> -->

                        <input style="border: 0;outline: 0;padding: 10px;margin-bottom: 5px;" id="bet_new_amount_min" name="bet_new_amount_min" min="1" placeholder="Min" type="number">
                        <input style="border: 0;outline: 0;padding: 10px;margin-bottom: 5px;" id="bet_new_amount_max" name="bet_new_amount_max" min="1" placeholder="Max" type="number">
                        <input style="border: 0;outline: 0;padding: 10px;margin-bottom: 5px;" id="bet_new_time" name="bet_new_time" placeholder="Expiry Date" type="datetime-local">
                    </form>

                    <div class="cc-action-btn-container">
                        <div class="cc-cancel-btn" onclick="toggle_bet_new()">CANCEL</div>
                        <div class="cc-save-btn" onclick="create_new_bet()">CREATE</div>
                    </div>
                </div>
                <!-- Create New Betting end-->

                <!-- Create New Betting start-->
                <!--<div class="cc-height-fill cc-second-panel" id="cc-edit-bet">-->
                <!--    <div class="back-btn-container" onclick="toggle_bet_edit();">-->
                <!--        <i class="fa fa-angle-left"></i> Back-->
                <!--    </div>-->

                <!--    <div class="group-image-container">-->
                <!--        <img src="data:image/png;base64,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" alt="nothing">-->
                <!--        <input style="display: none;" type="file" name="group_edit_img">-->
                <!--    </div>-->

                <!--    <textarea class="group-name" type="text" placeholder="Description"></textarea>-->
                <!-- <h2 style="text-align:center;margin:10px;">Participates</h2> -->

                <!--    <input style="border: 0;outline: 0;padding: 10px;margin-bottom: 5px;" placeholder="Min" type="number">-->
                <!--    <input style="border: 0;outline: 0;padding: 10px;margin-bottom: 5px;" placeholder="Max" type="number">-->
                <!--    <input style="border: 0;outline: 0;padding: 10px;margin-bottom: 5px;" placeholder="Date" type="datetime-local">-->

                <!--    <div class="cc-action-btn-container">-->
                <!--        <div class="cc-cancel-btn" onclick="toggle_bet_edit()">CANCEL</div>-->
                <!--        <div class="cc-save-btn">SAVE</div>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- Create New Betting end-->
            </div>

        </div>

        <div class="cc-height-fill cc-second-panel" id="cc_confirmation_popup" style="background: rgba(255, 255, 255, 0.1);backdrop-filter: blur(5px);bottom: 0;">
            <div class="cc_popup_window">
                <h4></h4>
                <div class="cc_popup_options">
                    <div class="cc_no" onclick="comfirm_action_popup('','','')"></div>
                    <div class="cc_yes"></div>
                </div>
            </div>
        </div>

        <div class="cc-height-fill cc-second-panel" id="cc_success_popup" style="background:lightgray;bottom: 0;">
            <div class="cc_popup_window">
                <h4 style="text-align:center;">Success</h4>
                <div class="cc_popup_options">
                    <div class="cc_yes" onclick="$('#cc_success_popup').toggleClass('visible')">OK</div>
                </div>
            </div>
        </div>

    </div>

    <?php if ($current_user_id != 0) : ?>
        <div class="chat-show-btn" title="Chat Here" onclick="togglechatbox();">
            <i class="fa fa-comments"></i>
            <!--<span class="chat-new-notification">2</span>-->
        </div>
    <?php endif; ?>
</div>

<!-- <script src="./data.json"></script> -->
<?php if ($current_user_id != 0) : ?>
    <script src="/cc/code/app.js"></script>
<?php endif; ?>