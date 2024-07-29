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
        createUser(data['id'], data['receiverId'], data['created'], data);
        createUser(data['receiverId'], data['id'], data['created'], data);

    })

    function userData(element) {
        const adminId = element.attr("data-senderId");
        const receiverId = element.attr("data-receverId");
        var receverName = element.attr("data-receverName");
        var receverImage = element.attr("data-receverImage");

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
            image: receverImage,
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

    function createUser(adminId, receiverId, timestamp, data) {

        jQuery('#sendMessage').prop('disabled', true);
        let response = new Promise((resolve, reject) => {
            console.log(data);
            db.ref(`users/${adminId}`).child(`${receiverId}`).set({
                id: adminId,
                name: data.name,
                image: data.image,
                lastmsg: data.lastmsg,
                created: timestamp,
                sender: adminId,
                isSeen: '1',
                a_remove: '0',
                msgtype: 'one',
            }).then(() => window.location.href = APP_URL + '/chat')
                .catch(function (error) {
                    reject(error)
                    jQuery('#sendMessage').prop('disabled', false);
                    console.log('error', error);
                })
        });
    }
});


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


    // if (typeof attachmentInput.value != 'undefined' && attachmentInput.value != '') {

    //     jQuery('#sendMessage').prop('disabled', true);
    //     jQuery('#message').val('');
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
    //     }
    // } else {

    if (messageData.message != '') {

        //console.log("messageData",messageData);
        var messagedata = messageData();

        sendMessage(messagedata['sender'], messagedata['reciever'], messagedata['created'], messagedata);
        sendMessage(messagedata['reciever'], messagedata['sender'], messagedata['created'], messagedata);
    }
    // }
}

function messageData() {

    var SenderId = jQuery(".chat-list.activecht").attr("data-senderId");
    var ReceiverId = jQuery(".chat-list.activecht").attr("data-receverId");

    const currentTime = new Date().getTime();
    const created = currentTime.toString();
    const msgValue = document.getElementById("message");
    // messageInput.addEventListener('keydown', handleEnter);
    const attachmentInput = document.getElementById("attachment");
    const message = parseHtml(msgValue.value);
    msgValue.value = '';
    //console.log(message, 'message');
    var img = '';
    const isSeen = '';
    var sender = '';
    var reciever = '';

    const messageData = {
        // attachment: '',
        // currentTime: currentTime,
        // message: message,
        // receiverId: ReceiverId,
        // senderId: senderId,

        sender: SenderId,
        reciever: ReceiverId,
        msg: msgValue,
        img: 'img',
        isSeen: 'isSeen',
        a_remove: '0',
        created: created,
    }

    return messageData;
}

function sendMessage(sender, reciever, created, data) {
    jQuery('#sendMessage').prop('disabled', true);
    let response = new Promise((resolve, reject) => {

        db.ref('messeges/' + `${sender}_${reciever}`).push({
            sender: sender,
            reciever: reciever,
            msg: data.msg,
            img: 'img',
            isSeen: '1',
            a_remove: '0',
            created: created,
        }).then(() => console.log('Message updated.'))
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
    // getMessages(get_chat_url, formData);
});