/*GOOGLE FIREBASE CHAT*/
firebase.initializeApp(firebaseConfig);
// const db = firebase.database();
const db = firebase.firestore();

document.getElementById("chatForm").addEventListener("submit", submitForm);

jQuery('#attachment').on('change', function () {
    var imageName = jQuery(this).val().replace(/.*(\/|\\)/, '');
    jQuery('#message').val(imageName);

})

$(document).ready(function () {
    $('.commentarea').keydown(function (e) {
        if (e.keyCode == 13 && !e.shiftKey) {
            submitForm(e);
        }
    });
});

// const pasteIntoInput = (el, text) => {
//     el.focus();
//     if (typeof el.selectionStart === "number" &&
//       typeof el.selectionEnd === "number") {
//       const val = el.value;
//       const selStart = el.selectionStart;
//       el.value = val.slice(0, selStart) + text + val.slice(el.selectionEnd);
//       el.selectionEnd = el.selectionStart = selStart + text.length;
//     } else if (typeof document.selection !== "undefined") {
//       const textRange = document.selection.createRange();
//       textRange.text = text;
//       textRange.collapse(false);
//       textRange.select();
//     }
//   }
//   const handleEnter = (evt) => {
//         if (evt.keyCode === 13 && evt.shiftKey) {
//             if (evt.type === "keypress") {
//                 evt.preventDefault();
//                 pasteIntoInput(evt.target, "\n");
//             }
//         }
//     }

function parseHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function parseMessage(text) {
    return text
        .replace(/(?:\r\n|\r|\n)/g, '<br>');
}

function urlify(text) {
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, function (url) {
        return '<a href="' + url + '" target="_blank" class="message-link">' + url + '</a>';
    });
}

function submitForm(e) {
    // console.log('submit form')
    e.preventDefault();
    // console.log(typeof(chatId), 'helllo')
    // if( typeof(chatId) == 'undefined' ){
    //     Id = jQuery("input[name='id']").val();
    //     chatId = jQuery("input[name='ChatId']").val();
    //     ReceiverId = jQuery("input[name='ReceiverId']").val();
    //     console.log("chatId",chatId);
    //     console.log("ReceiverId",ReceiverId);
    // }

    Id = jQuery(".chat-list-profile.activecht").attr("data-id");
    chatId = jQuery(".chat-list-profile.activecht").attr("data-chatId");
    ReceiverId = jQuery(".chat-list-profile.activecht").attr("data-receiverid");
    //console.log("chatId",chatId);
    //console.log("ReceiverId",ReceiverId);
    const currentTime = new Date().getTime();
    const timestamp = currentTime.toString();
    const messageInput = document.getElementById("message");
    // messageInput.addEventListener('keydown', handleEnter);
    const attachmentInput = document.getElementById("attachment");
    const message = parseHtml(messageInput.value);
    messageInput.value = '';
    //console.log(message, 'message');

    const messageData = {
        attachment: '',
        currentTime: currentTime,
        message: message,
        receiverId: ReceiverId,
        senderId: senderId,
    }
    //console.log(messageData, messageData.message, 'data')
    // if (attachmentInput.value == '' && message == '') {
    //     return;
    // }
    // console.log(messageData, 'DATA');
    // jQuery('#sendMessage').prop('disabled', true);
    // jQuery('#sendMessage').html(loaderIcon);


    if (typeof attachmentInput.value != 'undefined' && attachmentInput.value != '') {

        jQuery('#sendMessage').prop('disabled', true);
        jQuery('#message').val('');
        var formData = new FormData();
        formData.append('attachment', attachmentInput.files[0]);
        formData.append('Id', Id);
        chatImage(formData, chat_image_store_url)
        async function chatImage(formData, chat_image_store_url) {
            const response = await fetch(chat_image_store_url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })
                .then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    return response.json().then(function (errorBody) {
                        // console.log(errorBody.message);
                        throw new Error(errorBody.message);
                    });

                })
                .then((responseJson) => {
                    jQuery('#message').val(responseJson.data.name);
                    messageData.attachment = responseJson.data.url;
                    jQuery('#message').val('')
                    jQuery('#attachment').val('')
                    console.log("messageData", messageData);
                    sendMessage(chatId, timestamp, messageData);

                })
                .catch((error) => {
                    return iziToast.error({
                        title: 'Error!',
                        message: error.message,
                        position: 'topRight'
                    });
                });
            jQuery('#sendMessage').prop('disabled', false);
        }
    } else {

        if (messageData.message != '') {

            //console.log("messageData",messageData);
            sendMessage(chatId, timestamp, messageData);
        }
    }
}

function sendMessage(chatId, timestamp, data) {
    jQuery('#sendMessage').prop('disabled', true);
    let response = new Promise((resolve, reject) => {
        console.log(chatId);
        db.collection(chatId)
            .doc(timestamp)
            .set(data)
            .then(function (docRef) {
                jQuery('#sendMessage').prop('disabled', false);
                // jQuery('#sendMessage').html('Send');
                //dropToBottom('#chatWindow');
            })
            .catch(function (error) {
                reject(error)
                jQuery('#sendMessage').prop('disabled', false);
                console.log('error', error);
            })
    });
}

fireBaseListener = null;
function loadMessages(chatId) {
    if (!chatId) {
        //console.log('HELLLLL')
        return;
    }
    //console.log('hiiii',chatId);
    jQuery('#chatWindow').html('');
    if (fireBaseListener)
        fireBaseListener();
    fireBaseListener = db.collection(chatId).onSnapshot((snapshot) => {
        var messageList = '',
            allSenders = [];
        // messageList += '<p class="text-center date_cht">Today, '+moment(new Date()).format('MMMM MM')+'</p>'
        messageList += '<p class="text-center date_cht"></p>';
        snapshot.docChanges().forEach((change) => {
            //console.log("change",change);
            if (change.type === "added") {

                let sender = change.doc.data().senderId;
                let message = change.doc.data().message;
                let currentTime = change.doc.data().currentTime;
                let attachment = ('attachment' in change.doc.data()) ? change.doc.data().attachment : '';
                var msgDate = moment(new Date(currentTime)).format('YYYY-MM-DD');
                let date = moment(new Date()).format('YYYY-MM-DD');
                if (date == msgDate) {
                    var time = moment(new Date(currentTime)).format('h:mm a');

                } else {
                    var time = msgDate;
                }
                // console.log(sender, message,currentTime )

                //console.log(sender, senderId)
                if (sender == senderId) {
                    // console.log('hey')
                    messageList += '<div class="msg-send">';
                    messageList += '<div class="msg-data"><div class="pro_name d-flex justify-content-end"><span>' + time + '</span><p>You</p></div>';
                    if (attachment)
                        messageList += '<div class="msg_media"><img src="' + attachment + '"></div>';
                    if (message)
                        messageList += '<div class="msg msg_sender">' + parseMessage(urlify(message)) + '</div></div>';
                    messageList += '<div class="msg-profile"><img src="' + userImage + '?id=' + sender + '"></div>';
                    messageList += '</div>';
                }
                else {
                    messageList += '<div class="msg-received">';
                    messageList += '<div class="msg-profile"><img src="' + userImage + '?id=' + sender + '"></div>';
                    messageList += '<div class="msg-data"><div class="pro_name d-flex">';
                    messageList += `<p>{{USERNAME-${sender}}}</p>`;
                    messageList += '<span>' + time + '</span></div>';
                    if (attachment)
                        messageList += '<div class="msg_media"><img src="' + attachment + '"></div>';
                    if (message)
                        messageList += '<div class="msg msg-get">' + parseMessage(urlify(message)) + '</div>';
                    messageList += '</div></div>';
                }
                //messageList += `<p>{{USERNAME-${sender}}}</p>`;
                allSenders.push(sender);

                // return false;
            } else if (change.type === "modified") {
                // console.log('modified')
            } else if (change.type === "removed") {
                // console.log('removed')
            }
        });

        //jQuery('#chatWindow').append(messageList);
        //_lastMsgUpdate(chatId);
        // console.log(allSenders);
        response = fetch(get_user_names, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: allSenders
            })
        })
            .then((response) => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('error');
            })
            .then((responseJson) => {
                var data = responseJson.data;
                //console.log("data",data);
                for (const index in data) {
                    messageList = messageList.replace(new RegExp(`{{USERNAME-${index}}}`, "g"), data[index]);
                }

                jQuery('#chatWindow').append(messageList);
                _lastMsgUpdate(chatId);
                //jQuery('#chatWindow').scrollTop(jQuery('#chatWindow')[0].scrollHeight);

            });
    });


}

function _lastMsgUpdate(chatId) {
    var fireBaseListenerLastRecord = db.collection(chatId).orderBy('currentTime', 'desc').limit(1).get().then(function (prevSnapshot) {
        var lastdate_array = [];
        prevSnapshot.docChanges().forEach((change) => {
            var lastmessage = change.doc.data().message;
            var currentTime = change.doc.data().currentTime;
            var receiverId = change.doc.data().receiverId;
            var getdate = moment(new Date(currentTime)).format("h:mm a");
            lastdate_array.push({ chatid: chatId, lastmessage: lastmessage, receiverId: receiverId, date: moment(new Date(currentTime)).format('YYYY-MM-DD HH:mm:ss') });
            $('.chatmsg').text(lastmessage);
            $('.activecht').find('span').text(getdate);
        });
        response = fetch(last_msg_update_url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                data: lastdate_array
            })
        })
            .then((response) => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('error');
            })
            .then((responseJson) => {
                var data = responseJson.data;
            });
    })
}

function insertChatId() {
    var params = { 'order_id': $('.chat-list-profile.activecht').attr('data-orderId'), 'receiver_id': $('.chat-list-profile.activecht').attr('data-receiverId'), _token: $('meta[name="csrf-token"]').attr('content') };
    console.log(params);
    alert(params);
    let response = ajaxCall(chat_store_url, "post", params);
    response.then(handleStateData).catch(handleStateError)
    function handleStateData(response) {
        var element = jQuery('.chat-list-profile.activecht');
        // element.find("input[name='id']").val(response.chat.id);
        // element.find("input[name='ChatId']").val(response.chat.chatid);
        // element.find("input[name='ReceiverId']").val(response.chat.receiver_id);
        element.attr('data-chatId', response.chat.chatid);
        element.attr('data-id', response.chat.id);
        //console.log('response', response)
    }
    function handleStateError(error) {
        console.log('error', error)
    }
}

async function getMessages(get_chat_url, formData) {
    const response = await fetch(get_chat_url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    })
        .then((response) => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('error');
        })
        .then((responseJson) => {
            // console.log("responseJson", responseJson);
            let chatId = responseJson.data.getchatId;
            // console.log("chatId", chatId);
            if (!chatId)
                return;
            loadMessages(chatId);
            // _calleachId();
            jQuery('#chatUser').html(responseJson.data.chatheader);

        })
        .catch((error) => {
            console.log('error1', error);

        });

}

$(document).on('click', '.chat-list-profile', function () {
    //console.log("hello");
    var classElement = jQuery('.chat-list-profile');
    classElement.removeClass('activecht');
    // classElement.find('p.chat-txt').removeClass('chatmsg');
    // classElement.find('.time-count-deta span').removeClass('chatdate');

    var element = jQuery(this);
    element.addClass('activecht');
    // element.find('p.chat-txt').addClass('chatmsg'); 
    // element.find('.time-count-deta span').addClass('chatdate'); 

    jQuery('#sendMessage').attr('disabled', false);

    if ($('.chat-list-profile.activecht').attr('data-chatId') == '')
        insertChatId();
    var formData = new FormData();
    formData.append('receiverId', jQuery(".chat-list-profile.activecht").attr('data-receiverId'));
    formData.append('orderId', jQuery(".chat-list-profile.activecht").attr('data-orderId'));
    //console.log(get_chat_url)
    getMessages(get_chat_url, formData);
});

jQuery(document).ready(function () {
    $('.chat-list-profile.activecht').trigger('click');
    $(".chat-list-profile").click(function () {
        $("body").addClass("actvecht");
    });

});
$(document).on('click', '.backcht', function () {
    $("body").removeClass("actvecht")
});

$("#message").keypress(function (e) {
    if (e.which === 32 && !this.value.length) {
        e.preventDefault();
    }
});

// cookie start
function setCookie(key, value, expiry) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}
// cookie end

const dropToBottom = (element) => {
    setTimeout(function () {
        jQuery(element).scrollTop(jQuery(element)[0].scrollHeight);
    }, 500);
}

// if( typeof(chatId) != 'undefined' )
//     loadMessages(chatId);

// chat search form submit
$("#searchmember").on("submit", function (e) {
    e.preventDefault();
    if ($("input[name='search']").val())
        var get_search_chat = $("input[name='search']").val();
    else
        var get_search_chat = 'all';
    $.ajax({
        url: search_url + get_search_chat,
        success: function (result) {
            //console.log("result",result);
            if (result.status == true) {
                $("#chatlist").html(result.data.chatlist);
                //$('.chat-list.active').trigger('click');
            }
        }
    });
});