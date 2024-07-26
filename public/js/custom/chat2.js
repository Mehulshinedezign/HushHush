/*GOOGLE FIREBASE CHAT*/
firebase.initializeApp(firebaseConfig);
const db = firebase.database();
// const db = firebase.realtime();


// const currentTime = new Date().getTime();
var dbRef = db.ref(`/users`);
dbRef.once("value").then(snap => {
    snap.forEach((message) => {
        console.log(message.val());
    });
}).catch(error => {
    // console.log("error".error)
})


// // document.getElementById("chatForm").addEventListener("submit", submitForm);

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

        // Id = jQuery(".chat-list-profile.activecht").attr("data-id");
        const adminId = jQuery(".chat-list-profile").attr("data-senderId");
        const receiverId = jQuery(".chat-list-profile").attr("data-receverId");
        var adminname = jQuery(".chat-list-profile").attr("data-adminName");
        var adminimage = jQuery(".chat-list-profile").attr("data-adminimage");
        //console.log("chatId",chatId);
        var msgValue = 'Image';
        const currentTime = new Date().getTime();
        const timestamp = currentTime.toString();
        // const message = document.getElementById("message");
        // messageInput.addEventListener('keydown', handleEnter);
        const attachmentInput = document.getElementById("attachment");
        // const message = parseHtml(messageInput.value);
        // messageInput.value = '';
        const id = '';
        const isSeen = '';
        //console.log(message, 'message');
        var dbRef = db.ref(`/users`);
        const messageData = {

            id: adminId,
            name: adminname,
            image: adminimage,
            lastmsg: msgValue,
            created: timestamp,
            sender: adminId,
            isSeen: '1',
            a_remove: '0',
            msgtype: 'one',
        }
        sendMessage(adminId, receiverId, timestamp, messageData);

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
        //                 sendMessage(SenderId, timestamp, messageData);

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

        //     if (messageData.message != '') {

        //         //console.log("messageData",messageData);
        //         sendMessage(SenderId, timestamp, messageData);
        //     }
        // }

    })

    function sendMessage(adminId, receiverId, timestamp, data) {

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