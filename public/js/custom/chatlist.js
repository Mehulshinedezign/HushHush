var dbRef = db.ref(`/users/` + senderId);

let first = true;
let response = new Promise((resolve, reject) => {
    dbRef.once("value").then(snap => {
        snap.forEach(message => {

            let activeClass = first ? 'activecht' : '';
            $('.chatlist').append(`<li>
                <div class="chat-list ${activeClass}"
                    data-receiverId=${message.key}
                    data-senderId="${message.val().id}">
                    <div class="chat-profile-img-box">
                        <div class="chat-profile-left">
                            <div class="chat-profile-img">
                                <img src="${message.val().image}" class="img">
                            </div>
                            <p class="getname">
                                ${message.val().name}
                            </p>
                        </div>
                        <p class='d-none count-msg' id="${message.key + 'count'}">0</p>
                    </div> 
                </div>
            </li>`);
            first = false;
        });

    }).then(() => getFirstChatData())
        .catch(error => {
            console.log("error".error)
        })

});

// show first chat active and load all messages
function getFirstChatData() {
    element = $('.activecht');
    userliststatus();
    getMessages(element);
    checkmessagecount();
}


function checkmessagecount() {
    var elements = $('.chatlist').find('li');
    elements.each(function (index, element) {
        var list = $(element).find('div.chat-list');
        reciever = parseInt(list.attr('data-senderId'));
        sender = parseInt(list.attr('data-receiverId'));

        unSeenMessages(sender, reciever);

    })
}

// total number of unseen messages
const unseenMessageListeners = {}; // Object to store listener references by chat key

function unSeenMessages(sender, receiver) {
    const chatKey = `${receiver}_${sender}`;

    // Remove existing listener if it exists
    if (unseenMessageListeners[chatKey]) {
        db.ref('messeges/' + chatKey).off('child_added', unseenMessageListeners[chatKey]);
    }

    // Define new listener function
    let count = 0;
    const listener = db.ref('messeges/' + chatKey).on('child_added', (snap) => {
        const message = snap.val();

        if (message.isSeen === 'false') {   
            count += 1;
        }

        console.log(count, "Unseen message count");

        // Update the count on the UI
        if(count > 0){

            $("#" + sender + 'count').text(count);
            $("#" + sender + 'count').removeClass('d-none');
        }else{
            $("#" + sender + 'count').addClass('d-none');
        }
    });

    // Store the new listener function
    unseenMessageListeners[chatKey] = listener;
}




const onlineStatusListeners = {}; // Object to store listener references by receiver ID

function userliststatus() {
    // Remove all existing listeners before adding new ones
    for (const receiverId in onlineStatusListeners) {
        if (onlineStatusListeners[receiverId]) {
            db.ref(`/OnlinePresence/${receiverId}`).off('value', onlineStatusListeners[receiverId]);
        }
    }
    // Clear the object of listeners
    Object.keys(onlineStatusListeners).forEach(key => delete onlineStatusListeners[key]);

    // Add new listeners
    $('.chatlist').find('li').each(function (index, element) {
        var list = $(element).find('div:first');
        var receiverId = parseInt(list.attr('data-receiverid'));

        // Define new listener function
        const listener = db.ref(`/OnlinePresence/${receiverId}`).on('value', (snapshot) => {
            const status = snapshot.val().status;
            const onlineClass = status === 'online' ? 'online' : 'offline';
            const onlineRClass = status === 'online' ? 'offline' : 'online';
            const pertcularPlace = `.chat-list[data-receiverid="${snapshot.val().id}"]`;

            $(pertcularPlace).addClass(onlineClass);
            $(pertcularPlace).removeClass(onlineRClass);
        });

        // Store the listener function
        onlineStatusListeners[receiverId] = listener;
    });
}


// chat search form submit
$("#searchmember").on("submit", function (e) {
    e.preventDefault();

    if ($("input[name='search']").val()) {
        var get_search_chat = $("input[name='search']").val();

        dbRef.once("value").then(snap => {
            snap.forEach(message => {
                if (message.val().name == get_search_chat) {
                    let activeClass = first ? 'activecht' : '';

                    $('.chatlist').text('');
                    $('.chatlist').append(`<li>
                    <div class="chat-list ${activeClass}"
                        data-receiverId=${message.key}
                        data-senderId="${message.val().id}">
                        <div class="chat-profile-img-box">
                            <div class="chat-profile-img">
                                <img src="${message.val().image}" class="img">

                            </div>
                            <p class="getname">
                        ${message.val().name}
                            </p>
                        </div>
                    </div>
                </li>`);
                    first = false;
                }

            }).than();
        })
    }
    else {
        $('.chatlist').text('');
        dbRef.once("value").then(snap => {
            snap.forEach(message => {
                let activeClass = first ? 'activecht' : '';
                $('.chatlist').append(`<li>
                    <div class="chat-list ${activeClass}"
                        data-receiverId=${message.key}
                        data-senderId="${message.val().id}">
                        <div class="chat-profile-img-box">
                            <div class="chat-profile-img">
                                <img src="${message.val().image}" class="img">

                            </div>
                            <p class="getname">
                        ${message.val().name}
                            </p>
                        </div>
                    </div>
                </li>`);
                first = false;

            }).than();
        })
    }
});

