/*GOOGLE FIREBASE CHAT*/
firebase.initializeApp(firebaseConfig);
const db = firebase.database();
// const db = firebase.realtime();


// const currentTime = new Date().getTime();
var dbRef = db.ref(`/users/` + senderId);
dbRef.once("value").then(snap => {
    snap.forEach(message => {
        console.log(message.key, message.val());

    });
}).catch(error => {
    console.log("error".error)
})



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

$(document).ready(function () {
    $('.chat-list-profile').on('click', function () {

        var element = $(this);
        var data = userData(element);
        // console.log(data['id']);
        // alert(data['id']);
        createUser(data['id'], data['receiverId'], data['created'], data['image'], data['name'], data);
        createUser(data['receiverId'], data['id'], data['created'], data['authprofile'], data['authName'], data);

    })

    function userData(element) {
        const adminId = element.attr("data-senderId");
        const receiverId = element.attr("data-receverId");
        var receverName = element.attr("data-receverName");
        var receverImage = element.attr("data-receverImage");
        var authProfile = element.attr("data-profile");
        var authName = element.attr("data-name");

        var msgValue = 'Image';
        const currentTime = new Date().getTime();
        const timestamp = currentTime;

        const attachmentInput = document.getElementById("attachment");
        const id = '';
        const isSeen = '';

        var dbRef = db.ref(`/users`);
        const messageData = {

            id: adminId,
            name: receverName,
            authName: authName,
            image: receverImage,
            authprofile: authProfile,
            lastmsg: msgValue,
            created: timestamp,
            sender: adminId,
            receiverId: receiverId,
            isSeen: '1',
            a_remove: '0',
            msgtype: 'one',
        }
        return messageData;
    }

    function createUser(adminId, receiverId, timestamp, image, name, data) {

        // jQuery('#sendMessage').prop('disabled', true);
        let response = new Promise((resolve, reject) => {
            console.log(data);
            db.ref(`users/${adminId}`).child(`${receiverId}`).set({
                id: adminId,
                name: name,
                image: image,
                lastmsg: data.lastmsg,
                created: timestamp,
                sender: adminId,
                isSeen: '1',
                a_remove: '0',
                msgtype: 'one',
            }).then(() => window.location.href = APP_URL + '/chat')
                .catch(function (error) {
                    reject(error)
                    // jQuery('#sendMessage').prop('disabled', false);
                    console.log('error', error);
                })
        });
    }
});

document.getElementById("chatForm").addEventListener("submit", submitForm);

function submitForm(e) {
    // console.log('submit form')
    e.preventDefault();



    //console.log(messageData, messageData.message, 'data')
    // if (attachmentInput.value == '' && message == '') {
    //     return;
    // }
    // console.log(messageData, 'DATA');
    // jQuery('#sendMessage').prop('disabled', true);
    // jQuery('#sendMessage').html(loaderIcon);

    // const attachmentInput = document.getElementById("attachment");

    // if (typeof attachmentInput.value != 'undefined' && attachmentInput.value != '') {

    //     jQuery('#sendMessage').prop('disabled', true);
    //     var formData = new FormData();
    //     formData.append('attachment', attachmentInput.files[0]);
    //     formData.append('Id', Id);
    //     chatImage(formData, chat_image_store_url)
    //     async function chatImage(formData, chat_image_store_url) {
    //         const response = await fetch(chat_image_store_url, {
    //             method: 'POST',
    //             body: formData,
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //         })
    //             .then((response) => {
    //                 if (response.ok) {
    //                     return response.json();
    //                 }
    //                 return response.json().then(function (errorBody) {
    //                     // console.log(errorBody.message);
    //                     throw new Error(errorBody.message);
    //                 });

    //             })
    //             .then((responseJson) => {
    //                 jQuery('#message').val(responseJson.data.name);
    //                 messageData.attachment = responseJson.data.url;
    //                 jQuery('#message').val('')
    //                 jQuery('#attachment').val('')
    //                 console.log("messageData", messageData);
    //                 sendMessage(chatId, timestamp, messageData);

    //             })
    //             .catch((error) => {
    //                 return iziToast.error({
    //                     title: 'Error!',
    //                     message: error.message,
    //                     position: 'topRight'
    //                 });
    //             });
    //         jQuery('#sendMessage').prop('disabled', false);
    // }
    // } else {

    if (messageData.message != '') {

        //console.log("messageData",messageData);
        var messagedata = messageData();

        sendMessage(messagedata['sender'], messagedata['reciever'], messagedata['created'], messagedata['img'], messagedata);
        sendMessage(messagedata['reciever'], messagedata['sender'], messagedata['created'], messagedata['img'], messagedata);
    }
}
// }

function messageData() {

    element = jQuery(".chat-list.activecht");
    var SenderId = element.attr("data-senderId");
    var ReceiverId = jQuery(".chat-list.activecht").attr("data-receiverId");
    var img = jQuery(".chat-list.activecht").find('img').attr("src");

    const currentTime = new Date().getTime();
    const created = currentTime.toString();
    // const msgValue = document.getElementById("message").text();
    // const msgValue = jQuery(document).find("#message").text();

    var message = $('textarea#message').val();

    // messageInput.addEventListener('keydown', handleEnter);
    const attachmentInput = document.getElementById("attachment");
    // const message = parseHtml(msgValue);

    //console.log(message, 'message');
    // var img = '';
    const isSeen = '';
    var sender = '';
    var reciever = '';

    const messageData = {

        sender: SenderId,
        reciever: ReceiverId,
        msg: message,
        img: img,
        isSeen: 'isSeen',
        a_remove: '0',
        created: created,
    }

    return messageData;
}

function sendMessage(sender, reciever, created, img, data) {

    jQuery('#message').val('');

    let response = new Promise((resolve, reject) => {
        var test = 'messages/' + `${sender}_${reciever}`;
        db.ref(test).push(data).then(() => {
            console.log('Message updated.');
            loadMessages(sender, reciever, img);
            resolve(); // Resolve the promise on success
        }).catch((error) => {
            reject(error);
            console.log('error', error);
        });
    });

}

$(document).on('click', '.chat-list', function () {

    var classElement = jQuery('.chat-list');
    classElement.removeClass('activecht');
    // classElement.find('p.chat-txt').removeClass('chatmsg');
    // classElement.find('.time-count-deta span').removeClass('chatdate');

    var element = jQuery(this);
    element.addClass('activecht');
    // element.find('p.chat-txt').addClass('chatmsg'); 
    // element.find('.time-count-deta span').addClass('chatdate'); 

    // jQuery('#sendMessage').attr('disabled', false);

    // if ($('.chat-list-profile.activecht').attr('data-chatId') == '')
    //     insertChatId();
    // var formData = new FormData();
    // formData.append('receiverId', jQuery(".chat-list-profile.activecht").attr('data-receiverId'));
    // formData.append('orderId', jQuery(".chat-list-profile.activecht").attr('data-orderId'));
    // //console.log(get_chat_url)
    getMessages(element);
});

function getMessages(element) {
    var sender = element.attr('data-senderId');
    var reciever = element.attr('data-receiverId');
    userImage = element.find('img').attr('src');
    var name = element.find('p').html()
    // alert(name);
    var Chatimg = `<img src="${userImage}" class="chat-pro-img"><span>${name}</span>`;
    $('.chat-product-profile').html(Chatimg);
    // var reciever = jQuery()
    // $(document).find('.currentUser').text(name)
    loadMessages(sender, reciever, userImage);
}


// fireBaseListener = null;
function loadMessages(sender, reciever, userImage) {
    if (!sender) {
        //console.log('HELLLLL')
        return;
    }
    //console.log('hiiii',chatId);
    jQuery('#chatWindow').html('');
    // if (fireBaseListener)
    //     fireBaseListener();
    // fireBaseListener = db.collection(chatId).onSnapshot((snapshot) => {
    var messageList = '',
        allSenders = [];
    messageList += '<p class="text-center date_cht">Today, ' + moment(new Date()).format('MMMM MM') + '</p>'
    messageList += '<p class="text-center date_cht"></p>';


    // snapshot.docChanges().forEach((change) => {
    //     //console.log("change",change);
    //     if (change.type === "added") {

    // let sender = change.doc.data().senderId;
    // let message = change.doc.data().message;
    // let currentTime = change.doc.data().currentTime;
    // let attachment = ('attachment' in change.doc.data()) ? change.doc.data().attachment : '';



    var dbRef = db.ref('messages/' + `${sender}_${reciever}`);
    dbRef.once("value").then(snap => {
        snap.forEach(message => {
            var msgDate = moment(new Date(parseInt(message.val().created))).format('YYYY-MM-DD');
            console.log(msgDate);
            let date = moment(new Date()).format('YYYY-MM-DD');
            if (date == msgDate) {
                var time = moment(new Date(parseInt(message.val().created))).format('h:mm a');

            } else {
                var time = msgDate;
            }

            // console.log(sender, message.val().sender, 'there');
            // alert(sender, message.val().sender);
            if (parseInt(authUserId) == parseInt(message.val().sender)) {
                // console.log('hey')
                messageList += '<div class="chat-screen-right-wrapper">';
                messageList += '<div class="chat-screenmsg-wrapper">';
                messageList += '<div class="chat-screen-name-time">';
                messageList += '<span>' + time + '</span><p>You</p></div>';

                // if (attachment)
                //     messageList += '<div class="msg_media"><img src="' + attachment + '"></div>';
                if (message.val().msg)
                    messageList += '<div class="chat-txt-box">' + parseMessage(urlify(message.val().msg)) + '</div></div>';
                messageList += '<div class="chat-screen-img"><img src="' + userImage + '?id=' + message.val() + '"></div>';
                messageList += '</div>';


            }
            else {
                messageList += '<div class="chat-screen-left-wrapper">';
                messageList += '<div class="chat-screen-img"><img src="' + userImage + '?id=' + message.val() + '"></div>';
                messageList += '<div class="msg-data"><div class="pro_name d-flex">';
                // messageList += `<p>USERNAME-${message.val().name}</p>`;
                messageList += '<span>' + time + '</span></div>';
                // if (attachment)
                //     messageList += '<div class="msg_media"><img src="' + attachment + '"></div>';
                if (message.val().msg)
                    messageList += '<div class="chat-txt-box">' + parseMessage(urlify(message.val().msg)) + '</div>';
                messageList += '</div></div>';

            }


        });
        jQuery('#chatWindow').append(messageList);
    }).catch(error => {
        console.log("error".error)
    })
    // console.log(dbRef, "hererere");
    // alert(dbRef);
    // var msgDate = moment(new Date(currentTime)).format('YYYY-MM-DD');
    // let date = moment(new Date()).format('YYYY-MM-DD');
    // if (date == msgDate) {
    //     var time = moment(new Date(currentTime)).format('h:mm a');

    // } else {
    //     var time = msgDate;
    // }
    // // console.log(sender, message,currentTime )

    // //console.log(sender, senderId)
    // if (sender == senderId) {
    //     // console.log('hey')
    //     messageList += '<div class="chat-screen-right-wrapper">';
    //     messageList += '<div class="chat-screenmsg-wrapper">';
    //     messageList += '<div class="chat-screen-name-time">';
    //     messageList += '<span>' + time + '</span><p>You</p></div>';
    //     if (attachment)
    //         messageList += '<div class="msg_media"><img src="' + attachment + '"></div>';
    //     if (message)
    //         messageList += '<div class="chat-txt-box">' + parseMessage(urlify(message)) + '</div></div>';
    //     messageList += '<div class="chat-screen-img"><img src="' + userImage + '?id=' + sender + '"></div>';
    //     messageList += '</div>';

    // }
    // else {
    //     messageList += '<div class="chat-screen-left-wrapper">';
    //     messageList += '<div class="chat-screen-img"><img src="' + userImage + '?id=' + sender + '"></div>';
    //     messageList += '<div class="msg-data"><div class="pro_name d-flex">';
    //     messageList += `<p>{{USERNAME-${sender}}}</p>`;
    //     messageList += '<span>' + time + '</span></div>';
    //     if (attachment)
    //         messageList += '<div class="msg_media"><img src="' + attachment + '"></div>';
    //     if (message)
    //         messageList += '<div class="chat-txt-box">' + parseMessage(urlify(message)) + '</div>';
    //     messageList += '</div></div>';


    // }
    //messageList += `<p>{{USERNAME-${sender}}}</p>`;
    // allSenders.push(sender);

    // return false;
    // } else if (change.type === "modified") {
    //     // console.log('modified')
    // } else if (change.type === "removed") {
    //     // console.log('removed')
    // }
    // });

    //jQuery('#chatWindow').append(messageList);
    //_lastMsgUpdate(chatId);
    // console.log(allSenders);
    // response = fetch(get_user_names, {
    //     method: 'POST',
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    //         'Content-Type': 'application/json'
    //     },
    //     body: JSON.stringify({
    //         user_id: allSenders
    //     })
    // })
    //     .then((response) => {
    //         if (response.ok) {
    //             return response.json();
    //         }
    //         throw new Error('error');
    //     })
    //     .then((responseJson) => {
    //         var data = responseJson.data;
    //         //console.log("data",data);
    //         for (const index in data) {
    //             messageList = messageList.replace(new RegExp(`{{USERNAME-${index}}}`, "g"), data[index]);
    //         }

    //         jQuery('#chatWindow').append(messageList);
    //         _lastMsgUpdate(chatId);
    //         //jQuery('#chatWindow').scrollTop(jQuery('#chatWindow')[0].scrollHeight);

    //     });
    // });


}