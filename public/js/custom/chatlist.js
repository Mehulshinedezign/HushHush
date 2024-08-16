var dbRef = db.ref(`/users/` + senderId);

let first = true;
let response = new Promise((resolve, reject) => {
    dbRef.once("value").then(snap => {

        snap.forEach(message => {

            // var test = unSeenMessages(message);
            // console.log(test, "fsdfsdfsdfsdfsf");
            // alert(test);
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
                        <p id="${message.key + 'count'}"></p>
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
    getMessages(element);
    userliststatus();
}






firebaselistner = null;
function userliststatus() {

    var elements = $('.chatlist').find('li');
    elements.each(function (index, element) {
        var list = $(element).find('div:first');

        var receiver = parseInt(list.attr('data-receiverid'));
        if (firebaselistner)
            firebaselistner();

        var firebaselistner = db.ref(`/OnlinePresence/${receiver}`).on('value', (snapshot) => {
            console.log(snapshot.val());
            let onlineClass = snapshot.val().status == 'online' ? 'online' : 'offline';
            let onlineRClass = snapshot.val().status == 'online' ? 'offline' : 'online';
            var pertcularPlace = '.chat-list[data-receiverid="' + snapshot.val().id + '"]';
            console.log(pertcularPlace);
            $(pertcularPlace).addClass(onlineClass);
            $(pertcularPlace).removeClass(onlineRClass);
        });
    })
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

