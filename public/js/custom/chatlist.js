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
        });
    }).then(() => getFirstChatData())
        .catch(error => {
            console.log("error".error)
        })

});

function getFirstChatData() {
    element = $('.activecht');
    getMessages(element);
}