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
    $(document).on('click', '.chat-list-profile', function () {

        var element = $(this);
        var data = userData(element);
        onlinePresence(data['id'], data['created']);
        unSeenMessages(data['id'], data['receiverId']);
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

        let response = new Promise((resolve, reject) => {

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
                    console.log('error', error);
                })
        });
    }
});

// user online offline status update
$(document).ready(function () {
    userStatus();
});

function userStatus() {
    if (window.location.href == APP_URL + '/chat') {

        const currentTime = new Date().getTime();
        const timestamp = currentTime;

        let response = new Promise((resolve, reject) => {
            db.ref(`/OnlinePresence/${senderId}`).set({
                status: 'online',
                time: timestamp,
                id: senderId,
            }).then(() => console.log('Updated'))
                .catch(function (error) {
                    reject(error)
                    console.log('error', error);
                })
        });
    } else {
        const currentTime = new Date().getTime();
        const timestamp = currentTime;

        let response = new Promise((resolve, reject) => {
            db.ref(`/OnlinePresence/${senderId}`).set({
                status: 'offline',
                time: timestamp,
                id: senderId,
            }).then(() => console.log('Updated'))
                .catch(function (error) {
                    reject(error)
                    console.log('error', error);
                })
        });
    }
}

// online Presence
function onlinePresence(user, created) {

    let response = new Promise((resolve, reject) => {
        db.ref(`/OnlinePresence/${user}`).set({
            status: 'online',
            time: created,
            id: user,
        }).then(() => console.log('Updated'))
            .catch(function (error) {
                reject(error)
                console.log('error', error);
            })
    });
}

// total number of unseen messages
function unSeenMessages(sender, reciever) {
    alert('dzfxsdf');
    // if (fireBaseListener1234)
    //     fireBaseListener1234()

    // let response = new Promise((resolve, reject) => {
    // alert(reciever);
    var fireBaseListener1234 = db.ref('messeges/' + `${sender}_${reciever}`).on("child_added", (snap) => {
        var count = 0;
        var message = snap;

        if (message.val().isSeen == 'false') {
            count += 1;
        }
        console.log(count, "Dsfsdfsdfsdfsdfdsf");
        alert(count);
        return;
    });

    // })

}
document.getElementById("chatForm").addEventListener("submit", submitForm);

function submitForm(e) {
    e.preventDefault();
    var messagedata = messageData();
    if (messagedata.msg != '') {

        sendMessage(messagedata['sender'], messagedata['reciever'], messagedata['created'], messagedata, 'true');
        sendMessage(messagedata['reciever'], messagedata['sender'], messagedata['created'], messagedata, 'false');
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

    // var img = '';
    const isSeen = '';
    var sender = '';
    var reciever = '';

    const messageData = {

        sender: SenderId,
        reciever: ReceiverId,
        msg: message,
        // img: img,
        isSeen: 'true',
        a_remove: '0',
        created: created,
    }

    return messageData;
}


function sendMessage(sender, reciever, created, data, check) {

    jQuery('#message').val('');

    let response = new Promise((resolve, reject) => {
        var test = 'messeges/' + `${sender}_${reciever}`;
        db.ref(test).push({
            sender: data['sender'],
            reciever: data['reciever'],
            msg: data['msg'],
            // img: img,
            isSeen: check,
            a_remove: '0',
            created: data['created'],
        }).then(() => {
            console.log('Message updated.');
            // loadMessages(sender, reciever, img);
            resolve(); // Resolve the promise on success
        }).catch((error) => {
            reject(error);
            console.log('error', error);
        });
    });

}

// messsage isseen update
function messageUpdate(message, check) {
    // console.log(message.key);
    // alert(message.key);
    var sender = parseInt(message.val().reciever);
    var reciever = parseInt(message.val().sender);
    messageId = message.key;
    // console.log(sender, reciever, check);
    // alert(sender);
    let response = new Promise((resolve, reject) => {
        // var messagePath = 'messages/' + `${sender}_${receiver}/` + messageId;
        var test = 'messeges/' + `${sender}_${reciever}/` + messageId;
        console.log(sender, reciever, check);
        // alert(reciever);
        db.ref(test).update({
            isSeen: check,
        }).then(() => {
            console.log('Message updated.');
            // loadMessages(sender, reciever, img);
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
    getMessages(element);
});


function getMessages(element) {
    var sender = element.attr('data-senderId');
    var reciever = element.attr('data-receiverId');
    userImage = element.find('img').attr('src');
    var name = element.find('p').html()

    var Chatimg = `<img src="${userImage}" class="chat-pro-img"><span>${name}</span>`;
    $('.chat-product-profile').html(Chatimg);
    // var reciever = jQuery()
    // $(document).find('.currentUser').text(name)
    loadMessages(sender, reciever, userImage);
}


fireBaseListener = null;

// fireBaseListener = null;
function loadMessages(sender, reciever, userImage) {
    if (!sender) {

        return;
    }

    jQuery('#chatWindow').html('');
    if (fireBaseListener)
        fireBaseListener();

    // var messageList = '',
    //     allSenders = [];
    // messageList += '<p class="text-center date_cht">Today, ' + moment(new Date()).format('MMMM MM') + '</p>'
    // messageList += '<p class="text-center date_cht"></p>';

    // snap.forEach(message => {
    //     console.log('sadfasd', message.val(), "sdfsfsdfsd");

    var fireBaseListener = db.ref('messeges/' + `${sender}_${reciever}`).on("child_added", (snap) => {

        var message = snap;
        var messageList = '';

        let date = moment(new Date()).format('YYYY-MM-DD');

        var msgDate = moment(new Date(parseInt(message.val().created))).format('YYYY-MM-DD');

        if (date == msgDate) {
            var time = moment(new Date(parseInt(message.val().created))).format('h:mm a');

        } else {
            var time = msgDate;
        }

        if (parseInt(authUserId) == parseInt(message.val().sender)) {

            messageList += '<div class="chat-screen-right-wrapper">';
            messageList += '<div class="chat-screenmsg-wrapper">';
            messageList += '<div class="chat-screen-name-time">';
            messageList += '<span>' + time + '</span><p>You</p></div>';

            // if (attachment)
            //     messageList += '<div class="msg_media"><img src="' + attachment + '"></div>';
            if (message.val().msg)
                messageList += '<div class="chat-txt-box">' + parseMessage(urlify(message.val().msg)) + '</div></div>';
            messageList += '<div class="chat-screen-img"><img src="' + authUserprofile + '?id=' + message.val() + '"></div>';
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

            // issen update
            if (parseInt(authUserId) == parseInt(message.val().reciever)) {
                messageUpdate(message, 'true');
            }
        }


        // });

        jQuery('#chatWindow').append(messageList);
    });



}