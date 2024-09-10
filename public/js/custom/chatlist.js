var dbRef = db.ref(`/users/` + senderid);

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

var unseenMessageListeners = {}; // Object to store listener references by chat key

function checkmessagecount() {
    var elements = $('.chatlist').find('li');
    elements.each(function (index, element) {
        var list = $(element).find('div.chat-list');
        reciever = parseInt(list.attr('data-senderId'));
        sender = parseInt(list.attr('data-receiverId'));

        unSeenMessages(sender, reciever);

    })
}

function unSeenMessages(sender, receiver) {
    const chatKey = `${receiver}_${sender}`;

    // Remove existing listener if it exists
    if (unseenMessageListeners[chatKey]) {
        db.ref('messeges/' + chatKey).off('child_added', unseenMessageListeners[chatKey].childAdded);
        db.ref('messeges/' + chatKey).off('child_changed', unseenMessageListeners[chatKey].childChanged);
    }

    // Initialize count variable
    let count = 0;
    let totalMessage = 0;
    // Define the child_added listener
    const childAdded = db.ref('messeges/' + chatKey).on('child_added', (snap) => {
        const message = snap.val();
        if(message.isSeen==='true')
        {
            totalMessage +=1;
        }

        if (message.isSeen === 'false') {   
            count += 1;
        }
       
        updateCountUI(sender, count);
        updateTotalUnseenMessages();    
    });

    // Define the child_changed listener
    const childChanged = db.ref('messeges/' + chatKey).on('child_changed', (snap) => {
        const message = snap.val();
        if (message.isSeen === 'true') {
            count -= 1;
           

        } else if (message.isSeen === 'false' && !message.isSeenBefore) {
            count += 1;
           
        }
        
        updateCountUI(sender, count);
        updateTotalUnseenMessages();
    });

    // Store the new listeners
    unseenMessageListeners[chatKey] = {
        childAdded,
        childChanged
    };
}
// Update the total unseen message count for the user
function updateTotalUnseenMessages() {
    let totalUnseenCount = 0;
    for (const chatKey in unseenMessageListeners) {
        const countElement = $("#" + chatKey.split('_')[1] + 'count');
        const count = parseInt(countElement.text()) || 0;
        totalUnseenCount += count;
    }

    const userElement = $('.userIconbtn');
    if (totalUnseenCount > 0) {
        userElement.text(totalUnseenCount);
    } else {
        userElement.text('');
    }
}

function updateCountUI(sender, count) {
    const countElement = $("#" + sender + 'count');
    if (count > 0) {
        countElement.text(count);
        countElement.removeClass('d-none');
    } else {
        countElement.addClass('d-none');
    }
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

$('#chatClear').on('click',function(){
    $('#searchmember').val('');
})

// chat search form submit
$("#searchmember input").on("input", function (e) {
    e.preventDefault();
    // if (search) {
        var search = $(this).val().toLowerCase(); // Normalize case to handle case-insensitive search
        if (search) {
            dbRef.orderByChild("name").startAt(search).endAt(search + "\uf8ff").once("value", function(snapshot) {
                $('.chatlist').empty(); // Clear the chat list before adding new results
                if (snapshot.exists()) {
                    snapshot.forEach(function(message) {
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
                }
            })
        // }
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
        })
    }
});

