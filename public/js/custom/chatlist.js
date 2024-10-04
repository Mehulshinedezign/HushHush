var dbRef = db.ref(`/users/` + senderid);

let first = true;
let response = new Promise((resolve, reject) => {
    dbRef.once("value").then(snap => {
        snap.forEach(message => {
            let activeClass = '';
            if(sel_reciever != "" && message.key == sel_reciever){
                activeClass = 'activecht';
            }else{
                activeClass = sel_reciever != "" ? '' :(first ? 'activecht' : '');
            }

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
            // console.log("error".error)
        })

});

// show first chat active and load all messages
function getFirstChatData() {
    element = $('.activecht');
    if(element.length===0)
    {
        $('#chatWindow').append(`<div class="no-chat-available-box"><i class="fa-solid fa-comments"></i><div class="no-chat-available">No chats available.</div></div>`);
        $('#chatForm').addClass('d-none');
    }
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

    // Initialize count variable for unseen messages
    let count = 0;

    // Define the child_added listener
    const childAdded = db.ref('messeges/' + chatKey).on('child_added', (snap) => {
        const message = snap.val();
        if (message.isSeen === 'false') {
            count += 1;
        }
       
        updateCountUI(sender, count);
        updateTotalUnseenMessages();    
    });

    // Define the child_changed listener
    const childChanged = db.ref('messeges/' + chatKey).on('child_changed', (snap) => {
        const message = snap.val();
        if (message.isSeen === 'true' && count > 0) {
            count -= 1; // Decrease for each message that is seen
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

// Handle marking all unseen messages as seen when a chat is clicked
// function handleChatClick(sender, receiver) {
//     const chatKey = `${receiver}_${sender}`;

//     // Get all unseen messages in the selected chat and update count
//     const chatMessagesRef = db.ref('messeges/' + chatKey);
//     chatMessagesRef.once('value', (snapshot) => {
//         let unseenMessagesCount = 0;
        
//         snapshot.forEach((childSnapshot) => {
//             const message = childSnapshot.val();
//             if (message.isSeen === 'false') {
//                 unseenMessagesCount += 1;
//                 // Mark the message as seen in the database
//                 db.ref('messeges/' + chatKey + '/' + childSnapshot.key).update({
//                     isSeen: 'true'
//                 });
//             }
//         });

//         // Immediately set count to 0 since all messages are now seen
//         const countElement = $("#" + receiver + 'count');
//         updateCountUI(sender, 0);  // Force count to 0 after setting all as seen
//         updateTotalUnseenMessages();
//     });
// }

// Update the count UI for each chat
function updateCountUI(sender, count) {
    const countElement = $("#" + sender + 'count');
    if (count > 0) {
        countElement.text(count);
        countElement.removeClass('d-none');
    } else {
        countElement.text('0');
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

let isClearingInProgress = false;

$('#chatClear').off('click').on('click', function() {
    if (isClearingInProgress) return; // Exit if already in progress

    isClearingInProgress = true; // Set flag to true

    $('input[name="search"]').val('');
    clearData().finally(() => {
        isClearingInProgress = false; // Reset flag when done
    });
});
// $(document).on('keydown','#chatClear',function(){
//     console.log('here');    
//     alert('herer');
// })
function clearData() {
    let first = true;
    $('.chatlist').text(''); // Clear the chat list

    return dbRef.once("value")
        .then(snap => {
            snap.forEach(message => {
                let activeClass = '';
                if (sel_reciever != "" && message.key == sel_reciever) {
                    activeClass = 'activecht';
                } else {
                    activeClass = sel_reciever != "" ? '' : (first ? 'activecht' : '');
                }

                $('.chatlist').append(`
                    <li>
                        <div class="chat-list ${activeClass}"
                            data-receiverId="${message.key}"
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
                    </li>
                `);
                first = false;
            });
        })
        .then(() => getFirstChatData())
        .catch(error => {
            console.error("Error fetching data:", error);
        });
}

// search the user 
$("#searchmember input").on("keyup", function (e) {
    e.preventDefault();
    var search = $(this).val().toLowerCase(); // Normalize case to handle case-insensitive search
    $('.crossicon').removeClass('d-none')
    
    // If the search input is empty, show all items
    if (search === '') {
    $('.crossicon').addClass('d-none')
        $('.chatlist').find('li').removeClass('d-none');
    } else {
    $('.crossicon').removeClass('d-none')

        $('.chatlist').find('li').each(function () {
            var listItem = $(this);
            var listItemText = listItem.find('div:first').text().toLowerCase(); // Get the text and normalize case
            console.log(listItemText.indexOf(search));
            // Check if the list item contains the search text
            if (listItemText.indexOf(search) !== -1) {
                listItem.removeClass('d-none'); // Show item
            } else {
                listItem.addClass('d-none'); // Hide item
            }
        });
    }
});