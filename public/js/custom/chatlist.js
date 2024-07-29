var dbRef = db.ref(`/users/` + senderId);
dbRef.once("value").then(snap => {
    snap.forEach(message => {
        console.log(message.val())
        $('.chatlist').append(`<li>
                <div class="chat-list"
                    data-receiverId=${message.key}
                    data-senderId="${message.val().id}">
                    <div class="chat-profile-img-box">
                        <div class="chat-profile-img">
                            <img src="${message.val().image}">

                        </div>
                        <p>
                       ${message.val().name}
                        </p>
                    </div>
                </div>
            </li>`);

    });
}).catch(error => {
    console.log("error".error)
})

